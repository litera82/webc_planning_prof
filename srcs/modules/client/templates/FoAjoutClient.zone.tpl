{literal}
<script type="text/javascript">
	DD_roundies.addRule('div.formevent', '5px');
    DD_roundies.addRule('input.boutonform', '5px');

	var autoCompleteJson= function(data){
		var parsed=[];
		for (var i=0; i<data.length;i++){
			var row=data[i];
			parsed.push({
				data: row,
				value: row["societe_zNom"],
				result: row["societe_zNom"]
			});
		}
		return parsed;
	}

	$(function(){ 
		var url=j_basepath + "index.php?module=client&action=FoClient:autocompleteSociete";
		$('#client_zSociete').autocomplete(url,{
			/*mustMatch : true,*/
			minChars: 0,
			autoFill: false,
			scroll: true,
			scrollHeight: 300,
			dataType: "json" ,
			parse : autoCompleteJson,
			formatItem: function(row) {
				return row["societe_zNom"];
			}
		}).result(function(event, row, formatted){	
			if (typeof(row) == 'undefined') {		
				$('#client_iSociete').val(0);		
			} else {
				$('#client_iSociete').val(row["societe_id"]);
				$('#client_zSociete').val(row["societe_zNom"]);
			}
		}).blur(function(){
			$(this).search();
		});


		$('.selectUtilisateurCreateurId').change (
			function (){
				if(confirm("Voulez-vous mettre à jours le contenu des mails ?"))
				{
					var iUtilisateurCreateurId = $('.selectUtilisateurCreateurId').val();
					var iClientId = $('#client_id').val();
					$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:refreshModelMailContainer", iUtilisateurCreateurId:iUtilisateurCreateurId, iClientId:iClientId}, function(resultat){
						var content_mail_auto = FCKeditorAPI.GetInstance('content_mail_auto') ;
						var content_mail_relance = FCKeditorAPI.GetInstance('content_mail_relance') ;
						var content_mail_changeprof = FCKeditorAPI.GetInstance('content_mail_changeprof') ;
						var content_mail_perso = FCKeditorAPI.GetInstance('content_mail_perso') ;
						content_mail_auto.SetHTML(resultat[0].modelmail_content); 
						content_mail_relance.SetHTML(resultat[1].modelmail_content); 
						content_mail_changeprof.SetHTML(resultat[2].modelmail_content); 
						content_mail_perso.SetHTML(resultat[3].modelmail_content); 
					});
				}
			}
		);


	$('.sendCoursByMail').click(
		function (){
			var iClientId = $(this).attr('stagiaireid');
			$('.title1h2').text("Envoyer la liste des cours"); 
			$('.title2h2').text("Nom : " + $(this).attr('nom') + " " + $(this).attr('prenom'));
			$('.title3h2').text("eMail : " + $(this).attr('email'));
			$('.nbcclass').val(0);
			$('#realClientId').val("");
			$('#realClientId').val(iClientId);
			return false;
		}
	);

	$('.sendMail').click(function(){
		var iClientId = $('#realClientId').val();
		if (iClientId > 0){
			var iNombre = $('#nbc').val();
			$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:sendCoursStagiaireByMail", iNombre:iNombre, iClientId:iClientId}, function(resultat){
				$('.pop-up').hide();
				$('#masque').hide();
				alert(resultat);
			});
		}
	});


		$('.resetModelMailContent').click(
			function (){
				var typeContent = $(this).attr('typeContent');
				var valueContent = $(this).attr('valueContent');

				if(confirm("Reset le contenu du mail"))
				{
					if (valueContent != 4){
						var iClientId = $('#client_id').val();
						$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:resetModelMailContainer", iClientId:iClientId, valueContent:valueContent}, function(resultat){
							var contentToReset = FCKeditorAPI.GetInstance(typeContent) ;
							contentToReset.SetHTML(resultat[0].modelmail_content); 
						});
					}else{
							var contentToReset = FCKeditorAPI.GetInstance(typeContent) ;
							contentToReset.SetHTML(''); 
					}
				}
			}
		);
		$('.submitForm1').click(
		function(){
			var form = document.getElementById('edit_form');
			var sendMail = $('#sendMail').is(':checked');
			var sendMailAuto = $('#sendMailAuto').is(':checked');
			var sendMailRelanceAuto = $('#sendMailRelanceAuto').is(':checked');
			var sendMailChangeProf = $('#sendMailChangeProf').is(':checked');
			var sendMailPerso = $('#sendMailPerso').is(':checked');
			var testLoginPwd = 0;
			if (sendMail || sendMailAuto || sendMailRelanceAuto || sendMailChangeProf || sendMailPerso){
				if ($('#client_zLogin').val() == "" || $('#client_zPass').val() == ""){
					testLoginPwd = 1;
				}
			}
			if (testLoginPwd == 0)
			{
				var isValid = tmt_validateForm(form);
				if(isValid){
					$('#edit_form').submit();
				}
			}else{
				alert("Si vous voulez envoyer un mail au stagiaire, merci de renseigner les champs login et mot de passe.");
			}
		});
	});
	function checkThis (val, checked){
		if (val == 1){
			if ($('#sendMailAuto').is(':checked')){
				$('#sendMailChangeProf').attr('checked', false);	
				$('#sendMailRelanceAuto').attr('checked', false);	
				$('#sendMailPerso').attr('checked', false);	
			}
		}else if (val == 2){
			if ($('#sendMailRelanceAuto').is(':checked')){
				$('#sendMailAuto').attr('checked', false);	
				$('#sendMailChangeProf').attr('checked', false);	
				$('#sendMailPerso').attr('checked', false);	
			}
		}else if(val == 3){
			if ($('#sendMailChangeProf').is(':checked')){
				$('#sendMailAuto').attr('checked', false);	
				$('#sendMailRelanceAuto').attr('checked', false);	
				$('#sendMailPerso').attr('checked', false);	
			}
		}else{
			if ($('#sendMailPerso').is(':checked')){
				$('#sendMailAuto').attr('checked', false);	
				$('#sendMailRelanceAuto').attr('checked', false);	
				$('#sendMailChangeProf').attr('checked', false);	
			}
		}
	}
