<?php
	require_once 'includes/sessions.php';
	if (mySession_start())
	{
		header("location: lk.php");
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
			<cat>
			<?php
				if (mySession_start())
					echo '<a href="lk.php">Личный кабинет</a>';
				else
					echo '<a href="login.php">Личный кабинет</a>';
			?>
			</cat>
      <cat><a href="cart.php">Корзина</a></cat>
    </div>

			<!-- КОНТЕНТ -->
			<div class="content">
				<div class="wrapper">
					
					<div class="auth">
						<form action="" method="post">
 							E-mail: <input type="text" name="login" />
 							Пароль: <input type="password" name="password" />
 							<input class="button" type="submit" value="Войти" name="log_in" />
						</form>
					</div>

					<?php
						if (isset($_POST['log_in'])) 
						{
							$login = htmlspecialchars( trim($_POST['login']) ); 
							$password = htmlspecialchars( trim($_POST['password']) );
					
							if (!empty($login) && !empty($password))
 							{
 								$sql = 'SELECT acc_id, acc_password FROM lib_accounts WHERE acc_email = :login';
 								$params = [':login' => $login];
 								$stmt = $db->prepare($sql);
 								$stmt->execute($params);

 								$user = $stmt->fetch(PDO::FETCH_OBJ);

 								if ($user) 
 								{
 									if ($user->acc_password == md5($password))
 									{
 										mySession_write($user->acc_id);
 										header('Location: lk.php');
 									}
 									else
 										echo "Неверный логин или пароль!"; 
 								}
 								else
 									echo "Пользователь не найден!";
 							}
 							else
 								echo "Неверно задан логин или пароль!"; 
						}
					?>

				</div>
			</div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		<div class="footer">
		Lib <span>&copy; 2019</span><br>
		<span>Бронирование книг: бесплатно.</span>
		</div>
  </div>
  </body>
</html>