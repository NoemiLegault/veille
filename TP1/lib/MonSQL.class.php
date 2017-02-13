<?php
/**
 * Class MonSQL
 * Classe qui génère ma connection à MySQL à travers un singleton
 *
 *
 * @author Jonathan Martel
 * @version 1.0
 * @see : http://www.apprendre-php.com/tutoriels/tutoriel-45-singleton-instance-unique-d-une-classe.html
 *
 *
 */
class MonSQL extends mysqli{
	/**
	 * @var $_instance
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 * @private
	 */
	private function __construct($host, $user, $password, $database) 
	{
		parent::__construct($host, $user, $password, $database);

		if ($this-> connect_errno) {
			echo "Echec lors de la connexion à MySQL : (" . $this -> connect_errno . ") " . $this-> connect_error;
		}
		else {
			$this->set_charset("utf8");	
		}
		
	}

	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return Singleton
	 */
	public static function getInstance() {

		if (is_null(self::$_instance)) {
			self::$_instance = new self(HOST, USER, PASSWORD, DATABASE);
		}

		return self::$_instance;
	}

}
?>