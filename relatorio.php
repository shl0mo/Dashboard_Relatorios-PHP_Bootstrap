<?php
	session_start();
	$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
	$query = $pdo->prepare('SELECT * FROM dados WHERE fk_usuario = "'.$_SESSION['session'].'";');
	$query->execute();
	$data = $query->fetchAll();

	$total_contacts = count($data);
	$total_schedules = 0;
	foreach ($data as $value) {
		if ($value['agendou'] == 'Sim') {
			$total_schedules++;
		}
	}
	$schedule_rate = $total_schedules/$total_contacts * 100;
	$clinical_schedules = 0;
	foreach ($data as $value) {
		if ($value['area'] == 'Demartologia clínica' && $value['agendou'] == 'Sim') {
			$clinical_schedules++;
		}
	}
	$stetical_schedules = 0;
	foreach ($data as $value) {
		if ($value['area'] == 'Demartologia estética' && $value['agendou'] == 'Sim') {
			$stetical_schedules++;
		}
	}

	echo '<h1>Números principais</h1>';
	echo '<h2>Total agendamentos: '.$total_schedules.'</h2>';
	echo '<h2>Taxa de agendamento: '.round($schedule_rate, 2).'%</h2>';
	echo '<h2>Número tde cancelamentos: </h2>';
	echo '<h2>Taxa de cancelamento: </h2>';
	echo '<h2>Número de não comparecimentos: </h2>';
	echo '<h2>Taxa de não comparecimeto</h2>';
	echo '<h1>Números secundários (demartologista)</h1>';
	echo '<h2>Número de agendamentos - Demartologia Clínica: '.$clinical_schedules.'<h2>';
	echo '<h2>Número de agendamentos - Demartologia Estética: '.$stetical_schedules.'<h2>';

	$dataPoints = array();
	$dates = array();
	foreach ($data as $value) {
		array_push($dates, $value['data_agendamento']);
	}
	$unique_dates = array_unique($dates);
	foreach ($unique_dates as $i) {
		$total_schedules = 0;
		$total_contacts = 0;
		foreach ($data as $j) {
			if ($j['data_agendamento'] == $i) {
				if ($j['agendou'] == 'Sim') {
					$total_schedules++;
				}
				$total_contacts++;
			}
		}
		array_push($dataPoints, array('y' => $total_contacts, 'label' => $i));
	}
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
				type: "line",
				yValueFormatString: "#,##0.## tonnes",
				dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chart.render();
	}
</script>
</head>
<body>
	<div id="chartContainer" style="height: 370px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
