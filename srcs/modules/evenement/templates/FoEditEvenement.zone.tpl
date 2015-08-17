{assign $idTypeEvenementCourTelephone = ID_TYPE_EVENEMENT_COUR_TELEPHONE}

{literal}
<script type="text/javascript">
	$(function(){ 
		$(".content-pop-inner img").live('click',function() {

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
		$('.submitFormulaire').click(
			function(){
				var form = document.getElementById('edit_form');
				var isValid = tmt_validateForm(form);
				if(isValid){
					var iEvenementId = $('#evenement_id').val();
					var zDateTime = $('#dtcm_event_rdv').val();
					var iTypeEvent = $('#evenement_iTypeEvenementId').val();

					$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExistEdition", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
							if (datas == 0){
								$('#edit_form').submit();
							}else{
								if (iTypeEvent == 13 || iTypeEvent == 18){
									alert("La plage horaire est déja occupée.\nVous ne pouvez pas créer ou modifier un événement de type Disponible.") ;
								}else{
									$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExistEditionIsTypeEventDisponible", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
											if (datas == 13 || datas == 18){
												//alert("La plage horaire est déja occupée par un événement de type Disponible.") ;
												$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:desactiverEventDispo", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
													if (datas == 1){
														$('#edit_form').submit();
													}
													return false;
												});
											}else{
												if(confirm ("La plage horaire est déja occupée.\nVoulez-vous continuer ?"))
												{
													$("#x").val(1);
													$('#edit_form').submit();
												}
											}
										return false;
									});
								}
							}
						return false;
					});
				}
			}
		);

		$('.submitFormMail').click(
			function(){
				var form = document.getElementById('edit_form');
				var isValid = tmt_validateForm(form);
				if(isValid){
					var iEvenementId = $('#evenement_id').val();
					var zDateTime = $('#dtcm_event_rdv').val();
					$('#sendMail').val(1);
					$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExistEdition", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
							if (datas == 0){
								//$("#x").val(0);
								$('#edit_form').submit();
							}else{
								if(confirm ("La plage horaire est déja occupée.\nVoulez-vous continuer ?"))
								{
									$("#x").val(1);
									$('#edit_form').submit();
								}
							}
						return false;
					});
				}
			}
		);

		var url=j_basepath + "index.php?module=evenement&action=FoEvenement:autocompleteStagiaire";
		$('#evenement_zStagiaire').autocomplete(url,{
			/*mustMatch : true,*/
			minChars: 0,
			autoFill: false,
			scroll: true,
			scrollHeight: 300,
			dataType: "json" ,
			parse : autoCompleteJson,
			formatItem: function(row) {
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
			if (typeof(row) === undefined) {		
				$('#evenement_iStagiaire').val(0);		
				$('#evenement_zStagiaire').val("");		
			} else {
				$('.contentComposant').empty();
				$('.contentComposant').html("<table cellspacing='1' cellpadding='1' style='text-align:center;border:thin double;' border='1'><tbody><tr><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Date max validation&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Solde avant saisie&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Heures prevues&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Cours produit&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Professeur&nbsp;&nbsp;</th></tr></tbody></table>");

				if (row !== undefined){
					$('#evenement_iStagiaire').val(row["client_id"]);
					$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:chargeParIdAndComposantCours", iStagiaireId:$('#evenement_iStagiaire').val()}, function(datas){
						if (datas.length >= 1){
							var zContent = "" ;
							var checked = "" ;
							for (var i=0; i<datas.length;i++){
								var row = datas[i] ;
								/*if (i==0){
									checked ="checked";
								}else{
									checked = "" ;
								}*/
								if (row['NUMERO'] > 0){
								var hp = 0; 
								var hprev = 0; 
									if (row['clientsolde_produit'] != null){
										hp = row['clientsolde_produit'] ; 
										hprev = row['clientsolde_prevu'] ;
									}else if (row['HEURES_PRODUITES'] != null){
										hp = row['HEURES_PRODUITES'] ; 
										hprev = row['HEURES_PREVUES'] ; 
									}
									var solde = parseFloat(hprev) - parseFloat(hp) ;
									if (solde <= 0){
										$('#evenement_iStagiaire').val(0);		
										alert("Le stagiaire que vous avez choisi n'a plus de solde de cours!!!");
									}

									$("#evenement_solde").val(solde) ;
									$("#evenement_prevu").val(hprev) ;
									$("#evenement_produit").val(hp) ;

									zContent += "<tr><td style='text-align:center;background-color:#899EB0;'>"+row['Date_max_validation_format']+"</td><td style='text-align:center;background-color:#899EB0;'>"+solde+"</td><td style='text-align:center;background-color:#899EB0;'>"+row['HEURES_PREVUES']+"</td><td style='text-align:center;background-color:#899EB0;'>"+hp+"</td><td style='text-align:center;background-color:#899EB0;'>"+row['INDIVIDUSNOMFAMILLE']+" "+row['INDIVIDUSPRENOM'] + "&nbsp;&nbsp;<a href='#' title='Information sur le composant du cours' id='infoComposant' NUMERO='"+row['NUMERO']+"' CODE_STAGIAIRE_MIRACLE='"+row['CODE_STAGIAIRE_MIRACLE']+"' STAGE='"+row['STAGE']+"' STAGESOCIETE='"+row['STAGESOCIETE']+"' STAGELANGUE='"+row['STAGELANGUE']+"' NIVEAU='"+row['NIVEAU']+"' INDIVIDUS_1NOMFAMILLE='"+row['INDIVIDUS_1NOMFAMILLE']+"' INDIVIDUS_1PRENOM='"+row['INDIVIDUS_1PRENOM']+"' COMPTEUR='"+row['COMPTEUR']+"' REFPRODUIT='"+row['REFPRODUIT']+"' TYPEPRODUCTION='"+row['TYPEPRODUCTION']+"' PROF_NO='"+row['PROF_NO']+"' INDIVIDUSNOMFAMILLE='"+row['INDIVIDUSNOMFAMILLE']+"' INDIVIDUSPRENOM='"+row['INDIVIDUSPRENOM']+"' DATEAFFECTATION='"+row['DATEAFFECTATION']+"' HEURES_PREVUES='"+row['HEURES_PREVUES']+"' LIBELLESTAGIAIRE='"+row['LIBELLESTAGIAIRE']+"' QTE_PRODUITES='"+row['QTE_PRODUITES']+"' HEURES_PRODUITES='"+hp+"' DATE_TRANSFERT_ACCESS='"+row['DATE_TRANSFERT_ACCESS']+"' DATE_TRANSFERT_WEBCAL='"+row['DATE_TRANSFERT_WEBCAL']+"' class='infoComposant'><img src='{/literal}{$j_basepath}{literal}design/front/images/design/pictos/information.png' alt='Information sur le composant du cours' /></a></td></tr>";
									
								}
							}
							if (zContent != ""){
								$('.contentComposant').empty();
								$('.contentComposant').html("<table cellspacing='1' cellpadding='1' style='text-align:center;border:thin double;' border='1'><tbody><tr><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Date max validation&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Solde avant saisie&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Heures prevues&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Cours produit&nbsp;&nbsp;</th><th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Professeur&nbsp;&nbsp;</th></tr>" + zContent + "</tbody></table>");
								$('.enveloperiodecomposantCours').attr('style', 'display:block;');
							}
							datas = datas[0] ;
								
							$('#div-stagiaire-liste').hide();
							$('#evenement_zStagiaire').val('');
							$('#evenement_zLibelle').val('');
							$('#evenement_zLibelle').val(datas["client_zNom"]); 
							$('#evenement_iStagiaire').val(); 
							var html = datas["client_zNom"] + ' ' + datas["client_zPrenom"];
							$('#evenement_zStagiaire').val(html);
							$('#evenement_iStagiaire').val(datas["client_id"]);
							$('#p-txtville').show();
							$('#p-txtsociete').show();
							$('#p-txtphone').show();
							$('#p-txtmail').show();

							$('#txtphone').val(datas["client_zTel"]);
							$('#evenement_zContactTel').val(datas["client_zTel"]);
							$('#txtsociete').val(datas["societe_zNom"]);
							$('#txtville').val(datas["client_zVille"]);
							$('#txtmail').val(datas["client_zMail"]);						
						}
						
					});
				}
			}
		}).blur(function(){
			//$(this).search();
		});

		$('.periodicite').hide();
		$('.infoComposant').live( "click", 
			function (){
				alert("NUMERO = " + $(this).attr('NUMERO') + "\n" + 
						"Code stagiaire miracle = " + $(this).attr('CODE_STAGIAIRE_MIRACLE') + "\n" + 
						"Stage = " + $(this).attr('STAGE') + "\n" + 
						"Société = " + $(this).attr('STAGESOCIETE') + "\n" + 
						"Langue = " + $(this).attr('STAGELANGUE') + "\n" + 
						"Niveau = " + $(this).attr('NIVEAU') + "\n" + 
						"Nom stagiaire = " + $(this).attr('INDIVIDUS_1NOMFAMILLE') + " " + $(this).attr('INDIVIDUS_1PRENOM') + "\n" + 
						"Compteur = " + $(this).attr('COMPTEUR') + "\n" + 
						"Ref de produit = " + $(this).attr('REFPRODUIT') + "\n" + 
						"Type de production = " + $(this).attr('TYPEPRODUCTION') + "\n" + 
						"Prof NO = " + $(this).attr('PROF_NO') + "\n" + 
						"Nom Prof = " + $(this).attr('INDIVIDUSNOMFAMILLE') + " " + $(this).attr('INDIVIDUSPRENOM') + "\n" + 
						"Date d'affectation = " + $(this).attr('DATEAFFECTATION') + "\n" + 
						"Heures prevues = " + $(this).attr('HEURES_PREVUES') + "\n" + 
						"Libellé stagiaire = " + $(this).attr('LIBELLESTAGIAIRE') + "\n" + 
						"Qte produites = " + $(this).attr('QTE_PRODUITES') + "\n" + 
						"Cours produit à la fin du mois dernier = " + $(this).attr('HEURES_PRODUITES') + "\n" );
			}
		);

		$('.date').datepicker({
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
		$("#evenement_iTypeEvenementId").change(function(){
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:getTypeEvenement", iTypeEvenementId:$('#evenement_iTypeEvenementId').val()}, function(datas){
					$('#div-stagiaire-liste').hide();
					//$('#evenement_zStagiaire').val('');
					$('#evenement_zLibelle').val('');
					$('#evenement_zLibelle').val(datas["client_zNom"]); 

					var zDuree = datas["typeevenements_iDure"]; 
					if (datas["typeevenements_iDureeTypeId"] == 1){
						zDuree += ' heures';
					}else{
						zDuree += ' minutes';
					}
					$("#evenement_iDuree").val(zDuree);
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
					var dtcm_event_rdv = $('#dtcm_event_rdv').val().split(' ');
					var dateDebut = dtcm_event_rdv[0]; 
					$('#dtcm_event_rdv_periodicite').val(dateDebut);

					var heureDebut = dtcm_event_rdv[1]; 
					var heureDebutFinal = heureDebut.split(':');
					var heureDebutRendezVous = heureDebutFinal[0]+':'+heureDebutFinal[1];
					$("#evenement_heureDebutRendezVous option:selected").attr("selected",'');// on met simplement la valeur de l'attribut à vide
					$('#evenement_heureDebutRendezVous option[value='+heureDebutRendezVous+']').attr("selected","selected");

					var evenement_iDuree = $('#evenement_iDuree').val();
					$("#evenement_heureDureeRendezVous option:selected").attr("selected",'');// on met simplement la valeur de l'attribut à vide
					$('#evenement_heureDureeRendezVous option[value='+evenement_iDuree+']').attr("selected","selected");


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

		$('#resetvalidatepopskype').click(function (){
			content = '<img id="image_0" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="'+j_basepath+'design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">';
			$('#validatepopskype').html('');
			$('#validatepopskype').html(content);
			$('#evenementvalidation_skype').val(0);
		});
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

	function getInfoComposantCours (solde, prevu, produit){
		$("#evenement_solde").val(solde) ;
		$("#evenement_prevu").val(prevu) ;
		$("#evenement_produit").val(produit) ;
	}
	DD_roundies.addRule('div.formevent', '5px');
	DD_roundies.addRule('input.boutonform', '5px');
</script>
{/literal}
<form id="edit_form" action="{jurl 'evenement~FoEvenement:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
	<input type="hidden" name="evenement_id" id="evenement_id" value="{if $bEdit}{$oEvenement->evenement_id}{else}0{/if}" />
	<input type="hidden" name="evenement_origine" id="evenement_origine" value="{if $bEdit}{$oEvenement->evenement_origine}{else}2{/if}" />
	<input type="hidden" name="sendMail" id="sendMail" value="0" />
	<input type="hidden" name="evenement_iPriorite" id="evenement_iPriorite" value="1" />
	<input type="hidden" name="iAffichage" id="iAffichage" value="{$iAffichage}" />
	<input type="hidden" name="zDate" id="zDate" value="{$zDate}" />
	<input type="hidden" class="text" name="evenement_zLibelle" id="evenement_zLibelle" value="{if $bEdit}{$oEvenement->evenement_zLibelle}{/if}"> 
	<input type="hidden" name="evenement_iContactTel" id="evenement_iContactTel" value="0" />
	<input type="hidden" name="finPeriodiciteOccurence" id="finPeriodiciteOccurence" value="0" />
	<input type="hidden" name="periodiciteMensuel1" id="periodiciteMensuel1" value="0" />
	<input type="hidden" name="evenement_zDateHeureSaisie" id="evenement_zDateHeureSaisie" value="{if $bEdit}{$oEvenement->evenement_zDateHeureSaisie}{else}{$currentDate}{/if}" />
	<input type="hidden" name="x" id="x" value="{$x}" />

	<input type="hidden" name="prec" id="prec" value="{$prec}" />
	<input type="hidden" name="debut" id="debut" value="{$debut}" />
	<input type="hidden" name="fin" id="fin" value="{$fin}" />

	<input type="hidden" name="evenement_solde" id="evenement_solde" value="{if $bEdit}{$oEvenement->evenement_solde}{else}0{/if}" />
	<input type="hidden" name="evenement_prevu" id="evenement_prevu" value="{if $bEdit}{$oEvenement->evenement_prevu}{else}0{/if}" />
	<input type="hidden" name="evenement_produit" id="evenement_produit" value="{if $bEdit}{$oEvenement->evenement_produit}{else}0{/if}" />

	<h2>Création / Modification d’évènement</h2>
	<p class="clear">
		<label>Types d’évènement *</label>
		{foreach $toTypeEvenement as $oTypeEvenement}
			<input type="hidden" id="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" name="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" value="{$oTypeEvenement->typeevenements_iStagiaireActif}" />
		{/foreach}
		<select name="evenement_iTypeEvenementId" class="text" id="evenement_iTypeEvenementId" tmt:invalidindex="0" tmt:required="true" >
			<option value="0">----------------------Séléctionner----------------------</option>
			{foreach $toTypeEvenement as $oTypeEvenement}
				<option value="{$oTypeEvenement->typeevenements_id}" {if isset($tEvent['evenement_iTypeEvenementId']) && $tEvent['evenement_iTypeEvenementId'] == $oTypeEvenement->typeevenements_id}selected="selected"{/if}
				{if $bEdit}
					{if $oEvenement->evenement_iTypeEvenementId==$oTypeEvenement->typeevenements_id}
						selected=selected 
					{/if}
				{else}
					{if $oTypeEvenement->typeevenements_id == $idTypeEvenementCourTelephone}
							selected=selected
					{/if}
				{/if}
				>{$oTypeEvenement->typeevenements_zLibelle}</option>
			{/foreach}
		</select>
	</p>
	<p class="clear">
		<label>Description</label>
		<textarea style="height:auto" name="evenement_zDescription" id="evenement_zDescription">{if isset($tEvent['evenement_zDescription'])}{$tEvent['evenement_zDescription']}{/if}{if $bEdit}{$oEvenement->evenement_zDescription}{/if}</textarea>
	</p> 
	<p class="clear">
		<label>Stagiaire</label>
		<input type="hidden" name="evenement_iStagiaire" id="evenement_iStagiaire" value="{if $bEdit}{$oEvenement->evenement_iStagiaire}{else}0{/if}" />
		<input type="hidden" name="urlTraitementStagiaireRecherche" id="urlTraitementStagiaireRecherche" value="{jurl 'client~FoClient:rechercherStagiaire'}" />
		<input type="hidden" name="urlAjoutStagiaire" id="urlAjoutStagiaire" value="{jurl 'client~FoClient:add', array('iEvenementId'=>$iEvenementId), false}" />
		<input style="width:296px;" type="text" class="text" name="evenement_zStagiaire" id="evenement_zStagiaire" value="{if $bEdit}{if isset($oStagiaire->client_zNom)}{$oStagiaire->client_zNom} {$oStagiaire->client_zPrenom}{/if}{/if}" />
		&nbsp;<a href="#" title="Rechercher" id="rechercherStagiaire">
			<img src="{$j_basepath}design/front/images/design/rechercher.png" alt="Ajouter un stagiaire" />
		</a>
		&nbsp;<!--<a href="#" title="Ajouter un stagiaire" id="addNewStagiaire">
			<img src="{$j_basepath}design/front/images/design/buttons/plus.png" alt="Ajouter un stagiaire" />
		</a>-->		
		{if $bEdit && isset($oEvenement->evenement_iStagiaire) && $oEvenement->evenement_iStagiaire > 0}
		&nbsp;<a href="{jurl 'client~FoClient:add', array('iClientId'=>$oEvenement->evenement_iStagiaire), false}" id="imgInfoStagiaire" title="Detail du stagiaire" target="_blank">
			<!--img src="{$j_basepath}design/front/images/design/icone_info.png" alt="Detail du stagiaire" /-->
			<span style="background-color: #1E364E; border: 2px solid #1E364E; border-radius: 0; color: #FFFFFF; cursor: pointer; font-size: 1.2em; font-weight: bold;">&nbsp;INFO&nbsp;</span>

		</a>
			{if isset($zUrlCodeAnomalie) && $zUrlCodeAnomalie != ""}
			&nbsp;&nbsp;<a href="{$zUrlCodeAnomalie}" id="codeAnomalie" title="Code anomalie extranet" target="_blank">
				<!--<img src="{$j_basepath}design/front/images/design/arrow-ressources.png" alt="Code anomalie extranet" /-->
				<span style="background-color: #1E364E; border: 2px solid #1E364E; border-radius: 0; color: #FFFFFF; cursor: pointer; font-size: 1.2em; font-weight: bold;">&nbsp;EXTRANET&nbsp;</span>
			</a>
			{/if}
		{/if}
	</p>
	<p class="clear" id="div-stagiaire-liste">
		<label for="dtcm_event_project">&nbsp;</label>
		<select style="width:400px;" name="stagiaire-liste" id="stagiaire-liste" size="5" url="">
			<option></option>
		</select>
	</p>

	<div class="enveloperiode enveloperiodecomposantCours clear" style="">
		<fieldset class="composantCours" id="composantCours" style="padding-left: 10px; border-left-width: 1px; margin-left: 80px; width: 530px; margin-bottom: 10px;">
		<legend>Composants de cours</legend>
		<div class="clear contentComposant">
			<table cellspacing='1' cellpadding='1' style='text-align:center;border:thin double;' border='1'>
				<tbody>
					<tr>
						<th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Date max validation&nbsp;&nbsp;</th>
						<th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Solde avant saisie&nbsp;&nbsp;</th>
						<th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Heures prevues&nbsp;&nbsp;</th>
						<th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Cours produit&nbsp;&nbsp;</th>
						<th style='text-align:center;background-color:#2C2C2C;color:#FFFFFF;'>&nbsp;&nbsp;Professeur&nbsp;&nbsp;</th>
					</tr>
					{if $bEdit && isset($oEvenement->soldeavantsaisie) && $oEvenement->soldeavantsaisie != null}
					<tr>
						<td style="text-align:center;background-color:#899EB0;">{$oEvenement->Date_max_validation_format}</td>
						<td style="text-align:center;background-color:#899EB0;">{$oEvenement->soldeavantsaisie}</td>
						<td style="text-align:center;background-color:#899EB0;">{$oEvenement->HEURES_PREVUES}</td>
						<td style="text-align:center;background-color:#899EB0;">{$oEvenement->HEURES_PRODUITES}</td>
						<td style="text-align:center;background-color:#899EB0;">{$oEvenement->utilisateur_zNom}&nbsp;{$oEvenement->utilisateur_zPrenom}</td>
					</tr>
					{/if}
				</tbody>
			</table>
		</div>
		</fieldset>
	</div>
	
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
		<input type="text" class="date text" id="dtcm_event_rdv" readonly="readonly" name="dtcm_event_rdv" value="{$zDateDefaultEvent}" tmt:required="true"/>
	</p> 
	<p class="rdv clear">
		<label>Tel. pour ce jour</label>
		<input type="text" name="evenement_zContactTel" id="evenement_zContactTel" class="text" value="{if isset($oEvenement->evenement_zContactTel) && ($oEvenement->evenement_zContactTel != '' || $oEvenement->evenement_zContactTel != NULL)}{$oEvenement->evenement_zContactTel}{else}{if isset($oStagiaire->client_id) && $oStagiaire->client_id > 0}{if isset($oStagiaire->client_zTel) && $oStagiaire->client_zTel != ''}{$oStagiaire->client_zTel}{else}{if isset($oStagiaire->client_zPortable) && $oStagiaire->client_zPortable != ''}{$oStagiaire->client_zPortable}{/if}{/if}{/if}{/if}"/>
		<input type="button" value="C'est le stagiaire qui appelle" id="appelStagiaire" class="boutonforms" />
	</p>
	<p class="duree clear">
		<label>Durée</label>
		{if isset($tEvent['evenement_iDuree'])}
			{assign $zDureParDefaut = $tEvent['evenement_iDuree']}
		{else}
			{if !$bEdit}
				{assign $zDureParDefaut = '30 minutes'}
			{else}
				{if $oEvenement->evenement_iDureeTypeId == 1}
					{assign $zDureParDefaut = $oEvenement->evenement_iDuree . ' heures'}
				{else}
					{assign $zDureParDefaut = $oEvenement->evenement_iDuree . ' minutes'} 
				{/if}
			{/if}
		{/if}
		{*<!--select style="width:120px;"name="evenement_iDuree" class="text" id="evenement_iDuree">
			<option value="0">---------Durée---------</option>
			{foreach $toDurePeriodicite as $oDurePeriodicite}
			<option value="{$oDurePeriodicite}" {if isset($zDureParDefaut) && $oDurePeriodicite == $zDureParDefaut}selected='selected'{/if}>{$oDurePeriodicite}</option>
			{/foreach}
		</select-->*}
		<input type="text" id="evenement_iDuree"  name="evenement_iDuree" value="{$zDureParDefaut}" selectBoxOptions="{$zDurePeriodicite}" style="width:120px;margin-left:180px;">
	</p>
	<p class="rappel clear">
		<label>Rappel</label>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="1" {if $bEdit}{if $oEvenement->evenement_iRappel>0}checked="checked"{/if}{/if}/>
		<span>Oui</span>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="0" {if $bEdit}{if $oEvenement->evenement_iRappel==0}checked="checked"{/if}{else}checked="checked"{/if}/>
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
		<input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="1" {if $bEdit}{if $oEvenement->evenement_iStatut == STATUT_PUBLIE}checked="checked"{/if}{else}checked="checked"{/if} tmt:required="true"/><span>Afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="2" {if $bEdit}{if $oEvenement->evenement_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if}{/if} /><span>Ne pas afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="0" {if $bEdit}{if $oEvenement->evenement_iStatut == STATUT_DESACTIVE}checked="checked"{/if}{/if} /><span>Annuler</span>
	</p>
	{if $bEdit}
	<div class="enveloperiode clear" style="">
		<div class="validation clear">
			<fieldset class="heure">
				<legend>Validation du cour / Environnement du stagiaire</legend>
				<p class="clear">
					<label  style=" float: left; text-align: left; width: 120px;">Libellé</label>
					<span style="font-size:1.2em;">
					<select name="validationpresence" class="text" id="validationpresence" style="width:auto;">
						<option value="0">----------------------Séléctionner----------------------</option>
						<option value="1" {if $bEdit && isset($oEvenement->validation_validationId) && $oEvenement->validation_validationId==1} selected="selected"{/if}>Présent</option>
						<option value="2" {if $bEdit && isset($oEvenement->validation_validationId) && $oEvenement->validation_validationId==2} selected="selected"{/if}>Absence maladie</option>
						<option value="3" {if $bEdit && isset($oEvenement->validation_validationId) && $oEvenement->validation_validationId==3} selected="selected"{/if}>Absence professionnelle</option>
						<option value="4" {if $bEdit && isset($oEvenement->validation_validationId) && $oEvenement->validation_validationId==4} selected="selected"{/if}>Absence autres</option>
						<option value="5" {if $bEdit && isset($oEvenement->validation_validationId) && $oEvenement->validation_validationId==5} selected="selected"{/if}>Reporté</option>
					</select>
					</span>
				</p>
				<p class="clear">
					<label  style=" float: left; text-align: left; width: 120px;">Qualité audio (skype...)</label>
					<input id="evenementvalidation_skype" type="hidden" value="{if $bEdit && isset($oEvenement->evenementvalidation_skype)}{$oEvenement->evenementvalidation_skype}{/if}" name="evenementvalidation_skype">
					<span style="font-size:1.2em;" id="validatepopskype" class="content-pop-inner">
						{if $bEdit && isset($oEvenement->evenementvalidation_skype) && $oEvenement->evenementvalidation_skype==1}
						<img id="image_0" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">
						{/if}
						{if $bEdit && isset($oEvenement->evenementvalidation_skype) && $oEvenement->evenementvalidation_skype==2}
						<img id="image_0" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">
						{/if}
						{if $bEdit && isset($oEvenement->evenementvalidation_skype) && $oEvenement->evenementvalidation_skype==3}
						<img id="image_0" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">
						{/if}
						{if $bEdit && isset($oEvenement->evenementvalidation_skype) && $oEvenement->evenementvalidation_skype==4}
						<img id="image_0" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">
						{/if}
						{if $bEdit && isset($oEvenement->evenementvalidation_skype) && $oEvenement->evenementvalidation_skype==5}
						<img id="image_0" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="{$j_basepath}design/front/images/design/etoile-jaune.jpg" alt="Image" style="cursor: pointer;">
						{/if}
						{if $bEdit && isset($oEvenement->evenementvalidation_skype) && $oEvenement->evenementvalidation_skype==0}
						<img id="image_0" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_1" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_2" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_3" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;"><img id="image_4" src="{$j_basepath}design/front/images/design/etoile-blanche.jpg" alt="Image" style="cursor: pointer;">
						{/if}

					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img id="resetvalidatepopskype" src="{$j_basepath}design/front/images/design/pictos/reset.jpg" alt="Image" style="width:25px;height:28px;cursor: pointer;" title="Reset">
				</p>

<!-- Fiche d'environnement du stagiaire associe event-->
				<p class="clear">
					<label style=" float: left; text-align: left; width: 120px;">Bureau isolé</label>
					<span style="font-size:1.2em;">
					<select name="bureau" class="text" id="bureau" style="width:auto;">
						<option value="0" {if isset($oEvenement->bureau) && $oEvenement->bureau == 0}selected=selected{/if}>Non</option>
						<option value="1" {if isset($oEvenement->bureau) && $oEvenement->bureau == 1}selected=selected{/if}>Oui</option>
					</select>
					</span>
				</p>
				<p class="clear">
					<label style=" float: left; text-align: left; width: 120px;">Navigateur utilisé</label>
					<span style="font-size:1.2em;">
					<select name="navigateur" class="text" id="navigateur" style="width:auto;">
						<option value="0">------Séléctionner------</option>
						<option value="Mozilla Firefox"  {if isset($oEvenement->navigateur) && $oEvenement->navigateur == "Mozilla Firefox"}selected=selected{/if}>Mozilla Firefox</option>
						<option value="Internet Explorer" {if isset($oEvenement->navigateur) && $oEvenement->navigateur == "Internet Explorer"}selected=selected{/if}>Internet Explorer</option>
						<option value="Google Chrome" {if isset($oEvenement->navigateur) && $oEvenement->navigateur == "Google Chrome"}selected=selected{/if}>Google Chrome</option>
						<option value="Opera" {if isset($oEvenement->navigateur) && $oEvenement->navigateur == "Opera"}selected=selected{/if}>Opera</option>
						<option value="Safari" {if isset($oEvenement->navigateur) && $oEvenement->navigateur == "Safari"}selected=selected{/if}>Safari</option>
						<option value="Autres" {if isset($oEvenement->navigateur) && $oEvenement->navigateur == "Autres"}selected=selected{/if}>Autres</option>
					</select>
					</span>
				</p>
				<p class="clear">
					<label style=" float: left; text-align: left; width: 120px;">Telephone  fixe</label>
					<input type="text" class="text" id="telFixe" name="telFixe" value="{if isset($oEvenement->telFixe) && $oEvenement->telFixe != ''}{$oEvenement->telFixe}{/if}" style="width:200px;"/>
				</p> 
				<p class="clear">
					<label style=" float: left; text-align: left; width: 120px;">Telephone  mobile</label>
					<input type="text" class="text" id="telMobile" name="telMobile" value="{if isset($oEvenement->telMobile) && $oEvenement->telMobile != ''}{$oEvenement->telMobile}{/if}" style="width:200px;"/>
				</p> 
				<p class="clear">
					<label style=" float: left; text-align: left; width: 120px;">Skype</label>
					<input type="text" class="text" id="skype" name="skype" value="{if isset($oEvenement->skype) && $oEvenement->skype != ''}{$oEvenement->skype}{/if}" style="width:200px;"/>
				</p> 
				<p class="clear">
					<label style=" float: left; text-align: left; width: 120px;">Casque</label>
					<span style="font-size:1.2em;">
					<select name="casqueSkype" class="text" id="casqueSkype" style="width:auto;">
						<option value="0" {if isset($oEvenement->casqueSkype) && $oEvenement->casqueSkype == 0}selected=selected{/if}>Non</option>
						<option value="1" {if isset($oEvenement->casqueSkype) && $oEvenement->casqueSkype == 1}selected=selected{/if}>Oui</option>
					</select>
					</span>
				</p>
<!-- Fiche d'environnement du stagiaire associe event-->


				<p class="clear">
					<label  style=" float: left; text-align: left; width: 120px;">Commentaires</label>
					<span style="font-size:1.2em;">
					<textarea id="validationcomment" name="validationcomment" style="width:300px;height:50px">{if $bEdit && isset($oEvenement->validation_zComment) && $oEvenement->validation_zComment!=""}{$oEvenement->validation_zComment}{/if}</textarea>
					</span>
				</p>
			</fieldset>
		</div>
	</div>
	{/if}
	<div class="enveloperiode clear" {if $bEdit}style="display:none;"{/if}>
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
    <div class="plageleft">
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
	<div class="input">
		<a href="#" onclick="javascript:history.back();"><input type="button" value="Annuler" class="boutonform" /></a>
		<input type="button" value="{if $bEdit}Valider{else}Créer{/if}" class="boutonform submitFormulaire" />
		<input type="button" value="Valider avec envoi de mail" class="boutonform longtext submitFormMail" />
	</div>
	<div class="input" style="width:480px;">
		<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
	</div>
</form>

{literal}
	<style type="text/css">
	.selectBoxArrow{
		margin-top:1px;
		float:left;
		position:absolute;
		right:1px;
		margin-right:-180px;
	}	
	.selectBoxInput{
		border:0px;
		padding-left:1px;
		height:19px;
		position:absolute;
		top:0px;
		left:0px;
	}

	.selectBox{
		/*border:1px solid #7f9db9;*/
		height:20px;	
	
	}
	.selectBoxOptionContainer{
		position:absolute;
		border:1px solid #7f9db9;
		height:100px;
		background-color:#FFF;
		left:-1px;
		top:20px;
		visibility:hidden;
		overflow:auto;
		z-index:1000;
		margin-left:180px;
		height:250px;
	}
	.selectBoxIframe{
		position:absolute;
		background-color:#FFF;
		border:0px;
		z-index:999;
	}
	.selectBoxAnOption{
		font-family:arial;
		font-size:12px;
		cursor:default;
		margin:1px;
		overflow:hidden;
		white-space:nowrap;
	}
	</style>
<script type="text/javascript">
createEditableSelect(document.forms[0].evenement_iDuree);
</script>
{/literal}

