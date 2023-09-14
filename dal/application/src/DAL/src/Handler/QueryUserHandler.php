<?php declare(strict_types=1);

namespace DAL\Handler;

use DAL\Domain\User\Query\UserQueryModel;
use Mezzio\Hal\ResourceGenerator;
use Mezzio\Hal\HalResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use DomainException;

class QueryUserHandler implements RequestHandlerInterface
{
    public function __construct(
        public readonly UserQueryModel $model,
        public readonly ResourceGenerator $rg,
        public readonly HalResponseFactory $hrf
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id', '');
        if (empty($id)) {
            $users = $this->model->findAll();
            $users->setItemCountPerPage(25);
            $users->setCurrentPageNumber(1);
            $resource = $this->rg->fromObject($users, $request);
            return $this->hrf->createResponse($request, $resource);
        }
        $user = $this->model->findById($id);
        if (empty($user)) throw new DomainException("No use found with id '{$id}'!");
        $resource = $this->rg->fromObject($user, $request);
        return $this->hrf->createResponse($request, $resource);
    }
}
