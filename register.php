<?php
session_start();
?>
<?php
require_once "header.php";
require_once "footer.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;   /* Zarovnání do středu horizontálně */
            align-items: center;      /* Zarovnání do středu vertikálně */
        }

        .form-container {
            background-color: white;
            padding: 30px;
            width: 90%;
            max-width: 850px;
            margin-top: 90px; /* Posunutí dolů */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: rgb(136, 160, 141);
        }

        /* flexbox pro dva sloupce, aby to bylo pro noveho uzivatele rehlednejsi */
        .form-grid {
            display: flex;
            gap: 30px;
        }

        label {
            font-weight: bold;
       }

        input[type="text"], input[type="email"], input[type="password"], input[type="tel"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 25px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: rgb(136, 160, 141);
            border: none;
            color: white;
            font-size: 15px;
            cursor: pointer;

        }

    </style>
</head>
<?php

$host = 'localhost';  
$login = 'root'; 
$passwd = '';  
$schema = 'eshop';  

$conn = new mysqli($host, $login, $passwd, $schema);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $email = $_POST['email'];
    $heslo = $_POST['heslo'];
    $telefon = $_POST['telefon'];
    $ulice = $_POST['ulice'];
    $cislo_popisne = $_POST['cislo_popisne'];
    $mesto = $_POST['mesto'];
    $psc = $_POST['psc'];

/*     zasifrovani hesla */
    $hashed_password = password_hash($heslo, PASSWORD_DEFAULT);

   /* nastaveni role pro bezne uzivatele, tudiz id 2 */
    $role_id = 2;

  /*   sql dotaz pro vlozeni do database */
    $stmt = $conn->prepare("INSERT INTO uzivatele (jmeno, prijmeni, email, heslo, telefon, ulice, cislo_popisne, mesto, psc, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssi", $jmeno, $prijmeni, $email, $hashed_password, $telefon, $ulice, $cislo_popisne, $mesto, $psc, $role_id);

    if ($stmt->execute()) {
        echo "Registrace byla úspěšná!";
    /*     po registraci nas to presmeruje na prihlaseni.php */
        header("Location: prihlaseni.php");
        exit;
    } else {
        echo "Chyba při registraci: " . $stmt->error;
    }

/*   konec dotazu */
    $stmt->close();
    $conn->close();
}
?>
<body>
    <div class="form-container">
        <h2>Registrační formulář</h2>
        <form action="register.php" method="post">
            <div class="form-grid">
                <div>
                    <label for="jmeno">Jméno:</label>
                    <input type="text" name="jmeno" required placeholder="Zadejte jméno">

                    <label for="email">E-mail:</label>
                    <input type="email"  name="email" required placeholder="Zadejte email">

                    <label for="ulice">Ulice:</label>
                    <input type="text"  name="ulice" required placeholder="Zadejte ulici">

                    <label for="mesto">Město:</label>
                    <input type="text" name="mesto" required placeholder="Zadejte město">
                </div>
                <div>
                    <label for="prijmeni">Příjmení:</label>
                    <input type="text"  name="prijmeni" required placeholder="Zadejte příjmení">

                    <label for="password">Heslo:</label>
                    <input type="password" name="heslo" required placeholder="Zadejte heslo">

                    <label for="cislo_popisne">Číslo popisné:</label>
                    <input type="text" name="cislo_popisne" required placeholder="Zadejte číslo popisné">

                    <label for="psc">PSČ:</label>
                    <input type="text"  name="psc" required placeholder="Zadejte psč">
                </div>
            </div>
            <label for="telefon">Telefon:</label>
            <input type="tel" name="telefon" required placeholder="Zadejte telefon"> 
            
            <input type="submit" value="Registrovat">
        </form>
    </div>
</body>
</html>