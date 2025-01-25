<?php
/* kontrola sputeni session, jestli neni spustena tak ji my timto spustime */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "header.php";
require_once "funcs.php";

$conn = connect_db();
?>



<?php require_once "footer.php"; ?>