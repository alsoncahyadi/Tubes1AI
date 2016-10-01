<!DOCTYPE html>
<html>
<head>
	<title>AI front page</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="js/script.js" ></script>
</head>
<body>
	<div class="ai-container">
		<h1 class="frontpagetitle">AI FRONT PAGE</h1>
		<div class="text-centered">
			<p>Selesaikan testcase dengan algoritma:<p>
			<form id="SA" action="SA.php" method="post">
				<button type="submit" class="button" onclick="log()" name="SA">Simulated Annealing</button>
				Jumlah Langkah : <input type = "number" name="jumlahLangkah" value="700">
			</form>
			<form id="HC" action="HC.php" method="post">
				<button type="submit" class="button" name="HC">Hill Climbing</button>
				Iterasi Maksimum : <input type = "number" name="iterMax" value="1000"> 
			</form>
			<form id="GA" action="GA.php" method="post">
				<button type="submit" class="button" name="GA">Genetics Algorithm</button>
			</form>
			<!--
			<br> <br>
			Testcase file name:
			<input type = "text" name="filename" value="input.txt" form="SA"> -->
		</div>
	</div>
</body>
</html>