;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule = "light"
startAction = "default:index"


defaultEntrypoint= light
entrypointExtension= .php

[coordplugins]
;nom = file_ini_name or 1
;magicquotes = 1
;droitsActions = 1
connexionAuto = 1
auth = "light.auth.coord.ini.php"

[responses]
FoHtml = FoHtmlResponse
light = lightResponse
encodedJson=encodedJsonResponse

[simple_urlengine_entrypoints]
; paramètres pour le moteur d'url simple : liste des points d'entrées avec les actions
; qui y sont rattachées


; nom_script_sans_suffix = "liste de selecteur d'action séparé par un espace"
; selecteurs :
;   m~a@r    -> pour action "a" du module "m" répondant au type de requete "r"
;   m~*@r    -> pour toute action du module "m" répondant au type de requete "r"
;   @r       -> toute action de tout module répondant au type de requete "r"

light = "@classic"
xmlrpc = "@xmlrpc"
jsonrpc = "@jsonrpc"
rdf = "@rdf"