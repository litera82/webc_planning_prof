// variable de controle pour la suppression d'un visuel
var g_supprvisuel=0;

// appel du browse de FCKEditor
function browserImg(_zurlMedias, _zChampDestination)
{
	ServerPath = j_basepath + _zurlMedias;	
	url = j_basepath + 'FCKeditor/editor/filemanager/browser/default/browser.html?DirectoryOnly=1&Type=Image&externe=yes&champs='+_zChampDestination+'&focus=focus&FolderDisplay=1&ServerPath='+ServerPath+'&Connector=' + j_basepath + 'FCKeditor/editor/filemanager/connectors/php/connector.php';

	var iLeft = (screen.width  - screen.width * 0.7) / 2 ;
	var iTop  = (screen.height - screen.height * 0.7) / 2 ;

	var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
	sOptions += ",width=" + screen.width * 0.7 ;
	sOptions += ",height=" + screen.height * 0.7 ;
	sOptions += ",left=" + iLeft ;
	sOptions += ",top=" + iTop ;

	//+ '&input=image_bonplan'
	window.open( url , "BrowseWindow", sOptions ) ;
}
