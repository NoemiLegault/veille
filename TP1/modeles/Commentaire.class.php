<?php
/**
 * Classe Commentaire
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2016-03-31
 * @license MIT
 * 
 */
class Commentaire extends Modele {	
	
		
	/**
	 * Retourne les commentaires sur une biere
	 * @access public
	 * @param int $id_biere Identifiant de la bière
	 * @return Array
	 */
	public function getListe($id_biere) 
	{
		$res = Array();
		$query = "select * from commentaire where id_biere=".$id_biere;
		if($mrResultat = $this->_db->query($query))
		{
			while($commentaire = $mrResultat->fetch_assoc())
			{
				$res[] = $commentaire;
			}
		}
		return $res;
	}
	
	/**
	 * Ajoute un commentaire sur une biere
	 * @access public
	 * @param int $id_biere Identifiant de l'usager
	 * @param int $id_biere Identifiant de la bière
	 * @param String $commentaire Le commentaire
	 * @return int Identifiant du commentaire
	 */
	public function ajouterCommentaire($id_usager, $id_biere, $commentaire) 
	{
		
		
		$query = "INSERT INTO commentaire (commentaire, id_biere, id_usager ) 
		VALUES ('".$commentaire. "', ". $id_biere. ",".$id_usager.")";
		$resQuery = $this->_db->query($query);
		
		
		return ($this->_db->insert_id ? $this->_db->insert_id : 0);
	}
	
}


