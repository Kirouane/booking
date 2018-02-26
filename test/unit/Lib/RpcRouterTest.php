<?php
declare(strict_types=1);
namespace Lib;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RpcRouterTest
 * @package Lib
 */
class RpcRouterTest extends TestCase
{
    public function invokeInvalidRequestProvider()
    {
        return [
            [null],
            [false],
            [''],
            ['string'],
            [new \stdClass()],
            [1],
            [
                'jsonrpc' => '1.0'
            ],
            [
                'jsonrpc' => '2.0'
            ],
            [
                'jsonrpc' => '2.0',
                'method' => 'fake.method'
            ],
            [
                'jsonrpc' => '2.0',
                'method' => 'fake.method',
                'params' => null
            ],
        ];
    }

    /**
     * @test
     * @dataProvider invokeInvalidRequestProvider
     */
    public function invokeInvalidRequest($body)
    {
        $di = \Mockery::mock(Di::class);

        $request = \Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getParsedBody')->andReturn($body);

        $router = new \Lib\RpcRouter($di, []);
        $response = $router($request)->getPayload();
        self::assertSame(
            [
                'jsonrpc' => '2.0',
                'id' => null,
                'error' => [
                    'code' => -32600,
                    'message' => 'Invalid Request'
                ]
            ],
            $response
        );
    }

    /**
     * @test
     */
    public function invokeMethodNotFound()
    {
        $di = \Mockery::mock(Di::class);

        $request = \Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getParsedBody')->andReturn(
            [
                'jsonrpc' => '2.0',
                'method' => 'fake.method',
                'params' => []
            ]
        );

        $router = new \Lib\RpcRouter($di, []);
        $response = $router($request)->getPayload();
        self::assertSame(
            [
                'jsonrpc' => '2.0',
                'id' => null,
                'error' => [
                    'code' => -32601,
                    'message' => 'Method not found'
                ]
            ],
            $response
        );
    }

    /**
     * @test
     */
    public function invoke()
    {
        $di = \Mockery::mock(Di::class);
        $di->shouldReceive('get')->andReturn(function () {
            return ['okay'];
        });

        $request = \Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getParsedBody')->andReturn(
            [
                'jsonrpc' => '2.0',
                'method' => 'method',
                'id' => 'test',
                'params' => []
            ]
        );

        $router = new \Lib\RpcRouter($di, [
            'method' => 'TestClass'
        ]);
        $response = $router($request)->getPayload();
        self::assertSame(
            [
                'jsonrpc' => '2.0',
                'id' => 'test',
                'result' => ['okay']
            ],
            $response
        );
    }

    /**
     * @test
     */
    public function invokeException()
    {
        $di = \Mockery::mock(Di::class);
        $di->shouldReceive('get')->andReturn(function () {
            throw new \Exception('Unexpected exception');
        });

        $request = \Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getParsedBody')->andReturn(
            [
                'jsonrpc' => '2.0',
                'method' => 'method',
                'id' => 'test',
                'params' => []
            ]
        );

        $router = new \Lib\RpcRouter($di, [
            'method' => 'TestClass'
        ]);
        $response = $router($request)->getPayload();
        self::assertSame(
            [
                'jsonrpc' => '2.0',
                'id' => 'test',
                'error' => [
                    'code' => -32603,
                    'message' => 'Internal error',
                    'data' => 'Unexpected exception'
                ]
            ],
            $response
        );
    }

    /**
     * @test
     */
    public function invokeFatal()
    {
        $di = \Mockery::mock(Di::class);
        $di->shouldReceive('get')->andReturn(function () {
            $a = new \stdClass();
            $a->test();
        });

        $request = \Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getParsedBody')->andReturn(
            [
                'jsonrpc' => '2.0',
                'method' => 'method',
                'id' => 'test',
                'params' => []
            ]
        );

        $router = new \Lib\RpcRouter($di, [
            'method' => 'TestClass'
        ]);
        $response = $router($request)->getPayload();
        self::assertSame(
            [
                'jsonrpc' => '2.0',
                'id' => 'test',
                'error' => [
                    'code' => -32603,
                    'message' => 'Internal error',
                    'data' => 'Call to undefined method stdClass::test()'
                ]
            ],
            $response
        );
    }
}
