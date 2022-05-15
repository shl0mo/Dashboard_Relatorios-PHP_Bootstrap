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

	echo '<h1>Editar</h1>';	
?>
