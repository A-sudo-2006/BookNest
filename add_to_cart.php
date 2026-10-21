<?php

session_start();

require_once "db.php";


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: books.php");
    exit;
}


if (!isset($_POST['book_id'])) {
    header("Location: books.php");
    exit;
}


$book_id = (int) $_POST['book_id'];


if ($book_id <= 0) {
    header("Location: books.php");
    exit;
}


$stmt = $conn->prepare("SELECT id FROM books WHERE id = ?");

$stmt->bind_param("i", $book_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    $stmt->close();

    header("Location: books.php");
    exit;
}


$stmt->close();


if (isset($_SESSION['cart'][$book_id])) {

    $_SESSION['cart'][$book_id]++;

} else {

    $_SESSION['cart'][$book_id] = 1;
}


header("Location: cart.php");
exit;

?>