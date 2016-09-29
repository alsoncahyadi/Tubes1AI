<?php
	include "readfile.php";

///BANYAKNYA POPULASI YANG MAU DI GENERATE (HARUS GENAP)
	$pop = 2;
///

	//FUNCTIONS

	//ngambil constraint waktu ruangan
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

	//ngambil constraint hari ruangan
	$arrayHariR = array();
	function arrayHariR($jmlRuangan, $arrayHariR, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlRuangan; $i++){
		    $listHari = $arrayFile[$i*4+3];
		    $availableDays = strlen($listHari) / 2;
		    for ($j=0;$j<$availableDays;$j++) {
		        $hari = substr($listHari,$j*2,1);
		        $arrayHari[$i][$j] = $hari;
		        //echo $hari . "<br>";
		    }
		}
		return $arrayHariR;
	}
	//$arrayHariR = arrayHariR($jmlRuangan, $arrayHariR, $arrayRuangan, $arrayFile);

	//ngambil constraint waktu matkul
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

	//ngambil constraint hari matkul
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

	//ngambil jumlah hari matkul
	$jmlHariM = array();
	function jmlHariM($jmlRuangan, $jmlMatkul, $jmlHariM, $arrayRuangan, $arrayFile){
		for($i=0; $i<$jmlMatkul; $i++){
		    $ruangan = $arrayFile[$jmlRuangan*4+$i*6+1];
		    $listHari = $arrayFile[$jmlRuangan*4+$i*6+5];
		    $availableDays = strlen($listHari) / 2;
		    for ($j=0;$j<$availableDays;$j++) {
		        $hari = substr($listHari,$j*2,1);
		        $jmlHariM[$i] = $j;
		    }
		    //echo $jmlHariM[$i] . "<br>";
		}
		return $jmlHariM;
	}

	//ngambil constraint ruangan matkul
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

	//randomisasi keseluruhan matkul
	function randomizePop($pop, $jmlMatkul, $arrayRuangM, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		for($j=0; $j<$jmlMatkul; $j++){
			$arrayPop = randomizeMkul($pop, $j, $arrayRuangM, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);
		}
		return $arrayPop;
	}

	//randomisasi salah satu matkul
	function randomizeMkul($pop, $j, $arrayRuangM, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		for($i=0; $i<$pop; $i++){
			if($arrayRuangM[$j] == 0){
				$room = rand(0,3);
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

	//nampilin array dari populasi
	function tampil($cons, $pop, $jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayPop){
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
		return;
	}
	
	//inisialisasi array untuk populasi
	$arrayPop = array();
	for($h=0;$h<$pop;$h++)
		for ($i=0;$i<$jmlRuangan;$i++)
	    	for ($j=0;$j<$jmlMatkul+1;$j++)
	        	for ($k=0;$k<55;$k++) {
	            	$arrayPop[$h][$i][$j][$k][0] = 0; $arrayPop[$h][$i][$j][$k][1] = 0;
	            	$arrayPop[$h][$i][$j][$k][2] = 0; $arrayPop[$h][$i][$j][$k][3] = 0;}

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

	$salahCount = array();
	$bentrokCount = array();
	//ngitung jumlah matkul yang salah dijadwalkan
	function countSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $salahCount){
		echo"<br>";
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
			echo $salahCount[$h] . " ";
		}
		return $salahCount;
	}

	//ngitung jumlah matkul yang bentrok
	function countBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $bentrokCount){
		echo"<br>";
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
			echo $bentrokCount[$h] . " ";
		}
		return $bentrokCount;
	}

	//ngitung persentase dari fitness function
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

	//ngurutin persentase secara descending
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

	//ngambil index dari populasi mana dengan rate yang dah di sort
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

	//selection
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

	//crossover
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
						}
						if($arrayPop[$b][$j][$k][$l][2]){
							$arrayPop[$b][$j][$k][$l][1] = 1;
						}
					}
				}
			}
		}
		return $arrayPop;
	}

	//ngecek matkul apa yang pertama kali dicek > bentrok pada suatu populasi
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

	//ngecek matkul apa yang pertama kali dicek > salah pada suatu populasi
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

	//nge-assign matkul yang pertama kali salah, lalu dibenerin (belum selesai)
	function assignBener($pop, $j, $arrayRuangM, $jmlRuangan, $jmlMatkul, $arrayFile, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		$firstBener = array();
		echo"<br>";
		for($h=0; $h<$pop; $h++){
			$count = 0;
			for($i=0; $i<$jmlRuangan; $i++){
				for($j=0; $j<$jmlMatkul; $j++){
					$durasiKelas = $arrayFile[$jmlRuangan*4+$j*6+4];
					for($k=0; $k<55; $k++){
						if(!$arrayPop[$h][$i][$j][$k][0] && $arrayPop[$h][$i][$j][$k][1]){
							if($arrayPop[$h][$i][$j][$k+1][0]/* && !$arrayPop[$h][$i][$j][$k+1][1]*/){
								$firstBener[$h][0] = $j;
								$firstBener[$h][1] = $k - $durasiKelas;
								echo $firstBener[$h][0] . " ";
								echo $firstBener[$h][1] . " ";
							}
						}
					}
				}
			}
		}
		//for($h=0;; $h<$pop; $h++)
	}

	//mutation (masih salah)
	function mutation($pop, $firstSalah, $firstBentrok, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop){
		$count = 0;
		for($h=0; $h<$pop; $h++){
			if($firstSalah[$h][1] != -1){
				$matkul[$count] = $firstSalah[$h][1];
				$count++;
			}else{
				$matkul[$count] = -1;
				$count++;
			}
		}
		for($i=0; $i<$count; $i++){
			if($matkul[$i] != -1){
				$arrayPop = randomizeMkul($pop, $matkul[$i], $arrayRuangM, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);
			}
		}
		return $arrayPop;
	}

	//tampil(2, $pop, $jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayPop);
	//tampil(3, $pop, $jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayPop);

	//START THE GA!!!!

	//Init
	$arrayTimeR = arrayTimeR($jmlRuangan, $arrayTimeR, $arrayRuangan, $arrayFile);
	$arrayTimeM = arrayTimeM($jmlMatkul, $jmlRuangan, $arrayTimeM, $arrayRuangan, $arrayFile);
	$arrayHariM = arrayHariM($jmlRuangan, $jmlMatkul, $arrayHariM, $arrayRuangan, $arrayFile);
	$jmlHariM = jmlHariM($jmlRuangan, $jmlMatkul, $jmlHariM, $arrayRuangan, $arrayFile);
	$arrayRuangM = arrayRuangM($jmlRuangan, $jmlMatkul, $arrayRuangM, $arrayRuangan, $arrayFile);
	$arrayPop = randomizePop($pop, $jmlMatkul, $arrayRuangM, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);

	//do{
		//Fitness
		$salahCount = countSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $salahCount);
		$bentrokCount = countBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $bentrokCount);
		$fitnessRate = countFitness($pop, $fitnessRate, $salahCount, $bentrokCount);
		$fitnessSorted = sortFitness($pop, $fitnessRate, $fitnessSorted);
		$fitnessIndex = fitIndex($pop, $fitnessSorted, $fitnessRate, $fitnessIndex);

		//Tampil
		tampil(1, $pop, $jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayPop);
		for($h=0; $h<$pop; $h++){
			echo $fitnessSorted[$h] . " ";
		}

		assignBener($pop, $j, $arrayRuangM, $jmlRuangan, $jmlMatkul, $arrayFile, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);
