<!DOCTYPE html>
<html>
<head>
	<title>AI result</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?php session_start();
	$arrayRuangan = $_SESSION["arrayRuangan"];
	$indexRuangan = $_SESSION["indexRuangan"];
	$indexMatkul = $_SESSION["indexMatkul"];

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


	//fungsi menghitung persen ruangan yg terisi
	function persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan) {
		$terisi = 0;
		$total = 0;
		for ($i=0;$i<$jmlRuangan;$i++)
		    for ($k=0;$k<55;$k++) {
		         for ($j=0;$j<$jmlMatkul+1;$j++) {
		            //hitung terisi
		            $terisi += $arrayRuangan[$i][$j][$k][1];
			    }
		    	//hitung total
		        $total += $arrayRuangan[$i][$jmlMatkul][$k][0];
			}
		return(($terisi*100 + 0.0)/$total);
	}

	//Inisialisasi
	$jmlHari = 5;
	$jmlJam = 11;
	$jmlRuangan = count($arrayRuangan);
	$arrayHari = array();
	for($hari=0; $hari<$jmlHari; $hari++) {
		array_push($arrayHari, array());
		for($jam=0; $jam<$jmlJam; $jam++) {
			array_push($arrayHari[$hari], array());

			$arrayHari[$hari][$jam]["arrayMatkul"] = array();
			$arrayHari[$hari][$jam]["arrayIdxMatkul"] = array();
			$arrayHari[$hari][$jam]["arrayRuangan"] = array();
			$arrayHari[$hari][$jam]["arrayIdxRuangan"] = array();
			//$arrayHari[$hari][$jam]["arrayRawHtmlPerRuangan"] = array();
		}
	}

	/** DEFINISI STRUKTUR DATA
	 * $arrayHari
	 	-> $arrayJam
	 		-> ["arrayMatkul"] nama matkulnya
	 		   ["arrayIdxMatkul"] index matkulnya
	 		   ["arrayRuangan"] nama ruangannya
	 		   ["arrayIdxRuangan"] index ruangannya
	 		   //["rawHtml"] html yang ditulis
	**/

	//Preprocess
	//	memasukkan data ke struktur data $arrayHari
	foreach ($arrayRuangan as $idxRuangan => $ruangan) {
		foreach ($ruangan as $idxMatkul => $matkul) {
			foreach ($matkul as $idxJam => $jam) {
				if ($jam[1]) {
					//echo $idxJam . " <span class='matkul'>" . $indexMatkul[$idxMatkul] . "</span> => <span class='ruangan'>" . $indexRuangan[$idxRuangan] . "</span><br>";
					//echo (int)($idxJam/11) . ", " . $idxJam%11 . "<br>";
					array_push($arrayHari[(int)($idxJam/11)][$idxJam%11]["arrayMatkul"], $indexMatkul[$idxMatkul]);
					array_push($arrayHari[(int)($idxJam/11)][$idxJam%11]["arrayIdxMatkul"], $idxMatkul);

					array_push($arrayHari[(int)($idxJam/11)][$idxJam%11]["arrayRuangan"], $indexRuangan[$idxRuangan]);
					array_push($arrayHari[(int)($idxJam/11)][$idxJam%11]["arrayIdxRuangan"], $idxRuangan);
				}
			}
		}
	}

 //DEBUG PURPOSES ONLY
	//array_push($arrayHari[1][0]["arrayMatkul"], $indexMatkul[1]);
	//array_push($arrayHari[1][0]["arrayRuangan"], $indexRuangan[3]);
