<?php
	/*session_start();
	$arrayRuangan = $_SESSION["arrayRuangan"];
	$indexMatkul = $_SESSION["indexMatkul"];
	$indexRuangan = $_SESSION["indexRuangan"];
	$jmlRuangan = $_SESSION["jmlRuangan"];
	$jmlMatkul = $_SESSION["jmlMatkul"];
	*/
	include "StrukturData(1).php";

	//Function untuk randomize
	function randomize($min, $max, $add){
		$sample = range($min, $max - 1, $add);
		shuffle($sample);
		return($sample);
	}

	//Function untuk nampilin array
	function prtArray($array){
		echo "<br>";
		foreach($array as $number){
			echo $number . " ";
		}
		return;
	}

	//for testing default
	/*
	for ($i=0;$i<$jmlMatkul;$i++) {
	    $durasiKelas = $arrayFile[$jmlRuangan*4+$i*6+4];
	    for ($k=0;$k<$durasiKelas;$k++)
	            $arrayRuangan[0][$i][$k][1] = 1;
	        break;
		}
	*/

///BANYAKNYA POPULASI YANG MAU DI GENERATE
	$pop = 4;
///

///PEMBUATAN ARRAY UNTUK POPULASI (LEL, COPAS)
	//buat array step 1
	$arrayPop = array();
	for ($i=0;$i<$pop;$i++)
	    array_push($arrayPop,array());

	//buat array step 2
	for ($i=0;$i<$pop;$i++)
	    for ($j=0;$j<$jmlRuangan+1;$j++)
	        array_push($arrayPop[$i],array());

	//buat array step 3
	for ($i=0;$i<$pop;$i++)
	    for ($j=0;$j<$jmlRuangan+1;$j++)
	        for ($k=0;$k<$jmlMatkul;$k++)
	            array_push($arrayPop[$i][$j],array());

	//buat array step 4
	for ($i=0;$i<$pop;$i++)
	    for ($j=0;$j<$jmlRuangan+1;$j++)
	        for ($k=0;$k<$jmlMatkul;$k++)
	        	for ($l=0;$l<55;$l++)
	            	array_push($arrayPop[$i][$j][$k],array());

	//buat array step 5
	for ($i=0;$i<$pop;$i++)
	    for ($j=0;$j<$jmlRuangan+1;$j++)
	        for ($k=0;$k<$jmlMatkul;$k++)
	        	for ($l=0;$l<55;$l++)
	            	array_push($arrayPop[$i][$j][$k][$l],array());

	//init
	for($h=0;$h<$pop;$h++)
		for ($i=0;$i<$jmlRuangan;$i++)
	    	for ($j=0;$j<$jmlMatkul+1;$j++)
	        	for ($k=0;$k<55;$k++) {
	            	$arrayPop[$h][$i][$j][$k][0] = 0; $arrayPop[$h][$i][$j][$k][1] = 0; }

	//masukkin constraint Ruangan
	for($h=0;$h<$pop;$h++)
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
		            $arrayPop[$h][$i][$jmlMatkul][getIndex($hari,$waktu)+$k][0] = 1;
		    }
		}

	//masukkan slot yang dibolehin (boolean ke-0)
	for($h=0;$h<$pop;$h++)
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
		                    if ($arrayPop[$h][$l][$jmlMatkul][getIndex($hari,$waktu)+$k][0] == 1)
		                        $arrayPop[$h][$l][$i][getIndex($hari,$waktu)+$k][0] = 1;
		        } else {
		            $idxRuang = array_search($ruangan,$indexRuangan);
		            for ($k=0;$k<$durasi;$k++)
		                if ($arrayPop[$h][$idxRuang][$jmlMatkul][getIndex($hari,$waktu)+$k][0] == 1)
		                    $arrayPop[$h][$idxRuang][$i][getIndex($hari,$waktu)+$k][0] = 1;
		        }
		    }
		}
///

