<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear" style="width:943px">
			<form id="edit_form" method="POST" enctype="multipart/form-data" tmt:validate="true" >
						<h2>Liste des stagiaire correspondant au critère de recherhce</h2>
						{if $toParams[0]->nom != ""}<h2>Nom {$toParams[0]->nom}</h2>{/if}
						{if $toParams[0]->prenom != ""}<h2>Prénom {$toParams[0]->prenom}</h2>{/if}
						{if $toParams[0]->client_iUtilisateurCreateurId == 0}
							<h2>Professeur : Tous les professeurs</h2>
						{else}
							<h2>Professeur : {$toStagiaire['toListes'][0]->utilisateur_zNom}&nbsp;{$toStagiaire['toListes'][0]->utilisateur_zPrenom}</h2>
						{/if}	
						<h2>Nombre de stagiaires trouvés : {$toStagiaire['iResTotal']}</h2>
						<div>
							<table cellpadding="5" border="1">
								<tbody>
									<tr>
										<th>Civilité</th>
										<th width="20%">Nom</th>
										<th width="20%">Prénom</th>
										<th width="10%">Fonction</th>
										<th width="10%">eMail</th>
										<th width="10%">Tel</th>
										<th width="20%">Adresse</th>
										<th width="20%">Professeur</th>
									</tr>
									{foreach $toStagiaire['toListes'] as $oStagiaire}
									<tr>
										<td>{if $oStagiaire->client_iCivilite == CIVILITE_FEMME}Mme{else}Mr{/if}</td>
										<td><a href="{jurl 'client~FoClient:add', array('iClientId'=>$oStagiaire->client_id)}">{$oStagiaire->client_zNom}</a></td>
										<td>{$oStagiaire->client_zNom}</td>
										<td>{$oStagiaire->client_zFonction}</td>
										<td>{$oStagiaire->client_zMail}</td>
										<td>{$oStagiaire->client_zTel}</td>
										<td>{$oStagiaire->client_zRue}&nbsp;{$oStagiaire->client_zVille}&nbsp;{$oStagiaire->client_zCP}{if isset($oStagiaire->pays_zNom)}{$oStagiaire->pays_zNom}{/if}</td>
										<td>{$oStagiaire->utilisateur_zNom}&nbsp;{$oStagiaire->utilisateur_zPrenom}</td>
									</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
						<div class="input">
							<input type="button" value="Imprimer" class="boutonform submitForm" onclick="window.print();"/>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>
{$footer}