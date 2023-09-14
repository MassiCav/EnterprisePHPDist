<?php

declare(strict_types=1);

use DAL\Domain\User\Entity\User;
use DAL\Domain\User\Entity\UserCollection;
use Mezzio\Hal\Metadata\MetadataMap;
use Mezzio\Hal\Metadata\RouteBasedCollectionMetadata;
use Mezzio\Hal\Metadata\RouteBasedResourceMetadata;
use Laminas\Hydrator\ObjectProperty as ObjectPropertyHydrator;

return [
    MetadataMap::class => [
        [
            '__class__' => RouteBasedResourceMetadata::class,
            'resource_class' => User::class,
            'route' => 'query.user',
            'extractor' => ObjectPropertyHydrator::class,
        ],
        [
            '__class__' => RouteBasedCollectionMetadata::class,
            'collection_class' => UserCollection::class,
            'collection_relation' => 'user',
            'route' => 'query.user',
        ],
    ]
];
