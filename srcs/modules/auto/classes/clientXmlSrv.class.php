<?php
/**
* @package		webcalendar
* @subpackage	client
* @version		1
* @author		tahiry
*/

/**
* Fonctions utilitaires pour la gestion des flux clients
*/
class clientXmlSrv {
	var $i = 0;

	/**
	* @desc	Methode du constructeur
	*
	*/ 
	public function __construct (){

		$this->oResult = new StdClass () ;
		$this->oPortefeuilleProf = new StdClass () ;
		$this->oResult->toPortefeuilleProf	= array () ;

		$this->bDataroot = false ;
		$this->bPortefeuilleProf = false ;
		$this->bRefIndividu = false;
		$this->bNumeroDossierStagiaire = false;
		$this->bCivilite = false;
		$this->bNomfamille = false;
		$this->bPrenom = false;
		$this->bFonction = false;
		$this->bTel = false;
		$this->bAdresse1 = false;
		$this->bAdresse2 = false;
		$this->bMobile = false;
		$this->bCodePostal = false;
		$this->bVille = false;
		$this->bSociete = false;
		$this->bProf1 = false;
		$this->bProf2 = false;
		$this->bEmail = false;
	}

	/**
	* @desc	Methode du destructeur
	*
	*/
	public function __destruct (){
	
	}

	/**
	* @param string $_zUrl
	*/
	function getClientXml($_zUrl) {

		$xml_parser = xml_parser_create("UTF-8");
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($xml_parser, $this);
		
		xml_set_element_handler($xml_parser, "_startElement", "_endElement");
		xml_set_character_data_handler($xml_parser, "_charHandler");
		$fp = fopen($_zUrl, "r");
			//die("could not open XML input >>>>>>>>>>>>>>>>>>>> " . $_zUrl);
		while ($data = fread($fp, 4096)) {
			if (!xml_parse($xml_parser, $data, feof($fp))) {
				die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
			}
		}
		xml_parser_free($xml_parser);
		return $this->oResult ;
	}

	/**
	* @param string $xml_parser
	* @param string $zName		nom de la balise
	* @param string $zAttrs		nom de l'attribut de la balise
	*/
	function _startElement($xml_parser, $zName, $zAttrs) {		
		//Ouvre chaque balise trouvé
		switch($zName){
			case "dataroot":
				$this->bDataroot = true ;
				$this->oResult = new StdClass () ;
				$this->oResult->toPortefeuilleProf	= array () ;
			break;
			case "portefeuille_prof":
				$this->bPortefeuilleProf	= true ;
				$this->oPortefeuilleProf = new StdClass () ;
				$this->oPortefeuilleProf->client_iRefIndividu = "" ;
				$this->oPortefeuilleProf->client_iNumIndividu = "" ;
				$this->oPortefeuilleProf->client_zLogin = "" ;
				$this->oPortefeuilleProf->client_zPass = "" ;
				$this->oPortefeuilleProf->client_zCivilite = "" ;
				$this->oPortefeuilleProf->client_zNom = "" ;
				$this->oPortefeuilleProf->client_zPrenom = "" ;
				$this->oPortefeuilleProf->client_zFonction = "" ;
				$this->oPortefeuilleProf->client_zTel = "" ;
				$this->oPortefeuilleProf->client_zAdresse1 = "" ;
				$this->oPortefeuilleProf->client_zAdresse2 = "" ;
				$this->oPortefeuilleProf->client_zPortable = "" ;
				$this->oPortefeuilleProf->client_zCP = "" ;
				$this->oPortefeuilleProf->client_zVille = "" ;
				$this->oPortefeuilleProf->client_zSociete = "" ;
				$this->oPortefeuilleProf->client_zUtilisateurCreateurId1 = "" ;
				$this->oPortefeuilleProf->client_zUtilisateurCreateurId2 = "" ;
				$this->oPortefeuilleProf->client_zMail = "" ;
			break;
			case "RefIndividu":
				$this->bRefIndividu	= true ;
			break;
			case "numero_dossier_stagiaire":
				$this->bNumeroDossierStagiaire = true;
			break;
			case "Civilite":
				$this->bCivilite = true;
			break;
			case "Nomfamille":
				$this->bNomfamille = true;
			break;
			case "Prenom":
				$this->bPrenom = true;
			break;
			case "Fonction":
				$this->bFonction = true;
			break;
			case "Tel":
				$this->bTel = true;
			break;
			case "Adresse1":
				$this->bAdresse1 = true;
			break;
			case "Adresse2":
				$this->bAdresse2 = true;
			break;
			break;
			case "Mobile":
				$this->bMobile = true;
			break;
			case "CodePostal":
				$this->bCodePostal = true;
			break;
			case "Ville":
				$this->bVille = true;
			break;
			case "Societe":
				$this->bSociete = true;
			break;
			case "Prof1":
				$this->bProf1 = true;
			break;
			case "Prof2":
				$this->bProf2 = true;
			break;
			case "Email":
				$this->bEmail = true;
			break;
		}
	}

