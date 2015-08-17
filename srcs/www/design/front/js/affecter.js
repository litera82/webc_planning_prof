
var fadeTime = 300;
var lastOpen = null;
var wopener = null;

function afficherMasque()
{
	var w = $('body').width();
	var mh = $('body').height();
	var ih = $(document).height();

	if ( mh < ih ) mh = ih;		

	$('#masque')
		.css({width: w + 'px', height: mh +'px', opacity: 0.5, filter:'Alpha(Opacity=50)'})
		.fadeIn(fadeTime);

}

// affichage popup

$.fn.showPop = function(el, f)

{
	
	$(this).each(
		function()
		{
			$(this).click(
				function()
				{
					
					/* if( $(window).height() < $(el).height() ) 
                        extraPos = 150; */
					var lien = $(this).attr('title');
					$('.titreLien').html(lien);
					$('.ok').attr('href','http://' + lien);

					var tPos = ( $(window).height() - $(el).height() )/2 + $(window).scrollTop();
					
					if (tPos < 0)
					   tPos = 0;
					   
					var lPos = ( $(window).width() - $(el).width() )/2;
					if (lastOpen != null) { 
						$(lastOpen).fadeOut(fadeTime);
						wopener = lastOpen;
					}
					
					$(el)
						.fadeIn(fadeTime)
						.css({ top: tPos + 'px', left: lPos + 'px' });
					
					//$('select').css({visibility:'hidden'});					
					afficherMasque();
					
					lastOpen = el;
					
					if (f) f.call();
					
					return false;
				}
			);
		}
	);
	
}

$.fn.showElem = function(url)
{ 
	var tPos = ( $(window).height() - $(this).height() )/2 + $(window).scrollTop();
	var lPos = ( $(window).width() - $(this).width() )/2;
	
	$(this)
		.fadeIn(fadeTime)
		.css({ top: tPos + 'px', left: lPos + 'px' });
	
	lastOpen = this;
		
	afficherMasque();
	
	return false;
}



$.fn.hidePop = function(f)
{
	$(this).each(
		function()
		{
			$(this).click(
				function()
				{
					$('#masque').fadeOut(fadeTime);
					$(lastOpen).fadeOut(fadeTime);
					$('select').css({visibility:'visible'});
					if (f) f.call();
					return false;
				}
			);
		}
	);
	
	
	
}

