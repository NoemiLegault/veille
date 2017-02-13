<?php
/**
 * Class BiereControleur
 * Controleur de la ressource Biere
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2017-02-08
 * @license MIT
 */

  
class BiereControleur 
{
	/**
	 * Méthode qui gère les action en GET
	 * @param Requete $oReq
	 * @return Mixed Données retournées
	 */
	public function getAction(Requete $oReq)
	{
		$res = array();
		//var_dump($oReq->url_element);
		if(isset($oReq->url_element[1]) && is_numeric($oReq->url_element[1]))//Route : /biere/:id/..
		{
			$id_biere = (int) $oReq->url_element[1];
			if(isset($oReq->url_element[2])) //Route : biere/:id/{note, commentaire}
			{
				switch ($oReq->url_element[2]) {
					case 'note':
							$res = $this->getNote($id_biere);
						break;
					case 'commentaire':
							$res = $this->getCommentaire($id_biere);
						break;
					default:
						$oReq->erreur(400);
						break;
				}
			}
			else //Route : biere/:id
			{
				
				$res = $this->getBiere($id_biere);
			}
		}
		else
		{
			$res = $this->getListeBiere();	
		}
		
		
        return $res;	
	}
	
	/**
	 * Méthode qui gère les action en POST
	 * @param Requete $oReq
	 * @return Mixed Données retournées
	 */
	public function postAction(Requete $oReq)	// Modification
	{
		// autentifacation ///////////////////////////////////
		if(!$this->valideAuthentification())
		{
			$oReq->erreur(401);
			exit;
		}
		
		$res = array();
		if(isset($oReq->url_element[1]) && is_numeric($oReq->url_element[1]))
		{
			$id_biere = (int) $oReq->url_element[1];
			if($oReq->parametres != [])
			{
				$param = $oReq->parametres;
				$Biere = new Biere;
				$res = $Biere->modifierBiere($id_biere, $param);
				
			}
			else
			{
				$oReq->erreur(401);
			}
		}
		else
		{
			$oReq->erreur(401);
		}
		
		return $res;
	}
	
	/**
	 * Méthode qui gère les action en PUT
	 * @param Requete $oReq
	 * @return Mixed Données retournées
	 */
	public function putAction(Requete $oReq)		//ajout ou modification
	{
		if(!$this->valideAuthentification())
		{
			$oReq->erreur(401);
			exit;
		}
		
		//var_dump($oReq->url_element);
		//var_dump($oReq->parametres);
		/*
		$oBiere = new Biere;
		$oBiere->ajouterBiere(($oReq->parametres));
		*/
		$res = array();
		
		if(isset($oReq->url_element[2]) && ($oReq->url_element[1] && is_numeric($oReq->url_element[1])))
		{
			$id_biere = (int) $oReq->url_element[1];
			switch($oReq->url_element[2])
			{
				case 'commentaire':
					$oUsager = new Usager;
					$id_usager = $oUsager->ajouterUsager($data['courriel']);
					$oCommentaire = new Commentaire;
					$res = $oCommentaire->ajouterCommentaire($id_usager, $id_biere, ($oReq->parametres['commentaire']));
					return $res; 
					break;
					
				
				case 'note':
					$res = $this->putNote($id_biere,($oReq->parametres));
					break;
					
					
				default: $oReq->erreur(401);
			}
		}
		else
		{
			$oBiere = new Biere;
			$oBiere->ajouterBiere(($oReq->parametres));
			$res = $oBiere;
		}
		/*
		var_dum($oReq->parametres);
		res = $oReq->parametres;
		*/
		return $res;
	}
	
	/**
	 * Méthode qui gère les action en DELETE
	 * @param Requete $oReq
	 * @return Mixed Données retournées
	 */
	public function deleteAction(Requete $oReq)
	{
		if(!$this->valideAuthentification())
		{
			$oReq->erreur(401);
			exit;
		}
		
		$res = array();
		
		if(isset($oReq->url_element[1]) && is_numeric($oReq->url_element[1]))
		{
			$id_biere = (int) $oReq->url_element[1];
			$oBiere = new Biere();
			$res = $oBiere->effacerBiere($id_biere);
		}
		else
		{
			$oReq->erreur(401);
		}
		
		return $res;
		
	}
	
	
	
	/**
	 * Retourne les informations de la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @return Array Les informations de la bière
	 * @access private
	 */	
	private function getBiere($id_biere)
	{
		$res = Array();
		$oBiere = new Biere();
		$res = $oBiere->getBiere($id_biere);
		return $res; 
	}
	
	/**
	 * Retourne les informations des bières de la db	 
	 * @return Array Les informations sur toutes les bières
	 * @access private
	 */	
	private function getListeBiere()
	{
		$res = Array();
		$oBiere = new Biere();
		$res = $oBiere->getListe();
		
		return $res; 
	}
	
	/**
	 * Retourne les commentaires de la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @return Array Les commentaires de la bière
	 * @access private
	 */	
	private function getCommentaire($id_biere)
	{
		$res = Array();
		$oCommentaire = new Commentaire();
		$res = $oCommentaire->getListe($id_biere);
		return $res; 
	}

	/**
	 * Retourne la note moyenne et le nombre de note de la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @return Array La note de la bière
	 * @access private
	 */	
	private function getNote($id_biere)
	{
		
		$res = Array();
		$oNote = new Note();
		$res = $oNote->getMoyenne($id_biere);
		return $res; 
	}
	
	private function putNote($id_biere, $data)
	{
		$oUsager = new Usager;
		$id_usager = $oUsager->ajouterUsager($data['courriel']);
		$oNote = new Note;
		$res = $oNote->ajouterNote($id_usager, $id_biere, $data['note']);
		return $res; 
	}
	
	/**
	 * Valide les données d'authentification du service web
	 * @return Boolean Si l'authentification est valide ou non
	 * @access private
	 */	
	private function valideAuthentification()
    {
      	$access = false;
		$headers = apache_request_headers();
		
		if(isset($headers['Authorization']))
		{
			if(isset($_SERVER['PHP_AUTH_PW']) && isset($_SERVER['PHP_AUTH_USER']))
			{
				if($_SERVER['PHP_AUTH_PW'] == 'biero' && $_SERVER['PHP_AUTH_USER'] == 'biero')
				{
					$access = true;
				}
			}
		}
      	return $access;
    }
		
}
