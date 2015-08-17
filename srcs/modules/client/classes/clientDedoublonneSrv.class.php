<?php
/**
* @package		webcalendar
* @subpackage	client
* @version		1
* @author		IMCOS
*/

/**
* Fonctions utilitaires pour la gestion des flux clients
*
* @package		webcalendr
* @subpackage	client
* @autor	tahiry
*/
@ini_set ("memory_limit", -1) ;
@ini_set ("max_execution_time", 100000); 
class clientDedoublonneSrv {
	static function saveClientDepuisBddLogevent() {
		$zSql = "SELECT clients.client_iRefIndividu FROM clients GROUP BY clients.client_iRefIndividu ORDER BY clients.client_iRefIndividu ASC" ;	
		$oCnx = jDb::getConnection();
		$oRs  = $oCnx->query ($zSql);
		$toRecord = $oRs->fetchAll ();
		foreach($toRecord as $oRecord){
			if (($oRecord->client_iRefIndividu != "" || !is_null($oRecord->client_iRefIndividu)) && intval($oRecord->client_iRefIndividu)>0){
				$zSql = "SELECT * FROM clients WHERE clients.client_iRefIndividu = " . $oRecord->client_iRefIndividu ;
				$oCnx = jDb::getConnection();
				$oRs  = $oCnx->query ($zSql);
				$toClient = $oRs->fetchAll ();
				if (sizeof($toClient) > 1){
					$iNumIndividuMax = 0 ;	
					foreach($toClient as $oClient){
						if ($oClient->client_iNumIndividu > $iNumIndividuMax){
							$iNumIndividuMax = $oClient->client_iNumIndividu;
						}
						if ($iNumIndividuMax == $oClient->client_iNumIndividu){
							$oNewClient = new StdClass ();
							$oNewClient = $oClient ;
						}
					} // FIN foreach($toClient as $oClient)

					$zClientIdToTreate = ""; 
					for ($i=0; $i<sizeof($toClient); $i++){
						if ($toClient[$i]->client_id != $oNewClient->client_id){
							if ($zClientIdToTreate==""){
								$zClientIdToTreate .= $toClient[$i]->client_id; 
							}else{
								$zClientIdToTreate .= ",".$toClient[$i]->client_id; 
							}

							if ($oNewClient->client_iSociete == "" || is_null($oNewClient->client_iSociete))
								$oNewClient->client_iSociete = $toClient[$i]->client_iSociete;

							if ($oNewClient->client_iCivilite == "" || is_null($oNewClient->client_iCivilite))
								$oNewClient->client_iCivilite = $toClient[$i]->client_iCivilite;
							
							if ($oNewClient->client_iUtilisateurCreateurId == "" || is_null($oNewClient->client_iUtilisateurCreateurId))
								$oNewClient->client_iUtilisateurCreateurId = $toClient[$i]->client_iUtilisateurCreateurId;
							
							if ($oNewClient->client_zNom == "" || is_null($oNewClient->client_zNom))
								$oNewClient->client_zNom = $toClient[$i]->client_zNom;
							
							if ($oNewClient->client_zPrenom == "" || is_null($oNewClient->client_zPrenom))
								$oNewClient->client_zPrenom = $toClient[$i]->client_zPrenom;
							
							if ($oNewClient->client_zFonction == "" || is_null($oNewClient->client_zFonction))
								$oNewClient->client_zFonction = $toClient[$i]->client_zFonction;
							
							if ($oNewClient->client_zMail == "" || is_null($oNewClient->client_zMail))
								$oNewClient->client_zMail = $toClient[$i]->client_zMail;
							
							if ($oNewClient->client_zLogin == "" || is_null($oNewClient->client_zLogin))
								$oNewClient->client_zLogin = $toClient[$i]->client_zLogin;
							
							if ($oNewClient->client_zPass == "" || is_null($oNewClient->client_zPass))
								$oNewClient->client_zPass = $toClient[$i]->client_zPass;
							
							if ($oNewClient->client_zTel == "" || is_null($oNewClient->client_zTel))
								$oNewClient->client_zTel = $toClient[$i]->client_zTel;

							if ($oNewClient->client_zPortable == "" || is_null($oNewClient->client_zPortable))
								$oNewClient->client_zPortable = $toClient[$i]->client_zPortable;
							
							if ($oNewClient->client_zRue == "" || is_null($oNewClient->client_zRue))
								$oNewClient->client_zRue = $toClient[$i]->client_zRue;

							if ($oNewClient->client_zVille == "" || is_null($oNewClient->client_zVille))
								$oNewClient->client_zVille = $toClient[$i]->client_zVille;

							if ($oNewClient->client_zCP == "" || is_null($oNewClient->client_zCP))
								$oNewClient->client_zCP = $toClient[$i]->client_zCP;

							if ($oNewClient->client_iPays == "" || is_null($oNewClient->client_iPays))
								$oNewClient->client_iPays = $toClient[$i]->client_iPays;

							if ($oNewClient->client_zCryptedKey == "" || is_null($oNewClient->client_zCryptedKey))
								$oNewClient->client_zCryptedKey = $toClient[$i]->client_zCryptedKey;
						}
					}
					if ($zClientIdToTreate != ""){
						// UPDATE TABLES EVENT && CLIENTSAUTO ASSOCIE
						$zSql = "SELECT COUNT(*) AS iNbreEvent FROM evenement WHERE evenement.evenement_iStagiaire IN (".$zClientIdToTreate.")";
						$oDBW	  = jDb::getDbWidget() ;
						$oCount = $oDBW->fetchFirst($zSql) ;
						if ($oCount->iNbreEvent > 0){
							$zQuery1 = "UPDATE evenement SET evenement.evenement_iStagiaire = ".$oNewClient->client_id." WHERE evenement.evenement_iStagiaire IN (".$zClientIdToTreate.")";
							$oCnx = jDb::getConnection();
							$oCnx->exec($zQuery1);	
						} 
						$zQuery2 = "UPDATE clientsauto SET clientsauto_clientid = ".$oNewClient->client_id." WHERE clientsauto_clientid IN  (".$zClientIdToTreate.")";
						$oCnx = jDb::getConnection();
						$oCnx->exec($zQuery2);	

						// DELETE CLIENT 
						$zQuery3 = "DELETE FROM clients WHERE client_id IN (".$zClientIdToTreate.")";
						$oCnx = jDb::getConnection();
						$oCnx->exec($zQuery3);	
					}
					// UPDATE ONEWCLIENT
					jClasses::inc('commun~tools');
					$oDaoFact = jDao::get('commun~client') ;
					$oRecord = $oDaoFact->get($oNewClient->client_id) ;

					$oRecord->client_iSociete						= $oNewClient->client_iSociete ;
					$oRecord->client_iCivilite						= $oNewClient->client_iCivilite ;
					$oRecord->client_iUtilisateurCreateurId			= $oNewClient->client_iUtilisateurCreateurId ;
					$oRecord->client_zNom							= $oNewClient->client_zNom ;
					$oRecord->client_zPrenom						= $oNewClient->client_zPrenom ;
					$oRecord->client_zFonction						= $oNewClient->client_zFonction ;
					$oRecord->client_zMail							= $oNewClient->client_zMail ;
					$oRecord->client_zLogin							= $oNewClient->client_zNom ;
					$oRecord->client_zPass							= $oNewClient->client_zPass ;
					$oRecord->client_zTel							= $oNewClient->client_zTel ;
					$oRecord->client_zPortable						= $oNewClient->client_zPortable ;
					$oRecord->client_zRue							= $oNewClient->client_zRue ;
					$oRecord->client_zVille							= $oNewClient->client_zVille ;
					$oRecord->client_zCP							= $oNewClient->client_zCP ;
					$oRecord->client_iPays							= $oNewClient->client_iPays ;
					$oRecord->client_iNumIndividu					= $oNewClient->client_iNumIndividu ;
					$oRecord->client_iRefIndividu					= $oNewClient->client_iRefIndividu ;
					$oRecord->client_zCryptedKey					= $oNewClient->client_zCryptedKey ;
					$oRecord->client_iStatut						= $oNewClient->client_iStatut ;
					$oRecord->client_testDebut						= $oNewClient->client_testDebut ;

					if (isset($oRecord->client_dateCreation) && is_null($oRecord->client_dateCreation)){
						$oRecord->client_dateCreation				= date('Y-m-d H:i:s');
					}
					$oRecord->client_dateMaj						= date('Y-m-d H:i:s');
					$oProfil = jDb::getProfil(); 
					$bTestProfil = tools::testProfil($oProfil); 
					if (!$bTestProfil){tools::createConnector($oProfil);} 
					$oCnx = jDb::getConnection(); 
					$oDaoFact->update($oRecord);
				} // FIN if (sizeof($toClient) > 1)
			} // FIN if ($oRecord->client_iRefIndividu != "" || !is_null($oRecord->client_iRefIndividu))
		} // FIN foreach($toRecord as $oRecord)
		return 1003;
	}
}
?>