	/**
	* @param string $xml_parser
	* @param string $zName		nom de la balise
	*/
	function _endElement($xml_parser, $zName) {
		//Ferme chaque balise trouvé
		switch($zName){
			case "portefeuille_prof":
				$this->bPortefeuilleProf	= false ;
				array_push($this->oResult->toPortefeuilleProf, $this->oPortefeuilleProf);
			break;
			case "RefIndividu":
				$this->bRefIndividu	= false ;
			break;
			case "numero_dossier_stagiaire":
				$this->bNumeroDossierStagiaire = false;
			break;
			case "Civilite":
				$this->bCivilite = false;
			break;
			case "Nomfamille":
				$this->bNomfamille = false;
			break;
			case "Prenom":
				$this->bPrenom = false;
			break;
			case "Fonction":
				$this->bFonction = false;
			break;
			case "Tel":
				$this->bTel = false;
			break;
			case "Adresse1":
				$this->bAdresse1 = false;
			break;
			case "Adresse2":
				$this->bAdresse2 = false;
			break;
			break;
			case "Mobile":
				$this->bMobile = false;
			break;
			case "CodePostal":
				$this->bCodePostal = false;
			break;
			case "Ville":
				$this->bVille = false;
			break;
			case "Societe":
				$this->bSociete = false;
			break;
			case "Prof1":
				$this->bProf1 = false;
			break;
			case "Prof2":
				$this->bProf2 = false;
			break;
			case "Email":
				$this->bEmail = false;
			break;
		}
	}

	/**
	* handler
	* @param string $xml_parser
	* @param string $_zData	
	*/
	function _charHandler($xml_parser, $_zAttrs) {
		$zAttrs = trim($_zAttrs) ;
		if ($this->bPortefeuilleProf && $this->bRefIndividu){
			$this->oPortefeuilleProf->client_iRefIndividu .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bNumeroDossierStagiaire){
			$this->oPortefeuilleProf->client_iNumIndividu .= $zAttrs ;
			$this->oPortefeuilleProf->client_zPass .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bCivilite){
			$this->oPortefeuilleProf->client_zCivilite .= $zAttrs ;
		}

		if ($this->bPortefeuilleProf && $this->bNomfamille){
			$this->oPortefeuilleProf->client_zNom .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bPrenom){
			$this->oPortefeuilleProf->client_zPrenom .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bFonction){
			$this->oPortefeuilleProf->client_zFonction .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bTel){
			$this->oPortefeuilleProf->client_zTel .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bAdresse1){
			$this->oPortefeuilleProf->client_zAdresse1 .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bAdresse2){
			$this->oPortefeuilleProf->client_zAdresse2 .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bMobile){
			$this->oPortefeuilleProf->client_zPortable .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bCodePostal){
			$this->oPortefeuilleProf->client_zCP .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bVille){
			$this->oPortefeuilleProf->client_zVille .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bSociete){
			$this->oPortefeuilleProf->client_zSociete .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bProf1){
			$this->oPortefeuilleProf->client_zUtilisateurCreateurId1 .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bProf2){
			$this->oPortefeuilleProf->client_zUtilisateurCreateurId2 .= $zAttrs ;
		}
		if ($this->bPortefeuilleProf && $this->bEmail){
			$this->oPortefeuilleProf->client_zMail .= $zAttrs ;
			$this->oPortefeuilleProf->client_zLogin .= $zAttrs ;
		}
	}


