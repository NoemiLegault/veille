<?php
/**
 * Classe Note
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2016-03-31
 * @license MIT
 * 
 */
class Note extends Modele {	
	
		
	/**
	 * Retourne la note moyenne d'une biere
	 * @access public
	 * @param int $id_biere Identifiant de la bière
	 * @return Array Note et nombre de note
	 */
	public function getMoyenne($id_biere) 
	{
		$note = 0;
		$query = "select id_biere, avg(note) as moyenne from note where id_biere=".$id_biere;
		if($mrResultat = $this->_db->query($query))
		{
			$note = $mrResultat->fetch_assoc();
			$note = $note['moyenne'];
		}
		return $note;
	}
	
	
	/**
	 * Retourne le nombre de note d'une biere
	 * @access public
	 * @param int $id_biere Identifiant de la bière
	 * @return int
	 */
	public function getNombre($id_biere) 
	{
		$nombre = 0;
		$query = "select id_biere, count(*) as nombre from note where id_biere=".$id_biere;
		if($mrResultat = $this->_db->query($query))
		{
			$nombre = $mrResultat->fetch_assoc();
			$nombre = $nombre['nombre'];
		}
		return $nombre;
	}
	
	/**
	 * Ajoute une note sur une biere ou modifie la note si elle existe déjà
	 * @access public
	 * @param int $id_biere Identifiant de l'usager
	 * @param int $id_biere Identifiant de la bière
	 * @param int $note La note 
	 * @return Array
	 */
	public function ajouterNote($id_usager, $id_biere, $note) 
	{
		$ancienneNote = $this->getNoteParUsagerEtBiere($id_biere, $id_usager);
		if($ancienneNote)
		{
			$query = "UPDATE note SET note=". $note." where id_biere=".$id_biere." AND id_usager =".$id_usager;
			$id_note = $ancienneNote['id_note'];
		}
		else
		{
			$query = "INSERT INTO note (note, id_biere, id_usager ) 
			VALUES ('".$note. "', ". $id_biere. ",".$id_usager.")";	
			$id_note = ($this->_db->insert_id ? $this->_db->insert_id : 0);
		}
		
		$resQuery = $this->_db->query($query);
		
		return $id_note;
	}
	
	/**
	 * Retourne la note attribué par un usager sur une biere
	 * @access public
	 * @param int $id_biere Identifiant de l'usager
	 * @param int $id_biere Identifiant de la bière
	 * @return int La note existante ou 0
	 */
	public function getNoteParUsagerEtBiere($id_biere, $id_usager) 
	{
		$note = 0;
		
		$query = "select * from note where id_biere=".$id_biere." AND id_usager =".$id_usager;
		
		if($mrResultat = $this->_db->query($query))
		{
			$note = $mrResultat->fetch_assoc();
		}
		return $note;
	}
	
}

