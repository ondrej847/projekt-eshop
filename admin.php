<?php
session_start();
require_once "funcs.php";
require_once "layout/header.php";

$conn = connect_db();


if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    echo "<p>Musíte být přihlášeni jako administrátor, pokud chcete vidět všechny objednávky.</p>";
    exit;
}

$sql = "SELECT objednavka_id, datum_objednavky, celkova_cena, stav, platba, ulice, cislo_popisne, mesto, psc FROM objednavky ORDER BY datum_objednavky DESC";
$result = $conn->query($sql);

echo "<div class='objednavky-container'>";
echo "<h2>Všechny objednávky</h2>";

if ($result->num_rows > 0) {
    echo "<table class='objednavky-table'>";
    echo "<tr><th>Číslo objednávky</th><th>Datum</th><th>Celková cena</th><th>Stav</th><th>Platba</th><th>Adresa doručení</th><th>Akce</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['objednavka_id'] . "</td>";
        echo "<td>" . $row['datum_objednavky'] . "</td>";
        echo "<td>" . $row['celkova_cena'] . " Kč</td>";
        echo "<td>" . $row['stav'] . "</td>";
        echo "<td>" . $row['platba'] . "</td>";
        echo "<td>" . $row['ulice'] . " " . $row['cislo_popisne'] . ", " . $row['mesto'] . " " . $row['psc'] . "</td>";
        echo "<td>
            <form method='POST' action='update_objednavka.php'>
            <input type='hidden' name='order_id' value='" . $row['objednavka_id'] . "'>
            <select name='stav'>
            <option value='nová'" . ($row['stav'] == 'nová' ? ' selected' : '') . ">Nová</option>
            <option value='odeslána'" . ($row['stav'] == 'odeslána' ? ' selected' : '') . ">Odeslána</option>
            </select>
            <input type='submit' value='Uložit'>
            </form>
              </td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p class='objednavky-empty'>Žádné objednávky nebyly nalezeny.</p>";
}

echo "</div>";

$conn->close();
?>

<style>

.objednavky-container {
    max-width: 900px;
    margin: 100px auto;
    padding: 20px;
    background-color:rgb(247, 244, 244);
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.objednavky-container h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

.objednavky-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.objednavky-table th, .objednavky-table td {
    padding: 12px;
    border: 1px solid #ddd;
}

.objednavky-table th {
    background-color: #88a08d;
    color: white;
    font-weight: bold;
}

.objednavky-empty {
    font-size: 18px;
    color: red;
    text-align: center;
    margin-top: 20px;
}
</style>

<?php require_once "layout/footer.php"; ?>