<?php

echo "Coba buat Struktur Data!<br><br>";

//nama file
$name = "Testcase.txt"; // nama file

//open file
$file = fopen($name,"r");

//delete "Ruangan"
$huruf = fread($file,9);

//variabel
$arrayFile = array();

//start scanning ruang
while (!feof($file)) {
    $huruf = fread($file,1);

    //take kata
    $kata = "";
    while (($huruf!==";")and($huruf!=="\n")and(!feof($file))) {
        $kata = $kata . $huruf;
        $huruf = fread($file,1);
    }

    //cek termiasi ruangan (kalo enter, berarti udh ganti ke list matkul)
    $testing = strlen($kata);
    if ($testing==1)
        break;

    //proses constraint
    //print("$kata<br>");
    array_push($arrayFile,$kata);
}

$jmlRuangan = count($arrayFile) / 4;

//echo $jmlRuangan . "<br>";

//delete "Jadwal"
$huruf = fread($file,8);

//start scanning matkul
while (!feof($file)) {
    $huruf = fread($file,1);

    //take kata
    $kata = "";
    while (($huruf!==";")and($huruf!=="\n")and(!feof($file))) {
        $kata = $kata . $huruf;
        $huruf = fread($file,1);
    }

    //proses constraint
    //print("$kata<br>");
    array_push($arrayFile,$kata);
}

$jmlMatkul = (count($arrayFile) - ($jmlRuangan*4)) / 6;

//echo $jmlMatkul . "<br>";

//close file
fclose($file);

echo "Beres baca file!!<br>";

$indexRuangan = array();
for ($i=0;$i<$jmlRuangan;$i++) {
    array_push($indexRuangan,$arrayFile[$i*4]);
}

//echo $indexRuangan[2] . "<br>";

$indexMatkul = array();
for ($i=0;$i<$jmlMatkul;$i++) {
    array_push($indexMatkul,$arrayFile[$jmlRuangan*4+$i*6]);
}

//echo $indexMatkul[2] . "<br>";

//fungsi untuk membantu
function getIndex($hari,$waktu)
//mengembalikan nilai index pada array untuk kode hari dan waktu start tertentu
{
    return(($hari-1)*11+($waktu-7));
}

//buat array step 1
$arrayRuangan = array();
for ($i=0;$i<$jmlRuangan;$i++)
    array_push($arrayRuangan,array());

//buat array step 2
for ($i=0;$i<$jmlRuangan;$i++)
    for ($j=0;$j<$jmlMatkul+1;$j++)
        array_push($arrayRuangan[$i],array());

//buat array step 3
for ($i=0;$i<$jmlRuangan;$i++)
    for ($j=0;$j<$jmlMatkul+1;$j++)
        for ($k=0;$k<55;$k++)
            array_push($arrayRuangan[$i][$j],array());

//buat array step 4
for ($i=0;$i<$jmlRuangan;$i++)
    for ($j=0;$j<$jmlMatkul+1;$j++)
        for ($k=0;$k<55;$k++)
            array_push($arrayRuangan[$i][$j][$k],array());

//inisialisasi
//semua diisi 0 (false)
for ($i=0;$i<$jmlRuangan;$i++)
    for ($j=0;$j<$jmlMatkul+1;$j++)
        for ($k=0;$k<55;$k++) {
            $arrayRuangan[$i][$j][$k][0] = 0; $arrayRuangan[$i][$j][$k][1] = 0; }


//masukkin constraint Ruangan
for ($i=0;$i<$jmlRuangan;$i++) {
    $waktu = $arrayFile[$i*4+1];
    $waktuAkhir = $arrayFile[$i*4+2];
    $durasi = $waktuAkhir-$waktu;
    //echo $durasi . "<br>";
    $listHari = $arrayFile[$i*4+3];
    $availableDays = strlen($listHari) / 2;
    for ($j=0;$j<$availableDays;$j++) {
        $hari = substr($listHari,$j*2,1);
        for ($k=0;$k<$durasi;$k++)
            $arrayRuangan[$i][$jmlMatkul][getIndex($hari,$waktu)+$k][0] = 1;
    }
}

//echo $arrayRuangan[1][8][54][0]&&$arrayRuangan[1][8][6][0] . "<br>";
//echo $arrayRuangan[1][8][54][0]||$arrayRuangan[1][8][6][0] . "<br>";


