<?php
	session_start();
	if (!isset($_SESSION['session'])) {
		header('Location: http://localhost/Relatorios/login.php');
		exit();
	} else if ($_SESSION['session'] == 'admin') {
		header('Location: http://localhost/Relatorios/cadastro.php');
		exit();
	}
	if (isset($_POST['logout'])) {
		session_destroy();
		header('Location: http://localhost/Relatorios/login.php');
		exit();
	}

	$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
	$query = $pdo->prepare('SELECT B.motivo FROM (SELECT * FROM usuarios JOIN usuarios_motivos USING(usuario) WHERE usuario = "'.$_SESSION['session'].'") AS A JOIN motivos AS B ON A.motivo = B.id');
	$query->execute();
	$user_justifications_array = $query->fetchAll();
	if (isset($_POST['save'])) {
		$date = $_POST['date'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];	
		$state = $_POST['state'];
		$city = $_POST['city'];
		$channel = $_POST['channel'];
		$contact_type = $_POST['contact-type'];
		$status = $_POST['status'];
		switch ($status) {
			case 'Agendou':
				$status = 'Agendado';
				break;
			case 'Não agendou':
				$status = 'Não agendado';
				break;
			case 'Cancelou':
				$status = 'Cancelado';
				break;
		}
		$justification_schedule = null;
		$justification_cancellation = null;
		$justification_missing = null;
		foreach ($_POST as $key => $value) {
			if (strpos($key, 'justification') !== false) {
				switch ($key) {
					case $key == 'justification-schedule':
						$justification_schedule = $_POST['justification-schedule'];
						break;
					case $key == 'justification-cancellation':
						$justification_cancellation = $_POST['justification-cancellation'];
						break;
					case $key == 'justification-missing':
						$justification_missing = $_POST['justification-missing'];
						break;
				}
			}
		}
		$others_schedule = null;
		$others_cancellation = null;
		$others_missing = null;
		if ($justification_schedule == 'Outros') {
			$others_schedule = $_POST['others-schedule'];
		} else if ($justification_cancellation == 'Outros') {
			$others_cancellation = $_POST['others-cancellation'];
		} else if ($justification_missing == 'Outros') {
			$others_missing = $_POST['others-missing'];
		}
		$session = $_SESSION['session'];
		$field = $_POST['field'];
		$id_query = $pdo->prepare('SELECT * FROM dados');
		$id_query->execute();
		$id_array = $id_query->fetchAll();
		$insert_data = $pdo->prepare('INSERT INTO dados(id, data_agendamento, nome, telefone, estado, cidade, canal_origem, tipo_contato, status, area, fk_usuario,  motivo_agendamento, motivo_cancelamento, motivo_comparecimento, outros_agendamento, outros_cancelamento, outros_comparecimento) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
		$insert_data->execute(array(count($id_array) + 1, $date, $name, $phone, $state, $city, $channel, $contact_type, $status, $field, $session, $justification_schedule, $justification_cancellation, $justification_missing, $others_schedule, $others_cancellation, $others_missing));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
		<link rel="stylesheet" href="./css/style.css"/>
		<script src="./js/scripts.js"></script>
	</head>
	<body>
		<header class="mb-0 bg-dark w-100 p-2" style="height: 100px;">
			<nav class="navbar navbar-dark d-flex flex-direction-column w-100 align-items-center">
				<div class="container-fluid col w-100 text-light">
					<h1><?php echo 'Dr(a). '.$_SESSION['session'];?></h1>
				</div>
				<div class="container-fluid col-md-1 d-flex w-100">
					<form method="post" class="d-flex w-100">
						<button type="submit" name="logout" class="btn btn-danger text-center p-2 w-100">Sair</button>
					</form>
				</div>
			</nav>
		</header>
		<div class="main-container d-flex flex-direction-row h-100 mb-0 w-100">
			<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark col-md-2" style="width: 280px;">
			    <ul class="nav nav-pills flex-column mb-auto">
	  		      <hr>
			      <li>
				<a href="#" class="nav-link active text-white">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
					<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
					</svg>
				  Novo contato
				</a>
			      </li>
			      <hr/>
			      <li>
				<a href="#" class="nav-link text-white">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
					<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
					</svg>
				  Editar
				</a>
			      </li>
			      <hr/>
			      <li>
				<a href="./relatorio.php" class="nav-link text-white">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z"/>
					</svg>
				  Relatório
				</a>
			      </li>
			      <hr/>
			    </ul>
			</div>
			<div class="w-100 col">
				<form method="post" class="form form-group container pt-4 pb-5 w-100 col">
					<div id="form-container" class="form-container justify-center w-100 pl-5 pr-5 container">
						<div class="column">
							<div class="col-md-3 d-inline">
									<label for="date-input">Data</label>
									<input id="date-input" class="ml-3 rounded" type="text" name="date" readonly/>
							</div>
							<div class="col-md-3 d-inline">
								<label for="name-input col">Nome completo</label>	
								<input id="name-input" name="name" class="w-25 ml-3 rounded" type="Text" required/>
							</div>
							<div class="col-md-3 d-inline">
								<label for="phone-input col">Telefone</label>
								<input id="phone-input col" type="number" name="phone" class="w-25 ml-3 rounded" type="text" required/>
							</div>
						</div>
						<div class="form-box column">
							<div class="col-md-2 d-inline w-50">
								<label for="state-select col">Estado</label>
								<select id="state-select" name="state" class="ml-3 rounded" onchange="showCities()" required>
									<option value="">-- Selecione --</option>
								</select>
							</div>
							<div class="col-md-2 d-inline w-50">
								<label for="city-select">Cidade</label>
									<select id="city-select" name="city" class="ml-3 rounded" required>
										<option value="">-- Selecione --</option>
									</select>
							</div>
						</div>
						<div class="form-box row">
							<label for="channel-origin">Canal de origem</label>
								<select id="channel-origin" name="channel" class="w-100 ml-3 rounded" required>
									<option value="">-- Selecione --</option>
									<option value="Google">Google</option>
									<option value="Instagram">Instagram</option>
									<option value="Facebook">Facebook</option>
									<option value="Doctoralia">Doctoralia</option>
									<option value="Indicação">Indicação</option>
									<option value="Já é paciente">Já é paciente</option>
									<option value="Outros">Outros</option>
								</select>
						</div>
						<div class="form-box row">
							<label for="contact-type-select">Forma de contato</label>
								<select id="contact-type-select" name="contact-type" class="w-100 ml-3 rounded" required>
									<option value="">-- Selecione --</option>
									<option>Tipo contato 1</option>
									<option>Tipo contato 2</option>
									<option>Tipo contato 2</option>
								</select>
						</div>
						<div  class="form-box row">
							<label for="status-select">Status</label>
								<select id="status-select" name="status" class="w-100 ml-3 rounded" required>
									<option value-"">-- Selecionar --</option>
									<option value="Agendou">Agendou</option>
									<option value="Não agendou">Não agendou</option>
									<option value="Não compareceu">Não compareceu</option>
									<option value="Cancelou">Cancelou</option>
								</select>
						</div>
						<div id="field-container" class="form-box row">
							<label for="field">Área</label>
							<select id="field" name="field" class="w-100 ml-3 rounded" required>
								<option value="">-- Selecione --</option>
								<option value="Dermatologia estética">Dermatologia estética</option>
								<option value="Dermatologia clínica">Dermatologia clínica</option>
							</select>
						</div>
						<div class="button-box row">
							<button type="submit" name="save" class="btn btn-primary align-self-center w-25 mt-4 p-3">Salvar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
