<?php declare(strict_types=1);

namespace DAL\Domain\User\Query;

use DAL\Domain\User\Entity\User;
use DAL\Domain\User\Entity\UserCollection;
use Doctrine\ORM\EntityRepository;

final class UserQueryModel 
{
    public function __construct(public readonly EntityRepository $repo)
    {}

    public function findById(string $id) : User | null {
        return $this->repo->findOneBy(['id' => $id]);
    }

    public function findAll() : UserCollection  {
        return UserCollection::loadDataAsArray(
            $this->repo->findAll()
        );
    }
}