<?php
declare(strict_types=1);

namespace Lib;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class RpcRouter
 * @package Lib
 */
class RpcRouter
{

    /**
     * @var Di
     */
    private $di;

    /**
     * @var array
     */
    private $routes;

    /**
     * RpcRouter constructor.
     * @param Di $di
     * @param array $routes
     */
    public function __construct(Di $di, array $routes)
    {
        $this->di = $di;
        $this->routes = $routes;
    }

    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse($this->getResponse($request));
    }

    /**
     * @param $method
     * @return callable|null
     * @throws \DomainException
     */
    private function getController($method): ?callable
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        $controller = $this->routes[$method];
        return $this->di->get($controller);
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    private function getResponse(ServerRequestInterface $request): array
    {
        $body = $request->getParsedBody();

        $response = [
            'jsonrpc' => '2.0',
            'id' => null
        ];

        if (!\is_array($body)) {
            $response['error'] = [
                'code' => -32600,
                'message' => 'Invalid Request'
            ];

            return $response;
        }

        $response['id'] = $body['id'] ?? null;

        if (!isset($body['jsonrpc'], $body['method'], $body['params']) || $body['jsonrpc'] !== '2.0') {
            $response['error'] = [
                'code' => -32600,
                'message' => 'Invalid Request'
            ];

            return $response;
        }

        $controller = $this->getController($body['method']);
        if (!$controller) {
            $response['error'] = [
                'code' => -32601,
                'message' => 'Method not found'
            ];

            return $response;
        }

        try {
            $result = $controller($body['params']);
        } catch (\Throwable $e) {
            $response['error'] = [
                'code' => -32603,
                'message' => 'Internal error',
                'data' => $e->getMessage()
            ];

            return $response;
        }

        $response['result'] = $result;
        return $response;
    }
}
