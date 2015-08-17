;<?php die(''); ?>
locale = "fr_FR"
charset = "UTF-8"

;for security reasons , don't remove or modify the first line

startModule = "admin"
startAction = "administrateurs:index"

[urlengine]
; name of url engine :  "simple" or "significant"
engine			= simple
;engine			= significant

defaultEntrypoint= admin
entrypointExtension= .php

[coordplugins]
;nom = file_ini_name or 1
;magicquotes = 1
connexionAuto = 1
auth = "auth.coord.ini.php"
;droitsActions = 1

[responses]
BoHtml = BoHtmlResponse
encodedJson=encodedJsonResponse

[simple_urlengine_entrypoints]
; paramètres pour le moteur d'url simple : liste des points d'entrées avec les actions
; qui y sont rattachées


; nom_script_sans_suffix = "liste de selecteur d'action séparé par un espace"
; selecteurs :
;   m~a@r    -> pour action "a" du module "m" répondant au type de requete "r"
;   m~*@r    -> pour toute action du module "m" répondant au type de requete "r"
;   @r       -> toute action de tout module répondant au type de requete "r"


admin = "@classic"
xmlrpc = "@xmlrpc"
jsonrpc = "@jsonrpc"
rdf = "@rdf"

[typeOption]
testDroit=1
naclAction=17
naclRessourceType=16
module=option
nombreParPage=15
menu=3
sousMenu=0
titreListe=Gestion des types d'options
sousTitreListe=Liste des types d'options