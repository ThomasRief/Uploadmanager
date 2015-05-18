<?php

class connection {
	
	/**
	 * Das PDO Verbingungsobjekt
	 * @type PDO-Object.
	 */
	public $connection;
	
	/**
	 * Der zuletzt ausgeworfenen Fehler.
	 * @type string.
	 */
	public $lastError;
	
	
	/**
	 * Constructor. Erstellt ein PDO Verbindung zur Datenbank.
	 * @param string $dbuser Der Datenbank Nutzer.
	 * @param string $dbpass Das passende Passwort zum Nutzer.
	 * @param string $dbname (optional) Die Datenbank, mit der verbunden werden soll.
	 * 
	 * @return void, bei einem Fehler wird eine Exception geworfen.
	 */
	public function __construct( $dbuser, $dbpass, $dbname = NULL ) {
		
		try {
			
			$this->connection = new PDO( 'mysql:host=localhost;', $dbuser, $dbpass );
			if( $dbname != NULL ) {
				if( !self::connectToDatabase( $dbname ) ) {
					
					throw new Exception( 'Konnte keine Verbindung zur Datenbank erstellt werden!' );
				} 
			}
		
		} catch( PDOException $e ) {
			
			$this->lastError = $e;
			throw new Exception( 'Konnte keine Verbinung erstellt werden!' );
		}
	}
	
	
	/**
	 * Führt einen SQL-Befehl aus.
	 * @param string $sql der SQL-Befehl.
	 * 
	 * @return boolen FALSE bei einem Fehler, PDOStatement bei Erfolg.
	 */
	public function executeSQL( $sql ) {
		
		try {
			
			$stm = $this->connection->prepare( $sql );
			if( $stm->execute() ) {
				
				return $stm;
				
			} else {
				
				$this->lastError = $stm->errorInfo()[2];
				return FALSE;
			}
		
		} catch( PDOException $e ) {
			
			$this->lastError = $e;
			return FALSE;
		}
	}
	
	
	/**
	 * Führt ein gegebenen PDOStatement aus.
	 * @param PDOStatement $stm Der definierte Statement.
	 * 
	 * @return boolen TRUE bei Erfolg, FALSE bei einem Fehler.
	 */
	public function executeStatement( $stm ) {
		
		try {
			
			if( $stm->execute() ) {
				
				return $stm;
				
			} else {
				
				$this->lastError = $stm->errorInfo()[2];
				return FALSE;
			}
		
		} catch( PDOException $e ) {
			
			$this->lastError = $e;
			return FALSE;
		} 
	}
	
	
	/**
	 * Verbindet Klasse mit einer Datenbank.
	 * @param string $dbname Datenbankname.
	 * 
	 * @return boolen TRUE bei Erfolg, FALSE bei einem Fehler.
	 */
	public function connectToDatabase( $dbname ) {
		
		$sql = 'USE '.$dbname;
		if( self::executeSQL( $sql ) != FALSE ) {
			
			return TRUE;
		
		} else {
			
			return FALSE;
		} 
	}
	
	
	/**
	 * Nutze SQL-Select um Daten aus einer Tabelle auszulesen.
	 * @param string $table die Tabelle.
	 * @param string $columnToSelect (optional) der Datenwert, der ausgewählt werden soll. 
	 * @param boolen $useWhereStatement (optional) Soll die WHERE-Abfrage durchgeführt werden?
	 * @param string $whereCondition (optional) die WHERE Bedingung.
	 * @param string $whereValue (optional) der nötige Wert der Bedingung.
	 *  
	 * @return array der 'gefetchte' Wert bei Erfolg, boolen FALSE bei Misserfolg.
	 */
	public function select( $table, 
							$columnToSelect = '*', 
							$useWhereStatement = FALSE, 
							$whereCondition = NULL, 
							$whereValue = NULL ) {
		
		// create sql
		$sql = 'SELECT '.$columnToSelect.' FROM '.$table;
		if( $useWhereStatement ) {
			
			$sql .= ' WHERE '.$whereCondition.' = :whereValue;';
		} else {
			
			$sql .= ';';
		}
		
		// prepare
		$stm = $this->connection->prepare( $sql );
		if( $useWhereStatement ) {
			
			$stm->bindValue( ':whereValue', $whereValue );
		}
		
		// execute & fetch
		$result = self::executeStatement( $stm );
		if( $result != FALSE ) {
			if( !is_bool( $result ) ) {
			
				return $result->fetchAll();
			
			} else {
			
				return TRUE;
			}
		
		} else {
			
			return FALSE;
		} 
	}
 }
?>