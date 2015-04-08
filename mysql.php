<?php
if (!function_exists('mysql_connect')) {
class newdb {
	public static $info = [];
	public static $pdo;

	public static $results = [];	
	public static $error;
}

function mysql_connect($server, $username, $password) {
	newdb::$info['server'] = $server;
	newdb::$info['username'] = $username;
	newdb::$info['password'] = $password;


	return true;
 }

function mysql_select_db($name) {
	$dsn = 'mysql:dbname=' . $name . ';host=' .  newdb::$info['server'];
	newdb::$pdo = new \PDO($dsn, newdb::$info['username'], newdb::$info['password']);

	return true;

}

function mysql_free_result($id) {
	unset(newdb::$results[$id]);
	return true;
}

function mysql_insert_id() {
	return newdb::$pdo->lastInsertId();
}


function mysql_query($query) {
	$id = uniqid();
	try {
		newdb::$results[$id] = newdb::$pdo->prepare($query);
		newdb::$results[$id]->execute();
	}
	catch (Exception $e) {
		newdb::$error = $e->getMessage();
		return false;
	}

	return $id;

}

function mysql_error() {
	$err = newdb::$pdo->errorinfo()[0];
	if ((int) $err) return $err;
	else return false;
}

function mysql_fetch_array($id) {
	$o = newdb::$results[$id]->fetch();
	return $o;
}


function mysql_fetch_object($id) {
	$o = newdb::$results[$id]->fetchObject();
	return $o;
}


function mysql_num_rows($id) {
	newdb::$results[$id]->execute();
	$count = count(newdb::$results[$id]->fetchAll());
	newdb::$results[$id]->execute();
	return $count;
}

}
