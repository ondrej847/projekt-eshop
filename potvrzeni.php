<?php
session_start();
require_once "layout/header.php";


<div class="potvrzovaci-zprava">
    <h2>Vaše objednávka byla úspěšně vytvořena!</h2>
    <p>Číslo objednávky: <strong> <?php echo $order_id; ?> </p>
    <a href="index.php">Pokračovat v nákupu</a>
</div>

<?php require_once "layout/footer.php"; ?>