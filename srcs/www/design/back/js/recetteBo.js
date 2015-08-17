// JavaScript Document
/* document Ready */
$(document).ready(function() {
	ErrorControle = "";
   	var initErrorNbProduitAssocie		= "<br>- Veuillez choisir 5 produits au maximun pour la recette.";

	var val = $('#recette_image').val();

	if (val != ""){
		$("#recette_image").hide () ;
		$("#browseImgRecette").hide () ;
	}else{
		$("#Petit_Vivuel").hide () ;
	}

	if($('#ErreurlisteProduit').val() == 1){
		$('.errorMessage').html(initErrorNbProduitAssocie).show();
	}

	$('.parcourir').click(function(){
		var finder = new CKFinder() ;
		finder.BasePath = j_finder_basePath ;	
		finder.SelectFunction = SetFileFieldImageTitre;
		finder.Popup() ;
		return false;
	});

	$('#supprImg').click(function(){
		document.getElementById ("recette_image").value = "" ;
		$("#recette_image").show () ;
		$("#browseImgRecette").show () ;
		$("#Petit_Vivuel").hide () ;
		if (g_supprvisuel) {
			suppr = $('#petitVisuel').val();				
			suppr2 = $('#grandVisuel').val();
			suppr3 = $('#Vignette').val();

			$.ajax({
				type:"POST",
				url:j_basepath+'admin.php',
				data:"module=recette&action=recetteBo:traitementVisuels&process=suppr&fichier1="+suppr+"&fichier2="+suppr2+"&fichier3="+suppr3,
				dataType:"json",
				async:false
			});
		}
	
	});

	$('#AnnulerEditRecette').click(function(){
		if($(this).attr('id') == "AnnulerEditRecette") {
					if (g_supprvisuel) {
						suppr = $('#petitVisuel').val();				
						suppr2 = $('#grandVisuel').val();
						suppr3 = $('#Vignette').val();
						
						$.ajax({
							 type:"POST",
							 url:j_basepath+'admin.php',
							 data:"module=recette&action=recetteBo:traitementVisuels&process=suppr&fichier1="+suppr+"&fichier2="+suppr2+"&fichier3="+suppr3,
							 dataType:"json",
							 async:false
						});
					}
		}
	});

	//Errreur
	if ($('#erreurFormulaire').val() != 0){
		$('.errorMessage').html('');
		var zErrorMsg = "Certaines informations sont incomplètes ou invalides, veuillez les compléter correctement.<br>";
		$('.errorMessage').attr('style', 'display:block;');
		$('.errorMessage').html(zErrorMsg);
	}

});

