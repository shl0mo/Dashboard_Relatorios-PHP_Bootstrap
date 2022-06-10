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
	$query_user_gender = $pdo->prepare('SELECT sexo FROM usuarios WHERE usuario = "'.$_SESSION['session'].'";');
	$query_user_gender->execute();
	$gender = $query_user_gender->fetch()['sexo'];
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
		echo '<script>alert("Cliente inserido com sucesso")</script>';
	}
?>
<?php
	require_once('./views/header-sidebar.php');
?>
		<script type="text/javascript" src="./js/date-script.js"></script>
			<div class="w-100 col">
				<form method="post" class="form form-group container pt-4 pb-5 w-100 col">
					<div id="form-container" class="form-container w-100 container">
						<div class="column">
							<div class="col-md-4 d-inline">
									<div>
										<label for="date-input">Data</label>
									</div>
									<div>
										<input id="date-input" class="ml-3 rounded w-100" type="text" name="date" readonly/>
									</div>
							</div>
							<div class="col-md-4 d-inline">
								<div>	
									<label for="name-input">Nome completo</label>
								</div>
								<div>
									<input id="name-input" type="text" name="name" class="w-25 ml-3 rounded w-100" placeholder="Ex.: João da Silva" required/>
								</div>
							</div>
							<div class="col-md-4 d-inline">
								<div>
									<label for="phone-input">Telefone</label>
								</div>
								<div>
									<input id="phone-input" type="number" name="phone" class="w-25 ml-3 rounded w-100" placeholder="11999999999" required/>
								</div>
							</div>
						</div>
						<div class="form-box column">
							<div class="col-md-6 d-inline w-50">
								<label for="state-select">Estado</label>
								<select id="state-select" name="state" class="ml-3 rounded w-100" onchange="showCities()" required>
									<option value="">-- Selecione --</option>
								</select>
							</div>
							<div class="col-md-6 d-inline w-50">
								<label for="city-select">Cidade</label>
									<select id="city-select" name="city" class="ml-3 rounded w-100" required>
										<option value="">-- Selecione --</option>
									</select>
							</div>
						</div>
						<div class="form-box row w-100">
							<div class="col-md-12 ml-3">
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
						</div>
						<div class="form-box row w-100">
							<div class="col-md-12 ml-3">
								<label for="contact-type-select">Forma de contato</label>
									<select id="contact-type-select" name="contact-type" class="w-100 ml-3 rounded" required>
										<option value="">-- Selecione --</option>
										<option>Tipo contato 1</option>
										<option>Tipo contato 2</option>
										<option>Tipo contato 2</option>
									</select>
							</div>
						</div>
						<div  class="form-box row w-100">
							<div class="col-md-12 ml-3">
								<label for="status-select">Status</label>
									<select id="status-select" name="status" class="w-100 ml-3 rounded" required>
										<option value-"">-- Selecionar --</option>
										<option value="Agendou">Agendou</option>
										<option value="Não agendou">Não agendou</option>
										<option value="Não compareceu">Não compareceu</option>
										<option value="Cancelou">Cancelou</option>
									</select>
							</div>
						</div>
						<div id="field-container" class="form-box row w-100">
							<div class="col-md-12 ml-3">
								<label for="field">Área</label>
									<select id="field" name="field" class="w-100 ml-3 rounded" required>
										<option value="">-- Selecione --</option>
										<option value="Dermatologia estética">Dermatologia estética</option>
										<option value="Dermatologia clínica">Dermatologia clínica</option>
									</select>
							</div>
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
