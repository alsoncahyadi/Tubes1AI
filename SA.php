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

//echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7602",$indexRuangan) . "<br>";
//echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7603",$indexRuangan) . "<br>";
//echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7610",$indexRuangan) . "<br>";
//echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"Labdas2",$indexRuangan) . "<br>";

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

function cekBentrokRJ($arrayRuangan, $indexMatkul, $ruangan, $waktu){
	$jmlcrash=-1;
	for ($i=0;$i<sizeof($indexMatkul);$i++){
		$jmlcrash += $arrayRuangan[$ruangan][$i][$waktu][1];
	}
	return $jmlcrash;
}

//============FUNGSI-FUNGSI SA==============================================

//Variabel Global
$arrayTarget = array();//Isi: domain (waktu&tempat)
$arrayMJ = array();//Isi: variabel mk-durasi MJ[][0]=durasi
$tuple = array();//Isi: 0-tempat; 1- waktu
$T = 500;

function generateRandomStart($arrayRuangan, $indexMatkul, $indexRuangan, $arrayMJ, $arrayTarget){
	for ($i=0; $i<sizeof($indexRuangan);$i++){//cleanse
		for ($j=0; $j< sizeof($indexMatkul); $j++){
			for ($k=0; $k< sizeof($arrayRuangan[$i][$j]); $k++){
				$arrayRuangan[$i][$j][$k][1]=0;
			}
		}
	}
	
	for ($i=0;$i<sizeof($indexMatkul);$i++){
		$arrayTarget = collectDomain($arrayRuangan, $i, $indexRuangan, $arrayTarget);
		//waktu&tempat random
		$randVal = rand(0, sizeof($arrayTarget[$i]));
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

function checkRepeated($arrayA){
	$i =0;
	$same =true;
	
	while ($i<sizeof($arrayA)&& ($same==true)){
		$j=$i+1;
		while ($j<sizeof($arrayA) && ($same==true)){
			if ($arrayA[$i]==$arrayA[$j]) $same =false;
			else $j++;
		}
		$i++;
	}
	return $same;
}

function varMat_Jam($arrayMJ, $arrayRuangan, $indexMatkul){
	$k=0;
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

function countEnergy($arrayRuangan, $indexMatkul, $indexRuangan){
	$energy = 0;
	for($k=0;$k<sizeof($indexRuangan);$k++){
		$energy += cekBentrok($arrayRuangan,sizeof($indexRuangan),sizeof($indexMatkul),$indexRuangan[$k],$indexRuangan);
	}
	$energy+=cekKesalahan($arrayRuangan,sizeof($indexRuangan),sizeof($indexMatkul));
	return ($energy);
}

function collectDomain($arrayRuangan, $idxMatkul, $indexRuangan, $arrayTarget){
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

function countProb($temperature, $frmE, $crrE){
	return exp(-($crrE-$frmE)/$temperature);
}

function decision($T, $arrayRuangan, $idxMatkul, $idxRuangan, $waktu, $indexMatkul, $indexRuangan, $tuple, $arrayMJ){
	//save former state
	$formerEnergy 	= countEnergy($arrayRuangan, $indexMatkul, $indexRuangan);
	$tuple = searchPosition($arrayRuangan, $indexRuangan, $idxMatkul, $tuple);
	$formerPlace = $tuple[0];
	$formerTime = $tuple[1];
	
	//trial-move
	$arrayRuangan = moveMatkul($idxRuangan, $waktu, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
	$currEnergy 	= countEnergy($arrayRuangan, $indexMatkul, $indexRuangan);
	
	if ($formerEnergy < $currEnergy)
	{
		echo "cuy";
		if (countProb($T,$formerEnergy,$currEnergy)*10 < rand(0,10)){
		$arrayRuangan = moveMatkul($formerPlace, $formerTime, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);
		echo "uno";
		}else echo "dos";
		
	}else echo "achoo";
	//turunin suhu
	return $arrayRuangan;
}

function searchPosition($arrayRuangan, $indexRuangan, $idxMatkul, $tuple){
	
	$found=false;
	$i = 0;
	$waktu =0 ;
	$batas = 55;
	
	for ($k=0;$k<sizeof($indexRuangan); $k++){//telusurin ruangan
		for ($l=0; $l<sizeof($arrayRuangan[$k][$idxMatkul]); $l++){//telusurin waktu
			
			if ($arrayRuangan[$k][$idxMatkul][$l][1] == 1 && $found==false){
				$found = true;
				$i=$k;
				$waktu = $l;
			}
		}
	}
	$tuple[0]=$i;
	$tuple[1]=$waktu;
	return $tuple;
	
}

function moveMatkul($tTempat, $tWaktu, $idxMatkul, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul){
	
	$tuple = searchPosition($arrayRuangan, $indexRuangan, $idxMatkul, $tuple);
	$iterasi = $tuple[1];
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

function SimAnneling($T, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget, $tuple, $arrayMJ){
	$currMatkul = rand(0, sizeof($indexMatkul));
	$tuple = searchPosition($arrayRuangan, $indexRuangan, $currMatkul, $tuple);
	$step = 0;//posisi waktu target;
	
	while ($T!=0) {
		$arrayTarget = collectDomain($arrayRuangan, $currMatkul, $indexRuangan, $arrayTarget);//cari domain tempat&waktu
		$step = rand(0, sizeof($arrayTarget)-1);//nyari tempat&waktu
		
		$arrayRuangan = decision($T, $arrayRuangan, $currMatkul, $arrayTarget[$step][0], $arrayTarget[$step][1],  $indexMatkul, $indexRuangan, $tuple, $arrayMJ);

		$currMatkul = rand(0, sizeof($indexMatkul)-1);

		unset($arrayTarget);
		$arrayTarget = array();
		$T -= 1;
	}
	return $arrayRuangan;	
}


$arrayMJ = varMat_Jam($arrayMJ, $arrayRuangan, $indexMatkul);

$arrayRuangan = generateRandomStart($arrayRuangan, $indexMatkul, $indexRuangan, $arrayMJ, $arrayTarget);

//$arrayRuangan = moveMatkul(1, 4, 1, $arrayMJ, $arrayRuangan, $indexRuangan, $tuple, $indexMatkul);

$tuple = searchPosition($arrayRuangan, $indexRuangan, 1, $tuple);

echo $indexMatkul[1]." ".$indexRuangan[$tuple[0]]." ".$tuple[1]."<br>";

for ($i=0;$i<sizeof($arrayMJ);$i++){
	echo "<br>".$indexMatkul[$i]." - ".$arrayMJ[$i];
}

echo "<br>";
$arrayRuangan = SimAnneling($T, $arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget, $tuple, $arrayMJ);
//$arrayRuangan = decision(1000, $arrayRuangan, 1, 2, 24, $indexMatkul, $indexRuangan, $tuple, $arrayMJ);

echo "<br>".countEnergy($arrayRuangan, $indexMatkul, $indexRuangan);

session_start();
	$_SESSION["arrayRuangan"] = $arrayRuangan; 
	$_SESSION["indexRuangan"] = $indexRuangan;
	$_SESSION["indexMatkul"] = $indexMatkul;
	header("Location: Result.php");
 exit();

	

	
	
	
	
	
	
///Exe------------------------------------------------------------------------------
/*$arrayMJ = varMat_Jam($arrayMJ, $arrayRuangan, $indexMatkul, $indexRuangan);
generateRandomStart($arrayRuangan, $indexMatkul, $indexRuangan, $arrayMJ)

echo "<br>";
for ($i=0;$i<sizeof($indexMatkul);$i++){
	echo $indexMatkul[$i]." : ";
	//for ($j=0 ; $j<sizeof($arrayMJ[$i]) ; $j++){
		echo $arrayMJ[$i][0];
	//}
	echo "<br>";
}
*/


/*echo "<br>Ruangan ke-0, matkul ke-0, apakah ada di waktu ke-0 = " . $arrayRuangan[0][0][0][1] . "<br>"; //ini kalo tau indexnya
echo "Ruangan 7602, hari ke-3, waktu 9.00, apakah ada IF2150? " . $arrayRuangan[array_search("7602",$indexRuangan)][array_search("IF2150",$indexMatkul)][getIndex(3,"9.00")][1] . "<br>";

echo "Ruangan 7602, hari ke-3, waktu 9.00, apakah boleh diisi IF2150? " . $arrayRuangan[array_search("7602",$indexRuangan)][array_search("IF2150",$indexMatkul)][getIndex(3,"9.00")][0] . "<br>";

echo "Ruangan Labdas2, hari ke-4, waktu 13.00, apakah ada IF3130? " . $arrayRuangan[array_search("Labdas2",$indexRuangan)][array_search("IF3130",$indexMatkul)][getIndex(4,"13.00")][1] . "<br>";

echo "Ruangan Labdas2, hari ke-4, waktu 13.00, apakah boleh diisi IF3130? " . $arrayRuangan[array_search("Labdas2",$indexRuangan)][array_search("IF3130",$indexMatkul)][getIndex(4,"13.00")][0] . "<br>";

$arrayRuangan = SimAnneling($T,$arrayRuangan, $indexMatkul, $indexRuangan, $arrayTarget);
$jmlRuangan = sizeof($indexRuangan);
$jmlMatkul = sizeof($indexMatkul);
echo "<br><br>7602 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7602",$indexRuangan) . "<br>";
echo "7603 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7603",$indexRuangan) . "<br>";
echo "7610 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7610",$indexRuangan) . "<br>";
echo "Labdas 2 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"Labdas2",$indexRuangan) . "<br>";
*/

//echo countEnergy($arrayRuangan, $indexMatkul, $indexRuangan);

?>