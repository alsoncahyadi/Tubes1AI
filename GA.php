<?php
	ini_set('max_execution_time', 0);
	include "readfile.php";
	include "library.php";
	error_reporting(0);
	$maxStep = $_POST["maxStep"];
///BANYAKNYA POPULASI YANG MAU DI GENERATE (HARUS GENAP)
	$pop = 4;
///

///INISIALISASI ARRAY UNTUK POPULASI
	$arrayPop = array();
	for($h=0;$h<$pop;$h++)
		for ($i=0;$i<$jmlRuangan;$i++)
	    	for ($j=0;$j<$jmlMatkul+1;$j++)
	        	for ($k=0;$k<55;$k++) {
	            	$arrayPop[$h][$i][$j][$k][0] = 0; $arrayPop[$h][$i][$j][$k][1] = 0;
	            	$arrayPop[$h][$i][$j][$k][2] = 0; $arrayPop[$h][$i][$j][$k][3] = 0;}

	for($h=0;$h<$pop;$h++){
		for ($i=0;$i<$jmlRuangan;$i++) {
		    $waktu = $arrayFile[$i*4+1];
		    $waktuAkhir = $arrayFile[$i*4+2];
		    $durasi = $waktuAkhir-$waktu;
		    $listHari = $arrayFile[$i*4+3];
		    $availableDays = strlen($listHari) / 2;
		    for ($j=0;$j<$availableDays;$j++) {
		        $hari = substr($listHari,$j*2,1);
		        for ($k=0;$k<$durasi;$k++)
		            $arrayPop[$h][$i][$jmlMatkul][getIndex($hari,$waktu)+$k][0] = 1;
		    }
		}
	}

	for($h=0;$h<$pop;$h++){
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
		                    if ($arrayPop[$h][$l][$jmlMatkul][getIndex($hari,$waktu)+$k][0])
		                        $arrayPop[$h][$l][$i][getIndex($hari,$waktu)+$k][0] = 1;
		        } else {
		            $idxRuang = array_search($ruangan,$indexRuangan);
		            for ($k=0;$k<$durasi;$k++)
		                if ($arrayPop[$h][$idxRuang][$jmlMatkul][getIndex($hari,$waktu)+$k][0])
		                    $arrayPop[$h][$idxRuang][$i][getIndex($hari,$waktu)+$k][0] = 1;
		        }
		    }
		}
	}

	//Mengosongkan isi array ruangan
	for ($i=0;$i<$jmlMatkul;$i++) {
	    $durasiKelas = $arrayFile[$jmlRuangan*4+$i*6+4];
	    for ($k=0;$k<$durasiKelas;$k++)
	            $arrayRuangan[0][$i][$k][1] = 0;
	}
///

