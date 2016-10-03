<?php

include "readfile.php";
include "library.php";

//============FUNGSI-FUNGSI SA==============================================

//Variabel Global
$arrayTarget = array();//Isi: domain (waktu&tempat)
$arrayMJ = array();//Isi: variabel mk-durasi MJ[][0]=durasi
$tuple = array();//Isi: 0-tempat; 1- waktu
$T = 500;
$UR = sizeof($indexRuangan);
$UM = sizeof($indexMatkul);
$UW = sizeof($arrayRuangan[0][0]);
$langkah = $_POST["jumlahLangkah"];//ubah ini, jadinya dari input

function checkMultiple($UM, $UR,$UW, $arrayRuangan, $indexMatkul,$indexRuangan){//total jumlah jam tempat yg nabrak
	$jml=0;
	for ($i=0;$i<$UR; $i++){//ruangan
		for ($j=0; $j<$UW; $j++){//jam
			$nabrak = -1;
			for ($k=0; $k<$UM ;$k++){//matkul
				if ($arrayRuangan[$i][$k][$j][1]==1) $nabrak++;
			}
			if ($nabrak>0) $jml++;
		}
	}
	return $jml;
}

function generateRandomStart($UM, $UR, $UW, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayMJ, $arrayTarget){//untuk mengenerate random state diawal operasi SA
	//cleanse
	for ($i=0; $i<$UR;$i++){
		for ($j=0; $j< $UM; $j++){
			for ($k=0; $k< $UW; $k++){
				$arrayRuangan[$i][$j][$k][1]=0;
			}
		}
	}
	
	//placement
	for ($i=0;$i<$UM;$i++){
		$arrayTarget = collectDomain($UR, $UW, $arrayRuangan, $i, $indexRuangan, $arrayTarget);
		
		//waktu&tempat random
		if (sizeof($arrayTarget)>0){
			$randVal = rand(0, sizeof($arrayTarget)-1);
			
			$ruangan = $arrayTarget[$randVal][0];
			$waktu = $arrayTarget[$randVal][1];
		
			for ($j=0; $j<$arrayMJ[$i];$j++){
				$arrayRuangan[$ruangan][$i][$waktu][1]=1;
				$waktu++;
			}
		}
		unset($arrayTarget);
		$arrayTarget = array();
		
	}
	return $arrayRuangan;
}

function varMat_Jam($UW,$UM, $arrayMJ, $arrayRuangan, $indexMatkul){//Menghitung durasi permatkul dan menyimpan datanya di array
	
	for ($k=0;$k<$UM;$k++){
		$jml = 0;
		$i =0;
		while ($i<$UW && $arrayRuangan[0][$k][$i][1]==0) $i++;
		while ($i<$UW && $arrayRuangan[0][$k][$i][1]==1) {
			$i++;
			$jml++;
		}
		$arrayMJ[$k]=$jml;
		
	}
	return $arrayMJ;
}

function collectDomain($UR, $UW, $arrayRuangan, $idxMatkul, $indexRuangan, $arrayTarget){//Menciptakan array berisi domain matkul (tempat waktu)
	$idx=0;
	for ($k=0;$k<$UR; $k++){//telusurin ruangan
		for ($l=0; $l<$UW; $l++){//telusurin waktu
			if ($arrayRuangan[$k][$idxMatkul][$l][0] == 1){
				array_push($arrayTarget, array());
				array_push($arrayTarget[$idx],array());
				$arrayTarget[$idx][0] = $k;//tempat
				$arrayTarget[$idx][1] = $l;//waktu
				$idx++;
			}
		}
	}
	return $arrayTarget;
}

function countProb($T, $frmE, $crrE){//ngitung peluang perpindahan
	return exp(-($crrE-$frmE)/$T);
}

