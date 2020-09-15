<?php
namespace App\Validation\Rules;

use App\Model\User;
use Respect\Validation\Rules\AbstractRule;

class UserEmailAvailable extends AbstractRule {

  protected $userId;

  public function __construct($userId) {
    $this->userId = $userId;
  }

  public function validate($input) {
    return $this->userId !== null ?
      User::where([['email', '=', $input], ['id', '<>', $this->userId]])->count() === 0 :
      User::where([['email', '=', $input]])->count() === 0;
  }
}
