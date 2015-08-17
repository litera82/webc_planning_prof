$(function(){
	addEvent(window, "load", tmt_validatorInit);
	
	var val = $('#test_portrait').val();

	if (val != ""){
		$("#test_portrait").hide () ;
		$("#Portrait_imageTitre_button_value").hide () ;
	}else{
		$("#bloc_ApercuPortraitTitreImage").hide () ;
	}

	$("#produit_choisie").multiSelect("#produit_dispo", {trigger: "#produit_left"});
	$("#produit_dispo").multiSelect("#produit_choisie", {trigger: "#produit_right",beforeMove:displayVals});



	$('.parcourir').click(function(){
		
		var finder = new CKFinder() ;
		finder.BasePath = j_finder_basePath ;	
		finder.SelectFunction = SetFileFieldImageTitre;
		finder.Popup() ;
		return false;

	});

	$('#supprImg').click(function(){
	
		document.getElementById ("test_portrait").value = "" ;
		$("#test_portrait").show () ;
		$("#Portrait_imageTitre_button_value").show () ;
		$("#bloc_ApercuPortraitTitreImage").hide () ;
	
	});

	//Errreur
	if ($('#erreurFormulaire').val() == 1){
		$('.errorMessage').html('');
		var zErrorMsg = "Certaines informations sont incomplètes ou invalides, veuillez les compléter correctement.<br>";
		$('.errorMessage').attr('style', 'display:block;');
		$('.errorMessage').html(zErrorMsg);
	}

});

function SetFileFieldImageTitre( _zFileUrl ){

	var zUrlTraitement = $("#urlTraitementVisuelTest").val();

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

				document.getElementById('test_portrait').value = resultat[0].image ;
				document.getElementById('test_portraitMenu').value = resultat[1].image ;
				$('#apercuImg').empty();
				$('#apercuImg').html(resultat[0].visuel);

				$("#test_portrait").hide() ;
				$("#Portrait_imageTitre_button_value").hide() ;
				$("#bloc_ApercuPortraitTitreImage").show() ;
				
			 }
		});
	}

}

function formValidationTest(form){
	
	var bSubmit = false;
	
	if(!tmt_validateForm(form)){
		return false;
		bSubmit = false;
	}else{
	
		var o = $("#produit_choisie")[0].options;
		$("#listTest").val("");
		oL = o.length;
			
		for (var i = 0; i < oL; i++){
			if (i != oL - 1){
				$("#listTest").val($("#listTest").val() + o[i].value + ",");

			} else {
				$("#listTest").val($("#listTest").val() + o[i].value);
			}
		}
		bSubmit = true

	}

	return bSubmit;
}

function displayVals(){
	var iDispo = 0;
	var o = $("#produit_choisie")[0].options;
	oL = o.length;
	
	$('#produit_dispo').find('option').each(function(index){
		if($(this).attr('selected')){
			iDispo++;
		}
	});
	
	if((iDispo + oL) > 3){
		return false;
	} else{
		return true;
	}

}

/*/ Upload image titre
function setupAjaxUploadProtrait (){
	
	new AjaxUpload
	(
		"#zPortraitTitreImage",
		{
			action: "{/literal}{jurl 'pages~BoPortrait:ajaxUploadTitreImage', array(), false}{literal}",
			data: ,
			responseType: "json",
			onSubmit: function(file, ext)
						{
							if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
							{
								alert("Type de fichier invalide, veuillez uploader un fichier png, jpg ou gif!");
								return false;
							}
							$("#bloc_Edit").showLoading () ;
						},
			onComplete: function (file, resp)
						{
							$("#bloc_Edit").hideLoading () ;
							if (resp == 1)
							{
								alert ("Type de fichier invalide, veuillez uploader un fichier png, jpg ou gif!") ;
							}
							else if (resp == 2)
							{
								alert ("L'upload du fichier a échoué, veuillez recommencer!") ;
							}
							else
							{
								document.getElementById ("zPortraitTitreImageFile").value = resp ;
								document.getElementById ("img_ApercuTitreImage").src = "{/literal}{$j_basepath}{literal}userfiles/livrets/images/titre/{/literal}{$oLangue->langue_code}{literal}/" + resp ;
								document.getElementById ("img_ApercuTitreImage").alt =  resp ;
								$("#zPortraitTitreImage").hide () ;
								$("#bloc_ApercuPortraitTitreImage").show () ;
							}
						}
		}
	);
}*/