	static function importXmlStagiaire ($_zXmlImported)
    {
    	$oResult = 1 ;
		$oSimpleXml =  new SimpleXMLElement($_zXmlImported) ;
		$iSocieteId = 0;
		foreach ($oSimpleXml->stagiaire as $oStagiaire)
		{
			// --- Verifie si le stagiaire n'est pas déjà dans la base
			$oClient = clientSrv::getClientByNumIndividu($oStagiaire->num_individu) ;
			if (isset($oStagiaire->societe) && strlen(strval($oStagiaire->societe)) > 0){
		        $oCnx = jDb::getConnection () ;
				$zSqlSociete = 'SELECT * FROM societe WHERE societe.societe_zNom = "'.trim(strtolower($oStagiaire->societe)).'" LIMIT 0, 1';
				$Rs = $oCnx->query($zSqlSociete);
				$toResults = array ();
				if ($oRecord = $Rs->fetch()){
					array_push($toResults, $oRecord);
				}
				if (isset($toResults[0]->societe_id) && $toResults[0]->societe_id > 0){
					$iSocieteId = $toResults[0]->societe_id;
				}else{
					jClasses::inc('client~societeSrv');
					$toSociete['societe_id'] = 0;
					$toSociete['societe_zNom'] = $oStagiaire->societe;
					$toSociete['societe_iStatut'] = 1;
					$oRec = societeSrv::save($toSociete);  
					$iSocieteId = $oRec->societe_id; 
				}
			}

			if (!$oClient)
			{
				$oFac = jDao::get('commun~client');
				$oRecord = jDao::createRecord('commun~client');
				$oRecord->client_iSociete = $iSocieteId ;
				$zCivilite = $oStagiaire->civilite;
				switch (strval($zCivilite)){
					case "M.":$iCivilite=1;break;
					case "Mme":$iCivilite=0;break;
					case "Mlle":$iCivilite=2;break;
					default:$iCivilite=0; 
				}
				$oRecord->client_iCivilite = $iCivilite ;
				$oRecord->client_iUtilisateurCreateurId = AUTOPLANNIFICATION_ID_CATRIONA ;

				/*****/
				if ((isset ($oStagiaire->prof->nom[0]) && strlen($oStagiaire->prof->nom[0]) > 0) || (isset ($oStagiaire->prof->prenom[0]) && strlen($oStagiaire->prof->prenom[0]) > 0)){
					$oRecord->client_iUtilisateurCreateurId = clientXmlSaveSrv::getUtilisateurByNameProf($oStagiaire->prof->nom[0], $oStagiaire->prof->prenom[0]) ;
				}
				/*****/

				$oRecord->client_zNom = utf8_decode($oStagiaire->nom) ;
				$oRecord->client_zPrenom = utf8_decode($oStagiaire->prenoms) ;
				$oRecord->client_zFonction = utf8_decode($oStagiaire->fonction) ;
				$oRecord->client_zMail = utf8_decode($oStagiaire->mail) ;
				$oRecord->client_zLogin = utf8_decode($oStagiaire->nom) ;
				$oRecord->client_zPass = $oStagiaire->num_individu) ;
				$oRecord->client_zTel = $oStagiaire->telephone) ;
				$oRecord->client_zPortable = utf8_decode($oStagiaire->portable) ;
				$oRecord->client_zRue = utf8_decode($oStagiaire->rue_stage) ;
				$oRecord->client_zVille = utf8_decode($oStagiaire->ville_stage) ;
				$oRecord->client_zCP = utf8_decode($oStagiaire->Cp_stage) ;
				$oRecord->client_iPays = 0 ;
				$oRecord->client_iNumIndividu = $oStagiaire->num_individu ;
				if (isset($oStagiaire->cryptedmd5) && $oStagiaire->cryptedmd5 != ""){
					$oRecord->client_zCryptedKey = $oStagiaire->cryptedmd5 ;
				}
				$oRecord->client_iStatut = STATUT_OK ;
				$oRecord->client_testDebut = 0 ;
				jLog::dump($oRecord) ;
				$iTest = $oFac->insert($oRecord) ;
				
				if (!$oRecord->client_id)
				{
					$oResult = 0 ;
					break;
				}
				else 
				{
					$oFac = jDao::get('commun~client');
					$oRecord->client_zLogin = $oRecord->client_zLogin ;
					$oFac->update($oRecord) ;
					$_SESSION['JELIX_USER_AUTO'] = $oRecord ;
					
					// --- Envoye de mail au client
					//self::sendMailStagiaire($oRecord);
				}
			}
		}
		return $oResult ;
    }
}
?>
