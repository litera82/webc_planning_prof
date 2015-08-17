<?php
@ini_set ("memory_limit", -1) ;
class evenementExcelSrv {
	static function export($toParams){
		jClasses::inc('commun~toolDate');
		//AND HEURES_PREVUES - HEURES_PRODUITES > 0

		$zSql = "SELECT HEURES_PREVUES - HEURES_PRODUITES AS soldeavantsaisie, evenement.*, evenementvalidation.*, validation.*, typeevenements.*, clients.*, societe.*, clientsenvironnement.*, composant_cours.*,utilisateurs.* FROM evenement 
		INNER JOIN typeevenements ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id	
		LEFT JOIN clients ON evenement.evenement_iStagiaire = clients.client_id
		LEFT JOIN societe ON societe.societe_id = clients.client_iSociete
		LEFT JOIN composant_cours ON (clients.client_iNumIndividu = composant_cours.NUMERO OR clients.client_iNumIndividu = composant_cours.CODE_STAGIAIRE_MIRACLE)
		INNER JOIN utilisateurs ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id
		INNER JOIN utilisateursgroup ON utilisateursgroup.utilisateursgroup_utilisateurId = utilisateurs.utilisateur_id
		LEFT JOIN evenementvalidation ON evenement.evenement_id = evenementvalidation.evenementvalidation_eventId
		LEFT JOIN validation ON evenementvalidation.evenementvalidation_validationId = validation.validation_id
		LEFT JOIN clientsenvironnement ON clients.client_id = clientsenvironnement.clientId
		WHERE 1=1 
		AND evenement.evenement_iTypeEvenementId IN (7, 9, 10, 11, ".ID_TYPE_EVENEMENT_COUR_TELEPHONE.", 17, 20, 23, 24, 25)
		AND evenement.evenement_zDateHeureDebut BETWEEN '".toolDate::toDateSQL($toParams[0]->zDateDebut)."' AND '".toolDate::toDateSQL($toParams[0]->zDateFin)."'" ;
		
		if (isset ($toParams[0]->professeurs) && $toParams[0]->professeurs > 0){
			$zSql .= " AND evenement.evenement_iUtilisateurId = " . $toParams[0]->professeurs ;
		}
		if (isset ($toParams[0]->groupe_id) && $toParams[0]->groupe_id > 0){
			$zSql .= " AND utilisateursgroup.utilisateursgroup_groupId = " . $toParams[0]->groupe_id ;
		}
		$zSql .= " AND utilisateurs.utilisateur_statut = 1 "; 
		$zSql .= " GROUP BY evenement.evenement_id 
		ORDER BY evenement.evenement_zDateHeureDebut ASC " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}


	static function exportEventPlan($_zExportsFullPath, $_toEvenement, $_toParams, $_oUtilisateur){
    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		require_once (LIB_PATH . "pear/Spreadsheet/Excel/Writer.php") ;
		$oWorkBook = new  Spreadsheet_Excel_Writer ($_zExportsFullPath) ;
		// --- Format titre

		$oTitleFormat =& $oWorkBook->addFormat () ;
		$oTitleFormat->setFontFamily ("Arial") ;
		$oTitleFormat->setSize (8) ;
		$oTitleFormat->setBold (1) ;
		$oTitleFormat->setColor('yellow');
		$oTitleFormat->setPattern (1) ;
		$oTitleFormat->setFgColor ('blue') ; 
		$oTitleFormat->setAlign ("centre") ;

		// --- Format d'entête

		$oHeaderFormat =& $oWorkBook->addFormat () ;
		$oHeaderFormat->setFontFamily ("Arial") ;
		$oHeaderFormat->setSize (10) ;
		$oHeaderFormat->setBold (1) ;
		$oHeaderFormat->setFgColor (22) ;
		$oHeaderFormat->setAlign ("centre") ;

		$oHeaderFormat->setBottom (1) ;
		$oHeaderFormat->setTop (1) ;
		$oHeaderFormat->setLeft (1) ;
		$oHeaderFormat->setRight (1) ;

		$oHeaderFormatEntete =& $oWorkBook->addFormat () ;
		$oHeaderFormatEntete->setFontFamily ("Arial") ;
		$oHeaderFormatEntete->setSize (10) ;
		$oHeaderFormatEntete->setBold (1) ;
		$oHeaderFormatEntete->setAlign ("centre") ;

		// --- Format de ligne
		$oLineFormatRight =& $oWorkBook->addFormat () ;
		$oLineFormatRight->setFontFamily ("Arial") ;
		$oLineFormatRight->setSize (8) ;
		$oLineFormatRight->setAlign ("right") ;

		//Date 	Durée 	Type d'événement 	Details du Stagiare 	Description de l'événement 	Actions
		$oWorkSheet =& $oWorkBook->addWorksheet (" Cours plannifiés ") ;
		$oWorkSheet->setColumn (0, 0, 40) ;//Date
		$oWorkSheet->setColumn (1, 1, 40) ;//Nom
		$oWorkSheet->setColumn (2, 2, 40) ;//Société
		$oWorkSheet->setColumn (3, 3, 10) ;//Durée
		$oWorkSheet->setColumn (4, 4, 30) ;//Code stagiaire miracle
		$oWorkSheet->setColumn (5, 5, 20) ;//Numero individu
		$oWorkSheet->setColumn (6, 6, 30) ;//Tel
		$oWorkSheet->setColumn (7, 7, 25) ;//Description de l'événement
		$oWorkSheet->setColumn (8, 8, 12) ;//H prévues
		$oWorkSheet->setColumn (9, 9, 12) ;//H produites
		$oWorkSheet->setColumn (10, 10, 12) ;//Solde 
		$oWorkSheet->setColumn (11, 11, 25) ;//Professeur
		$oWorkSheet->setColumn (12, 12, 30) ;//Type d'événement 
		$oWorkSheet->setColumn (13, 13, 30) ;//Présence du stagiaire 
		$oWorkSheet->setColumn (14, 14, 100) ;//Commentaires 

		$iLineIndex = 2 ;
		$iCol = 0;

		//ecriture de l'entete
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode(" Liste des cours plannifiés "), $oHeaderFormatEntete) ;

		$iLineIndex = 3 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode("De " . $_toParams[0]->zDateDebut . " à " . $_toParams[0]->zDateFin), $oHeaderFormatEntete) ;

