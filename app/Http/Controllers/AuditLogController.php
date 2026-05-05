<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\AuditLogs\ActivityLogDetailData;
use App\Data\AuditLogs\ActivityLogIndexFilterData;
use App\Data\AuditLogs\ActivityLogListItemData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class AuditLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    public function index(ActivityLogIndexFilterData $filters): Response
    {
        $this->authorize();

        $op = config('database.default') === 'pgsql' ? 'ilike' : 'like';

        $logs = QueryBuilder::for(Activity::query()->with('causer'))
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $q, $value) use ($op): void {
                    $term = '%' . $value . '%';
                    $q->where(function (Builder $sub) use ($op, $term): void {
                        $sub->where('description', $op, $term)
                            ->orWhere('log_name', $op, $term);
                    });
                }),
                AllowedFilter::exact('subject_type'),
                AllowedFilter::exact('user_filter', 'causer_id'),
                AllowedFilter::callback('date_from', fn (Builder $q, $v) => $q->where('created_at', '>=', $v)),
                AllowedFilter::callback('date_to', fn (Builder $q, $v) => $q->where('created_at', '<=', $v)),
            ])
            ->allowedSorts(['created_at', 'description'])
            ->defaultSort('-created_at')
            ->paginate($filters->perPage)
            ->withQueryString()
            ->through(fn (Activity $a) => ActivityLogListItemData::fromModel($a));

        return Inertia::render('AuditLogs/Index', [
            'logs' => $logs,
            'filters' => $filters,
        ]);
    }

    public function show(Activity $activity): Response
    {
        $this->authorize();

        return Inertia::render('AuditLogs/Show', [
            'log' => ActivityLogDetailData::fromModel($activity->load('causer')),
        ]);
    }

    private function authorize(): void
    {
        $user = auth()->user();
        if ($user === null || ! $user->can('view audit logs')) {
            throw new AuthorizationException;
        }
    }
}
