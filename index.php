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
	if (isset($_POST['save'])) {
		$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
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
		$justification = $_POST['justification'];
		if ($justification == '') $justification = null;
		$session = $_SESSION['session'];
		$field = $_POST['field'];
		$id_query = $pdo->prepare('SELECT * FROM dados');
		$id_query->execute();
		$id_array = $id_query->fetchAll();
		$insert_data = $pdo->prepare('INSERT INTO dados(id, data_agendamento, nome, telefone, estado, cidade, canal_origem, tipo_contato, status, motivo, area, fk_usuario, motivo_outros) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)');
		$insert_data->execute(array(count($id_array) + 1, $date, $name, $phone, $state, $city, $channel, $contact_type, $status, $justification, $field, $session));
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
		<script>
			const objStates = {
				list_states: '',
				UF: ''
			}
		
			function handleSchedule () {
				const justification = document.querySelector('#justification-select')
				const justification_classList = justification.classList
				const justification_label_classList = document.querySelector('[for="justification-select"]').classList
				if (this.value != 'Não agendou') {
					if (!justification_classList.contains('hidden')) {
						justification_classList.add('hidden')
						justification_label_classList.add('hidden')
					}
					document.getElementById('justification-select').value = ''
				} else {
					if (justification_classList.contains('hidden')) {
						justification_classList.remove('hidden')
						justification_label_classList.remove('hidden')
						justification.setAttribute('required', 'true')
					}
				}
			}

			function  handleJustificationOthers () {
				const justification_others = document.querySelector('#justification-others') 
				const justification_others_classList = justification_others.classList
				const justification_others_label_classList = document.querySelector('[for="justification-others"]').classList
				if (this.value != 'Outros') {
					if (!justification_others_classList.contains('hidden')) {
						justification_others_classList.add('hidden')
						justification_others_label_classList.add('hidden')
					}
					document.getElementById('justification-others').value = ''
				} else {
					if (justification_others_classList.contains('hidden')) {
						justification_others_classList.remove('hidden')
						justification_others_label_classList.remove('hidden')
						justification_others.setAttribute('required', 'true')
					}
				}
			}
			
			function getCities () {
				const state = document.getElementById('state-select').value
				if (state !== '') {
					$.ajax({
						url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/{UF}/municipios'
					})
					
				}
			}
			
			function getStates () {
				return $.ajax({ url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' })
			}
			
			function getCities (UF) {
				return $.ajax({ url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + UF + '/municipios' })
			}
			
			function showCities () {
				const cities = document.querySelectorAll('#city-select option')
				const city_select = document.getElementById('city-select')
				const selected_state = document.getElementById('state-select').value
				for (city of cities) {
					city_select.remove(city)
				}
				let option = document.createElement('option')
				option.innerHTML = '-- Selecione --'
				city_select.appendChild(option)		
				if (selected_state === '') return
				getStates().then(res => {
					const selected_state = document.getElementById('state-select').value
					let UF = ''
					console.log(selected_state)
					for (state of res) {
						if (state.nome == selected_state) {
							UF = state.sigla
							getCities(UF).then(res => {
								for (city of res) {
									let option_city = document.createElement('option')
									option_city.innerHTML = city.nome
									document.getElementById('city-select').appendChild(option_city)
								}
							})
						}
					}
				})
			}
			
			const interval = setInterval(() => {
				if (document.contains(document.querySelector('#status-select'))) {
						document.querySelector('#status-select').addEventListener('change', handleSchedule, false)
						document.querySelector('#justification-select').addEventListener('change', handleJustificationOthers, false)
						let date = new Date()
						const year = date.getFullYear()
						let month = date.getMonth() + 1
						const day = date.getDate()
						if (String(month).length == 1) month = '0' + month
						document.querySelector('#date-input').value = day + '/' + month + '/' + year
						getStates().then(res => {
							for (state of res) {
								let option_state = document.createElement('option')
								option_state.innerHTML = state.nome
								document.getElementById('state-select').appendChild(option_state)
							}
						})
						clearInterval(interval)
					}
			}, 100)	
		</script>
	</head>
	<body>
		<header>
			<h1>Dados para o relatório</h1>
		</header>
		<div class="main-container">
			<form method="post" class="form form-group container pt-5 pb-5 w-50">
				<div class="form-container justify-center w-75">
					<div class="form-box row">
						<label for="date-input">Data</label>
						<input id="date-input" class="w-100 ml-3 rounded" type="text" name="date" readonly/>
					</div>
					<div class="form-box row">
						<label for="name-input col">Nome completo</label>	
						<input id="name-input" name="name" class="w-100 ml-3 rounded" type="Text" required/>
					</div>
					<div class="form-box row">
						<label for="phone-input col">Telefone</label>
						<input id="phone-input col" type="number" name="phone" class="w-100 ml-3 rounded" type="text" required/>
					</div>
					<div class="form-box row">
						<label for="state-select col">Estado</label>
						<select id="state-select" name="state" class="w-100 ml-3 rounded" onchange="showCities()" required>
							<option value="">-- Selecione --</option>
						</select>
					</div>
					<div class="form-box row">
						<label for="city-select">Cidade</label>
							<select id="city-select" name="city" class="w-100 ml-3 rounded" required>
								<option value="">-- Selecione --</option>
							</select>
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
					<div class="form-box row">
						<label for="status-select">Status</label>
							<select id="status-select" name="status" class="w-100 ml-3 rounded" required>
								<option value-"">-- Selecionar --</option>
								<option value="Agendou">Agendou</option>
								<option value="Não agendou">Não agendou</option>
								<option value="Não compareceu">Não compareceu</option>
								<option value="Cancelou">Cancelou</option>
							</select>
					</div>
					<div class="form-box row">
						<label for="justification-select" class="hidden">Motivo de não ter agendado</label>
							<select id="justification-select" name="justification" class="hidden w-100 ml-3 rounded">
								<option value="">-- Selecione --</option>
								<option value="Convênio que não atende">Convênio que não atende</option>
								<option value="Criança">Criança</option>
								<option value="Data">Data</option>
								<option value="Só queria informações">Só queria informações</option>
								<option value="Tratamento/procedimento que não faz">Tratamento/procedimento que não faz</option>
								<option value="Valor">Valor</option>
								<option value="Outros">Outros</option>
							</select>
					</div>
					<div class="form-box row">
						<label for="justification-others" class="hidden">Especifique o motivo do não agendamento</label>
						<textarea id="justification-others" class="align-self-center ml-3 w-100 hidden" style="resize: none; height: 80px;"></textarea>
					</div>
					<div class="form-box row">
						<label for="field">Área</label>
						<select id="field" name="field" class="w-100 ml-3 rounded" required>
							<option value="">-- Selecione --</option>
							<option value="Dermatologia estética">Dermatologia estética</option>
							<option value="Dermatologia clínica">Dermatologia clínica</option>
						</select>
					</div>
					<div class="button-box row">
						<button type="submit" name="save" class="btn btn-success align-self-center w-25 mt-4 p-3">Salvar</button>
					</div>
				</div>
			</form>
			<form method="post">
				<button type="submit" name="logout" class="btn btn-danger align-self-center w-25 mt-4 p-3">Sair</button>
			</form>
		</div>
	</body>
</html>
