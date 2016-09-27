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
$found = 0;

//ALGO LU

foreach ($arrayRuangan as $idxRuangan => $ruangan) {
	foreach ($ruangan[$idxMatkul] as $idxJam => $jam) {
			if ($jam[1]) {
				$arrayRuangan[$idxRuangan][$idxMatkul][$idxJam][1] = false;
				$count++;
				$Ruangan = $idxRuangan;
			}
		}
	}

// PARSER FUNCTION ----------------------------------------------------------
function getJam($harijam, $letakjam) {
/*

*/
	if ($harijam[$letakjam]==0) {
		$currentjam = 0;
	} elseif ($harijam[$letakjam]==1) {
		$currentjam = 1;
		if (strlen($harijam) == $letakjam+2) {
			if($harijam[$letakjam+1]==0) {
				$currentjam = 	10;			
			}
		}
	} elseif ($harijam[$letakjam]==2) {
		$currentjam = 2;
	} elseif ($harijam[$letakjam]==3) {
		$currentjam = 3;
	} elseif ($harijam[$letakjam]==4) {
		$currentjam = 4;
	} elseif ($harijam[$letakjam]==5) {
		$currentjam = 5;
	} elseif ($harijam[$letakjam]==6) {
		$currentjam = 6;
	} elseif ($harijam[$letakjam]==7) {
		$currentjam = 7;
	} elseif ($harijam[$letakjam]==8) {
		$currentjam = 8;
	} elseif ($harijam[$letakjam]==9) {
		$currentjam = 9;
	} elseif ($harijam[$letakjam]==10) {
		$currentjam = 10;		
	} 
	return($currentjam);
}

function getTotalJam($harijam) {
// Masukkannya berupa hari yang dipost dari result.php
// Keluarannya adalah index hari : Senin:1, Selasa:2, Rabu:3, Kamis:4, Jumat:5

if (($harijam[0] == 's') && ($harijam[1] == 'e') && ($harijam[2] == 'n') && 
	($harijam[3] == 'i') && ($harijam[4] == 'n')) {
	$i = 0;
	$letakjam = 5;
} elseif (($harijam[0] == 's') && ($harijam[1] == 'e') && ($harijam[2] == 'l') && 
	($harijam[3] == 'a') && ($harijam[4] == 's') && ($harijam[5] == 'a')) {
	$i = 1;
	$letakjam = 6;
} elseif (($harijam[0] == 'r') && ($harijam[1] == 'a') && ($harijam[2] == 'b') && 
	($harijam[3] == 'u')) {
	$i = 2;
	$letakjam = 4;
} elseif (($harijam[0] == 'k') && ($harijam[1] == 'a') && ($harijam[2] == 'm') && 
	($harijam[3] == 'i') && ($harijam[4] == 's')) {
	$i = 3;
	$letakjam = 5;
} elseif (($harijam[0] == 'j') && ($harijam[1] == 'u') && ($harijam[2] == 'm') && 
	($harijam[3] == 'a') && ($harijam[4] == 't')) {
	$i = 4;
	$letakjam = 5;
}
	return ($i*11 + getJam($harijam, $letakjam));
}
//--------------------------------------------------------------------------------

$currentjam = getTotalJam($harijam);
$x = 0;	
for ($x = 0; $x < $count ; $x++) {
	$arrayRuangan
	[$Ruangan]
	[$idxMatkul]
	[$currentjam]
	[1] = true;
	$currentjam = $currentjam +1 ;
}

//echo "jumlah matkul disitu :" , $count, "       ";
//echo "Jam :" , getTotalJam($harijam), "       ";


$_SESSION["arrayRuangan"] = $arrayRuangan;
$_SESSION["indexruangan"] = $indexRuangan;
$_SESSION["indexMatkul"] = $indexMatkul;

header("Location: /aischeduling/result.php");


?>