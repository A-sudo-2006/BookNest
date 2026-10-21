<?php

session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (!isset($_GET['id'])) {
    header("Location: cart.php");
    exit;
}


$book_id = (int) $_GET['id'];


if ($book_id > 0) {

    unset($_SESSION['cart'][$book_id]);

}


header("Location: cart.php");
exit;

?>