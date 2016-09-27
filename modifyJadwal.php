<?php
session_start();

$arrayRuangan = $_SESSION["arrayRuangan"];
$indexRuangan = $_SESSION["indexRuangan"];
$indexMatkul = $_SESSION["indexMatkul"];
$lala = $_POST["pindahKe"];
echo $lala;

$arrayRuangan[0][0][1] = 0;
//ALGO LU

$_SESSION["arrayRuangan"] = $arrayRuangan;
$_SESSION["arrayRuangan"] = $arrayRuangan;
$_SESSION["arrayRuangan"] = $arrayRuangan;

header("Location: /aischeduling/result.php");
?>