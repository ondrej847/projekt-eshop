<?php
/* konrolka session */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "header.php";
require_once "footer.php";




$server = "localhost";
$login = "root";
$passwd = "";
$schema = "eshop";


$conn = new mysqli($server, $login, $passwd, $schema);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Můj účet</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

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
    <?php endif; ?>
</div>
<style>
body {
    margin: 0px;
    padding: 0px;
}

.account-info {
    padding: 20px;
    margin-top: 80px; /* Aby se to nepřekrývalo s hlavním menu */
    background-color: #f4f4f4;
    text-align: center;
}

.account-details {
    font-size: 18px;
    color: #333;
}

.admin-button {
    margin-top: 20px;
    text-decoration: none; 
    color: rgb(136, 160, 141);
    text-align: center;
}


/* zatim, TODO: pak opravit a odstranit */

.buttons {
    margin-right: 30px;  /* tlacitka si posunu trochu doprava */
    display: flex;  /* aby byli tlacitka zarovnana vedle sebe */      
    }

.button {
    padding: 10px 20px;  /* urcuje vnitrni okraje mezi obsahem a hranicemi */
    margin-left: 15px;  /* mezera mezi tlacitky */ 
    background-color:rgb(136, 160, 141);  /* pozadi tlacitek najito na internetu */
    color: white;  /* Bílý text na tlačítkách */    
    text-decoration: none;  /* aby nebyl text podtrzeny */
    border-radius: 15px;  /* zaobleni rohu */

    
}

.button:hover {
    background-color: #45a049;  /* pri najeti mysi na tlacitka bude tmavsi zelena */
} 


</style>

</body>
</html>