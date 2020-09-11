<?php
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\App;
use \App\Validation\Validator;

return [

  App::class => static function (ContainerInterface $container) {
    AppFactory::setContainer($container);
    return AppFactory::create();
  },

  ResponseFactoryInterface::class => static function (ContainerInterface $container) {
    $app = $container->get(App::class);
    return $app->getResponseFactory();
  },

  'database' => [
    'driver'    => getenv('DB_DRIVER')    ?: 'mysql',
    'host'      => getenv('DB_HOST')      ?: '127.0.0.1',
    'port'      => getenv('DB_PORT')      ?: '3306',
    'charset'   => getenv('DB_CHARSET')   ?: 'utf8',
    'collation' => getenv('DB_COLLATION') ?: 'utf8_unicode_ci',
    'prefix'    => getenv('DB_PREFIX')    ?: '',
    'database'  => getenv('DB_DATABASE'),
    'username'  => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD')
  ],

  'static' => __DIR__ . '/../static/',
  'migrations' => __DIR__ . '/../migrations/',
  'validator' => new Validator,
];
