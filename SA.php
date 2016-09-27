<?php

include "readfile.php";
include "library.php";

//============FUNGSI-FUNGSI SA==============================================

//Variabel Global
$arrayTarget = array();//Isi: domain (waktu&tempat)
$arrayMJ = array();//Isi: variabel mk-durasi MJ[][0]=durasi
$tuple = array();//Isi: 0-tempat; 1- waktu
//$langkah = 5000;
$T = 1000;

function checkMultiple($arrayRuangan, $indexMatkul,$indexRuangan){//total jumlah jam tempat yg nabrak
	$jml=0;
	for ($i=0;$i<sizeof($indexRuangan); $i++){//ruangan
		for ($j=0; $j<sizeof($arrayRuangan[0][0]); $j++){//jam
			$nabrak = -1;
			for ($k=0; $k<sizeof($indexMatkul);$k++){//matkul
				if ($arrayRuangan[$i][$k][$j][1]==1) $nabrak++;
			}
			if ($nabrak>0) $jml++;
		}
	}
	return $jml;
}

function generateRandomStart($arrayRuangan, $indexMatkul, $indexRuangan, $arrayMJ, $arrayTarget){//untuk mengenerate random state diawal operasi SA
	
	//cleanse
	for ($i=0; $i<sizeof($indexRuangan);$i++){
		for ($j=0; $j< sizeof($indexMatkul); $j++){
			for ($k=0; $k< sizeof($arrayRuangan[$i][$j]); $k++){
				$arrayRuangan[$i][$j][$k][1]=0;
			}
		}
	}
	
	//placement
	for ($i=0;$i<sizeof($indexMatkul);$i++){
		$arrayTarget = collectDomain($arrayRuangan, $i, $indexRuangan, $arrayTarget);
		//waktu&tempat random
		$randVal = rand(0, sizeof($arrayTarget[$i])-1);
		$ruangan = $arrayTarget[$randVal][0];
		$waktu = $arrayTarget[$randVal][1];
		
		for ($j=0; $j<$arrayMJ[$i];$j++){
			$arrayRuangan[$ruangan][$i][$waktu][1]=1;
			$waktu++;
		}
		unset($arrayTarget);
		$arrayTarget = array();
	}
	return $arrayRuangan;
}

function varMat_Jam($arrayMJ, $arrayRuangan, $indexMatkul){//Menghitung durasi permatkul dan menyimpan datanya di array
	for ($k=0;$k<sizeof($indexMatkul);$k++){
		$jml = 0;
		$i =0;
		while ($i<55 && $arrayRuangan[0][$k][$i][1]==0) $i++;

		while ($i<55 && $arrayRuangan[0][$k][$i][1]==1) {
			$i++;
			$jml++;
		}
		$arrayMJ[$k]=$jml;
	}
	return $arrayMJ;
}

function countEnergy($arrayRuangan, $indexMatkul, $indexRuangan){//Menghitung energi	
	$energy = checkMultiple($arrayRuangan, $indexMatkul,$indexRuangan);
	/*for($k=0;$k<sizeof($indexRuangan);$k++){
		$energy += cekBentrok($arrayRuangan,sizeof($indexRuangan),sizeof($indexMatkul),$indexRuangan[$k],$indexRuangan);
	}
	$energy+=cekKesalahan($arrayRuangan,sizeof($indexRuangan),sizeof($indexMatkul));
	*/return ($energy);
}

