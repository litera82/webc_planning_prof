{literal}
<script type="text/javascript">
$( function () {

	addEvent(window, "load", tmt_validatorInit);

	$('.submitForm').click(
		function(){
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);

	$('.submitFormExport').click(
		function (){
			//submitFormRecherche() ;
			$('#edit_formList').submit();
		}
	);

	$('.submitFormExportIcs').click(
		function (){
			document.location.href = j_basepath + "index.php?module=evenement&action=FoEvenement:exportIcsEventListing&zDateDebut=" + $('#zDateDebut').val() + "&zDateFin=" + $('#zDateFin').val() + "&iTypeEvenement=" + $('#iTypeEvenement').val() + "&iStagiaire=" + $('#iStagiaire').val();

		}
	);

	$('.date').datepicker({
		duration: '',
		showTime: false,
		showOn: 'button',
		buttonImageOnly : true,
		buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
		constrainInput: false
	});
	$('.date1').datepicker({
		duration: '',
		showTime: false,
		showOn: 'button',
		buttonImageOnly : true,
		buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
		constrainInput: false
	});
	$('.date2').datepicker({
		duration: '',
		showTime: false,
		showOn: 'button',
		buttonImageOnly : true,
		buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
		constrainInput: false
	});

	var url=j_basepath + "index.php?module=evenement&action=FoEvenement:autocompleteStagiaire&o=1";
	$('#evenement_zStagiaire').autocomplete(url,{
		/*mustMatch : true,*/
		minChars: 0,
		autoFill: false,
		scroll: true,
		scrollHeight: 300,
		dataType: "json" ,
		parse : autoCompleteJson,
		formatItem: function(row) {
			//$('.ac_results').attr({'style':'width:auto;'})
			var zInfo = "" ;
			if (row["client_zNom"] !== undefined && row["societe_zNom"] != "")
			{
				zInfo += ' ' + row["client_zNom"] ;
			}
			if (row["client_zPrenom"] !== undefined && row["client_zPrenom"] != "")
			{
				zInfo += ' ' + row["client_zPrenom"] ;
			}
			if (row["client_zTel"] !== undefined && row["client_zTel"] != "")
			{
				zInfo += '&nbsp;&nbsp;[' + row["client_zTel"] + ']' ;
			}
			if (row["societe_zNom"] !== undefined && row["societe_zNom"] != "")
			{
				zInfo += '&nbsp;&nbsp;[' + row["societe_zNom"] + ']' ;
			}
			if (row["client_zVille"] !== undefined && row["client_zVille"] != "")
			{
				zInfo += '&nbsp;&nbsp;[' + row["client_zVille"] + ']' ;
			}
			return zInfo ;
		}
	}).result(function(event, row, formatted){	
		if (typeof(row) == 'undefined') {		
			$('#evenement_stagiaire').val(0);		
			$('#evenement_zStagiaire').val("");		
		} else {
			$('#evenement_stagiaire').val(row["client_id"]);
		}
	}).blur(function(){
		//$(this).search();
	});

	var url1=j_basepath + "index.php?module=evenement&action=FoEvenement:autocompleteSociete";
	$('#evenement_zSociete').autocomplete(url1,{
		minChars: 0,
		autoFill: false,
		scroll: true,
		scrollHeight: 300,
		dataType: "json" ,
		parse : autoCompleteJson1,
		formatItem: function(row) {
			var zInfo = "" ;
			if (row["societe_zNom"] != ""){
				zInfo += ' ' + row["societe_zNom"] ;
			}
			return zInfo ;
		}
	}).result(function(event, row, formatted){	
		if (typeof(row) == 'undefined') {		
			$('#evenement_societe').val(0);		
			$('#evenement_zSociete').val("");		
		} else {
			$('#evenement_societe').val(row["societe_id"]);
			$('#evenement_zSociete').val(row["societe_zNom"]);		
		}
	}).blur(function(){
		$(this).search();
	});


	$('.modifierEvent').click(
		function (){
			if ($(this).attr('ieventid') > 0){
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:eventListingEditEvent", iEvenementId:$(this).attr('ieventid')}, function(row){
					$('.classEvenementId').val(row['oEvenement']['evenement_id']);
					$('#evenement_zDescription').val(row['oEvenement']['evenement_zDescription']) ;
					$('.evenement_zStagiairepop').val(row['oStagiaire']['client_zNom']+' '+row['oStagiaire']['client_zPrenom']) ;
					$('#div-stagiaire-liste').attr({'style':'display:none'}) ; 
					$('#txtmail').val(row['oStagiaire']['client_zMail']);
					$('.iStagiairePop').val(row['oStagiaire']['client_id']);
					$('#txtphone').val(row['oStagiaire']['client_zTel']);
					$('#txtsociete').val(row['oSociete']['societe_zNom']);
					$('#txtville').val(row['oStagiaire']['client_zRue']+' '+row['oStagiaire']['client_zVille']+' '+row['oStagiaire']['client_zCP']);
					$('.datedtcm_event_rdv').val(row['oEvenement']['evenement_zDateHeureDebutFr']) ;
				});		
			}else{
				alert("Erreur lors du chargement de l'evenement!!") ;
			}
		}
	);

	$('#rechercherStagiaire').click(
		function (){
			var evenement_zStagiaire = $('.evenement_zStagiairepop').val();
			if (evenement_zStagiaire == "")
			{
				evenement_zStagiaire = " "; 
			}
			if (evenement_zStagiaire != ""){
				$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:rechercherStagiaire", zStagiaire:evenement_zStagiaire}, function(datas){
					if(datas.length>0){
						$('#div-stagiaire-liste').show();
						$('#stagiaire-liste').html('');
						var html = '<option value="0">Séléctionner le stagiaire</option>';
						for(i=0; i< datas.length; i++){
							html += '<option value="' + datas[i]["client_id"] +'">&nbsp;' + datas[i]["client_zNom"] + '&nbsp;' + datas[i]["client_zPrenom"] + '&nbsp;&nbsp;[' + datas[i]["client_zTel"] + ']&nbsp;&nbsp;[' + datas[i]["societe_zNom"] + ']&nbsp;&nbsp;[' + datas[i]["client_zVille"] + ']</option>';
						}
						$('#stagiaire-liste').html(html);
						$('#stagiaire-liste').val(0);
					}else{
						$('#div-stagiaire-liste').show();
						$('#stagiaire-liste').html('');
						var html = '<option value="0">Aucun stagiaire</option>';
						$('#stagiaire-liste').html(html);
						$('#div-stagiaire-liste').hide();
						$('#evenement_zStagiaire').val('');
						alert('Aucun stagiaire trouvé correspondant à votre recherche. Veuillez saisir un autre nom');
					}					 
				});
			}else{
				alert('Veuillez enter un nom de stagiaire');
			}
			return false;
		}
	);

	$('#stagiaire-liste').click(
		function (){
			var iStagiaire = $('#stagiaire-liste').val();
			if(iStagiaire > 0){
				$('#evenement_iStagiaire').val(iStagiaire);
				$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:chargeParId", iStagiaireId:iStagiaire}, function(datas){
					$('#div-stagiaire-liste').hide();
					$('.evenement_zStagiairepop').val('');
					$('#evenement_zLibelle').val('');
					$('#evenement_zLibelle').val(datas["client_zNom"]); 

					var html = datas["client_zNom"] + ' ' + datas["client_zPrenom"];
					$('.evenement_zStagiairepop').val(html);
					$('.iStagiairePop').val(datas["client_id"]); 
					$('#p-txtville').show();
					$('#p-txtsociete').show();
					$('#p-txtphone').show();
					$('#p-txtmail').show();

					$('#txtphone').val(datas["client_zTel"]);
					$('#txtsociete').val(datas["societe_zNom"]);
					$('#txtville').val(datas["client_zVille"]);
					$('#txtmail').val(datas["client_zMail"]);
				});
			}
		}
	);

	$('.submitFormulaire').click(
		function(){
			// Les parametres 
			var dtcm_event_rdv = $('#dtcm_event_rdv').val();
			var dtcm_event_rdv1 = $('#dtcm_event_rdv1').val();
			var evenement_origine = $('#evenement_origine').val();
			var evenement_iTypeEvenementId = $('#evenement_iTypeEvenementId').val();
			var evenement_stagiaire = $('#evenement_stagiaire').val();
			// Infos event 
			var iEventId = $('.classEvenementId').val() ;
			var iTypeEventId = $('.classTypeEvenementId').val() ;
			var zEventDesc = $('.classDescription').val() ;
			var iEventStagiaireId = $('.classStagiaireId').val() ;

			if (iEventId <= 0)
			{
				alert("Erreur, impossible d'enregistrer l'evenement!!!");
			}else if (iTypeEventId == 0)
			{
				alert("Merci de selectionner le type d'evenement!!!");
			}else{
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:savePopEventListing", iEventId:iEventId, iTypeEventId:iTypeEventId, zEventDesc:zEventDesc, iEventStagiaireId:iEventStagiaireId}, function(row){
					if (row == iEventId)
					{
						window.location.href = $("#action1").val() + '&dtcm_event_rdv='+dtcm_event_rdv+'&dtcm_event_rdv1='+dtcm_event_rdv1+'&evenement_origine='+evenement_origine+'&evenement_iTypeEvenementId='+evenement_iTypeEvenementId+'&evenement_stagiaire='+evenement_stagiaire;
					}else{
						alert("Erreur lors de l'enregistrement de l'evenement!!!") ;
					}
				});		
			}
		}
	);

	$('#resetvalidatepopskype').click(
		function (){
			content = '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">';
			$('#validatepopskype').html('');
			$('#validatepopskype').html(content);
		}
	);

	$('.print').hide();
	$('.invalidateCours').click(function (){
		if(confirm("Voulez vous vraiment invalider le cours ?")){
			var iValidationEventId = $(this).attr('iValidationEventId');
			$("#criteriondtcm_event_rdv").val($("#dtcm_event_rdv").val());
			$("#criteriondtcm_event_rdv1").val($("#dtcm_event_rdv1").val());
			$("#criterionevenement_origine").val($("#evenement_origine").val());
			$("#criterionevenement_iTypeEvenementId").val($("#evenement_iTypeEvenementId").val());
			$("#criterionevenement_zStagiaire").val($("#evenement_zStagiaire").val());
			$("#criterionevenement_zSociete").val($("#evenement_zSociete").val());
			$("#criteriongroupe_id").val($("#groupe_id").val());
			$("#criterionprofesseurs").val($("#professeurs").val());

			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:invalidateEvent", iValidationEventId:iValidationEventId}, function(datas){
				if (datas > 0){
						document.location.href = j_basepath + "index.php?module=evenement&action=FoEvenement:getEventListing&dtcm_event_rdv=" + $("#criteriondtcm_event_rdv").val() + "&dtcm_event_rdv1=" + $("#criteriondtcm_event_rdv1").val() + "&evenement_origine=" + $("#criterionevenement_origine").val() + "&evenement_iTypeEvenementId=" + $('#criterionevenement_iTypeEvenementId').val() + "&evenement_zStagiaire=" + $("#criterionevenement_zStagiaire").val() + "&evenement_zSociete=" + $("#criterionevenement_zSociete").val() + "&groupe_id=" + $("#criteriongroupe_id").val() + "&professeurs=" + $("#criterionprofesseurs").val();
					}else{
						alert("Erreur lors de la dévalidation du cours !!!") ;
					}
				}
			);
		}
	});
	$('.validateCours').click(
		function(){
			var iEventId = $(this).attr('iEventId') ;
			$("#validatepopEvenementId").val(0);
			$("#validatepopEvenementId").val(iEventId);
			if ($(this).attr('zurlcodeanomalie') != ""){
				$("#zUrlCodeAnomalie").attr({"href":"#"});
				$("#zUrlCodeAnomalie").attr({"href":$(this).attr('zurlcodeanomalie')});
				$(".urlExtranet").attr({"style":"display:block;"});
			}else{
				$("#zUrlCodeAnomalie").attr({"href":"#"});
				$(".urlExtranet").attr({"style":"display:none;"});
			}
			
			$("#validatepopdate").text('');
			$("#validatepopdate").append($(this).attr('zDate'));

			$("#validatepopdure").text('');
			$("#validatepopdure").append($(this).attr('zDure'));

			$("#validatepoptype").text('');
			$("#validatepoptype").append($(this).attr('zType'));

			$("#validatepopclient").text('');
			$("#validatepopclient").append($(this).attr('zClient'));

			$("#validatepopsociete").text('');
			$("#validatepopsociete").append($(this).attr('zSociete'));

			$("#validatepopdescription").text('');
			$("#validatepopdescription").append($(this).attr('zDescription'));

			$("#criteriondtcm_event_rdv").val($("#dtcm_event_rdv").val());
			$("#criteriondtcm_event_rdv1").val($("#dtcm_event_rdv1").val());
			$("#criterionevenement_origine").val($("#evenement_origine").val());
			$("#criterionevenement_iTypeEvenementId").val($("#evenement_iTypeEvenementId").val());
			$("#criterionevenement_zStagiaire").val($("#evenement_zStagiaire").val());
			$("#criterionevenement_zSociete").val($("#evenement_zSociete").val());
			$("#criteriongroupe_id").val($("#groupe_id").val());
			$("#criterionprofesseurs").val($("#professeurs").val());

			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:chargerValidateEvent", iEventId:iEventId}, function(datas){
				console.log(datas);
				if (datas.evenementvalidation_id > 0 && datas.evenementvalidation_id !== undefined){
					$('#presence').val(datas.evenementvalidation_validationId);
					$('.classDescriptionValidation').val(datas.evenementvalidation_commentaire);
					var content = '';
					if (datas.evenementvalidation_skype == 1){
						content += '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">' ;
					}else if(datas.evenementvalidation_skype == 2){
						content += '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">' ;
					}else if(datas.evenementvalidation_skype == 3){
						content += '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">';
					}else if(datas.evenementvalidation_skype == 4){
						content += '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">' ;
					}else if(datas.evenementvalidation_skype == 5){
						content += '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;">';
					}else{
						content += '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">';
					}
					$('#validatepopskype').html('');
					$('#validatepopskype').html(content);

					$('#bureau').val('');
					$('#bureau').val(datas.bureau);
					$('#navigateur').val('');
					$('#navigateur').val(datas.navigateur);
					$('#telFixe').val('');
					$('#telFixe').val(datas.telFixe);
					$('#telMobile').val('');
					$('#telMobile').val(datas.telMobile);
					$('#skype').val('');
					$('#skype').val(datas.skype);
					$('#casqueSkype').val('');
					$('#casqueSkype').val(datas.casqueSkype);
				}else{
					$('#presence').val(0);
					$('.classDescriptionValidation').val('');		
					
					var content = '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">' ;
					$('#validatepopskype').html('');
					$('#validatepopskype').html(content);

					$('#bureau').val(0);
					$('#navigateur').val('');
					$('#telFixe').val('');
					$('#telMobile').val('');
					$('#skype').val('');
					$('#casqueSkype').val(0);
				}
			});
		}
	);

	$(".content-pop-inner img").live('click', function() {
		var rang = parseInt($(this).attr("id").split("_")[1]);
		var val = rang + 1;
		var imgJaune = j_basepath+"design/front/images/design/etoile-jaune.jpg";
		var imgBlanc = j_basepath+"design/front/images/design/etoile-blanche.jpg";
		$("input[name=evenementvalidation_skype]").val(val);
		for(i=0; i<val; i++) {
			$("img[id=image_"+i+"]").attr({"src":imgJaune});
		}
		for(k=i; k<6; k++) {
			$("img[id=image_"+k+"]").attr({"src":imgBlanc});
		}
	}); 

	$('.submitExtranet').click(function (){
		
	});
	$('.plannifiedEvent').click(function (){
		alert("ID CLIENT === " + $(this).attr("iPlanifierClientId") + "\nDATE DE DEBUT === " + $(this).attr("iPlanifierDateDebut")); 
	});

	$('.submitValidationCours').click(function (){
		var iEventId = $('#validatepopEvenementId').val();
		var iValidationId = $('#presence').val();
		var iSkype = $('#evenementvalidation_skype').val();
		var zComment = $('.classDescriptionValidation').val();

		var bureau = $('#bureau').val();
		var navigateur = $('#navigateur').val();
		var telFixe = $('#telFixe').val();
		var telMobile = $('#telMobile').val();
		var skype = $('#skype').val();
		var casqueSkype = $('#casqueSkype').val();

		$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:validateEvent", iEventId:iEventId, iValidationId:iValidationId, zComment:zComment, iSkype:iSkype, bureau:bureau, navigateur:navigateur, telFixe:telFixe, telMobile:telMobile, skype:skype, casqueSkype:casqueSkype}, function(datas){
			if (datas > 0){
				$('.pop-up').hide();
				$('#masque').hide();
				document.location.href = j_basepath + "index.php?module=evenement&action=FoEvenement:getEventListing&dtcm_event_rdv=" + $("#criteriondtcm_event_rdv").val() + "&dtcm_event_rdv1=" + $("#criteriondtcm_event_rdv1").val() + "&evenement_origine=" + $("#criterionevenement_origine").val() + "&evenement_iTypeEvenementId=" + $('#criterionevenement_iTypeEvenementId').val() + "&evenement_zStagiaire=" + $("#criterionevenement_zStagiaire").val() + "&evenement_zSociete=" + $("#criterionevenement_zSociete").val() + "&groupe_id=" + $("#criteriongroupe_id").val() + "&professeurs=" + $("#criterionprofesseurs").val();
			}else{
				alert("Erreur lors de l'enregistrement !!!") ;
			}
		});
	});

	//$('#professeurs').attr({'disabled':'disabled'}) ;

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
						for(i=0; i<datas.length; i++){
							html += '<option value="' + datas[i]["utilisateur_id"]+'"  >' + datas[i]["utilisateur_zNom"] + ' ' + datas[i]["utilisateur_zPrenom"] + '<\/option>';
						}
						$('#professeurs').html(html);
				 });				
			}
		}
	);
});

