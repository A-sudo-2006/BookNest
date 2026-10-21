<?php

session_start();

if (isset($_GET['id']) && isset($_GET['action'])) {

    $book_id = intval($_GET['id']);
    $action = $_GET['action'];

    if (isset($_SESSION['cart'][$book_id])) {

        if ($action === 'increase') {

            $_SESSION['cart'][$book_id]++;

        } elseif ($action === 'decrease') {

            $_SESSION['cart'][$book_id]--;

            if ($_SESSION['cart'][$book_id] <= 0) {
                unset($_SESSION['cart'][$book_id]);
            }
        }
    }
}

header("Location: cart.php");
exit();

?>