<?php

declare(strict_types=1);

namespace App\Data\Users;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class UserIndexFilterData extends Data
{
    public function __construct(
        #[MapInputName('filter.search')]
        public string|Optional $search,
        #[MapInputName('filter.role')]
        public string|Optional $role,
        #[MapInputName('filter.is_active')]
        public string|Optional $is_active,
        public string|Optional $sort,
        public int $perPage = 25,
    ) {}
}