//masukkan slot yang dibolehin (boolean ke-0)
for ($i=0;$i<$jmlMatkul;$i++) {
    $ruangan = $arrayFile[$jmlRuangan*4+$i*6+1];
    $waktu = $arrayFile[$jmlRuangan*4+$i*6+2];
    $waktuAkhir = $arrayFile[$jmlRuangan*4+$i*6+3];
    $durasi = $waktuAkhir-$waktu;
    $listHari = $arrayFile[$jmlRuangan*4+$i*6+5];
    $availableDays = strlen($listHari) / 2;
    for ($j=0;$j<$availableDays;$j++) {
        $hari = substr($listHari,$j*2,1);
        if ($ruangan=="-") {
            for ($k=0;$k<$durasi;$k++)
                for ($l=0;$l<$jmlRuangan;$l++)
                    if ($arrayRuangan[$l][$jmlMatkul][getIndex($hari,$waktu)+$k][0] == 1)
                        $arrayRuangan[$l][$i][getIndex($hari,$waktu)+$k][0] = 1;
        } else {
            $idxRuang = array_search($ruangan,$indexRuangan);
            for ($k=0;$k<$durasi;$k++)
                if ($arrayRuangan[$idxRuang][$jmlMatkul][getIndex($hari,$waktu)+$k][0] == 1)
                    $arrayRuangan[$idxRuang][$i][getIndex($hari,$waktu)+$k][0] = 1;
        }
    }
}


//Karena local search itu complete, jadi diisi yak (ini bukan "random", ini gw pepetin aja di ruang dan waktu paling awal)
for ($i=0;$i<$jmlMatkul;$i++) {
    $durasiKelas = $arrayFile[$jmlRuangan*4+$i*6+4];
    for ($k=0;$k<$durasiKelas;$k++)
            $arrayRuangan[0][$i][$k][1] = 1;
}

//Dah, dengan ini $arrayFile sudah tidak dibutuhkan. Selesaaai!!!!


//FUNGSI-FUNGSI YANG "MUNGKIN" DIBUTUHKAN

//fungsi pengecek kesalahan (kesalahan adalah mengalokasikan matkul di jadwal yang seharusnya tidak ada)
function cekKesalahan($arrayRuangan,$jmlRuangan,$jmlMatkul) {
    $test = 0;
    for ($i=0;$i<$jmlRuangan;$i++)
        for ($j=0;$j<$jmlMatkul+1;$j++)
            for ($k=0;$k<55;$k++)
                if (($arrayRuangan[$i][$j][$k][0]==0) and ($arrayRuangan[$i][$j][$k][1]==1))
                    $test++;
    return($test);
}

//echo cekKesalahan($arrayRuangan,$jmlRuangan,$jmlMatkul);

//fungsi cek bentrok (jumlah bentrok di suatu ruangan)
function cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$namaRuangan,$indexRuangan) {
    $idxRuangan = array_search($namaRuangan,$indexRuangan);
    $count = 0;
    for ($j=0;$j<$jmlMatkul+1;$j++) {
        $count1 = 0;
        for ($k=0;$k<55;$k++)
            $count1 += $arrayRuangan[$idxRuangan][$j][$k][1];
        if ($count1>1)
            $count += $count1-1;
    }
    return($count);
}

function persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,$namaRuangan,$indexRuangan) {
    $idxRuangan = array_search($namaRuangan,$indexRuangan);
    $terisi = 0;
    for ($j=0;$j<$jmlMatkul+1;$j++)
        for ($k=0;$k<55;$k++)
            $terisi += $arrayRuangan[$idxRuangan][$j][$k][1];
    //echo $terisi . "<br>";

    $total = 0;
    for ($k=0;$k<55;$k++)
        $total += $arrayRuangan[$idxRuangan][$jmlMatkul][$k][0];
    //echo $total . "<br>";

    return(($terisi + 0.0)/$total);
}


//============FUNGSI-FUNGSI SA==============================================

//Variabel Global
$arrayTarget = array();//Isi: domain (waktu&tempat)
$arrayMJ = array();//Isi: variabel mk-durasi MJ[][0]=durasi
$tuple = array();//Isi: 0-tempat; 1- waktu
//$langkah = 5000;
$T = 1000;

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
	$energy = 0;
	for($k=0;$k<sizeof($indexRuangan);$k++){
		$energy += cekBentrok($arrayRuangan,sizeof($indexRuangan),sizeof($indexMatkul),$indexRuangan[$k],$indexRuangan);
	}
	$energy+=cekKesalahan($arrayRuangan,sizeof($indexRuangan),sizeof($indexMatkul));
	return ($energy);
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
$arrayRuangan = SimAnneling($T, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget, $tuple, $arrayMJ);

//passing
session_start();
	$_SESSION["arrayRuangan"] = $arrayRuangan; 
	$_SESSION["indexRuangan"] = $indexRuangan;
	$_SESSION["indexMatkul"] = $indexMatkul;
	header("Location: Result.php");
 exit();
?>
