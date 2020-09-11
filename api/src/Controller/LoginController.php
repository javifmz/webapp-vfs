<?php
namespace App\Controller;
use App\Model\User;

class LoginController extends Controller {

  public function login($request, $response, $args) {
    $params = $request->getParsedBody();
    $email = $params['email'];
    $password = $params['password'];
    $user = User::select('id', 'password', 'token')->where('email', '=', $email)->first();
    if($user != null && password_verify($password, $user->password)) {
      if($user->token === null) { // TODO: Date expiration
        $user->token = bin2hex(openssl_random_pseudo_bytes(64));
        while(User::where('token', '=', $user->token)->count() > 0) {
          $user->token = bin2hex(openssl_random_pseudo_bytes(64));
        }
      }
      $token = $user->token;
      $user->save();
      $user = User::find($user->id);
      $response = $this->responder->render($user);
      return $response->withHeader('Access-Token', $token);
    }
    return $response->withStatus(401);
  }
}
