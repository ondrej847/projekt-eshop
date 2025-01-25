<?php
session_start();

require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();

?>
<?php require_once "layout/footer.php"; ?>