//Controle formulaire de contact
function catchError (frm, invalidFields){ 
	var initErrorMsg = "";
	var initErrorLibelle				= "<br>- Veuillez choisir un libellé pour la recette.";
	var initErrorVisuelPetit			= "<br> -Veuillez choisir une image pour la recette.";
	var initErrorTempsPreparation		= "<br> -Veuillez préciser le temps de préparation pour la recette. Il doit être une entier";
	var initErrorTempsCuisson			= "<br> -Veuillez  préciser le temps de cuisson pour la recette. Il doit être une entier";
	var initErrorDescriptif				= "<br> -Veuillez  préciser le descriptif de la préparation de la recette.";
	var initErrorListeIngredient		= "<br> -Veuillez  préciser au moins un ingredient pour la recette.";
	var initErrorNbProduitAssocie		= "<br>- Veuillez choisir 5 produits au maximun pour la recette.";
	var initErrorNombrePersonne			= "<br>Le nombre de personne pour la recette doit être un entier compris entre 1 et 10";

	var bCheckForm = false;
	var ValideIngredient = false;
	$('[id^=ingredientRecettelibelle]').each(function(){
		if($(this).attr('value') != ""){
			ValideIngredient = true;
		}
	});
	for(i=0;i<invalidFields.length;i++){
		if(invalidFields[i].name == 'recetteLibelle'){
			initErrorMsg += initErrorLibelle;

		}
		if(invalidFields[i].name == 'recette_image'){
			//$('#recette_image').addClass('invalid');
			initErrorMsg += initErrorVisuelPetit;
		}

		if(invalidFields[i].name == 'recetteTempsPreparation'){
			initErrorMsg += initErrorTempsPreparation;
		}

		if(invalidFields[i].name == 'recetteTempsCuisson'){
			initErrorMsg += initErrorTempsCuisson;
		}

		if(invalidFields[i].name == 'recetteNombrePersonne'){
			initErrorMsg += initErrorNombrePersonne;
		}

		if(invalidFields[i].name == 'produits_associes'){
			//$('#produits_associes').addClass('invalid');
			initErrorMsg += initErrorNbProduitAssocie;
		}
		
		if( invalidFields[i].name == 'recettePreparation'){
			//$('#recettePreparation').addClass('invalid');
			initErrorMsg += initErrorDescriptif;
		}
	}

	if ($('#recette_image').val() == ''){
		initErrorMsg += initErrorVisuelPetit;
		$('#recette_image').addClass('invalid');
	}else{
		$('#recette_image').css('border', '2px inset #CCCCCC');
	}

	if(!ValideIngredient || $('#testIngredient').val()== 6){
		initErrorMsg += initErrorListeIngredient;
		$('#ingredient').addClass('invalid');
	}
	
	var o = $("#produits_associes")[0].options;
	g_supprvisuel = 0;
	$("#listeProduit").val("");
	oL = o.length;
		
	for (var i = 0; i < oL; i++){
		if (i != oL - 1){
			$("#listeProduit").val($("#listeProduit").val() + o[i].value + ",");

		} else {
			$("#listeProduit").val($("#listeProduit").val() + o[i].value);
		}
	}
	if(oL > 5){
		$('#produits_associes').addClass('invalid');
		initErrorMsg += initErrorNbProduitAssocie;
		$('.errorMessage').html(initErrorMsg).show();
	}



	var exp = new RegExp("^[0-9]+$","g"); 
	if(exp.test($('#recetteNombrePersonne').val()) == false){
		$('#recetteNombrePersonne').addClass('invalid');
		$('#recetteNombrePersonne').val('');
	}else{
		var NbPersonne = parseInt($('#recetteNombrePersonne').val());
		if(NbPersonne < 1 || NbPersonne > 10){
			$('.errorMessage').empty();
			$('#recetteNombrePersonne').addClass('invalid');
			$('#recetteNombrePersonne').val('');
		}else{
			$('#recetteNombrePersonne').css('border', '2px inset #CCCCCC');
			$('.errorMessage').empty();
		}
	}

	$('.errorMessage').empty();
	//uniformisation message d'erreur
	if(initErrorMsg != ""){
		initErrorMsg = 'Certaines informations sont incomplètes ou invalides, veuillez les compléter correctement.<br>';
	}
	$('.errorMessage').html(initErrorMsg).show();
}


 function formValidationRecette(form){
	var initErrorNbProduitAssocie		= "<br>- Veuillez choisir 5 produits au maximun pour la recette.";
	var bSubmit = false;
	if(tmt_validateForm(form)){
		var o = $("#produits_associes")[0].options;
		g_supprvisuel = 0;
		$("#listeProduit").val("");
		oL = o.length;
			
		for (var i = 0; i < oL; i++){
			if (i != oL - 1){
				$("#listeProduit").val($("#listeProduit").val() + o[i].value + ",");

			} else {
				$("#listeProduit").val($("#listeProduit").val() + o[i].value);
			}
		}
		if(oL > 5){
			$('#produits_associes').addClass('invalid');
			$('.errorMessage').html(initErrorNbProduitAssocie).show();
		}else{
			g_supprvisuel=1;
			bSubmit = true;
		}
		
		if($('#testIngredient').val()== 6){
			var initErrorListeIngredient		= "<br> -Veuillez  préciser au moins un ingredient pour la recette.";
			$('#ingredient').addClass('invalid');
			$('.errorMessage').html(initErrorListeIngredient).show();
		}else{
			bSubmit = true;
		}

		if($('#ErreurlisteProduit').val()== 1){
			var initErrorNbProduitAssocie		= "<br>- Veuillez choisir 5 produits au maximun pour la recette.";
			$('#produits_associes').addClass('invalid');
			$('.errorMessage').html(initErrorNbProduitAssocie).show();
		}else{
			bSubmit = true;
		}

		if(bSubmit){
			document.forms["recette"].submit();
		}
	}else{
		return false;
	}
}

function controle1(){
	$('.errorMessage').empty();
	var exp = new RegExp("^[0-9]+$","g"); 
	if(exp.test($('#recetteNombrePersonne').val()) == false){
		$('#recetteNombrePersonne').addClass('invalid');
		$('#recetteNombrePersonne').val('');
	}else{
		var NbPersonne = parseInt($('#recetteNombrePersonne').val());
		if(NbPersonne < 1 || NbPersonne > 10){
			$('.errorMessage').empty();
			$('#recetteNombrePersonne').addClass('invalid');
			$('#recetteNombrePersonne').val('');
		}else{
			$('#recetteNombrePersonne').css('border', '2px inset #CCCCCC');
			$('.errorMessage').empty();
		}
	}
}
function controle2(){
	var exp = new RegExp("^[0-9]+$","g"); 
	var ErrortempsCuisson			= "<br>Le temps de cuisson pour la recette doit être une entier";
	if(exp.test($('#recetteTempsCuisson').val()) == false){
		$('#recetteTempsCuisson').addClass('invalid');
		$('#recetteTempsCuisson').val('');
		ErrorControle += ErrortempsCuisson;
		$('.errorMessage').html(ErrorControle).show();
	}else{
		$('#recetteTempsCuisson').css('border', '2px inset #CCCCCC');
		$('.errorMessage').empty();

	}
}
function controle3(){
	var exp = new RegExp("^[0-9]+$","g"); 
	var ErrortempsPreparation		= "<br>Le temps de préparation pour la recette doit être un entier";
	if(exp.test($('#recetteTempsPreparation').val()) == false){
		$('#recetteTempsPreparation').addClass('invalid');
		$('#recetteTempsPreparation').val('');
		ErrorControle += ErrortempsPreparation;
		$('.errorMessage').html(ErrorControle).show();
	}else{
		$('#recetteTempsPreparation').css('border', '2px inset #CCCCCC');
		$('.errorMessage').empty();

	}
}

