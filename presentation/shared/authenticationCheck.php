<?php
session_start();

if(isset($_SESSION['userid']) == false || $_SESSION['userid'] == null || $_SESSION['userid'] == false){
    header("Location: ../login/login.php");
}
?>