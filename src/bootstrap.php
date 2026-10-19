<?php

declare(strict_types=1);

require_once __DIR__ . '/../Commands/IOrderCommand.php';
require_once __DIR__ . '/../Commands/IUndoableCommand.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Discount.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/OrderStore.php';
require_once __DIR__ . '/../Exceptions/ValidationFailedException.php';
require_once __DIR__ . '/../Handlers/AbstractHandler.php';
require_once __DIR__ . '/../Handlers/EmailValidationHandler.php';
require_once __DIR__ . '/../Handlers/ProductValidationHandler.php';
require_once __DIR__ . '/../Handlers/StockValidationHandler.php';
require_once __DIR__ . '/../Handlers/DiscountValidationHandler.php';
require_once __DIR__ . '/../Handlers/PaymentValidationHandler.php';
require_once __DIR__ . '/../Handlers/OrderStatusValidationHandler.php';
require_once __DIR__ . '/../Handlers/DiscountOrderStatusHandler.php';
require_once __DIR__ . '/../Handlers/NoExistingDiscountHandler.php';
require_once __DIR__ . '/../Handlers/PayOrderStatusHandler.php';
require_once __DIR__ . '/../Services/RestoreOrderService.php';
require_once __DIR__ . '/../Commands/PlaceOrderCommand.php';
require_once __DIR__ . '/../Commands/CancelOrderCommand.php';
require_once __DIR__ . '/../Commands/ApplyDiscountCommand.php';
require_once __DIR__ . '/../Commands/PayOrderCommand.php';
require_once __DIR__ . '/../Commands/CommandInvoker.php';
require_once __DIR__ . '/../Services/PlaceOrderService.php';
require_once __DIR__ . '/../Services/CancelOrderService.php';
require_once __DIR__ . '/../Services/PayOrderService.php';
require_once __DIR__ . '/../Services/ApplyDiscountService.php';
require_once __DIR__ . '/../Services/Logger.php';