function showBTPProduitCoord () {
	$(wopener).showElem ();
}
$(function(){ 
		$('.daterdv').datetimepicker({
			duration: '',
			showTime: true,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false,
			stepHour: 1,
			stepMinute: 10,
			stepSecond: 10,
			timeOnlyTitle: 'Choisir l’heure de début',
			timeText: 'Heure',
			hourText: 'Heure',
			minuteText: 'Minute',
			secondText: 'Second',
			currentText: 'Maintenant',
			closeText: 'Choisir',
			hourMin: 7,
			hourMax: 19
		});
		$('.date').datepicker({
			duration: '',
			showTime: true,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false
		});
		$('.date1').datepicker({
			duration: '',
			showTime: true,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false
		});

		$('.datePeriodicite').datepicker({
			duration: '',
			showTime: false,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false
		});

		$('.datePeriodiciteFin').datepicker({
			duration: '',
			showTime: false,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false
		});

		$.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		prevText: '&#x3c;Préc',
		nextText: 'Suiv&#x3e;',
		currentText: 'Courant',
		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
		'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
		'Jul','Aoû','Sep','Oct','Nov','Déc'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
		dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['fr']);

		$('.submitFormulaire').click(
			function(){
				/*var form = $('.pop-up').find('#edit_form');
				var isValid = tmt_validateForm(form);
				if(isValid){
					var iEvenementId = $('#evenement_id').val();
					var zDateTime = $('#dtcm_event_rdv').val();
					$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExistEdition", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
							if (datas == 0){*/
								$('.pop-up').find('#edit_form').submit();
							/*}else{
								if(confirm ("La plage horaire est déja occupée.\nVoulez-vous continuer ?"))
								{
									$("#x").val(1);
									$('#edit_form').submit();
								}
							}
						return false;
					});*/
				//}
			}
		);

		var url=j_basepath + "index.php?module=evenement&action=FoEvenement:autocompleteStagiaire";
		$('.pop-up').find("#edit_form").find('#rechercherStagiaire').click(
			function (){
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:autocompleteStagiaireAffectation", q:$('.pop-up').find("#edit_form").find('#evenement_zStagiaire').val()}, function(row){
					html = '';
					var selected = '';
					for (i=0 ; i<row.length ; i++)
					{	
						html += '<option onclick="getClient('+row[i].client_id+')" value="'+ row[i].client_id +'" '+ selected +'>'+ row[i].client_zNom + ' ' + row[i].client_zPrenom +'&nbsp;&nbsp;[' + row[i].client_zTel + ']'+'&nbsp;&nbsp;[' + row[i].societe_zNom + ']'+'&nbsp;&nbsp;[' + row[i].client_zVille + ']' +'</option>';
					}		$('.pop-up').find("#edit_form").find('#stagiaire-liste').show();
					$('.pop-up').find("#edit_form").find('#stagiaire-liste').html(html);
					$('#div-stagiaire-liste').show();
					$('#evenement_zLibelle').val('');
				});
				return false; 
			}
		);

		$('.periodicite').hide();
		$('#appelStagiaire').click(
			function (){
				if ($('#evenement_iContactTel').val() == 0)
				{
					$('#evenement_iContactTel').val(1); 
					$("#evenement_zContactTel").attr('disabled', 'disabled');
					$('#appelStagiaire').val("C'est le prof qui appelle");
				}else{
					$('#evenement_iContactTel').val(0);
					$("#evenement_zContactTel").removeAttr('disabled');
					$('#appelStagiaire').val("C'est le stagiaire qui appelle");
				}
		});
		$('.pop-up').find("#edit_form").find("#evenement_iTypeEvenementId").change(function(){
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:getTypeEvenement", iTypeEvenementId:$('.pop-up').find("#edit_form").find('#evenement_iTypeEvenementId').val()}, function(datas){
					//$('#div-stagiaire-liste').hide();
					//$('#evenement_zLibelle').val('');
					//$('#evenement_zLibelle').val(datas["client_zNom"]); 

					var zDuree = datas["typeevenements_iDure"]; 
					if (datas["typeevenements_iDureeTypeId"] == 1){
						$("#evenement_iDuree").val(datas["typeevenements_iDure"] + ' heures');
					}else{
						$("#evenement_iDuree").val(datas["typeevenements_iDure"] + ' minutes');
					}
				});
		});

		$("#periodemonth").click(function(){
			$('.NombreOccurenceDuplication').show();
			$('.DateFinDuplication').hide();
			$('#DateFinDuplicationJours').hide();

			$('.datePeriodicite').removeAttr('tmt:required');
			$('.datePeriodicite').removeAttr('tmt:message');

			$('.NombreOccurencePeriodicite').attr({'tmt:required':'true'});
		});
		$('#dtcm_event_rdv_periodiciteFin').change(
			function (){
				$('.plagePeriodicite2').attr({'checked':'checked'});
				$('#dtcm_event_rdv_periodiciteFin').attr({'tmt:required':'true'});
				$('.plagePeriodicite1').removeAttr('checked');
				$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');

				form = document.getElementById('edit_form');
				form.tmt_validator = new tmt_formValidator(form);
			}
		);
		$('#evenement_finPeriodiciteOccurence1').change(
			function (){
				$('.plagePeriodicite1').attr({'checked':'checked'});
				$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
				$('.plagePeriodicite2').removeAttr('checked');
				$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');

				form = document.getElementById('edit_form');
				form.tmt_validator = new tmt_formValidator(form);
			}
		);
		$('#evenement_periodiciteMensuel21').change(
			function (){
				$('.selectEvenement_periodiciteMensuel2').attr({'checked':'checked'});
				$('.selectEvenement_periodiciteMensuel1').removeAttr('checked');
			}
		);
		$('#evenement_periodiciteMensuel11').change(
			function (){
				$('.selectEvenement_periodiciteMensuel1').attr({'checked':'checked'});
				$('.selectEvenement_periodiciteMensuel2').removeAttr('checked');
			}
		);


		$('#addNewStagiaire').click(
			function (){
				$('#edit_form').removeAttr("tmt:validate");
				$('#edit_form').attr({'action':$('#urlAjoutStagiaire').val()});
				$('#edit_form').submit();
			}
		);

		$("#evenement_iDupliquer").click(
			function (){
				var checked = $("#evenement_iDupliquer").attr('checked');
				if (checked){
					$('.periodicite').show();
					$('.evenement_periodiciteQuotidienne').hide();
					$('.evenement_periodiciteHebdomadaire').hide();
					$('.evenement_periodiciteMensuel1').hide();
					$('#finPeriodiciteOccurence').val(1);

					var dtcm_event_rdv = $('.pop-up').find("#edit_form").find('#dtcm_event_rdv_affectation').val().split(' ');
					var dateDebut = dtcm_event_rdv[0]; 
					$('#dtcm_event_rdv_periodicite').val(dateDebut);
					var heureDebut = dtcm_event_rdv[1]; 
					var heureDebutFinal = heureDebut.split(':');
					var heureDebutRendezVous = heureDebutFinal[0]+':'+heureDebutFinal[1];
					$("#evenement_heureDebutRendezVous option:selected").attr("selected",'');// on met simplement la valeur de l'attribut à vide
					$('#evenement_heureDebutRendezVous option[value="'+heureDebutRendezVous+'"]').attr("selected","selected");

					var evenement_iDuree = $('#evenement_iDuree').val();
					$("#evenement_heureDureeRendezVous option:selected").attr("selected",'');// on met simplement la valeur de l'attribut à vide
					$('#evenement_heureDureeRendezVous option[value="'+evenement_iDuree+'"]').attr("selected","selected");


					gererAffichagePeriodicite(2);
				}else{
					$('.periodicite').hide();			
					$('#evenement_heureDebutRendezVous').removeAttr('tmt:required');
					$('#evenement_heureDureeRendezVous').removeAttr('tmt:required');
					$('#dtcm_event_rdv_periodicite').removeAttr('tmt:required');
					$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');
					$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
					$('#finPeriodiciteOccurence').val(0);
					$('#periodiciteMensuel1').val(0);
				}	
				form = document.getElementById('edit_form');
				form.tmt_validator = new tmt_formValidator(form); 			
			}
		);
	//Chargememnt donnée formulaire popup
	$('.showpopupAffestation').click (
		function(){
		if ($('.showpopupAffestation').attr('clientId') > 0){
			$('.pop-up').find("#edit_form").find('#evenement_iStagiaire').val($('.showpopupAffestation').attr('clientId'));
		}else{
			$('.pop-up').find("#edit_form").find('#evenement_iStagiaire').val('');
		}
		$('.pop-up').find("#edit_form").find('#evenement_zStagiaire').val('');
		$('.pop-up').find("#edit_form").find('#evenement_zLibelle').val('');

		$('.pop-up').find("#edit_form").find('#evenement_zStagiaire').val('');
		$('.pop-up').find("#edit_form").find('#stagiaire-liste').hide();

		$('.pop-up').find("#edit_form").find('#p-txtville').hide();
		$('.pop-up').find("#edit_form").find('#p-txtsociete').hide();
		$('.pop-up').find("#edit_form").find('#p-txtphone').hide();
		$('.pop-up').find("#edit_form").find('#p-txtmail').hide();

		$('.pop-up').find("#edit_form").find('#txtphone').val('');
		$('.pop-up').find("#edit_form").find('#evenement_zContactTel').val('');
		$('.pop-up').find("#edit_form").find('#txtsociete').val('');
		$('.pop-up').find("#edit_form").find('#txtville').val('');
		$('.pop-up').find("#edit_form").find('#txtmail').val('');

		$('.pop-up').find("#edit_form").find('#criteria_datedebut').val('');
		$('.pop-up').find("#edit_form").find('#criteria_datefin').val('');

		// GET eVENT DEFAULT PROF 

		
			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:approcheParListeGetEvent", id:$(this).attr('eventId')}, function(datas){
				$('.pop-up').find("#edit_form").find('#evenement_id').val(datas['evenement_id']) ; 
				$('.pop-up').find("#edit_form").find('#evenement_origine').val(datas['evenement_origine']) ; 
				$('.pop-up').find("#edit_form").find('#evenement_iPriorite').val(datas['evenement_iPriorite']) ; 
				$('.pop-up').find("#edit_form").find('#evenement_zLibelle').val(datas['evenement_zLibelle']) ; 
				$('.pop-up').find("#edit_form").find('#evenement_iContactTel').val(datas['evenement_iContactTel']) ; 
				$('.pop-up').find("#edit_form").find('#evenement_zDateHeureSaisie').val(datas['evenement_zDateHeureSaisie']) ; 
				$('.pop-up').find("#edit_form").find('.evenement_iTypeEvenementId').val(datas['evenement_iTypeEvenementId']) ; 
				
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:getDefaultTypeEvenement"}, function(defaultTypeEvent){
					$('.pop-up').find("#edit_form").find('#evenement_iTypeEvenementId option[value="'+defaultTypeEvent+'"]').attr("selected","selected");
				});


				$('.pop-up').find("#edit_form").find('#criteria_datedebut').val($('#dtcm_event_rdv').val());
				$('.pop-up').find("#edit_form").find('#criteria_datefin').val($('#dtcm_event_rdv1').val());


				$('.pop-up').find("#edit_form").find('#evenement_zDescription').text(datas['evenement_zDescription']) ; 
				if (datas['evenement_zDateHeureDebut'] != "")
				{
					var dateheure = datas['evenement_zDateHeureDebut'].split(' '); 
					var dateen = dateheure[0].split('-'); 
					var heureen = dateheure[1].split(':'); 
					var dateheurefr = dateen[2] + "/" + dateen[1] + "/" + dateen[0] + " " + heureen[0] + ":" + heureen[1] ;
					$('.pop-up').find("#edit_form").find('#dtcm_event_rdv_affectation').val(dateheurefr) ;
				}
			});
			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:approcheParListeGetPeriodicite"}, 
				function(datas){
					var selected = '';
					html = '<option selected value="0" >---------Heure---------</option>';
						for (i=0 ; i<datas.length ; i++)
						{	
							html += '<option value="'+ datas[i] +'" '+ selected +'>'+ datas[i] +'</option>';
						}
					$('.pop-up').find("#edit_form").find('#evenement_heureDebutRendezVous').html(html);					
				}
			);

			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:approcheParListeGetDurePeriodicite"}, 
				function(datas){
					var selected = '';
					html = '<option selected value="0" >---------Durée---------</option>';
						for (i=0 ; i<datas.length ; i++)
						{	
							if ( datas[i] == "30 minutes")
							{
								selected = 'selected';
							}else{
								selected = '';
							}
							html += '<option value="'+ datas[i] +'" '+ selected +'>'+ datas[i] +'</option>';
						}
								
					$('.pop-up').find("#edit_form").find('#evenement_heureDureeRendezVous').html(html);					
					$('.pop-up').find("#edit_form").find('#evenement_iDuree').html(html);					

				}
			);
		}	
	);

	$('.pop-up').find("#edit_form").find("#evenement_iTypeEvenementId").change(function(){
		$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:getTypeEvenement", iTypeEvenementId:$(this).val()}, function(datas){
			$('.pop-up').find("#edit_form").find('#div-stagiaire-liste').hide();
			$('.pop-up').find("#edit_form").find('#evenement_zLibelle').val('');
			$('.pop-up').find("#edit_form").find('#evenement_zLibelle').val(datas["client_zNom"]); 

			var zDuree = datas["typeevenements_iDure"]; 
			if (datas["typeevenements_iDureeTypeId"] == 1){
				zDuree += ' heures';
			}else{
				zDuree += ' minutes';
			}
			$('.pop-up').find("#edit_form").find("#evenement_iDuree").val(zDuree);
			$('.pop-up').find("#edit_form").find('#evenement_heureDureeRendezVous option[value="'+zDuree+'"]').attr("selected","selected");
		});
	});

	// ouverture popup
	$('.showpopupAffestation').showPop('#popupAffestation');

	// fermeture popup
	$('.fermer').hidePop();
	$('.close').hidePop();
	//$('.close').click(function (){$('.showpopupAffestation').hide()});
}); 

