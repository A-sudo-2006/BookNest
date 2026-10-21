
<?php

session_start();

include 'db.php';


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cart.php");
    exit();
}


if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}


$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$pincode = trim($_POST['pincode'] ?? '');


if (
    empty($name) ||
    empty($email) ||
    empty($phone) ||
    empty($address) ||
    empty($city) ||
    empty($pincode)
) {
    die("Please fill all required fields.");
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}


if (!preg_match('/^[0-9]{10}$/', $phone)) {
    die("Phone number must contain 10 digits.");
}


if (!preg_match('/^[0-9]{6}$/', $pincode)) {
    die("PIN code must contain 6 digits.");
}


$cart = $_SESSION['cart'];

$cartBooks = [];

$total = 0;


foreach ($cart as $book_id => $quantity) {

    $book_id = (int)$book_id;
    $quantity = (int)$quantity;

    if ($quantity <= 0) {
        continue;
    }


    $stmt = $conn->prepare(
        "SELECT id, title, price
         FROM books
         WHERE id = ?"
    );

    $stmt->bind_param("i", $book_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $book = $result->fetch_assoc();


    if ($book) {

        $item_total = $book['price'] * $quantity;

        $total += $item_total;

        $cartBooks[] = [
            'id' => $book['id'],
            'title' => $book['title'],
            'price' => $book['price'],
            'quantity' => $quantity
        ];
    }
}


if (empty($cartBooks)) {
    die("Your cart is empty.");
}


$conn->begin_transaction();


try {

    $status = "Confirmed";


    $orderStmt = $conn->prepare(
        "INSERT INTO orders
        (name, email, phone, address, city, pincode, total_amount, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );


    $orderStmt->bind_param(
        "ssssssds",
        $name,
        $email,
        $phone,
        $address,
        $city,
        $pincode,
        $total,
        $status
    );


    $orderStmt->execute();


    $order_id = $conn->insert_id;


    $itemStmt = $conn->prepare(
        "INSERT INTO order_items
        (order_id, book_id, quantity, price)
        VALUES (?, ?, ?, ?)"
    );


    foreach ($cartBooks as $book) {

        $itemStmt->bind_param(
            "iiid",
            $order_id,
            $book['id'],
            $book['quantity'],
            $book['price']
        );

        $itemStmt->execute();
    }


    $conn->commit();


    unset($_SESSION['cart']);


    header(
        "Location: order_success.php?order_id=" . $order_id
    );

    exit();


} catch (Exception $e) {

    $conn->rollback();

    echo "<h2>Order could not be placed.</h2>";
    echo "<p>Please try again.</p>";
    echo "<a href='cart.php'>Back to Cart</a>";

    exit();
}

?>
```