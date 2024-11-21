<?php
session_start();

unset($_SESSION['ingelogde_gebruiker']);
header("Location: login.php");

?>