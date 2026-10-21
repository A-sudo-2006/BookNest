<?php
session_start();

$cart_count = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_count += $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BookNest | Your Cozy Book Corner</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="navbar">

    <div class="logo">
        <span>📚</span> BookNest
    </div>

    <nav>
        <a href="index.php" class="active">Home</a>
        <a href="books.php">Books</a>
        <a href="cart.php" class="cart-link">
            🛒 Cart
            <span class="cart-badge"><?php echo $cart_count; ?></span>
        </a>
    </nav>

</header>


<section class="hero">

    <div class="hero-content">

        <p class="small-title">WELCOME TO BOOKNEST</p>

        <h1>
            Find a book,
            <br>
            <span>find yourself.</span>
        </h1>

        <p>
            Discover stories, knowledge and adventures
            waiting for you on every page.
        </p>

        <div class="hero-buttons">
            <a href="books.php" class="btn-primary">
                Explore Books →
            </a>

            <a href="#about" class="btn-secondary">
                Discover BookNest
            </a>
        </div>

    </div>

    <div class="hero-book">

        <div class="book-decoration">✦</div>

        <div class="book-card">

            <div class="book-card-top">
                BOOK
            </div>

            <div class="book-card-middle">
                <span>YOUR</span>
                <strong>NEXT</strong>
                <span>CHAPTER</span>
            </div>

            <div class="book-card-bottom">
                BOOKNEST
            </div>

        </div>

    </div>

</section>


<section class="features" id="about">

    <div class="section-heading">
        <p>WHY BOOKNEST?</p>
        <h2>A little place for every reader.</h2>
    </div>

    <div class="feature-grid">

        <div class="feature-card">
            <div class="feature-icon">📖</div>
            <h3>Curated Books</h3>
            <p>
                Explore a collection of books selected
                for curious minds and passionate readers.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">💗</div>
            <h3>Made With Love</h3>
            <p>
                BookNest is designed to make your
                book-shopping experience simple and enjoyable.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🛒</div>
            <h3>Easy Shopping</h3>
            <p>
                Add your favourite books to the cart
                and manage your collection easily.
            </p>
        </div>

    </div>

</section>


<section class="quote-section">

    <div class="quote-mark">“</div>

    <h2>
        A room without books is like
        a body without a soul.
    </h2>

    <p>— Marcus Tullius Cicero</p>

</section>


<footer>

    <div class="footer-logo">
        📚 BookNest
    </div>

    <p>
        Your cozy corner for books & stories.
    </p>

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