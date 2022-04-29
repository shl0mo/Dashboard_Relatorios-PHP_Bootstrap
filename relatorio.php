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
	$total_missings = 0;
	foreach ($data as $value) {
		if ($value['status'] == 'Agendado') {
			$total_schedules++;
		}
		if ($value['area'] == 'Dermatologia clínica' && $value['status'] == 'Agendado') {
			$clinical_schedules++;
		}
		if ($value['area'] == 'Dermatologia estética' && $value['status'] == 'Agendado') {
			$stetical_schedules++;
		}
		if ($value['status'] == 'Cancelado') {
			$total_cancellations++;
		}
		if ($value['status'] == 'Não compareceu') {
			$total_missings++;
		}
	}
	$schedule_rate = round($total_schedules/$total_contacts * 100, 2);
	$cancellation_rate = round($total_cancellations/$total_contacts * 100, 2);
	$missing_rate = round($total_missings/$total_contacts *100, 2);
	
	echo '<h1>Números principais</h1>';
	echo '<h2>Total agendamentos: '.$total_schedules.'</h2>';
	echo '<h2>Taxa de agendamento: '.$schedule_rate.'%</h2>';
	echo '<h2>Número de cancelamentos: '.$total_cancellations.'</h2>';
	echo '<h2>Taxa de cancelamento: '.$cancellation_rate.'%</h2>';
	echo '<h2>Número de não comparecimentos: '.$total_missings.'</h2>';
	echo '<h2>Taxa de não comparecimeto '.$missing_rate.'%</h2>';
	echo '<h1>Números secundários (demartologista)</h1>';
	echo '<h2>Número de agendamentos - Dermatologia Clínica: '.$clinical_schedules.'<h2>';
	echo '<h2>Número de agendamentos - Dermatologia Estética: '.$stetical_schedules.'<h2>';

	$dates = array();
	$channels = array();
	$cities = array();
	$contact_types = array();
	$justifications_schedule = array();
	foreach ($data as $value) {
		array_push($dates, $value['data_agendamento']);
		array_push($channels, $value['canal_origem']);
		array_push($cities, $value['cidade']);
		array_push($contact_types, $value['tipo_contato']);
		if ($value['motivo_agendamento'] != null) array_push($justifications_schedule, $value['motivo_agendamento']);
	}

	$unique_dates = array_unique($dates);
	$dataPoints_contacts = array();
	$dataPoints_schedules = array();
	foreach ($unique_dates as $i) {
		$total_schedules = 0;
		$total_contacts = 0;
		foreach ($data as $j) {
			if ($j['data_agendamento'] == $i) {
				if ($j['status'] == 'Agendado') $total_schedules++;
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
			if ($j['canal_origem'] == $i) $total_channel++;
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
			if ($j['cidade'] == $i) $total_city++;
			$total_contacts++;
		}
		$perc_city = $total_city/$total_contacts;
		array_push($dataPoints_cities_total, array('y' => $total_city, 'label' => $i));
		array_push($dataPoints_cities_perc, array('y' => $perc_city, 'label' => $i));
		$times++;
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
			if ($j['tipo_contato'] == $i) $total_contact_type++;
			$total_contacts++;
		}
		$perc_contact_type = $total_contact_type/$total_contacts;
		array_push($dataPoints_contact_types, array('label' => $i, 'y' => $perc_contact_type));
	}

	$unique_justifications = array_unique($justifications_schedule);
	$dataPoints_justifications = array();
	foreach ($unique_justifications as $i) {
		$total_justification = 0;
		$total_contacts = 0;
		foreach ($data as $j) {
			if ($j['motivo_agendamento'] == $i) $total_justification++;
			if ($j['motivo_agendamento'] != null) $total_contacts++;
		}
		$perc_justification = $total_justification/$total_contacts;
		array_push($dataPoints_justifications, array('label' => $i, 'y' => $perc_justification));
	}
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
					text: 'Gráfico de barras horizontais dos canais de origem em porcentagem'	
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
			
			const justification_chart = new CanvasJS.Chart('justification-chartContainer', {
				title: {
					text: 'Gráfico de setores das porcentagens dos motivos de não agendamento'	
				},
				data: [{
					type: 'pie',
					yValueFormatString: "#,##0.00%",
					indexLabel: "{label} ({y})",
					dataPoints: <?php echo json_encode($dataPoints_justifications, JSON_NUMERIC_CHECK);?>
				}]
			})
	
			contacts_schedules_chart.render()
			channels_chart_total.render()
			channels_chart_perc.render()
			cities_chart_total.render()
			cities_chart_perc.render()
			contact_type_chart.render()
			justification_chart.render()
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
	<div id="justification-chartContainer" class="graph"></div>
	<div class="container-relatory">
		<h1>Outros motivos para o não agendamento</h1>
		<?php
			foreach ($data as $value) {
				if ($value['outros_agendamento'] != null) {
					echo '<h2>'.$value['outros_agendamento'].'</h2>';
				}
			}
		?>
	</div>
	<div class="container-relatory">
		<h1>Outros motivos para o cancelamento</h1>
	</div>
	<div class="container-relatory">
		<h1>Outros motivos para o não comparecimento</h1>
	</div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
