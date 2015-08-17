;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule = "accueil"
startAction = "accueilFo:index"

[urlengine]
; name of url engine :  "simple" or "significant"
engine        = simple
;engine        = significant

defaultEntrypoint= ajax
entrypointExtension= .php

[coordplugins]
;nom = file_ini_name or 1
;magicquotes = 1
connexionAuto = 1
auth = "fo.auth.coord.ini.php"

[responses]
htmlFo = htmlFoResponse
encodedJson=encodedJsonResponse

[simple_urlengine_entrypoints]
; paramètres pour le moteur d'url simple : liste des points d'entrées avec les actions
; qui y sont rattachées

ajax = "@classic"
xmlrpc = "@xmlrpc"
jsonrpc = "@jsonrpc"
rdf = "@rdf"