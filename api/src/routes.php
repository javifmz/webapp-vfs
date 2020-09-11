<?php
use \App\Controller\LoginController;

$app->add(function ($request, $handler) {
  $response = $handler->handle($request);
  return $response
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
          ->withHeader('Access-Control-Expose-Headers', 'Access-Token');
});

$app->post('/login', LoginController::class . ':login');

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});
