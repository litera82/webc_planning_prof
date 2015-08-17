<table width="728" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td width="20" rowspan="6"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="680" height="9" colspan="7"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_01_01_01.jpg" width="680" height="9" alt="" /></td>
        <td width="28" rowspan="6"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>

    </tr>
    <tr>
        <td width="420" height="17" bgcolor="#000000" colspan="2"></td>
        <td width="85" height="17" bgcolor="#000000"></td>
        <td width="16" height="17" bgcolor="#000000"></td>
        <td width="131" height="17" colspan="2" bgcolor="#000000"></td>
        <td width="28" height="17"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_01_01_02_05.jpg" width="28" height="17" alt="" /></td>
    </tr>
    <tr>
    	<td width="30" height="87" bgcolor="#000000" valign="top"><img width="30" height="87" border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="620" height="87" colspan="4" bgcolor="#000000"><a href="#" title="Format2+ : Plus de services, plus de resultats"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/logo.gif" width="278" height="74" alt="" /></a></td>
        <td width="30" height="87" colspan="2" bgcolor="#000000" valign="top"><img width="30" height="87" border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
    </tr>
    <tr>
        <td width="30" rowspan="2" bgcolor="#625d59" valign="top"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_02_01_01.jpg" width="30" height="419" alt="" /></td>
        <td width="620" height="23" colspan="4" valign="top" bgcolor="#00FF00"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_02_01_02_01.jpg" width="620" height="23" alt="" /></td>
        <td width="30" colspan="2" rowspan="2" bgcolor="#625d59" valign="top"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_02_01_03.jpg" width="30" height="419" alt="" /></td>
    </tr>
    <tr>
        <td width="620" height="418" colspan="4" valign="top" bgcolor="#FFFFFF">
        	<p style="padding:15px 40px; margin:0; text-align:justify;">
                <font face="Verdana, Geneva, sans-serif" size="2">
					Bienvenue sur le planning en ligne de Forma2+ / <i>Welcome to the Forma2+ online planning system.</i><br /><br />
					Vous êtes inscrit(e) en tant que stagiaire. Vous pouvez maintenant planifier vos cours avec votre professeur <b>{$oUtilisateur->utilisateur_zNom} {$oUtilisateur->utilisateur_zPrenom}</b> : {if isset($oUtilisateur->utilisateur_zTel)}{$oUtilisateur->utilisateur_zTel}{/if} ; {if isset($oUtilisateur->utilisateur_zMail)}<a href="mailto:{$oUtilisateur->utilisateur_zMail}" target="_blank" style="color: #003399;text-decoration: underline;">{$oUtilisateur->utilisateur_zMail}</a>{/if} en vous connectant sur : <a href="http://{$zUrlToIndexStagiaire}" target="_blank" style="text-decoration:none;color:#003399;">Planning en ligne/Forma2+</a><br /><br />
					<i>You are registered as trainee. You can now plan your courts lessons with your teacher <b>{$oUtilisateur->utilisateur_zNom} {$oUtilisateur->utilisateur_zPrenom}</b> : {if isset($oUtilisateur->utilisateur_zTel)}{$oUtilisateur->utilisateur_zTel}{/if} ; {if isset($oUtilisateur->utilisateur_zMail)}<a href="mailto:{$oUtilisateur->utilisateur_zMail}" target="_blank" style="color: #003399;text-decoration: underline;">{$oUtilisateur->utilisateur_zMail}</a>{/if} to logging on to: <a href="http://{$zUrlToIndexStagiaire}" target="_blank" style="text-decoration:none;color:#003399;">Planning en ligne/Forma2+</a></i><br /><br />
					<ul>
						<li><b>Votre identifiant / <i>Your login</i> :</b> {$oClient->client_zLogin}</li>
						<li><b>Votre mot de passe / <i>Your password</i> :</b> {$oClient->client_zPass}</li>
					</ul>
					<br />
                </font>
            </p>
        	<p style="padding:15px 40px; margin:0; text-align:left;">
                <font face="Verdana, Geneva, sans-serif" size="2">
					Nous vous souhaitons un agréable stage / We wish you a pleasant training.
					<br /><br />
					<b>Rappel :</b>Les cours individuels peuvent être reportés et ne sont pas perdus si le stagiaire prévient <b><u>directement son professeur</u></b>, la veille avant 17h (jours ouvrés) pour les cours téléphone ou tutorés. Dans un souci d'efficacité, il est conseillé de le reporter la même semaine ou la semaine suivante.
					<br /><br />
					A très bientôt, / <i>Best regards, we look forward to hearing from you soon</i><br /><br />
					Equipe Planning en ligne Forma2+ / <i>The Forma2+ Réservation Team/Department</i> 
                </font>
            </p>
		</td>
    </tr>
    <tr>
        <td width="680" height="64" colspan="7" bgcolor="#625d59" align="center" valign="middle"><font face="Verdana, Geneva, sans-serif" size="1">Ne pas r&eacute;pondre &agrave; ce courrier &eacute;lectronique</td>
    </tr>
    <tr>
        <td width="20" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="30" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="390" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="85" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="16" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="129" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="2" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>

        <td width="28" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="28" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
    </tr>
</table>