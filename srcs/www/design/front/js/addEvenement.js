/*addEvenement.js*/
$( function () {
	addEvent(window, "load", tmt_validatorInit);

	$("#rechercherStagiaire").show();
	$('#div-stagiaire-liste').hide();
	$('#p-txtville').hide();
	$('#p-txtsociete').hide();
	$('#p-txtphone').hide();
	$('#p-txtmail').hide();
	$("#addNewStagiaire").show();

	$('.submitForm').click(
		function(){
			$('#sendMail').val(0);
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);
	$('.submitFormMail').click(
		function(){
			$('#sendMail').val(1);
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);

	$('#evenement_iTypeEvenementId').change(
		function (){
			var val = $(this).val();
			var stagiaireActif = $("#typeevenements_iStagiaireActif_"+val).val();
			if (stagiaireActif == 0){
				$("#evenement_iStagiaire").val(0);
				$("#evenement_zStagiaire").attr('disabled', 'disabled');
				$("#evenement_zStagiaire").val('');
				$("#rechercherStagiaire").hide();
				$("#addNewStagiaire").hide();
				$("#p-txtmail").hide();
				$("#txtmail").val('');
				$("#p-txtphone").hide();
				$("#txtphone").val('');
				$("#p-txtsociete").hide();
				$("#txtsociete").val('');
				$("#p-txtville").hide();
				$("#txtville").hide();
			}else{
				$("#evenement_zStagiaire").removeAttr('disabled');
				$("#rechercherStagiaire").show();
				$("#addNewStagiaire").show();
			}
		}
	);

	$('#rechercherStagiaire').click(
		function (){
			var evenement_zStagiaire = $('#evenement_zStagiaire').val();
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
					$('#evenement_zStagiaire').val('');
					$('#evenement_zLibelle').val('');
					$('#evenement_zLibelle').val(datas["client_zNom"]); 

					var html = datas["client_zNom"] + ' ' + datas["client_zPrenom"];
					$('#evenement_zStagiaire').val(html);

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
});