/*
	array_push($arrayHari[2][0]["arrayMatkul"], $indexMatkul[1]);
	array_push($arrayHari[2][0]["arrayRuangan"], $indexRuangan[1]);

	array_push($arrayHari[3][0]["arrayMatkul"], $indexMatkul[1]);
	array_push($arrayHari[3][0]["arrayRuangan"], $indexRuangan[1]);

	array_push($arrayHari[4][0]["arrayMatkul"], $indexMatkul[1]);
	array_push($arrayHari[4][0]["arrayRuangan"], $indexRuangan[1]);*/

	//Proses raw text html untuk setiap hari
	foreach($arrayHari as $idxHari => $hari) {
		foreach($hari as $idxJam => $jam) {
			$arrayHari[$idxHari][$idxJam]["rawHtml"] = "";
			foreach($jam["arrayMatkul"] as $idxArray => $matkul) {
				$rawHtml = "<div class='data " . 
				$jam["arrayIdxMatkul"][$idxArray] . "' id=''><strong>( </strong><span class='matkul'>" . "<strong>[" . 
				$jam["arrayIdxMatkul"][$idxArray] . "] </strong>" . $jam["arrayMatkul"][$idxArray] . "</span><strong> )</strong> - <span class='ruangan'>" . $jam["arrayRuangan"][$idxArray] . "</span></div>";
				$arrayHari[$idxHari][$idxJam]["rawHtml"] .= $rawHtml;
			}
		}
	}

	function echoRawHtml($idxHari, $idxJam) {
		global $arrayHari;
		$rawHtml = "";
		foreach($arrayHari[$idxHari][$idxJam]["arrayMatkul"] as $idxArray => $matkul) {
			$rawHtml .= "<div class='data " . 
			$arrayHari[$idxHari][$idxJam]["arrayIdxMatkul"][$idxArray] . "' id=''><strong>( </strong><span class='matkul'>" . "<strong>[" .
			$arrayHari[$idxHari][$idxJam]["arrayIdxMatkul"][$idxArray] . "] </strong>" .
			$arrayHari[$idxHari][$idxJam]["arrayMatkul"][$idxArray] . "</span><strong> )</strong> - <span class='ruangan'>" .
			$arrayHari[$idxHari][$idxJam]["arrayRuangan"][$idxArray] . "</span></div>";
		}
		echo $rawHtml;
	}

	function echoRawHtmlPerRuangan($idxRuangan, $idxHari, $idxJam) {
		global $arrayHari;
		$rawHtml = "";
		foreach($arrayHari[$idxHari][$idxJam]["arrayMatkul"] as $idxArray => $matkul) {
			if ($arrayHari[$idxHari][$idxJam]["arrayIdxRuangan"][$idxArray] == $idxRuangan) {
				$rawHtml .= "<div class='data " . 
				$arrayHari[$idxHari][$idxJam]["arrayIdxMatkul"][$idxArray] . "'><strong>( </strong><span class='matkul'>" . "<strong>[" .
				$arrayHari[$idxHari][$idxJam]["arrayIdxMatkul"][$idxArray] . "] </strong>" .
				$arrayHari[$idxHari][$idxJam]["arrayMatkul"][$idxArray] . "</span><strong> )</strong> - <span class='ruangan'>" .
				$arrayHari[$idxHari][$idxJam]["arrayRuangan"][$idxArray] . "</span></div>";
			}
		}
		echo $rawHtml;
	}

	$jmlRuangan = count($arrayRuangan);
	$jmlMatkul = 0;
	if ($jmlRuangan > 0) {
		$jmlMatkul = count($arrayRuangan[0]) - 1;
	}
	$jmlBentrok = cekAllBentrok($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);
	$persenTerisi = persenTerisi($arrayRuangan,$jmlRuangan,$jmlMatkul,$indexRuangan);


