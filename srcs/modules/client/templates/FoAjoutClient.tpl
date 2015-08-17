<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear">
				{$oZoneAjoutClient}
			</div>
		</div>
	</div>
</div>
{$footer}
<div class="pop-up" id="periodepop" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px;">
		<h2>Création d'une société</h2>
        <a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
		<div class="inner clear">
			<form id="edit_form_societe" url="{jurl 'client~FoSociete:save', array(), false}" >
						<p class="clear">
							<label>Raison sociale *</label>
							<input class="text" type="text" name="societe_zNom" id="societe_zNom" value=""/>
							<input class="text" type="hidden" name="societe_iStatut" id="societe_iStatut" value="1" />
						</p>
						<div class="input">
							<a href="#"><input type="button" value="Annuler" class="boutonform fermer" /></a>
							<input type="button" value="Créer" class="boutonform saveSociete" />
						</div>
				</form>
		</div>
</div>

<div class="pop-up" id="periodepopsendmail" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px;">
		<h2 class="title1h2"></h2>
        <a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
		<br /><h3 class="title2h2"></h3>
		<h3 class="title3h2"></h3>

		<div class="inner clear" style="padding-bottom: 0px;">
			<form id="edit_form_societe" url="{jurl 'client~FoSociete:save', array(), false}" style="text-align:center;" >
			<input type="hidden" name="realClientId" id="realClientId" value="">
						<p class="clear">Nombre de cours
							<select tmt:required="true" name="nbc" class="nbcclass" id="nbc" style="visibility: visible;width:50px;">
								<option value="0" selected="selected">Tous</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="30">30</option>
								<option value="40">40</option>
								<option value="50">50</option>
								<option value="60">60</option>
								<option value="70">70</option>
								<option value="80">80</option>
								<option value="90">90</option>
								<option value="100">100</option>
							</select> 
							<br />
							<br />
						</p>
						<div class="input">
							<input type="button" value="Envoyer" class="boutonform sendMail" />
						</div>
				</form>
		</div>
</div>
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>
{literal}
<script type="text/javascript">
	$(function() {
		$('.saveSociete').click(
			function (){
				if ($('#societe_zNom').val() == '')
				{
					alert('Veuillez remplir le champ Raison sociale')
				}else{
					$.getJSON(j_basepath + "index.php", {module:"client", action:"FoSociete:saveAjax", societe_zNom:$('#societe_zNom').val(), societe_iStatut:$('#societe_iStatut').val()}, function(datas){
						if(datas){
							var html = "";
							html += '<option value="0">----------------------Séléctionner----------------------</option>';
							for(i=0; i< datas.length; i++){
								html += '<option value="' + datas[i]["societe_id"] +'">&nbsp;' + datas[i]["societe_zNom"] + '</option>';
							}
							$('#client_iSociete').html(html);
							$('#client_iSociete').val(0);

							$('#masque').fadeOut(fadeTime);
							$(lastOpen).fadeOut(fadeTime);
							return false;
						}
					});
				}				
			}
		);
	});
</script>
{/literal}
