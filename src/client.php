<?php

declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
use App\Commands\CommandInvoker;
use App\Commands\PlaceOrderCommand;
use App\Commands\CancelOrderCommand;
use App\Commands\PayOrderCommand;
use App\Commands\ApplyDiscountCommand;
use App\Models\Product;
use App\Models\Discount;
use App\Models\OrderStore;
use App\Services\PlaceOrderService;
use App\Services\CancelOrderService;
use App\Services\PayOrderService;
use App\Services\ApplyDiscountService;
use App\Services\RestoreOrderService;

$products = [
    new Product(1, 'iPhone', 50000, 10),
    new Product(2, 'Laptop', 30000, 5),
    new Product(3, 'Headphones', 2000, 20),
];

$discounts = [
    new Discount('SAVE10', 'percentage', 10),
    new Discount('SAVE500', 'fixed', 500),
];

$orders = new OrderStore();
$invoker = new CommandInvoker();
$placeService = new PlaceOrderService($products, $discounts, $invoker);
$cancelService = new CancelOrderService($orders, $invoker);
$payService = new PayOrderService($orders, $invoker);
$discountService = new ApplyDiscountService($orders, $discounts, $invoker);
$restoreService = new RestoreOrderService($products);

while (true) {

    echo "\n========== ORDER SYSTEM ==========\n";
    echo "1. Show Products\n";
    echo "2. Place Order\n";
    echo "3. My Orders\n";
    echo "4. Apply Discount\n";
    echo "5. Pay Order\n";
    echo "6. Cancel Order\n";
    echo "7. Undo\n";
    echo "0. Exit\n";

    $choice = readline("\nChoose: ");

    try {

        switch ($choice) {

            case '1':

                foreach ($products as $product) {
                    echo "{$product->id} - {$product->name} - {$product->price} EGP - Stock: {$product->quantity}\n";
                }

                break;

            case '2':

                $email = readline("Enter Email: ");
                $productId = (int) readline("Product ID: ");
                $quantity = (int) readline("Quantity: ");

                $product = null;

                foreach ($products as $item) {
                    if ($item->id === $productId) {
                        $product = $item;
                        break;
                    }
                }

                if (!$product) {
                    echo "Product not found.\n";
                    break;
                }

                $command = new PlaceOrderCommand($orders, $product, $quantity);

                $placeService->execute([
                    'email' => $email,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ], $command);

                echo "Order created! ID: {$command->getCreatedOrder()->id}\n";

                break;

            case '3':

                if (empty($orders->all())) {
                    echo "No orders found.\n";
                    break;
                }

                foreach ($orders->all() as $order) {

                    echo "\nOrder #{$order->id}\n";
                    echo "Product: {$order->product->name}\n";
                    echo "Quantity: {$order->quantity}\n";
                    echo "Total: {$order->total} EGP\n";
                    echo "Status: {$order->status}\n";
                    echo "Payment: " . ($order->isPaid ? 'Paid' : 'Unpaid') . "\n";
                    echo "Discount: " . ($order->discount?->code ?? 'None') . "\n";
                    echo "-------------------------\n";
                }

                break;

            case '4':

                $email = readline("Enter Email: ");
                $orderId = (int) readline("Order ID: ");
                $code = readline("Discount Code: ");

                $discount = null;

                foreach ($discounts as $item) {
                    if ($item->code === $code) {
                        $discount = $item;
                        break;
                    }
                }

                if (!$discount) {
                    echo "Discount not found.\n";
                    break;
                }

                $command = new ApplyDiscountCommand(
                    $orders->find($orderId),
                    $discount
                );

                $discountService->execute([
                    'email' => $email,
                    'order_id' => $orderId,
                    'discount_code' => $code,
                    'action' => 'apply_discount',
                ], $command);

                echo "Discount applied successfully!\n";

                break;

            case '5':

                $email = readline("Enter Email: ");
                $orderId = (int) readline("Order ID: ");
                $card = readline("Visa Number: ");

                $command = new PayOrderCommand($orders->find($orderId));

                $payService->execute([
                    'email' => $email,
                    'order_id' => $orderId,
                    'card_number' => $card,
                ], $command);

                echo "Payment successful!\n";

                break;

            case '6':

                $email = readline("Enter Email: ");
                $orderId = (int) readline("Order ID: ");

                $command = new CancelOrderCommand(
                    $orders->find($orderId),
                    $restoreService
                );

                $cancelService->execute([
                    'email' => $email,
                    'order_id' => $orderId,
                ], $command);

                echo "Order cancelled!\n";

                break;

            case '7':

                $invoker->undo();

                echo "Undo successful!\n";

                break;

            case '0':

                exit("Goodbye!\n");

            default:

                echo "Invalid choice.\n";
        }

    } catch (Throwable $e) {
        echo "Error: {$e->getMessage()}\n";
    }
}