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
			<form class="ai-form" action="SA.php" method="post">
				<button type="submit" class="button" onclick="log()" name="SA">Simulated Annealing</button><br>
				Jumlah Langkah : <input class="number" type = "number" name="jumlahLangkah" value="700">
			</form>
			<form class="ai-form" action="HC.php" method="post">
				<button type="submit" class="button" name="HC">Hill Climbing</button><br>
				Iterasi Maksimum : <input class="number" type = "number" name="iterMax" value="1000">
			</form>
			<form class="ai-form" action="GA.php" method="post">
				<button type="submit" class="button" name="GA">Genetics Algorithm</button><br>
				Iterasi Maksimum : <input class="number" type = "number" name="maxStep" value="1000">
			</form>
		</div>
	</div>
</body>
</html>