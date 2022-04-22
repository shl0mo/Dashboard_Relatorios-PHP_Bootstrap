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
	<div class="main-container-login w-25 p-5 border rounded">
		<form method="post" class="w-100 align-items-center d-flex">
			<div class="w-50 mb-3">
				<input type="text" class="input-login w-100 p-1 border border-secondary rounded" name="user" placeholder="Usuário" required/>
			</div>	
			<div class="w-50 mb-3">
				<input type="password" class="input-login w-100 p-1 border border-secondary rounded" name="password" placeholder="Senha" required/>
			</div>
			<div class="w-25 d-flex align-items-center">
					<button type="submit" class="btn btn-success w-100 p-1">Entrar</button>
			</div>
		</form>
	</div>
</body>
</html>
