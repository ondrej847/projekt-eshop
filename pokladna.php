<?php
session_start();
require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='prazdny-kosik'>Váš košík je prázdný.</div>";
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM uzivatele WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $jmeno = $user['jmeno'];
    $prijmeni = $user['prijmeni'];
    $ulice = $user['ulice'];
    $cislo_popisne = $user['cislo_popisne'];
    $mesto = $user['mesto'];
    $psc = $user['psc'];
}

$celkova_cena = 0;
$product_ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$sql = "SELECT * FROM produkty WHERE product_id IN ($product_ids)";
$products_result = $conn->query($sql);

if ($products_result->num_rows > 0) {
while ($row = $products_result->fetch_assoc()) {
     $product_id = $row['product_id'];
    $quantity = $_SESSION['cart'][$product_id];
     $celkova_cena += $row['cena'] * $quantity;
}
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['potvrdit_objednavku'])) {

    $adresa = $_POST['adresa_'] ?? 'registrace'; 

    if ($adresa === 'registrace') {
        $ulice = $user['ulice'];
        $cislo_popisne = $user['cislo_popisne'];
        $mesto = $user['mesto'];
        $psc = $user['psc'];
    } elseif ($adresa === 'novou_adresu') {
        $ulice = $_POST['ulice'] ?? '';
        $cislo_popisne = $_POST['cislo_popisne'] ?? '';
        $mesto = $_POST['mesto'] ?? '';
        $psc = $_POST['psc'] ?? '';
    }
    $sql = "INSERT INTO objednavky (user_id, celkova_cena, stav, platba, ulice, cislo_popisne, mesto, psc) 
            VALUES ($user_id, $celkova_cena, 'nová', 'nezaplaceno', '$ulice', '$cislo_popisne', '$mesto', '$psc')";
    if ($conn->query($sql)) {
        $objednavka_id = $conn->insert_id;
    } 
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "INSERT INTO objednavky_produkty (objednavka_id, product_id, mnozstvi, cena) 
                    VALUES ($objednavka_id, $product_id, $quantity, (SELECT cena FROM produkty WHERE product_id = $product_id))";
        
        }
        unset($_SESSION['cart']);
        
        header("Location: potvrzeni.php?objednavka_id=$_id");
        exit; 
}
?>

<style>
.pokladna-container {
    max-width: 1100px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 80px;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

.platba {
margin-top: 20px;
}
.total {
    text-align: right;
    font-weight: bold;
    margin-top: 20px;
}

.prazdny-kosik {
    text-align: center;
    font-size: 18px;
    color: red;
}
.potvrdit-button {
    padding: 10px 20px;  /* urcuje vnitrni okraje mezi obsahem a hranicemi */
    font-size: 16px;
    background-color:rgb(136, 160, 141);  
    color: white;  /* Bílý text na tlačítkách */    
    text-decoration: none;  /* aby nebyl text podtrzeny */
    border-radius: 15px;  /* zaobleni rohu */
    width: 100%; /* Na celou šířku pokud chceš */
    margin-top: 30px;
    cursor: pointer;
}
</style>

<div class="pokladna-container">
<h1>Pokladna</h1>
    <p><strong>Celková cena: </strong><?= $celkova_cena ?> Kč</p> <!-- strong - zvyrazneni tesxu -->
    
    <form action="pokladna.php" method="post">
    <h3>Adresa doručení</h3>
    <label>
        <input type="radio" name="adresa_" value="registrace" checked> Použít adresu z registrace
    </label>
    <br>
    <label>
        <input type="radio" name="adresa_" value="novou_adresu"> Zadat jinou adresu
    </label>
    <br>
    <div id="nova_adresa" style="display: none;">
        <label for="ulice">Ulice:</label>
        <input type="text" name="ulice" id="ulice" required>
        <br>
        <label for="cislo_popisne">Číslo popisné:</label>
        <input type="text" name="cislo_popisne" id="cislo_popisne" required>
        <br>
        <label for="mesto">Město:</label>
        <input type="text" name="mesto" id="mesto" required>
        <br>
        <label for="psc">PSČ:</label>
        <input type="text" name="psc" id="psc" required>
        <br>
    </div>
    <div id="adresa_registrace">
        <p><strong>Adresa z registrace:</strong></p>
        <p><?= htmlspecialchars($ulice . " " . $cislo_popisne . ", " . $mesto . " " . $psc); ?></p>
    </div>
    <div class="platba">
            <label for="zpusob-platby">Způsob platby:</label>
            <select name="order_type">
            <option value="dobírka">Dobírka</option>         
            </select>
        </div>
        <form action="pokladna.php" method="post">
    <button type="submit" name="potvrdit_objednavku" class="potvrdit-button">Potvrdit objednávku </button>
        </form>

</form>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const adresaTypeRadios = document.getElementsByName('adresa_');
    const adresaRegistraceDiv = document.getElementById('adresa_registrace');
    const novaAdresaDiv = document.getElementById('nova_adresa');


    adresaTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'novou_adresu') {
                adresaRegistraceDiv.style.display = 'none';
                novaAdresaDiv.style.display = 'block';
            } else {
                adresaRegistraceDiv.style.display = 'block';
                novaAdresaDiv.style.display = 'none';
 }
});
 });
});
</script>

<?php
$conn->close();
require_once "layout/footer.php";
?>