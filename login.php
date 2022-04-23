<?php
	session_start();
	if (isset($_SESSION['session'])) {
		echo '<h1>'.$_SESSION['session'].'</h1>';
		header('Location: http://localhost/Relatorios');
		exit();
		
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
		<style>
			.login-failed {
				
			}
		</style>
		<script>
			const loginFailed = () => {
				loginFailed_html = `<div class="btn-danger align-self-center w-50 text-center login-failed rounded mb-3">Usuário ou senha inválidos</div>`
				document.getElementById("login-status").innerHTML = loginFailed_html.trim()
			}
		</script>
</head>
<body>
	<div class="main-container-login container w-25 p-5 border rounded">
		<div class="row">
			<h4 class="pb-3">Login</h4>
		</div>
		<div id="login-status" class="row"></div>
		<div class="row">
			<form method="post" class="w-100 align-items-center d-flex">
				<div class="w-50 mb-3">
					<input type="text" class="input-login w-100 p-1 rounded" name="user" placeholder="Usuário" required/>
				</div>	
				<div class="w-50 mb-3">
					<input type="password" class="input-login w-100 p-1 rounded" name="password" placeholder="Senha" required/>
				</div>
				<div class="w-25 d-flex align-items-center">
						<button type="submit" name="login" class="btn btn-success w-100 p-1">Entrar</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
<?php
	if (isset($_POST['login'])) {
		$pdo = new PDO('mysql:host=localhost;dbname=dados_clientes', 'root', '');
		$user = $_POST['user'];
		$password = $_POST['password'];
		$query = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = "'.$user.'" AND senha = "'.$password.'"');
		$query->execute();
		$data = $query->fetchAll();
		if (count($data) > 0) {
			$_SESSION['session'] = $user;
			echo '<h1>Logado com sucesso</h1>';
			header('Location: http://localhost/Relatorios');
			exit();
		} else {
			echo '<script>loginFailed()</script>';
		}
	}
?>
