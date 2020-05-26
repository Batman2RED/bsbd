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
      <cat><a href="sostav.php">Краткое содержание</a></cat>
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
		

<div class="content">
				<div class="wrapper">
					<table class="catalog-list">
						<?php 

							$sql = 'SELECT * FROM lib_list WHERE book_tittle = :book_tittle';
							$stmt = $db->prepare($sql);

							$view = $db->query("SELECT * FROM lib_book");

							foreach ($view as $tittle)
							{	
								$stmt->execute([':book_tittle' => $tittle['book_tittle'] ]);
								$structures = '';

    							while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
								{	
									$structures = $structures.', '.$row['structure'];
								}

								$structures = ltrim($structures, ",");

								echo '<div class="lib-item">
									  	<img src="'.$tittle['book_img'].'" height=20%>
									  	<div class="product-composition">
									  		<h3>'.$tittle['book_tittle'].'</h3>
									  		<b>'.$structures.'</b>
									  	</div>
									  </div>';

							}

							$sql = null;
							$stmt = null;
							$view = null;
						?>
					</table>
				</div>
			</div>
<div class="footer">
		Lib <span>&copy; 2019</span><br>
		<span>Бронирование книг: бесплатно.</span>
		</div>
  </div>
  </body>
</html>