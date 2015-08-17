function date_heure(id)
{

		var csChaine;
		var nJour, nMois, nAnnee, nHeures , nMinutes, nSecondes;
		var dtJour;
		csChaine = " ";
		dtJour = new Date();
		nJour = dtJour.getDate();
        jours = new Array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
		csChaine += jours[dtJour.getDay()]+' ';
		if ( nJour < 10 ) csChaine += "0";
		csChaine += nJour;
		nMois = dtJour.getMonth() + 1;
		if (nMois == 1) csChaine += " Janvier";
		else if (nMois == 2) csChaine += " Février";
		else if (nMois == 3) csChaine += " Mars";
		else if (nMois == 4) csChaine += " Avril";
		else if (nMois == 5) csChaine += " Mai";
		else if (nMois == 6) csChaine += " Juin";
		else if (nMois == 7) csChaine += " Juillet";
		else if (nMois == 8) csChaine += " Août";
		else if (nMois == 9) csChaine += " Septembre";
		else if (nMois == 10) csChaine += " Octobre";
		else if (nMois == 11) csChaine += " Novembre";
		else if (nMois == 12) csChaine += " Décembre";
		csChaine += " ";
		nAnnee = dtJour.getFullYear();
		if (nAnnee <= 99) nAnnee += 1900;
		csChaine += nAnnee + " / <br />";
		nHeures = dtJour.getHours();
		if (nHeures < 10) csChaine += "0";
		csChaine += nHeures + ":";
		nMinutes = dtJour.getMinutes();
		if (nMinutes < 10) csChaine += "0";
		csChaine += nMinutes + ":";
		nSecondes = dtJour.getSeconds();
		if (nSecondes < 10) csChaine += "0";
		csChaine += nSecondes;

		document.getElementById(id).innerHTML = csChaine;
        setTimeout('date_heure("'+id+'");','1000');

		return true;
}