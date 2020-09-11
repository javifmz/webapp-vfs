<?php
namespace App\Responder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

final class JsonResponder {

  private $responseFactory;
  
  public function __construct(ResponseFactoryInterface $responseFactory) {
    $this->responseFactory = $responseFactory;
  }

  public function render($data = null) : ResponseInterface {
    $json = json_encode($data);
    if ($json === false) {
      throw new UnexpectedValueException('Malformed UTF-8 characters, possibly incorrectly encoded.');
    }
    $response = $this->responseFactory->createResponse()->withHeader('Content-Type', 'application/json');
    $response->getBody()->write($json);
    return $response;
  }
}
