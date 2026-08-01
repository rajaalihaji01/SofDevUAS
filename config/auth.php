<?php
session_start();

if(!isset($_SESSION['login'])){
header("location:../auth/login.php");
exit;
}

if(time() - $_SESSION['last_activity'] > 600){
session_destroy();
header("location:../auth/login.php?timeout=1");
}

$_SESSION['last_activity']=time();
?>