function ordonnerRecette(iAction, iRecetteId,iPage,iParPage,zSortField,zSortDirection){
		$.ajax({
			 type:"POST",
			 url:j_basepath +'admin.php',
			 data:"module=recette&action=recetteBo:ordonnerRecette&iAction="+iAction+"&iRecetteId="+iRecetteId+"&iPage="+iPage+"&iParPage="+iParPage+"&zSortField="+zSortField+"&zSortDirection="+zSortDirection,
			 async:false,
			 success:function(resultat){
				 $("#contenu").html('');
				 $("#contenu").html(resultat); 
				 //pagination
				 doOnLoad($('div.ajaxZone'));   
			 }
		});	
	return false;
}

function depublieRecette(iRecetteId,iPage,iParPage,zSortField,zSortDirection){
		$.ajax({
			 type:"POST",
			 url:j_basepath +'admin.php',
			 data:"module=recette&action=recetteBo:depublieRecette&iRecetteId="+iRecetteId+"&iPage="+iPage+"&iParPage="+iParPage+"&zSortField="+zSortField+"&zSortDirection="+zSortDirection,
			 async:false,
			 success:function(resultat){
				 $("#contenu").html('');
				 $("#contenu").html(resultat); 
				 //pagination
				 doOnLoad($('div.ajaxZone'));   
			 }
		});	
	return false;
}

function publieRecette(iRecetteId,iPage,iParPage,zSortField,zSortDirection){
		$.ajax({
			 type:"POST",
			 url:j_basepath +'admin.php',
			 data:"module=recette&action=recetteBo:publieRecette&iRecetteId="+iRecetteId+"&iPage="+iPage+"&iParPage="+iParPage+"&zSortField="+zSortField+"&zSortDirection="+zSortDirection,
			 async:false,
			 success:function(resultat){
				 $("#contenu").html('');
				 $("#contenu").html(resultat); 
				 //pagination
				 doOnLoad($('div.ajaxZone'));   
			 }
		});	
	return false;
}

function SetFileFieldImageTitre( _zFileUrl ){

	var zUrlTraitement = $("#urlTraitementVisuelRecette").val();

	if(_zFileUrl!=''){
		$.ajax({
			 type: "POST",
			 url: zUrlTraitement,
			 data: {
				 "process": 'resize',
				 "fichier": _zFileUrl
			 },
			 dataType: "json",
			 async: false,
			 success: function(resultat){
				document.getElementById('recette_image').value = resultat[0].image ;
				document.getElementById('petitVisuel').value = resultat[0].image ;
				document.getElementById('grandVisuel').value = resultat[2].image;
				document.getElementById('Vignette').value = resultat[1].image;	
				g_supprvisuel=1;
				$('#apercuImg').empty();
				$('#apercuImg').html(resultat[0].visuel);

				$("#recette_image").hide() ;
				$("#browseImgRecette").hide() ;
				$("#Petit_Vivuel").show() ;
			 }
		});
	}

}

function operationAjoute(_toIngredients, _zIngredientRecette_libelle, _operationAjouteURL)
	{
		
		if (_zIngredientRecette_libelle != '')
		{   
			
			$.ajax	({
					url:  _operationAjouteURL,
					type: "post",
					data: {
							zIngredientRecette_libelle: _zIngredientRecette_libelle, 
							toIngredients: _toIngredients 
						  },
					success: function (_zRep)
					{
						$("#miditra").html (_zRep) ;
					},
					async: false
				}) ;
		}
		else
		{
			alert ("Veuillez renseigner le libéllé");
		}
	}
	
	function supprime (_toIngredients, _iIndex, _operationSupprime)
	{	
		if (confirm ("Etes-vous sûr de vouloir supprimer cet élément ?"))
		{
			 $.ajax	({
					url: _operationSupprime,
					type: "post",
					data: { toIngredients:_toIngredients, 
							iIndex: _iIndex 
						  },
					success: function (_zRep)
					{
						$("#miditra").html (_zRep) ;
					},
					async: false
				}) ;
			return false;
		 }
	 }