/*
		//Select
		$arrayPop = selection($pop, $fitnessIndex, $jmlMatkul, $jmlRuangan, $arrayPop, $arrayM);

		//Crossover
		$arrayPop = crossover($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $fitnessIndex);
	
		//Mutate
		$firstBentrok = cekFirstBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstBentrok);
		$firstSalah = cekFirstSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $firstSalah);
		$arrayPop = mutation($pop, $firstSalah, $firstBentrok, $jmlRuangan, $arrayRuangM, $indexRuangan, $arrayTimeR, $arrayTimeM, $arrayHariM, $jmlHariM, $arrayPop);

		//$loop++;
	//}*/
	//while($loop<10);
/*
	//Fitness
		$salahCount = countSalah($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $salahCount);
		$bentrokCount = countBentrok($pop, $jmlRuangan, $jmlMatkul, $arrayPop, $bentrokCount);
		$fitnessRate = countFitness($pop, $fitnessRate, $salahCount, $bentrokCount);
		$fitnessSorted = sortFitness($pop, $fitnessRate, $fitnessSorted);
		$fitnessIndex = fitIndex($pop, $fitnessSorted, $fitnessRate, $fitnessIndex);

	//Tampil
	tampil(1, $pop, $jmlRuangan, $jmlMatkul, $indexRuangan, $indexMatkul, $arrayPop);
	for($h=0; $h<$pop; $h++){
		echo $fitnessSorted[$h] . " ";
	}
	
*/
///

?>