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

	if (isset($_POST['save'])) {
		$session = $_SESSION['session'];
		$id = $_POST['id'];
		$date = $_POST['date'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$state = $_POST['state'];
		$city = $_POST['city'];
		$channel = $_POST['channel'];
		$contact_type = $_POST['contact-type'];
		$status = $_POST['status'];
		$field = $_POST['field'];
		$fk_user = $session;
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
		$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
		$query = $pdo->prepare('UPDATE dados SET data_agendamento = ?, nome = ?, telefone = ?, estado = ?, cidade = ?, canal_origem = ?, tipo_contato = ?, status = ?, area = ?, fk_usuario = ?, motivo_agendamento = ?, motivo_cancelamento = ?, motivo_comparecimento = ?, outros_agendamento = ?, outros_cancelamento = ?, outros_comparecimento = ? WHERE id = ?');
		$query->execute(array($date, $name, $phone, $state, $city, $channel, $contact_type, $status, $field, $fk_user, $justification_schedule, $justification_cancellation, $justification_missing, $others_schedule, $others_cancellation, $others_missing, $id));
		header('Location: http://localhost/Relatorios/success.php?status=success');
		exit();
	}

	$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
	$query = $pdo->prepare('SELECT * FROM dados WHERE id = ?');
	$id = $_POST['id-edit'];
	$query->execute(array($id));
	$data_array = $query->fetchAll()[0];
	$name = $data_array['nome'];
	$phone = $data_array['telefone'];
	$state = $data_array['estado'];
	$city = $data_array['cidade'];
	$channel_origin = $data_array['canal_origem'];
	$contact_type = $data_array['tipo_contato'];
	$status = $data_array['status'];
	$field = $data_array['area'];
	$justification = null;
	if ($data_array['motivo_agendamento'] != null) {
		$justification = $data_array['motivo_agendamento'];
	} else if ($data_array['motivo_cancelamento'] != null) {
		$justification = $data_array['motivo_cancelamento'];
	} else if ($data_array['motivo_comparecimento'] != null) {
		$justification = $data_array['motivo_comparecimento'];
	}
	$justification_others = null;
	if ($data_array['outros_agendamento'] != null) {
		$justification_others = $data_array['outros_agendamento'];
	} else if ($data_array['outros_cancelamento'] != null) {
		$justification_others = $data_array['outros_cancelamento'];
	} else if ($data_array['outros_comparecimento'] != null) {
		$justification_others = $data_array['outros_comparecimento'];
	}
	$query_user_gender = $pdo->prepare('SELECT sexo FROM usuarios WHERE usuario = "'.$_SESSION['session'].'";');
	$query_user_gender->execute();
	$gender = $query_user_gender->fetch()['sexo'];
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
									<input type="hidden" name="id" value="<?php echo $id?>">
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
									<option value="<?php echo $contact_type?>" selected><?php echo $contact_type?></option>
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
													';
													if ($justification != null) {
															'<option value="'.$justification.'" selected>'.$justification.'</option>';
													}
													foreach ($user_justifications_array as $justification) {		
														$div_justification .= '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
													}
													$div_justification .= '<option value="Outros">Outros</option>;
														</select>
													</div>
													</div>';
													break;
												case 'Não compareceu':
													$array_select[2] = 'selected';
													$div_justification = '
													<div class="form-box row w-100">
													<div class="col-md-12 w-100 ml-3">
													<label id="label-justification" for="justification-select">Motivo da falta</label>
														<select id="justification-select" name="justification-missing" class="w-100 ml-3 rounded" required>
															<option value="">-- Selecione --</option>
													';
													if ($justification != null) {
														$div_justification .= '<option value="'.$justification.'" selected>'.$justification.'</option>';
													}
													foreach ($user_justifications_array as $justification) {		
														$div_justification .= '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
													}
													$div_justification .= '<option value="Outros">Outros</option>
														</select>
													</div>
													</div>';
													break;
												case 'Cancelado':
													$array_select[3] = 'selected';
													$div_justification = '
													<div class="form-box row w-100">
													<div class="col-md-12 w-100 ml-3">
													<label id="label-justification" for="justification-select">Motivo do cancelamento</label>
														<select id="justification-select" name="justification-cancellation" class="w-100 ml-3 rounded" required>
															<option value="">-- Selecione --</option>
													';
													if ($justification != null) {
															$div_justification .= '<option value="'.$justification.'" selected>'.$justification.'</option>';
													}
													foreach ($user_justifications_array as $justification) {		
														$div_justification .= '<option value="'.$justification['motivo'].'">'.$justification['motivo'].'</option>';
													}
													$div_justification .= '<option value="Outros">Outros</option>
														</select>
													</div>
													</div>';
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
												<textarea id="justification-others" class="align-self-center ml-3 w-100" name="others-schedule" style="resize: none; height: 80px;" required>'.$justification_others.'</textarea>
											</div>
										</div>';
										echo $div_justification_others;
										break;
									case 'Não compareceu':
										$div_justification_others .= '
											<label for="justification-others">Especifique o motivo</label>
												<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-missing" style="resize: none; height: 80px;" required></textarea>
											</div>
										</div>';
										echo $div_justification_others;
										break;
									case 'Cancelado':
										$div_justification_others .= ' 
											<label for="justification-others">Especifique o motivo</label>
												<textarea id="justification-cancellation" class="align-self-center ml-3 w-100" name="others-cancellation" style="resize: none; height: 80px;" required></textarea>
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
										<option value="<?php echo $field?>" selected><?php echo $field ?></option>
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
