<?php
session_start();

$arrayRuangan = $_SESSION["arrayRuangan"];
$indexRuangan = $_SESSION["indexRuangan"];
$indexMatkul = $_SESSION["indexMatkul"];


$idxMatkul = $_POST["changeMatkul"];
$harijam = $_POST["pindahKe"];

//echo $idxMatkul;

$count = 0;


$arrayRuangan[0][0][1] = 0;
//ALGO LU

foreach ($arrayRuangan as $idxRuangan => $ruangan) {
	foreach ($ruangan[$idxMatkul] as $idxJam => $jam) {
			if ($jam[1]) {
				$arrayRuangan[$idxRuangan]
				[$idxMatkul]
				[$idxJam]
				[1] = false;
				$count++;
			}
		}
	}

echo $count;


$_SESSION["arrayRuangan"] = $arrayRuangan;
$_SESSION["arrayRuangan"] = $arrayRuangan;
$_SESSION["arrayRuangan"] = $arrayRuangan;

// header("Location: /GitHub/Tubes1AI/result.php");


?>