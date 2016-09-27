<?php

ini_set('max_execution_time', 0);
//Di run di google chrome
//pastiin ada Testcase.txt. Kalo beda directory, edit-edit aja



//echo "Coba buat Struktur Data!<br><br>";

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

//echo "Beres baca file!!<br>";

//echo count($arrayFile) . "<br>";

//semua hal yg berhubungan dengan file sudah ditampung di array file


/*
//nyoba aja
for ($i=1;$i<=$jmlRuangan;$i++) {
    echo strlen($arrayFile[($i-1)*4+3]) . "<br>";
    echo substr($arrayFile[($i-1)*4+3],0,1)+5 . "<br>";
}
*/

//buat Struktur Data
//Dibuat array index untuk nama ruangan dan nama matkul

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

/*
INDEX INDEX INDEX INDEX : Penjelasan tentang index
$indexRuangan sama $indexMatkul untuk memudahkan dalam indexing
KALO MAU TAU index sekian itu apa ruangannya atau apa matkulnya, tinggal akses arraynya : $indexSesuatu[sesuatu]
KALO MAU TAU suatu nama matkul atau suatu nama ruangan itu ada di index berapa, tinggal search : array_search("Sesuatu",$indexSesuatu)

STRUKTUR DATA STRUKTUR DATA STRUKTUR DATA : Penjelasan tentang struktur data singkat
Jadi, dibuat array yang isinya $jmlRuangan Elemen (step 1)
Elemen tersebut adalah array yang isinya $jmlMatkul+1 Elemen (step 2)
        elemen terakhir berisi array of boolean (yang dipake boolean ke 0 aja, boolean ke 1 bodo amat) contraint keberadaan ruangan itu
Elemen tersebut adalah array yang isinya 55 (1 hari ada 11 jam kemungkinan) elemen (step 3)
Elemen tersebut adalah array yang isinya 2 boolean (step 4)
Boolean ke-0 menunjukkan apakah lokasi dan waktu tersebut diperbolehkan untuk matkul tersebut
(true berarti boleh, false berarti gaboleh (ga ada slot))
Boolean ke-1 menunjukkan slot yang ditempatin, INI YANG HARUS DIPINDAH-PINDAH.
(true berarti ditempatin di situ, false berarti g diisi)

4th dimensional array!!! wakakakak
Kenapa boolean, karena mindah2in lebih gampang daripada kalo dibuat string (menurut gw)
Ngabisis banyak bet space?? Ngabisin banyak waktu?? Keknya bukan kiteria penilaian

*/

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


//array selesai dibuat dan diinisialisasi!!!


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
                    $arrayRuangan[$l][$i][getIndex($hari,$waktu)+$k][0] = 1;
        } else {
            $idxRuang = array_search($ruangan,$indexRuangan);
            for ($k=0;$k<$durasi;$k++)
                $arrayRuangan[$idxRuang][$i][getIndex($hari,$waktu)+$k][0] = 1;
        }
    }
}

//echo $arrayRuangan[0][0][44][0] . "<br>";
//echo $arrayRuangan[0][0][48][0] . "<br>";
//echo $arrayRuangan[0][0][49][0] . "<br>";
//echo $arrayRuangan[2][0][44][0] . "<br>";
//echo $arrayRuangan[3][1][26][0] . "<br>";


//nah, sekarang statusnya masih kosong

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

//fungsi kombinasi2 (untuk menghitung jumlah bentrok)
function kombinasi2($x) {
    if ($x==2)
        return 1;
    else return $x-1+kombinasi2($x-1);
}

//fungsi cek bentrok (jumlah bentrok di suatu ruangan)
function cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$namaRuangan,$indexRuangan) {
    $idxRuangan = array_search($namaRuangan,$indexRuangan);
    $count = 0;
    for ($k=0;$k<55;$k++) {
        $count1 = 0;
        for ($j=0;$j<$jmlMatkul;$j++)
            $count1 += $arrayRuangan[$idxRuangan][$j][$k][1];
        if ($count1>1)
            $count += kombinasi2($count1);
    }
    return($count);
}

//fungsi cek All bentrok (jumlah bentrok di suatu ruangan)
function cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan) {
    
    $total = 0;
    for ($idxRuangan=0;$idxRuangan<$jmlRuangan;$idxRuangan++) {
        $count = 0;
        for ($k=0;$k<55;$k++) {
            $count1 = 0;
            for ($j=0;$j<$jmlMatkul;$j++)
                $count1 += $arrayRuangan[$idxRuangan][$j][$k][1];
            if ($count1>1)
                $count += kombinasi2($count1);
        }
        $total += $count;
    }
    return($total);
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

header("Location: /AI/result.php");
die();

?>
