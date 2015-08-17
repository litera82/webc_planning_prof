$(function(){
	/*$('a[@name=ajout_niveau]').click(function(){
		
		var iCount =  parseInt($('input[@name=count]').val());
		//iCount = parseInt(iCount) ;
		iCount += 1;
		var zInput = '<tr class"row1" name="row_'+ iCount +'" style ="background:#E5F5FC none repeat scroll 0 0;"><td class="color1">Niveau 2</td><td class="color1"><a href="#"><input type="text" name="niveau2_libelle_'+ iCount +'" id="niveau2_libelle" value="" /></td></tr>'
		
		var zRow = '[@name=row_'+ (iCount-1) +']';
		$(zInput).insertAfter(zRow);	
		
		$('input[@name=count]').val(iCount);
		
	});*/
	
	$('#descendre').click (function ()
	{
		$('div#listeGamme').html ('') ;
	});
	
	$('#monter').click (function ()
	{
		$('div#listeGamme').html ('') ;
	});
	 $('#ajouter').click (function ()
	{
		document.location.href ="admin.php?module=produit&action=gammeBo:edition";
	});
});

function supprimerGamme(iGammeId){
	var zUrl = $('#urlEstSupprimable').val();

	$.getJSON(zUrl, {'iGammeId':iGammeId},
	function(data){
		if (data == 0){
			alert("Vous ne pouvez pas supprimer cette gamme car au moins un produit y est encore associé");
		}else{
			var zUrlConfirm = $('#urlSupprimeGamme').val();
			document.location.href = zUrlConfirm+"&iGammeId="+iGammeId;
		}
	});
}

function permuter (_iGammeId, _iDesc){
	$.ajax
	 ({
		   type: "POST",
		   url: $('#urlPermutationGamme').val(),
		   cache: false,
		   data: {
					'iGammeId' : _iGammeId,
					'iDesc' : _iDesc
				 },
		   success: function (response){
				/*var iRapprochement = 0;
				loadSortableListWithPagination(iRapprochement);*/
				$('#loadContent').html('');
				$('#loadContent').html(response);

			}
	
	 });
	 return false ;   
}