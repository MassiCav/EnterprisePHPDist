<?php declare(strict_types=1);

namespace DAL\Domain\User\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PreUpdate;

#[Entity, HasLifecycleCallbacks, Cache]
final class User
{
    #[Id, Column(type:'string', unique: true)]
    public readonly string $id;
    #[Column(type:'string')]
    public string $name;
    #[Column(type:'string', length: 4196)]
    public string $allUniqueNames;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->allUniqueNames = "$name";
    }

    #[PreUpdate]
    public function allUniqueNamesUpdate(): void
    {
        $vec = explode(',', $this->allUniqueNames);
        $vec[] = $this->name;
        $this->allUniqueNames = implode(',', array_unique($vec));
    }
}