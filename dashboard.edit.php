<?php

///Start session and test if user is logged in
	session_start();
	if( !isset( $_SESSION['userID'] ) ) {
		
		header( 'Location: index.php?pleaseLogin' );
	}

///import needed classes and configuration
	require_once( 'classes/connection.class.php' );
	require_once( 'classes/user.class.php' );
	require_once( 'classes/html.class.php' );

	require_once( 'config/db_data.config.php' );
	require_once( 'config/user_table.config.php' );
	require_once( 'config/menu.config.php' );

///define some meta values about this document
	$title 				= 'Password Ändern |Uploadmanager';
	$templateLink 		= 'templates/dashboard.template.php';

///start page processing
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
		$html->addStylelink( 'styles/noticeBoxes.css' );
		$html->addStylelink( 'styles/dashboard.css' );
		$html->addStylelink( 'styles/dashboardHeader.css' );
		
		//generate content
		$content =
		'<div class="notice">
			<p>
				Leider ist dieser Bereich noch nicht ausgebaut!
			</p>
		</div>';
		
		// set hooks
		$html->setHook( 'menu_username', $user->user_name );
		$html->setHook( 'account_contentTitle', 'Passwort ändern:' );
		$html->setHook( 'account_content', $content );
		
		// menu
		$menu = 
		array(
			'userPerm' => $user->user_permGroup,
			'selectedPage' => 'edit',
			'links' => $menuArray );
		
		// set templates
		$html->setHookAsTemplate( 'account_menu', 'templates/menu.template.php', $menu );
		$html->setHookAsTemplate( 'template_head', 'templates/head.template.php' );
		
		// creat file
		$html->createFile();

	// catch errors
	} catch( Exception $e ) {
		
		header( 'Location: error.php' );
	}

?>