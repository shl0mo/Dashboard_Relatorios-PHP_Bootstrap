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
	$query = $pdo->prepare('SELECT * FROM dados WHERE id = ?');
	$query->execute(array($_POST['id-edit']));
	$data_array = $query->fetchAll()[0];
	$name = $data_array['nome'];
	$phone = $data_array['telefone'];
	$state = $data_array['estado'];
	$city = $data_array['cidade'];
	$channel_origin = $data_array['canal_origem'];
	$status = $data_array['status'];
	$justification = '';
	if ($data_array['motivo_agendamento'] != null) {
		$justification = $data_array['motivo_agendamento'];
	} else if ($data_array['motivo_cancelamento'] != null) {
		$justification = $data_array['motivo_cancelamento'];
	} else if ($data_array['motivo_comparecimento'] != null) {
		$justification = $data_array['motivo_comparecimento'];
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
				const justification_label = document.querySelector('[for="justification-select"]') 
				const justification_others = document.querySelector('#justification-others') 
				const justification_others_label = document.querySelector('[for="justification-others"]')
				if (document.contains(justification_others)) {
					justification_others.remove()
					justification_others_label.remove()
				}
				if (this.value !== 'Não agendou' && this.value !== 'Cancelou' && this.value !== 'Não compareceu') {
					if (document.contains(justification)) {
						justification.remove()
						justification_label.remove()
					}
				} else if (this.value === 'Cancelou') {
					if (document.contains(justification)) {
						justification.remove()
						justification_label.remove()
					}
					if (!document.contains(justification)) {						
						const div_justification = document.createElement('div')
						div_justification.className = 'form-box row w-100'
						const html = `
							<div class="col-md-12 w-100 ml-3">
								<label id="label-justification" for="justification-select">Motivo do cancelamento</label>
									<select id="justification-select" name="justification-cancellation" class="w-100 ml-3 rounded" required>
										<option value="">-- Selecione --</option>
										<?php
											foreach ($user_justifications_array as $justification) {			
												echo '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
											}
										?>
										<option value="Outros">Outros</option>
									</select>
							</div>
						`
						const select_justification = html.trim()
						div_justification.innerHTML = select_justification
						const parent_node = document.querySelector('#form-container')
						const next_node = document.querySelector('#field-container')
						parent_node.insertBefore(div_justification, next_node)
						document.querySelector('#justification-select').addEventListener('change', handleJustificationOthers, false)
					}
				} else if (this.value === 'Não compareceu') {
					if (document.contains(justification)) {
						justification.remove()
						justification_label.remove()
					}
					if (!document.contains(justification)) {
						const div_justification = document.createElement('div')
						div_justification.className = 'form-box row w-100'
						const html = `
							<div class="col-md-12 w-100 ml-3">
								<label id="label-justification" for="justification-select">Motivo da falta</label>
									<select id="justification-select" name="justification-missing" class="w-100 ml-3 rounded" required>
										<option value="">-- Selecione --</option>
										<?php
											foreach ($user_justifications_array as $justification) {			
												echo '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
											}
										?>
										<option value="Outros">Outros</option>
									</select>
							</div>
						`
						const select_justification = html.trim()
						div_justification.innerHTML = select_justification
						const parent_node = document.querySelector('#form-container')
						const next_node = document.querySelector('#field-container')
						parent_node.insertBefore(div_justification, next_node)
						document.querySelector('#justification-select').addEventListener('change', handleJustificationOthers, false)		
					}
				} else if (this.value === 'Não agendou') {
					if (document.contains(justification)) {
						justification.remove()
						justification_label.remove()
					}
					if (!document.contains(justification)) {
						const div_justification = document.createElement('div')
						div_justification.className = 'form-box row w-100'
						const html = `
							<div class="col-md-12 w-100 ml-3">
								<label id="label-justification" for="justification-select">Motivo de não ter agendado</label>
									<select id="justification-select" name="justification-schedule" class="w-100 ml-3 rounded" required>
										<option value="">-- Selecione --</option>
										<?php
											foreach ($user_justifications_array as $justification) {			
												echo '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
											}
										?>
										<option value="Outros">Outros</option>
									</select>
							</div>
						`
						const select_justification = html.trim()
						div_justification.innerHTML = select_justification
						const parent_node = document.querySelector('#form-container')
						const next_node = document.querySelector('#field-container')
						parent_node.insertBefore(div_justification, next_node)
						document.querySelector('#justification-select').addEventListener('change', handleJustificationOthers, false)
					}
				}
			}
		
			function  handleJustificationOthers () {
				let justification_others = document.querySelector('#justification-others') 
				const justification_others_label = document.querySelector('[for="justification-others"]')
				if (this.value != 'Outros') {
					if (document.contains(justification_others)) {
						justification_others.remove()
						justification_others_label.remove()
					}
				} else {
					if (!document.contains(justification_others)) {
						const div_others = document.createElement('div')
						div_others.className = 'form-box row w-100'
						const justification_select = document.querySelector('#justification-select')
						const name_justification = justification_select.name
						let html = '<div class="col-md-12 w-100 ml-3">'
						if (name_justification === 'justification-schedule') {
							html += `
								<label for="justification-others">Especifique o motivo</label>
									<textarea id="justification-others" class="align-self-center ml-3 w-100" name="others-schedule" style="resize: none; height: 80px;"></textarea>
							`
						} else if (name_justification === 'justification-cancellation') {
							html += `
								<label for="justification-others">Especifique o motivo</label>
									<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-cancellation" style="resize: none; height: 80px;"></textarea>
							`
						} else if (name_justification === 'justification-missing') {
							html += `
								<label for="justification-others">Especifique o motivo</label>
									<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-missing" style="resize: none; height: 80px;"></textarea>
							`
						}
						html += '</div>'
						const textarea_others = html.trim()
						div_others.innerHTML = textarea_others
						const parent_node = document.querySelector('#form-container')
						const next_node = document.querySelector('#field-container')
						parent_node.insertBefore(div_others, next_node)
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
						let date = new Date()
						const year = date.getFullYear()
						let month = date.getMonth() + 1
						let day = date.getDate()
						if (String(day).length == 1) day = '0' + day
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
				<a href="./" class="nav-link text-white">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
					<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
					</svg>
				  Novo contato
				</a>
			      </li>
			      <hr/>
			      <li>
				<a href="./listar.php" class="nav-link active text-white">
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
									<input id="name-input" type="text" name="name" class="w-25 ml-3 rounded w-100" placeholder="Ex.: João da Silva"  value="<?php echo $name?>" required/>
								</div>
							</div>
							<div class="col-md-4 d-inline">
								<div>
									<label for="phone-input">Telefone</label>
								</div>
								<div>
									<input id="phone-input" type="number" name="phone" class="w-25 ml-3 rounded w-100" placeholder="11999999999" value="<?php echo $phone?>" required/>
								</div>
							</div>
						</div>
						<div class="form-box column">
							<div class="col-md-6 d-inline w-50">
								<label for="state-select">Estado</label>
								<select id="state-select" name="state" class="ml-3 rounded w-100" onchange="showCities()" required>
									<option value="<?php echo $state?>"><?php echo $state?></option>
								</select>
							</div>
							<div class="col-md-6 d-inline w-50">
								<label for="city-select">Cidade</label>
									<select id="city-select" name="city" class="ml-3 rounded w-100" required>
										<option value="<?php echo $city?>"><?php echo $city?></option>
									</select>
							</div>
						</div>
						<div class="form-box row w-100">
							<div class="col-md-12 ml-3">
								<label for="channel-origin">Canal de origem</label>
									<select id="channel-origin" name="channel" class="w-100 ml-3 rounded" required>
										<?php
											$array_select = array_fill(0, 7, '');
											switch ($channel_origin) {
												case 'Google':
													$array_select[0] = 'selected';
													break;
												case 'Instagram':
													$array_select[1] = 'selected';
													break;
												case 'Facebook':
													$array_select[2] = 'selected';
													break;
												case 'Doctoralia':
													$array_select[3] = 'selected';
													break;
												case 'Indicação':
													$array_select[4] = 'selected';
													break;
												case 'Já é paciente':
													$array_select[5] = 'selected';
													break;
												case 'Outros':
													$array_select[6] = 'selected';
													break;
											}
										?>
										<option value="">-- Selecione --</option>
										<option value="Google" <?php echo $array_select[0]?>>Google</option>
										<option value="Instagram" <?php echo $array_select[1]?>>Instagram</option>
										<option value="Facebook" <?php echo $array_select[2]?>>Facebook</option>
										<option value="Doctoralia" <?php echo $array_select[3]?>>Doctoralia</option>
										<option value="Indicação" <?php echo $array_select[4]?>>Indicação</option>
										<option value="Já é paciente" <?php echo $array_select[5]?>>Já é paciente</option>
										<option value="Outros" <?php echo $array_select[6]?>>Outros</option>
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
										<?php
											$array_select = array_fill(0, 4, '');
											$div_justification = '';
											switch ($status) {
												case 'Agendado':
													$array_select[0] = 'selected';
													break;
												case 'Não agendado':
													$array_select[1] = 'selected';
													$div_justification = '
													<div  class="form-box row w-100">
													<div class="col-md-12 w-100 ml-3">
														<label id="label-justification" for="justification-select">Motivo de não ter agendado</label>
														<select id="justification-select" name="justification-schedule" class="w-100 ml-3 rounded" required>
															<option value="">-- Selecione --</option>
															<?php
																foreach ($user_justifications_array as $justification) {			
																	echo \'<option value="'.$justification.'">'.$justification.'</option>\';
																}
															?>
															<option value="Outros">Outros</option>
														</select>
													</div>
													</div>
													';
													break;
												case 'Não compareceu':
													$array_select[2] = 'selected';
													$div_justification = '
													<div class="form-box row w-100">
													<div class="col-md-12 w-100 ml-3">
													<label id="label-justification" for="justification-select">Motivo da falta</label>
														<select id="justification-select" name="justification-missing" class="w-100 ml-3 rounded" required>
															<option value="">-- Selecione --</option>
															<?php
																foreach ($user_justifications_array as $justification) {			
																	echo \'<option value="'.$justification.'">'.$justification.'</option>\';
																}
															?>
															<option value="Outros">Outros</option>
														</select>
													</div>
													</div>
													';
													break;
												case 'Cancelado':
													$array_select[3] = 'selected';
													$div_justification = '
													<div class="form-box row w-100">
													<div class="col-md-12 w-100 ml-3">
													<label id="label-justification" for="justification-select">Motivo do cancelamento</label>
														<select id="justification-select" name="justification-cancellation" class="w-100 ml-3 rounded" required>
															<option value="">-- Selecione --</option>
													<option value="'.$justification.'" selected>'.$justification.'</option>
													<option value="Outros">Outros</option>
														</select>
													</div>
													</div>
													';
													break;
											}
										?>
										<option value-"">-- Selecionar --</option>
										<option value="Agendou" <?php echo $array_select[0]?>>Agendou</option>
										<option value="Não agendou" <?php echo $array_select[1]?>>Não agendou</option>
										<option value="Não compareceu" <?php echo $array_select[2]?>>Não compareceu</option>
										<option value="Cancelou" <?php echo $array_select[3]?>>Cancelou</option>
									</select>
							</div>
						</div>
						<?php
							echo $div_justification;
							if ($justification == 'Outros') {
								$div_justification_others = '
									<div class="form-box row w-100">
										<div class="col-md-12 w-100 ml-3">
								';
								switch ($status) {
									case 'Não agendado':
										$div_justification_others .= '
											<label for="justification-others">Especifique o motivo</label>
												<textarea id="justification-others" class="align-self-center ml-3 w-100" name="others-schedule" style="resize: none; height: 80px;"></textarea>
											</div>
										</div>';
										echo $div_justification_others;
										break;
									case 'Não compareceu':
										$div_justification_others .= '
											<label for="justification-others">Especifique o motivo</label>
												<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-missing" style="resize: none; height: 80px;"></textarea>
											</div>
										</div>';
										echo $div_justification_others;
										break;
									case 'Cancelado':
										$div_justification_others .= ' 
											<label for="justification-others">Especifique o motivo</label>
												<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-cancellation" style="resize: none; height: 80px;"></textarea>
											</div>
										</div>';
										echo $div_justification_others;
										break;
								}
							}

						?>
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
