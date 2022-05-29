<?php
// Database handler
class Dbh {

	protected function connect() { // to use this class, set it to protected and you must extend
		try {
			$host = '';
			$username = '';
			$password = '';
			$dbname = '';
			$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
			return $dbh;
		}
		catch (PDOException $e) {
			$_SESSION['error'] = 'Error! '. $e->getMessage();
			header('location: ../index.php');
			die();
		}
	}

}