<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'useSlimErrors'       => true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'db' => [
                    'default' => 'cuidabem',
                    'cuidabem' => [
                        'driver'    => 'mysql',
                        'host'      => 'cuidabe-api.ddns.net',
                        'database'  => 'cuidabem',
                        'username'  => 'cuidabem',
                        'password'  => 'e6Fv920j%',
                        'charset'   => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix'    => '',
                    ],
                ],
                'jwt' => [
                    'key' => 'Ajakep**#jj983'
                ],
            ]);
        }
    ]);
};
