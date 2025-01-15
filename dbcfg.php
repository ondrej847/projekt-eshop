<?php
$server = "localhost";
$login = "root";
$passwd = "";
$schema = "eshop";

$con = mysqli_connect($server, $login, $passwd, $schema);

/* konrola pripojeni */
if (!$con) {
    return mysqli_connect_error();
}

return $con;


?>