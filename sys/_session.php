<?php
session_start();
if (!isset($_SESSION["tec_nome"])) {
    header("Location: login.php");
    exit;
    
}
?>