//-------------------------------------TABLE COLORING PART------------------------------------
// author : ramosj.noah
	function isCellBentrok($idxRuangan, $idxHari, $idxJam) {
		global $arrayHari;
		$count = 0;
		foreach($arrayHari[$idxHari][$idxJam]["arrayMatkul"] as $idxArray => $matkul) {
			$count++;
		}
		if ($count > 1) {
			return true;
		} else {
			return false;
		}
	}

	// Konstruksi
	$tabelwarna = array();
	for ($i = 0 ; $i < $jmlRuangan; $i++) {
		array_push($tabelwarna,array());
	}

	function giveColor($idx) {
		if ($idx == 0) {
			echo "white";
		} elseif ($idx == -1) {
			echo "red";
		} elseif ($idx == 1) {
			echo "#a7c6e5";
		} elseif ($idx == 2) {
			echo "#8df48b";
		} elseif ($idx == 3) {
			echo "#f4f38b";
		} elseif ($idx == 4) {
			echo "#dea7e5";
		}
	}

	function bgCell($idxRuangan, $idxHari, $idxJam) {
		global $tabelwarna;
		$jam = $idxHari*11 + $idxJam;
		echo giveColor($tabelwarna[$idxRuangan][$jam]);
	}

	function tableColoring(){
		global $tabelwarna;
		global $arrayRuangan;
		global $jmlRuangan;
		global $jmlMatkul;
		// Inisiasi
		for ($i = 0; $i <$jmlRuangan ; $i++) {
			for ($j = 0; $j <= 54 ; $j++) {
				$tabelwarna[$i][$j] = 0;
			}
		}
		for ($rgn = 0; $rgn < $jmlRuangan ; $rgn++) {
			for ($i=0 ; $i <= 54 ; $i++) {
				$matkulInCell = 0;
				for ($idxMatkul=0 ; $idxMatkul < $jmlMatkul; $idxMatkul++) {

					if ($arrayRuangan[$rgn][$idxMatkul][$i][1] == true) {
						// Kalau di pertama ada matkul
						if ($i==0) {
							$tabelwarna[$rgn][$i] = 1;
							$matkulInCell++;
						} // Kalau atasnya putih, dan ada matkul disitu

						elseif (($i>0) && ($tabelwarna[$rgn][$i-1] == 0) ) {
							$tabelwarna[$rgn][$i] = 1;
							$matkulInCell++;
						} // Kalau atasnya bukan putih, ada matkul, dan matkulnya sama
						elseif (($i>0) && ($tabelwarna[$rgn][$i-1] != 0) && ($arrayRuangan[$rgn][$idxMatkul][$i-1][1] == true)){

							$tabelwarna[$rgn][$i] = $tabelwarna[$rgn][$i-1];
							$matkulInCell++;
						} // Kalau atasnya bukan putih, ada matkul, dan matkulnya beda
						elseif (($i>0) && ($tabelwarna[$rgn][$i-1] != 0) && ($arrayRuangan[$rgn][$idxMatkul][$i-1][1] == false)){
							if ($tabelwarna[$rgn][$i-1] == 1) {
								$tabelwarna[$rgn][$i] = 2;
							} else {
								$tabelwarna[$rgn][$i] = 1;
							}
							$matkulInCell++;
						}

						// Kalau atasnya merah, langsung kasih 1.
						if (($i > 0) && ($tabelwarna[$rgn][$i-1] == -1) && ($arrayRuangan[$rgn][$idxMatkul][$i][1] == true)) {
							$tabelwarna[$rgn][$i] = 1;	
						}	

						// Kalau tabelwarna dengan index -11 sama, maka kembali ke index i terkecil dengan warna yang sama berturut-turu
						if ($i>10) { 
							if ($tabelwarna[$rgn][$i]==$tabelwarna[$rgn][$i-11]) {
								while ($tabelwarna[$rgn][$i]==$tabelwarna[$rgn][$i-1]) {
									$i = $i-1;
								}
								$tabelwarna[$rgn][$i]++;
								while (($tabelwarna[$rgn][$i]==$tabelwarna[$rgn][$i-1]) || ($tabelwarna[$rgn][$i] == $tabelwarna[$rgn][$i-11])) {
									$tabelwarna[$rgn][$i]++;							
								}
							}
						}
					}
				} 
				// Kalau i-1 nya sama dengan current i, berarti tabelwarna++, kecuali index matkulnya sama.
				// Kalau matkulInCell lebih dari 1, dijadiin Merah.
				if ($matkulInCell > 1) {
					$tabelwarna[$rgn][$i] = -1;
				} 
			}
		}
	}

	tableColoring();
