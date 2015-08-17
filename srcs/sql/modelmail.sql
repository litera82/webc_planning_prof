/*
SQLyog Ultimate - MySQL GUI v8.21 
MySQL - 5.5.20-log 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `modelmail` (
	`modelmail_id` double ,
	`modelmail_type` double ,
	`modelmail_objet` varchar (765),
	`modelmail_label` blob ,
	`modelmail_ident` varchar (765),
	`modelmail_value` double ,
	`modelmail_content` blob 
); 
insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('1','1','Forma2+ vous invite à planifier votre premier cours / Forma2+ invites you to plan your first lesson','Enregistrer et proposer auto-planification','sendMailAuto','1','<p style=\"padding:15px 40px; margin:0; text-align:justify;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Bienvenue sur le planning en ligne de Forma2+ / <i>Welcome to the Forma2+ online planning system.</i><br /><br />Vous &ecirc;tes inscrit(e) en tant que stagiaire. Vous pouvez maintenant planifier vos cours avec votre professeur <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a> en vous connectant sur : <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /><i>You are registered as trainee. You can now plan your courts lessons with your teacher <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a> to logging on to: <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /></i></font></p><ul><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><i><ul><li><b>Votre identifiant / <i>Your login</i> :</b>%s</li><li><b>Votre mot de passe / <i>Your password</i> :</b>%s</li></ul></i></font></ul><p><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><i></i></font><i><p>&nbsp;</p><p style=\"padding:15px 40px; margin:0; text-align:left;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Nous vous souhaitons un agr&eacute;able stage / We wish you a pleasant training.<br /><br /><b>Rappel :</b>Les cours individuels peuvent &ecirc;tre report&eacute;s et ne sont pas perdus si le stagiaire pr&eacute;vient <b><u>directement son professeur</u></b>, la veille avant 17h (jours ouvr&eacute;s) pour les cours t&eacute;l&eacute;phone ou tutor&eacute;s. Dans un souci d\'efficacit&eacute;, il est conseill&eacute; de le reporter la m&ecirc;me semaine ou la semaine suivante.<br /><br />A tr&egrave;s bient&ocirc;t, / <i>Best regards, we look forward to hearing from you soon</i><br /><br />Equipe Planning en ligne Forma2+ / <i>The Forma2+ R&eacute;servation Team/Department</i></font></p></i></p>');
insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('2','1','Forma2+ vous invite à planifier votre premier cours / Forma2+ invites you to plan your first lesson','Enregistrer et relancer auto-planification','sendMailRelanceAuto','2','<p style=\"padding:15px 40px; margin:0; text-align:justify;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Bienvenue sur le planning en ligne de Forma2+ / <i>Welcome to the Forma2+ online planning system.</i><br /><br />Votre professeur <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a>  n\'arrive pas &agrave; vous joindre pour l\'organisation de votre formation, nous vous remercions de planifier votre prochain cours t&eacute;l&eacute;phonique en vous connectant sur  son planning : Planning en ligne/Forma2+ : <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /><br /><br />You are registered as trainee. You can now plan your courts lessons with your teacher <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a> to logging on to:<a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /></font></p><ul><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><ul><li><b>Votre identifiant / <i>Your login</i> :</b>%s</li><li><b>Votre mot de passe / <i>Your password</i> :</b>%s</li></ul></font></ul><p><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><br /></font></p><p>&nbsp;</p><p style=\"padding:15px 40px; margin:0; text-align:left;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Nous vous souhaitons un agr&eacute;able stage / We wish you a pleasant training.<br /><br /><b>Rappel :</b>Les cours individuels peuvent &ecirc;tre report&eacute;s et ne sont pas perdus si le stagiaire pr&eacute;vient <b><u>directement son professeur</u></b>, la veille avant 17h (jours ouvr&eacute;s) pour les cours t&eacute;l&eacute;phone ou tutor&eacute;s. Dans un souci d\'efficacit&eacute;, il est conseill&eacute; de le reporter la m&ecirc;me semaine ou la semaine suivante.<br /><br />A tr&egrave;s bient&ocirc;t, / <i>Best regards, we look forward to hearing from you soon</i><br /><br />Equipe Planning en ligne Forma2+ / <i>The Forma2+ R&eacute;servation Team/Department</i></font></p>');
insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('3','1','Forma2+ vous invite à planifier votre premier cours / Forma2+ invites you to plan your first lesson','Enregistrer et envoyer un mail de changement de professeur','sendMailChangeProf','3','<p style=\"padding:15px 40px; margin:0; text-align:justify;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Bonjour<br /><br />Nous vous pr&eacute;sentons toutes nos excuses, votre professeur n\'est plus disponible pour assurer vos cours, aussi nous vous avons  r&eacute;affect&eacute; &agrave;  un nouveau formateur : <b>%s</b> : %s ;<a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a><br /><br />D&egrave;s &agrave; pr&eacute;sent  nous vous proposons  de prendre RDV directement en vous connectant sur son planning : <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /><br /><br /></font></p><ul><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><ul><li><b>Votre identifiant / <i>Your login</i> :</b>%s</li><li><b>Votre mot de passe / <i>Your password</i> :</b>%s</li></ul></font></ul><p><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><br /></font></p><p>&nbsp;</p><p style=\"padding:15px 40px; margin:0; text-align:left;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Nous vous souhaitons un agr&eacute;able stage / We wish you a pleasant training.<br /><br /><b>Rappel :</b>Les cours individuels peuvent &ecirc;tre report&eacute;s et ne sont pas perdus si le stagiaire pr&eacute;vient <b><u>directement son professeur</u></b>, la veille avant 17h (jours ouvr&eacute;s) pour les cours t&eacute;l&eacute;phone ou tutor&eacute;s. Dans un souci d\'efficacit&eacute;, il est conseill&eacute; de le reporter la m&ecirc;me semaine ou la semaine suivante.<br /><br />A tr&egrave;s bient&ocirc;t, / <i>Best regards, we look forward to hearing from you soon</i><br /><br />Equipe Planning en ligne Forma2+ / <i>The Forma2+ R&eacute;servation Team/Department</i></font></p>');
insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('4','1',NULL,' Enregistrer et envoyer un mail personnalisé','sendMailPerso','4',NULL);
