<?php

namespace AbstractEverything\SesCart\Cart;

use Illuminate\Session\SessionManager;

class CartManager
{
    /**
     * Session manager
     * @var Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * Constructor
     * @param Product        $product
     * @param SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Add a product to the cart, if the product id already exists then increment its quantity
     * @param integer  $id
     * @param string  $name
     * @param integer  $price
     * @param integer $quantity
     * @param array   $options
     * @return array
     */
    public function add($id, $name = '', $price = 0, $quantity = 1, array $options = [])
    {
        $cartItems = $this->getCartSession();

        foreach ($cartItems as &$cartItem)
        {
            if ($cartItem['id'] == $id)
            {
                $cartItem['quantity'] = $cartItem['quantity'] + $quantity;
                $this->session->set(config('sescart.session_name'), $cartItems);

                return $cartItem;
            }
        }

        $newItem = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'options' => $options,
        ];

        $this->session->push(config('sescart.session_name'), $newItem);

        return $newItem;
    }

    /**
     * Find a cart item by its id
     * @param  integer $id
     * @return array
     */
    public function find($id)
    {
        $cartItems = $this->getCartSession();

        foreach ($cartItems as $cartItem)
        {
            if ($cartItem['id'] == $id)
            {
                return $cartItem;
            }
        }
    }

    /**
     * Show all cart sessions
     * @return array
     */
    public function all()
    {
        return $this->getCartSession();
    }

    /**
     * Remove a product with id from the cart
     * @param  integer $id
     * @return boolean
     */
    public function remove($id)
    {
        $cartItems = $this->getCartSession();

        foreach ($cartItems as $key => $cartItem)
        {
            if ($cartItem['id'] == $id)
            {
                return $this->session->forget($key);
            }
        }
    }

    /**
     * Clear all the cart session data
     * @return boolean
     */
    public function clear()
    {
        return $this->session->forget(config('sescart.session_name'));
    }

    /**
     * Calculate the subtotal of all the items in the cart
     * @return integer
     */
    public function subtotal()
    {
        $cartItems = $this->getCartSession();
        $subtotal = 0;

        foreach ($cartItems as $cartItem)
        {
            $subtotal += ($cartItem['price'] * $cartItem['quantity']);
        }

        return $subtotal;
    }

    /**
     * Count the number of items in the cart
     * @return integer
     */
    public function count()
    {
        $cartItems = $this->getCartSession();
        $count = 0;

        foreach ($cartItems as $cartItem)
        {
            $count += $cartItem['quantity'];
        }

        return $count;
    }

    /**
     * Get the current cart session
     * @return array
     */
    public function getCartSession()
    {
        return $this->session->get(config('sescart.session_name'), []);
    }
}