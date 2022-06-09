<?php require_once('./views/header-sidebar.php');?>
	<?php
		if (isset($_POST['confirm'])) {
			header('Location: http://localhost/Relatorios/listar.php');
		}
	?>
	<style>
		#success-container {
			margin-top: 100px;
			box-shadow: 0px 2px 10px rgba(0,0,0,0.2);
		}
	</style>
	<div id="success-container" class="d-flex align-self-center w-75 justify-content-center flex-column rounded py-5">
		<form method="post">
			<div class="w-100 d-flex justify-content-center mb-2">
				<h2>Cadastro efetuado com sucesso</h2>
			</div>
			<div class="w-100 d-flex justify-content-center">
				<button type="submit" name="confirm" class="btn btn-primary px-2 px-5">OK</button>
			</div>
		</form>
	</div>
</div>
</html>