//------------------------------------------------------------------------------------------------

	?>
	<div class="container" id="result">
		<!-- MASTER -->
		<div class="container" id="master">
			<h1 class="resulttitle">Jadwal Mata Kuliah dan Ruangannya</h1>
			<?php
			$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
			$url .= $_SERVER['SERVER_NAME'];
			$url .= $_SERVER['REQUEST_URI'];
			?>
			<a href="<?php echo dirname($url) ?>"><h3 class="resulttitle">back to front page</h3></a>
			<table class="tabledefault" style="width:100%">
				<tr class="">
					<th class="tabledefault" style="background-color: rgba(0,0,0,0.3);">Jam \ Hari</th>
					<th class="tableheading tabledefault">Senin</th>
					<th class="tableheading tabledefault">Selasa</th>
					<th class="tableheading tabledefault">Rabu</th>
					<th class="tableheading tabledefault">Kamis</th>
					<th class="tableheading tabledefault">Jumat</th>
				</tr>
				<?php for($idxJam = 0; $idxJam < 11; $idxJam++) {?>
				<tr class="">
					
					<td class="tablejam" id="jam"> <?php echo $idxJam+7 . ":00&nbsp;" . "(" . $idxJam . ")&nbsp;" ?> </td>

					<td class="tabledefault tableharijam" id= <?php echo "senin" . $idxJam ?>><?php echoRawHtml(0,$idxJam) ?></td>
					<td class="tabledefault tableharijam" id= <?php echo "selasa" . $idxJam ?>><?php echoRawHtml(1,$idxJam) ?></td>
					<td class="tabledefault tableharijam" id= <?php echo "rabu" . $idxJam ?>><?php echoRawHtml(2,$idxJam) ?></td>
					<td class="tabledefault tableharijam" id= <?php echo "kamis" . $idxJam ?>><?php echoRawHtml(3,$idxJam) ?></td>
					<td class="tabledefault tableharijam" id= <?php echo "jumat" . $idxJam ?>><?php echoRawHtml(4,$idxJam) ?></td>
				</tr>
				<?php } ?>
			</table>
			<h3 class="jumlahbentrok">Jumlah Bentrok: <?php echo $jmlBentrok ?></h3>
			<h3 class="jumlahbentrok">Persen Terisi: <?php echo $persenTerisi ?> %</h3>
			
			<form action="modifyJadwal.php" method="post">
				<h2>Modify Jadwal</h2>
				Matkul yang ingin dipindah:&nbsp;
				<input type="text" name="changeMatkul" id="changeMatkul">
				&nbsp;Pindahkan ke:&nbsp;
				<input type="text" name="pindahKe" id="pindahKe">
				<input type="submit">
				<p>You can do point and click on the table to change it and click 'Submit'</p>
			</form>			

			<?php if (isset($_SESSION['JamFit'])) {
						$JamFit = $_SESSION['JamFit'];
						if ($JamFit) { 
			?>
			<?php } else { 
			?>	
						<div class = "changeinvalid">
						<p>Pemindahan sebelumnya tidak valid.</p>	
						</div>
			<?php
						 }
					} 
			?>

		</div>

		<!-- PER RUANGAN -->
		<div class="container" id="perruangan">
			<h1 class="resulttitle" style="padding-bottom: 1		em;">Jadwal Matkul Per Ruangan</h1>
			<form action="modifyJadwal.php" method="post">
				<h2>Modify Jadwal</h2>
				Matkul yang ingin dipindah:&nbsp;
				<input type="text" name="changeMatkul" id="changeMatkul">
				&nbsp;Pindahkan ke:&nbsp;
				<input type="text" name="pindahKe" id="pindahKe">
				<input type="submit">
				<p>You can do point and click on the table to change it and click 'Submit'</p>
			</form>			


			<?php foreach($arrayRuangan as $idxRuangan => $ruangan) { ?>
				<div class='jadwalperruangan'>
					<h2 class="resulttitle"><?php echo $indexRuangan[$idxRuangan] ?></h2>
					<table class="tabledefault table" id = "<?php echo $indexRuangan[$idxRuangan] ?>" style="width:100%">
						<tr class="">
							<th class="tabledefault" style="background-color: rgba(0,0,0,0.3);">Jam \ Hari</th>
							<th class="tableheading tabledefault">Senin</th>
							<th class="tableheading tabledefault">Selasa</th>
							<th class="tableheading tabledefault">Rabu</th>
							<th class="tableheading tabledefault">Kamis</th>
							<th class="tableheading tabledefault">Jumat</th>
						</tr>
						<?php 

						for($idxJam = 0; $idxJam < 11; $idxJam++) {?>
						<tr class="">
							<td class="tablejam" id="jam"> <?php echo $idxJam+7 . ":00&nbsp;" . "(" . $idxJam . ")&nbsp;" ?> </td>
							<td style="background-color: <?php bgCell($idxRuangan,0,$idxJam)?>";
							class="tabledefault tableharijam" id= <?php echo "senin" . $idxJam."-".$idxRuangan?>><?php echoRawHtmlPerRuangan($idxRuangan,0,$idxJam)?></td>
							<td style="background-color: <?php bgCell($idxRuangan,1,$idxJam)?>";
							class="tabledefault tableharijam" id= <?php echo "selasa" . $idxJam."-".$idxRuangan?>><?php echoRawHtmlPerRuangan($idxRuangan,1,$idxJam)?></td>
							<td style="background-color: <?php bgCell($idxRuangan,2,$idxJam)?>";
							class="tabledefault tableharijam" id= <?php echo "rabu" . $idxJam."-".$idxRuangan?>><?php echoRawHtmlPerRuangan($idxRuangan,2,$idxJam)?></td>
							<td style="background-color: <?php bgCell($idxRuangan,3,$idxJam)?>";
							class="tabledefault tableharijam" id= <?php echo "kamis" . $idxJam."-".$idxRuangan?>><?php echoRawHtmlPerRuangan($idxRuangan,3,$idxJam)?></td>
							<td style="background-color: <?php bgCell($idxRuangan,4,$idxJam)?>";
							class="tabledefault tableharijam" id= <?php echo "jumat" . $idxJam."-".$idxRuangan?>><?php echoRawHtmlPerRuangan($idxRuangan,4,$idxJam)?></td>
						</tr>
						<?php 

						} ?>
					</table>
				</div>
			<?php } ?>
		</div>
	</div>
</body>

	<script src="js/jquery-3.1.1.min.js" ></script>
	<script type="text/javascript" src="js/script.js" ></script>

</html>