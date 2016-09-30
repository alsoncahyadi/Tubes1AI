<?php
	include "readfile.php";

///BANYAKNYA POPULASI YANG MAU DI GENERATE (HARUS GENAP)
	$pop = 2;
///

///INISIALISASI ARRAY UNTUK POPULASI
	$arrayPop = array();
	for($h=0;$h<$pop;$h++)
		for ($i=0;$i<$jmlRuangan;$i++)
	    	for ($j=0;$j<$jmlMatkul+1;$j++)
	        	for ($k=0;$k<55;$k++) {
	            	$arrayPop[$h][$i][$j][$k][0] = 0; $arrayPop[$h][$i][$j][$k][1] = 0;
	            	$arrayPop[$h][$i][$j][$k][2] = 0; $arrayPop[$h][$i][$j][$k][3] = 0;}

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
			//for($j=0; $j<3; $j++)
				//echo $arrayTimeR[$i][$j] . "<br>";
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
		        //echo $hari . "<br>";
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
		        //echo $jmlHariR . "<br>";
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
			//for($j=0; $j<3; $j++)
				//echo $arrayTimeM[$i][$j] . "<br>";
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
		    //echo $arrayHariM[$i][0] . "<br>";
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
		    //echo $jmlHariM[$i] . "<br>";
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
	        //echo $arrayRuangM[$i] . "<br>";
		}
		return $arrayRuangM;
	}

	//Menentukan constraint hari mana saja yang sama antara mata kuliah dan ruangan
	function cekSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR, $hariSama){
		//echo "<br>";
		$count = 0;
		for($x=0; $x<$jmlHariM[$j]+1; $x++){
			for($y=0; $y<$jmlHariR[$i]+1; $y++){
				if($arrayHariM[$j][$x] == $arrayHariR[$i][$y]){
					$hariSama[$count] = $arrayHariM[$j][$x];
					//echo $hariSama[$count];
					$count++;
				}
			}
		}
		return $hariSama;
	}

	//Menghitung jumlah constraint hari yang sama antara mata kuliah dan ruangan
	function jmlSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR){
		echo "<br>";
		$count = 0;
		for($x=0; $x<$jmlHariM[$j]+1; $x++){
			for($y=0; $y<$jmlHariR[$i]+1; $y++){
				if($arrayHariM[$j][$x] == $arrayHariR[$i][$y]){
					$count++;
				}
			}
		}
		//echo $count;
		return $count;
	}


	//Fungsi untuk melakukan randomisasi jadwal terhadap seluruh mata kuliah
	function randomizePop($pop, $jmlMatkul, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $jmlHariR, $arrayHariR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		for($j=0; $j<$jmlMatkul; $j++){
			$arrayPop = randomizeMkul($pop, $j, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $jmlHariR, $arrayHariR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);
		}
		return $arrayPop;
	}

	//Fungsi untuk melakukan randomisasi jadwal terhadap satu mata kuliah
	function randomizeMkul($pop, $j, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $jmlHariR, $arrayHariR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		for($i=0; $i<$pop; $i++){
			if($arrayRuangM[$j] == 0){
				$room = rand(0,$jmlRuangan-1);
			}
			else{
				$room = array_search($arrayRuangM[$j],$indexRuangan);
			}
			//echo " rm" . $room;
			if($arrayTimeR[$room][1]<$arrayTimeM[$j][1])
				$dur = $arrayTimeR[$room][1];
			else
				$dur = $arrayTimeM[$j][1];
			if($arrayTimeR[$room][0]>$arrayTimeM[$j][0]){
				$st = $arrayTimeR[$room][0];
			}
			else
				$st = $arrayTimeM[$j][0];
			$a = rand(0, $jmlHariM[$j]);
			$b = ($arrayHariM[$j][$a]-1) * 11;
			$start = rand($st,($dur-$arrayTimeM[$j][3]));
			//echo " st" . $start;
			for($k=$start+$b;$k<$start+$arrayTimeM[$j][3]+$b;$k++){
				$arrayPop[$i][$room][$j][$k][1] = 1;
			}
		}
		return $arrayPop;
	}

	//Menampilkan array hasil GA
	function tampil($jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayRuangan){
			echo "<h3>Population " . ($h+1) . "</h3>"; 
			for($i = 0; $i < $jmlRuangan; $i++){
				for($j = 0; $j < $jmlMatkul; $j++){
					for($k = 0; $k < 55; $k++){
						if($arrayRuangan[$i][$j][$k][1])
							echo "<br>>>> " . $indexRuangan[$i] . " " . $indexMatkul[$j] .  " Jam = " . ($k);
					}
				}
				echo "<br>";
			}
		return;
	}

	$salahCount = array();
	$bentrokCount = array();
	//Menghitung jumlah mata kuliah yang salah penempatan jadwal
	function countSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $salahCount){
		//echo"<br>";
		for($h = 0; $h < $pop; $h++){
			$salahCount[$h] = 0;
			for($i = 0; $i < $jmlRuangan; $i++){
				for($j = 0; $j < $jmlMatkul; $j++){
					for($k = 0; $k < 55; $k++){
						if(!$arrayPop[$h][$i][$j][$k][0] && $arrayPop[$h][$i][$j][$k][1])
							$salahCount[$h]++;
					}
				}
			}
			//echo $salahCount[$h] . " ";
		}
		return $salahCount;
	}

	//Menghitung jumlah mata kuliah yang bentrok
	function countBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $bentrokCount){
		//echo"<br>";
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
			//echo $bentrokCount[$h] . " ";
		}
		return $bentrokCount;
	}

	//Menghitung persentase fitness function(fitness rate)
	$fitnessRate = array();
	function countFitness($pop, $fitnessRate, $salahCount, $bentrokCount){
		//echo "<br>";
		for($h = 0; $h < $pop; $h++){
			$bener = 20 - (($salahCount[$h]+$bentrokCount[$h])/2);
			$fitnessRate[$h] = $bener/20;
			//echo $fitnessRate[$h] . " ";
		}
		return $fitnessRate;
	}

	//Mengurutkan fitness rate secara descending
	$fitnessSorted = array();
	function sortFitness($pop, $fitnessRate, $fitnessSorted){
		//echo"<br>";
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
			//echo $fitnessSorted[$i] . " ";
		}
		return $fitnessSorted;
	}

	//Menentukan index dari masing2 fitness rate yang sudah diurutkan. index menunjukkan bahwa fitness rate urutan keberapa ialah milik populasi yang mana
	$fitnessIndex = array();
	function fitIndex($pop, $fitnessSorted, $fitnessRate, $fitnessIndex){
		//echo"<br>";
		for($i=0; $i<$pop; $i++){
			for($j=0; $j<$pop; $j++){
				if($fitnessSorted[$i] == $fitnessRate[$j]){
					$fitnessIndex[$i] = $j;
				}
			}
			//echo $fitnessIndex[$i] . " ";
		}
		return $fitnessIndex;
	}

	//Fungsi Selection
	$arrayM = array();
	function selection($pop, $fitnessIndex, $jmlMatkul, $jmlRuangan, $arrayPop, $arrayM){
		//echo "<br>";
		for($h=0; $h<$pop; $h+=2){
			$a = $fitnessIndex[$h];
			$b = $fitnessIndex[$h+1];
			$idxCount = 0;
			for($i=0; $i<$jmlRuangan; $i++){
				for($j=0; $j<$jmlMatkul; $j++){
					for($k=0; $k<55; $k++){
						if(!$arrayPop[$a][$i][$j][$k][0] && $arrayPop[$a][$i][$j][$k][1]){
							$arrayM[$idxCount] = $j;
							$idxCount++;
						}
					}
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
	function cekFirstBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstBentrok){
		//echo"<br>";
		for($h=0; $h<$pop; $h++){
			$count = 0;
			for($k=0; $k<55; $k++){
				for($i=0; $i<$jmlRuangan; $i++){
					for($j=0; $j<$jmlMatkul; $j++){
						if($count<1 && $arrayPop[$h][$i][$j][$k][1]){
							$firstBentrok[$h][0] = $i;
							$firstBentrok[$h][1] = $j;
							$count++;
						}else if($count<1 && $k==54){
							$firstBentrok[$h][0] = -1;
							$firstBentrok[$h][1] = -1;
						}
					}
				}
			}
			//echo $firstBentrok[$h][0] . " ";
			//echo $firstBentrok[$h][1] . " ";
		}
		return $firstBentrok;
	}

	//Mencari mata kuliah yang salah, yang pertama ditemukan yang diambil
	$firstSalah = array();
	function cekFirstSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstSalah){
		//echo"<br>";
		for($h=0; $h<$pop; $h++){
			$count = 0;
			for($i=0; $i<$jmlRuangan; $i++){
				for($j=0; $j<$jmlMatkul; $j++){
					for($k=0; $k<55; $k++){
						if($count<1 && !$arrayPop[$h][$i][$j][$k][0] && $arrayPop[$h][$i][$j][$k][1]){
							$firstSalah[$h][0] = $i;
							$firstSalah[$h][1] = $j;
							$count++;
						}else if($count<1 && $k==54){
							$firstSalah[$h][0] = -1;
							$firstSalah[$h][1] = -1;
						}
					}
				}
			}
			//echo $firstSalah[$h][0] . " ";
			//echo $firstSalah[$h][1] . " ";
		}
		return $firstSalah;
	}

	//Fungsi Mutation
	function mutation($pop, $firstSalah, $arrayPop, $arrayRuangM, $jmlRuangan, $jmlHariR, $jmlHariM, $arrayHariR, $arrayHariM, $arrayTimeM, $arrayTimeR){
		for($h=0; $h<$pop; $h++){
			$hariSama = array();
			if($firstSalah[$h][0] != -1 && $firstSalah[$h][1] != -1){
				$i = $firstSalah[$h][0];
				$j = $firstSalah[$h][1];
				for($k=0; $k<55; $k++){
					$arrayPop[$h][$i][$j][$k][1] = 0;
				}
				if($arrayTimeR[$i][1]<$arrayTimeM[$j][1])
					$dur = $arrayTimeR[$i][1];
				else
					$dur = $arrayTimeM[$j][1];
				if($arrayTimeR[$i][0]>$arrayTimeM[$j][0])
					$st = $arrayTimeR[$i][0];
				else
					$st = $arrayTimeM[$j][0];
				$hariSama = cekSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR, $hariSama);
				$jmlSama = jmlSama($j, $i, $jmlHariM, $arrayHariM, $jmlHariR, $arrayHariR);
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
					while($c == $i);
					$b = ($hariSama[$a]-1) * 11;
				}
				else{
					$b = ($hariSama[$a]-1) * 11;
					$c = $i;
				}
				$start = rand($st,($dur-$arrayTimeM[$j][3]));
				//echo " st" . $start;
				for($l=$start+$b;$l<$start+$arrayTimeM[$j][3]+$b;$l++){
					$arrayPop[$h][$c][$j][$l][1] = 1;
				}
			}
		}
		return $arrayPop;
	}

	//Memasukkan nilai $arrayPop ke $arrayRuangan
	function insert($jmlRuangan, $jmlMatkul, $arrayPop, $fitnessIndex, $arrayRuangan){
		for($i=0; $i<$jmlRuangan; $i++){
			for($j=0; $j<$jmlMatkul; $j++){
				for($k=0; $k<55; $k++){
					if($arrayPop[$fitnessIndex[0]][$i][$j][$k][1])
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
		$salahCount = countSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $salahCount);
		$bentrokCount = countBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $bentrokCount);
		$fitnessRate = countFitness($pop, $fitnessRate, $salahCount, $bentrokCount);
		$fitnessSorted = sortFitness($pop, $fitnessRate, $fitnessSorted);
		$fitnessIndex = fitIndex($pop, $fitnessSorted, $fitnessRate, $fitnessIndex);

		//Select
		$arrayPop = selection($pop, $fitnessIndex, $jmlMatkul, $jmlRuangan, $arrayPop, $arrayM);
		
		//Crossover
		$arrayPop = crossover($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $fitnessIndex);
	
		//Mutate
		$firstBentrok = cekFirstBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstBentrok);
		$firstSalah = cekFirstSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstSalah);
		if($salahCount>0){
			$arrayPop = mutation($pop, $firstSalah, $arrayPop, $arrayRuangM, $jmlRuangan, $jmlHariR, $jmlHariM, $arrayHariR, $arrayHariM, $arrayTimeM, $arrayTimeR);
		}else if($bentrokCount>0){
			$arrayPop = mutation($pop, $firstBentrok, $arrayPop, $arrayRuangM, $jmlRuangan, $jmlHariR, $jmlHariM, $arrayHariR, $arrayHariM, $arrayTimeM, $arrayTimeR);
		}

		$step++;
		if($step>=20)
			break;
	}
	while($salahCount[$fitnessSorted[0]]>0 && $bentrokCount[$fitnessSorted[0]]>0);

	$arrayRuangan = insert($jmlRuangan, $jmlMatkul, $arrayPop, $fitnessIndex, $arrayRuangan);

	session_start();
	$_SESSION["arrayRuangan"] = $arrayRuangan;
	$_SESSION["indexRuangan"] = $indexRuangan;
	$_SESSION["indexMatkul"] = $indexMatkul;
	$_SESSION["jmlBentrok"] = $jmlBentrok;

	header("Location: /AI/result.php");
	die();

///

?>