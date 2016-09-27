<?php

ini_set('max_execution_time', 0);
include "readfile.php";
include "library.php";

//echo persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,"7602",$indexRuangan);

//oiya, sorry kalo banyak echo maupun print, masih di komentar soalnya buat debugging...

echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7602",$indexRuangan) . "<br>";
//echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7603",$indexRuangan) . "<br>";
//echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7610",$indexRuangan) . "<br>";
//echo cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"Labdas2",$indexRuangan) . "<br>";

//echo persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,"7602",$indexRuangan);

//oiya, sorry kalo banyak echo maupun print, masih di komentar soalnya buat debugging...



//Penjelasan cara ngakses Data
//Seluruh Data ada di $arrayRuangan

echo "<br>Ruangan ke-0, matkul ke-0, apakah ada di waktu ke-0 = " . $arrayRuangan[0][0][0][1] . "<br>"; //ini kalo tau indexnya
echo "Ruangan 7602, hari ke-3, waktu 9.00, apakah ada IF2150? " . $arrayRuangan[array_search("7602",$indexRuangan)][array_search("IF2150",$indexMatkul)][getIndex(3,"9.00")][1] . "<br>";
echo "Ruangan 7602, hari ke-3, waktu 9.00, apakah boleh diisi IF2150? " . $arrayRuangan[array_search("7602",$indexRuangan)][array_search("IF2150",$indexMatkul)][getIndex(3,"9.00")][0] . "<br>";
echo "Ruangan Labdas2, hari ke-4, waktu 13.00, apakah ada IF3130? " . $arrayRuangan[array_search("Labdas2",$indexRuangan)][array_search("IF3130",$indexMatkul)][getIndex(4,"13.00")][1] . "<br>";
echo "Ruangan Labdas2, hari ke-4, waktu 13.00, apakah boleh diisi IF3130? " . $arrayRuangan[array_search("Labdas2",$indexRuangan)][array_search("IF3130",$indexMatkul)][getIndex(4,"13.00")][0] . "<br>";

echo "<br><br>.<br>.<br>.<br><br>";


//fungsi-fungsi pendukung!!!

//count matkul
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

//prosedur geser ruangan
//prekondisi : array memang cukup untuk bergeser sejauh $geser
function moveRuanganPlus(&$arrayRuangan,$jmlRuangan,$idxMatkul,$geser) {
    $lokasiMatkul = findRuanganMatkul($arrayRuangan,$idxMatkul,$jmlRuangan);
    $waktuMatkul = findWaktuMatkul($arrayRuangan,$idxMatkul,$lokasiMatkul);
    $jumlahJam = countMatkul($arrayRuangan,$lokasiMatkul,$idxMatkul);

    //delete
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul+$i][1] = 0;
            
    //insert
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul+$geser][$idxMatkul][$waktuMatkul+$i][1] = 1;

    return;
}

//prosedur geser ruangan
//prekondisi : array memang cukup untuk bergeser sejauh $geser
function moveRuanganMinus(&$arrayRuangan,$jmlRuangan,$idxMatkul,$geser) {
    $lokasiMatkul = findRuanganMatkul($arrayRuangan,$idxMatkul,$jmlRuangan);
    $waktuMatkul = findWaktuMatkul($arrayRuangan,$idxMatkul,$lokasiMatkul);
    $jumlahJam = countMatkul($arrayRuangan,$lokasiMatkul,$idxMatkul);

    //delete
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul+$i][1] = 0;
    
    //insert
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul-$geser][$idxMatkul][$waktuMatkul+$i][1] = 1;

    return;
}

