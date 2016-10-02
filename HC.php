<?php

ini_set('max_execution_time', 0);
include "readfile.php";
include "library.php";

//Fungsi-fungsi pendukung Hill Climbing!!

//count matkul
// mengembalikan durasi matkul
function countMatkul($arrayRuangan,$idxRuangan,$idxMatkul) {
    $count = 0;
    for ($i=0;$i<55;$i++) {
        $count += $arrayRuangan[$idxRuangan][$idxMatkul][$i][1];
    }
    return($count);
}


//fungsi find matkul
//mengembalikan idxRuangan
function findRuanganMatkul($arrayRuangan,$idxMatkul,$jmlRuangan) {
    $lokasi = 0;
    $found = 0;
    while (($lokasi<$jmlRuangan) and (!$found)) {
        if (countMatkul($arrayRuangan,$lokasi,$idxMatkul)!==0)
            $found = 1;
        else $lokasi++;
    }

    if ($found)
        return ($lokasi);
    else return (-1);
}

//fungsi yang mengembalikan waktu start matkul di ruangan tsb
function findWaktuMatkul($arrayRuangan,$idxMatkul,$idxRuangan) {
    $waktu = 0;
    $found = 0;
    while (($waktu<55) and (!$found)) {
        if ($arrayRuangan[$idxRuangan][$idxMatkul][$waktu][1])
            $found = 1;
        else $waktu++;
    }

    if ($found)
        return ($waktu);
    else return (-1);

}


//fungsi can Allocate
function canAllocate($arrayRuangan,$idxMatkul,$lokasiMatkul,$waktuMatkul,$jumlahJam) {
    if ($waktuMatkul+$jumlahJam>55)
        return 0;
    else {
        $test = 0;
        for ($i=0;$i<$jumlahJam;$i++)
            if ($arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul+$i][0]==1)
                $test++;

        if ($test==$jumlahJam)
            return 1;
        else return 0;
    }
}

//prosedur yang meletakkan Matkul di lokasi dan waktu start yang random
//prekondisi : matkul memang harus bisa dialokasikan
function randomMatkul(&$arrayRuangan,$idxMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile) {
    $lokasiMatkul = findRuanganMatkul($arrayRuangan,$idxMatkul,$jmlRuangan);
    $waktuMatkul = findWaktuMatkul($arrayRuangan,$idxMatkul,$lokasiMatkul);
    $jumlahJam = countMatkul($arrayRuangan,$lokasiMatkul,$idxMatkul);

    //delete
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul+$i][1] = 0;
    
    $indexLokasi = $jmlRuangan*4+$idxMatkul*6+1;
    $ruangan = $arrayFile[$indexLokasi];

    if ($ruangan=="-")
        $newLokasi = rand(0,$jmlRuangan-1);
    else $newLokasi = array_search($ruangan,$indexRuangan);
    $newWaktu = rand(0,55-1);

    while (!canAllocate($arrayRuangan,$idxMatkul,$newLokasi,$newWaktu,$jumlahJam)) {
        if ($ruangan=="-")
            $newLokasi = rand(0,$jmlRuangan-1);
        $newWaktu = rand(0,55-1);
    }

    //insert
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$newLokasi][$idxMatkul][$newWaktu+$i][1] = 1;

    return;
}

//prosedur random restart
function startRandom (&$arrayRuangan,$jmlMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile) {
    for ($i=0;$i<$jmlMatkul;$i++)
        randomMatkul($arrayRuangan,$i,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile);

    return;
}

//prosedur nextStep
function nextStep (&$arrayRuangan,$jmlMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile) {
    $i = rand(0,$jmlMatkul-1);
    randomMatkul($arrayRuangan,$i,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile);

    return;
}


//Hill Climbing Algorithm
//Start (karena awalnya blom di tempat yang bener, di random)
startRandom($arrayRuanganTemp,$jmlMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile);

//hitung jumlah bentrok
$jmlBentrok = cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);

//init jumlah langkah
$jmlLangkah = 0;

//buat temporary untuk next step
$arrayRuanganTemp = $arrayRuangan;

//jumlah langkah/iterasi max (input dari user)
$jmlLangkahMax = $_POST["iterMax"];

//looping
while (($jmlLangkah < $jmlLangkahMax) and ($jmlBentrok!==0)) {
    //nextStep
    nextStep($arrayRuanganTemp,$jmlMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile);

    //cek jumlah bentroknya
    $jmlBentrokTemp = cekAllBentrok($arrayRuanganTemp,$jmlRuangan,$jmlMatkul,$indexRuangan);

    if ($jmlBentrokTemp <= $jmlBentrok) {
        //kalau lebih baik atau sama dengan dari sebelumnya, move
        $arrayRuangan = $arrayRuanganTemp;
        if($jmlBentrokTemp < $jmlBentrok)
            //kalau lebih baik, iterasi mulai dari 0 lagi
            $jmlLangkah = 0;
        //update jmlBentrok
        $jmlBentrok = cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);
    }

    //increment jmlh langkah
    $jmlLangkah++;
}

$jmlBentrok = cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);



session_start();
$_SESSION["arrayRuangan"] = $arrayRuangan;
$_SESSION["indexRuangan"] = $indexRuangan;
$_SESSION["indexMatkul"] = $indexMatkul;
$_SESSION["jmlBentrok"] = $jmlBentrok;


$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$url .= $_SERVER['SERVER_NAME'];
$url .= $_SERVER['REQUEST_URI'];

header("Location: " . dirname($url) . "/result.php");
die();

?>
