
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
        echo "<div class='error-message' id='error-message'>Neplatný email nebo heslo!</div>";
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
    
.checkbox {
    position: relative;
    top: 2px;
}   /* TODO - doupravit css na checkbox, aby byl vic nahore a spojeny se span textem */

.checkbox-text {
    font-size: 14px;
    vertical-align: middle; 
}

.error-message {
    color: red; 
    background-color: #f8d7da; 
    padding: 10px;
    margin-top: 10px;
    border-radius: 5px;  
    font-size: 16px;
    text-align: center;  
    position: absolute;  
    top: 80px;  
    left: 50%;  
    transform: translateX(-50%); 
    width: 20%; 
    z-index: 10;  
}

</style>

<div class="form-container">
    <h2>Přihlášení</h2>
        <form action="prihlaseni.php" method="POST">
            <label for="id_email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Zadejte svůj email" required>
            <label for="id_heslo">Heslo:</label>

            <input type="password" name="heslo" id="id_heslo" placeholder="Zadejte své heslo" required>
            <input type="checkbox" id="showPasswordCheckbox" class="checkbox">
            <span class="checkbox-text">Zobrazit heslo</span>
            <input type="submit" value="Přihlásit">
    
                <div class="register">
                    <a href="register.php" class="register"> Zaregistrujte se</a>
            
        </form>
                </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const checkbox = document.getElementById('showPasswordCheckbox');
    const passwordInput = document.getElementById('id_heslo');

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            passwordInput.type = 'text'; 
        } else {
            passwordInput.type = 'password'; 
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(function() {
        errorMessage.style.opacity = '0';  
        setTimeout(function() {
        errorMessage.style.display = 'none';  
        }, 1000); 
    }, 3000);  
}
});
</script>


<?php require_once "layout/footer.php"; ?>
    
