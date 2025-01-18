<?php
session_start();

require_once "header.php";
require_once "footer.php";

/* zkontrolujeme zda je kosik prazdny, pokud ano dame echo */
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='cart-empty'>Váš košík je prázdný.</div>";
    exit;
}

$server = "localhost";
$login = "root";
$passwd = "";
$schema = "eshop";

$conn = new mysqli($server, $login, $passwd, $schema);


/* timto prikazem odebereme produkt z kosiku */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $remove_product_id = $_POST['remove_product_id'];
    unset($_SESSION['cart'][$remove_product_id]);

/* aby doslo k obnoveni kosiku po odebrani, pouzijeme toto a nasledne presmerujeme uzivatele znovu na cart.php */
    if (empty($_SESSION['cart'])) {
        header("Location: cart.php");
        exit;
    }


}

/* nacteme produkty v kosiku */
$product_ids = implode(',', array_keys($_SESSION['cart']));
$sql = "SELECT * FROM produkty WHERE product_id IN ($product_ids)";
$result = $conn->query($sql);

/* vypocet ceny */
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Košík</title>
   
</head>
<body>

<div class="cart-container">
    <h1>Váš košík</h1>
    <table>
        <thead>
            <tr>
                  <th>Položka</th>
                 <th>Cena</th>
                 <th>Množství</th>
                  <th>Mezisoučet</th>
                 <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?> <!-- zda v databazi jssou odpovidajici produkty -->
                <?php while ($row = $result->fetch_assoc()): 
                    $product_id = $row['product_id']; /* ziskame produkt_id */
                    $quantity = $_SESSION['cart'][$product_id];/*  nacte mnozstvi */
                    $subtotal = $row['cena'] * $quantity; /* cena kusu krát mnozstvi */
                    $total_price += $subtotal;
                ?>
                    <tr>
                        <td>  <!-- htmlspecialchars - zajistuje, ze texty a obrazky se zobrazi spravne a bezpecne na webu -->
                            <img class="icon" src="<?= htmlspecialchars($row['obrazek_url']) ?>" alt="<?= htmlspecialchars($row['nazev']) ?>"> 
                            <?= htmlspecialchars($row['nazev']) ?>
                        </td>
                        <td><?= htmlspecialchars($row['cena']) ?> Kč</td>
                        <td><?= $quantity ?></td>
                        <td><?= $subtotal ?> Kč</td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="remove_product_id" value="<?= $product_id ?>">
                                <button type="submit" class="remove-btn">&times;</button> <!-- &times je misto tlacitko to X -->
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <p class="total">Celková cena: <?= $total_price ?> Kč</p>
    <a href="pokladna.php" class="btn">Přejít k pokladně</a>
</div>

<?php $conn->close(); ?>
</body>
</html>