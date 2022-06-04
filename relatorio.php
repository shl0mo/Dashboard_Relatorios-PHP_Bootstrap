<?php
	session_start();
	if (!isset($_SESSION['session'])) {
		header('Location: http://localhost/Relatorios/login.php');
		exit();	
	}
	if (isset($_POST['logout'])) {
		session_destroy();
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
	$total_channels = array();
	$total_channels_schedules = array();
	$dataPoints_channels_total = array();
	$dataPoints_channels_perc = array();
	$matrix_justifications = array();
	foreach ($unique_channels as $i) {
		$total_channel = 0;
		$total_contacts = 0;
		$channel_schedules = 0;
		$array_justifications = array();
		foreach ($data as $j) {
			if ($j['canal_origem'] == $i) $total_channel++;
			if ($j['canal_origem'] == $i && $j['status'] == 'Agendado') $channel_schedules++;
			if ($j['canal_origem'] == $i && $j['motivo_agendamento'] != null) array_push($array_justifications, $j['motivo_agendamento']);
			$total_contacts++;
		}
		$perc_channel = $total_channel/$total_contacts;
		array_push($dataPoints_channels_total, array('y' => $total_channel, 'label' => $i));
		array_push($dataPoints_channels_perc, array('y' => $perc_channel, 'label' => $i));
		array_push($total_channels, $total_channel);
		array_push($total_channels_schedules, $channel_schedules);
		array_push($matrix_justifications, $array_justifications);
	}

	$dataPoints_channels_justifications = array();
	foreach ($matrix_justifications as $line) {
		$dataPoint_line = array();
		foreach ($line as $i) {
			$total_justification = 0;
			$total_occurrences = 0;
			foreach ($line as $j) {
				if ($i == $j) $total_justification++;
				$total_occurrences++;	
			}
			$percentage = round($total_justification/$total_occurrences * 100, 2);
			array_push($dataPoint_line, array('label' => $i, 'y' => $percentage));
		}
		array_push($dataPoints_channels_justifications, $dataPoint_line);
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
	<meta charset="UTF-8"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<link rel="stylesheet" href="./css/style.css"/>
	<style>
		table {
			width: 65%;
			align-self: center;
			margin-bottom: 50px;
		}

		table li {
			font-size: 20px;
		}

		.graph {
			display: flex;
			align-self: center;
			min-height: 370px;
			width: 70%;
			margin: 30px 0px;	
		}
		
		.container-relatory {
			display: flex;
			flex-direction: column;
		}		

		.container-relatory h1 {
			font-size: 30px;
			display: flex;
			align-self: center;
		}

		.container-relatory h2 {
			font-size: 25px;
			align-self: center;
			margin-top: 20px;
		}

		.channels-table {
			width: 40%;
		}
		
		.channels-table td {
			font-size: 20px;
			padding: 8px;
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
	<header class="mb-0 bg-dark w-100 p-2" style="height: 100px;">
		<nav class="navbar navbar-dark d-flex flex-direction-column w-100 align-items-center">
			<div class="container-fluid col w-100 text-light">
				<h1><?php echo '&bull; Dr. '.$_SESSION['name'];?></h1>
			</div>
			<div class="container-fluid col-md-1 d-flex w-100">
				<form method="post" class="d-flex w-100">
					<button type="submit" name="logout" class="btn btn-danger text-center p-2 w-100">Sair</button>
				</form>
			</div>
		</nav>
	</header>
	<div class="main-container d-flex flex-direction-row h-100 mb-0 w-100">
		<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark col-md-2" style="width: 280px;">
		    <ul class="nav nav-pills flex-column mb-auto">
	  	      <hr>
			<li>
			<a href="./" class="nav-link text-white">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
				<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
				</svg>
				 Novo contato
			</a>
		      </li>
		      <hr/>
		      <li>
			<a href="./listar.php" class="nav-link text-white">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
				<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
				</svg>
			  Editar contato
			</a>
		      </li>
		      <hr/>
		      <li>
			<a href="./relatorio.php" class="nav-link text-white active">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
				<path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z"/>
				</svg>
			  Relatório
			</a>
		      </li>
	              <hr/>
	 	</ul>
		</div>

	<div class="w-100 d-flex justify-content-center flex-column">
		<div>
			<div class="d-flex flex-column justify-center text-center mt-5">
				<h2>Números principais</h2>
				<table class="channels-table border">
					<tbody>
					<tr>
						<td class="border text-left">Total agendamentos</td>
						<td class="border"><?php echo $total_schedules ?></td>
					</tr>
					<tr>
						<td class="border text-left">Taxa de agendamento</td>
						<td class="border"><?php echo $schedule_rate.'%' ?></td>
					</tr>
					<tr>
						<td class="border text-left">Número de cancelamentos</td>
						<td class="border"><?php echo $total_cancellations ?></td>
					</tr>
					<tr>
						<td class="border text-left">Taxa de cancelamento</td>
						<td class="border"><?php echo $cancellation_rate.'%' ?></td>
					</tr>
					<tr>
						<td class="border text-left">Número de não comparecimentos</td>
						<td class="border"><?php echo $total_missings ?></td>
					</tr>
					<tr>
						<td class="border text-left">Taxa de não comparecimeto</td>
						<td class="border"><?php echo $missing_rate.'%' ?></td>
					</tr>
					</tbody>
				</table>
			</div>			
			<div class="d-flex flex-column justify-center text-center">
				<h2>Números secundários</h2>
				<table class="channels-table border">
					<tbody>
					<tr>
						<td class="border text-left">Número de agendamentos - Dermatologia Clínica</td>
						<td class="border"><?php echo $clinical_schedules?></td>
					</tr>
					<tr>
						<td class="border text-left">Número de agendamentos - Dermatologia Estética</td>
						<td class="border"><?php echo $stetical_schedules?></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
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
			echo '<table>';
			echo '<ul>';
			foreach ($data as $value) {
				if ($value['outros_agendamento'] != null) {
					echo '
						<tr>
							<td><li>'.$value['outros_agendamento'].'</li></td>
						</tr>
					';
				}
			}
			echo '</ul>';
			echo '</table>';
		?>
	</div>
	<div class="container-relatory">
		<h1>Motivos de cancelamento</h1>
		<?php
			echo '<table>';
			echo '<ul>';
			foreach ($data as $value) {
				if ($value['motivo_cancelamento'] != null) {
					echo '
						<tr>
							<td><li>'.$value['motivo_cancelamento'].'</li></td>
						</tr>
					';
				}
			}
			echo '</ul>';
			echo '</table>';
		?>
	</div>
	<div class="container-relatory">
		<h1>Outros motivos para o não comparecimento</h1>
		<?php
			echo '<table>';
			echo '<ul>';
			foreach ($data as $value) {
				if ($value['motivo_comparecimento'] != null) {
					echo '
						<tr>
							<td><li>'.$value['motivo_comparecimento'].'</li></td>
						</tr>
					';
				}
			}
			echo '</ul>';
			echo '</table>';
		?>
	</div>
	<div class="container-relatory">
		<h1>Relatório de canais</h1>
		<?php
			$i = 0;
			foreach ($unique_channels as $key => $value) {
				echo '<h2>'.$unique_channels[$key].'</h2>';
				echo '<table class="channels-table border">';
				echo '
					<tr>
						<td class="border">Total de contatos</td> <td class="border">'.$total_channels[$i].'</td>
					</tr>
				';
				echo '
					<tr>
						<td class="border">Total de agendamentos</td> <td class="border">'.$total_channels_schedules[$i].'</td>
					</tr>
				';
				echo '
					<tr>
						<td class="border">Taxa de agendamento</td> <td class="border">'.round($total_channels_schedules[$i]/$total_channels[$i] * 100, 2).'%</td>
					</tr>
				';
				echo '
					<tr>
						<td class="border">Total de não agendamentos</td> <td class="border">'.count($dataPoints_channels_justifications[$i]).'</td>
					</tr>
				';
				if (count($dataPoints_channels_justifications[$i]) > 0) {
					echo '</table>';
					echo '					
						<div id="justification-'.$unique_channels[$key].'-chartContainer" class="graph"></div>
						<script>				
							const justification_'.$unique_channels[$key].'_chart = new CanvasJS.Chart(\'justification-'.$unique_channels[$key].'-chartContainer\', {
								title: {
									text: \'Gráfico de setores das porcentagens dos motivos de não agendamento\'
								},
								data: [{
									type: \'pie\',
									yValueFormatString: "#,##0.00%",
									indexLabel: "{label} ({y})",
									dataPoints: '.json_encode($dataPoints_channels_justifications[$i], JSON_NUMERIC_CHECK).'
								}]
							})
							justification_'.$unique_channels[$key].'_chart.render()
						</script>
					';
				}
				echo '</table>';
				$i++;
			}
		?>
		</div>
	</div>
	</div>
</body>	
</html>
