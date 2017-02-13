<?php
/**
 * Class Arrondissement
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2016-03-31
 * @license MIT
 * 
 */
class Biere extends Modele {	
	
		
	/**
	 * Retourne la liste des bieres
	 * @access public
	 * @return Array
	 */
	public function getListe() 
	{
		$res = Array();
		$query = "select  t1.id_biere,  description, nom, brasserie, image, date_ajout, date_modif, AVG(IFNULL(note, 0)) as moyenne, count(id_note) as nombre from biere t1 
left join note t2 ON t1.id_biere = t2.id_biere GROUP by t1.id_biere";
		if($mrResultat = $this->_db->query($query))
		{
			while($biere = $mrResultat->fetch_assoc())
			{
				foreach($biere as $cle=> $valeur)
				{
					$biere[$cle] = utf8_encode($valeur);
				}
				$res[] = $biere;
			}
		}
		return $res;
	}
	
	/**
	 * Ajoute une biere
	 * @param Array $data Les données d'une bière 
	 * @access public
	 * @return int id de la biere
	 */
	public function ajouterBiere($data) 
	{
		
		
		if(extract($data) > 0)
		{
			$image = $image || "";	
			$query = "INSERT INTO biere (`nom`, `brasserie`, `description`, `image`,`actif`) 
			VALUES ('".$nom. "','". $brasserie. "','". $description. "','".$image."','1')";
			$resQuery = $this->_db->query($query);
			
		}
				
		return ($this->_db->insert_id ? $this->_db->insert_id : 0);
	}
	
	/**
	 * Effacer une biere
	 * @access public
	 * @param Array $id_biere Identifiant de la bière  
	 * @return Boolean
	 */
	public function effacerBiere($id_biere) 
	{
		$resQuery = false;
		if(isset($id_biere))
		{
			$id_biere = $this->_db->real_escape_string($id_biere);
			$query = "DELETE from biere where id_biere = ". $id_biere;
			$resQuery = $this->_db->query($query);	
		}
		return $resQuery;
	}
	
	/**
	 * Récupère  une biere
	 * @access public
	 * @param int $id Identifiant de la bière
	 * @return Array
	 */
	public function getBiere($id) 
	{
		$res = Array();
		if($mrResultat = $this->_db->query("select * from biere where id_biere=". $id))
		{
			$biere = $mrResultat->fetch_assoc();
		}
		return $biere;
	}
	
	/**
	 * Modifier une biere
	 * @access public
	 * @param int $id Identifiant de la bière
	 * @param Array $param Paramètres et valeur à modifier 
	 * @return int id de la bière ou 0 en cas d'échec
	 */
	public function modifierBiere($id, $param)	
	{
		$aSet = Array();
		$resQuery = false;
		foreach ($param as $cle => $valeur) {
			$aSet[] = ($cle . "= '".$valeur. "'");
		}
		if(count($aSet) > 0)
		{
			$query = "Update biere SET ";
			$query .= join(", ", $aSet);
			
			$query .= (", date_modif = now() WHERE id_biere = ". $id); 
			$resQuery = $this->_db->query($query);
			
		}
		//echo $query;
		return ($resQuery ? $id : 0);
	}
	
}


