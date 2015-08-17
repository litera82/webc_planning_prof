<h1 class="noBg">Validation des cours</h1>
<h2>Exportation des cours validés par les professeurs</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'evenement~BoValidationCours:export', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
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
					alert("Erreur lors de l'exportation!!!");
				</script>
			{/literal}
			{/if}
			{if $x == 1003}
				<script type="text/javascript">
					alert("L'exportation des cours validés terminés !!!");
				</script>
			{/if}
		{/if}
</form>
<p class="line_bottom">&nbsp;</p>