function decision($UM, $UR,$UW,$T, $arrayRuangan, $idxMatkul, $idxRuangan, $waktu, $indexMatkul, $indexRuangan, $tuple, $arrayMJ){//Menentukan apakah matkul pindah jadwal/ngga, kalau memungkinkan, dipindah
	//save former state
	$formerEnergy 	= checkMultiple($UM, $UR,$UW, $arrayRuangan, $indexMatkul,$indexRuangan) + cekKesalahan($arrayRuangan,$UR,$UM) ;
	$tuple 			= searchPosition($UR, $UW, $arrayRuangan, $indexRuangan, $idxMatkul, $tuple);
	
	$formerPlace = $tuple[0];
	$formerTime = $tuple[1];
		
	//trial-move
	$arrayRuangan 	= moveMatkul($UR,$UW,$idxRuangan, $waktu, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
	$currEnergy 	= checkMultiple($UM, $UR,$UW, $arrayRuangan, $indexMatkul,$indexRuangan) + cekKesalahan($arrayRuangan,$UR,$UM) ;

	if ($formerEnergy <= $currEnergy)
	{
		if ($T>0 && countProb($T,$formerEnergy,$currEnergy)*10 < mt_rand(0,10)){//return
			$arrayRuangan = moveMatkul($UR,$UW,$formerPlace, $formerTime, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
		}else if ($T<=0)
			$arrayRuangan = moveMatkul($UR,$UW,$formerPlace, $formerTime, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
	}
	return $arrayRuangan;
}

function searchPosition($UR, $UW, $arrayRuangan, $indexRuangan, $idxMatkul, $tuple){//Mencari posisi waktu dan tempat
	$found=false;
	$i = 0;
	$waktu =0 ;
	$k=0;
	$l=0;
	
	for ($k=0;$k<$UR && $found==false; $k++){//telusurin ruangan
		for ($l=0; $l<$UW && $found==false; $l++){//telusurin waktu
			if ($arrayRuangan[$k][$idxMatkul][$l][1] == 1){
				$found = true;
				$i=$k;
				$waktu = $l;
			}
		}
	}
	
	$tuple[0]=$i; //tempat
	$tuple[1]=$waktu;//waktu
	return $tuple;
	
}

function moveMatkul($UR,$UW,$tTempat, $tWaktu, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul){//Fungsi untuk memindahkan matkul dari suatu tempat, ke tempat baru $tTempat dengan waktu mulai = $tWaktu 
	$tuple = searchPosition($UR,$UW,$arrayRuangan, $indexRuangan, $idxMatkul, $tuple);
	$iterasi = $tuple[1];
	$valid = true;
	$durasi = $arrayMJ[$idxMatkul];
	//clearing
	for($i=0; $i<$durasi; $i++){
		$arrayRuangan[$tuple[0]][$idxMatkul][$iterasi][1] = 0;
		$iterasi++;
		$arrayRuangan[$tTempat][$idxMatkul][$tWaktu][1] = 1;
		$tWaktu++;
	}
	return $arrayRuangan;
}

function SimAnneling($langkah, $UM, $UR, $UW,$T, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget, $tuple, $arrayMJ){//Fungsi utama SA (tidak termasuk pembangkitan random di awal)
	$step = array();//posisi waktu target;
	$tempL=$langkah;
	$tempT=$T;
	
	while ($langkah!=0) 
	{
		$currMatkul = rand(0, $UM-1);
		$arrayTarget = collectDomain($UR, $UW,$arrayRuangan, $currMatkul, $indexRuangan, $arrayTarget);//cari domain tempat&waktu
		$valid = false;
		if(sizeof($arrayTarget)>0){
			while ($valid == false){//domain valid check
				$step = mt_rand(0, sizeof($arrayTarget) - 1);//nyari tempat&waktu
				$batas = $arrayTarget[$step][1] + $arrayMJ[$currMatkul]-1;
				if ($batas < $UW)
				{
					$bisa=true;
					for ($i=0; $i<$arrayMJ[$currMatkul]; $i++){
						if ($bisa==true && $arrayRuangan[$arrayTarget[$step][0]][$currMatkul][($arrayTarget[$step][1])+$i][0] == 0){
							$bisa = false;
						}
					}
					$valid = $bisa;
				}
				
				
				//$valid = ($arrayRuangan[$arrayTarget[$step][0]][$currMatkul][$batas][0] == 1 && $arrayRuangan[$arrayTarget[$step][0]][$currMatkul][$arrayTarget[$step][1]][0] == 1);
			}
			$tuple = searchPosition($UR, $UW, $arrayRuangan, $indexRuangan, $currMatkul, $tuple);
		
			$arrayRuangan = decision($UM, $UR,$UW,$T, $arrayRuangan, $currMatkul, $arrayTarget[$step][0], $arrayTarget[$step][1],  $indexMatkul, $indexRuangan, $tuple, $arrayMJ);
		}
		//unset array of domain
		unset($arrayTarget);
		$arrayTarget = array();
		$T --;
		$langkah--;
	}
	$langkah = $tempL;
	$T = $tempT;
	return $arrayRuangan;	
}


//-----------------------------------MAIN SA------------------------------------------------------------------

//Menghitung durasi per matkul dan menyimpan datanya di arrayMJ
$arrayMJ = varMat_Jam($UW,$UM,$arrayMJ, $arrayRuangan, $indexMatkul); 


//Membangkitkan state atau solusi random

//Simulated anneling

//while (checkMultiple($UM, $UR,$UW, $arrayRuangan, $indexMatkul,$indexRuangan)!=0 && $max>0)

$max = 3;
$cek = checkMultiple($UM, $UR,$UW, $arrayRuangan, $indexMatkul,$indexRuangan)+cekKesalahan($arrayRuangan,$UR,$UM) ;

while ($cek!=0 && $max!=0){
	$arrayRuangan = generateRandomStart($UM, $UR, $UW,$arrayRuangan, $indexMatkul, $indexRuangan, $arrayMJ, $arrayTarget);
	$arrayRuangan = SimAnneling($langkah,$UM, $UR, $UW,$T, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget, $tuple, $arrayMJ);
	$max--;
	$cek = checkMultiple($UM, $UR,$UW, $arrayRuangan, $indexMatkul,$indexRuangan) + cekKesalahan($arrayRuangan,$UR,$UM) ;
}

//passing
session_start();
	$_SESSION["arrayRuangan"] = $arrayRuangan; 
	$_SESSION["indexRuangan"] = $indexRuangan;
	$_SESSION["indexMatkul"] = $indexMatkul;
	
	//$_SESSION["pass"] = $pass;
	$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= $_SERVER['REQUEST_URI'];

	header("Location: " . dirname($url) . "/result.php");
 exit();
?>