function collectDomain($arrayRuangan, $idxMatkul, $indexRuangan, $arrayTarget){//Menciptakan array berisi domain matkul (tempat waktu)
	$idx=0;
	for ($k=0;$k<sizeof($indexRuangan); $k++){//telusurin ruangan
		for ($l=0; $l<sizeof($arrayRuangan[$k][$idxMatkul]); $l++){//telusurin waktu
			
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

function decision($T, $arrayRuangan, $idxMatkul, $idxRuangan, $waktu, $indexMatkul, $indexRuangan, $tuple, $arrayMJ){//Menentukan apakah matkul pindah jadwal/ngga, kalau memungkinkan, dipindah
	//save former state
	$formerEnergy 	= countEnergy($arrayRuangan, $indexMatkul, $indexRuangan);
	$tuple = searchPosition($arrayRuangan, $indexRuangan, $idxMatkul, $tuple);
	
	$formerPlace = $tuple[0];
	$formerTime = $tuple[1];
		
	//trial-move
	$arrayRuangan = moveMatkul($idxRuangan, $waktu, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
	$currEnergy 	= countEnergy($arrayRuangan, $indexMatkul, $indexRuangan);

	if ($formerEnergy <= $currEnergy)
	{
		if ($T>0 && countProb($T,$formerEnergy,$currEnergy)*10 < mt_rand(0,10)){//return
			$arrayRuangan = moveMatkul($formerPlace, $formerTime, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
		}else if ($T<=0)
			$arrayRuangan = moveMatkul($formerPlace, $formerTime, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
	}
	return $arrayRuangan;
}

function searchPosition($arrayRuangan, $indexRuangan, $idxMatkul, $tuple){//Mencari posisi waktu dan tempat
	$found=false;
	$i = 0;
	$waktu =0 ;
	
	for ($k=0;$k<sizeof($indexRuangan); $k++){//telusurin ruangan
		for ($l=0; $l<sizeof($arrayRuangan[$k][$idxMatkul]); $l++){//telusurin waktu
			if ($arrayRuangan[$k][$idxMatkul][$l][1] == 1 && $found==false){
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

function moveMatkul($tTempat, $tWaktu, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul){//Fungsi untuk memindahkan matkul dari suatu tempat, ke tempat baru $tTempat dengan waktu mulai = $tWaktu 
	$tuple = searchPosition($arrayRuangan, $indexRuangan, $idxMatkul, $tuple);
	$iterasi = $tuple[1];
	$valid = true;
	//clearing
	for($i=0; $i<$arrayMJ[$idxMatkul]; $i++){
		$arrayRuangan[$tuple[0]][$idxMatkul][$iterasi][1] = 0;
		$iterasi++;
	}
	//placement
	for($i=0; $i<$arrayMJ[$idxMatkul]; $i++){
		$arrayRuangan[$tTempat][$idxMatkul][$tWaktu][1] = 1;
		$tWaktu++;
	}
	return $arrayRuangan;
}

function SimAnneling($T, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget, $tuple, $arrayMJ){//Fungsi utama SA (tidak termasuk pembangkitan random di awal)
	$step = array();//posisi waktu target;

	while ($T!=-500) 
	{
		$currMatkul = rand(0, sizeof($indexMatkul)-1);
		$arrayTarget = collectDomain($arrayRuangan, $currMatkul, $indexRuangan, $arrayTarget);//cari domain tempat&waktu
		$valid = false;
		while ($valid == false){//domain valid check
			$step = mt_rand(0, sizeof($arrayTarget) - 1);//nyari tempat&waktu
			$batas = $arrayTarget[$step][1]+$arrayMJ[$currMatkul];
			if ($batas < sizeof($arrayRuangan[0][0]))
				$valid = ($arrayRuangan[$arrayTarget[$step][0]][$currMatkul][$batas-1][0] == 1);
		}
		$tuple = searchPosition($arrayRuangan, $indexRuangan, $currMatkul, $tuple);
		$arrayRuangan = decision($T, $arrayRuangan, $currMatkul, $arrayTarget[$step][0], $arrayTarget[$step][1],  $indexMatkul, $indexRuangan, $tuple, $arrayMJ);
		
		//unset array of domain
		unset($arrayTarget);
		$arrayTarget = array();
		$T -= 1;
	}
	return $arrayRuangan;	
}


//-----------------------------------MAIN SA------------------------------------------------------------------

//Menghitung durasi per matkul dan menyimpan datanya di arrayMJ
$arrayMJ = varMat_Jam($arrayMJ, $arrayRuangan, $indexMatkul); 


//Membangkitkan state atau solusi random
	$arrayRuangan = generateRandomStart($arrayRuangan, $indexMatkul, $indexRuangan, $arrayMJ, $arrayTarget);
//Simulated anneling
	$arrayRuangan = SimAnneling($T, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget, $tuple, $arrayMJ);

//passing
session_start();
	$_SESSION["arrayRuangan"] = $arrayRuangan; 
	$_SESSION["indexRuangan"] = $indexRuangan;
	$_SESSION["indexMatkul"] = $indexMatkul;
	$_SESSION["jml"] = checkMultiple($arrayRuangan, $indexMatkul,$indexRuangan);
	
	$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= $_SERVER['REQUEST_URI'];

	header("Location: " . dirname($url) . "/result.php");
 exit();
?>
