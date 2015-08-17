{literal}
<script type="text/javascript">
$( function () {
	addEvent(window, "load", tmt_validatorInit);
	$('.submitFormSearch').click(
		function(){
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);
	$(".confirmDelete").click(
		function (){
			if (confirm('Etes vous vraiment sûr de vouloir supprimer ce stagiaire ?')){
				document.location.href = $(".confirmDelete").attr("urlDelete");	
			}
		}
	);
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
});
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

</script>
{/literal}
<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear" style="width:960px;padding: 5px 5px 5px;">
				<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~FoClient:getClientListing', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
					<input type="hidden" name="evenement_id" id="evenement_id" />
							<h2>Recherche de stagiaire</h2>
						<table cellspacing="0">
							<tbody>
								<tr>
									<td>
										<p class="clear">
											<label>Nom</label>
											<input class="text" type="text" name="client_zNom" id="client_zNom" value="{if isset($toParams[0]->nom) && $toParams[0]->nom != ''}{$toParams[0]->nom}{/if}" style="width:200px;"/>
										</p>
									</td>
									<td>
										<p class="clear">
											<label>Prénom</label>
											<input class="text" type="text" name="client_zPrenom" id="client_zPrenom" value="{if isset($toParams[0]->prenom) && $toParams[0]->prenom != ''}{$toParams[0]->prenom}{/if}" style="width:200px;"/>
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p class="clear">
											<label>Société</label>
											<input type="text" class="text" value="{if isset($toParams[0]->societe) && $toParams[0]->societe != ''}{$toParams[0]->societe}{/if}" name="client_zSociete" id="client_zSociete" style="width:200px;">
										</p>
									</td>
									<td>
										<p class="clear">
											<label>Professeur</label>
											<select class="text" name="client_iUtilisateurCreateurId" class="js-style-me" id="client_iUtilisateurCreateurId" style="width:200px;" >
											<option value="0">----------------------Séléctionner----------------------</option>
											{foreach $toProfesseur as $oProfesseur}
												<option value="{$oProfesseur->utilisateur_id}"
													{if isset ($toParams[0]->client_iUtilisateurCreateurId) && $toParams[0]->client_iUtilisateurCreateurId == $oProfesseur->utilisateur_id}selected="selected"{/if}	
												>{$oProfesseur->utilisateur_zNom} {$oProfesseur->utilisateur_zPrenom}</option>
											{/foreach}
											</select>
										</p>
									</td>
									</tr>
								<tr>
									<td>
										<p class="clear">
											<label>Stagiaire ayant effectué un test de début de stage</label>
											<select class="text" name="client_testDebut" class="js-style-me" id="client_testDebut" style="width:100px;">
												<option value="2">Afficher tout</option>
												<option value="1">Oui</option>
												<option value="0">Non</option>
											</select>
										</p>
									</td>
									<td>
										<p class="clear">
											<label>Afficher uniquement les stagiaires actifs</label>
											<input type="checkbox" name="stagiaire_actif" id="stagiaire_actif" value="1" {if isset($toParams[0]->stagiaire_actif) && $toParams[0]->stagiaire_actif == 1}checked="checked"{/if}/>
										</p>
									</td>

								</tr>
							</tbody>
						</table>

						<div class="input" style="width:280px;padding-top:1px;">
							<input type="button" value="Lister" class="boutonform submitFormSearch" style="padding: 2px 5px;" />
							<a href="{jurl 'client~FoClient:add', array('tEvent'=>null), false}" target="_blank"><input type="button" value="Ajouter un stagiaire" class="boutonform" style="padding: 2px 5px;" /></a>
						</div>
					</form>
			</div>
		</div>
		<div class="content">
			<div class="formevent listeclients clear" style="width:943px">
			<form id="edit_form" method="POST" enctype="multipart/form-data" tmt:validate="true" >
						<h2>Liste des stagiaires correspondant au critère de recherche</h2>
						{if $toParams[0]->nom != ""}<h3><span class="title">Nom : </span> <span>{$toParams[0]->nom}</span></h3>{/if}
						{if $toParams[0]->prenom != ""}<h3><span class="title">Prénom : </span> <span>{$toParams[0]->prenom}</span></h3>{/if}
						{if $toParams[0]->client_iUtilisateurCreateurId == 0}
							<h3 class="extra">Professeur : <span>Tous les professeurs</span></h3>
						{else}
							{if isset ($toStagiaire['toListes'][0])}
							<h3><span class="title">Professeur :</span> <span>{$toStagiaire['toListes'][0]->utilisateur_zNom}&nbsp;{$toStagiaire['toListes'][0]->utilisateur_zPrenom}</span></h3>
							{/if}
						{/if}	
						<h3><span class="title">Nombre de stagiaires trouvés :</span> <span>{$toStagiaire['iResTotal']}</span></h3>
						<h3 class="last"><span class="title">Stagiaires actifs :</span> <span>{if isset($toParams[0]->stagiaire_actif) && $toParams[0]->stagiaire_actif == 1}Oui{else}Non{/if}</span></h3>
						<div class="tabevent" style="">
							<table cellpadding="1" cellspacing="1" border="1">
								<tbody>
									<tr>
										<th class="col1" style="text-align:center;">Civilité&nbsp;&nbsp;&nbsp;</th>
										<th class="col2" style="text-align:center;">Nom</th>
										<th class="col3" style="text-align:center;">Prénom</th>
										<th class="col4" style="text-align:center;">Fonction</th>
										<th class="col5" style="text-align:center;">Email</th>
										<th class="col6" style="text-align:center;">Tél</th>
										<th class="col7" style="text-align:center;">Professeur</th>
										<th class="col8" style="text-align:center;width:155px">Actions</th>
									</tr>
									{assign $i = 1}
									{assign $iNbreStagiaire = sizeof($toStagiaire['toListes'])}
									{if $iNbreStagiaire > 50}
										<tr class="extra{$i++%2+1}">
											<td class="col1" colspan="8" style="text-align:center;">
												Trop de stagiaires trouvés, veuillez affiner votre recherche en ajoutant des critères !!! 
											</td>
										</tr>
									{else}
										{foreach $toStagiaire['toListes'] as $oStagiaire}
										<tr class="extra{$i++%2+1}">
											<td class="col1">
												<span>
													{if $oStagiaire->client_iCivilite == CIVILITE_FEMME}Mme{/if}
													{if $oStagiaire->client_iCivilite == CIVILITE_MADEMOISELLE}Mlle{/if}
													{if $oStagiaire->client_iCivilite == CIVILITE_HOMME}Mr{/if}
												</span>
											</td>
											<td class="col2"><a href="{jurl 'client~FoClient:add', array('iClientId'=>$oStagiaire->client_id)}">{$oStagiaire->client_zNom}</a></td>
											<td class="col3">{$oStagiaire->client_zPrenom}</td>
											<td class="col4">{$oStagiaire->client_zFonction}</td>
											<td class="col5">{$oStagiaire->client_zMail}</td>
											<td class="col6">{$oStagiaire->client_zTel}</td>
											<td class="col7">{$oStagiaire->utilisateur_zNom}&nbsp;{$oStagiaire->utilisateur_zPrenom}</td>
											<td class="col8" style="text-align:center;">
												<a class="sendCoursByMail" stagiaireid="{$oStagiaire->client_id}" nom="{$oStagiaire->client_zNom}" prenom="{$oStagiaire->client_zPrenom}" email="{$oStagiaire->client_zMail}" href="#" title="Lui envoyer sa liste de cours"><img src="{$j_basepath}design/front/images/design/email.png" alt="Lui envoyer sa liste de cours" height="20" width="20"/></a>&nbsp;&nbsp;<a target="_blank" href="{jurl 'evenement~FoEvenement:getEventListing', array('evenement_stagiaire'=>$oStagiaire->client_id, 'iCheckDate'=>1)}" title="Voir les évenements associés au stagiaire"><img src="{$j_basepath}design/front/images/design/calendar.png" alt="Voir les évenements associés au stagiaire" /></a>&nbsp;&nbsp;<a href="{jurl 'client~FoClient:add', array('iClientId'=>$oStagiaire->client_id)}" title="Détails du stagiaires"><img src="{$j_basepath}design/front/images/design/edit.png" alt="Voir les détails du stagiaire" /></a>{if isset($oStagiaire->bSupprimable) && $oStagiaire->bSupprimable}&nbsp;&nbsp;<a class="confirmDelete" href="#" urlDelete="{jurl 'client~FoClient:delete', array('iClientId'=>$oStagiaire->client_id, 'client_zNom'=>$toParams[0]->nom, 'client_zPrenom' => $toParams[0]->prenom, 'client_zSociete' => $toParams[0]->societe
												, 'client_iUtilisateurCreateurId' =>$toParams[0]->client_iUtilisateurCreateurId, 'client_testDebut' => $toParams[0]->client_testDebut, 'stagiaire_actif' => $toParams[0]->stagiaire_actif
												)}" title="Supprimer le stagiaire"><img src="{$j_basepath}design/front/images/design/pictos/delete.gif" alt="Supprimer le stagiaire" /></a>{/if}
											</td>
										</tr>
										{/foreach}
									{/if}
								</tbody>
							</table>
							<!-- begin Pagination -->
							<div class="navigPage clearfix" style="text-align:right;">
							{if $oNavBar->iNbPages > 1}
								{if $oNavBar->iPrevPage > 0}
									<a title="Précédent" href="{jurl 'client~FoClient:getClientListing', array('iCurrentPage' => $oNavBar->iPrevPage,'client_zNom'=>$toParams[0]->nom, 'client_zPrenom'=>$toParams[0]->prenom, 'client_zSociete'=>$toParams[0]->societe, 'client_iUtilisateurCreateurId'=>$toParams[0]->client_iUtilisateurCreateurId, 'client_testDebut'=>$toParams[0]->client_testDebut, 'stagiaire_actif'=>$toParams[0]->stagiaire_actif)}"> < </a>&nbsp;
								{/if}
								{if $oNavBar->iShowFirst > 0}
									<a title="Page {$oNavBar->iNbPages}" href="{jurl 'client~FoClient:getClientListing', array('iCurrentPage' => 1,'client_zNom'=>$toParams[0]->nom, 'client_zPrenom'=>$toParams[0]->prenom, 'client_zSociete'=>$toParams[0]->societe, 'client_iUtilisateurCreateurId'=>$toParams[0]->client_iUtilisateurCreateurId, 'client_testDebut'=>$toParams[0]->client_testDebut, 'stagiaire_actif'=>$toParams[0]->stagiaire_actif)}">1</a>&nbsp…&nbsp;
								{/if}

								{foreach $oNavBar->tiPages as $iPage}
									<a  title="Page {$iPage}" href="{jurl 'client~FoClient:getClientListing', array('iCurrentPage' => $iPage,'client_zNom'=>$toParams[0]->nom, 'client_zPrenom'=>$toParams[0]->prenom, 'client_zSociete'=>$toParams[0]->societe, 'client_iUtilisateurCreateurId'=>$toParams[0]->client_iUtilisateurCreateurId, 'client_testDebut'=>$toParams[0]->client_testDebut, 'stagiaire_actif'=>$toParams[0]->stagiaire_actif)}" 
										{if $iPage == $oNavBar->iCurrPage} class="activePage" {/if}
									>{$iPage}</a>
								{/foreach}

								{if $oNavBar->iShowLast > 0}
									&nbsp…&nbsp<a title="Page {$oNavBar->iNbPages}" href="{jurl 'client~FoClient:getClientListing', array('iCurrentPage' => $oNavBar->iNbPages,'client_zNom'=>$toParams[0]->nom, 'client_zPrenom'=>$toParams[0]->prenom, 'client_zSociete'=>$toParams[0]->societe, 'client_iUtilisateurCreateurId'=>$toParams[0]->client_iUtilisateurCreateurId, 'client_testDebut'=>$toParams[0]->client_testDebut, 'stagiaire_actif'=>$toParams[0]->stagiaire_actif)}">{$oNavBar->iNbPages}</a>&nbsp;
								{/if}

								{if $oNavBar->iNbPages > $oNavBar->iCurrPage}
									<a title="Suivant" href="{jurl 'client~FoClient:getClientListing', array('iCurrentPage' => $oNavBar->iNextPage,'client_zNom'=>$toParams[0]->nom, 'client_zPrenom'=>$toParams[0]->prenom, 'client_zSociete'=>$toParams[0]->societe, 'client_iUtilisateurCreateurId'=>$toParams[0]->client_iUtilisateurCreateurId, 'client_testDebut'=>$toParams[0]->client_testDebut, 'stagiaire_actif'=>$toParams[0]->stagiaire_actif)}"> > </a>
								{/if}
							{/if}
							</div>
							<!-- end Pagination -->
						</div>
						<div class="input">
							<input type="button" value="Imprimer" class="boutonform submitForm" onclick="window.print();"/>
							<a href="{jurl 'client~FoClient:add', array('tEvent'=>null), false}" target="_blank"><input type="button" value="Ajouter un stagiaire" class="boutonform" /></a>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>
{$footer}

<div class="pop-up" id="periodepop" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px;">
		<h2 class="title1h2"></h2>
        <a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
		<br /><h3 class="title2h2"></h3>
		<h3 class="title3h2"></h3>

		<div class="inner clear" style="padding-bottom: 0px;">
			<form id="edit_form_societe" url="{jurl 'client~FoSociete:save', array(), false}" style="text-align:center;" >
			<input type="hidden" name="realClientId" id="realClientId" value="">
						<p class="clear">Nombre de cours
							<select tmt:required="true" name="nbc" class="nbcclass" id="nbc" style="visibility: visible;width:50px;">
								<option value="0" selected="selected">Tous</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="30">30</option>
								<option value="40">40</option>
								<option value="50">50</option>
								<option value="60">60</option>
								<option value="70">70</option>
								<option value="80">80</option>
								<option value="90">90</option>
								<option value="100">100</option>
							</select> 
							<br />
							<br />
						</p>
						<div class="input">
							<input type="button" value="Envoyer" class="boutonform sendMail" />
						</div>
				</form>
		</div>
</div>
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>