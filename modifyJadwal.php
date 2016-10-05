<?php
session_start();

$arrayRuangan = $_SESSION["arrayRuangan"];
$indexRuangan = $_SESSION["indexRuangan"];
$indexMatkul = $_SESSION["indexMatkul"];


$idxMatkul = $_POST["changeMatkul"];
$harijam = $_POST["pindahKe"];


$count = 0;
$found = 0;

foreach ($arrayRuangan as $idxRuangan => $ruangan) {
	foreach ($ruangan[$idxMatkul] as $idxJam => $jam) {
			if ($jam[1]) {
				$arrayRuangan[$idxRuangan][$idxMatkul][$idxJam][1] = false;
				$count++;
				$Ruangan = $idxRuangan;
			}
		}
	}

if (strpos($harijam, '-')==true) {
	$Ruangan = $harijam[strpos($harijam,'-')+1];
	if (isset($harijam[strpos($harijam,'-')+2])) {
		$Ruangan = $Ruangan*10 + $harijam[strpos($harijam,'-')+2];
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
		if (isset($harijam[$letakjam+1])) {
			if ($harijam[$letakjam+1]=='0') {
				$currentjam = 10;			
			} elseif ($harijam[$letakjam+1] == '-') {
				echo "PINDAH RUANG";
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
//echo $currentjam , '    ', $count, '   ';


// Pengecekan apakah pemindahan valid atau tidak ---------------------------------
if (($currentjam+$count) > 55) {
	$_SESSION['JamFit'] = false;
} elseif (($currentjam+$count) < 56) { 
	$_SESSION['JamFit'] = true;
	$x = 0;	
	for ($x = 0; $x < $count ; $x++) {
		$arrayRuangan
		[$Ruangan]
		[$idxMatkul]
		[$currentjam]
		[1] = true;
		$currentjam = $currentjam +1 ;
	}
//--------------------------------------------------------------------------------


	$_SESSION["arrayRuangan"] = $arrayRuangan;
	$_SESSION["indexruangan"] = $indexRuangan;
	$_SESSION["indexMatkul"] = $indexMatkul;
}
	$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= $_SERVER['REQUEST_URI'];

	header("Location: " . dirname($url) . "/result.php");
?>