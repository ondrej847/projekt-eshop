<?php
function connect_db() {

require_once "dbcfg.php";
$conn = new mysqli($server, $login, $passwd, $schema);

if ($conn->connect_error) {
    return "Chyba připojení: " . $conn->connect_error;
}

return $conn;
}
?>