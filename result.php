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
				$rawHtml .= "<div class='dataperruangan " . 
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


			<form action="modifyJadwal.php" method="post">
				<h2>Change Matkul</h2>
				Matkul yang ingin dipindah:&nbsp;
				<input type="text" name="changeMatkul" id="changeMatkul">
				&nbsp;Pindahkan ke:&nbsp;
				<input type="text" name="pindahKe" id="pindahKe"> <br>
				<input type="submit">
			</form>
		</div>

		<!-- PER RUANGAN -->
		<div class="container" id="perruangan">
			<h1 class="resulttitle" style="padding-bottom: 3em;">Jadwal Matkul Per Ruangan</h1>
			<?php foreach($arrayRuangan as $idxRuangan => $ruangan) { ?>
				<div class='jadwalperruangan'>
					<h2 class="resulttitle"><?php echo $indexRuangan[$idxRuangan] ?></h2>
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

							<td class="tabledefault" id= <?php echo "senin" . $idxJam ?>><?php echoRawHtmlPerRuangan($idxRuangan,0,$idxJam) ?></td>
							<td class="tabledefault" id= <?php echo "selasa" . $idxJam ?>><?php echoRawHtmlPerRuangan($idxRuangan,1,$idxJam) ?></td>
							<td class="tabledefault" id= <?php echo "rabu" . $idxJam ?>><?php echoRawHtmlPerRuangan($idxRuangan,2,$idxJam) ?></td>
							<td class="tabledefault" id= <?php echo "kamis" . $idxJam ?>><?php echoRawHtmlPerRuangan($idxRuangan,3,$idxJam) ?></td>
							<td class="tabledefault" id= <?php echo "jumat" . $idxJam ?>><?php echoRawHtmlPerRuangan($idxRuangan,4,$idxJam) ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>
			<?php } ?>
		</div>
	</div>
</body>

	<script src="js/jquery-3.1.1.min.js" ></script>
	<script type="text/javascript" src="js/script.js" ></script>
</html>