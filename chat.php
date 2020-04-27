<?php
	session_start();
	include_once "defines.php";
	require_once('classes/BD.class.php');
	BD::conn();

	if(!isset($_SESSION['email_logado'], $_SESSION['id_user'])){
		header("Location: index.php");
	}

	$pegaUser = BD::conn()->prepare("SELECT * FROM `usuarios` WHERE `email` = ?");
	$pegaUser->execute(array($_SESSION['email_logado']));
	$dadosUser = $pegaUser->fetch();

	if(isset($_GET['acao']) && $_GET['acao'] == 'sair'){
		unset($_SESSION['email_logado']);
		unset($_SESSION['id_user']);
		session_destroy();
		header("Location: index.php");
	}
?>
<!DOCTYPE HTML>
<html lang="pt-BR">
	<head>
		<meta charset=UTF-8>
		<title>chat</title>
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery_play.js"></script>
		<script type="text/javascript">
			$.noConflict();
		</script>
		
	</head>

	<body>

	<header class="main-header">
        <div class="container">
        
          
		<span class="user_online" id="<?php echo $dadosUser['id'];?>"></span>
		<h2>Bienvendo <?php echo $dadosUser['nome'];?></h2>
              
    	<a class="salir" href="?acao=sair">Salir</a>
         
      </header>
     
	
		<aside id="users_online">
			<ul>
			<?php
				$pegaUsuarios = BD::conn()->prepare("SELECT * FROM `usuarios` WHERE `id` != ?");
				$pegaUsuarios->execute(array($_SESSION['id_user']));
				while($row = $pegaUsuarios->fetch()){
					$foto = ($row['foto'] == '') ? 'default.jpg' : $row['foto'];
					$blocks = explode(',', $row['blocks']);
					$agora = date('Y-m-d H:i:s');
					if(!in_array($_SESSION['id_user'], $blocks)){
						$status = 'on';
						if($agora >= $row['limite']){
							$status = 'off';
							
						}
			?>
				<li id="<?php echo $row['id'];?>">
					<div class="imgSmall"><img src="fotos/<?php echo $foto;?>" border="0" /></div>
					<a href="#" id="<?php echo $_SESSION['id_user'].':'.$row['id'];?>" class="comecar"><?php echo utf8_encode($row['nome']);?></a>
					<span id="<?php echo $row['id'];?>" class="status <?php echo $status;?>"></span>
				</li>
			<?php }}?>
			</ul>
		</aside>

		<aside id="chats">
			
		</aside>
		<script type="text/javascript" src="js/functions.js"></script>
	</body>
</html>
