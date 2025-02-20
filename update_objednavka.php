<?php
session_start();
require_once "funcs.php";

$conn = connect_db();

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    echo "Nemáte oprávnění k této akci.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $stav = $_POST['stav'];

    // Aktualizace stavu objednávky
    $sql = "UPDATE objednavky SET stav = '$stav' WHERE objednavka_id = $order_id";

    if ($conn->query($sql) === TRUE) {
        echo "Stav objednávky byl úspěšně aktualizován.";
    } else {
        echo "Chyba při aktualizaci stavu objednávky: " . $conn->error;
    }
}

$conn->close();
header("Location: admin.php");
exit();
?>