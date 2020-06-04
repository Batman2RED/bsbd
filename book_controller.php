<?php
	require_once 'includes/sessions.php';

	if (!mySession_start())
	{
		header("location: login.php");
	}

	if(isset($_POST['del'])){
		$del_book_sql = 'DELETE FROM lib_book
								WHERE book_id = :book_id; 
						DELETE FROM lib_list
								WHERE book_id = :book_id;';

		$stmt = $db->prepare($del_book_sql);
		$stmt->execute([':book_id' => $_POST['del']]);
		die();
	}

	if(isset($_COOKIE['SESSID']))
	{
		$sql = 'SELECT * FROM lib_user 
					INNER JOIN lib_accounts ON lib_accounts.user_id = lib_user.u_id 
					INNER JOIN lib_session ON lib_session.acc_id = lib_accounts.acc_id 
					INNER JOIN lib_availability ON lib_availability.acc_id = lib_accounts.acc_id
					WHERE lib_session.session_id = :sess_id';

		$stmt = $db->prepare($sql);
 		$stmt->execute([':sess_id' => $_COOKIE['SESSID']]);
 		$user = $stmt->fetch(PDO::FETCH_OBJ);

		if ($user->u_role != 'admin')
 		{
 			header("location: lk.php");
 		}
	}

	
	if(isset($_POST['book'])){
		$book = $_POST['book'];
		if(empty($book['tittle']) || empty($book['price']) || empty($book['author']) || empty($book['publisher']) || !isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK ){
			echo('<div style="color: red; text-align: center; background: yellow; padding: 8px; font-size: 18px;">Заполните все поля!</div>');
		}
		else
		{
			
			if($_FILES['image']['error'] == UPLOAD_ERR_OK)
			{
				$name = 'img/' . md5(uniqid()) . '.jpg';
				move_uploaded_file($_FILES['image']['tmp_name'],  $name);
				unset($_FILES['image']);
			}

			$add_book_sql = 'INSERT INTO lib_book (book_tittle, book_price, book_img) 
								VALUES (:tittle, :price, :img); 
							SET @lastID := LAST_INSERT_ID();
							INSERT INTO lib_list (book_id, info, info2)
								VALUES (@lastID, :author, :publisher)';
			$add_book_params = [ ':tittle' => $book['tittle'], 
									':price' => $book['price'], 
									':img' => $name,
									':author' => $book['author'],
									':publisher' => $book['publisher']
								];

			$stmt = $db->prepare($add_book_sql);
			$stmt->execute($add_book_params);
			
			header("location: sostav.php");
		}
	}

?>

<!DOCTYPE HTML>

<html>
	<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" /> <!--Руссификация, путём определения кодировки-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Изменение ширины сайта в зависимости от разрешения устр-ва-->
    <title>
		Добавление книги. От сети библиотек Lib.
    </title>
    <link rel="stylesheet" type="text/css" href="css/css.css"> <!--Подключение стилей-->
		<link href="https://fonts.googleapis.com/css?family=Amatic+SC|Neucha|Pangolin|Poiret+One|Press+Start+2P|Rubik+Mono+One|Underdog&amp;subset=cyrillic" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-1.10.2.js"></script> <!--Подключение jQuery со скриптами-->
    <script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
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

					<form action=# method=POST enctype='multipart/form-data'>
						<p>
							<table  border="0" style="margin: auto; text-align: left;">
								<tr>
									<td><b>Название: </b></td>
									<td><input type="text" name="book[tittle]"></td>
								</tr>
								<tr>
									<td><b>Цена: </b></td>
									<td><input type="text" name="book[price]"></td>
								</tr>
								<tr>
									<td><b>Автор: </b></td>
									<td><input type="text" name="book[author]"></td>
								</tr>
								<tr>
									<td><b>Издатель: </b></td>
									<td><input type="text" name="book[publisher]"></td>
								</tr>
								<tr>
									<td colspan="2"><br></td>
								</tr>
								<tr>
									<td colspan="2" cellspacing="10"><input type="file" name="image" accept="image/jpeg"></td>
								</tr>
								<tr>
									<td colspan="2"><br></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="submit"></td>
								</tr>
							</table>
						</p>
					</form>
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