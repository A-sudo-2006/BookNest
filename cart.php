<?php

session_start();

require_once "db.php";


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


$cart = $_SESSION['cart'];

$cart_items = [];

$total = 0;


if (!empty($cart)) {

    $ids = array_keys($cart);

    $safe_ids = implode(
        ",",
        array_map('intval', $ids)
    );

    $sql = "SELECT * FROM books WHERE id IN ($safe_ids)";

    $result = $conn->query($sql);


    if ($result) {

        while ($book = $result->fetch_assoc()) {

            $id = (int) $book['id'];

            $quantity = (int) $cart[$id];

            $subtotal = $book['price'] * $quantity;

            $total += $subtotal;

            $cart_items[] = [
                'id' => $id,
                'title' => $book['title'],
                'author' => $book['author'],
                'price' => $book['price'],
                'image' => $book['image'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}


$cart_count = 0;

foreach ($cart as $quantity) {
    $cart_count += $quantity;
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

    <title>Your Cart | BookNest</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>

<header class="navbar">

    <div class="logo">
        <span>📚</span> BookNest
    </div>

    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="books.php">
            Books
        </a>

        <a
            href="cart.php"
            class="cart-link active"
        >
            🛒 Cart

            <span class="cart-badge">
                <?php echo $cart_count; ?>
            </span>

        </a>

    </nav>

</header>


<main class="cart-page">

    <section class="page-header">

        <p class="small-title">
            YOUR BOOKNEST
        </p>

        <h1>
            Your <span>reading basket.</span>
        </h1>

        <p>
            Books you've chosen for your next chapter.
        </p>

    </section>


    <?php if (empty($cart_items)) { ?>

        <section class="empty-cart">

            <div class="empty-cart-icon">
                🛒
            </div>

            <h2>
                Your cart is empty
            </h2>

            <p>
                Looks like you haven't found your next
                favourite book yet.
            </p>

            <a
                href="books.php"
                class="btn-primary"
            >
                Browse Books →
            </a>

        </section>


    <?php } else { ?>


        <section class="cart-layout">


            <div class="cart-items">


                <?php foreach ($cart_items as $item) { ?>

                    <div class="cart-item">


                        <div class="cart-book-cover">

                            <?php if (!empty($item['image'])) { ?>

                                <img
                                    src="assets/images/<?php
                                    echo htmlspecialchars(
                                        $item['image']
                                    );
                                    ?>"
                                    alt="<?php
                                    echo htmlspecialchars(
                                        $item['title']
                                    );
                                    ?>"
                                >

                            <?php } else { ?>

                                <div class="mini-cover">
                                    📖
                                </div>

                            <?php } ?>

                        </div>


                        <div class="cart-book-details">

                            <span>
                                BOOK
                            </span>

                            <h2>
                                <?php
                                echo htmlspecialchars(
                                    $item['title']
                                );
                                ?>
                            </h2>

                            <p>
                                by
                                <?php
                                echo htmlspecialchars(
                                    $item['author']
                                );
                                ?>
                            </p>

                            <p class="cart-price">
                                ₹<?php
                                echo number_format(
                                    $item['price'],
                                    2
                                );
                                ?>
                            </p>

                        </div>


                        <div class="cart-quantity">

                            <span>
                                Quantity
                            </span>

                            <strong>
                                <?php echo $item['quantity']; ?>
                            </strong>

                        </div>


                        <div class="cart-subtotal">

                            <strong>
                                ₹<?php
                                echo number_format(
                                    $item['subtotal'],
                                    2
                                );
                                ?>
                            </strong>


                            <a
                                href="remove_from_cart.php?id=<?php
                                echo $item['id'];
                                ?>"
                                class="remove-btn"
                                onclick="return confirm(
                                    'Remove this book from your cart?'
                                );"
                            >
                                Remove
                            </a>

                        </div>


                    </div>

                <?php } ?>


            </div>


            <aside class="cart-summary">

                <h2>
                    Order Summary
                </h2>

                <div class="summary-row">

                    <span>
                        Items
                    </span>

                    <strong>
                        <?php echo $cart_count; ?>
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Subtotal
                    </span>

                    <strong>
                        ₹<?php
                        echo number_format(
                            $total,
                            2
                        );
                        ?>
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Delivery
                    </span>

                    <strong>
                        FREE
                    </strong>

                </div>


                <hr>


                <div class="summary-total">

                    <span>
                        Total
                    </span>

                    <strong>
                        ₹<?php
                        echo number_format(
                            $total,
                            2
                        );
                        ?>
                    </strong>

                </div>


                <a
                    href="checkout.php"
                    class="checkout-btn"
                >
                    Proceed to Checkout →
                </a>


                <a
                    href="books.php"
                    class="continue-shopping"
                >
                    ← Continue Shopping
                </a>

            </aside>


        </section>


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