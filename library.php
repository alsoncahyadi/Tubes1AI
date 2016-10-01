<?php

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

?>