///GENERATE POPULASI
	for($x=0;$x<$pop;$x++){

		//Random 9-13
		$i = rand(0,$jmlRuangan-1);
		$j = 2;
		$durasiKelas = $arrayFile[$jmlRuangan*4+$j*6+4];
		$l=randomize(0,5,2);
		$m=$l[1]*11;
		$a=rand(2,4);
		for($k=$a;$k<($a+$durasiKelas);$k++){
			//$arrayRuangan[$i][$j][$k+$m][1] = 1;
			$arrayPop[$x][$i][$j][$k+$m][1] = 1;
		}

		//Random 10-16
		$i = rand(0,$jmlRuangan-1);
		$j = 1;
		$durasiKelas = $arrayFile[$jmlRuangan*4+$j*6+4];
		$l=randomize(2,4,1);
		$m=$l[1]*11;
		$a=rand(4,7);
		for($k=$a;$k<($a+$durasiKelas);$k++){
			//$arrayRuangan[$i][$j][$k+$m][1] = 1;
			$arrayPop[$x][$i][$j][$k+$m][1] = 1;
		}

		//Random 7-9
		$i = rand(0,$jmlRuangan-1);
		for($j=4; $j<7; $j+=2){
			$durasiKelas = $arrayFile[$jmlRuangan*4+$j*6+4];
			$l=randomize(0,4,1);
			$m=$l[1]*11;
			$a=0;
			for($k=$a;$k<($a+$durasiKelas);$k++){
				//$arrayRuangan[$i][$j][$k+$m][1] = 1;
				$arrayPop[$x][$i][$j][$k+$m][1] = 1;
			}
		}

		//Random 7-12
		$i=0;
		$j=0;
		$durasiKelas = $arrayFile[$jmlRuangan*4+$j*6+4];
		$l=randomize(0,5,1);
		$m=$l[1]*11;
		$a=rand(0,1);
		for($k=$a;$k<($a+$durasiKelas);$k++){
			//$arrayRuangan[$i][$j][$k+$m][1] = 1;
			$arrayPop[$x][$i][$j][$k+$m][1] = 1;
		}

		$i = rand(0,$jmlRuangan-1);
		for($j=3; $j<$jmlMatkul; $j+=2){
			$durasiKelas = $arrayFile[$jmlRuangan*4+$j*6+4];
			$l=randomize(2,5,1);
			$m=$l[1]*11;
			$a=rand(0,1);
			for($k=$a;$k<($a+$durasiKelas);$k++){
				//$arrayRuangan[$i][$j][$k+$m][1] = 1;
				$arrayPop[$x][$i][$j][$k+$m][1] = 1;
			}
		}
	}
///

///SEMENTARA GA PENTING, GA USAH DIPIKIRIN (LEL)
	/*
	//ELIMINASI
	for($h=0;$h<$pop;$h++){
		for($i=0;$i<$jmlRuangan;$i++){
			for($j=0;$j<$jmlMatkul;$j++){
				for($k=0;$k<55;$k++){
					if(!$arrayPop[$h][$i][$j][$k][0])
						$arrayPop[$h][$i][$j][$k][1] = 0;
				}
			}
		}
	}
	*/
	
	//prtArray($arJamTmp);

	//Masukin jadwal
	/*$j = 0;
	$arMklTmp = randomize(0,$jmlMatkul);
	$ref = 4;

	for($i=0; $i<$jmlMatkul; $i++){
		if($j == $ref){
			$j = 0;
			$ref = 4;
		}
		while($j<$ref){
			$k = $arMklTmp[$j];
			$arrayRuangan[$i][$k][$j][1] = 1;
			$j++;
		}
	}*/

	/*for ($i=0;$i<$jmlMatkul;$i++){
	    $durasiKelas[$i] = $arrayFile[$jmlRuangan*4+$i*6+4];
	    for ($k=0;$k<$durasiKelas[$i];$k++){
	    	$arrayRuangan[0][$i][$k][1] = 1;
	    }
	}*/
///

