<?php

$menuArray = 
array(
	'Startseite' => array(
		'links' => array(
			'Übersicht' => array(
				'href' => 'dashboard.index.php',
			 	'perm' => 'user',
			 	'id' => 'index' ) ) ),
	'Mein Account' => array(
		'links' => array (
			'Password ändern' => array(
			 	'href' => 'dashboard.edit.php',
			 	'perm' => 'user',
			 	'id' => 'edit' ) ) ),
	'Dateiuploads' => array(
		'links' => array (
			'Meine Uploads' => array(
			 	'href' => 'dashboard.overview.php',
			 	'perm' => 'user',
			 	'id' => 'overview' ),
			 'Datei Hochladen' => array(
			 	'href' => 'dashboard.upload.php',
			 	'perm' => 'user',
			 	'id' => 'upload' ) ) ),
	'Administration' => array(
		'links' => array (
			'Übersicht' => array(
			 	'href' => 'dashboard.admin.php',
			 	'perm' => 'admin',
			 	'id' => 'admin' ),
			 'Nutzerliste' => array(
			 	'href' => 'dashboard.userlist.php',
			 	'perm' => 'admin',
			 	'id' => 'userlist' ),
			 'Nutzer hinzufügen' => array(
			 	'href' => 'dashboard.addUser.php',
			 	'perm' => 'admin',
			 	'id' => 'addUser' ),
			 'Einstellungen' => array(
			 	'href' => 'dashboard.settings.php',
			 	'perm' => 'admin',
			 	'id' => 'settings' ) ) ) );

?>