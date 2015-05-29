<?php

///Start session
	session_start();

///relocate user if he is allready logged in to dashboard.index.php
	if( isset( $_SESSION['userID'] ) ) {
		
		header( 'Location: dashboard.index.php' );
	}

///import needed classes and configurations
	require_once( 'classes/connection.class.php' );
	require_once( 'classes/user.class.php' );
	require_once( 'classes/html.class.php' );

	require_once( 'config/user_table.config.php' );
	
	// test for config
	if( !file_exists( 'config/db_data.config.php' ) ) {
		
		header( 'Location: installer.php' );
		exit;
	
	} else {
		
		require_once( 'config/db_data.config.php' );
	}


///define some meta informations about this page
	$title 				= 'Bitte melden Sie sich an!';
	$templateLink 		= 'templates/login.template.php';

///start page processing
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
		$html->addStylelink( 'styles/noticeBoxes.css' );
		$html->addStylelink( 'styles/index.css' );
		
		// generate content
		$notices = 
		'<div class="notice">
			<p>
				<strong>Hinweis:</strong><br>
				Dies ist kein öffentlicher Bereich. Ohne einen Account können Sie diese Seite nicht
				betreten!
			</p>
		</div>';
		
		// test for errors
		if( isset( $_GET ) ) {
			// failed to log in
			if( isset( $_GET['failedLogin'] ) ) {
				
				$notices .= 
				'<div class="notice error">
					<p>
						<strong>Die Anmeldung ist Fehlgeschlagen!</strong><br>
						Bitte überprüfen Sie Ihre Logindaten und 
						versuchen es noch einmal.
					</p>
				</div>';
			
			// user trys to enter to fast
			} elseif( isset( $_GET['toFast'] ) ) {
				
				$notices .= 
				'<div class="notice important">
					<p>
						<strong>Slow down!</strong><br>
						Bitte spame nicht. Warte einen Moment, bevor du das Formular wieder abschickst.
					</p>
				</div>';
			
			// user isnt logged in but trys to enter areas only for logged in users
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
		
		
		// most content is allready defined into the main template
		
		// set hooks
		$html->setHook( 'login_notices', $notices );
		$html->setHookAsTemplate( 'template_head', 'templates/head.template.php' );
		
		// creat file
		$html->createFile();

	// catch errors
	} catch( Exception $e ) {
		
		header( 'Location: error.php' );
	}

?>