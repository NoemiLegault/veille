<?php
/**
 * Class Usager
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2016-03-31
 * @license MIT
 * 
 */
class Usager extends Modele {	
	
		
	/**
	 * Retourne la liste des usager
	 * @access public
	 * @return Array
	 */
	public function getListe() 
	{
		$res = Array();
		if($mrResultat = $this->_db->query("select * from usager"))
		{
			while($usager = $mrResultat->fetch_assoc())
			{
				$res[] = $usager;
			}
		}
		return $res;
	}
	
	/**
	 * Ajoute un usager
	 * @access public
	 * @param String $courriel Courriel de l'usager
	 * @return int Identifiant de l'usager
	 */
	public function ajouterUsager($courriel) 
	{
		$id_usager = 0;
		$usager = $this->getUsagerParCourriel($courriel);
		if(!$usager)
		{
			$query = "INSERT INTO usager (courriel) 
			VALUES ('".$courriel. "')";
			$resQuery = $this->_db->query($query);
			$id_usager = ($this->_db->insert_id ? $this->_db->insert_id : 0);		
		}
		else {
			$id_usager = $usager['id_usager']; 
		}
		
		return $id_usager;
	}
	
	
	
	/**
	 * Récupère un usager par id
	 * @access public
	 * @param int $id Identifiant de l'usager
	 * @return Array
	 */
	public function getUsagerParId($id) 
	{
		$res = Array();
		if($mrResultat = $this->_db->query("select * from usager where id_usager=". $id))
		{
			$usager = $mrResultat->fetch_assoc();
		}
		return $usager;
	}
	
	/**
	 * Récupère un usager par Courriel
	 * @access public
	 * @param String $courriel Courriel de l'usager
	 * @return Array
	 */
	public function getUsagerParCourriel($courriel) 
	{
		$res = Array();
		
		if($mrResultat = $this->_db->query("select * from usager where courriel='". $courriel."'"))
		{
			$usager = $mrResultat->fetch_assoc();
		}
		return $usager;
	}
	
	
}
