<?php declare(strict_types=1);

namespace DAL\Handler;

use DAL\Domain\User\Query\UserQueryModel;
use Mezzio\Hal\HalResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mezzio\Hal\ResourceGenerator;

class QueryUserHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $uqm = $container->get(UserQueryModel::class);
        $rg  = $container->get(ResourceGenerator::class);
        $hrf = $container->get(HalResponseFactory::class);
        return new QueryUserHandler($uqm, $rg, $hrf);
    }
}
