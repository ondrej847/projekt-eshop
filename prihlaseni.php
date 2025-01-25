<?php
/* kontrola sputeni session, jestli neni spustena tak ji my timto spustime */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
/*    ziskani dat z formualre */
    $email = $_POST['email'];
    $heslo = $_POST['heslo'];

/*  dotaz pro ziskani dat uzivatele podle emailu */
    $stmt = $conn->prepare("SELECT * FROM uzivatele WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

/* overeni hesla */
    if ($user_data && password_verify($heslo, $user_data['heslo'])) {
        
        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['jmeno'] = $user_data['jmeno'];
        $_SESSION['role_id'] = $user_data['role_id'];

/*         po prihlaseni presmerovani na nas index.php */
       header("Location: index.php");
        exit;
    } else {
/* pokud zada spatne udaje napise mu to zpravu: TODO - dodělat css pro zpravu */
        echo "Neplatný email nebo heslo!";
    }

/* konec dotazu  */
    $stmt->close();
    $conn->close();
}
?>
    <link rel="stylesheet" href="css/style-pri-reg.css">
<!-- TODO: opravit prihlasovani, aby po kliknuti zpet na strance se uzivatel odhlasil a byl odhlasen    -->
<style>
    body {
        height: 90vh;
    }
    .register {  
        display: block;
        text-align: center;
        margin-top: 10px;
        color: rgb(136, 160, 141);
        text-decoration: none;
    }
</style>

    <div class="form-container">
        <h2>Přihlášení</h2>
        <form action="prihlaseni.php" method="POST">
            <label for="id_email">Email:</label>
            <input type="email" name="email" placeholder="Zadejte svůj email" required>

            <label for="id_heslo">Heslo:</label>
            <input type="password" name="heslo"  placeholder="Zadejte své heslo" required>

            <input type="submit" value="Přihlásit">
    <div class="register">
        <a href="register.php" class="register"> Zaregistrujte se</a>
            
        </form>
        </div>
    </div>

    <?php require_once "layout/footer.php"; ?>