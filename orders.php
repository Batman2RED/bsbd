<?php
	require_once 'includes/sessions.php';

	if (!mySession_start())
	{
		header("location: login.php");
	}

	/* ПРОВЕРКА НА АДМИНА [НАЧАЛО] */
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
 	/* ПРОВЕРКА НА АДМИНА [КОНЕЦ] */

	if (isset($_GET['action']) && $_GET['action']=="drop")
	{ 
		$sql = 'DELETE FROM lib_cart WHERE order_id = :order_id';
		$params = [ ':order_id' => strval(trim($_GET['id'])) ];
		$stmt = $db->prepare($sql);
		$stmt->execute($params);
	}

	if (isset($_GET['action']) && $_GET['action']=="update")
	{
		$sql = 'UPDATE lib_orders SET order_status = "Доставляется" WHERE order_id = :order_id';
		$params = [ ':order_id' => strval(trim($_GET['id'])) ];
		$stmt = $db->prepare($sql);
		$stmt->execute($params);
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
      <cat><a href="sostav.php">Красткое содержание</a></cat>
			<cat>
			<?php
				if (mySession_start())
					echo '<a href="lk.php">Личный кабинет</a>';
				else
					echo '<a href="login.php">Авторизация</a>';
			?>
			</cat>
      <cat><a href="cart.php">Корзина</a></cat>
    </div>

			<!-- КОНТЕНТ -->
			<div class="content">
				<div class="wrapper">
					<h1>Список заказов</h1> 
					<table> 
          					<tr> 
            					<th>Идентификатор</th> 
            					<th>Наименования</th> 
            					<th>Количество</th> 
            					<th>Статус</th> 
            					<th>Сумма</th> 
        					</tr>

							<?php
								$sql = 'SELECT * FROM lib_orders
										INNER JOIN lib_cart ON lib_cart.order_id = lib_orders.order_id
									   ';

								$stmt = $db->query($sql);

								$totalorder = array('id' => 0, 'price' => 0, 'status' => '');

								while ($order = $stmt->fetch(PDO::FETCH_OBJ)) 
								{
							?>		
									<tr>
										<td> 
											<?php 
												if ($totalorder['id'] != $order->order_id) 
												{	
													echo $order->order_id; 
												}
												else
													echo "";
											?> 
										</td>

										<td>	
											<?php echo $order->book_tittle ?>
										</td>

										<td>
											<?php echo $order->count ?>
										</td>

										<td>
											<?php 
												if ($totalorder['id'] !=  $order->order_id) 
												{
													$totalorder['status'] = $order->order_status;
													echo $order->order_status;
												}
											?>
										</td>

										<td>
											<?php
												$totalorder['price'] = $order->price * $order->count;
												if ($totalorder['id'] != $order->order_id) 
												{
													echo $totalorder['price'].' руб.';
												}
											?>
										</td>

										<td>
											<?php 
												if ($totalorder['id'] != $order->order_id) 
												{
													$totalorder['status'] = '';
													$totalorder['id'] = $order->order_id;
											?>
											

													<a 	class="button" 
													   	href="orders.php?page=orders&action=drop&id=<?php echo  $totalorder['id'] ?>" >
        												Отменить заказ
        											</a> 

        											<a 	class="button" 
        												href="orders.php?page=orders&action=update&id=<?php echo  $totalorder['id'] ?>" >
        												Изменить статус заказа
        											</a> 
        								
										  <?php } ?>
										
										</td>
									</tr>
						  <?php } ?>
		
        			</table>
				</div>
			</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div class="footer">
		Lib <span>&copy; 2019</span><br>
		<span>Бронирование книг: бесплатно.</span>
		</div>
  </div>
  </body>
</html>