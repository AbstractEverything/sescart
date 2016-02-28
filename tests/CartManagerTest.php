<?php

use AbstractEverything\SesCart\Cart\CartManager;
use Illuminate\Session\SessionManager;
use Orchestra\Testbench\TestCase;

class CartManagerTest extends TestCase
{
    protected function getEnvironmentSetup($app)
    {
        $app['config']->set('sescart.session_name', '_cart');
    }

    protected function getPackageProviders($app)
    {
        return ['AbstractEverything\SesCart\SesCartServiceProvider'];
    }

    public function testItCanAddANewItem()
    {
        $sessionManager = Mockery::mock(SessionManager::class);
        $sessionManager->shouldReceive('get')->once()->andReturn([]);
        $sessionManager->shouldReceive('push')->once();

        $cart = new CartManager($sessionManager);
        $item = $cart->add(123, 'Test item', 11.50, 1);

        $this->assertEquals('Test item', $item['name']);
    }

    public function testItIncrementsQuantityWhenASecondItemIsAdded()
    {
        $sessionManager = Mockery::mock(SessionManager::class);
        $sessionManager->shouldReceive('get')->once()->andReturn([
            [
                'id' => 123,
                'name' => 'Test 1',
                'price' => 4,
                'quantity' => 2,
            ]
        ]);
        $sessionManager->shouldReceive('set')->once();

        $cart = new CartManager($sessionManager);
        $item = $cart->add(123, 'Test item', 11.50, 1);

        $this->assertEquals(3, $item['quantity']);
    }

    public function testItCalculatesPrices()
    {
        $sessionManager = Mockery::mock(SessionManager::class);
        $sessionManager->shouldReceive('get')->once()->andReturn([
            [
                'id' => 1,
                'name' => 'Test 1',
                'price' => 4,
                'quantity' => 2,
            ],
            [
                'id' => 2,
                'name' => 'Test 2',
                'price' => 3.50,
                'quantity' => 1,
            ],
        ]);

        $cart = new CartManager($sessionManager);
        $this->assertEquals(11.50, $cart->subtotal());
    }

    public function testItFindsById()
    {
        $sessionManager = Mockery::mock(SessionManager::class);
        $sessionManager->shouldReceive('get')->once()->andReturn([
            [
                'id' => 1,
                'name' => 'Test 1',
                'price' => 4,
                'quantity' => 2,
            ],
            [
                'id' => 2,
                'name' => 'Test 2',
                'price' => 3.50,
                'quantity' => 1,
            ],
        ]);

        $cart = new CartManager($sessionManager);
        $this->assertEquals('Test 2', $cart->find(2)['name']);
    }

    public function testItCountsItems()
    {
        $sessionManager = Mockery::mock(SessionManager::class);
        $sessionManager->shouldReceive('get')->once()->andReturn([
            [
                'id' => 1,
                'name' => 'Test 1',
                'price' => 4,
                'quantity' => 2,
            ],
            [
                'id' => 2,
                'name' => 'Test 2',
                'price' => 3.50,
                'quantity' => 1,
            ],
        ]);

        $cart = new CartManager($sessionManager);
        $this->assertEquals(3, $cart->count());
    }

    public function testItRemovesItems()
    {
        $sessionManager = Mockery::mock(SessionManager::class);
        $sessionManager->shouldReceive('get')->once()->andReturn([
            [
                'id' => 22,
                'name' => 'Test 1',
                'price' => 4,
                'quantity' => 2,
            ],
            [
                'id' => 33,
                'name' => 'Test 2',
                'price' => 3.50,
                'quantity' => 1,
            ],
        ]);
        $sessionManager->shouldReceive('forget')->with(1)->once();

        $cart = new CartManager($sessionManager);
        $cart->remove(33);
    }
}