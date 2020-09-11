<?php
namespace App\Validation;
use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator {

  protected $errors;

  public function validate($request, array $rules, array $rulesDep = []) {
    $params = $request->getParsedBody();
    foreach($rules as $field => $rule) {
      try {
        $rule->setName($field)->assert($params[$field]);
      } catch(NestedValidationException $e) {
        $this->errors[$field] = $e->getMessages();
      }
    }
    foreach($rulesDep as $field => $rule) {
      try {
        $rule->setName($field)->assert($params);
      } catch(NestedValidationException $e) {
        $this->errors[$field] = $e->getMessages();
      }
    }
    return $this;
  }

  public function failed() {
    return !empty($this->errors);
  }

  public function errors() {
    return $this->errors;
  }
}