</script>
{/literal}
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~FoClient:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
	<input type="hidden" name="iEvenementId" id="iEvenementId" value="{$iEvenementId}" />
	<input type="hidden" name="client_id" id="client_id" value="{$iClientId}" />
	<input type="hidden" name="id" id="id" value="{if isset($oClientsEnv->id)}{$oClientsEnv->id}{/if}" />
	<input type="hidden" name="urlRefreshModelMailContainer" id="urlRefreshModelMailContainer" value="{jurl 'client~FoClient:refreshModelMailContainer', array(), false}" />
	<!--input type="hidden" name="sendMail" id="sendMail" value="0" />
	<input type="hidden" name="sendMailAuto" id="sendMailAuto" value="0" /-->
	<input type="hidden" name="client_iSociete" id="client_iSociete" value="{if $iClientId > 0}{$oClient->client_iSociete}{/if}"/>

			<h2>Création / Modification de stagiaire {if $bEdit}<a target="_blank" href="{jurl 'evenement~FoEvenement:getEventListing', array('evenement_stagiaire'=>$iClientId, 'iCheckDate'=>1)}" title="Voir les évenements associés au stagiaire"><img src="{$j_basepath}design/front/images/design/calendar.png" alt="Voir les évenements associés au stagiaire" /></a>&nbsp;&nbsp;<a class="sendCoursByMail" stagiaireid="{$iClientId}" nom="{$oClient->client_zNom}" prenom="{$oClient->client_zPrenom}" email="{$oClient->client_zMail}" href="#" title="Lui envoyer sa liste de cours"><img src="{$j_basepath}design/front/images/design/email.png" alt="Lui envoyer sa liste de cours" height="20" width="20"/></a>{/if}</h2>
			<p class="clear">
				<label>Société *</label>
				{assign $zSocieteNom = ""}
				{foreach $toSociete as $oSociete}
					{if $oSociete->societe_id == $oClient->client_iSociete}
						{assign $zSocieteNom = $oSociete->societe_zNom}
					{/if}
				{/foreach}
				<input type="text" class="text" value="{$zSocieteNom}" name="client_zSociete" id="client_zSociete" tmt:required="true">
				&nbsp;&nbsp;
				<a href="#" class="create" title="Ajouter une société">
					<img src="{$j_basepath}design/front/images/design/buttons/plus.png" alt="Ajouter une société">
				</a> 
			</p>
			<p class="clear">
				<label>Professeur</label>
				<select class="text selectUtilisateurCreateurId" name="client_iUtilisateurCreateurId" id="client_iUtilisateurCreateurId" mt:required="true" tmt:invalidIndex="0">
					<option value="0">----------------------Séléctionner----------------------</option>
					{foreach $toProfesseur as $oProfesseur}
						<option value="{$oProfesseur->utilisateur_id}" {if $bEdit}{if $oClient->client_iUtilisateurCreateurId==$oProfesseur->utilisateur_id} selected=selected {/if}{else}{if $oProfesseur->utilisateur_id==$oUtilisateur->utilisateur_id} selected=selected {/if}{/if}>{$oProfesseur->utilisateur_zPrenom}&nbsp;{$oProfesseur->utilisateur_zNom}</option>
					{/foreach}
				</select>
			</p>
			<p class="civil clear">
				<label>Civilité</label>
				<select id="client_iCivilite" name="client_iCivilite" tmt:required="true" >
					<option value="1" {if $bEdit}{if $oClient->client_iCivilite == CIVILITE_HOMME}selected="selected"{/if}{else}selected="selected"{/if}>Mr</option>
					<option value="0" {if $bEdit}{if $oClient->client_iCivilite == CIVILITE_FEMME}selected="selected"{/if}{/if}>Mme</option>
					<option value="2" {if $bEdit}{if $oClient->client_iCivilite == CIVILITE_MADEMOISELLE}selected="selected"{/if}{/if}>Mlle</option>
				</select>
            </p>

			<p class="clear">
				<label>Nom *</label>
				<input class="text" type="text" name="client_zNom" id="client_zNom" value="{$oClient->client_zNom}" tmt:required="true"/>
			</p>
			<p class="clear">
				<label>Prénom </label>
				<input class="text" type="text" name="client_zPrenom" id="client_zPrenom" value="{$oClient->client_zPrenom}" />
			</p>

			<p class="clear">
				<label>Login </label>
				<input class="text" type="text" name="client_zLogin" id="client_zLogin" value="{$oClient->client_zLogin}" />
			</p>
			<p class="clear">
				<label>Mot de passe </label>
				<input class="text" type="text" name="client_zPass" id="client_zPass" value="{$oClient->client_zPass}" />
			</p>
			{*<!--<p class="clear">
				<label>Professeur *</label>
				<select id="client_iUtilisateurCreateurId" name="client_iUtilisateurCreateurId" tmt:required="true" >
					{foreach $toUtilisateur as $oUtilisateur}
						<option value="{$oUtilisateur->utilisateur_id}" {if $bEdit}{if $oClient->client_iUtilisateurCreateurId==$iUtilisateurId} selected=selected {/if}{else}{if $oUtilisateur->utilisateur_id == $iUtilisateurId}selected=selected{/if}{/if}>{$oUtilisateur->utilisateur_zNom}&nbsp;{$oUtilisateur->utilisateur_zPrenom}</option>
					{/foreach}
				</select>
            </p>-->*}
			<p class="type2 clear">
				<label>Téléphone </label>
				<input type="text" class="text" name="client_zTel" id="client_zTel" value="{$oClient->client_zTel}"/>
			</p>
			<p class="type2 clear">
				<label>Portable</label>
				<input class="text" type="text" name="client_zPortable" id="client_zPortable" value="{$oClient->client_zPortable}" />
			</p>
			<p class="type2 clear">
				<label>Mail *</label>
				<input class="text" type="text" name="client_zMail" id="client_zMail" value="{$oClient->client_zMail}" tmt:required="true"/>
			</p>
			<p class="clear">
				<label>Fonction </label>
				<input class="text" type="text" name="client_zFonction" id="client_zFonction" value="{$oClient->client_zFonction}" />
			</p>
			<p class="clear">
				<label>Rue </label>
				<input class="text" type="text" name="client_zRue" id="client_zRue" value="{$oClient->client_zRue}" />
			</p>
			<p class="type2 clear">
				<label>Ville </label>
				<input class="text" type="text" name="client_zVille" id="client_zVille" value="{$oClient->client_zVille}" />
			</p>
			<p class="type2 clear">
				<label>Code postal </label>
				<input class="text" type="text" name="client_zCP" id="client_zCP" value="{$oClient->client_zCP}" tmt:filter="postalcode" />
			</p>
			<p class="clear">	
				<label>Pays </label>
				<select class="text" name="client_iPays" id="client_iPays">
					<option value="0">----------------------Séléctionner----------------------</option>
					{foreach $toPays as $oPays}
						<option value="{$oPays->pays_id}" {if $bEdit}{if $oClient->client_iPays==$oPays->pays_id} selected=selected {/if}{else}{if $oPays->pays_id==64} selected=selected {/if}{/if}>{$oPays->pays_zNom}</option>
					{/foreach}
				</select>
			</p>
			<p class="clear">
				<label>Numéro Individu </label>
				<input class="text" type="text" name="client_iNumIndividu" id="client_iNumIndividu" value="{$oClient->client_iNumIndividu}" />
			</p>
			<p class="clear">
				<label>Statut *</label>
					<input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="1" {if $bEdit}{if $oClient->client_iStatut == STATUT_PUBLIE}checked="checked"{/if}{else}checked="checked"{/if} tmt:required="true"/><span>Afficher</span><input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="2" {if $bEdit}{if $oClient->client_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if}{/if} /><span>Ne pas afficher</span><input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="0" {if $bEdit}{if $oClient->client_iStatut == STATUT_DESACTIVE}checked="checked"{/if}{/if} /><span>Annuler</span>
			</p>
