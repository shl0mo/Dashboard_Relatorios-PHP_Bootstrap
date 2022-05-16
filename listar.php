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
	$query = $pdo->prepare('SELECT * FROM dados WHERE fk_usuario = "'.$_SESSION['session'].'"');
	$query->execute();
	$data_array = $query->fetchAll();
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
			<a href="./" class="nav-link active text-white">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
				<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
				</svg>
				 Novo contato
			</a>
		      </li>
		      <hr/>
		      <li>
			<a href="./listar.php" class="nav-link text-white">
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
		<div class="w-100 d-flex container flex-column" style="flex: 1;">
			<div class="container d-flex flex-row">
				<div>
					<input type="text" placeholder="Nome completo">
				</div>
				<div>
					<button class="btn btn-primary p-2 ml-1 d-flex h-100 align-items-center">Pesquisar</button>
				</div>
			</div>
			<?php
				echo '<table>
					<thead>
						<th>ID</th>
						<th>Nome</Nome>
						<th>Canal de Origem</th>
						<th>Status</th>
					</thead>
				';
				foreach ($data_array as $costumer_data) {
					echo '
							<tr>
								<td>
									<div>'.$costumer_data['id'].'</div>
								</td>
								<td>
									<div>'.$costumer_data['nome'].'</div>
								</td>
								<td>
									<div>'.$costumer_data['canal_origem'].'</div>
								</td>
								<td>
									<div>Editar</div>
								</td>
							</tr>
					';
				}
				echo '</table>';
				?>
		</div>
	</div>
</body>
</html>