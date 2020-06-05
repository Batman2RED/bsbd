<?php
	require_once 'includes/sessions.php';

	$is_admin = false;

	if (!mySession_start()){
		header("location: login.php");
	}
	else{
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
		else $is_admin = true;
	}

	if(isset($_POST['del']) && $is_admin){
		$del_book_sql = 'DELETE FROM lib_book
								WHERE book_id = :book_id; 
						DELETE FROM lib_authors
								WHERE book_id = :book_id;';

		$stmt = $db->prepare($del_book_sql);
		$stmt->execute([':book_id' => $_POST['del']]);
		die();
	}

	if(isset($_POST['edit']) && $is_admin){
		$book_id = $_POST['edit'];
		$stmt = $db->prepare("SELECT * FROM lib_book WHERE book_id = :book_id");
		$stmt->execute([':book_id' => $book_id]);
		
		$authors = '';

		$a_stmt = $db->prepare("SELECT * FROM lib_authors WHERE book_id = :book_id");
		$a_stmt->execute([':book_id' => $book_id]);
		while ($row = $a_stmt->fetch(PDO::FETCH_ASSOC))
		{	
			if(!empty($row['book_author'])) $authors .= $row['book_author'].', ';
			
		}

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo('
			<div class="wrapper" style=" text-align: center;">
				<b>Изменение книги №' . $book_id . ':</b>
				<form action=book_controller.php method=POST enctype="multipart/form-data">
					<p>
						<table  border="0" style="margin: auto; text-align: left;">
							<tr>
								<td><b>Название: </b></td>
								<td><input type="text" name="update_book[tittle]" value="' . $row['book_tittle'] . '"></td>
							</tr>
							<tr>
								<td><b>Цена: </b></td>
								<td><input type="text" name="update_book[price]" value="' . $row['book_price'] . '"></td>
							</tr>
							<tr>
								<td><b>Авторы: </b></td>
								<td><input type="text" name="update_book[author]" placeholder="Через запятую" value="' . trim($authors, ' ,') . '"></td>
							</tr>
							<tr>
								<td><b>Издатель: </b></td>
								<td><input type="text" name="update_book[publisher]" value="' . $row['book_publisher'] . '"></td>
							</tr>
							<tr>
								<td colspan="2"><br></td>
							</tr>
							<!--<tr>
								<td colspan="2" cellspacing="10"><input type="file" name="image" accept="image/jpeg"></td>
							</tr>-->
							<tr>
								<td colspan="2"><input type="hidden" name="update_book[book_id]" value="' . $row['book_id'] . '"><br></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit"></td>
							</tr>
						</table>
					</p>
				</form>
			</div>
			');
		}
		
		die();
	}

	if(isset($_POST['update_book']) && $is_admin){
		$upd_book = $_POST['update_book'];
		if(empty($upd_book['tittle']) || empty($upd_book['price']) || empty($upd_book['author']) || empty($upd_book['publisher'])){
			echo('<div style="color: red; text-align: center; background: yellow; padding: 8px; font-size: 18px;">Заполните все поля!</div>');
			die();
		}
		else{

			//Обновляем данные о книге:
			$upd_book_sql = 'UPDATE lib_book SET book_tittle = :tittle, book_price = :price, book_publisher = :publisher 
								WHERE book_id = :book_id;';
			
			$upd_book_params = [ ':tittle' => $upd_book['tittle'], 
									':price' => $upd_book['price'], 
									':publisher' => $upd_book['publisher'],
									':book_id' => $upd_book['book_id']
								];
			$stmt = $db->prepare($upd_book_sql);
			$stmt->execute($upd_book_params);

			//Обновляем авторов книги:
			$stmt = $db->prepare('DELETE FROM lib_authors WHERE book_id = :book_id;');
			$stmt->execute([':book_id' => $upd_book['book_id']]);

			$authors = explode(',', trim($upd_book['author']));
			$upd_author_sql = 'UPDATE lib_authors book_author = :book_author
								WHERE book_id = :book_id;';
			$stmt = $db->prepare($upd_author_sql);

			$add_author_sql = 'INSERT INTO lib_authors (book_id, book_author)
								VALUES (:book_id, :book_author);';
			$stmt = $db->prepare($add_author_sql);

			foreach($authors as $author)
			{
				if(!empty($author)) 
				{
					$add_author_params = [ 
									':book_id' => $upd_book['book_id'], 
									':book_author' => trim($author)
								];
					$stmt->execute($add_author_params);
				}
			}
			header("location: sostav.php");
			die();
		}
	}
	
	if(isset($_POST['book']) && $is_admin){
		$book = $_POST['book'];
		if(empty($book['tittle']) || empty($book['price']) || empty($book['author']) || empty($book['publisher']) || !isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK ){
			echo('<div style="color: red; text-align: center; background: yellow; padding: 8px; font-size: 18px;">Заполните все поля!</div>');
		}
		else{			
			if($_FILES['image']['error'] == UPLOAD_ERR_OK)
			{
				$name = 'img/' . md5(uniqid()) . '.jpg';
				move_uploaded_file($_FILES['image']['tmp_name'],  $name);
				unset($_FILES['image']);
			}
						
			$add_book_sql = 'INSERT INTO lib_book (book_tittle, book_price, book_img, book_publisher) 
								VALUES (:tittle, :price, :img, :publisher);';
			
			$add_book_params = [ ':tittle' => $book['tittle'], 
									':price' => $book['price'], 
									':img' => $name,
									':publisher' => $book['publisher']
								];
			$stmt = $db->prepare($add_book_sql);
			$stmt->execute($add_book_params);

			$new_book = $db->query('SELECT * FROM lib_book ORDER BY book_id DESC LIMIT 1');
			$new_book_id = '';
			foreach($new_book as $row) $new_book_id = $row['book_id'];

			$authors = explode(',', trim($book['author']));
			$add_author_sql = 'INSERT INTO lib_authors (book_id, book_author)
								VALUES (:book_id, :book_author);';
			$stmt = $db->prepare($add_author_sql);

			foreach($authors as $author)
			{
				if(!empty($author)) 
				{
					$add_author_params = [ 
									':book_id' => $new_book_id, 
									':book_author' => trim($author)
								];
					$stmt->execute($add_author_params);
				}
			}
			
			header("location: sostav.php");
			die();
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
									<td><b>Авторы: </b></td>
									<td><input type="text" name="book[author]" placeholder="Через запятую"></td>
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