<br />
<br />
			<div class="enveloperiode clear" style="">
				<div class="validation clear">
					<fieldset class="heure">
						<legend>Fiche d'environnement du stagiaire</legend>
						<p class="clear">
							<label style=" float: left; text-align: left; width: 200px;">Bureau isolé</label>
							<span style="font-size:1.2em;">
							<select name="bureau" class="text" id="bureau" style="width:auto;">
								<option value="0" {if isset($oClientsEnv->bureau) && $oClientsEnv->bureau == 0}selected=selected{/if}>Non</option>
								<option value="1" {if isset($oClientsEnv->bureau) && $oClientsEnv->bureau == 1}selected=selected{/if}>Oui</option>
							</select>
							</span>
						</p>
						<p class="clear">
							<label style=" float: left; text-align: left; width: 200px;">Navigateur utilisé</label>
							<span style="font-size:1.2em;">
							<select name="navigateur" class="text" id="navigateur" style="width:auto;">
								<option value="0">------Séléctionner------</option>
								<option value="Mozilla Firefox"  {if isset($oClientsEnv->navigateur) && $oClientsEnv->navigateur == "Mozilla Firefox"}selected=selected{/if}>Mozilla Firefox</option>
								<option value="Internet Explorer" {if isset($oClientsEnv->navigateur) && $oClientsEnv->navigateur == "Internet Explorer"}selected=selected{/if}>Internet Explorer</option>
								<option value="Google Chrome" {if isset($oClientsEnv->navigateur) && $oClientsEnv->navigateur == "Google Chrome"}selected=selected{/if}>Google Chrome</option>
								<option value="Opera" {if isset($oClientsEnv->navigateur) && $oClientsEnv->navigateur == "Opera"}selected=selected{/if}>Opera</option>
								<option value="Safari" {if isset($oClientsEnv->navigateur) && $oClientsEnv->navigateur == "Safari"}selected=selected{/if}>Safari</option>
								<option value="Autres" {if isset($oClientsEnv->navigateur) && $oClientsEnv->navigateur == "Autres"}selected=selected{/if}>Autres</option>
							</select>
							</span>
						</p>
						<p class="clear">
							<label style=" float: left; text-align: left; width: 200px;">Telephone  fixe</label>
							<input type="text" class="text" id="telFixe" name="telFixe" value="{if isset($oClientsEnv->telFixe) && $oClientsEnv->telFixe != ''}{$oClientsEnv->telFixe}{/if}" style="width:200px;"/>
						</p> 
						<p class="clear">
							<label style=" float: left; text-align: left; width: 200px;">Telephone  mobile</label>
							<input type="text" class="text" id="telMobile" name="telMobile" value="{if isset($oClientsEnv->telMobile) && $oClientsEnv->telMobile != ''}{$oClientsEnv->telMobile}{/if}" style="width:200px;"/>
						</p> 
						<p class="clear">
							<label style=" float: left; text-align: left; width: 200px;">Skype</label>
							<input type="text" class="text" id="skype" name="skype" value="{if isset($oClientsEnv->skype) && $oClientsEnv->skype != ''}{$oClientsEnv->skype}{/if}" style="width:200px;"/>
						</p> 
						<p class="clear">
							<label style=" float: left; text-align: left; width: 200px;">Casque</label>
							<span style="font-size:1.2em;">
							<select name="casqueSkype" class="text" id="casqueSkype" style="width:auto;">
								<option value="0" {if isset($oClientsEnv->casqueSkype) && $oClientsEnv->casqueSkype == 0}selected=selected{/if}>Non</option>
								<option value="1" {if isset($oClientsEnv->casqueSkype) && $oClientsEnv->casqueSkype == 1}selected=selected{/if}>Oui</option>
							</select>
							</span>
						</p>
					</fieldset>
				</div>
			</div>
