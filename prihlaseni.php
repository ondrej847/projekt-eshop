<?php
/* kontrola sputeni session, jestli neni spustena tak ji my timto spustime */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "header.php";
require_once "footer.php"; 

$host = 'localhost';  
$login = 'root'; 
$passwd = '';  
$schema = 'eshop';  

$conn = new mysqli($host, $login, $passwd, $schema);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    
<!-- TODO: opravit prihlasovani, aby po kliknuti zpet na strance se uzivatel odhlasil a byl odhlasen    -->
            <!-- udělat funkci na connect_db (zkousel jsem, ale nefungovalo mi to) -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;   /* Zarovnání do středu horizontálně */
            align-items: center;      /* Zarovnání do středu vertikálně */
            height: 90vh;

        }
        .form-container { /* ohraniceni a zvyrazneni formulare */
            background-color: white;
            padding: 30px;
            width: 90%;
            max-width: 500px;
        }
        h2 { /* test prihlaseni */
            text-align: center;
            margin-bottom: 20px;
            color: rgb(136, 160, 141);
        }
        
        label { /* zvyrazneni emailu a hesla */
            font-weight: bold;

        }
        input[type="text"], input[type="password"], input[type="email"] { 
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }
        input[type="submit"] { /* tlacitko na prihlaseni */
            width: 100%;
            padding: 10px;
            background-color: rgb(136, 160, 141);
            border: none;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }
        a {/*  tlaciko na registraci */
            display: block;
            text-align: center;
            margin-top: 10px;
            color: rgb(136, 160, 141);
            text-decoration: none;
            font-size: 14px;
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Přihlášení</h2>
        <form action="prihlaseni.php" method="POST">
            <label for="id_email">Email:</label>
            <input type="email" name="email" placeholder="Zadejte svůj email" required>

            <label for="id_heslo">Heslo:</label>
            <input type="password" name="heslo"  placeholder="Zadejte své heslo" required>

            <input type="submit" value="Přihlásit">
            <a href="register.php">Zaregistrujte se</a>
        </form>
    </div>
</body>
</html>