var autoCompleteJson= function(data){
	var parsed=[];
	for (var i=0; i<data.length;i++){
		var row=data[i];
		parsed.push({
			data: row,
			value: row["client_zNom"] + ' ' + row["client_zPrenom"]+' (' + row["client_zTel"] + ')',
			result: row["client_zNom"] + ' ' + row["client_zPrenom"]
		});
	}
	return parsed;
}

function getClient (id){
	$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:chargeParId", iStagiaireId:id}, function(datas){
		$('.pop-up').find("#edit_form").find('#evenement_iStagiaire').val(id);
		$('.pop-up').find("#edit_form").find('#evenement_zStagiaire').val('');
		$('.pop-up').find("#edit_form").find('#evenement_zLibelle').val('');
		$('.pop-up').find("#edit_form").find('#evenement_zLibelle').val(datas["client_zNom"]); 

		var html = datas["client_zNom"] + ' ' + datas["client_zPrenom"];
		$('.pop-up').find("#edit_form").find('#evenement_zStagiaire').val(html);

		$('.pop-up').find("#edit_form").find('#p-txtville').show();
		$('.pop-up').find("#edit_form").find('#p-txtsociete').show();
		$('.pop-up').find("#edit_form").find('#p-txtphone').show();
		$('.pop-up').find("#edit_form").find('#p-txtmail').show();

		$('.pop-up').find("#edit_form").find('#txtphone').val(datas["client_zTel"]);
		$('.pop-up').find("#edit_form").find('#evenement_zContactTel').val(datas["client_zTel"]);
		$('.pop-up').find("#edit_form").find('#txtsociete').val(datas["societe_zNom"]);
		$('.pop-up').find("#edit_form").find('#txtville').val(datas["client_zVille"]);
		$('.pop-up').find("#edit_form").find('#txtmail').val(datas["client_zMail"]);
		var HPrevu = 0;
		if (datas["HEURES_PREVUES"] != null){
			HPrevu = datas["HEURES_PREVUES"];
		}
		var HProduit = 0;
		if (datas["HEURES_PRODUITES"] != null){
			HProduit = datas["HEURES_PRODUITES"];
		}
		var soldeavantsaisie = datas["HEURES_PREVUES"] - datas["HEURES_PRODUITES"]
		alert("Cours prévus = "+HPrevu+"\nCours produites = "+HProduit+"\nSolde de cours = "+soldeavantsaisie+"\n") ;
	});
}
function selectPeriodiciteQuotidienne (){
	gererAffichagePeriodicite (1)
}
function selectPeriodiciteHebdo (){
	gererAffichagePeriodicite (2)
}
function selectPeriodiciteMensuel (){
	gererAffichagePeriodicite (3)
}
function getVal (valeur){
	$('#finPeriodiciteOccurence').val(valeur);
	if (valeur == 1)
	{
		$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
		$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
	}else{
		$('#dtcm_event_rdv_periodiciteFin').attr({'tmt:required':'true'});
		$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');
	}
	form = document.getElementById('edit_form');
	form.tmt_validator = new tmt_formValidator(form); 			
}
function getVal1 (valeur){
	$('#periodiciteMensuel1').val(valeur);
	if (valeur == 1)
	{
		$('#evenement_periodiciteMensuel11').attr({'tmt:required':'true'});
		$('#evenement_periodiciteMensuel12').attr({'tmt:required':'true'});
		$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
	}else{
		$('#evenement_periodiciteMensuel23').attr({'tmt:required':'true'});
		$('#evenement_periodiciteMensuel11').removeAttr('tmt:required');
		$('#evenement_periodiciteMensuel12').removeAttr('tmt:required');
	}
	form = document.getElementById('edit_form');
	form.tmt_validator = new tmt_formValidator(form); 			
}

