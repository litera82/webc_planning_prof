<?php

	include_once ("../application.init.php") ;

	// --- Fonctions utilitaires

	function cleanCurrentFile ($_zFilePath, $_bToRemove = false)
	{
		if (filetype ($_zFilePath) == "dir")
		{
			echo "<li>Rep : ". $_zFilePath . "<ul>" ;
			$oDir = opendir ($_zFilePath) ;
			while (($zCurrEntry = readdir ($oDir)) !== false)
			{
				if ($zCurrEntry != "." && $zCurrEntry != "..")
				{
					cleanCurrentFile ($_zFilePath . "/" . $zCurrEntry, true) ;
				}
			}
			echo "</ul></li>" ;
			if ($_bToRemove)
			{
				rmdir ($_zFilePath) ;
			}
		}
		elseif (filetype ($_zFilePath) == "file")
		{
			echo "<li>Fichier : ". $_zFilePath . "</li>" ;
			if ($_bToRemove)
			{
				unlink ($_zFilePath) ;
			}
		}
	}

	$zRootFile = JELIX_APP_TEMP_PATH ;
	if (substr ($zRootFile, -1, 1) == "/")
	{
		$zRootFile = substr ($zRootFile, 0, -1) ;
	}

	cleanCurrentFile ($zRootFile) ;

?>