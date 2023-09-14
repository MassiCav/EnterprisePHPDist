<?php declare(strict_types=1);

namespace DAL\Domain\User\Command\Command;

use DAL\Commanding\Domain\Command;
use DAL\Commanding\Infrastructure\Exception\InvalidEventInjectedInCommand;
use DAL\Domain\User\Entity\User;
use DAL\Domain\User\Event\UserAddNameEvent;
use DAL\EventSourcing\Domain\DomainEvent;
use OpsWay\Doctrine\ORM\Swoole\EntityManager;

final class UserAddName implements Command 
{
    private static string $repoIdentifier = User::class;
    private UserAddNameEvent $event;

    public function __construct(public readonly EntityManager $em)
    {}

    public function setEvent(DomainEvent $e): Command
    {
        if (!$e instanceof UserAddNameEvent)
            throw InvalidEventInjectedInCommand::fromIjectedEvenAndCommand($e, $this);
        $this->event = $e;
        return $this;
    }

    public function execute(): void
    {
        /** @var User $user */
        $user = $this->em->getRepository(self::$repoIdentifier)->findOneBy(['id' => $this->event->data->id]);
        if (empty($user)) {
            $user = new User($this->event->data->id, $this->event->data->name);
            $this->em->persist($user);
            $this->em->flush();
            return;
        } 
        $user->name = $this->event->data->name;
        $this->em->persist($user);
        $this->em->flush();
    }
}