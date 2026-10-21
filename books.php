<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_count = 0;

foreach ($_SESSION['cart'] as $quantity) {
    $cart_count += $quantity;
}

$sql = "SELECT * FROM books ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Books | BookNest</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<header class="navbar">

    <div class="logo">
        <span>📚</span> BookNest
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="books.php" class="active">Books</a>

        <a href="cart.php" class="cart-link">
            🛒 Cart
            <span class="cart-badge">
                <?php echo $cart_count; ?>
            </span>
        </a>
    </nav>

</header>


<main class="books-page">

    <section class="page-header">

        <p class="small-title">BOOKNEST COLLECTION</p>

        <h1>
            Find your next
            <span>favourite book.</span>
        </h1>

        <p>
            Browse our collection and add your favourites
            to your reading basket.
        </p>

    </section>


    <section class="books-container">

        <?php

        if ($result && $result->num_rows > 0) {

            while ($book = $result->fetch_assoc()) {

                $book_id = $book['id'];
                $title = htmlspecialchars($book['title']);
                $author = htmlspecialchars($book['author']);
                $price = $book['price'];

        ?>

        <div class="book-item">

            <div class="book-cover">

                <?php if (!empty($book['image'])) { ?>

                    <img
                        src="assets/images/<?php echo htmlspecialchars($book['image']); ?>"
                        alt="<?php echo $title; ?>"
                    >

                <?php } else { ?>

                    <div class="cover-placeholder">
                        <span>📖</span>
                        <strong><?php echo $title; ?></strong>
                        <small>BOOKNEST</small>
                    </div>

                <?php } ?>

            </div>


            <div class="book-info">

                <span class="book-category">
                    BOOK
                </span>

                <h2>
                    <?php echo $title; ?>
                </h2>

                <p class="author">
                    by <?php echo $author; ?>
                </p>

                <div class="book-bottom">

                    <strong class="price">
                        ₹<?php echo number_format($price, 2); ?>
                    </strong>

                    <form
                        action="add_to_cart.php"
                        method="POST"
                    >

                        <input
                            type="hidden"
                            name="book_id"
                            value="<?php echo $book_id; ?>"
                        >

                        <button
                            type="submit"
                            class="add-cart-btn"
                        >
                            Add to Cart 🛒
                        </button>

                    </form>

                </div>

            </div>

        </div>

        <?php

            }

        } else {

        ?>

        <div class="empty-books">

            <div>📚</div>

            <h2>No books found</h2>

            <p>
                Add books to your database and they will
                appear here.
            </p>

        </div>

        <?php } ?>

    </section>

</main>


<footer>

    <div class="footer-logo">
        📚 BookNest
    </div>

    <p>Your cozy corner for books & stories.</p>

    <div class="footer-links">
        <a href="index.php">Home</a>
        <a href="books.php">Books</a>
        <a href="cart.php">Cart</a>
    </div>

    <p class="copyright">
        © <?php echo date("Y"); ?> BookNest. Made with ♥ for readers.
    </p>

</footer>

</body>
</html>