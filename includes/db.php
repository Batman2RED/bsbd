<?php

	$host = 'localhost';
	$db_name = 'bsbd\_project';
	$db_user = 'bsbd_admin';
	$db_pass = '123';

	$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

	try {
		$db = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_pass, $options);
	} catch (PDOException $e) {
		die ('Подключение не удалось!' . $e->getMessage());
	}
	//***Admin***
	//Логин: Roman2
	//Пароль: 123
	//***User***
	//Логин: Fedor
	//Пароль: 123
?>
