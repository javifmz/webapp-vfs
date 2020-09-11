<?php
namespace App\Middleware;
use Psr\Container\ContainerInterface;

class Middleware {

  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function __get($property) {
    if($this->container->{$property}) {
      return $this->container->{$property};
    }
  }
}
