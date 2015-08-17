;<?php die(''); ?>
;for security reasons , don't remove or modify the first line
;this file doesn't list all possible properties. See lib/jelix/core/defaultconfig.ini.php for that

locale = "fr_FR"
charset = "UTF-8"

; see http://www.php.net/manual/en/timezones.php for supported values
timeZone = "Europe/Paris"

checkTrustedModules = on

; list of modules : module,module,module
trustedModules = jelix,jauth,nacl2,commun,admin,administrateur,evenement,jelix_calendar,typeEvenement,utilisateurs,client,light,auto,logevent,stagiaire 

pluginsPath = lib:jelix-plugins/,app:plugins/
;modulesPath = lib:jelix-modules/,app:modules/,/var/www/html_php5/jelix/neov/neov-modules/
modulesPath = lib:jelix-modules/,app:modules/


theme = default

enableOldActionSelector = 

[coordplugins]
;nom = nom_fichier_ini
magicquotes = 0


[responses]
;html=myHtmlResponse
BoHtml = BoHtmlResponse
FoHtml = FoHtmlResponse
encodedJson=encodedJsonResponse

[error_handling]
messageLogFormat = "%date%\t[%code%]\t%msg%\t%file%\t%line%\n"
logFile = error.log
email = root@localhost
emailHeaders = "Content-Type: text/plain; charset=UTF-8\nFrom: webmaster@yoursite.com\nX-Mailer: Jelix\nX-Priority: 1 (Highest)\n"
quietMessage="Une erreur technique est survenue. Désolé pour ce désagrément."

; mots clés que vous pouvez utiliser : ECHO, ECHOQUIET, EXIT, LOGFILE, SYSLOG, MAIL, TRACE
default      = ECHO TRACE LOGFILE EXIT
error        = ECHO TRACE LOGFILE EXIT
warning      = ECHO TRACE LOGFILE
notice       = ECHO TRACE LOGFILE
strict       = ECHO TRACE LOGFILE
; pour les exceptions, il y a implicitement un EXIT
exception    = ECHO TRACE LOGFILE



[compilation]
checkCacheFiletime  = on
force  = off

[urlengine]
; name of url engine :  "simple" or "significant"
engine        = simple
;engine        = significant

; this is the url path to the jelix-www content (you can found this content in lib/jelix-www/)
; because the jelix-www directory is outside the yourapp/www/ directory, you should create a link to
; jelix-www, or copy its content in yourapp/www/ (with a name like 'jelix' for example)
; so you should indicate the relative path of this link/directory to the basePath, or an absolute path.
jelixWWWPath = "jelix/"


; enable the parsing of the url. Set it to off if the url is already parsed by another program
; (like mod_rewrite in apache), if the rewrite of the url corresponds to a simple url, and if
; you use the significant engine. If you use the simple url engine, you can set to off.
enableParser = on

multiview = off

; basePath corresponds to the path to the base directory of your application.
; so if the url to access to your application is http://foo.com/aaa/bbb/www/index.php, you should
; set basePath = "/aaa/bbb/www/". 
; if it is http://foo.com/index.php, set basePath="/"
; Jelix can guess the basePath, so you can keep basePath empty. But in the case where there are some
; entry points which are not in the same directory (ex: you have two entry point : http://foo.com/aaa/index.php 
; and http://foo.com/aaa/bbb/other.php ), you MUST set the basePath (ex here, the higher entry point is index.php so
; : basePath="/aaa/" )
basePath = "/webcalendar-prof/srcs/www/"


defaultEntrypoint= index

entrypointExtension= .php

; leave empty to have jelix error messages
notfoundAct = "commun~communFo:notFound"
;notfoundAct = "jelix~error:notfound"

; liste des actions requerant https (syntaxe expliquée dessous), pour le moteur d'url simple
simple_urlengine_https =


[simple_urlengine_entrypoints]
; paramètres pour le moteur d'url simple : liste des points d'entrées avec les actions
; qui y sont rattachées


; nom_script_sans_suffix = "liste de selecteur d'action séparé par un espace"
; selecteurs :
;   m~a@r    -> pour action "a" du module "m" répondant au type de requete "r"
;   m~*@r    -> pour toute action du module "m" répondant au type de requete "r"
;   @r       -> toute action de tout module répondant au type de requete "r"

index = "@classic"
;xmlrpc = "@xmlrpc"
;jsonrpc = "@jsonrpc"
;rdf = "@rdf"
;auto = "@classic"


[logfiles]
default=messages.log
debug=error.log

[mailer]
webmasterEmail = root@localhost
webmasterName =

; how to send mail : "mail" (mail()), "sendmail" (call sendmail), or "smtp" (send directly to a smtp)
mailerType = smtp
; Sets the hostname to use in Message-Id and Received headers
; and as default HELO string. If empty, the value returned
; by SERVER_NAME is used or 'localhost.localdomain'.
hostname =
sendmailPath = "/usr/sbin/sendmail"

; if mailer = smtp , fill the following parameters

; SMTP hosts.  All hosts must be separated by a semicolon : "smtp1.example.com:25;smtp2.example.com"
;smtpHost = "localhost"
smtpHost = "se294.nfrance.com"
; default SMTP server port
smtpPort = 25
; SMTP HELO of the message (Default is hostname)
smtpHelo =
; SMTP authentication
smtpAuth = on
smtpUsername = smtp1
smtpPassword = qi7cg9bu
; SMTP server timeout in seconds
smtpTimeout = 10


[acl]
driver = db


[sessions]
; to disable sessions, set the following parameter to 0
start = 1
; You can change the session name by setting the following parameter (only accepts alpha-numeric chars) :
; name = "mySessionName"
; Use alternative storage engines for sessions
;
; usage :
;
; storage = "files"
; files_path = "app:var/sessions/"
;
; or
;
; storage = "dao"
; dao_selector = "jelix~jsession"
; dao_db_profile = ""

[zones]
disableCache = on