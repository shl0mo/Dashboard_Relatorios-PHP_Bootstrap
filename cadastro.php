<?php
	
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
	<div class="main-container-register position-absolute align-self-center container w-25 p-5 border rounded">
		<div class="row w-100">
			<h4 class="h4-register d-flex align-self-center">Cadastro de Usuário</h4>
		</div>
		<div class="row">
			<form class="form-register form-container" method="post" class="w-100 align-items-center d-flex">
				<div class="form-box row align-items-center">
						<label for="input-name" class="label-register">Nome completo</label>
							<input id="input-name" class="w-75" name="name" type="text"  placeholder="Ex: João Silva"/>
				</div>
				<div class="form-box row align-items-center">
						<label for="input-username"  class="label-register">Nome de usuário</label>
							<input id="input-username" class="w-75" name="username" type="text" placeholder="Ex: joaosilva"/>
				</div>
				<div class="form-box row align-items-center">
					<label for="input-password"  class="label-register">Senha</label>
						<input id="input-password" class="w-75" name="password" type="text" placeholder="**********"/>
				</div>
				<div class="form-box row align-items-center mt-4">
					<div class="w-25 flex align-self-center">
						<button type="submit" class="btn btn-success w-100 p-2">Cadastrar</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	</div>
</body>
</html>
