<?php
	if (isset($_POST['register'])) {
		$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
		$query = $pdo->prepare('SELECT * FROM tipos');
		$query->execute();
		$types = $query->fetchAll();
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
</head>
<body>
	<div class="h-100 d-flex justify-content-center aling-items-center">
		<div class="position-absolute align-self-center container w-25 p-5 border rounded">
			<div class="row w-100">
				<h4 class="h4-register d-flex align-self-center">Cadastro de Usuário</h4>
			</div>
			<div class="row">
				<form class="form-register form-container" method="post" class="w-100 align-items-center d-flex">
					<div class="form-box row align-items-center w-100">
							<label for="input-name" class="label-register">Nome completo</label>
								<input name="name" id="input-name" class="w-75" type="text" placeholder="Ex: João Silva" required/>
					</div>
					<div class="form-box row align-items-center w-100">
							<label for="input-username"  class="label-register">Nome de usuário</label>
								<input name="username" id="input-username" class="w-75" type="text" placeholder="Ex: joaosilva" required/>
					</div>
					<div class="form-box row align-items-center w-100">
						<label for="input-password" class="label-register">Senha</label>
							<input name="password" id="input-password" class="w-75" name="password" type="text" placeholder="**********" required/>
					</div>
					<div class="form-box row align-items-center w-100">
						<label for="select-usertype">Tipo de usuário</label>
							<select name="usertype" id="select-usertype" class="w-75" required>
								<option value="">-- Selecione --</option>
								<?php
									foreach ($types as $type) {
										echo '<option>'.$type['tipo'].'</option>';
									}
								?>
							</select>
					</div>
					<div class="form-box row align-items-center w-100">
						<label>Justificativas para o usuário</label>
					</div>
					<div class="form-box row align-items-center mt-4 w-100">
						<div class="w-25 flex align-self-center">
							<button name="register" type="submit" class="btn btn-success w-100 p-2">Cadastrar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