///FUNCTIONS

	//Mengambil constraint waktu kapan saja suatu ruangan dapat digunakan
	$arrayTimeR = array();
	function arrayTimeR($jmlRuangan, $arrayTimeR, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlRuangan; $i++){
			$waktu = $arrayFile[$i*4+1];
		    $waktuAkhir = $arrayFile[$i*4+2];
		    $durasi = $waktuAkhir-$waktu;
			$arrayTimeR[$i][0] = $waktu-7;
			$arrayTimeR[$i][1] = $waktuAkhir-7;
			$arrayTimeR[$i][2] = $durasi;
		}
		return $arrayTimeR;
	}

	//Mengambil constraint hari apa saja suatu ruangan dapat digunakan
	$arrayHariR = array();
	function arrayHariR($jmlRuangan, $arrayHariR, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlRuangan; $i++){
		    $listHari = $arrayFile[$i*4+3];
		    $availableDays = strlen($listHari) / 2;
		    for ($j=0;$j<$availableDays;$j++) {
		        $hari = substr($listHari,$j*2,1);
		        $arrayHariR[$i][$j] = $hari;
		    }
		}
		return $arrayHariR;
	}

	//Mengambil constraint jumlah hari dimana suatu ruangan dapat digunakan
	$jmlHariR = array();
	function jmlHariR($jmlRuangan, $jmlHariR, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlRuangan; $i++){
		    $listHari = $arrayFile[$i*4+3];
		    $availableDays = strlen($listHari) / 2;
		    for ($j=0;$j<$availableDays;$j++) {
		        $jmlHariR[$i] = $j;
		    }
		}
		return $jmlHariR;
	}

	//Mengambil constraint waktu kapan saja suatu mata kuliah dapat dijadwalkan
	$arrayTimeM = array();
	function arrayTimeM($jmlMatkul, $jmlRuangan, $arrayTimeM, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlMatkul; $i++){
			$durasiKelas = $arrayFile[$jmlRuangan*4+$i*6+4];
			$waktu = $arrayFile[$jmlRuangan*4+$i*6+2];
		    $waktuAkhir = $arrayFile[$jmlRuangan*4+$i*6+3];
		    $durasi = $waktuAkhir-$waktu;
			$arrayTimeM[$i][0] = $waktu-7;
			$arrayTimeM[$i][1] = $waktuAkhir-7;
			$arrayTimeM[$i][2] = $durasi;
			$arrayTimeM[$i][3] = $durasiKelas;
		}
		return $arrayTimeM;
	}

	//Mengambil constraint hari apa saja suatu mata kuliah dapat dijadwalkan
	$arrayHariM = array();
	function arrayHariM($jmlRuangan, $jmlMatkul, $arrayHariM, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlMatkul; $i++){
		    $ruangan = $arrayFile[$jmlRuangan*4+$i*6+1];
		    $listHari = $arrayFile[$jmlRuangan*4+$i*6+5];
		    $availableDays = strlen($listHari) / 2;
		    for ($j=0;$j<$availableDays;$j++) {
		        $hari = substr($listHari,$j*2,1);
		        $arrayHariM[$i][$j] = $hari;
		    }
		}
		return $arrayHariM;
	}

	//Mengambil constraint jumlah hari dimana suatu mata kuliah dapat dijadwalkan
	$jmlHariM = array();
	function jmlHariM($jmlRuangan, $jmlMatkul, $jmlHariM, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlMatkul; $i++){
		    $ruangan = $arrayFile[$jmlRuangan*4+$i*6+1];
		    $listHari = $arrayFile[$jmlRuangan*4+$i*6+5];
		    $availableDays = strlen($listHari) / 2;
		    for ($j=0;$j<$availableDays;$j++){
		        $jmlHariM[$i] = $j;
		    }
		}
		return $jmlHariM;
	}

	////Mengambil constraint ruangan dimana suatu mata kuliah dapat dijadwalkan
	$arrayRuangM = array();
	function arrayRuangM($jmlRuangan, $jmlMatkul, $arrayRuangM, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlMatkul; $i++){
		    $ruangan = $arrayFile[$jmlRuangan*4+$i*6+1];
		    if ($ruangan!="-") {
	            $arrayRuangM[$i] = $ruangan;
	        }
	        else
	        	$arrayRuangM[$i] = 0;
		}
		return $arrayRuangM;
	}

	//Menentukan constraint hari mana saja yang sama antara mata kuliah dan ruangan
	function cekSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR, $hariSama){
		$count = 0;
		for($x=0; $x<$jmlHariM[$j]+1; $x++){
			for($y=0; $y<$jmlHariR[$i]+1; $y++){
				if($arrayHariM[$j][$x] == $arrayHariR[$i][$y]){
					$hariSama[$count] = $arrayHariM[$j][$x];
					$count++;
				}
			}
		}
		return $hariSama;
	}

	//Menghitung jumlah constraint hari yang sama antara mata kuliah dan ruangan
	function jmlSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR){
		$count = 0;
		for($x=0; $x<$jmlHariM[$j]+1; $x++){
			for($y=0; $y<$jmlHariR[$i]+1; $y++){
				if($arrayHariM[$j][$x] == $arrayHariR[$i][$y]){
					$count++;
				}
			}
		}
		return $count;
	}


	//Fungsi untuk melakukan randomisasi jadwal terhadap seluruh mata kuliah
	function randomizePop($pop, $jmlMatkul, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $jmlHariR, $arrayHariR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		for($j=0; $j<$jmlMatkul; $j++){
			for($i=0; $i<$pop; $i++){
				$arrayPop = randomizeMkul($j, $i, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $jmlHariR, $arrayHariR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);
			}
		}
		return $arrayPop;
	}

	//Fungsi untuk melakukan randomisasi jadwal terhadap satu mata kuliah
	function randomizeMkul($j, $i, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $jmlHariR, $arrayHariR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		$hariSama = array();
		do{
			if($arrayRuangM[$j] == 0){
				$room = rand(0,$jmlRuangan-1);
			}
			else{
				$room = array_search($arrayRuangM[$j],$indexRuangan);
			}
			if($arrayTimeR[$room][1]<$arrayTimeM[$j][1])
				$dur = $arrayTimeR[$room][1];
			else
				$dur = $arrayTimeM[$j][1];
			if($arrayTimeR[$room][0]>$arrayTimeM[$j][0]){
				$st = $arrayTimeR[$room][0];
			}
			else
				$st = $arrayTimeM[$j][0];
		}while($dur-$st<$arrayTimeM[$j][3]);
		$hariSama = cekSama($j, $room, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR, $hariSama);
		$jmlSama = jmlSama($j, $room, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR);
		$a = rand(0, $jmlSama-1);
		if($jmlSama == 0){
			do{
				if($arrayRuangM[$j] == 0){
					$c = rand(0,$jmlRuangan-1);
				}
				else{
					$c = array_search($arrayRuangM[$j],$indexRuangan);
				}
				$hariSama = cekSama($j, $c, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR, $hariSama);
				$jmlSama = jmlSama($j, $c, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR);
				$a = rand(0, $jmlSama-1);
			}
			while($c == $room);
			$b = ($hariSama[$a]-1) * 11;
		}
		else{
			$b = ($hariSama[$a]-1) * 11;
			$c = $room;
		}
		$start = rand($st,($dur-$arrayTimeM[$j][3]));
		for($l=$start+$b;$l<$start+$arrayTimeM[$j][3]+$b;$l++){
			$arrayPop[$i][$c][$j][$l][1] = 1;
		}
		return $arrayPop;
	}

	//Menampilkan array hasil GA
	function tampil($jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayRuangan){
			for($i = 0; $i < $jmlRuangan; $i++){
				for($j = 0; $j < $jmlMatkul; $j++){
					for($k = 0; $k < 55; $k++){
						if($arrayRuangan[$i][$j][$k][1])
							echo "<br>>>> " . $indexRuangan[$i] . " " . $indexMatkul[$j] .  " Jam = " . ($k);
					}
				}
			}
		return;
	}

	$bentrokCount = array();
	//Menghitung jumlah mata kuliah yang bentrok
	function countBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $bentrokCount){
		for($h = 0; $h < $pop; $h++){
			$bentrokCount[$h] = 0;
			for($i = 0; $i < $jmlRuangan; $i++){
				for($k = 0; $k < 55; $k++){
					$count = 0;
					for($j = 0; $j < $jmlMatkul; $j++){
						$count += $arrayPop[$h][$i][$j][$k][1];
					}
					if ($count>1)
            			$bentrokCount[$h] += kombinasi2($count);
				}
			}
		}
		return $bentrokCount;
	}

	//Menghitung persentase fitness function(fitness rate)
	$fitnessRate = array();
	function countFitness($pop, $fitnessRate, $bentrokCount){
		for($h = 0; $h < $pop; $h++){
			$bener = 20 - $bentrokCount[$h];
			$fitnessRate[$h] = $bener/20;
		}
		return $fitnessRate;
	}

	//Mengurutkan fitness rate secara descending
	$fitnessSorted = array();
	function sortFitness($pop, $fitnessRate, $fitnessSorted){
		for($h=0; $h<$pop; $h++)
			$fitnessSorted[$h] = $fitnessRate[$h];
		for($i=0; $i<$pop; $i++){
			for($h=$i+1; $h<$pop; $h++){
				if($fitnessSorted[$i]<$fitnessSorted[$h]){
					$temp = $fitnessSorted[$h];
					$fitnessSorted[$h] = $fitnessSorted[$i];
					$fitnessSorted[$i] = $temp;
				}
			}
		}
		return $fitnessSorted;
	}

	//Menentukan index dari masing2 fitness rate yang sudah diurutkan. index menunjukkan bahwa fitness rate urutan keberapa ialah milik populasi yang mana
	$fitnessIndex = array();
	function fitIndex($pop, $fitnessSorted, $fitnessRate, $fitnessIndex){
		for($i=0; $i<$pop; $i++){
			for($j=0; $j<$pop; $j++){
				if($fitnessSorted[$i] == $fitnessRate[$j]){
					$fitnessIndex[$i] = $j;
				}
			}
		}
		return $fitnessIndex;
	}

	//Fungsi Selection
	$arrayM = array();
	function selection($pop, $fitnessIndex, $jmlMatkul, $jmlRuangan, $arrayPop, $arrayM){
		for($h=0; $h<$pop; $h+=2){
			$a = $fitnessIndex[$h];
			$b = $fitnessIndex[$h+1];
			$idxCount = 0;
			for($i=0; $i<$jmlRuangan; $i++){
			    $count = 0;
			    for ($k=0;$k<55;$k++) {
			        $count1 = 0;
			        for ($j=0;$j<$jmlMatkul;$j++)
			            $count1 += $arrayPop[$h][$i][$j][$k][1];
			        if ($count1>1)
			            $count += kombinasi2($count1);
			    }
			    if($count>0){
			    	$arrayM[$idxCount] = $j;
					$idxCount++;
			    }
			}
		}
		for($j=0; $j<$jmlRuangan; $j++){
			for($k=0; $k<$idxCount; $k++){
				$c=$arrayM[$k];
				for($l=0; $l<55; $l++){
					if($arrayPop[$a][$j][$c][$l][1]){
						$arrayPop[$a][$j][$c][$l][2] = 1;
					}
					if($arrayPop[$b][$j][$c][$l][1]){
						$arrayPop[$b][$j][$c][$l][3] = 1;
					}
				}
			}
		}
		return $arrayPop;
	}

	//Fungsi Cross-Over
	function crossover($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $fitnessIndex){
		for($i=0; $i<$pop; $i+=2){
			$a = $fitnessIndex[$i];
			$b = $fitnessIndex[$i+1];
			for($j=0;$j<$jmlRuangan;$j++){
				for($k=0;$k<$jmlMatkul;$k++){
					for($l=0;$l<55;$l++){
						if($arrayPop[$a][$j][$k][$l][2]){
							$arrayPop[$b][$j][$k][$l][2] = 1;
							$arrayPop[$a][$j][$k][$l][2] = 0;
							$arrayPop[$a][$j][$k][$l][1] = 0;
						}
						if($arrayPop[$b][$j][$k][$l][3]){
							$arrayPop[$a][$j][$k][$l][3] = 1;
							$arrayPop[$b][$j][$k][$l][3] = 0;
							$arrayPop[$b][$j][$k][$l][1] = 0;
						}
					}
				}
			}
			for($j=0;$j<$jmlRuangan;$j++){
				for($k=0;$k<$jmlMatkul;$k++){
					for($l=0;$l<55;$l++){
						if($arrayPop[$a][$j][$k][$l][3]){
							$arrayPop[$a][$j][$k][$l][1] = 1;
							$arrayPop[$a][$j][$k][$l][3] = 0;
						}
						if($arrayPop[$b][$j][$k][$l][2]){
							$arrayPop[$b][$j][$k][$l][1] = 1;
							$arrayPop[$b][$j][$k][$l][2] = 0;
						}
					}
				}
			}
		}
		return $arrayPop;
	}

	//Mencari mata kuliah yang bentrok, yang pertama ditemukan yang diambil
	$firstBentrok = array();
	function getMRBentrok($h, $pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstBentrok){
		for($i = 0; $i < $jmlRuangan; $i++){
			for($k = 0; $k < 55; $k++){
				$count = 0;
				for($j = 0; $j < $jmlMatkul; $j++){
					$count += $arrayPop[$h][$i][$j][$k][1];
					if($count>1){
						$firstBentrok[$h][0] = $i;
						$firstBentrok[$h][1] = $j;
						return $firstBentrok;
					}
				}
			}
		}
		$firstBentrok[$h][0] = -1;
		$firstBentrok[$h][1] = -1;
		return $firstBentrok;
	}

	//Fungsi Mutation
	function mutation($h, $firstBentrok, $arrayPop, $arrayRuangM, $jmlRuangan, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR, $arrayTimeR, $arrayTimeM){
		$hariSama = array();
		if($firstBentrok[$h][0] != -1 && $firstBentrok[$h][1] != -1){
			$i = $firstBentrok[$h][0];
			$j = $firstBentrok[$h][1];
			$durasi = 0;
			for($k=0; $k<55; $k++){
				if($arrayPop[$h][$i][$j][$k][1]){
					$jamAkhir = $k+1;
					$durasi++;
				}
			}
			$jamAwal = $jamAkhir - $durasi;
			$hariSama = cekSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR, $hariSama);
			$jmlSama = jmlSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR);
			if($jmlSama != 0){
				for($x=0; $x<$jmlSama; $x++){
					$a = ($hariSama[$x]-1) * 11;
					for($y=0; $y<11; $y++){
						if($jamAwal == $a + $y){
							$hari = $hariSama[$x]-1;
							$idxHari = $x;
						}
					}
				}
				if($choice){
					if($hari < $hariSama[$jmlSama-1]-1){
						$jamAwal += ($hariSama[$idxHari] - $hari) * 11;
						$jamAkhir += ($hariSama[$idxHari] - $hari) * 11;
					}
					else{
						$jamAwal -= (($hariSama[$idxHari] - $hari) * 11);
						$jamAkhir -= (($hariSama[$idxHari] - $hari) * 11);
					}
				}
			}
			else{
				if($arrayRuangM[$j]==0){
					do{
						$ruangan = rand(0,$jmlRuangan-1);
						if($arrayTimeR[$ruangan][1]<$arrayTimeM[$j][1])
							$dur = $arrayTimeR[$ruangan][1];
						else
							$dur = $arrayTimeM[$j][1];
						if($arrayTimeR[$ruangan][0]>$arrayTimeM[$j][0]){
							$st = $arrayTimeR[$ruangan][0];
						}
						else
							$st = $arrayTimeM[$j][0];
					}while($dur-$st<$arrayTimeM[$j][3]&&$ruangan == $i);
					$i = $ruangan;
					echo $ruangan . "<br>";
				}
			}
			for($l=0; $l<55; $l++){
				if($arrayPop[$h][$i][$j][$l][1]){
					$arrayPop[$h][$i][$j][$l][1] = 0;
					}
				}
			for($m=$jamAwal;$m<$jamAkhir;$m++){
				$arrayPop[$h][$i][$j][$m][1] = 1;
			}
		}
		return $arrayPop;
	}

	//Memasukkan nilai $arrayPop ke $arrayRuangan
	function insert($jmlRuangan, $jmlMatkul, $arrayPop, $h, $arrayRuangan){
		for($i=0; $i<$jmlRuangan; $i++){
			for($j=0; $j<$jmlMatkul; $j++){
				for($k=0; $k<55; $k++){
					if($arrayPop[$h][$i][$j][$k][1])
						$arrayRuangan[$i][$j][$k][1] = 1;
				}
			}
		}
		return $arrayRuangan;
	}
