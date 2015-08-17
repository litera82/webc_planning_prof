<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Tahiry RANDRIAMBOLA <t.randriambola@gmail.com>
*/

class FoAjoutClientZone extends jZone 
{
 
    protected $_tplname		= 'client~FoAjoutClient.zone' ;
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
		jClasses::inc('client~clientsenvironnementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

 
		$iEvenementId 				= $this->getParam('iEvenementId',0);  
		$iClientId 					= $this->getParam('iClientId',0);  
		$oUser = jAuth::getUserSession();

		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::getById ($iUtilisateurId) ;
		$toProfesseur['toListes'] = array () ;
		$toModelMail = array () ;
		$oClientsEnvironnement = new StdClass () ;
		array_push ($toProfesseur['toListes'], $oUtilisateur);
		if (isset($oUtilisateur->utilisateur_bSuperviseur) && $oUtilisateur->utilisateur_bSuperviseur == UTILISATEUR_SUPERVISEUR){
			$toParamsUtilisateur['utilisateur_statut'] = 1;
			$toParamsUtilisateur['notinutilisateur'] = $iUtilisateurId;
			$toTmpProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur, 'utilisateur_zPrenom');
			//$toTmpProfesseur = utilisateursSrv::getUtilisateurBySuperviseurId($iUtilisateurId) ;
			foreach($toTmpProfesseur['toListes'] as $oProfesseur){
				array_push ($toProfesseur['toListes'], $oProfesseur);
			}
		}


		$_toParamsUtilisateur['utilisateur_statut'] = 1;
       	$toTmpUtilisateur			= utilisateursSrv::listCriteria($_toParamsUtilisateur);
		$bEdit 						= ($iClientId>0) ? true : false ;
        $oClient 					= ($iClientId>0) ? ClientSrv::getById($iClientId) : jDao::createRecord('commun~client') ;
		$toParamsSociete = array ();
		$toParamsSociete[0] = new stdClass();
		$toParamsSociete[0]->statut = 1;
       	$toTmpSociete				= societeSrv::listCriteria($toParamsSociete);
       	$toTmpPays					= paysSrv::chargerTous();
		$oNewUtilisateur			= $oUtilisateur ;

		if ($iUtilisateurId != $oClient->client_iUtilisateurCreateurId){
			$oNewUtilisateur = utilisateursSrv::getById ($oClient->client_iUtilisateurCreateurId) ;
		}

		$zContentHtmlEmail1 = '' ;
		$zContentHtmlEmail2 = '' ;
		$zContentHtmlEmail3 = '' ;

		if ($iClientId>0){
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
		}


		/********************************************************************************/
		if ($iClientId > 0){
			$oClientsEnvironnement = clientsenvironnementSrv::getByClientId ($iClientId);
		}
		/********************************************************************************/
		$toParams['bEdit'] 					= $bEdit ;
       	$toParams['iClientId'] 				= $iClientId ;
       	$toParams['oClient'] 				= $oClient ;
		$toParams['toSociete'] 				= $toTmpSociete['toListes'];
		$toParams['toPays'] 				= $toTmpPays;
		$toParams['iUtilisateurId']			= $iUtilisateurId;
		$toParams['toUtilisateur'] 			= $toTmpUtilisateur['toListes'];
		$toParams['toProfesseur'] 			= $toProfesseur['toListes'];
		$toParams['oUtilisateur'] 			= $oUtilisateur;
		$toParams['toModelMail'] 			= $toModelMail;
		$toParams['oClientsEnv'] 	= $oClientsEnvironnement;

		$this->_tpl->assign($toParams);
	}
}
?>