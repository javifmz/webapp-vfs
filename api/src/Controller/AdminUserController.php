<?php
namespace App\Controller;
use Respect\Validation\Validator;
use \App\Model\User;

class AdminUserController extends Controller {

  /* CRUD: List users */
  public function getUsers($request, $response, $args) {
    // Get the parameters
    $params = $request->getQueryParams();
    $from = $params['from'];
    $size = $params['size'];
    $admin = $params['admin'];
    $status = $params['status'];
    $string = $params['string'];
    // Create the queries
    $itemsQuery = User::query();
    $itemsQuery->where('status', '=', $status);
    if ($admin)  $itemsQuery->where('admin', 1);
    if ($string) $itemsQuery->whereRaw('name LIKE ?', ['%' . $string . '%']);
    $countQuery = clone $itemsQuery;
    // Return the items
    $teams = array(
      'items' => $itemsQuery->offset($from)->limit($size)->get(),
      'total' => $countQuery->count(),
    );
    return $this->responder->render($teams);
  }

  /* CRUD: Get user */
  public function getUser($request, $response, $args) {
    $userId = $args['userId'];
    $user = User::find($userId);
    return $this->responder->render($user);
  }
  
  /* CRUD: Add user */
  public function addUser($request, $response, $args) {
    $user = new User;
    return $this->saveUser($user, $request, $response, $args);
  }

  /* CRUD: Update user */
  public function updateUser($request, $response, $args) {
    $userId = $args['userId'];
    $user = User::find($userId);
    if($user) {
      return $this->saveUser($user, $request, $response, $args);
    } else {
      return $response->withStatus(400);
    }
  }

  /* CRUD: Update user status */
  public function updateUserStatus($request, $response, $args) {
    $userId = $args['userId'];
    $params = $request->getParsedBody();
    $status = $params['status'];
    $user = User::find($userId);
    if($user) {
      $user->status = $status;
      $user->save();
      return $this->responder->render($user);
    } else {
      return $response->withStatus(400);
    }
  }
  
  /* CRUD: Update user password */
  public function updateUserPassword($request, $response, $args) {
    $userId = $args['userId'];
    $user = User::find($userId);
    if($user) {
      return $this->saveUserPassword($user, $request, $response, $args);
    } else {
      return $response->withStatus(400);
    }
  }

  /* CRUD: Delete user */
  public function deleteUser($request, $response, $args) {
    $userId = $args['userId'];
    $user = User::find($userId);
    $user->delete();
    return $response;
  }

  private function saveUser($user, $request, $respose, $args) {
    $validation = $this->validate($request, $user->id);
    if(!$validation->failed()) {
      $this->fill($user, $request);
      $user->save();
      return $this->responder->render($user);
    } else {
      return $this->responder->render($validation->errors())->withStatus(400);
    }
  }

  private function saveUserPassword($user, $request, $response, $args) {
    $validation = $this->validatePassword($request, $user->id);
    if(!$validation->failed()) {
      $this->fillPassword($user, $request);
      $user->save();
      return $response->withStatus(200);
    } else {
      return $this->responder->render($validation->errors())->withStatus(400);
    }
  }
  
  private function validate($request, $userId) {
    return $this->container->get('validator')->validate($request, [
      'name' => Validator::stringType()->notEmpty(),
      'surname' => Validator::stringType()->notEmpty(),
      'email' => Validator::email()->notEmpty()->userEmailAvailable($userId),
      'admin' => Validator::intType()->in([0, 1]),
    ]);
  }

  private function validatePassword($request, $userId) {
    return $this->container->get('validator')->validate($request, [
      'password' => Validator::length(8, null)->alnum(),
    ]);
  }

  private function fill($user, $request) {
    $params = $request->getParsedBody();
    $user->name = trim($params['name']);
    $user->surname = trim($params['surname']);
    $user->email = trim($params['email']);
    $user->admin = trim($params['admin']);
  }

  private function fillPassword($user, $request) {
    $params = $request->getParsedBody();
    $user->password = password_hash(trim($params['password']), PASSWORD_DEFAULT);
  }
}
