<h1 class="noBg">Client > suivie des staigiares</h1>
<h2>Exporter les données des stagiaires </h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~client:exportSuivieStagiaire', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
	<p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button" style="text-align:center;">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Lancer l'exportation" />&nbsp;
    </p>
	<p class="errorMessage" id="errorMessage"></p>
		{if isset($x) && $x != 0}
			{if $x == 1001}
			{literal}
				<script type="text/javascript">
					alert("Erreur lors l'exportation des données stagiaire !!!");
				</script>
			{/literal}
			{/if}
			{if $x == 1003}
				<script type="text/javascript">
					alert("L'exportation des stagiaire a été effectuée avec succès !!!");
				</script>
			{/if}
		{/if}
</form>
<p class="line_bottom">&nbsp;</p>