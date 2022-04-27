<?php
	session_start();
	if (!isset($_SESSION['session'])) {
		header('Location: http://localhost/Relatorios/login.php');
		exit();	
	}
	$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
	$query = $pdo->prepare('SELECT * FROM dados WHERE fk_usuario = "'.$_SESSION['session'].'";');
	$query->execute();
	$data = $query->fetchAll();

	$total_contacts = count($data);
	$total_schedules = 0;
	$clinical_schedules = 0;
	$stetical_schedules = 0;
	$total_cancellations = 0;
	foreach ($data as $value) {
		if ($value['status'] == 'Agendado') {
			$total_schedules++;
		}
		if ($value['area'] == 'Demartologia clínica' && $value['status'] == 'Agendado') {
			$clinical_schedules++;
		}
		if ($value['area'] == 'Demartologia estética' && $value['status'] == 'Agendado') {
			$stetical_schedules++;
		}
		if ($value['status'] == 'Cancelado') {
			$total_cancellations++;
		}
	}
	$schedule_rate = $total_schedules/$total_contacts * 100;
	$cancellation_rate = $total_cancellations/$total_contacts * 100;
	
	echo '<h1>Números principais</h1>';
	echo '<h2>Total agendamentos: '.$total_schedules.'</h2>';
	echo '<h2>Taxa de agendamento: '.round($schedule_rate, 2).'%</h2>';
	echo '<h2>Número de cancelamentos: </h2>';
	echo '<h2>Taxa de cancelamento: </h2>';
	echo '<h2>Número de não comparecimentos: </h2>';
	echo '<h2>Taxa de não comparecimeto</h2>';
	echo '<h1>Números secundários (demartologista)</h1>';
	echo '<h2>Número de agendamentos - Demartologia Clínica: '.$clinical_schedules.'<h2>';
	echo '<h2>Número de agendamentos - Demartologia Estética: '.$stetical_schedules.'<h2>';

	$dates = array();
	$channels = array();
	$cities = array();
	$contact_types = array();
	$justifications = array();
	foreach ($data as $value) {
		array_push($dates, $value['data_agendamento']);
		array_push($channels, $value['canal_origem']);
		array_push($cities, $value['cidade']);
		array_push($contact_types, $value['tipo_contato']);
	}

	$unique_dates = array_unique($dates);
	$dataPoints_contacts = array();
	$dataPoints_schedules = array();
	foreach ($unique_dates as $i) {
		$total_schedules = 0;
		$total_contacts = 0;
		foreach ($data as $j) {
			if ($j['data_agendamento'] == $i) {
				if ($j['status'] == 'Agendado') {
					$total_schedules++;
				}
				$total_contacts++;
			}
		}
		array_push($dataPoints_contacts, array('y' => $total_contacts, 'label' => $i));
		array_push($dataPoints_schedules, array('y' => $total_schedules, 'label' => $i));	
	}

	$unique_channels = array_unique($channels);
	$dataPoints_channels_total = array();
	$dataPoints_channels_perc = array();
	foreach ($unique_channels as $i) {
		$total_channel = 0;
		$total_contacts = 0;
		foreach ($data as $j) {
			if ($j['canal_origem'] == $i) {
				$total_channel++;
			}
			$total_contacts++;
		}
		$perc_channel = $total_channel/$total_contacts;
		array_push($dataPoints_channels_total, array('y' => $total_channel, 'label' => $i));
		array_push($dataPoints_channels_perc, array('y' => $perc_channel, 'label' => $i));
	}

	$unique_cities = array_unique($cities);
	$dataPoints_cities_total = array();
	$dataPoints_cities_perc = array();
	$times = 0;
	foreach ($unique_cities as $i) {
		$total_city = 0;
		$total_contacts = 0;
		foreach ($data as $j) {
			if ($j['cidade'] == $i) {
				$total_city++;
			}
			$total_contacts++;
		}
		$perc_city = $total_city/$total_contacts;
		array_push($dataPoints_cities_total, array('y' => $total_city, 'label' => $i));
		array_push($dataPoints_cities_perc, array('y' => $perc_city, 'label' => $i));
		$i++;
		if ($times == 5) {
			break;
		}
	}

	$unique_contact_types = array_unique($contact_types);
	$dataPoints_contact_types = array();
	foreach ($unique_contact_types as $i) {
		$total_contact_type = 0;
		$total_contacts = 0;
		foreach ($data as $j) {
			if ($j['tipo_contato'] == $i) {
				$total_contact_type++;	
			}
			$total_contacts++;
		}
		$perc_contact_type = $total_contact_type/$total_contacts;
		array_push($dataPoints_contact_types, array('label' => $i, 'y' => $perc_contact_type));
	}

	$unique_justifications = array_unique($justifications);
	$dataPoints_justifications = array();
	
