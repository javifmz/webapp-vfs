<?php
namespace App\Controller;
use Psr\Container\ContainerInterface;
use \App\Responder\JsonResponder;

class Controller {

  protected $container;
  protected $responder;

  public function __construct(ContainerInterface $container, JsonResponder $responder) {
    $this->container = $container;
    $this->responder = $responder;
  }

  public function __get($property) {
    if($this->container->{$property}) {
      return $this->container->{$property};
    }
  }

  public function getQueryParam($request, $name, $default=null) {
    $params = $request->getQueryParams();
    return isset($params[$name]) ? $params[$name] : $default;
  }
}
