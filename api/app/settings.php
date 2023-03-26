<?php

use DI\ContainerBuilder;

use Doctrine\DBAL\DriverManager;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        \Doctrine\DBAL\Connection::class => DriverManager::getConnection([
            'dbname' => getenv('MYSQL_DATABASE'),
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'host' => getenv('MYSQL_HOST'),
            'port' => getenv('MYSQL_PORT'),
            'driver' => 'pdo_mysql',
        ]),
    ]);
};
