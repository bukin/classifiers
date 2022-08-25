<?php

use InetStudio\ClassifiersPackage\Entries\Presentation\GraphQL\Queries\EntriesQuery;
use InetStudio\ClassifiersPackage\Groups\Presentation\GraphQL\Queries\GroupsQuery;

return [
    'classifiers-package' => [
        'query' => [
            EntriesQuery::class,
            GroupsQuery::class,
        ],
        'mutation' => [],
        'middleware' => ['web'],
        'method' => ['GET'],
    ],
];
