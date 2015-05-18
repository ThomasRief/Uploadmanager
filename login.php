<?php

require_once( 'classes/connection.class.php' );
require_once( 'classes/user.class.php' );

require_once( 'config/db_data.config.php' );
require_once( 'config/user_table.config.php' );

	//check for valid login, redirects to account.php on success, index.php?failedLogin on failure and index.php?toFast on spam detection. On success, saves users id (out of database) in $_SESSION['userID'])
	session_start();

	if( isset($_POST['submit']) )
	{
		//check for spam
		$current_time = gettimeofday();
		$current_time = $current_time['sec'];
		
		if( isset($_SESSION['time']) )
		{
			$next_allowed_time = $_SESSION['time'] + 3;
			if( $next_allowed_time > $current_time )
			{
				header( 'Location: ./index.php?toFast' );
				exit;
			}
		}
		
		$username = $_POST['name'];
		$password = $_POST['password'];
		$pwd_hash = hash('sha256', $password);
		
		$connection = new connection( DB_USER, DB_PASS, DB_NAME );

		$sql = 'SELECT '.USERTABLE_USERID.' FROM '.USERTABLE_NAME.' WHERE '.USERTABLE_USERNAME.' = :username AND '.USERTABLE_USERPASS.' = :passwordHash;';
		$stm = $connection->connection->prepare( $sql );
		$stm->bindValue( ':username', $username );
		$stm->bindValue( ':passwordHash', $pwd_hash );

		$result = $connection->executeStatement( $stm );
		
		$id = $result->fetchAll();
		echo '<br>';
		if( count($id) == 1 )	//only one element exists
		{
			$id_num = $id[0][USERTABLE_USERID];
			$_SESSION['userID'] = $id_num;
			header( 'Location: ./account.php' );
		}
		else
		{
			$time = gettimeofday();
			echo 'time-sec: '.$time['sec'].'<br>';
			$_SESSION['time'] = $time['sec'];
			echo 'session: '.$_SESSION['time'].'<br>';
			header( 'Location: ./index.php?failedLogin' );
		}
	}

?>