function gererAffichagePeriodicite (iPeriodicite){
	if(iPeriodicite == 1){
		$('.evenement_periodiciteHebdomadaire').hide();
		$('.evenement_periodiciteMensuel1').hide();
		$('.evenement_periodiciteQuotidienne').show();

		$('#evenement_periodiciteMensuel11').removeAttr('tmt:required');
		$('#evenement_periodiciteMensuel12').removeAttr('tmt:required');
		$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
	}
	if(iPeriodicite == 2){
		$('.evenement_periodiciteQuotidienne').hide();
		$('.evenement_periodiciteMensuel1').hide();
		$('.evenement_periodiciteHebdomadaire').show();

		$('#evenement_periodiciteMensuel11').removeAttr('tmt:required');
		$('#evenement_periodiciteMensuel12').removeAttr('tmt:required');
		$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
	}
	if(iPeriodicite == 3){
		$('.evenement_periodiciteQuotidienne').hide();
		$('.evenement_periodiciteHebdomadaire').hide();
		$('.evenement_periodiciteMensuel1').show();

		$('#evenement_periodiciteMensuel11').attr({'tmt:required':'true'});
		$('#evenement_periodiciteMensuel12').attr({'tmt:required':'true'});
		$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
	}
	$('#evenement_heureDebutRendezVous').attr({'tmt:required':'true', 'tmt:invalidindex':'0'});
	$('#evenement_heureDureeRendezVous').attr({'tmt:required':'true', 'tmt:invalidindex':'0'});

	$('#dtcm_event_rdv_periodicite').attr({'tmt:required':'true'});
	$('#evenement_finPeriodiciteOccurence').attr({'checked':'checked'});
	$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
	$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
	

	var finPeriodiciteOccurence = $('#finPeriodiciteOccurence').val();
	if (finPeriodiciteOccurence == 1)
	{
		$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
		$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
	}else{
		$('#dtcm_event_rdv_periodiciteFin').attr({'tmt:required':'true'});
		$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');
	}
	form = document.getElementById('edit_form');
	form.tmt_validator = new tmt_formValidator(form);
}
function submitFormRecherche(){
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
					$('#edit_form').attr({'action':$('#action1').val()}) ;
					$('#edit_form').submit();
				}
			}
		 });
	}else{
		$('#edit_form').attr({'action':$('#action1').val()}) ;
		$('#edit_form').submit();
	}
	return false;
}
function submitFormRechercheApprocheListe(){
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

DD_roundies.addRule('div.formevent', '5px');
DD_roundies.addRule('input.boutonform', '5px');