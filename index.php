<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<style>
			:root {
				--box-shadow: 0px 2px 10px rgba(0,0,0,0.2); 
			}
			
			* {
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}
			
			body {
				background-color: important;
			}
			
			header {
				background-color: #000;
				height: 150px;
				margin-bottom: 90px;
				color: white;
				display: flex;
				align-items: center;
				padding-left: 5%;
			}
			
			form  {
				border-radius: 15px;
				box-shadow: var(--box-shadow);
				display: flex;
				flex-direction: column;
			}
		
			input {
				border: 1px solid rgba(0,0,0,0.3);
				padding: 10px;
				background-color: white;
			}
			
			select {
				border: 1px solid rgba(0,0,0,0.3);
				padding: 12px;
				background-color: rgba(0,0,0,0.01);
			}
			
			label {
				margin-top: 30px;
				font-size: 1.2em;
			}
			
			.hidden {
				display: none;
			}
			
			.justify-center {
				display: flex;
				align-self: center;
			}
			
			.row {
				display: flex;
				flex-direction: column;
			} 
			
			.justify-center {
				display: flex;
				flex-direction: column;
				justify-content: center;
			}
			
			.main-container {
				margin-bottom: 200px;
			}
			
			.form-container {
				padding-bottom: 40px;
			}
			
			.but-test {
				width: 200px;
				height: 50px;
				background-color: black;
			}
		</style>

		<script>
			const objStates = {
				list_states: 'a',
				UF: ''
			}
		
			function handleSchedule () {
				const justification_classList = document.querySelector('#justification-select').classList
				const justification_label_classList = document.querySelector('[for="justification-select"]').classList
				if (this.value == 'Sim') {
					if (!justification_classList.contains('hidden')) {
						justification_classList.add('hidden')
						justification_label_classList.add('hidden')
					}
				} else {
					if (justification_classList.contains('hidden')) {
						justification_classList.remove('hidden')
						justification_label_classList.remove('hidden')
					}
				}
			}
			
			function getCities () {
				const state = document.getElementById('state-select').value
				if (state !== '-- Selecione --') {
					
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
				if (selected_state === '-- Selecione --') return
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
					if (document.contains(document.querySelector('#schedule-select'))) {
						document.querySelector('#schedule-select').addEventListener('change', handleSchedule, false)
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
						<input id="date-input" class="w-100 ml-3 rounded" ctext" value="Data de hoje" readonly/>
					</div>
					<div class="form-box row">
						<label for="name-input col">Nome completo</label>	
						<input id="name-input" name="name" class="w-100 ml-3 rounded" type="Text" required/>
					</div>
					<div class="form-box row">
						<label for="phone-input col">Telefone</label>
						<input id="phone-input col" name="phone" class="w-100 ml-3 rounded" type="text" required/>
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
						<label for="schedule-select">Agendou?</label>
							<select id="schedule-select" name="schedule" class="w-100 ml-3 rounded" required>
								<option value-"">-- Selecionar --</option>
								<option>Sim</option>
								<option>Não</option>
							</select>
					</div>
					<div class="form-box row">
						<label for="justification-select" class="hidden">Motivo de não ter agendado</label>
							<select id="justification-select" name="justification" class="hidden w-100 ml-3 rounded" required>
								<option value="">-- Selecione --</option>
								<option>Motivo 1</option>
								<option>Motivo 2</option>
								<option>Motivo 3</option>
							</select>
					</div>
					<div class="form-box row">
						<label for="field">Área</label>
						<select id="field" name="field" class="w-100 ml-3 rounded" required>
							<option value="">-- Selecione --</option>
							<option>Demartologia estética</option>
							<option>Demartologia clínica</option>
						</select>
					</div>
					<div class="button-box row">
						<button type="submit" onclick="validateData()" name="enviar" class="btn btn-success align-self-center w-25 mt-4 p-3">Enviar</button>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>

<?php
	$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
	if (isset($_POST['enviar'])) {
		$name = $_POST['name'];
		$phone = $_POST['phone'];	
		$state = $_POST['state'];
		$city = $_POST['city'];
		$channel = $_POST['channel'];
		$contact_type = $_POST['contact-type'];
		$schedule = $_POST['schedule'];
		$justification = $_POST['justification'];
		if ($justification == '--Selecione --') $justification = null;
		echo '<h1>'.$justification.'</h1>';
		$field = $_POST['field'];
		$id_query = $pdo->prepare('SELECT * FROM dados');
		$id_query->execute();
		$id_array = $id_query->fetchAll();
		$insert_data = $pdo->prepare('INSERT INTO dados VALUES(?,?,?,?,?,?,?,?,?,?,?)');
		$insert_data->execute(array(count($id_array) + 1,'2022-15-04', $name, $phone, $state, $city, $channel, $contact_type, $schedule, $justification, $field));
	}
?>