//prosedur geser waktu
//prekondisi : array memang cukup untuk bergeser sejauh $geser
function moveWaktuPlus(&$arrayRuangan,$jmlRuangan,$idxMatkul,$geser) {
    $lokasiMatkul = findRuanganMatkul($arrayRuangan,$idxMatkul,$jmlRuangan);
    $waktuMatkul = findWaktuMatkul($arrayRuangan,$idxMatkul,$lokasiMatkul);
    $jumlahJam = countMatkul($arrayRuangan,$lokasiMatkul,$idxMatkul);

    //delete
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul+$i][1] = 0;
    
    //insert
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul+$geser+$i][1] = 1;

    return;
}

//prosedur geser waktu
//prekondisi : array memang cukup untuk bergeser sejauh $geser
function moveWaktuMinus(&$arrayRuangan,$jmlRuangan,$idxMatkul,$geser) {
    $lokasiMatkul = findRuanganMatkul($arrayRuangan,$idxMatkul,$jmlRuangan);
    $waktuMatkul = findWaktuMatkul($arrayRuangan,$idxMatkul,$lokasiMatkul);
    $jumlahJam = countMatkul($arrayRuangan,$lokasiMatkul,$idxMatkul);

    //delete
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul+$i][1] = 0;
    
    //insert
    for ($i=0;$i<$jumlahJam;$i++)
        $arrayRuangan[$lokasiMatkul][$idxMatkul][$waktuMatkul-$geser+$i][1] = 1;

    return;
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
    
    $indexLokasi = array_search($indexMatkul[$idxMatkul],$arrayFile)+1;
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
function nextStep (&$arrayRuangan,$jmlMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile) {
    for ($i=0;$i<$jmlMatkul;$i++)
        randomMatkul($arrayRuangan,$i,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile);

    return;
}


//Hill Climbing
//randomRestart
nextStep($arrayRuanganTemp,$jmlMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile);

$jmlBentrok = cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);
$jmlLangkah = 0;
$arrayRuanganTemp = $arrayRuangan;
$jmlLangkahMax = 1000;

while (($jmlLangkah < $jmlLangkahMax) and ($jmlBentrok!==0)) {
    nextStep($arrayRuanganTemp,$jmlMatkul,$jmlRuangan,$indexMatkul,$indexRuangan,$arrayFile);
    $jmlBentrokTemp = cekAllBentrok($arrayRuanganTemp,$jmlRuangan,$jmlMatkul,$indexRuangan);

    if ($jmlBentrokTemp <= $jmlBentrok) {
        $arrayRuangan = $arrayRuanganTemp;
        if($jmlBentrokTemp < $jmlBentrok)
            $jmlLangkah = 0;
        $jmlBentrok = cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);
    }

    $jmlLangkah++;
    //echo "Jumlah bentrok = " . $jmlBentrok . " ; Iterasi ke-" . $jmlLangkah  . "<br>";
}

$jmlBentrok = cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);

//echo "<br>Kesalahan : " . cekKesalahan($arrayRuangan,$jmlRuangan,$jmlMatkul) . "<br>";
//echo "Bentrok : "  . $jmlBentrok . "<br>";
//echo "Langkah : " . $jmlLangkah . "<br>";


session_start();
$_SESSION["arrayRuangan"] = $arrayRuangan;
$_SESSION["indexRuangan"] = $indexRuangan;
$_SESSION["indexMatkul"] = $indexMatkul;
$_SESSION["jmlBentrok"] = $jmlBentrok;

/*

$arrayRuangan
    -> array of Matkul + 1 (+1 nya ga akan dipakai)
        -> array of Time
            -> 2 boolean [0] dan [1]
               [0] gaboleh diubah (constraint)
               [1] yang dipindah2in ()

VARIABEL    => <Jam, Hari> di seluruh ruangan
DOMAIN      => Matkul
CONSTRAINT  => 
    (BELUM LENGKAP) Membatasi waktu bisa diisi sama ruangan apa

function getIndex($hari,$waktu)
array_search($ruangan,$indexRuangan)
*/
$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$url .= $_SERVER['SERVER_NAME'];
$url .= $_SERVER['REQUEST_URI'];

header("Location: " . dirname($url) . "/result.php");
die();

?>