///

//////START THE GA!!!!//////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Init
	$arrayTimeR = arrayTimeR($jmlRuangan, $arrayTimeR, $arrayRuangan, $arrayFile);
	$arrayTimeM = arrayTimeM($jmlMatkul, $jmlRuangan, $arrayTimeM, $arrayRuangan, $arrayFile);
	$arrayHariM = arrayHariM($jmlRuangan, $jmlMatkul, $arrayHariM, $arrayRuangan, $arrayFile);
	$arrayHariR = arrayHariR($jmlRuangan, $arrayHariR, $arrayRuangan, $arrayFile);
	$jmlHariM = jmlHariM($jmlRuangan, $jmlMatkul, $jmlHariM, $arrayRuangan, $arrayFile);
	$jmlHariR = jmlHariR($jmlRuangan, $jmlHariR, $arrayRuangan, $arrayFile);
	$arrayRuangM = arrayRuangM($jmlRuangan, $jmlMatkul, $arrayRuangM, $arrayRuangan, $arrayFile);
	$arrayPop = randomizePop($pop, $jmlMatkul, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $jmlHariR, $arrayHariR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);

	$step=0;
	do{

		//Fitness
		$bentrokCount = countBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $bentrokCount);
		$fitnessRate = countFitness($pop, $fitnessRate, $bentrokCount);
		$fitnessSorted = sortFitness($pop, $fitnessRate, $fitnessSorted);
		$fitnessIndex = fitIndex($pop, $fitnessSorted, $fitnessRate, $fitnessIndex);
		//echo $bentrokCount[$fitnessIndex[0]] . "<br>";

		//Select
		$arrayPop = selection($pop, $fitnessIndex, $jmlMatkul, $jmlRuangan, $arrayPop, $arrayM);
		
		//Crossover
		$arrayPop = crossover($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $fitnessIndex);
		
		for($h = 0; $h < $pop; $h++){
			$firstBentrok = getMRBentrok($h, $pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstBentrok);
		}

		//Mutate
		for($h=0; $h<$pop; $h++){
			$arrayPop = mutation($h, $firstBentrok, $arrayPop, $arrayRuangM, $jmlRuangan, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR);
		}

		$step++;
		if($step>=$maxStep)
			break;
		
	}while($bentrokCount[$fitnessIndex[0]]>0);

	$arrayRuangan = insert($jmlRuangan, $jmlMatkul, $arrayPop, $fitnessIndex[0], $arrayRuangan);
	tampil($jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayRuangan);

	echo "<br>" . cekKesalahan($arrayRuangan,$jmlRuangan,$jmlMatkul);
	echo "<br>" . cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);

	session_start();
	$_SESSION["arrayRuangan"] = $arrayRuangan;
	$_SESSION["indexRuangan"] = $indexRuangan;
	$_SESSION["indexMatkul"] = $indexMatkul;

	$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= $_SERVER['REQUEST_URI'];

	header("Location: " . dirname($url) . "/result.php");
	die();

///

?>