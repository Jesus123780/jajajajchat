<?php
	session_start();
	include_once "defines.php";
	require_once('classes/BD.class.php');
	BD::conn();
?>
<!DOCTYPE HTML>
<html lang="pt-BR">
	<head>
		<meta charset=UTF-8>
		<title>CHAT</title>
	</head>
	<body>
		<div class="formulario">
			<h1>Ingrese correo</h1>
			<?php
				if(isset($_POST['acao']) && $_POST['acao'] == 'logar'){
					$email = strip_tags(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING)));
					if($email == ''){
						echo 'informe o email';
					}else{
						$pegaUser = BD::conn()->prepare("SELECT * FROM `usuarios` WHERE `email` = ?");
						$pegaUser->execute(array($email));

						if($pegaUser->rowCount() == 0){
							echo 'Não encontramos este login!';
						}else{
							$agora = date('Y-m-d H:i:s');
							$limite = date('Y-m-d H:i:s', strtotime('+2 min'));
							$update = BD::conn()->prepare("UPDATE `usuarios` SET `horario` = ?, `limite` = ? WHERE `email` = ?");
							if( $update->execute(array($agora, $limite, $email)) ){
								while($row = $pegaUser->fetchObject()){
									$_SESSION['email_logado'] = $email;
									$_SESSION['id_user'] = $row->id;
									header("Location: chat.php");
								}
							}
						}
					}
				}
			?>
			<form action="" method="post" enctype="multipart/form-data">
				<label>
					<span>Ingrese el correo </span>
					<input type="text" name="email" placeholder="Ingrese el correo "/>
				</label>
				<input type="hidden" name="acao" value="logar" />
				<input type="submit" value="Entrar" class="botao right" />
			</form>
		</div>
	</body>
</html>