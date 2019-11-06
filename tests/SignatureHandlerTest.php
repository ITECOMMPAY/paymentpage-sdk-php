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
        ],
        'frame_mode' => 'popup',
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

    public function testCheck()
    {
        self::assertTrue($this->handler->check($this->data, $this->signature));
    }
}
