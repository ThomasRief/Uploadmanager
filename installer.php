<?php
	
/// start session
	session_start();
	
/// set defaults
	$mainTitle = 'Datenbankkonfiguration';
	$subTitle = '';
	$error = '';
	$content = '';
	$self = $_SERVER['PHP_SELF'];
	
/// test for file
	$configFileExists = file_exists( 'config/db_data.config.php' );
	
/// get stuff to do
	if( isset( $_GET['do'] ) ) {
		
		$do = $_GET['do'];
	
	} else {
		
		$do = 'default';
	}
	
/// create interface
	#if( isset( $_SESSION['userID'] ) ) {
		
		switch( $do ) {
			
			// default:
			case 'default':
				// content informations
				$subTitle = 'Willkommen';
				$content = 
				'<h3>Willkommen</h3>
				<p>
					Mithilfe dieses Programmes können Sie Fehler an
					der Datenbankverbindung oder der Datenbank selber
					beheben. Drücken Sie einfach auf "Weiter", um fortzufahren.
				</p>
				<a href="'.$self.'?do=check">Weiter</a>';
			break;
			
			// case to check for errors
			case 'check':
				// content information
				$subTitle = 'Systemcheck:';
				$content = 
				'<h3>Es konnten folgende Fehler gefunden werden:</h3>
				<div class="dir">';
				
				// check for config file:
				if( !$configFileExists ) {
					
					$content .= 
					'<div class="file folder redBorder">
						<p>
							Es fehlt die db_data.config.php-Datei!
							(<a href="'.$self.'?do=config">Beheben</a>)
						</p>
					</div>';
				
				} else {
					
					require_once( 'classes/connection.class.php' );
					require_once( 'config/db_data.config.php' );
					
					try {
						
						$conn = new connection( DB_USER, DB_PASS );
						
						$sql = 'USE '.DB_NAME;
						if( $conn->executeSQL( $sql ) == FALSE ) {
							
							$content .= 
							'<div class="file folder redBorder">
								<p>
									Datenbank "'.DB_NAME.'" konnte nicht gefunden werden und muss erstellt werden! 
									(<a href="'.$_SERVER['PHP_SELF'].'?do=db">beheben</a>)
								</p>
							</div>';
						
						} else {
				
							$content .= 
							'<div class="file">
								<p>
									Es wurden keine Probleme gefunden!
								</p>
							</div>';
						}
					
					} catch( PDOException $e ) {
						
						$error = 
						'<div class="error">
							<p>
								Es ist ein Fehler mit der Datenbank aufgetreten!
							</p>
						</div>';
						
					}
						
				}
				
				$content .= 
				'</div><br />
				<a href="index.php">Abbrechen</a>';
			break;
			
			// case create config file
			case 'config':
				// content informations
				$subTitle = 'Verbindung erstellen:';
				$content = 
				'<h3>Bitte geben Sie Ihre Zugangsdaten an:</h3>
				<p>
					Sie benötigen einen funktionierenden MySQL-Server!
				</p>
				<form action="'.$self.'?do=config" method="POST">
					<input type="text" name="name" value="root" placeholder="Nutzername" />
					<input type="password" name="password" placeholder="Passwort" />
					
					<input type="submit" name="submit" value="Verbinden" />
				</form>';
				
				if( isset( $_POST['submit'] ) ) {
					
					require_once( 'classes/connection.class.php' );
					try {
						
						$name = $_POST['name'];
						$pass = $_POST['password'];
						$conn = new connection( $name, $pass );
					
						$confile = 
						'<?php '.PHP_EOL.
						'	define( "DB_USER", "'.$name.'" );'.PHP_EOL.
						'	define( "DB_PASS", "'.$pass.'" );'.PHP_EOL.
						'	define( "DB_NAME", "uploadmanager" );'.PHP_EOL.
						'?>';
					
						$file = fopen( 'config/db_data.config.php', 'a' );
						fwrite( $file, $confile );
						
						header( 'Location: '.$_SERVER['PHP_SELF'].'?do=check' );
					
					} catch( Exception $e ) {
						
						$error = 
						'<div class="error">
							<p>
								Die Datenbankverbindung konnte nicht erstellt werden!
							</p>
						</div>';
					}
				}
				
			break;
			
			// case create database
			case 'db':
				$subTitle = 'Datenbank erstellen:';
				$content =
				'<h3>Datenbank und Tabellen anlegen</h3>
				<p>
					Im folgenden Schritt werden die nötigen Datenbanken und die dazu entsprechenden Tabellen erstellt:
				</p>
				<div class="dir">
					<div class="folder">
						<p>Datenbank: uploadmanager</p>
						<div class="folder">
							<p>Tabelle: users</p>
							<div class="file"><p>Datenwert: user</p></div>
							<div class="file"><p>Datenwert: admin</p></div>
						</div>
						<div class="file"><p>Tabelle: downloadlog</p></div>
					</div>
				</div>
				<p>
					Klicken Sie auf "Weiter" um die Datenbank zu installieren.
				</p>
				<a href="'.$self.'?do=db&checked">Weiter</a>';
				
				if( isset( $_GET['checked'] ) ) {
		
					require_once( 'classes/connection.class.php' );
					require_once( 'config/db_data.config.php' );
					$conn = new connection( DB_USER, DB_PASS );
		
					$sqlArray = array( 
									0 => 'CREATE DATABASE IF NOT EXISTS uploadmanager;',
									1 => 'CREATE TABLE IF NOT EXISTS users 
										  (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
										   name VARCHAR(16),
										   password VARCHAR(64),
										   permissions VARCHAR(16)
										  );',
									2 => 'CREATE TABLE IF NOT EXISTS downloadlog
										  (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
										   uploadUser VARCHAR(16),
										   downloadLink VARCHAR(32),
										   date TIMESTAMP
										  );',
									3 => 'INSERT INTO users 
										  (name, password, permissions ) 
										  VALUES ("user", "'.hash( 'sha256', 'user' ).'", "user");',
									4 => 'INSERT INTO users 
										  (name, password, permissions ) 
										  VALUES ("admin", "'.hash( 'sha256', 'admin' ).'", "admin");' );
						
					$errorOccurred = FALSE;			  
					foreach( $sqlArray as $sql ) {
						
						$conn->connectToDatabase( DB_NAME );
						
						if( $conn->executeSQL( $sql ) == FALSE ) {
							
							$errorOccurred = TRUE;
							$error = 
							'<div class="error">
								<p>
									Es konnten nicht alle Datenwerte installiert werden!
								</p>
							</div>';
						} 
					}
					
					if( !$errorOccurred ) {
						
						header( 'Location: '.$_SERVER['PHP_SELF'].'?do=check' );
					}
				}
			
			break;
		}
	
	/*
	} else {
		
		$error = 
		'<div class="error">
			<p>
				<strong>Fehler:</strong><br>
				Sie haben nicht das Recht diese Seite zu öffnen! Bitte melden Sie sich 
				als Administrator an!
			</p>
		</div>';
		$content = '<br><a href="index.php">Zurück zur Startseite</a>';
	} 
	*/
	
/// return the HTML
echo(
'<!DOCTYPE html>
	<html>
		<head>
			<title> Datenbankkonfiguration </title>
			<meta charset="UTF-8" />
			
			<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans" />
			<link rel="stylesheet" type="text/css" href="styles/installer.css" />
			
		</head>
		<body>
			<div class="wrapper">
				
				<header>
					
					<h1>'.$mainTitle.'</h1>
					<h2>'.$subTitle.'</h2>
					
				</header>
				<div class="content">
					
					'.$error.'					
					'.$content.'
					
				</div>
				
			</div>
		</body>
	</html>')

?>
