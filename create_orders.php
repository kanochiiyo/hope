<?php
session_start();

require_once(__DIR__ . "/functions/authentication.php");
require_once(__DIR__ . "/functions/functions.php");

$connection = getConnection();

if (!isLogged()) {
    header("Location:login.php");
}

include(__DIR__ . "/templates/header.php");

?>
<div class="container-fluid d-flex" id="createOrder">
    <?php include(__DIR__ . "/templates/sidebar.php"); ?>
</div>