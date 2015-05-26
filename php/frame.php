<?php

/**
 * Hauptdatei des uploadmanager-Frames. Einfach am Anfang des Dokuments einbinden.
 * 
 * @author Emanuel Bitkov, 26.05.2015 
 */
 
 try {
 	
 	/// start session
 		session_start();
 	
 	/// load classes
	 	require_once( 'classes/connection.class.php' );
	 	require_once( 'classes/user.class.php' );
	 	require_once( 'classes/html.class.php' );
 	
 	/// load config
 		require_once( 'config/menu.config.php' );
 		require_once( 'config/user_table.config.php' );
 		
 		// test for connection 
 		if( file_exists( 'config/db_data.config.php' ) ) {
			
			require_once( 'config/db_data.config.php' );
		
		} else {
			
			header( 'Location: installer.php' );
		}
 	
 	/// create classes 
 		$conn = new connection( DB_USER, DB_PASS, DB_NAME );
 		$user = new user();
 		$html = new html();
 		
 	/// load user
 		if( isset( $_SESSION['userID'] ) ) {
			if( !$user->loadUser( $_SESSION['userID'] ) ) {
				
				throw Exception( 'Nutzer konnte nicht geladen werden!' );
			}
		
		} else {
			
			header( 'Location: index.php' );
		}
 	
 } catch( ErrorException $e ) {
 	
 	/// Leite bei einem Fehler um
 		header( 'Location: error.php' );
 }

?>