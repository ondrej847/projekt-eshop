<?php
/* kontrola sputeni session, jestli neni spustena tak ji my timto spustime */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eshop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="hlastr">   
        <a href="index.php" class="cinkarna">Činkárna</a>

        <div class="buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
            <!--    kdyz je uzivatel prihlasen bude mit moznost se odlhasit pomoci tlacitka a bude mit moznost na svuj ucet -->
                <a href="logout.php" class="button">Odhlásit</a>
                <a href="ucet.php" class="button">Můj účet</a>
            <?php else: ?>
            <!--     pokud prihlasen neni, bude mit moznost se jen prihlasit -->
                <a href="prihlaseni.php" class="button">Přihlásit</a>
            <?php endif; ?>
            
            <a href="cart.php" class="button">Košík</a>
        </div>
    </div>

<?php require_once "footer.php"; ?>