///NAMPILIN HASIL GENERATE
	$cons = 1;
	for($h = 0; $h < $pop; $h++){
		echo "<h3>Population " . ($h+1) . "</h3>"; 
		for($i = 0; $i < $jmlRuangan; $i++){
			for($j = 0; $j < $jmlMatkul; $j++){
				for($k = 0; $k < 55; $k++){
					if($arrayPop[$h][$i][$j][$k][$cons])
						echo "<br>>>> " . $indexRuangan[$i] . " " . $indexMatkul[$j] .  " Jam = " . ($k);
				}
			}
			echo "<br>";
		}
	}
///

///FITNESS FUNCTION
	//NGITUNG JUMLAH MAKUL TEPAT SASARAN
	$fitnessCount = array();
	$fitnessRate = array();
	echo"<br>";
	for($h = 0; $h < $pop; $h++){
		$fitnessCount[$h] = 0;
		for($i = 0; $i < $jmlRuangan; $i++){
			for($j = 0; $j < $jmlMatkul; $j++){
				for($k = 0; $k < 55; $k++){
					if($arrayPop[$h][$i][$j][$k][0])
						$fitnessCount[$h] += $arrayPop[$h][$i][$j][$k][1];
				}
			}
		}
		$fitnessRate[$h] = ($fitnessCount[$h]/20)*100;
		echo "Population " . ($h+1) . " Fitness = " . $fitnessRate[$h] . "%<br>";
	}
///

///SELECTION
	//PEMILIHAN 2 INDIVIDU DENGAN FITNESS RATE TERTINGGI
	$count = 0;
	$parent = array();
	$tempRate = 0;
	$individu = array(0,0);
	for($h=0; $h<$pop; $h++)
		$parent[$h] = $fitnessRate[$h];

	for($i=0; $i<$pop; $i++)
		for($h=1; $h<$pop; $h++){
			if($parent[$h-1]<$parent[$h]){
				$temp = $parent[$h];
				$parent[$h] = $parent[$h-1];
				$parent[$h-1] = $temp;
			}

		}

	for($i=0; $i<$pop; $i++){
		if($parent[0] == $fitnessRate[$i]){
			$individu[0] = $i;
		}
		if($parent[1] == $fitnessRate[$i]){
			$individu[1] = $i;
		}
	}

	echo "<br>";
	for($i=0;$i<2;$i++){
		echo "Parent " . ($i+1) . " = " . ($individu[$i]+1) . "<br>";
	}

	//PEMILIHAN KROMOSOM MANA YANG MAU DIGANTI
	$indexR = array();
	$indexM = array();
	$indexJ = array();
	for($i=0; $i<2; $i++){
		$a = $individu[$i];
		for($j=0; $j<$jmlRuangan; $j++){
			for($k=0; $k<$jmlMatkul; $k++){
				for($l=0; $l<55; $l++){
					if(!$arrayPop[$a][$j][$k][$l][0] && $arrayPop[$a][$j][$k][$l][1]){
					}
				}
			}
		}
	}
///

//Cross-Over

	//Mutation

	//Efektif atau ga

	echo "<br>Salah = " . cekKesalahan($arrayRuangan,$jmlRuangan,$jmlMatkul) . "<br>";
	echo "7602 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7602",$indexRuangan) . "<br>";
	echo "7603 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7603",$indexRuangan) . "<br>";
	echo "7610 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"7610",$indexRuangan) . "<br>";
	echo "Labdas 2 Bentrok = " . cekBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,"Labdas2",$indexRuangan) . "<br>";
	echo "<br>";
	echo "7602 Terisi = " . $q = persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,"7602",$indexRuangan) . "<br>";
	echo "7603 Terisi = " . $w = persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,"7603",$indexRuangan) . "<br>";
	echo "7610 Terisi = " . $e = persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,"7610",$indexRuangan) . "<br>";
	echo "Labdas 2 Terisi = " . $r = persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,"Labdas2",$indexRuangan) . "<br>";
	echo "Total Terisi = " . ($q+$w+$e+$r);

?>