		if (isset($_toParams[0]->groupe_id) && $_toParams[0]->groupe_id != 0){
			if ($_toParams[0]->groupe_id == 3){
				$zGroupe = "Groupe de prof : Maurice"; 
			}elseif($_toParams[0]->groupe_id == 2){
				$zGroupe = "Groupe de prof : FR face à face"; 
			}else{
				$zGroupe = "Groupe de prof : FR téléphone"; 
			}
		}else{
			$zGroupe = "Groupe de prof : Tous"; 
		}

		if (isset($_oUtilisateur) && isset($_oUtilisateur->utilisateur_id) && $_oUtilisateur->utilisateur_id > 0){
			$zProfesseur = "Professeur : " . $_oUtilisateur->utilisateur_zNom . " " .$_oUtilisateur->utilisateur_zPrenom; 
		}else{
			$zProfesseur = "Professeur : Tous les professeurs"; 
		}

		$iLineIndex = 4 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($zGroupe), $oHeaderFormatEntete) ;

		$iLineIndex = 5 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($zProfesseur), $oHeaderFormatEntete) ;

		$iLineIndex = 6 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode("Nombre d'événement trouvés :" . $_toEvenement['iResTotal']), $oHeaderFormatEntete) ;


		$iLineIndex = 9 ;
		$iCol = 0;

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode(" Date "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+1, utf8_decode(" Nom "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+2, utf8_decode(" Société "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+3, utf8_decode(" Durée "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+4, utf8_decode(" Code stagiaire miracle "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+5, utf8_decode(" Numero individu "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+6, utf8_decode(" Tel "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+7, utf8_decode(" Description de l'événement"), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+8, utf8_decode(" H prévues"), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+9, utf8_decode(" H produites "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+10, utf8_decode(" Solde"), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+11, utf8_decode(" Professeur"), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+12, utf8_decode(" Type d'événement "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+13, utf8_decode(" Présence du stagiaire "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+14, utf8_decode(" Commentaires "), $oHeaderFormat) ;

		$iCpt = 1;
		
		$iLineIndex = 10 ;
		$oLineFormatLeft =& $oWorkBook->addFormat () ;
		$oLineFormatLeft->setFontFamily ("Arial") ;
		$oLineFormatLeft->setSize (8) ;
		$oLineFormatLeft->setAlign ("left") ;
		$oLineFormatLeft->setFgColor ("white") ;

		$oLineFormatLeft->setBottom (1) ;
		$oLineFormatLeft->setTop (1) ;
		$oLineFormatLeft->setLeft (1) ;
		$oLineFormatLeft->setRight (1) ;

		$oLineFormatCenter =& $oWorkBook->addFormat () ;
		$oLineFormatCenter->setFontFamily ("Arial") ;
		$oLineFormatCenter->setSize (8) ;
		$oLineFormatCenter->setAlign ("centre") ;
		$oLineFormatCenter->setFgColor ("white") ;

		$oLineFormatCenter->setBottom (1) ;
		$oLineFormatCenter->setTop (1) ;
		$oLineFormatCenter->setLeft (1) ;
		$oLineFormatCenter->setRight (1) ;

		foreach ($_toEvenement['toListes'] as $oEvenement){
			/*if (floor($iCpt%2) == 0){
				$oLineFormatLeft->setFgColor (22) ;
				$oLineFormatCenter->setFgColor (22) ;
			}*/

			$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($oEvenement->evenement_zDateJoursDeLaSemaine) . " " . utf8_decode(toolDate::toDateWebCalendarForXls($oEvenement->evenement_zDateHeureDebut)), $oLineFormatLeft) ;

			if ($oEvenement->client_id > 0){
				$oWorkSheet->writeString ($iLineIndex, $iCol+1, utf8_decode($oEvenement->client_zNom . " " . $oEvenement->client_zPrenom), $oLineFormatCenter) ;			
				$oWorkSheet->writeString ($iLineIndex, $iCol+2, utf8_decode($oEvenement->societe_zNom), $oLineFormatCenter) ;			
			}else{
				$oWorkSheet->writeString ($iLineIndex, $iCol+1, "-", $oLineFormatCenter) ;						
				$oWorkSheet->writeString ($iLineIndex, $iCol+2, "-", $oLineFormatCenter) ;						
			}

			$zDure = $oEvenement->evenement_iDuree . ' mn';
			if (isset ($oEvenement->evenement_iDureeTypeId) && $oEvenement->evenement_iDureeTypeId == 1){
				$zDure = $oEvenement->evenement_iDuree . ' h';
			}

			$oWorkSheet->writeString ($iLineIndex, $iCol+3, utf8_decode($zDure), $oLineFormatCenter) ;
			if ($oEvenement->client_id > 0){
				$oWorkSheet->writeString ($iLineIndex, $iCol+4, utf8_decode($oEvenement->CODE_STAGIAIRE_MIRACLE), $oLineFormatCenter) ;
				$oWorkSheet->writeString ($iLineIndex, $iCol+5, utf8_decode($oEvenement->client_iNumIndividu), $oLineFormatCenter) ;		
				$oWorkSheet->writeString ($iLineIndex, $iCol+6, utf8_decode($oEvenement->client_zPortable . " - " . $oEvenement->evenement_zContactTel), $oLineFormatCenter) ;			
				
				$oWorkSheet->writeString ($iLineIndex, $iCol+7, utf8_decode($oEvenement->evenement_zLibelle . " / ". $oEvenement->evenement_zDescription), $oLineFormatLeft) ;
			}else{
				$oWorkSheet->writeString ($iLineIndex, $iCol+4, "-", $oLineFormatCenter) ;						
				$oWorkSheet->writeString ($iLineIndex, $iCol+5, "-", $oLineFormatCenter) ;						
				$oWorkSheet->writeString ($iLineIndex, $iCol+6, "-", $oLineFormatCenter) ;						
				$oWorkSheet->writeString ($iLineIndex, $iCol+7, utf8_decode($oEvenement->evenement_zDescription), $oLineFormatLeft) ;
			}
			
			$oWorkSheet->writeString ($iLineIndex, $iCol+8, utf8_decode($oEvenement->HEURES_PREVUES), $oLineFormatCenter) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+9, utf8_decode($oEvenement->HEURES_PRODUITES), $oLineFormatCenter) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+10, utf8_decode($oEvenement->soldeavantsaisie), $oLineFormatCenter) ;

			$oWorkSheet->writeString ($iLineIndex, $iCol+11, utf8_decode($oEvenement->utilisateur_zNom . " " . $oEvenement->utilisateur_zPrenom), $oLineFormatCenter) ;

			$oWorkSheet->writeString ($iLineIndex, $iCol+12, utf8_decode($oEvenement->typeevenements_zLibelle), $oLineFormatCenter) ;

			$oWorkSheet->writeString ($iLineIndex, $iCol+13, utf8_decode($oEvenement->validation_zLibelle), $oLineFormatCenter) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+14, utf8_decode($oEvenement->evenementvalidation_commentaire), $oLineFormatCenter) ;

			$iLineIndex++ ;
			$iCpt++;
		}

		$oWorkBook->close () ;

		if (is_file ($_zExportsFullPath) ) {
			@chmod ($_zExportsFullPath, 0666) ;			
		}
		unset ($oWorkSheet) ;
		unset ($oWorkBook) ;
	}
}
?>