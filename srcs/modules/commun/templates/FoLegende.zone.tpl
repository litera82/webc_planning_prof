<ul>
	{foreach $toLegende as $oLegende}
	<li style="padding:2px 20px 2px 15px;font-size:1em;width:180px;border-color:{$oLegende->typeevenements_zCouleur};margin-bottom:5px;color:#2C2C2C;">{$oLegende->typeevenements_zLibelle}</li>
	{/foreach}
</ul>