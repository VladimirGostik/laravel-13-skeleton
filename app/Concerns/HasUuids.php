<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Database\Eloquent\Concerns\HasUuids as BaseHasUuids;
use Symfony\Component\Uid\Uuid;

trait HasUuids
{
    use BaseHasUuids;

    public function newUniqueId(): string
    {
        return (string) Uuid::v7();
    }

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return [$this->getKeyName()];
    }
}
