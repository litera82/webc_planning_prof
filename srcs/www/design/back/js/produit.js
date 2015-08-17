$(function(){

	addEvent(window, "load", tmt_validatorInit);
	var vignetteMenu = $('#produit_vignetteMenu').val();
	var vignetteVisuel = $('#produit_visuel').val();
	var vignetteVisuelFlash = $('#produit_visuelFlash').val();

	if (vignetteMenu != ""){
		$("#produit_vignetteMenu").hide () ;
		$("#vignetteMenu").hide () ;
	}else{
		$("#bloc_apercuVignetteMenu").hide () ;
	}

	if (vignetteVisuel != ""){
		$("#produit_visuel").hide () ;
		$("#visuel").hide () ;
	}else{
		$("#bloc_apercuVisuel").hide () ;
	}

	if (vignetteVisuelFlash != ""){
		$("#produit_visuelFlash").hide () ;
		$("#visuelFlash").hide () ;
	}else{
		$("#bloc_apercuVisuelFlash").hide () ;
	}

	$('#gamme').change (
		function (){
			if ($('#gamme').val() != 0){
				var zUrl = $('#urlChargerFilsGamme').val();
				$.getJSON(zUrl , {iGammeId:$('#gamme').val()}, function(datas){
					var htmlContent = '<option value="0">Séléctionner la sous gamme de produit</option>';
					for(i=0; i< datas.length; i++){
						htmlContent += '<option value="' + datas[i]["gamme_id"]+'"  >' + datas[i]["gamme_libelle"] + '<\/option>';
					}
					$('#sousgamme').html('');
					$('#sousgamme').html(htmlContent);
				});
			}else{
				var zUrl = $('#urlChargerFilsGamme').val();
				alert($('#gamme').val()); 
			}
		}
	);

	$('.parcourirImgVignetteMenu').click(function(){
		if ($('#gamme').val() != 0){
			var finder = new CKFinder() ;
			finder.BasePath = j_finder_basePath ;	
			finder.SelectFunction = SetFileFieldVignetteMenu;
			finder.Popup() ;
		}else{
			alert('Veuillez choisir la gamme du produit.')
		}

		return false;
	});
	$('.parcourirImgVisuel').click(function(){
		if ($('#gamme').val() != 0){
			var finder = new CKFinder() ;
			finder.BasePath = j_finder_basePath ;	
			finder.SelectFunction = SetFileFieldVisuel;
			finder.Popup() ;
		}else{
			alert('Veuillez choisir la gamme du produit.')
		}
		return false;
	});
	$('.parcourirImgVisuelFlash').click(function(){
		if ($('#gamme').val() != 0){
			var finder = new CKFinder() ;
			finder.BasePath = j_finder_basePath ;	
			finder.SelectFunction = SetFileFieldVisuelFlash;
			finder.Popup() ;
		}else{
			alert('Veuillez choisir la gamme du produit.')
		}
		return false;
	});

	$('#supprImgVignetteMenu').click(function(){
		$("#produit_vignetteMenu").val('');
		$("#produit_vignetteMenu").show () ;
		$("#vignetteMenu").show () ;
		$("#bloc_apercuVignetteMenu").hide () ;
	});
	$('#supprImgVisuel').click(function(){
		$("#produit_visuel").val('');
		$("#produit_visuel").show () ;
		$("#visuel").show () ;
		$("#bloc_apercuVisuel").hide () ;
	});
	$('#supprImgVisuelFlash').click(function(){
		$("#produit_visuelFlash").val('');
		$("#produit_visuelFlash").show () ;
		$("#visuelFlash").show () ;
		$("#bloc_apercuVisuelFlash").hide () ;
	});
});


function SetFileFieldVignetteMenu( _zFileUrl ){
	if(_zFileUrl!=''){
		$.ajax({
			 type: "POST",
			 url: $("#urlTraitementVisuel").val(),
			 data: {
				 "process": 'resize',
				 "fichier": _zFileUrl,
				 "iType": 1, 
				 "valueGammeId": $("#valueGammeId").val()	
			 },
			 dataType: "json",
			 async: false,
			 success: function(resultat){
				$('#produit_vignetteMenu').val(resultat.image);
				$('#apercuImgVignetteMenu').empty();
				$('#apercuImgVignetteMenu').html(resultat.visuel);

				$("#produit_vignetteMenu").hide() ;
				$("#vignetteMenu").hide() ;
				$("#bloc_apercuVignetteMenu").show() ;
			 }
		});
	}
}

function SetFileFieldVisuel( _zFileUrl ){
	if(_zFileUrl!=''){
		$.ajax({
			 type: "POST",
			 url: $("#urlTraitementVisuel").val(),
			 data: {
				 "process": 'resize',
				 "fichier": _zFileUrl,
				 "iType": 2, 
				 "valueGammeId": $("#valueGammeId").val()	
			 },
			 dataType: "json",
			 async: false,
			 success: function(resultat){
				$('#produit_visuel').val(resultat.image);
				$('#apercuImgVisuel').empty();
				$('#apercuImgVisuel').html(resultat.visuel);

				$("#produit_visuel").hide() ;
				$("#visuel").hide() ;
				$("#bloc_apercuVisuel").show() ;
			 }
		});
	}
}

function SetFileFieldVisuelFlash( _zFileUrl ){
	if(_zFileUrl!=''){
		if ($("#valueGammeId").val() == ""){
			var valueGammeId = $("#gamme").val();
		}else{
			var valueGammeId = $("#valueGammeId").val();
		}
		$.ajax({
			 type: "POST",
			 url: $("#urlTraitementVisuel").val(),
			 data: {
				 "process": 'resize',
				 "fichier": _zFileUrl,
				 "iType": 3, 
				 "valueGammeId": valueGammeId
			 },
			 dataType: "json",
			 async: false,
			 success: function(resultat){
				$('#produit_visuelFlash').val(resultat.image);
				$('#apercuImgVisuelFlash').empty();
				$('#apercuImgVisuelFlash').html(resultat.visuel);
				$('#produit_visuelFlashZoom').val(resultat.imageZoom);
				$("#produit_visuelFlash").hide() ;
				$("#visuelFlash").hide() ;
				$("#bloc_apercuVisuelFlash").show() ;
			 }
		});
	}
}


function validateproduitForm(form){
	var bValid = tmt_validateForm(form);
	var miseEnAvant = $("input[type=radio][name=produit_miseEnAvant]:checked").attr('value');
	if(!bValid){
		return false;
	}else{
		if(miseEnAvant == 0){
			document.forms["frmProduit"].submit();
		}else{
			if ($('#produit_miseEnAvant_titre').val() == ''){
				$('#produit_miseEnAvant_titre').attr("class", "invalid");
				$('.errorMessage').html('Certaines informations sont incomplètes ou invalides, veuillez les compléter correctement').show();
				return false;	
			}else{
				document.forms["frmProduit"].submit();
			}
		}
	}
}


function supprimerProduit(iProduitId, zSortField, zSortDirection, iListe, iPage, iParPage){
	var zUrl = $('#urlEstSupprimable').val();
	$.getJSON(zUrl, {'iProduitId':iProduitId},
	function(data){
		if (data == 0){
			alert("Vous ne pouvez pas supprimer cet produit car au moins un test ou une recette y est encore associé");
		}else{
			var zUrlConfirm = $('#urlSupprimeProduit').val();
			document.location.href = zUrlConfirm+"&iProduitId="+iProduitId+"&zSortField="+zSortField+"&zSortDirection="+zSortDirection+"&iListe="+iListe+"&iPage="+iPage+"&iParPage="+iParPage;
		}
	});
}
