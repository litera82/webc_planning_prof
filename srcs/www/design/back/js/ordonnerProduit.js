$(function(){

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
			}
		}
	);

});

function permuter(_iProduitId, _iAction){
	var zUrlTraitement = $('#urlTraitementOrdre').val();

		$.ajax({
			 type:"POST",
			 url: zUrlTraitement,
			 data: {
					iProduitId:_iProduitId, 
					iAction:_iAction
				  },
			 async:false,
			 success:function(resultat){
				$('.liste').html('');
				$('.liste').html(resultat);
			 }
		});	
	return false;
}