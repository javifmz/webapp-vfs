<?php
namespace App\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AdminMiddleware extends Middleware {

  public function __invoke(Request $request, RequestHandler $handler): Response {
    $user = $request->getAttribute('user');
    if($user->admin) {
      $response = $handler->handle($request);
      return $response;
    }
    return $response->withStatus(403);
  }
}
