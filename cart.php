<?php
session_start();

require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();


if (!isset($_SESSION['user_id'])) {

    echo '
    <div id="message" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
         background-color: white; color:rgb(136, 160, 141); 
            padding: 20px; text-align: center; font-size: 28px;  
             border: rgb(136, 160, 141);; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
        Nejsi přihlášen, přihlas se
    </div>';
    echo '<script>
            console.log("Zpráva bude zobrazená a přesměrování za 2 sekundy.");
            setTimeout(function() {
                window.location.href = "prihlaseni.php"; 
            }, 4000); 
          </script>';
    exit(); 
}

/* zkontrolujeme zda je kosik prazdny, pokud ano dame echo */
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='cart-empty'>Váš košík je prázdný.</div>";
    exit;
}



/* timto prikazem odebereme produkt z kosiku */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $remove_product_id = (int)$_POST['remove_product_id']; // Zajistíme, že hodnota je integer
    if (isset($_SESSION['cart'][$remove_product_id])) {
        $_SESSION['cart'][$remove_product_id] -= 1; // Odebereme 1 kus
        if ($_SESSION['cart'][$remove_product_id] <= 0) {
            unset($_SESSION['cart'][$remove_product_id]); // Pokud je množství 0, odstraníme produkt
        }
    }
}

/* aby doslo k obnoveni kosiku po odebrani, pouzijeme toto a nasledne presmerujeme uzivatele znovu na cart.php */
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
        exit;
}

/* nacteme produkty v kosiku */
if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $sql = "SELECT * FROM produkty WHERE product_id IN ($product_ids)";
    $result = $conn->query($sql);
} else {
    $result = false;
}

/* vypocet ceny */
$total_price = 0;
?>

<style>
.cart-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    margin-top: 40px;
}

table {
    width: 100%;
    border-collapse: collapse;    
}    

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
}

.total {
    text-align: right;
    font-weight: bold;
}

.button {       
    float: right;
}

.remove-button {
    color: #ff5c5c;
    background: none;       
    border: none;
    cursor: pointer;
    font-size: 1.2em;
}

.remove-button:hover {
    color: #ff1c1c;
}

.icon {
    width: 100px;
    height: auto;
}

.cart-empty {

}
  
</style>

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
                                <button type="submit" class="remove-button">&times;</button> <!-- &times je misto tlacitko to X -->
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <p class="total">Celková cena: <?= $total_price ?> Kč</p>
    <a href="pokladna.php" class="button">Přejít k pokladně</a>
</div>

<?php $conn->close(); ?>

<?php require_once "layout/footer.php"; ?>