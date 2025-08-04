<?php
include 'header.php';

// Initialize message variables
$message = '';
$message_type = '';

// Handle adding or updating items in the cart
if (isset($_POST['add_to_cart']) || isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price']; // Cast price to float
    $qty = (int) $_POST['qty']; // Cast quantity to integer

    // Check if the cart session exists, if not, create one
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product already exists in the cart, update the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['qty'] = $qty; // Update quantity
        $message = "Product quantity updated in the cart.";
        $message_type = 'success';
    } else {
        // Add the new product to the cart
        $_SESSION['cart'][$product_id] = [
            'product_name' => $product_name,
            'price' => $price,
            'qty' => $qty
        ];
        $message = "Product added to the cart.";
        $message_type = 'success';
    }
}

// Handle removal of items from the cart
if (isset($_GET['remove']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    $message = "Product removed from the cart.";
    $message_type = 'danger';
}

// Calculate total
$cart_total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        $cart_total += (float) $product['price'] * (int) $product['qty']; // Ensure both are numeric
    }
}
?>

<style>
.enhanced-cart-section {
    background: linear-gradient(135deg, #fff 80%, #f8e1ff 100%);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(178,51,114,0.1);
    padding: 40px;
    margin-bottom: 30px;
}
.enhanced-cart-table {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: 1px solid #f0f0f0;
}
.enhanced-cart-table th {
    background: linear-gradient(90deg, #B23372 0%, rgba(178, 51, 115, 0.69) 100%);
    color: #fff;
    padding: 20px 15px;
    font-weight: 600;
    text-align: center;
    border: none;
}
.enhanced-cart-table td {
    padding: 20px 15px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}
.enhanced-cart-table tr:hover {
    background-color: #f8f9fa;
}
.enhanced-quantity-input {
    width: 80px !important;
    padding: 8px 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
}
.enhanced-quantity-input:focus {
    border-color: #B23372;
    box-shadow: 0 0 0 3px rgba(178,51,114,0.1);
    outline: none;
}
.enhanced-update-btn {
    background: #B23372;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    margin-left: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.enhanced-update-btn:hover {
    background: #7c3aed;
    transform: translateY(-1px);
}
.enhanced-remove-btn {
    color: #dc3545;
    font-size: 1.5rem;
    transition: all 0.3s ease;
    text-decoration: none;
}
.enhanced-remove-btn:hover {
    color: #c82333;
    transform: scale(1.1);
}
.enhanced-summary-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 30px;
    border: 1px solid #f0f0f0;
}
.enhanced-summary-table {
    width: 100%;
    border-collapse: collapse;
}
.enhanced-summary-table td {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 1.1rem;
}
.enhanced-summary-table tr:last-child td {
    border-bottom: none;
    font-weight: 700;
    font-size: 1.3rem;
    color: #B23372;
}
.enhanced-payment-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fff;
}
.enhanced-payment-select:focus {
    border-color: #B23372;
    box-shadow: 0 0 0 3px rgba(178,51,114,0.1);
    outline: none;
}
.enhanced-btn {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}
.enhanced-btn-primary {
    background: linear-gradient(90deg, #B23372 0%, rgba(178, 51, 115, 0.77) 100%);
    color: #fff;
}
.enhanced-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(178,51,114,0.3);
    color: #fff;
}
.enhanced-btn-secondary {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #e9ecef;
}
.enhanced-btn-secondary:hover {
    background: #e9ecef;
    color: #495057;
}
.enhanced-alert {
    background: linear-gradient(90deg, #d4edda 0%, #c3e6cb 100%);
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    font-weight: 500;
}
.enhanced-alert-danger {
    background: linear-gradient(90deg, #f8d7da 0%, #f5c6cb 100%);
    border: 1px solid #f5c6cb;
    color: #721c24;
}
.enhanced-cart-title {
    color: #B23372;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
    text-align: center;
}
.enhanced-cart-subtitle {
    color: #6b7280;
    text-align: center;
    margin-bottom: 40px;
    font-size: 1.1rem;
}
.empty-cart-message {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
    font-size: 1.2rem;
}
.empty-cart-message i {
    font-size: 4rem;
    color: #B23372;
    margin-bottom: 20px;
    display: block;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .enhanced-cart-section {
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 0;
        box-shadow: none;
        background: #fff;
    }
    
    .enhanced-cart-title {
        font-size: 1.5rem;
    }
    
    .enhanced-cart-subtitle {
        font-size: 1rem;
        margin-bottom: 30px;
    }
    
    .enhanced-cart-table {
        border-radius: 0;
        box-shadow: none;
        border: 1px solid #e0e0e0;
    }
    
    .enhanced-cart-table th,
    .enhanced-cart-table td {
        padding: 12px 8px;
        font-size: 0.9rem;
    }
    
    .enhanced-cart-table th i {
        display: none;
    }
    
    .enhanced-quantity-input {
        width: 60px !important;
        padding: 6px 8px;
        font-size: 0.9rem;
    }
    
    .enhanced-update-btn {
        padding: 6px 8px;
        margin-left: 4px;
    }
    
    .enhanced-remove-btn {
        font-size: 1.2rem;
    }
    
    .enhanced-summary-card {
        padding: 20px;
        border-radius: 0;
        box-shadow: none;
        border: 1px solid #e0e0e0;
        background: #f8f9fa;
    }
    
    .enhanced-summary-table td {
        padding: 10px 15px;
        font-size: 1rem;
    }
    
    .enhanced-summary-table tr:last-child td {
        font-size: 1.1rem;
    }
    
    .enhanced-btn {
        padding: 10px 20px;
        font-size: 0.9rem;
        width: 100%;
        margin-bottom: 10px;
        text-align: center;
        border-radius: 0;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
    }
    
    .d-flex.justify-content-between > div {
        width: 100%;
    }
    
    .empty-cart-message {
        padding: 40px 15px;
        font-size: 1rem;
    }
    
    .empty-cart-message i {
        font-size: 3rem;
    }
}

@media (max-width: 576px) {
    .enhanced-cart-section {
        padding: 15px;
        border-radius: 0;
        box-shadow: none;
        background: #fff;
    }
    
    .enhanced-cart-title {
        font-size: 1.3rem;
    }
    
    .enhanced-cart-table {
        font-size: 0.8rem;
        border-radius: 0;
        box-shadow: none;
    }
    
    .enhanced-cart-table th,
    .enhanced-cart-table td {
        padding: 8px 4px;
        font-size: 0.8rem;
    }
    
    .enhanced-quantity-input {
        width: 50px !important;
        padding: 4px 6px;
        font-size: 0.8rem;
        border-radius: 0;
    }
    
    .enhanced-update-btn {
        padding: 4px 6px;
        font-size: 0.8rem;
        border-radius: 0;
    }
    
    .enhanced-summary-card {
        padding: 15px;
        border-radius: 0;
        box-shadow: none;
        background: #f8f9fa;
    }
    
    .enhanced-summary-table td {
        padding: 8px 10px;
        font-size: 0.9rem;
    }
    
    .enhanced-summary-table tr:last-child td {
        font-size: 1rem;
    }
    
    .enhanced-btn {
        padding: 8px 16px;
        font-size: 0.85rem;
        border-radius: 0;
    }
    
    .enhanced-alert {
        padding: 12px 15px;
        font-size: 0.9rem;
        border-radius: 0;
    }
    
    .empty-cart-message {
        padding: 30px 10px;
        font-size: 0.9rem;
    }
    
    .empty-cart-message i {
        font-size: 2.5rem;
    }
    
    .enhanced-payment-select {
        border-radius: 0;
    }
}

/* Table responsive behavior */
@media (max-width: 768px) {
    .enhanced-cart-table {
        overflow-x: auto;
    }
    
    .enhanced-cart-table table {
        min-width: 600px;
    }
}

@media (max-width: 576px) {
    .enhanced-cart-table table {
        min-width: 500px;
    }
}
</style>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Shopping Cart</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Shopping Cart</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="shopping-cart-area pt-90 pb-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10" style="padding: 0 !important; ">
                <div class="enhanced-cart-section">
                    <h1 class="enhanced-cart-title">Shopping Cart</h1>
                    <p class="enhanced-cart-subtitle">Review your items and proceed to checkout</p>

                    <?php if ($message): ?>
                        <div class="enhanced-alert <?= $message_type === 'danger' ? 'enhanced-alert-danger' : '' ?>">
                            <i class="zmdi <?= $message_type === 'danger' ? 'zmdi-close-circle' : 'zmdi-check-circle' ?>" style="margin-right: 8px;"></i><?= $message ?>
                        </div>
                    <?php endif; ?>

                    <div class="enhanced-cart-table">
                        <table id="shopping-cart-table" class="data-table cart-table" style="width: 100%; margin: 0;">
                            <thead>
                                <tr>
                                    <th><i class="zmdi zmdi-shopping-cart" style="margin-right: 8px;"></i>Product Name</th>
                                    <th><i class="zmdi zmdi-format-list-numbered" style="margin-right: 8px;"></i>Quantity</th>
                                    <th><i class="zmdi zmdi-money" style="margin-right: 8px;"></i>Unit Price</th>
                                    <th><i class="zmdi zmdi-calculator" style="margin-right: 8px;"></i>Total</th>
                                    <th><i class="zmdi zmdi-delete" style="margin-right: 8px;"></i>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                                    <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
                                        <tr>
                                            <td class="sop-cart">
                                                <i class="zmdi zmdi-star" style="color: #B23372; margin-right: 8px;"></i>
                                                <?php echo $product['product_name']; ?>
                                            </td>
                                            <td class="cen">
                                                <form action="cart.php" method="post" style="display: flex; align-items: center; justify-content: center;">
                                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                    <input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>">
                                                    <input type="hidden" name="price" value="<?php echo number_format((float)$product['price'], 2); ?>">
                                                    <input type="number" name="qty" value="<?php echo $product['qty']; ?>" min="1" class="enhanced-quantity-input">
                                                    <button type="submit" name="update_cart" class="enhanced-update-btn">
                                                        <i class="zmdi zmdi-refresh"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="sop-cart">
                                                <i class="zmdi zmdi-money" style="color: #B23372; margin-right: 5px;"></i>
                                                NGN <?php echo number_format((float)$product['price'], 2); ?>
                                            </td>
                                            <td class="sop-cart">
                                                <strong>NGN <?php echo number_format((float)$product['price'] * (int)$product['qty'], 2); ?></strong>
                                            </td>
                                            <td class="sop-icon text-center">
                                                <a href="cart.php?remove=<?php echo $product_id; ?>" class="enhanced-remove-btn" onclick="return confirm('Are you sure you want to remove this item?')">
                                                    <i class="zmdi zmdi-close-circle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="empty-cart-message">
                                            <i class="zmdi zmdi-shopping-cart"></i>
                                            Your cart is empty. <br>
                                            <a href="products.php" class="enhanced-btn enhanced-btn-primary" style="margin-top: 15px;">
                                                <i class="zmdi zmdi-shopping-basket" style="color: #fff;"></i>Start Shopping
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                        <div class="enhanced-summary-card mt-4">
                            <h3 style="color: #B23372; margin-bottom: 25px; font-weight: 600; text-align: center;">
                                <i class="zmdi zmdi-receipt" style="margin-right: 8px;"></i>Order Summary
                            </h3>
                            <table class="enhanced-summary-table">
                                <tr>
                                    <td><i class="zmdi zmdi-money" style="color: #B23372; margin-right: 8px;"></i>Sub-Total:</td>
                                    <td class="text-end">NGN <?php echo number_format($cart_total, 2); ?></td>
                                </tr>
                                <tr>
                                    <td><i class="zmdi zmdi-local-shipping" style="color: #B23372; margin-right: 8px;"></i>Shipping Charge:</td>
                                    <td class="text-end">Free Shipping</td>
                                </tr>
                                <tr>
                                    <td><i class="zmdi zmdi-calculator" style="color: #B23372; margin-right: 8px;"></i>Total:</td>
                                    <td class="text-end">NGN <?php echo number_format($cart_total, 2); ?></td>
                                </tr>
                            </table>

                            <div class="mt-4">
                                <label style="font-weight: 600; color: #333; margin-bottom: 10px; display: block;">
                                    <i class="zmdi zmdi-credit-card" style="color: #B23372; margin-right: 8px;"></i>Select Payment Method:
                                </label>
                                <form action="checkout.php" method="POST">
                                    <select name="payment_method" class="enhanced-payment-select" required>
                                        <option value="online_payment">Online Transfer</option>
                                        <option value="cod">Cash on Delivery</option>
                                    </select>
                                    
                                    <div class="d-flex justify-content-between flex-wrap mt-4">
                                        <div class="mb-2">
                                            <a href="products.php" class="enhanced-btn enhanced-btn-secondary">
                                                <i class="zmdi zmdi-arrow-left" style="margin-right: 8px;"></i>Continue Shopping
                                            </a>
                                        </div>
                                        <div>
                                            <button type="submit" class="enhanced-btn enhanced-btn-primary">
                                                <i class="zmdi zmdi-shopping-cart-plus" style="margin-right: 8px;"></i>Proceed to Checkout
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>