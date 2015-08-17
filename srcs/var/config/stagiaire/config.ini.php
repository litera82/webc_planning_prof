;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule = "stagiaire"
startAction = "default:index"


defaultEntrypoint= stag
entrypointExtension= .php

[coordplugins]
;nom = file_ini_name or 1
magicquotes = 0
;droitsActions = 1
connexionAuto = 1
auth = "stagiaire.auth.coord.ini.php"

[responses]
FoHtml = FoHtmlResponse
encodedJson=encodedJsonResponse
stag=stagiaireResponse

[simple_urlengine_entrypoints]
; paramètres pour le moteur d'url simple : liste des points d'entrées avec les actions
; qui y sont rattachées


; nom_script_sans_suffix = "liste de selecteur d'action séparé par un espace"
; selecteurs :
;   m~a@r    -> pour action "a" du module "m" répondant au type de requete "r"
;   m~*@r    -> pour toute action du module "m" répondant au type de requete "r"
;   @r       -> toute action de tout module répondant au type de requete "r"

stag = "@classic"
xmlrpc = "@xmlrpc"
jsonrpc = "@jsonrpc"
rdf = "@rdf"

[mailer]
webmasterEmail = t.randriambola@gmail.com
webmasterName = Toky

; how to send mail : "mail" (mail()), "sendmail" (call sendmail), or "smtp" (send directly to a smtp)
mailerType = mail