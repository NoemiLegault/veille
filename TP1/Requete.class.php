<?php
/**
 * Class Requete
 * Gère les requêtes HTTP (Router)
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2017-02-08
 * @license MIT
 * @see http://www.lornajane.net/posts/2012/building-a-restful-php-server-understanding-the-request
 */

class Requete 
{
	public $verbe;
	public $parametres;
	public $url_element;
	private $_db;
	
  	public function __construct() {
    	// Connection mySQL
		$this->_db = MonSQL::getInstance();
		
		// Analyse de la requête 
		$this->verbe = $_SERVER['REQUEST_METHOD'];
	   	$_GET['url'] = trim($_GET['url'], '\/');
	   	//var_dump($_GET);
	   	$this->url_element = explode("/", $_GET['url']);
		
		// Traitement des paramètres 
       	$this->traitementParametre();
	}
	
	/**
	 * Décode les paramètres de la requête
	 * @access private
	 */
	private function traitementParametre() {
        $parametres = array();
        
		if(isset($_SERVER['QUERY_STRING']))
		{
			parse_str($_SERVER['QUERY_STRING'], $parametres);
			unset($parametres['url']);
		}
        
        $donnees_brut = file_get_contents("php://input");
		
		$content_type = false;
		if(isset($_SERVER['CONTENT_TYPE']))
		{
			$content_type = $_SERVER['CONTENT_TYPE'];
		}
		
		$donnees = json_decode($donnees_brut);
		if($donnees)
		{
			foreach ($donnees as $nom => $valeur) {
				$parametres[$nom] = $this->aseptiserParametre($valeur);
			}
		}
		$this->parametres = $parametres;
				
    }
	
	/**
	 * Permet de nettoyer les valeurs passées en paramètre
	 * @param String $valeur Valeur à filtrer
	 * @return String La valeur filtrer.
	 * @access private
	 */
	private function aseptiserParametre($valeur)
	{
		$valeur = $this->_db->real_escape_string($valeur);
		$valeur = htmlspecialchars($valeur);
		return $valeur;
	}
	
	/**
	 * Génère le code d'erreur passé en paramètre
	 * @access public
	 */	
	public function erreur($code)
	{
		http_response_code($code);
		
	}
}
?>