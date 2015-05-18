<?php

session_start();
if( isset( $_GET['deleteInstallation'] ) ) {
	
	function rrmdir($dir) { 
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
	       } 
	     } 
	     reset($objects); 
	     rmdir($dir); 
	   } 
	 }
	 
	rrmdir( 'installer' );
	unlink( 'installer.php' );
}

if( isset( $_SESSION['userID'] ) ) {
	
	header( 'Location: account.php' );
}

// important stuff!!!
require_once( 'classes/connection.class.php' );
require_once( 'classes/user.class.php' );
require_once( 'classes/html.class.php' );

require_once( 'config/db_data.config.php' );
require_once( 'config/user_table.config.php' );

// some document information
$title 				= 'Bitte melden Sie sich an!';
$templateLink 		= 'templates/login.template.php';

// really important to catch Exceptions
try {
	
	// set classes
	$connection = new connection( DB_USER, DB_PASS, 'uploadmanager' );
	$html = new html();
	
	// set some document information
	$html->setTemplate( $templateLink );
	$html->title = $title;
	
	// bind styles
	$html->addStylelink( 'http://fonts.googleapis.com/css?family=Open+Sans' );
	$html->addStylelink( 'styles/main.css' );
	$html->addStylelink( 'styles/index.css' );
	
	// some other stuff
	$notices = 
	'<div class="notice important">
		<p>
			<strong>Hinweis:</strong><br>
			Dies ist kein öffentlicher Bereich. Ohne einen Account können Sie diese Seite nicht
			betreten!
		</p>
	</div>';
	
	if( isset( $_GET ) ) {
		if( isset( $_GET['failedLogin'] ) ) {
			
			$notices .= 
			'<div class="notice error">
				<p>
					<strong>Die Anmeldung ist Fehlgeschlagen!</strong><br>
					Bitte überprüfen Sie Ihre Logindaten und 
					versuchen es noch einmal.
				</p>
			</div>';
		
		} elseif( isset( $_GET['toFast'] ) ) {
			
			$notices .= 
			'<div class="notice important">
				<p>
					<strong>Slow down!</strong><br>
					Bitte spame nicht. Warte einen Moment, bevor du das Formular wieder abschickst.
				</p>
			</div>';
		
		} elseif( isset( $_GET['pleaseLogin'] ) ) {
			
			$notices .= 
			'<div class="notice error">
				<p>
					<strong>Wichtig!</strong><br>
					Bitte melden Sie sich an! Diese Seite ist nur mit einem gültigen Login
					verfügbar.
				</p>
			</div>';
		}
	}
	
	// set a custom hook
	$html->setHook( 'login_notices', $notices );
	
	// creat file
	$html->createFile();

// catch errors
} catch( Exception $e ) {
	
	header( 'Location: error.php' );
}

?>