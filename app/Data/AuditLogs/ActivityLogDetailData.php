<?php

declare(strict_types=1);

namespace App\Data\AuditLogs;

use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class ActivityLogDetailData extends Data
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function __construct(
        public int $id,
        public string $log_name,
        public string $description,
        public ?string $event,
        public ?string $subject_type,
        public ?string $subject_id,
        public ?string $causer_name,
        public ?string $causer_email,
        public array $properties,
        public ?string $created_at,
    ) {}

    public static function fromModel(Activity $activity): self
    {
        $causer = $activity->causer;

        return new self(
            id: (int) $activity->getKey(),
            log_name: (string) $activity->log_name,
            description: (string) $activity->description,
            event: $activity->event,
            subject_type: $activity->subject_type,
            subject_id: $activity->subject_id !== null ? (string) $activity->subject_id : null,
            causer_name: $causer?->name,
            causer_email: $causer?->email,
            properties: $activity->properties->all(),
            created_at: $activity->created_at instanceof Carbon ? $activity->created_at->toIso8601String() : null,
        );
    }
}
