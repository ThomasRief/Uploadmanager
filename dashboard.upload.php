<?php

session_start();
if( !isset( $_SESSION['userID'] ) ) {
	
	header( 'Location: index.php?pleaseLogin' );
}

// important stuff!!!
require_once( 'classes/connection.class.php' );
require_once( 'classes/user.class.php' );
require_once( 'classes/html.class.php' );

require_once( 'config/db_data.config.php' );
require_once( 'config/user_table.config.php' );

// some document information
$title 				= 'Datei Upload';
$templateLink 		= 'templates/account.template.php';

// really important to catch Exceptions
try {
	
	// set classes
	$connection = new connection( DB_USER, DB_PASS, 'uploadmanager' );
	$html = new html();
	$user = new user();
	
	// load user
	if( !$user->loadUser( $_SESSION['userID'] ) ) {
		
		header( 'Location: error.php' );
	}
	
	// set some document information
	$html->setTemplate( $templateLink );
	$html->title = $title;
	
	// bind styles
	$html->addStylelink( 'http://fonts.googleapis.com/css?family=Open+Sans' );
	$html->addStylelink( 'styles/main.css' );
	$html->addStylelink( 'styles/upload.css' );
	
	// define content
	$content =  ' <h2>Neuer Upload</h2>                       
                  <p>Waehle die Datei, die hochgeladen werden soll</p>
                  <form action="uploadedfiles.php" method="post" enctype="multipart/form-data">
                    <input name="userfile" type="file" /><br>
                    <input type="submit" value="Hochladen">
                  </form>';
	
	
	// set hook
	$html->setHook( 'menu_username', $user->user_name );
	$html->setHook( 'account_contentTitle', 'Willkommen!' );
	$html->setHook( 'account_content', $content );
	
	// set templates
	$html->setHookAsTemplate( 'account_menu', 'templates/menu.template.php' );
	
	// creat file
	$html->createFile();

// catch errors
} catch( Exception $e ) {
	
	header( 'Location: error.php' );
}

?>