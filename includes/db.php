<?php

	$host = 'localhost';
	$db_name = 'bsbd\_project';
	$db_user = 'root';
	$db_pass = '';

	$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

	try {
		$db = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_pass, $options);
	} catch (PDOException $e) {
		die ('Подключение не удалось!' . $e->getMessage());
	}
	//Логин: Roman2
	//Пароль: 123
?>
