<?php declare(strict_types=1);

namespace DAL\Domain\User\Entity;

use Laminas\Paginator\Adapter\ArrayAdapter;
use Laminas\Paginator\Paginator;

final class UserCollection extends Paginator
{
    public static function loadDataAsArray(array $data): self
    {
        return new self(
            new ArrayAdapter(
                $data
            )
        );
    }
}