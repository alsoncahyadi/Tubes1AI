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
			<form action="readfile.php" method="post">
				<button type="submit" class="button" onclick="log()" name="SA">Simulated Annealing</button>
			</form>
			<form action="readfile.php" method="post">
				<button type="submit" class="button" name="HC">Hill Climbing</button>
			</form>
			<form action="readfile.php" method="post">
				<button type="submit" class="button" name="GA">Genetics Algorithm</button>
			</form>
		</div>
	</div>
</body>
</html>