?>
<!DOCTYPE HTML>
<html>
<head>
<style>
	.graph {
		min-height: 370px;
		width: 50%;
	}
</style>
	<script>
		window.onload = function() {
 
			const contacts_schedules_chart = new CanvasJS.Chart("contacts-schedules-chartContainer", {
				theme: 'light1',
				title: {
					text: "Contatos e agendamentos"
				},
				axisY: {
					title: "Total"
				},
				data: [{
					type: "line",
					yValueFormatString: "#,##0.## tonnes",
					dataPoints: <?php echo json_encode($dataPoints_contacts, JSON_NUMERIC_CHECK); ?>,
					legendText: 'Contatos',
					showInLegend: true
				},
				{
					type: "line",
					yValueFormatString: "#,##0.## tonnes",
					dataPoints: <?php echo json_encode($dataPoints_schedules, JSON_NUMERIC_CHECK); ?>,
					legendText: 'Agendamentos',
					showInLegend: true
				}]
			})

			const channels_chart_total = new CanvasJS.Chart('channels-total-chartContainer', {
				theme: 'light1',
				title: {
					text: 'Gráfico de barras horizontais dos canais de origem em números absolutos'	
				},
				axisY: {
					title: 'Total'
				},
				data: [{
					type: 'bar',					
					yValueFormatString: "#,##0.##",
					indexLabel: '{y}',
					indexLabelPlacement: 'outside',
					indexLabelFontWeight: 'bolder',
					dataPoints: <?php echo json_encode($dataPoints_channels_total, JSON_NUMERIC_CHECK);?>
				}]
			})

	
			const channels_chart_perc = new CanvasJS.Chart('channels-perc-chartContainer', {
				title: {
					text: 'Gráfico de barras horizontais dos canais de origem em números absolutos'	
				},
				axisY: {
					title: 'Porcentagem'
				},
				data: [{
					type: 'bar',					
					yValueFormatString: "#,##0.##%",
					indexLabel: '{y}',
					indexLabelPlacement: 'outside',
					indexLabelFontWeight: 'bolder',
					dataPoints: <?php echo json_encode($dataPoints_channels_perc, JSON_NUMERIC_CHECK);?>
				}]
			})

			const cities_chart_total = new CanvasJS.Chart('cities-total-chartContainer', {
				title: {
					text: 'Gráfico de barras de ocorrências das cinco principais cidades'	
				},
				axisY: {
					title: 'Total'
				},
				data: [{
					type: 'column',
					yValueFormatString: "#,##0.##",
					indexLabel: '{y}',
					indexLabelPlacement: 'outside',
					indexLabelFontWeight: 'bolder',
					dataPoints: <?php echo json_encode($dataPoints_cities_total, JSON_NUMERIC_CHECK);?>
				}]
			})

			const cities_chart_perc = new CanvasJS.Chart('cities-perc-chartContainer', {
				title: {
					text: 'Gráfico de barras das porcentagens das cinco principais cidades'	
				},
				axisY: {
					title: 'Porcentagem'
				},
				data: [{
					type: 'column',
					yValueFormatString: "#,##0.##%",
					indexLabel: '{y}',
					indexLabelPlacement: 'outside',
					indexLabelFontWeight: 'bolder',
					dataPoints: <?php echo json_encode($dataPoints_cities_perc, JSON_NUMERIC_CHECK);?>
				}]
			})

			const contact_type_chart = new CanvasJS.Chart('contact-type-chartContainer', {				
				title: {
					text: 'Gráfico de setores das porcentagens das formas de contato'	
				},
				data: [{
					type: 'pie',
					yValueFormatString: "#,##0.00%",
					indexLabel: "{label} ({y})",
					dataPoints: <?php echo json_encode($dataPoints_contact_types, JSON_NUMERIC_CHECK);?>
				}]
			})


	
			contacts_schedules_chart.render()
			channels_chart_total.render()
			channels_chart_perc.render()
			cities_chart_total.render()
			cities_chart_perc.render()
			contact_type_chart.render()
		}
	</script>
</head>
<body>
	<div id="contacts-schedules-chartContainer" class="graph"></div>
	<div id="channels-total-chartContainer" class="graph"></div>
	<div id="channels-perc-chartContainer" class="graph"></div>
	<div id="cities-total-chartContainer" class="graph"></div>
	<div id="cities-perc-chartContainer" class="graph"></div>
	<div id="contact-type-chartContainer" class="graph"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
