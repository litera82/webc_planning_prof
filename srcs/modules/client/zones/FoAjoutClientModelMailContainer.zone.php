<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Tahiry RANDRIAMBOLA <t.randriambola@gmail.com>
*/

class FoAjoutClientModelMailContainerZone extends jZone 
{
 
    protected $_tplname		= 'client~FoAjoutClientModelMailContainer.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{

		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc ('typeEvenement~typeEvenementsSrv');
    	jClasses::inc ('client~clientSrv');
    	jClasses::inc ('client~societeSrv');
    	jClasses::inc ('client~paysSrv');
    	jClasses::inc ('client~modelMailSrv');

		$iClientId 					= $this->getParam('iClientId',0);  
		$iUtilisateurCreateurId 	= $this->getParam('iUtilisateurCreateurId',0);  
        $oClient 					= ($iClientId>0) ? ClientSrv::getById($iClientId) : jDao::createRecord('commun~client') ;
		$oNewUtilisateur			= utilisateursSrv::getById ($iUtilisateurCreateurId) ;

		$zUrlToIndexStagiaire = URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass ;

		$toModelMail = modelMailSrv::chargerByType (1) ;
		foreach ($toModelMail as $oModelMail){
			switch($oModelMail->modelmail_value){
				case 1:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,		$oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
				case 2:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
				case 3:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
			}
		}

		$this->_tpl->assign('bEdit', true);
		$this->_tpl->assign('toModelMail', $toModelMail);
	}
}
?>