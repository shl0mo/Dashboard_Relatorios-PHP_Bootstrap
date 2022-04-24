<?php

	$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
	$query = $pdo->prepare('SELECT * FROM dados');
	$query->execute();
	$data = $query->fetchAll();	

	$dataPoints = array(
		array('y' => count($data), 'label' => 'Total')
	); 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
	window.onload = function() {
 
		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			theme: "light2",
			title:{
				text: "Gráfico em barras verticais do número de registros"
			},
			axisY: {
				title: "Número de registros"
			},
			data: [{
				type: "column",
				yValueFormatString: "#,##0.## tonnes",
				dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chart.render();
	}
</script>
</head>
<body>
	<h1>Número de registros <?php echo count($data)?> </h1>
	<div id="chartContainer" style="height: 370px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