<br />
<br />
			<p class="clear">
				<label><input type="checkbox" title="Enregistrer et envoi de mail" onclick="#" id="sendMail" name="sendMail" value="1"></label>
				<span style="font-size:1.2em;font-weight: bold;">Enregistrer et envoi de mail</span>
			</p>
			<!--<p class="clear">
				<label><input type="checkbox" title="Enregistrer et proposer auto-planification" id="sendMailAuto" name="sendMailAuto" value="1" onclick="checkThis(1, this.checked);"></label>
				<span style="font-size:1.2em;font-weight: bold;">Enregistrer et proposer auto-planification</span>
			</p>-->
			<div class="modelMailContainer" id="modelMailContainer">
			{if $bEdit}
				{for $i=0; $i<sizeof($toModelMail); $i++}
					{if $toModelMail[$i]->modelmail_id == 1}{assign $zContent = 'content_mail_auto'}{/if}
					{if $toModelMail[$i]->modelmail_id == 2}{assign $zContent = 'content_mail_relance'}{/if}
					{if $toModelMail[$i]->modelmail_id == 3}{assign $zContent = 'content_mail_changeprof'}{/if}
					{if $toModelMail[$i]->modelmail_id == 4}{assign $zContent = 'content_mail_perso'}{/if}
					<p class="clear">
						<label>
							<input type="checkbox" title="{$toModelMail[$i]->modelmail_label}" id="{$toModelMail[$i]->modelmail_ident}" name="{$toModelMail[$i]->modelmail_ident}" value="{$toModelMail[$i]->modelmail_value}" onclick="checkThis({$toModelMail[$i]->modelmail_value}, this.checked);">
						</label>
						<span style="font-size:1.2em;font-weight: bold;">
							{$toModelMail[$i]->modelmail_label}
						</span>
						<br />
						<br />
						<label style="font-size:1.2em;font-weight: bold;">
							Objet
						</label>
						<span style="font-size:1.2em;font-weight: bold;">
							<textarea name="objet_{$toModelMail[$i]->modelmail_ident}" style="height:46px;">{$toModelMail[$i]->modelmail_objet}</textarea>
							{*<!--<a href="#" class="resetModelMailContent" typeContent="{$zContent}" valueContent="{$toModelMail[$i]->modelmail_value}" title="Reset le contenu du mail">
								<img src="{$j_basepath}design/front/images/design/pictos/reset.jpg" alt="Reset le contenu du mail">
							</a>-->*} 
						</span>
						<br />
						<br />
						<span style="font-size:1.2em;font-weight: bold;">{fckeditor $zContent, 'Default', '100%', 200, $toModelMail[$i]->modelmail_content}</span>
					</p>
				{/for}
			{/if}
			</span>
			</div>
			<div class="input" style="width:auto;">
				<a href="{jurl 'client~FoClient:clientListing', array(), false}"><input type="button" value="Annuler" class="boutonform" /></a>
				{if $bEdit}
				<input type="button" value="Modifier" class="boutonform submitForm1" />
				{else}
				<input type="button" value="Créer" class="boutonform submitForm1" />
				{/if}
				<!--<input type="button" value="Enregistrer et envoi de mail" class="boutonform longtext submitFormMail" />
				<input type="button" value="Enregistrer et proposer auto-planification" class="boutonform longtext submitFormAutoplannification" style="width:auto;"/>-->
			</div>
		<div class="input" style="width:480px;">
			<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
		</div>
	</form>

