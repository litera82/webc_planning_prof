{literal}
<script type="text/javascript">
$(function(){ 
	$('#groupe_id').change(
		function (){
			var groupe_id = $('#groupe_id').val(); 
			var zUrl = $('#urlChargeProfParGroupId').val(); 
			if (groupe_id > 0){
					$.getJSON(zUrl , 
					{
						groupe_id:groupe_id
					},
					function(datas){
						var html = '<option value="0">--------------------Tous--------------------<\/option>';
						console.log(datas);
						for(i=0; i<datas.length; i++){
							html += '<option value="' + datas[i]["utilisateur_id"]+'"  >' + datas[i]["utilisateur_zNom"] + ' ' + datas[i]["utilisateur_zPrenom"] + '<\/option>';
						}
						$('#professeurs').html(html);
						//$('#professeurs').attr({'disabled':''}) ;
				 });				
			}
		}
	);
});
function submitFormRechercheApprocheListeDispo(){
	var fin = $('#dtcm_event_rdv1').val();
	var debut = $('#dtcm_event_rdv').val();
	var zUrl = $('#urlCalculDateDiff').val();
	if($('#dtcm_event_rdv1').val()!=""){
		$.ajax({
			type: "POST",
			url: zUrl,
			data: {
				'zDebut':debut,
				'zFin':fin
			},
			success: function(response){
				if (response < 0)
				{
					alert('La date de début doit être antérieur à la date de fin');
				}else{
					$('#edit_form').attr({'action':$('#action2').val()}) ;
					$('#edit_form').submit();
				}
			}
		 });
	}else{
		$('#edit_form').attr({'action':$('#action2').val()}) ;
		$('#edit_form').submit();
	}
	return false;
}
function addEventEventlisting (){
	document.location.href = $('#urlAddEvent').val() + "&prec=2&debut="+$('#dtcm_event_rdv').val()+"&fin="+$('#dtcm_event_rdv1').val();
}
</script>
{/literal}
<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear" style="width:960px;padding: 5px 5px 5px;">
				<form id="edit_form" action="#" method="POST" enctype="multipart/form-data" tmt:validate="true">
					<input type="hidden" name="action2" id="action2" value="{jurl 'evenement~FoEvenement:getEventListingDispo', array(), false}"/>
					<input type="hidden" name="approcheParListeGetEvent" id="approcheParListeGetEvent" value="{jurl 'evenement~FoEvenement:approcheParListeGetEvent'}" />
					<input type="hidden" name="urlAddEvent" id="urlAddEvent" value="{jurl 'evenement~FoEvenement:add', array(), false}"/>
					<input type="hidden" name="evenement_id" id="evenement_id" value=""/>
					<input type="hidden" name="urlCalculDateDiff" id="urlCalculDateDiff" value="{jurl 'evenement~FoEvenement:calculDateDiff'}"/>
					<input type="hidden" name="urlChargeProfParGroupId" id="urlChargeProfParGroupId" value="{jurl 'evenement~FoEvenement:chargeProfParGroupId'}"/>

					<h2>Recherche d'évènement</h2>
					<table cellspacing="0">
						<tbody>
							<tr>
								<td>	
									<p class="civil clear">
										<label style="width:200px;">Date du</label>
										<input type="text" class="date text" id="dtcm_event_rdv" name="dtcm_event_rdv" style="width:100px;" value="{if isset ($toParams[0]->zDateDebut)}{$toParams[0]->zDateDebut}{/if}" readonly="readonly"/>
									</p>
								</td>
								<td>
									<p class="civil clear">
										<label style="width:200px;">Jusqu'au</label>
										<input type="text" class="date text" id="dtcm_event_rdv1" name="dtcm_event_rdv1" style="width:100px;" value="{if isset ($toParams[0]->zDateFin)}{$toParams[0]->zDateFin}{/if}" readonly="readonly"/>
									</p>
								</td>
							</tr>
							{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}
							<tr>
								<td>
								<p class="clear">
									<label style="width:200px;">Groupes de prof</label>
									<select class="text"  style="width:200px;" name="groupe_id" id="groupe_id">
										<option value="0">--------------------Tous--------------------</option>
										{foreach $toGroupe as $oGroupe}
											<option value="{$oGroupe->groupe_id}" {if isset ($toParams[0]->groupe_id) && $toParams[0]->groupe_id == $oGroupe->groupe_id} selected="selected"{/if}>{$oGroupe->groupe_libelle}</option>
										{/foreach}
									</select>
								</p>
								</td>
								<td>
								<p class="clear">
									<label style="width:200px;">Proffesseur</label>
									<select class="text" style="width:200px;" name="professeurs" id="professeurs" >

										<option value="0">--------------------Tous--------------------</option>
										{foreach $toUtilisateur as $oTmpUtilisateur}
											<option value="{$oTmpUtilisateur->utilisateur_id}" {if isset ($toParams[0]->professeurs) && $toParams[0]->professeurs == $oTmpUtilisateur->utilisateur_id} selected="selected"{/if}>{$oTmpUtilisateur->utilisateur_zPrenom} {$oTmpUtilisateur->utilisateur_zNom}</option>
										{/foreach}
									</select>
								</p>
								</td>
							</tr>
							{/if}
						</tbody>
					</table>
					<div class="input" style="width:280px;padding-top:1px;">
						<input type="button" value="Ajouter un évènement" class="boutonform" onclick="addEventEventlisting();" style="padding: 2px 5px;"/>
						<input type="button" value="Rechercher" class="boutonform" onclick="submitFormRechercheApprocheListeDispo();"   style="padding: 2px 5px;"/>
					</div>
					<div class="input" style="width:480px;">
						<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
					</div>
				</form>
			</div>
		</div>
		<div class="content">
			<div class="formevent listeclients clear" style="width:943px;">
			<input type="hidden" value="" id="eventToDelete" name="eventToDelete"/>
			<input type="hidden" value="{$toParams[0]->zDateDebut}" id="zDateDebut" name="zDateDebut"/>
			<input type="hidden" value="{$toParams[0]->zDateFin}" id="zDateFin" name="zDateFin"/>
			<input type="hidden" value="{$toParams[0]->iTypeEvenement}" id="iTypeEvenement" name="iTypeEvenement"/>
			<input type="hidden" value="{$toParams[0]->iStagiaire}" id="iStagiaire" name="iStagiaire"/>
			<input type="hidden" value="{$toParams[0]->evenement_origine}" id="evenement_stagiaire " name="evenement_origine"/>
			<input type="hidden" value="{$toParams[0]->iCheckDate}" id="iCheckDate" name="iCheckDate"/>
			<h2>Liste d'évènements {if $oUtilisateur->utilisateur_iTypeId != TYPE_UTILISATEUR_ADLINISTRATEUR}pour {$oUtilisateur->utilisateur_zNom} {$oUtilisateur->utilisateur_zPrenom}{/if}</h2>
			{*<!--{if isset($toParams[0]->iCheckDate) && $toParams[0]->iCheckDate == 0}
			<h3><span class="title">De</span> <span>{$toParams[0]->zDateDebut}</span> <span class="title">à</span> <span>{$toParams[0]->zDateFin}</span></h3>
			{/if}
			{if isset($toParams[0]->evenement_origine) && $toParams[0]->evenement_origine != 0}
				{if $toParams[0]->evenement_origine == 1}
				<h3><span class="title">Origine : </span><span>Auto-planification</span></h3>
				{else}
				<h3><span class="title">Origine : </span><span>Agenda</span></h3>
				{/if}
			{else}
				<h3><span class="title">Origine : </span><span>Tous</span></h3>
			{/if}	
			
			{if $toParams[0]->iTypeEvenement == 0}
				<h3><span class="title">Types d'événement : </span><span>Tous les Types</span></h3>
			{else}
				<h3><span class="title">Types d'événement : </span><span>{if isset($toEvenement[0]->typeevenements_zLibelle)}{$toEvenement[0]->typeevenements_zLibelle}{else}{$toTypeEvenementSelected[0]->typeevenements_zLibelle}{/if}</span></h3>
			{/if}		
			<h3 class="last"><span class="title">Nombre d'événement trouvés :</span> <span>{$iResTotal}</span></h3>-->*}
			<div id="accordion" style="font-family:Arial,sans-serif;">
			{foreach $toEvenement as $oEvent}
				<div>
					<h3 style="font-size:1em;">
						<a href="#" style="color:#E17009{*$oEvent->typeevenements_zCouleur*}">
							{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}{$oEvent->utilisateur_zPrenom}&nbsp;{$oEvent->utilisateur_zPrenom}&nbsp;- &nbsp;{/if}{$oEvent->evenement_zDateJoursDeLaSemaine}&nbsp;{$oEvent->evenement_zDateHeureDebut|date_format:'%d/%m/%Y'} à {$oEvent->evenement_zDateHeureDebut|date_format:'%H:%M'}
						</a>
					</h3>
					<div>
						<p>
						  Pour affecter une plage horaire à un stagiaire, cliquez sur le bouton "<strong style="color:#1D5987;">Affecter à un stagiaire</strong>".
							<br />
							<br />
							{if isset($oEvent->evenement_zDescription) && $oEvent->evenement_zDescription != ""}
							<b>Description</b><br />
							{$oEvent->evenement_zDescription}
							{/if}
						</p>
						<button class="showpopupAffestation" eventId="{$oEvent->evenement_id}" dateEvent="{$oEvent->evenement_zDateHeureDebut|date_format:'%d/%m/%Y'}" heureEvent="{$oEvent->evenement_zDateHeureDebut|date_format:'%H:%M'}" href="#">Affecter à un stagiaire</button>
					</div>
				</div>
			{/foreach}
			</div>
	</div>
</div>
{$footer}


<div class="pop-up formevent clear"  id="popupAffestation" style="background-color:#E9E9E9; border:1px solid #E1E1E1; width:600px">
	<form id="edit_form" name="edit_form" action="{jurl 'evenement~FoEvenement:saveAffectation', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true">
	<input type="hidden" name="evenement_id" id="evenement_id" value="" />

	<input type="hidden" name="criteria_datedebut" id="criteria_datedebut" value="" />
	<input type="hidden" name="criteria_datefin" id="criteria_datefin" value="" />
	
	<input type="hidden" name="evenement_origine" id="evenement_origine" value="" />
	<input type="hidden" name="sendMail" id="sendMail" value="0" />
	<input type="hidden" name="evenement_iPriorite" id="evenement_iPriorite" value="1" />
	<input type="hidden" name="iAffichage" id="iAffichage" value="" />
	<input type="hidden" name="zDate" id="zDate" value="" />
	<input type="hidden" class="text" name="evenement_zLibelle" id="evenement_zLibelle" value=""> 
	<input type="hidden" name="evenement_iContactTel" id="evenement_iContactTel" value="0" />
	<input type="hidden" name="finPeriodiciteOccurence" id="finPeriodiciteOccurence" value="0" />
	<input type="hidden" name="periodiciteMensuel1" id="periodiciteMensuel1" value="0" />
	<input type="hidden" name="evenement_zDateHeureSaisie" id="evenement_zDateHeureSaisie" value="" />
	<input type="hidden" name="evenement_iTypeEvenementId" id="evenement_iTypeEvenementId" class="evenement_iTypeEvenementId" value="" />
	<input type="hidden" name="x" id="x" value="0" />

	<h2>Afféctation d'un évènement à un stagiaire</h2>
	<a href="#" title="Fermer" class="fermer"><img src="{$j_basepath}design/front/images/design/close.png" alt="fermer"></a>
	<p class="clear">
	</p>
	<p class="clear">
		<label>Types d’évènement *</label>
		{foreach $toTypeEvenement as $oTypeEvenement}
			<input type="hidden" id="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" name="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" value="{$oTypeEvenement->typeevenements_iStagiaireActif}" />
		{/foreach}
		<select name="evenement_iTypeEvenementId" class="text" id="evenement_iTypeEvenementId" tmt:invalidindex="0" tmt:required="true" >
			<option value="0">----------------------Séléctionner----------------------</option>
			{foreach $toTypeEvenement as $oTypeEvenement}
				<option value="{$oTypeEvenement->typeevenements_id}">{$oTypeEvenement->typeevenements_zLibelle}</option>
			{/foreach}
		</select>
	</p>
	<p class="clear">
		<label>Description</label>
		<textarea style="height:auto" name="evenement_zDescription" id="evenement_zDescription"></textarea>
	</p> 
	<p class="clear">
		<label>Stagiaire</label>
		<input type="hidden" name="evenement_iStagiaire" id="evenement_iStagiaire" value="" />
		<input style="width:296px;" type="text" class="text" name="evenement_zStagiaire" id="evenement_zStagiaire" value="" />
		&nbsp;<a href="#" title="Rechercher" id="rechercherStagiaire">
			<img src="{$j_basepath}design/front/images/design/rechercher.png" alt="Rechercher" />
		</a>
	</p>
	<p class="clear" id="div-stagiaire-liste">
		<label for="dtcm_event_project">&nbsp;</label>
		<select style="width:400px;" name="stagiaire-liste" id="stagiaire-liste" size="10" url="">
			<option></option>
		</select>
	</p>
	<p class="clear" id="p-txtmail"> 
		<label>Email</label>
		<input type="text" name="txtmail" id="txtmail" class="text" readonly="readonly"/>
	</p> 
	<p class="clear" id="p-txtphone"> 
		<label>Téléphone</label>
		<input type="text" name="txtphone" id="txtphone" class="text" readonly="readonly"/>
	</p> 
	<p class="clear" id="p-txtsociete"> 
		<label>Société</label>
		<input type="text" name="txtsociete" id="txtsociete" class="text" readonly="readonly"/>
	</p> 
	<p class="clear" id="p-txtville"> 
		<label>Adresse</label>
		<input type="text" name="txtville" id="txtville" class="text" readonly="readonly"/>
	</p> 

	<p class="rdv clear">
		<label>Rendez vous *</label>
		<input type="text" class="daterdv text" id="dtcm_event_rdv_affectation" name="dtcm_event_rdv" value="" tmt:required="true"/>
	</p> 
	<p class="rdv clear">
		<label>Tel. pour ce jour</label>
		<input type="text" name="evenement_zContactTel" id="evenement_zContactTel" class="text" value=""/>
		<input type="button" value="C'est le stagiaire qui appelle" id="appelStagiaire" class="boutonforms" />
	</p>
	<p class="duree clear">
		<label>Durée</label>
		<select style="width:120px;"name="evenement_iDuree" class="text" id="evenement_iDuree">
		</select>
	</p>
	<p class="rappel clear">
		<label>Rappel</label>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="1"/>
		<span>Oui</span>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="0" checked="checked"/>
		<span>Non</span>
		<input type="text" class="text" name="evenement_iRappelJour" id="evenement_iRappelJour"/>
		<span class="text">jours</span>
		<input type="text" class="text" name="evenement_iRappelHeure" id="evenement_iRappelHeure"/>
		<span class="text">heures</span>
		<input type="text" class="text" name="evenement_iRappelMinute" id="evenement_iRappelMinute"/>
		<span class="text">minutes avant</span>
	</p>
	<p class="statut clear">
		<label>Statut *</label>
		<input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="1" tmt:required="true" checked="checked"/><span>Afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="2"/><span>Ne pas afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="0" /><span>Annuler</span>
	</p>

<!--periodicite-->
	<div class="enveloperiode clear">
		<p class="master clear">
			<label><strong>Périodicité</strong></label>
			<input type="checkbox" name="evenement_iDupliquer" id="evenement_iDupliquer" value="1" />
		</p>
		<div class="periodicite clear" style="display:none;">
		<fieldset class="heure">
    <legend>Heure du rendez vous</legend>
		<p class="clear">
			<label>Debut</label>
			<select style="width:120px;"name="evenement_heureDebutRendezVous" class="text" id="evenement_heureDebutRendezVous">
				<option value="0">---------Heure---------</option>
				{foreach $toPeriodicite as $oPeriodicite}
				<option value="{$oPeriodicite}">{$oPeriodicite}</option>
				{/foreach}
			</select>
		<!--/p>
		<p class="clear"-->
			<label>Durée</label>
			<select style="width:120px;"name="evenement_heureDureeRendezVous" class="text" id="evenement_heureDureeRendezVous">
				<option value="0">---------Durée---------</option>
				{foreach $toDurePeriodicite as $oDurePeriodicite}
				<option value="{$oDurePeriodicite}">{$oDurePeriodicite}</option>
				{/foreach}
			</select>
		</p>
		</fieldset>
    <fieldset class="fieldperiode">
    	<legend>Periodicité</legend>
		<div class="leftperiode">
    <p class="clear">
    	 <label>Quotidienne</label>
			<input type="radio" name="choixperiode" id="periodicite" class="radio choixperiode" value="1" onclick="selectPeriodiciteQuotidienne()"/>
		</p>
		<p class="clear">
			<label>Hebdomadaire</label>
			<input type="radio" name="choixperiode" id="periodicite" class="radio choixperiode" value="2" checked="checked" onclick="selectPeriodiciteHebdo()"/>
    </p>
		<p class="clear">
			<label>Mensuelle</label>
			<input type="radio" name="choixperiode" id="periodicite" class="radio choixperiode" value="3" onclick="selectPeriodiciteMensuel()"/>
		</p>
    </div>
    <div class="rightperiode">
		<div class="clear evenement_periodiciteQuotidienne">
			<p class="clear">
        <label>Tous les</label>
         <select name="evenement_periodiciteQuotidienne" id="evenement_periodiciteQuotidienne">
          {for $i=1; $i<=7; $i++}
            <option value="{$i}">{$i}</option>
          {/for}
        </select>
        <span> jours</span>
      </p>
		</div>
		<div class="clear evenement_periodiciteHebdomadaire">
    	<p class="clear tilte">
      	<span>Tous les</span>
        <select name="evenement_periodiciteHebdomadaire" id="evenement_periodiciteHebdomadaire">
				{for $i=1; $i<=4; $i++}
					<option value="{$i}">{$i}</option>
				{/for}
				</select>
        <span>semaine(s) le </span>
      </p>
      <p class="jour clear">
      	<input type="checkbox" name="evenement_iLundi" id="evenement_iLundi" value="1" />
        <span>Lundi</span>
        <input type="checkbox" name="evenement_iMardi" id="evenement_iMardi" value="1" />
        <span>Mardi</span>
        <input type="checkbox" name="evenement_iMercredi" id="evenement_iMercredi" value="1" />
        <span>Mercredi</span>
        <span class="extra clear">
        <input type="checkbox" name="evenement_iJeudi" id="evenement_iJeudi" value="1" />
        <span>Jeudi</span>
        <input type="checkbox" name="evenement_iVendredi" id="evenement_iVendredi" value="1" />
        <span>Vendredi</span>
        </span>
      </p>
		</div>
		
		<div class="clear evenement_periodiciteMensuel1">
    	<p class="top clear">
        <input type="radio" class="radio selectEvenement_periodiciteMensuel1" name="evenement_periodiciteMensuel1" id="evenement_periodiciteMensuel1" value="1" onclick="getVal1(1);" checked="checked"/>
        <span>Le</span>
        <select name="evenement_periodiciteMensuel11" id="evenement_periodiciteMensuel11">
          {for $i=1; $i<=31; $i++}
            <option value="{$i}">{$i}</option>
          {/for}
           </select>
        <span>tous les</span> 
        <select name="evenement_periodiciteMensuel12" id="evenement_periodiciteMensuel12">
          {for $i=1; $i<=12; $i++}
            <option value="{$i}">{$i}</option>
          {/for}
        </select> 
        <span>mois</span>
      </p>
      <p class="bottom clear">
      <input type="radio" class="radio selectEvenement_periodiciteMensuel2" name="evenement_periodiciteMensuel1" id="evenement_periodiciteMensuel1" value="2" onclick="getVal1(2);"/>
			<span>Le</span> 
			<select name="evenement_periodiciteMensuel21" class="extra" id="evenement_periodiciteMensuel21">
				<option value="1">Premier</option>
				<option value="2">Deuxième</option>
				<option value="3">Troisième</option>
				<option value="4">Quatrième</option>
				<option value="5">Dernier</option>
			</select>
			<select name="evenement_periodiciteMensuel22" class="extra" id="evenement_periodiciteMensuel22">
				<option value="1">Lundi</option>
				<option value="2">Mardi</option>
				<option value="3">Mercredi</option>
				<option value="4">Jeudi</option>
				<option value="5">Vendredi</option>
			</select>
			<span>tous les</span> 
			<select name="evenement_periodiciteMensuel23" id="evenement_periodiciteMensuel23">
				{for $i=1; $i<=12; $i++}
					<option value="{$i}">{$i}</option>
				{/for}
			</select> 
      <span>mois</span>
      </p>
		</div>
    </div>
    </fieldset>
    <fieldset class="plage">
    <legend>Plage de periodicité</legend>
    <div class="plageleft" style="width:170px;">
      <p class="clear">
        <label>Debut</label>
        <input type="text" id="dtcm_event_rdv_periodicite" name="dtcm_event_rdv_periodicite" class="datePeriodicite text" style="width:90px;"/>
      </p>
    </div>
    <div class="plageright">
      <p class="clear">
        <input type="radio" class="radio plagePeriodicite1" name="evenement_finPeriodiciteOccurence" id="evenement_finPeriodiciteOccurence" value="1" onclick="getVal(1);"/> 
        <span>Fin apres</span>
        <input type="text" class="text" name="evenement_finPeriodiciteOccurence1" tmt:filters="numbersonly" id="evenement_finPeriodiciteOccurence1" > 								 				
        <span>occurences</span>
      </p>
      <p class="clear">
        <input type="radio" class="radio plagePeriodicite2" name="evenement_finPeriodiciteOccurence" id="evenement_finPeriodiciteOccurence" value="2" onclick="getVal(2);"/> 
        <span>Fin le</span>
        <input type="text" id="dtcm_event_rdv_periodiciteFin" class="text extra datePeriodiciteFin" name="dtcm_event_rdv_periodiciteFin" />
      </p>
    </div>
    </fieldset>
		</div>
	</div>
<!--periodicite-->

	<div class="input">
		<a href="#" class="close"><input type="button" value="Annuler" class="boutonform" /></a>
		<input type="button" value="Affecter" class="boutonform submitFormulaire" />
	</div>
	<div class="input" style="width:480px;">
		<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
	</div>
</form>
	</div>
</div>
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>