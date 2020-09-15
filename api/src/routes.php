<?php
use \Slim\Routing\RouteCollectorProxy;
use \App\Controller\LoginController;
use \App\Controller\AdminUserController;

$app->add(function ($request, $handler) {
  $response = $handler->handle($request);
  return $response
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
          ->withHeader('Access-Control-Expose-Headers', 'Access-Token');
});

$app->post('/login', LoginController::class . ':login');

$app->group('/admin', function (RouteCollectorProxy $adminGroup) {  
  $adminGroup->group('/users', function (RouteCollectorProxy $usersGroup) {
    $usersGroup->get('', AdminUserController::class . ':getUsers');
    $usersGroup->get('/{userId}', AdminUserController::class . ':getUser');  
    $usersGroup->put('/{userId}', AdminUserController::class . ':updateUser');
    $usersGroup->put('/{userId}/status', AdminUserController::class . ':updateUserStatus');
    $usersGroup->put('/{userId}/password', AdminUserController::class . ':updateUserPassword');
    $usersGroup->delete('/{userId}', AdminUserController::class . ':deleteUser');
    $usersGroup->post('', AdminUserController::class . ':addUser');
  });
})->add($admin)->add($logged);

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});
