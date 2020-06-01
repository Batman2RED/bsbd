<?php
	require_once 'includes/db.php';
	require_once 'includes/sessions.php';
?>

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
		<div class="sidebar">
		<p><img src="img/1.svg" class="mava"><pop> Мы можем доставить вам книги</pop></p>
		<p><img src="img/2.svg" class="mava"><pop> Подписка бесплатная</pop></p>
		<p><img src="img/3.svg" class="mava"><pop> Дружелюбный персонал</pop></p>
		</div>
			<div class="border2">
			<div class="opis"><bor2><p> Сервис Lib помогает делать оригинальные подарки. Ведь книга это лучший подарок.</p>
			<p> Что можно с Lib?</p>
			<p> - Удивить свою «вторую половинку» огромной стопкой книг, которые вы никогда не читали и держите их только для виду.</p></bor2>
			<p> - Подбодрить коллегу по работе – привезем коробку с книгами посреди рабочего дня.</p></bor2>
			<p> - Заказать книгу с бесплатной доставкой потенциальному партнеру по бизнесу. Оригинальный подарок поможет настроить его на благодушный лад перед важными переговорами.</p></bor2>
			<p> - Поздравить близких. Даже если вы далеко от родных, хотя так хотели быть рядом – дайте им понять, что помните о семье. Закажите книгу родителям на годовщину свадьбы – ее привезут в нужное время и торжественно вручат супругам.</p></bor2>
				</div>
      </div>
    <div class="footer">
		Lib <span>&copy; 2019</span><br>
		<span>Бронирование книг: бесплатно.</span>
		</div>
  </div>
  </body>
</html>
