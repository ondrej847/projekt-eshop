<?php
/* konrolka session */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();

/* nacteni informaci z DB */
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM uzivatele WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
/* vyčteme udaje z database a vypiseme */
    $user = $result->fetch_assoc();
    $jmeno = $user['jmeno'];
    $prijmeni = $user['prijmeni'];
    $email = $user['email'];
    $telefon = $user['telefon'];
    $ulice = $user['ulice'];
    $cislo_popisne = $user['cislo_popisne'];
    $mesto = $user['mesto'];
    $psc = $user['psc'];
    $role_id = $user['role_id']; /* chceme ziskat roli uzivatele */
}
$conn->close();
?>

<style>
.account-info {
    padding: 20px;
    margin-top: 80px; /* Aby se to nepřekrývalo s hlavním menu */
    background-color:rgb(236, 235, 235);
    text-align: center;
}

.account-details {
    font-size: 18px;
    color: #333;
}

.admin-button {
    margin-top: 20px;
    text-align: center;
}
.objednavky-button {
    margin-top: 20px;
    text-align: center;

}

</style>

<div class="account-info">
    <h2>Informace o účtu</h2>
    <div class="account-details">
        <p><strong>Jméno:</strong> <?php echo $jmeno . " " . $prijmeni; ?></p> <!-- strong - text ktery bude zvyraznen -->
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Telefon:</strong> <?php echo $telefon ? $telefon : "Nezadáno"; ?></p>
        <p><strong>Adresa:</strong> <?php echo $ulice . " " . $cislo_popisne . ", " . $mesto . " " . $psc; ?></p>
    </div>
</div>
 <!-- tlacitko pro admina  -->
 <?php if (isset($role_id) && $role_id == 1): ?>
        <div class="admin-button">
            <a href="admin.php" class="button">Administrátorská stránka</a>
        </div>
 <?php else: ?>
        <div class="objednavky-button">        
            <a href="objednavky.php" class="button">Moje objednávky</a>
        </div>
 <?php endif; ?>
</div>


<?php require_once "layout/footer.php"; ?>