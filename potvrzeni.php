<?php
session_start();
require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();

if (!isset($_GET['objednavka_id'])) {
    echo "Chyba: Chybí ID objednávky.";
    exit;
}

$objednavka_id = intval($_GET['objednavka_id']);
$sql = "SELECT * FROM objednavky WHERE objednavka_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $objednavka_id);
$stmt->execute();
$result = $stmt->get_result();


$objednavka = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<style>
.potvrzeni-container {
    max-width: 650px;
    margin: 50px auto;
    padding: 50px;
    border: 2px solid rgb(136, 160, 141);
    border-radius: 8px;
    background-color: #f9f9f9;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 180px ;
}
.potvrzeni-container h2 {
    color: rgb(136, 160, 141);
}
</style>

<div class="potvrzeni-container">
        <h2>Objednávka č. <?= ($objednavka['objednavka_id']) ?> byla vytvořena!</h2>
        <p>Bude doručena na adresu:</p>
        <p><strong><?= ($objednavka['ulice']) . " " . ($objednavka['cislo_popisne']) . ", " . ($objednavka['mesto']) . ", " . ($objednavka['psc']) ?></strong></p>
        <p>Děkujeme za váš nákup!</p>
    </div>

<script>
        console.log("Zpráva bude zobrazená a přesměrování za 8 sekund.");
            setTimeout(function() {
                window.location.href = "index.php"; 
            }, 8000); 
</script>';

<?php require_once "layout/footer.php"; ?>