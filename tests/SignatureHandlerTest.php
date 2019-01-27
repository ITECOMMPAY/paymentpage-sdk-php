<?php

namespace ecommpay\tests;

use ecommpay\SignatureHandler;

class SignatureHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
    private $data = [
        'customer' => [
            'project_id' => 0,
            'id' => 'sutm_id',
        ],
        'card' => [
            'pan' => '4242424242424242',
            'year' => 2020,
            'month' => 8,
            'card_holder' => 'John Smith',
            'cvv' => '123',
            'save' => true,
        ]
    ];

    /**
     * @var string
     */
    private $signature = 'lY0LTSAzpR7zGce5qfYGacOuYlHGWqkMcQlqmjlsDDZI2gVcE1qVeWANnkIR7mdOqRXJnL1kO0lUmkQ0YYLWRg==';

    /**
     * @var SignatureHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->handler = new SignatureHandler('secret');
    }

    public function testSign()
    {
        self::assertEquals(
            $this->signature,
            $this->handler->sign($this->data)
        );
    }

    public function testDeepSign()
    {
        $data = [
            'customer' => [
                'address' => [
                    'position' => [
                        'geo' => [
                            'latitude' => 10,
                            'longitude' => 20,
                        ],
                        'relative' => '1 km away',
                    ],
                    'ZIP code' => 123312,
                ],
                'id' => 1,
            ],
            'payment' => 0,
        ];

        $expected = 'customer:address:position:;customer:address:zip code:123312;customer:id:1;payment:0';
        $actual = self::callProtectedMethod($this->handler, 'getParamsStamp', [$data]);

        self::assertEquals($expected, $actual);
    }

    public function testCheck()
    {
        self::assertTrue($this->handler->check($this->data, $this->signature));
    }

    private static function callProtectedMethod($obj, $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}
