<?php

session_start();

require_once "db.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (empty($_SESSION['cart'])) {
    header("Location: books.php");
    exit;
}

$cart = $_SESSION['cart'];

$total = 0;

$ids = array_keys($cart);

$safe_ids = implode(",", array_map('intval', $ids));

$sql = "SELECT * FROM books WHERE id IN ($safe_ids)";

$result = $conn->query($sql);

if ($result) {

    while ($book = $result->fetch_assoc()) {

        $id = (int)$book['id'];

        $quantity = (int)$cart[$id];

        $total += $book['price'] * $quantity;
    }
}


/* =========================
   PLACE ORDER
========================= */

$order_placed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name !== '' && $email !== '' && $address !== '') {

        $order_placed = true;

        /*
         * Clear cart after successful order
         */
        $_SESSION['cart'] = [];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php
        echo $order_placed
            ? "Order Confirmed | BookNest"
            : "Checkout | BookNest";
        ?>
    </title>

    <link rel="stylesheet" href="./style.css">

</head>

<body>


<header class="navbar">

    <div class="logo">
        📚 BookNest
    </div>

    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="books.php">
            Books
        </a>

        <a href="cart.php">
            🛒 Cart
        </a>

    </nav>

</header>


<main class="checkout-page">


<?php if ($order_placed) { ?>


    <!-- =========================
         THANK YOU SCREEN
    ========================== -->

    <section class="thank-you-card">

        <div class="success-icon">
            ✓
        </div>

        <p class="small-title">
            ORDER CONFIRMED
        </p>

        <h1>
            Thank you,
            <span><?php echo htmlspecialchars($name); ?>!</span>
        </h1>

        <p class="thank-you-message">

            Your order has been successfully placed. 📚

            <br>

            Your next chapter is on its way!

        </p>


        <div class="order-success-box">

            <div>
                <span>Order Status</span>

                <strong>
                    Confirmed ✓
                </strong>
            </div>


            <div>
                <span>Total Paid</span>

                <strong>
                    ₹<?php echo number_format($total, 2); ?>
                </strong>
            </div>

        </div>


        <p class="thank-you-note">

            We've received your order details.
            Thank you for choosing BookNest. ♥
            
        </p>


        <div class="success-buttons">

            <a
                href="books.php"
                class="btn-primary"
            >
                Continue Shopping →
            </a>

            <a
                href="index.php"
                class="btn-secondary"
            >
                Back to Home
            </a>

        </div>

    </section>


<?php } else { ?>


    <!-- =========================
         CHECKOUT FORM
    ========================== -->

    <div class="checkout-card">

        <div class="checkout-icon">
            ✨
        </div>

        <p class="small-title">
            BOOKNEST CHECKOUT
        </p>

        <h1>
            Your order is
            <span>almost ready.</span>
        </h1>

        <p>
            Total amount:
        </p>

        <div class="checkout-total">
            ₹<?php echo number_format($total, 2); ?>
        </div>


        <form
            method="POST"
            action="checkout.php"
        >

            <input
                type="text"
                name="name"
                placeholder="Your Name"
                required
            >


            <input
                type="email"
                name="email"
                placeholder="Email Address"
                required
            >


            <textarea
                name="address"
                placeholder="Delivery Address"
                required
            ></textarea>


            <button
                type="submit"
                class="checkout-btn"
            >
                Place Order ♥
            </button>

        </form>


        <a
            href="cart.php"
            class="continue-shopping"
        >
            ← Back to Cart
        </a>

    </div>


<?php } ?>


</main>


<footer>

    <div class="footer-logo">
        📚 BookNest
    </div>

    <p>
        Your cozy corner for books & stories.
    </p>

    <div class="footer-links">

        <a href="index.php">
            Home
        </a>

        <a href="books.php">
            Books
        </a>

        <a href="cart.php">
            Cart
        </a>

    </div>

    <p class="copyright">
        © <?php echo date("Y"); ?> BookNest.
        Made with ♥ for readers.
    </p>

</footer>


</body>
</html>