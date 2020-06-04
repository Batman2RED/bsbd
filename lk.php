<?php
	require_once 'includes/sessions.php';

	if (!mySession_start())
	{
		header("location: login.php");
	}

?>

<!DOCTYPE HTML>

<html>
	<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" /> <!--Руссификация, путём определения кодировки-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Изменение ширины сайта в зависимости от разрешения устр-ва-->
    <title>
		Посмотреть книги. Забронировать книги. От сети библиотек Lib.
    </title>
    <link rel="stylesheet" type="text/css" href="css/css.css"> <!--Подключение стилей-->
		<link href="https://fonts.googleapis.com/css?family=Amatic+SC|Neucha|Pangolin|Poiret+One|Press+Start+2P|Rubik+Mono+One|Underdog&amp;subset=cyrillic" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-1.10.2.js"></script> <!--Подключение jQuery со скриптами-->
    <script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="js/scrpt.js"></script>
		<script src="js/it.js" language="javascript"></script>
		<link rel="shortcut icon" href="img/log.png">
  </head>
  <body>
	<div class="container">
		<div class="top"><img src="img/logo2.png" class="oboi">
		
		</div>
    <div class="menu"> <!--Меню-->
      <cat><a href="index.php">Главная</a></cat>
      <cat><a href="classic.php">Книги</a></cat>
      <cat><a href="sostav.php">Информация</a></cat>
			<cat><a href="logout.php">Выйти</a></cat>
      <cat><a href="cart.php">Корзина</a></cat>
    </div>

			<!-- КОНТЕНТ -->
			<div class="content">
				<div class="wrapper">

					<b>Добро пожаловать!</b>

					<?php 

						$sql = 'SELECT * FROM lib_user 
								INNER JOIN lib_accounts ON lib_accounts.user_id = lib_user.u_id 
								INNER JOIN lib_session ON lib_session.acc_id = lib_accounts.acc_id 
								INNER JOIN lib_availability ON lib_availability.acc_id = lib_accounts.acc_id
								WHERE lib_session.session_id = :sess_id';
						$stmt = $db->prepare($sql);
 						$stmt->execute([':sess_id' => $_COOKIE['SESSID']]);
 						$user = $stmt->fetch(PDO::FETCH_OBJ);

 						echo '<br>Вы вошли на сайт, как <u>'.$user->u_name.'</u>
 							  <br>На вашем счету '.$user->amount.' руб.
 							 ';

 						if ($user->u_role == 'admin')
 						{

 							echo "<br><br>Меню:
 							  <br><a href='orders.php' class='button'> Заказы</a><br>
							  <br><a href='book_controller.php' class='button'> Добавить книгу</a>
 							";
 						
 						}
 		
					?>

					
				</div>
			</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div class="footer">
		Lib <span>&copy; 2019</span><br>
		<span>Бронирование книг: бесплатно.</span>
		</div>
  </div>
  </body>
</html>