<?php
namespace App\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \App\Model\User;

class LoggedMiddleware extends Middleware {

  public function __invoke(Request $request, RequestHandler $handler): Response {
    $authorization = $request->getHeader('HTTP_AUTHORIZATION');
    if(isset($authorization) && sizeof($authorization) > 0) {
      if (preg_match('/Bearer\s+(.*)$/i', $authorization[0], $auth)) {
        $token = $auth[1];
        $user = User::where('token', '=', $token)->first();
        if($user != null) {
          $response = $handler->handle($request->withAttribute('user', $user));
          return $response;
        }
      }
    }
    $response = new Response();
    return $response->withStatus(401);
  }
}
