{literal}
<script type="text/javascript">
$( function () {

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
	$('#groupe_id').change(function (){
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
	});
});
</script>
{/literal}
<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear" style="width:960px;padding: 5px 5px 5px;">
				<form id="edit_form" action="{jurl 'evenement~FoEvenementExcel:export', array(), false}" method="POST" enctype="multipart/form-data" >
					<input type="hidden" name="urlChargeProfParGroupId" id="urlChargeProfParGroupId" value="{jurl 'evenement~FoEvenement:chargeProfParGroupId'}"/>
					<h2>Export Excel des cours plannifiés</h2>
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
									<input type="text" class="date1 text" id="dtcm_event_rdv1" name="dtcm_event_rdv1" style="width:100px;" value="{if isset ($toParams[0]->zDateFin)}{$toParams[0]->zDateFin}{/if}" readonly="readonly"/>
								</p>
								</td>
							</tr>
							<tr>
								<td>
								<p class="clear">
									<label style="width:200px;">Groupes de prof</label>
									<select class="text"  style="width:200px;" name="groupe_id" id="groupe_id">
										<option value="0">--------------------Tous--------------------</option>
										{if isset($toGroupe)}
										{foreach $toGroupe as $oGroupe}
											<option value="{$oGroupe->groupe_id}" {if isset ($toParams[0]->groupe_id) && $toParams[0]->groupe_id == $oGroupe->groupe_id} selected="selected"{/if}>{$oGroupe->groupe_libelle}</option>
										{/foreach}
										{/if}
									</select>
								</p>
								</td>
								<td>
								<p class="clear">
									<label style="width:200px;">Proffesseur</label>
									<select class="text" style="width:200px;" name="professeurs" id="professeurs" >
										<option value="0">--------------------Tous--------------------</option>
										{if isset($toUtilisateur)}
										{foreach $toUtilisateur as $oTmpUtilisateur}
											<option value="{$oTmpUtilisateur->utilisateur_id}" {if isset ($toParams[0]->professeurs) && $toParams[0]->professeurs == $oTmpUtilisateur->utilisateur_id} selected="selected"{/if}>{$oTmpUtilisateur->utilisateur_zPrenom} {$oTmpUtilisateur->utilisateur_zNom}</option>
										{/foreach}
										{/if}
									</select>
								</p>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="input" style="width:152px;padding-top:1px;">
						<input type="submit" value="Export Excel" class="boutonform" style="padding: 2px 5px;"/>
					</div>
			</form>
			</div>
		</div>
	</div>
</div>
{$footer}
