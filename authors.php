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

	if(isset($_POST['author_del']) && $is_admin){
		$del_book_sql = 'DELETE FROM lib_authors WHERE author_id = :author_id;';
		$stmt = $db->prepare($del_book_sql);
		try{
			$stmt->execute([':author_id' => $_POST['author_del']]);
		}
		catch (Exception $e){
			echo('<div id="alertbox" style="color: red; background: yellow;">Невозможно удалить запись, т.к. в базе данных еще существуют книги данного автора!</div>');
			die();
		}
		echo('<div id="alertbox" style="color: white; background: green;">Успешно удалено!</div>');
		die();
	}
	
	if(isset($_POST['author_edit']) && $is_admin){
		$author_id = $_POST['author_edit'];
		$stmt = $db->prepare("SELECT * FROM lib_authors WHERE author_id = :author_id");
		$stmt->execute([':author_id' => $author_id]);
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo('
			<div class="wrapper" style=" text-align: center;">
				<b>Изменение автора №' . $author_id . ':</b>
				<form action=authors.php method=POST enctype="multipart/form-data">
					<p>
						<table  border="0" style="margin: auto; text-align: left;">
							<tr>
								<td><b>Фамилия: </b></td>
								<td><input type="text" name="update_author[lastname]" value="' . $row['author_lastname'] . '"></td>
							</tr>
							<tr>
								<td><b>Имя: </b></td>
								<td><input type="text" name="update_author[firstname]" value="' . $row['author_firstname'] . '"></td>
							</tr>
							<tr>
								<td><b>Отчество: </b></td>
								<td><input type="text" name="update_author[patronymic]" value="' . $row['author_patronymic'] . '"></td>
							</tr>
							<tr>
								<td><b>Год рождения: </b></td>
								<td><input type="text" name="update_author[year]" value="' . $row['author_birthyear'] . '"></td>
							</tr>
							<tr>
								<td colspan="2"><input type="hidden" name="update_author[author_id]" value="' . $row['author_id'] . '"><br></td>
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
	
	if(isset($_POST['update_author']) && $is_admin){
		$upd_author = $_POST['update_author'];
		if(empty($upd_author['lastname']) || empty($upd_author['firstname']) || empty($upd_author['patronymic']) || empty($upd_author['year'])){
			echo('<div style="color: red; background: yellow; text-align: center; padding: 8px;font-size: 18px;">Заполните все поля!</div>');
			die();
		}
		else{
			$upd_author_sql = 'UPDATE lib_authors SET author_lastname = :lastname, author_firstname = :firstname, author_patronymic = :patronymic, author_birthyear = :year
								WHERE author_id = :author_id;';
			
			$upd_author_params = [ ':lastname' => trim($upd_author['lastname']), 
									':firstname' => trim($upd_author['firstname']),
									':patronymic' => trim($upd_author['patronymic']),
									':year' => trim($upd_author['year']),
									':author_id' => trim($upd_author['author_id'])
								];
			$stmt = $db->prepare($upd_author_sql);
			$stmt->execute($upd_author_params);

			header("location: authors.php");
			die();
		}
	}
	
	if(isset($_POST['add_author']) && $is_admin){
		$add_author = $_POST['add_author'];
		if(empty($add_author['lastname']) || empty($add_author['firstname']) || empty($add_author['patronymic']) || empty($add_author['year'])){
			echo('<div id="alertbox" style="color: red; background: yellow;">Заполните все поля!</div>');
		}
		else{			
			$add_author_sql = 'INSERT INTO lib_authors (author_lastname, author_firstname, author_patronymic, author_birthyear)
								VALUES (:lastname, :firstname, :patronymic, :year);';
			
			$add_author_params = [ ':lastname' => trim($add_author['lastname']), 
									':firstname' => trim($add_author['firstname']),
									':patronymic' => trim($add_author['patronymic']),
									':year' => trim($add_author['year'])
								];
			$stmt = $db->prepare($add_author_sql);
			$stmt->execute($add_author_params);
					
			header("location: authors.php");
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
		Список авторов. От сети библиотек Lib.
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
					<table border="1" cellpadding="5" style="margin: auto; text-align: center;">
						<caption><b>Список авторов:<b></caption>
						<tr>
							<th>ID:</th>
							<th>Фамилия:</th>
							<th>Имя:</th>
							<th>Отчество:</th>
							<th>Год рождения:</th>
							<th>Управление:</th>
						</tr>
						<?php
							$stmt = $db->query("SELECT * FROM lib_authors");
							$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
							if(!empty($data)){
								foreach($data as $author){
									echo('
										<tr>
											<td>'. $author['author_id'] .'</td>
											<td>'. $author['author_lastname'] .'</td>
											<td>'. $author['author_firstname'] .'</td>
											<td>'. $author['author_patronymic'] .'</td>
											<td>'. $author['author_birthyear'] .'</td>
											<td><a style="color:red; cursor: pointer;" act="author_edit" author_id="'.  $author['author_id'] .'">Редактировать</a>
											<br><a href=# style="color:red" act="author_del" author_id="'.  $author['author_id'] .'">Удалить</a></td>
										</tr>
									');
								}
							}
							else echo('<tr><td colspan="6">Список пуст :(</td></tr>');
						?>
					</table>

					<form action=authors.php method=POST enctype="multipart/form-data">
					<p>
						<table style="margin: auto; text-align: left;">
							<tr>
								<td><b>Фамилия: </b></td>
								<td><input type="text" name="add_author[lastname]"></td>
							</tr>
							<tr>
								<td><b>Имя: </b></td>
								<td><input type="text" name="add_author[firstname]"></td>
							</tr>
							<tr>
								<td><b>Отчество: </b></td>
								<td><input type="text" name="add_author[patronymic]"></td>
							</tr>
							<tr>
								<td><b>Год рождения: </b></td>
								<td><input type="text" name="add_author[year]"></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><br><input type="submit" value="Добавить автора"></td>
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