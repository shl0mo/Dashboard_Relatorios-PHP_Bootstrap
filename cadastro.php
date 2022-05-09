<?php
	session_start();
	if ($_SESSION['session'] != 'admin') {
		header('Location: http://localhost/Relatorios/login.php');
		exit();
	}
	if (isset($_POST['logout'])) {
		session_destroy();
		header('Location: http://localhost/Relatorios/login.php');
		exit();
	}

	$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
	$query = $pdo->prepare('SELECT * FROM tipos');
	$query->execute();
	$types = $query->fetchAll();
	$query = $pdo->prepare('SELECT * FROM motivos');
	$query->execute();
	$justifications = $query->fetchAll();
	if (isset($_POST['register'])) {
		$name = $_POST['name'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$usertype = $_POST['usertype'];
		$query = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = "'.$username.'"');
		$query->execute();
		$registered = count($query->fetchAll());
		if (!$registered) {
			$query = $pdo->prepare('INSERT INTO usuarios VALUES(?,?,?,?)');
			$query->execute(array($username, $password, $usertype, $name));
			$query = $pdo->prepare('SELECT * FROM usuarios_motivos');
			$query->execute();
			$id_array = $query->fetchAll();
			$id = count($id_array);
			foreach ($_POST as $key => $value) {
				if (strpos($key, 'motivo') !== false) {
					$id_motivo_array = explode('-', $key);
					$id_motivo = intval(end($id_motivo_array));
					$query = $pdo->prepare('INSERT INTO usuarios_motivos VALUES(?,?,?)');
					$query->execute(array($id + 1, $username, $id_motivo));
					$id++;
				}
			}
			echo '<script>alert("Usuário cadastrado com suceso")</script>';
		} else {
			echo '<script>alert("Este Username já está cadastrado. Escolha outro")</script>';
		}
	}
?>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
	<link rel="stylesheet" href="./css/style.css"/>
	<script>
		const verifyCheckbox = (event) => {
			const check_boxes = document.querySelectorAll('[type="checkbox"]')
			let one_checked = false
			for (const checkbox of check_boxes) {
				if (checkbox.checked === true) one_checked = true
			}
			if (!one_checked) {
				event.preventDefault()
				alert('Escolha as justificativas')
			}
		}
	</script>
</head>
<body>
	<div class="h-100 d-flex justify-content-center aling-items-center">
		<div class="position-absolute align-self-center container p-5 border" style="top: 0px; width: 700px;">
			<div class="row w-100">
				<h4 class="h4-register d-flex align-self-center">Cadastro de Usuário</h4>
			</div>
			<div class="row">
				<form class="form-register form-container ml-5 mr-5" method="post">
					<div class="form-box row align-items-center w-100">
							<label for="input-name" class="label-register">Nome completo</label>
								<input name="name" id="input-name" class="w-100" type="text" placeholder="Ex: João Silva" required/>
					</div>
					<div class="form-box row align-items-center w-100">
							<label for="input-username"  class="label-register">Username</label>
								<input name="username" id="input-username" class="w-100" type="text" placeholder="Ex: joaosilva" required/>
					</div>
					<div class="form-box row align-items-center w-100">
						<label for="input-password" class="label-register">Senha</label>
							<input name="password" id="input-password" class="w-100" name="password" type="text" placeholder="**********" required/>
					</div>
					<div class="form-box row align-items-center w-100">
						<label for="select-usertype">Tipo de usuário</label>
							<select name="usertype" id="select-usertype" class="w-100" required>
								<option value="">-- Selecione --</option>
								<?php
									foreach ($types as $type) {
										echo '<option>'.$type['tipo'].'</option>';
									}
								?>
							</select>
					</div>
					<div class="form-box row align-items-left">
						<label class="d-flex align-self-center">Justificativas para o usuário</label>
						<div class="align-items-left">
						<?php
							$index = 1;
							foreach ($justifications as $justification) {
								echo '	
									<div class="d-flex justify-content-left flex-direction-row align-items-center m-1">
										<input type="checkbox" name="motivo-'.$index.'" id="motivo-'.$index.'" class="d-inline-flex" value="'.$justification['motivo'].'"/><label for="motivo-'.$index.'" class="m-1">'.$justification['motivo'].'</label>
									</div>
								';
								$index++;
							}
						?>
						</div>
					</div>
					<div class="form-box row align-items-center mt-4 w-100">
						<div class="w-50 flex align-self-center">
							<button name="register" type="submit" class="btn btn-primary w-100 p-2" onclick="verifyCheckbox(event)">Cadastrar</button>
						</div>
					</div>
				</form>
				<form method="post" class="form-container ml-5 mr-5">
					<div class="form-box row align-items-center w-100">
						<div class="w-50 flex align-self-center">
							<button name="logout" type="submit" class="btn btn-danger w-100 p-2">Sair</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</body>
</html>
