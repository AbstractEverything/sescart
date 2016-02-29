# Cart manager for Laravel 5.2

Manage cart items using sessions.

## Install

Run `composer require abstracteverything/sescart` or add:

```
{
    "require": {
        "abstracteverything/sescart": "dev-master"
    }
}
```

to your composer.json file and run `composer update`.

Add `AbstractEverything\SesCart\ServiceProvider` to your providers array.

Add `AbstractEverything\SesCart\Facades\SesCart` to your facades array.

Export the config file using `php artisan vendor:publish`.

## Usage

```
// Add cart items:

$cart->add(123, 'Grapes', 12.50, 2, [
    'variety' => 'green',
    'type' => 'seedless',
]);

// Find cart item by its id:

$cart->find(123);

// Get all the items in the cart:

$cart->all();

// Calculate total price of all items:

$cart->subtotal(); // 25

// Remove an item by its id:

$cart->remove(123);

// Count the number of items in the cart:

$cart->count();

// Clear the cart session:

$cart->clear();
```