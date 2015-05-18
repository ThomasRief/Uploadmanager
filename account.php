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
$title 				= 'Nutzerkontrollzentrum';
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
	$content =
	'<p>
		Willkommen im Nutzerkontrollzentrum! Hier finden Sie alle Werkzeuge, um Ihr Profil zu bearbeiten,
		oder Daten hochzuladen.
	</p>';
	
	// set hook
	$html->setHook( 'menu_username', $user->user_name );
	$html->setHook( 'account_contentTitle', 'Willkommen!' );
	$html->setHook( 'account_content', $content );
	
	// menu
	$menu = 
	array(
		'userPerm' => $user->user_permGroup,
		'links' => array(
			'Mein Account' => array(
				'href' => 'accountEdit.php',
				'perm' => 'user',
				'sub' => array() ),
			'Upload' => array(
				'href' => 'upload.php',
				'perm' => 'user',
				'sub' => array() ),
			'Administration' => array(
				'href' => 'admin.php',
				'perm' => 'admin',
				'sub' => array() ) ) );
	
	// set templates
	$html->setHookAsTemplate( 'account_menu', 'templates/menu.template.php', $menu );
	
	// creat file
	$html->createFile();

// catch errors
} catch( Exception $e ) {
	
	header( 'Location: error.php' );
}

?>