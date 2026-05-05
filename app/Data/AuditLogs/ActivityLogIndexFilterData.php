<?php

declare(strict_types=1);

namespace App\Data\AuditLogs;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class ActivityLogIndexFilterData extends Data
{
    public function __construct(
        #[MapInputName('filter.search')]
        public string|Optional $search,
        #[MapInputName('filter.subject_type')]
        public string|Optional $subject_type,
        #[MapInputName('filter.user_filter')]
        public string|Optional $user_filter,
        #[MapInputName('filter.date_from')]
        public string|Optional $date_from,
        #[MapInputName('filter.date_to')]
        public string|Optional $date_to,
        public string|Optional $sort,
        public int $perPage = 25,
    ) {}
}
