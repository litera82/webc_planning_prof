<?php
/**
* @package   jelix_calendar
* @subpackage client
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class clientCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_CLIENT, BoHtmlResponse::MENU_CLIENT_LISTE) ;
		$oCritere = new stdClass ();

		$oCritere->nom = $this->param('nom', '', true);
		$oCritere->statut = $this->param('statut', 3, true);

        $oResp->body->assignZone('zContent', 'client~BoClientListe', array('oCritere'=>$oCritere)) ;
	
		return $oResp ;
    }
	function edit() {
		$toParams = $this->params() ;
		$oResp = $this->getResponse('BoHtml') ;
		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_CLIENT, BoHtmlResponse::MENU_CLIENT_LISTE) ;
		$oResp->body->assignZone('zContent', 'client~BoClientEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ;
        jClasses::inc('client~clientSrv');
        $oclient = clientSrv::save($toParams) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'client~client:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('client~clientSrv');
        clientSrv::delete($this->param('iClientId', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'client~client:index' ;
        return $oResp ;
    }

	/**
	* R�cup�ration des donn�es xml client
	*
	*/
	function pageImportXmlData(){
		$oRep = $this->getResponse('BoHtml');
		$oRep->tiMenusActifs = array(BoHtmlResponse::MENU_CLIENT, BoHtmlResponse::MENU_CLIENT_IMPORT) ;
        $x = $this->param('x', 0) ;
		$oRep->body->assignZone('zContent', 'client~BoImportXmlStagiaire', array('x'=>$x)) ;
		return $oRep;
	}
	function pageImportBddLogEvent(){
		$oRep = $this->getResponse('BoHtml');
		$oRep->tiMenusActifs = array(BoHtmlResponse::MENU_CLIENT, BoHtmlResponse::MENU_CLIENT_BDD) ;
        $x = $this->param('x', 0) ;
		$oRep->body->assignZone('zContent', 'client~BoImportBddLogEventStagiaire', array('x'=>$x)) ;
		return $oRep;
	}
	/**
	* R�cup�ration des donn�es xml client
	*
	*/
	function getClientData(){
        $oResp = $this->getResponse('redirect') ;
		if (isset($_FILES['PostedXMLStagiaire']["name"])) {
			if (file_exists($_FILES['PostedXMLStagiaire']["tmp_name"]) && is_file ($_FILES['PostedXMLStagiaire']["tmp_name"])) {
				$zFileDest = XML_FILE_PORTEFEUILLE_PROF . '_'.date('d_m_Y_H_i_s').'.xml';
				if (!move_uploaded_file($_FILES['PostedXMLStagiaire']["tmp_name"],  XML_PATH_PORTEFEUILLE_PROF . $zFileDest)){
					$oResp->action = 'auto~import:importXmlStagiaire';
					$oResp->params = array('x'=>1001); //Erreur transfert fichier
				}else{
					chmod(XML_PATH_PORTEFEUILLE_PROF . $zFileDest, 0777);
					@unlink($_FILES['PostedXMLStagiaire']["tmp_name"]);
					//$x=importStagiaire::parseXml(PATH_XML_STAGIAIRE . $zFileDest); 
					jClasses::inc('client~clientXmlSaveSrv');
					clientXmlSaveSrv::saveClient(XML_PATH_PORTEFEUILLE_PROF . $zFileDest);
					$oResp->action = 'client~client:pageImportXmlData';
					$oResp->params = array('x'=>1003); //Succes import
				}
		   }else{
				$oResp->action = 'client~client:pageImportXmlData';
				$oResp->params = array('x'=>1001); //Erreur transfert fichier
		   }
		}else{
			$oResp->action = 'client~client:pageImportXmlData';
			$oResp->params = array('x'=>1002); // Erreur upload fichier
		}
		return $oResp ;	
	}

	function getClientDataDepuisBddLogEvent(){
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc('client~clientXmlSaveSrv');
		$iRet = clientXmlSaveSrv::saveClientDepuisBddLogevent();
		$oResp->action = 'client~client:pageImportBddLogEvent';
		$oResp->params = array('x'=>$iRet); // 1003 Succes import
		return $oResp ;	
	}

	function pageSuivieStagiaire (){
		$oRep = $this->getResponse('BoHtml');
		$oRep->tiMenusActifs = array(BoHtmlResponse::MENU_CLIENT, BoHtmlResponse::MENU_CLIENT_SUIVIE) ;
        $x = $this->param('x', 0) ;
		$oRep->body->assignZone('zContent', 'client~BoSuivieStagiaire', array('x'=>$x)) ;
		return $oRep;
	}

	function exportSuivieStagiaire(){
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc('client~clientSrv');
		$iRet = clientSrv::exportSuivieStagiaire();
		$oResp->action = 'client~client:pageSuivieStagiaire';
		$oResp->params = array('x'=>$iRet); // 1003 Succes import
		return $oResp ;	
	}

	function pageBddClientDedoublonne(){
		$oRep = $this->getResponse('BoHtml');
		$oRep->tiMenusActifs = array(BoHtmlResponse::MENU_CLIENT, BoHtmlResponse::MENU_CLIENT_DEDOUBLE) ;
        $x = $this->param('x', 0) ;
		$oRep->body->assignZone('zContent', 'client~BoBddClientDedoublonne', array('x'=>$x)) ;
		return $oRep;
	}

	function dedoublonnerTableClient(){
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc('client~clientDedoublonneSrv');
		$iRet = clientDedoublonneSrv::saveClientDepuisBddLogevent();
		$oResp->action = 'client~client:pageBddClientDedoublonne';
		$oResp->params = array('x'=>$iRet); // 1003 Succes import
		return $oResp ;	
	}

}