var autoCompleteJson = function(data){
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
var autoCompleteJson1 = function(data){
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

function addIdEventToDelete (_iEventId){
	var iEventIdChecked = $('.suppr_'+_iEventId).attr('checked')?1:0;
	if (iEventIdChecked == 1){
		if ($('#eventToDelete').val() == "")
		{
			$('#eventToDelete').val(_iEventId); 
		}else{
			var newVal = $('#eventToDelete').val() + '@_@' + _iEventId; 
			$('#eventToDelete').val(newVal);
		}
	}else{
		if ($('#eventToDelete').val() != "")
		{
			var val=$('#eventToDelete').val(); 
			var tVal = val.split('@_@');
			for(i=0; i<tVal.length; i++){
				if (tVal[i] == _iEventId){
					tVal.splice(i, 1);
				}
			}
			tVal.sort();
			var zNewVal = ""; 
			for(i=0; i<tVal.length; i++){
				if (tVal[i] != ""){
					if (zNewVal == ""){
						zNewVal = tVal[i];
					}else{
						zNewVal = zNewVal + "@_@" + tVal[i];
					}
				}
			}
			$('#eventToDelete').val(zNewVal); 
		}
	}	
}

function suppressionMultipleEvent (){
	document.location.href = j_basepath + "index.php?module=evenement&action=FoEvenement:suppressionMultipleEvent&zListeEvenementId=" + $('#eventToDelete').val() + "&zDateDebut=" + $('#zDateDebut').val() + "&zDateFin=" + $('#zDateFin').val() + "&iTypeEvenement=" + $('#iTypeEvenement').val() + "&iStagiaire=" + $('#iStagiaire').val();
}
function addEventEventlisting (){
	document.location.href = $('#urlAddEvent').val() + "&prec=1&debut="+$('#dtcm_event_rdv').val()+"&fin="+$('#dtcm_event_rdv1').val();
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
function imprimerEventListing(){
	$('.print').show();
	window.print();
	$('.print').hide();
}
</script>
{/literal}
<div class="main-page noPrint">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear" style="width:960px;padding: 5px 5px 5px;">
				<form id="edit_form" action="#" method="POST" enctype="multipart/form-data" tmt:validate="true">
					<input type="hidden" name="action1" id="action1" value="{jurl 'evenement~FoEvenement:getEventListing', array(), false}"/>
					<input type="hidden" name="urlAddEvent" id="urlAddEvent" value="{jurl 'evenement~FoEvenement:add', array(), false}"/>
					<input type="hidden" name="evenement_id" id="evenement_id" />
					<input type="hidden" name="urlCalculDateDiff" id="urlCalculDateDiff" value="{jurl 'evenement~FoEvenement:calculDateDiff'}"/>
					<input type="hidden" name="urlChargeProfParGroupId" id="urlChargeProfParGroupId" value="{jurl 'evenement~FoEvenement:chargeProfParGroupId'}"/>
					<input type="hidden" name="urlChargerValidateEvent" id="urlChargerValidateEvent" value="{jurl 'evenement~FoEvenement:chargerValidateEvent'}"/>
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
							<tr>
								<td>
								<p class="clear">
									<label style="width:200px;">Origine</label>
									<select class="text"  style="width:200px;" name="evenement_origine" id="evenement_origine" >
										<option value="0">--------------------Tous--------------------</option>
										<option value="1" {if isset ($toParams[0]->evenement_origine) && $toParams[0]->evenement_origine == 1}selected="selected"{/if}>Auto-planification</option>
										<option value="2" {if isset ($toParams[0]->evenement_origine) && $toParams[0]->evenement_origine == 2}selected="selected"{/if}>Agenda</option>
									</select>
								</p>
								</td>
								<td>
								<p class="clear">
									<label style="width:200px;">Type de l'évènement </label>
									<select class="text" style="width:200px;" name="evenement_iTypeEvenementId" id="evenement_iTypeEvenementId" >
									<option value="0">----------------Séléctionner----------------</option>
									{foreach $toTypeEvenement as $oTypeEvenement}
										{if $oTypeEvenement->typeevenements_id != ID_TYPE_EVENEMENT_DISPONIBLE}
											<option value="{$oTypeEvenement->typeevenements_id}" {if isset ($toParams[0]->iTypeEvenement) && $toParams[0]->iTypeEvenement == $oTypeEvenement->typeevenements_id}selected="selected"{/if}>{$oTypeEvenement->typeevenements_zLibelle}</option>
										{/if}
									{/foreach}
									</select>
								</p>
								</td>
							</tr>
							<tr>
								<td>
								<p class="clear">
									<label style="width:200px;">Stagiaire</label>
									<input type="hidden" name="evenement_stagiaire" id="evenement_stagiaire" value="0" />
									<input style="width:200px;" type="text" class="text" name="evenement_zStagiaire" id="evenement_zStagiaire"/>
								</p>
								</td>
								<td>
								<p class="clear">
									<label style="width:200px;">Société</label>
									<input type="hidden" name="evenement_societe" id="evenement_societe" value="0" />
									<input style="width:200px;" type="text" class="text" name="evenement_zSociete" id="evenement_zSociete" value='{if isset($toParams[0]->evenement_zSociete) && $toParams[0]->evenement_zSociete != ""}{$toParams[0]->evenement_zSociete}{/if}'/>
								</p>
								</td>
							</tr>
							<tr>
								<td>
								<p class="clear">
									<label style="width:200px;"><input type="checkbox" value="1" name="cours_produit" title="Afficher uniquements les cours produits" {if isset($toParams[0]->cours_produit) && $toParams[0]->cours_produit == 1}checked="checked"{/if}></label>
									<span style="font-size:1.2em;"> Afficher uniquements les cours produits</span>
								</p>
								</td>
								<td>
								<p class="clear">&nbsp;</p>
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
					<div class="input" style="width:405px;padding-top:1px;">
						<input type="button" value="Ajouter un évènement" class="boutonform" onclick="addEventEventlisting();" style="padding: 2px 5px;"/>
						<input type="button" value="Exporter vers Excel" class="boutonform submitForm submitFormExport"  style="padding: 2px 5px;"/>
						<input type="button" value="Rechercher" class="boutonform" onclick="submitFormRecherche();"  style="padding: 2px 5px;"/>
					</div>
					<div class="input" style="width:480px;padding-top:1px;">
						<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
					</div>
			</form>
			</div>
		</div>
		<div class="content" style="padding-top: 15px; margin-left: -110px; width: 1200px;">
			<div class="formevent listeclients clear" style="width: 1135px;">
				<form id="edit_formList" action="{jurl 'evenement~FoEvenement:exportEventListing', array(), false}" method="POST" enctype="multipart/form-data">
					<input type="hidden" value="" id="eventToDelete" name="eventToDelete"/>
					<input type="hidden" value="{$toParams[0]->zDateDebut}" id="zDateDebut" name="zDateDebut"/>
					<input type="hidden" value="{$toParams[0]->zDateFin}" id="zDateFin" name="zDateFin"/>
					<input type="hidden" value="{$toParams[0]->iTypeEvenement}" id="iTypeEvenement" name="iTypeEvenement"/>
					<input type="hidden" value="{$toParams[0]->iStagiaire}" id="iStagiaire" name="iStagiaire"/>
					<input type="hidden" value="{$toParams[0]->evenement_origine}" id="evenement_stagiaire " name="evenement_origine"/>
					<input type="hidden" value="{$toParams[0]->iCheckDate}" id="iCheckDate" name="iCheckDate"/>
					<input type="hidden" value="{$toParams[0]->iAfficheNomStagiaire}" id="iAfficheNomStagiaire" name="iAfficheNomStagiaire"/>
					<input type="hidden" value="{$toParams[0]->cours_produit}" id="iCoursProduit" name="iCoursProduit"/>
					{if isset($toParams[0]->iAfficheNomStagiaire) && $toParams[0]->iAfficheNomStagiaire == 0}
					<h2>Liste d'évènements {if $oUtilisateur->utilisateur_iTypeId != TYPE_UTILISATEUR_ADLINISTRATEUR}pour {$oUtilisateur->utilisateur_zNom} {$oUtilisateur->utilisateur_zPrenom}{/if}</h2>
					{else}
					<h2>Liste d'évènements {if isset($oClient->client_id) && $oClient->client_id > 0}pour {$oClient->client_zNom} {$oClient->client_zPrenom}{/if}</h2>
					{/if}
					<div class="legendeplan clear" style="padding:2px;">
						<ul>
							<li style="padding:2px 20px 2px 15px; font-size:1em; width:120px; border-color:#01FE09; color:#2C2C2C;">
								Présent
							</li>
							<li style="padding:2px 20px 2px 15px; font-size:1em; width:120px; border-color:#32B7F9; color:#2C2C2C;">
								Absence maladie
							</li>
							<li style="padding:2px 20px 2px 15px; font-size:1em; width:150px; border-color:#DEF932; color:#2C2C2C;">
								Absence professionnelle
							</li>
							<li style="padding:2px 20px 2px 15px; font-size:1em; width:120px; border-color:#FA3EC2; color:#2C2C2C;">
								Absence autres
							</li>
							<li style="padding:2px 20px 2px 15px; font-size:1em; width:120px; border-color:#FE615B; color:#2C2C2C;">
								Reporté
							</li>
						</ul>
					</div>
					<table cellpadding="1" cellspacing="1" border="1">
						<tbody>
							<tr>
								<th style="width:150px;text-align:center;"><span>Dates</span></th>
								<th style="width:70px;text-align:center;">Durées</th>
								<th style="width:160px;text-align:center;">Cours</th>
								<th style="width:135px;text-align:center;">Stagiares</th>
								<th style="width:123px;text-align:center;">Sociétés</th>
								<th style="width:180px;text-align:center;">Validation</th>
								
								<th style="width:40px;text-align:center;">Prévues</th>
								<th style="width:40px;text-align:center;">Produites</th>
								<th style="width:40px;text-align:center;">Solde</th>
								<th style="width:40px;text-align:center;">Planifiés</th>

								<th style="width:auto;text-align:center;">Actions</th>
							</tr>
						</tbody>
					</table>
					<div class="tabevent" style="">
						<table cellpadding="1" cellspacing="1" border="1">
							<tbody style="height: 30px;line-height:70%">
								{assign $i = 1}
								{foreach $toEvenement as $oEvenement}
									{assign $backgroundColor = ""}
									{if isset($oEvenement->evenementvalidation_id) && $oEvenement->evenementvalidation_id > 0}
										{if $oEvenement->evenementvalidation_validationId == 1}{assign $backgroundColor = "background-color:#01FE09;"}{/if}
										{if $oEvenement->evenementvalidation_validationId == 2}{assign $backgroundColor = "background-color:#32B7F9;"}{/if}
										{if $oEvenement->evenementvalidation_validationId == 3}{assign $backgroundColor = "background-color:#DEF932;"}{/if}
										{if $oEvenement->evenementvalidation_validationId == 4}{assign $backgroundColor = "background-color:#FA3EC2;"}{/if}
										{if $oEvenement->evenementvalidation_validationId == 5}{assign $backgroundColor = "background-color:#FE615B;"}{/if}
									{/if}
									<tr class="extra{$i++%2+1}" style="height: 30px;line-height:70%">
										<td class="col1" style="width:140px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">
											<span>
												<a href="#" class="modifierEvent" ieventid="{$oEvenement->evenement_id}" title="Modifier l'evenement">
													{$oEvenement->evenement_zDateJoursDeLaSemaine}&nbsp;
													{$oEvenement->evenement_zDateHeureDebut|date_format:'%d/%m/%Y %H:%M'}
												</a>
											</span>
										</td>
										<td class="col2" style="width:60px;text-align:center;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">{$oEvenement->typeevenements_iDure} {if $oEvenement->typeevenements_iDureeTypeId == 1}h{else}mn{/if}</td>
										<td class="col3" style="width:150px;{$backgroundColor}">{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}{$oEvenement->utilisateur_zNom} {$oEvenement->utilisateur_zPrenom}<br>{/if}&nbsp;{$oEvenement->typeevenements_zLibelle}</td>
										<td class="col4" style="width:125px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">
											{if $oEvenement->client_id > 0}
												<a href="{jurl 'client~FoClient:add', array('iClientId'=>$oEvenement->client_id), false}" id="imgInfoStagiaire" title="Detail du stagiaire" target="_blank">&nbsp;&nbsp;{$oEvenement->client_zNom} {$oEvenement->client_zPrenom}</a>
											{else}
												<p style="text-align:center;"> - </p>
											{/if}
										</td>
										<td class="col5" style="line-height:normal;width:113px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">
											{if isset($oEvenement->societe_zNom) && $oEvenement->societe_zNom !=""}
												&nbsp;&nbsp;{$oEvenement->societe_zNom}
											{else}
												<p style="text-align:center;"> - </p>
											{/if}
										</td>
										<td class="col6"style="width:170px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;line-height:normal;">&nbsp;&nbsp;{$oEvenement->validation_zLibelle|nl2br}<br />&nbsp;&nbsp;{$oEvenement->evenementvalidation_date|date_format:'%d/%m/%Y %H:%M'}<br />&nbsp;&nbsp;{$oEvenement->evenementvalidation_commentaire|nl2br}</td>

										<td class="col7" style="width:38px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">
												<p style="text-align:center;">{if isset($oEvenement->clientsolde_prevu) && $oEvenement->clientsolde_prevu != NULL }{$oEvenement->clientsolde_prevu}{else}{$oEvenement->HEURES_PREVUES}{/if}</p>
										</td>
										<td class="col8" style="width:44px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">
												<p style="text-align:center;">{if isset($oEvenement->clientsolde_produit) && $oEvenement->clientsolde_produit != NULL }{$oEvenement->clientsolde_produit}{else}{$oEvenement->HEURES_PRODUITES}{/if}</p>
										</td>
										<td class="col9" style="width:30px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">
												<p style="text-align:center;">{if isset($oEvenement->clientsolde_solde) && $oEvenement->clientsolde_solde != NULL }{$oEvenement->clientsolde_solde}{else}{$oEvenement->soldeavantsaisie}{/if}</p>
										</td>
										<td class="col10" style="width:39px;{$backgroundColor};padding=6px 10px 0 0;vertical-align:none;">
												<p style="text-align:center;">{if $oEvenement->plannifie > 0}<a href="#" title="Voir la lister des cours plannifiés" class="plannifiedEvent" iPlanifierClientId="{$oEvenement->client_id}" iPlanifierDateDebut="{$oEvenement->evenement_zDateHeureDebut}">{$oEvenement->plannifie}</a>{else}0{/if}</p>
										</td>
										<td class="col11" style="width:auto;text-align:center;;padding=6px 10px 0 0;vertical-align:none;">
											<input type="checkbox" class="suppr_{$oEvenement->evenement_id}" name="suppr_{$oEvenement->evenement_id}" id="suppr_{$oEvenement->evenement_id}" onclick="javascript:addIdEventToDelete({$oEvenement->evenement_id})" title="Cocher pour supprimer">&nbsp;<a href="{jurl 'evenement~FoEvenement:deleteEvent', array('iEvenementId'=>$oEvenement->evenement_id, 'iOption'=>1, 'zDateDebut'=>$toParams[0]->zDateDebut, 'zDateFin'=>$toParams[0]->zDateFin, 'iTypeEvenement'=>$toParams[0]->iTypeEvenement, 'iStagiaire'=>$toParams[0]->iStagiaire), false}"><img src="{$j_basepath}design/front/images/design/pictos/sub.png" alt="Supprimer" title="Supprimer" border="0" /></a>&nbsp;<a target="_blank" href="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEvenement->evenement_id, 'zDate'=>$oEvenement->evenement_zDateDebut, 'iTime'=>$oEvenement->evenement_zHeureDebut), false}"><img src="{$j_basepath}design/front/images/design/pictos/edit.png" alt="Editer" title="Editer" border="0" /></a>&nbsp;<a title="Validation du cours" href="#" iEventId="{$oEvenement->evenement_id}" class="validateCours" 
											zDate="{$oEvenement->evenement_zDateJoursDeLaSemaine} {$oEvenement->evenement_zDateHeureDebut|date_format:'%d/%m/%Y %H:%M'}"
											zDure="{$oEvenement->typeevenements_iDure} {if $oEvenement->typeevenements_iDureeTypeId == 1}h{else}mn{/if}"
											zClient="{if $oEvenement->client_id > 0}{$oEvenement->client_zNom} {$oEvenement->client_zPrenom}{/if}"
											zSociete="{if isset($oEvenement->societe_zNom) && $oEvenement->societe_zNom !=''}{$oEvenement->societe_zNom}{/if}"
											zDescription="{$oEvenement->evenement_zDescription|nl2br}" zType="{$oEvenement->typeevenements_zLibelle}" zUrlCodeAnomalie="{$oEvenement->url_code_anomalie}" ><img src="{$j_basepath}design/front/images/design/pictos/produit.png" alt="Validation du cours" title="Validation du cours" border="0" /></a>&nbsp;{if isset($oEvenement->evenementvalidation_id) && $oEvenement->evenementvalidation_id > 0 && isset($oEvenement->devalidable) && $oEvenement->devalidable == 1}<a title="Dévalider le cours" href="#" iValidationEventId="{$oEvenement->evenementvalidation_id}" class="invalidateCours"><img src="{$j_basepath}design/front/images/design/pictos/devalider.png" alt="Dévalider le cours" title="Dévalider le cours" border="0" /></a>{/if}
										</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
							<!-- begin Pagination -->
							<div class="navigPage clearfix" style="text-align:right;">
							{if $oNavBar->iNbPages > 1}
								{if $oNavBar->iPrevPage > 0}
									<a title="Précédent" href="{jurl 'evenement~FoEvenement:getEventListing', array('iCurrentPage' => $oNavBar->iPrevPage, 'dtcm_event_rdv'=>$toParams[0]->zDateDebut, 'cours_produit'=>$toParams[0]->cours_produit, 'dtcm_event_rdv1'=>$toParams[0]->zDateFin, 'groupe_id'=>$toParams[0]->groupe_id, 'professeurs'=>$toParams[0]->professeurs, 'evenement_iTypeEvenementId'=>$toParams[0]->iTypeEvenement, 'evenement_origine'=>$toParams[0]->evenement_origine, 'evenement_stagiaire'=>$toParams[0]->iStagiaire, 'iCheckDate'=>$toParams[0]->iCheckDate, 'z'=>$toParams[0]->iAfficheNomStagiaire, 'evenement_zSociete'=>$toParams[0]->evenement_zSociete)}"> < </a>&nbsp;
								{/if}
								{if $oNavBar->iShowFirst > 0}
									<a title="Page {$oNavBar->iNbPages}" href="{jurl 'evenement~FoEvenement:getEventListing', array('iCurrentPage' => 1, 'dtcm_event_rdv'=>$toParams[0]->zDateDebut, 'cours_produit'=>$toParams[0]->cours_produit, 'dtcm_event_rdv1'=>$toParams[0]->zDateFin, 'groupe_id'=>$toParams[0]->groupe_id, 'professeurs'=>$toParams[0]->professeurs, 'evenement_iTypeEvenementId'=>$toParams[0]->iTypeEvenement, 'evenement_origine'=>$toParams[0]->evenement_origine, 'evenement_stagiaire'=>$toParams[0]->iStagiaire, 'iCheckDate'=>$toParams[0]->iCheckDate, 'z'=>$toParams[0]->iAfficheNomStagiaire, 'evenement_zSociete'=>$toParams[0]->evenement_zSociete)}">1</a>&nbsp…&nbsp;
								{/if}

								{foreach $oNavBar->tiPages as $iPage}
									<a  title="Page {$iPage}" href="{jurl 'evenement~FoEvenement:getEventListing', array('iCurrentPage' => $iPage, 'dtcm_event_rdv'=>$toParams[0]->zDateDebut, 'cours_produit'=>$toParams[0]->cours_produit, 'dtcm_event_rdv1'=>$toParams[0]->zDateFin, 'groupe_id'=>$toParams[0]->groupe_id, 'professeurs'=>$toParams[0]->professeurs, 'evenement_iTypeEvenementId'=>$toParams[0]->iTypeEvenement, 'evenement_origine'=>$toParams[0]->evenement_origine, 'evenement_stagiaire'=>$toParams[0]->iStagiaire, 'iCheckDate'=>$toParams[0]->iCheckDate, 'z'=>$toParams[0]->iAfficheNomStagiaire, 'evenement_zSociete'=>$toParams[0]->evenement_zSociete)}" 
										{if $iPage == $oNavBar->iCurrPage} class="activePage" {/if}
									>{$iPage}</a>
								{/foreach}

								{if $oNavBar->iShowLast > 0}
									&nbsp…&nbsp<a title="Page {$oNavBar->iNbPages}" href="{jurl 'evenement~FoEvenement:getEventListing', array('iCurrentPage' => $oNavBar->iNbPages, 'dtcm_event_rdv'=>$toParams[0]->zDateDebut, 'cours_produit'=>$toParams[0]->cours_produit, 'dtcm_event_rdv1'=>$toParams[0]->zDateFin, 'groupe_id'=>$toParams[0]->groupe_id, 'professeurs'=>$toParams[0]->professeurs, 'evenement_iTypeEvenementId'=>$toParams[0]->iTypeEvenement, 'evenement_origine'=>$toParams[0]->evenement_origine, 'evenement_stagiaire'=>$toParams[0]->iStagiaire, 'iCheckDate'=>$toParams[0]->iCheckDate, 'z'=>$toParams[0]->iAfficheNomStagiaire, 'evenement_zSociete'=>$toParams[0]->evenement_zSociete)}">{$oNavBar->iNbPages}</a>&nbsp;
								{/if}

								{if $oNavBar->iNbPages > $oNavBar->iCurrPage}
									<a title="Suivant" href="{jurl 'evenement~FoEvenement:getEventListing', array('iCurrentPage' => $oNavBar->iNextPage, 'dtcm_event_rdv'=>$toParams[0]->zDateDebut, 'cours_produit'=>$toParams[0]->cours_produit, 'dtcm_event_rdv1'=>$toParams[0]->zDateFin, 'groupe_id'=>$toParams[0]->groupe_id, 'professeurs'=>$toParams[0]->professeurs, 'evenement_iTypeEvenementId'=>$toParams[0]->iTypeEvenement, 'evenement_origine'=>$toParams[0]->evenement_origine, 'evenement_stagiaire'=>$toParams[0]->iStagiaire, 'iCheckDate'=>$toParams[0]->iCheckDate, 'z'=>$toParams[0]->iAfficheNomStagiaire, 'evenement_zSociete'=>$toParams[0]->evenement_zSociete)}"> > </a>
								{/if}
							{/if}
							</div>
							<!-- end Pagination -->
					</div>
					<div class="input" style="width:600px;">
						<input type="button" value="Imprimer" class="boutonform submitForm" onclick="javascript:imprimerEventListing();"/>
						<input type="button" value="Supprimer les évènements séléctionnés" class="boutonform" onclick="javascript:suppressionMultipleEvent()"/>
						<input type="button" value="Exporter vers Excel" class="boutonform submitForm submitFormExport"/>
						<input type="button" value="Exporter vers Outlook" class="boutonform submitForm submitFormExportIcs"/>
					</div>
				</p>
				</form>
			</div>
		</div>
	</div>
</div>

<!--PRINT-->
	<div class="tabevent print">
		<table>
			<tbody>
				<tr style="background-color:red;">
					<th class="col1" style="width:180px;"><span>Date</span></th>
					<th class="col2" style="width:50px;">Durée</th>
					<th class="col3" style="width:auto;">&nbsp;&nbsp;&nbsp;&nbsp;Type d'événement</th>
					<th class="col4" style="width:auto;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;Stagiares</th>
					<th class="col5" style="width:auto;">Description de l'événement</th>
				</tr>
				{assign $i = 1}
				{foreach $toEvenementPrint as $oEvenement}
				<tr class="extra{$i++%2+1}" style="height: 30px;background-color:#899EB0;" >
					<td class="col1">
						<span>
							{$oEvenement->evenement_zDateJoursDeLaSemaine}&nbsp;
							{$oEvenement->evenement_zDateHeureDebut|date_format:'%d/%m/%Y %H:%M'}
						</span>
					</td>
					<td class="col2" style="width:50px;">&nbsp;&nbsp;{$oEvenement->typeevenements_iDure} {if $oEvenement->typeevenements_iDureeTypeId == 1}h{else}mn{/if}</td>
					<td class="col3" style="width:auto;">&nbsp;&nbsp;&nbsp;&nbsp;{if $oEvenement->typeevenements_id == ID_TYPE_EVENEMENT_DISPONIBLE || $oEvenement->typeevenements_id == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE}<p style="text-align:center;">-------</p>{else}{$oEvenement->typeevenements_zLibelle}{/if}</td>
					<td class="col4" style="width:auto;">
						{if $oEvenement->client_id > 0}
							<a href="#">&nbsp;&nbsp;&nbsp;&nbsp;{$oEvenement->client_zNom} {$oEvenement->client_zPrenom}</a>
							<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;Numero :</strong> {$oEvenement->client_id}<br /> 
							{if isset($oEvenement->societe_zNom) && $oEvenement->societe_zNom !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Société :</strong> {$oEvenement->societe_zNom}<br />
							{/if}  
							{if isset($oEvenement->client_zTel) && $oEvenement->client_zTel !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Tél :</strong> {$oEvenement->client_zTel}<br />
							{/if}
							{if isset($oEvenement->client_zPortable) && $oEvenement->client_zPortable !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Portable :</strong> {$oEvenement->client_zPortable}<br />
							{/if}  
							{if isset($oEvenement->evenement_zContactTel) && $oEvenement->evenement_zContactTel !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Tél pour le Jour :</strong> {$oEvenement->evenement_zContactTel}</p>
							{/if}
						{else}
							<p style="text-align:center;">-------</p>
						{/if}
					</td>
					<td class="col5"style="width:auto;">{$oEvenement->evenement_zDescription}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
<!--PRINT-->
{$footer}
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>

<!--POPUOP-->
<div class="pop-up popModifierEvent formevent clear" id="periodepop" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px;width:600px;">
<form id="edit_form" action="#" >
	<input class="classEvenementId" type="hidden" name="evenement_id" id="evenement_id" value="0" />
	<h2>Création / Modification d’évènement</h2>
	<a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
	<p class="clear"><br /></p> 
	<p class="clear">
		<label>Types d’évènement *</label>
		<select name="evenement_iTypeEvenementId" class="text classTypeEvenementId" id="evenement_iTypeEvenementId">
			<option value="0">----------------------Séléctionner----------------------</option>
			{foreach $toTypeEvenement as $oTypeEvenement}
				<option value="{$oTypeEvenement->typeevenements_id}" {if isset($oEvenement->typeevenements_id) && $oEvenement->typeevenements_id == $oTypeEvenement->typeevenements_id}selected="selected"{/if}>{$oTypeEvenement->typeevenements_zLibelle}</option>
			{/foreach}
		</select>
	</p>
	<p class="clear">
		<label>Description</label>
		<textarea style="height:auto" name="evenement_zDescription" id="evenement_zDescription" class="classDescription"></textarea>
	</p> 
	<p class="clear">
		<label>Stagiaire</label>
		<input type="hidden" class="iStagiairePop classStagiaireId" name="evenement_iStagiaire" id="evenement_iStagiaire" value="0" />
		<input type="hidden" name="urlTraitementStagiaireRecherche" id="urlTraitementStagiaireRecherche" value="{jurl 'client~FoClient:rechercherStagiaire'}" />
		<input type="hidden" name="urlAjoutStagiaire" id="urlAjoutStagiaire" value="" />
		<input style="width:296px;" type="text" class="text evenement_zStagiairepop" name="evenement_zStagiaire" id="evenement_zStagiaire" value="" />
		&nbsp;<a href="#" title="Rechercher" id="rechercherStagiaire">
			<img src="{$j_basepath}design/front/images/design/rechercher.png" alt="Ajouter un stagiaire" />
		</a>
	</p>
	<p class="clear" id="div-stagiaire-liste">
		<label for="dtcm_event_project">&nbsp;</label>
		<select style="width:400px;" name="stagiaire-liste" id="stagiaire-liste" size="5" url="">
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
		<label>Rendez vous</label>
		<input type="text" class="datedtcm_event_rdv text" id="dtcm_event_rdv" readonly="readonly" name="dtcm_event_rdv" value=""/>
	</p> 
	<div class="input">
		<a href="#" class="fermerPop"><input type="button" value="Annuler" class="boutonform" /></a>
		<input type="button" value="Modifier" class="boutonform submitFormulaire" />
	</div>
	<div class="input" style="width:480px;">
		<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
	</div>

</form>
</div>

<!--POPUOP-->

<!--POPUOP-->
<div class="pop-up popModifierEvent formevent clear" id="validatepop" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px;width:600px;">
<form id="editFormvalidation" action="#" >
	<h2>Validation</h2>
		<input type="hidden" id="criteriondtcm_event_rdv" name="criteriondtcm_event_rdv" value="">
		<input type="hidden" id="criteriondtcm_event_rdv1" name="criteriondtcm_event_rdv1" value="">
		<input type="hidden" id="criterionevenement_origine" name="criterionevenement_origine" value="">
		<input type="hidden" id="criterionevenement_iTypeEvenementId" name="criterionevenement_iTypeEvenementId" value="">
		<input type="hidden" id="criterionevenement_zStagiaire" name="criterionevenement_zStagiaire" value="">
		<input type="hidden" id="criterionevenement_zSociete" name="criterionevenement_zSociete" value="">
		<input type="hidden" id="criteriongroupe_id" name="criteriongroupe_id" value="">
		<input type="hidden" id="criterionprofesseurs" name="criterionprofesseurs" value="">

	<a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
	<p class="clear"><br /></p> 
	<p class="clear" id="p-txtphone"> 
		<label>Client : </label><span id="validatepopclient"></span>
	</p>
	<div style="display:none;">
	<p class="clear" id="p-txtphone">
		<br /><label><b>Infos evenement</b></label>
	</p> 
	<p class="clear" id="p-txtphone">
		<input type="hidden" id="validatepopEvenementId" name="evenement_id" value="">
		<input type="hidden" id="evenementvalidation_skype" name="evenementvalidation_skype" value="">
		<label>Date : </label><span id="validatepopdate"></span>
	</p> 
	<p class="clear" id="p-txtphone"> 
		<label>Durée : </label><span id="validatepopdure"></span>
	</p> 
	<p class="clear" id="p-txtphone"> 
		<label>Type d'evenement : </label><span id="validatepoptype"></span>
	</p> 
	<p class="clear" id="p-txtphone"> 
		<label>Societe : </label><span id="validatepopsociete"></span>
	</p> 
	<p class="clear" id="p-txtphone"> 
		<label>Description : </label><span id="validatepopdescription"></span>
	</p> 
	</div>
	<p class="clear" id="p-txtphone">
		<br /><label><b>Validation du cours</b></label>
	</p> 
	<p class="clear">
		<label>Presence du stagiaire * : </label>
		<select name="presence" class="text classTypeEvenementId" id="presence" style="width:auto;">
			<option value="0">----------------------Séléctionner----------------------</option>
			<option value="1">Présent</option>
			<option value="2">Absence maladie</option>
			<option value="3">Absence professionnelle</option>
			<option value="4">Absence autres</option>
			<option value="5">Reporté</option>
		</select>
	</p>
	<p class="clear">
		<label>Qualité audio (skype...)</label>
		<span id="validatepopskype" class="content-pop-inner"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img id="resetvalidatepopskype" src="{$j_basepath}design/front/images/design/pictos/reset.jpg" alt="Image" style="width:25px;height:28px;cursor: pointer;" title="Reset">
	</p>
<!-- Fiche d'environnement du stagiaire associe event-->
	<p class="clear">
		<label>Bureau isolé</label>
		<span style="font-size:1.2em;">
		<select name="bureau" class="text" id="bureau" style="width:50px;">
			<option value="0">Non</option>
			<option value="1">Oui</option>
		</select>
		</span>
	</p>
	<p class="clear">
		<label>Navigateur utilisé</label>
		<span style="font-size:1.2em;">
		<select name="navigateur" class="text" id="navigateur" style="width:auto;">
			<option value="0">------Séléctionner------</option>
			<option value="Mozilla Firefox">Mozilla Firefox</option>
			<option value="Internet Explorer">Internet Explorer</option>
			<option value="Google Chrome">Google Chrome</option>
			<option value="Opera">Opera</option>
			<option value="Safari">Safari</option>
			<option value="Autres">Autres</option>
		</select>
		</span>
	</p>
	<p class="clear">
		<label>Telephone  fixe</label>
		<input type="text" class="text" id="telFixe" name="telFixe" value="" style="width:200px;"/>
	</p> 
	<p class="clear">
		<label>Telephone  mobile</label>
		<input type="text" class="text" id="telMobile" name="telMobile" value="" style="width:200px;"/>
	</p> 
	<p class="clear">
		<label>Skype</label>
		<input type="text" class="text" id="skype" name="skype" value="" style="width:200px;"/>
	</p> 
	<p class="clear">
		<label>Casque</label>
		<span style="font-size:1.2em;">
		<select name="casqueSkype" class="text" id="casqueSkype" style="width:50px;">
			<option value="0">Non</option>
			<option value="1">Oui</option>
		</select>
		</span>
	</p>
	<!--------------------------------------------------------->
	<p class="clear">
		<label>Commentaires : </label>
		<textarea style="height:auto" name="description" id="description" class="classDescriptionValidation"></textarea>
	</p> 
	<p class="clear urlExtranet" style="display:none;">
		<label>&nbsp;</label>
		<a style="color:#0000FF;font-size:1.1em;font-weight:bold;" href="#" id="zUrlCodeAnomalie" target="_blank">Cliquez pour accéder à l'extranet</a>
	</p> 
	<div class="input">
		<a href="#" class="fermerPop"><input type="button" value="Annuler" class="boutonform" /></a>
		<input type="button" value="Valider" class="boutonform submitValidationCours" />
	</div>

</form>
</div>

<!--POPUOP-->