<?php
session_start();

require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();

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
    <link rel="stylesheet" href="css/style-pri-reg.css">
<style>
/* flexbox pro dva sloupce, aby to bylo pro noveho uzivatele rehlednejsi */
    .form-grid {
        display: flex;
        gap: 30px;
    }

    input[type="tel"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 25px;
        border: 1px solid #ccc;
    }
</style>

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

    <?php require_once "layout/footer.php"; ?>