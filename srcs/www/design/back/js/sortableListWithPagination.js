/** @version  2.0 pagination 
*	@author NEOV by tojo Randrianantenaina 
*/
function loadSortableListWithPagination (iPage, nbParPage)
{
	ajaxZone = $("div").parents ('.ajaxZone') ;
	table = $("div").parents ('.sortableListWithPagination').find ('table') ;
	zSortDirection = table.attr("zCurrentSortDirection");
	zSortField = table.attr("zCurrentSortField");
	iTypeId = table.attr("iTypeId");
	ajaxZone.load (src, {zSortField:zSortField, zSortDirection:zSortDirection, iTypeId:iTypeId , iPage:iPage, iParPage:nbParPage, iRapprochement:iRapprochement}, function() {
			doOnLoad ($("div")) ;
		}) ;
}
		

$.fn.sortableListWithPagination = function () {
	$ (this).each (function() {
		this.table = $ (this).find ('table') ;
		
		zCurrentSortField = this.table.attr ('zCurrentSortField') ;
		iParPage = this.table.attr ('iParPage') ;
		iRapprochement = this.table.attr ('iRapprochement') ;
		
		zCurrentSortDirection = this.table.attr ('zCurrentSortDirection') ;
		src = this.table.attr ('src') ;

		iCurrentPage = this.table.attr ('iCurrentPage') ;
		iCurrentPage = new Number (iCurrentPage) ;
		iNbPage = this.table.attr ('iNbPage') ;
		iNbPage = new Number (iNbPage) ;
		iNombreTotal = this.table.attr ('iNbrTotal') ;
		
		if (zCurrentSortField==undefined || zCurrentSortDirection==undefined || src==undefined || iCurrentPage==undefined || iNbPage==undefined) {
			alert ("Le tag table doit avoir les attributs src, zCurrentSortField, zCurrentSortDirection, iCurrentPage et iNbPage") ;
			return true ;
		}


			
		divPagination = $(this).find ('.page') ;
		divPagination.empty () ;
		divPagination.addClass("pDiv1");


		ajoutFirstBloc = jQuery("<div></div>");
		ajoutFirstBloc.addClass ("pGroup");
		ajoutSpan = jQuery("<span></span>");
		ajoutSpan.addClass ("pPageStat");
		var zPluriel="";
		if(iNombreTotal > 1){zPluriel="s";}
		ajoutSpan.append ("Total : " + iNombreTotal + " &eacute;l&eacute;ment"+zPluriel);
		ajoutFirstBloc.append (ajoutSpan);
		divPagination.append (ajoutFirstBloc);
		
		// bloc pour last et le next
		ajoutBlocLast = jQuery("<div></div>");
		ajoutBlocLast.addClass ("pGroup1");

		// next
		ajoutNext = jQuery('<div></div>');
		ajoutNext.addClass ("largeur");
	

		if (iCurrentPage < iNbPage)
		{
			ajoutNextA = jQuery('<a></a>');
			ajoutNextA.addClass ("next");
			ajoutNextA.attr ("href","#");
			ajoutNextAImage = jQuery('<img>');
			ajoutNextAImage.attr ("src", j_basepath+"design/back/images/next.gif");
			ajoutNextA.append (ajoutNextAImage);
			ajoutNext.append(ajoutNextA);
		}
		
		// last	
		ajoutLast = jQuery('<div></div>');
		ajoutLast.addClass ("largeur");
		if (iCurrentPage < iNbPage)
		{

			ajoutLastA = jQuery('<a></a>');
			ajoutLastA.addClass ("last");
			ajoutLastA.attr ("href","#");
			ajoutLastImage = jQuery('<img>');
			ajoutLastImage.attr ("src", j_basepath+"design/back/images/last.gif");
			ajoutLastA.append (ajoutLastImage);
			ajoutLast.append(ajoutLastA);
		}

		ajoutBlocLast.append (ajoutNext);
		ajoutBlocLast.append (ajoutLast);
		divPagination.append(ajoutBlocLast);
		
		// separateur 
		ajoutSeparateur = jQuery('<span></span>');
		ajoutSeparateur.addClass("btnseparator1");
		divPagination.append(ajoutSeparateur);

		// pour l'input type text
		ajoutText= jQuery("<div></div>");
		ajoutText.addClass ("pGroup1");
		ajoutText.append("Page&nbsp;");
		ajoutText.append("<input name='zCurrentPage' class='input' type='text' size='4' value='"+ iCurrentPage +"' style='vertical-align:middle;'")
		if(iNbPage == 0){iNbPage=iCurrentPage;}
		ajoutText.append("&nbsp;sur " + iNbPage);
		divPagination.append (ajoutText);

		// separateur 
		ajoutSeparateur = jQuery('<span></span>');
		ajoutSeparateur.addClass("btnseparator1");
		divPagination.append(ajoutSeparateur);

		// bloc pour le first et le previous
		ajoutBlocFirst = jQuery("<div></div>");
		ajoutBlocFirst.addClass ("pGroup1");

		// first
		ajoutFirst = jQuery('<div></div>');
		ajoutFirst.addClass ("largeur");
	
		if (iCurrentPage > 1)
		{
			ajoutFirstA = jQuery('<a></a>');
			ajoutFirstA.addClass ("first");
			ajoutFirstA.attr ("href","#");
			ajoutFirstImage = jQuery('<img>');
			ajoutFirstImage.attr ("src", j_basepath+"design/back/images/first.gif");
			ajoutFirstA.append (ajoutFirstImage);
			ajoutFirst.append(ajoutFirstA);
		}

		// previous
		ajoutPrev = jQuery('<div></div>');
		ajoutPrev.addClass ("largeur");

		if (iCurrentPage > 1)
		{
			ajoutPrevA = jQuery('<a></a>');
			ajoutPrevA.addClass ("prev");
			ajoutPrevA.attr ("href","#");
			ajoutPrevImage = jQuery('<img>');
			ajoutPrevImage.attr ("src", j_basepath+"design/back/images/prev.gif");
			ajoutPrevA.append (ajoutPrevImage);
			ajoutPrev.append(ajoutPrevA);
		}

		ajoutBlocFirst.append (ajoutFirst);
		ajoutBlocFirst.append (ajoutPrev);
		divPagination.append(ajoutBlocFirst);

		// separateur 
		ajoutSeparateur = jQuery('<span></span>');
		ajoutSeparateur.addClass("btnseparator1");
		divPagination.append(ajoutSeparateur);

		ajoutDiv = jQuery("<div></div>");
		ajoutDiv.attr ("id", "groupSelect");
		ajoutDiv.append ("&nbsp;Affichage&nbsp;");
		ajoutDiv.addClass("pGroup1");
		
		ajoutSelect = jQuery("<select></select>");
		ajoutSelect.attr ("name","rp");
		ajoutSelect.addClass ("selection") ; 
		
		ajoutOption1 = jQuery("<option>5&nbsp;</option>");
		ajoutOption1.attr("value","5");
		if (iParPage==5)
		{
			ajoutOption1.attr("selected","selected");
			
		}
		
		ajoutOption2 = jQuery('<option>10&nbsp;&nbsp;</option>');
		ajoutOption2.attr("value","10");
		if (iParPage==10)
		{
			ajoutOption2.attr("selected","selected");
		}

		ajoutOption3 = jQuery("<option>15&nbsp;&nbsp;</option>");
		ajoutOption3.attr("value","15");
		if (iParPage==15)
		{
			ajoutOption3.attr("selected","selected");
		}

		ajoutOption4 = jQuery("<option>20&nbsp;&nbsp;</option>");
		ajoutOption4.attr("value","20");
		if (iParPage==20)
		{
			ajoutOption4.attr("selected","selected");
		}

		ajoutOption5 = jQuery("<option>30&nbsp;&nbsp;</option>");
		ajoutOption5.attr("value","30");
		if (iParPage==30)
		{
			ajoutOption5.attr("selected","selected");
		}

		ajoutOption6 = jQuery("<option>50&nbsp;&nbsp;</option>");
		ajoutOption6.attr("value","50");
		if (iParPage==50)
		{
			ajoutOption6.attr("selected","selected");
		}

		ajoutOption7 = jQuery("<option>75&nbsp;&nbsp;</option>");
		ajoutOption7.attr("value","75");
		if (iParPage==75)
		{
			ajoutOption7.attr("selected","selected");
		}

		ajoutOption8 = jQuery("<option>100&nbsp;&nbsp;</option>");
		ajoutOption8.attr("value","100");
		if (iParPage==100)
		{
			ajoutOption8.attr("selected","selected");
		}

		ajoutSelect.append(ajoutOption1);
		ajoutSelect.append(ajoutOption2);
		ajoutSelect.append(ajoutOption3);
		ajoutSelect.append(ajoutOption4);
		ajoutSelect.append(ajoutOption5);
		ajoutSelect.append(ajoutOption6);
		ajoutSelect.append(ajoutOption7);
		ajoutSelect.append(ajoutOption8);

		ajoutDiv.append(ajoutSelect);
		ajoutDiv.append ("&nbsp;par page&nbsp;");
		divPagination.append(ajoutDiv);

		// separateur 
		ajoutSeparateur = jQuery("<span></span>");
		ajoutSeparateur.addClass("btnseparator1");
		divPagination.append(ajoutSeparateur);


		$(this).find (".input").keyup (function() { 
			iPage = $(".input").val();
			iPage = new Number (iPage);
			nbParPage = $("select[@name=rp]").val();
			if (isNaN (iPage))
			{
				alert ("la page que vous venez d'entrer est incorrecte");
				$("input[@name=zCurrentPage]").val(iCurrentPage);
				return false;
			}
			else
			{
				if (iPage==0 || iPage > iNbPage)
				{
					alert ("la page que vous venez d'entrer est incorrecte");
					$("input[@name=zCurrentPage]").val(iCurrentPage);
					return false;
				}
				else
				{
					loadSortableListWithPagination (iPage,nbParPage);
				}
				
			}
		}) ;
		
		$(this).find (".selection").change (function() {
			iPage = 1 ; 
			nbParPage = $(".selection").val();
			loadSortableListWithPagination (iPage,nbParPage);
		}) ;

		$(this).find (".first").click (function() {
			nbParPage = $("select[@name=rp]").val();
			iPage = 1;
			loadSortableListWithPagination (iPage,nbParPage);
		}) ;

		$(this).find (".next").click (function() {
			table = $(this).parents ('.sortableListWithPagination').find ('table') ;
			nbParPage = $("select[@name=rp]").val();
			
			iCurrentPage = new Number (table.attr('iCurrentPage')) ;
			iPage = iCurrentPage + 1 ; 
		
			loadSortableListWithPagination (iPage,nbParPage);

		}) ;

		$(this).find (".prev").click (function() {
			
			table = $(this).parents ('.sortableListWithPagination').find ('table') ;
			nbParPage = $("select[@name=rp]").val();
			iCurrentPage = new Number (table.attr('iCurrentPage')) ;
			iPage = iCurrentPage - 1 ; 
			loadSortableListWithPagination (iPage,nbParPage);
		}) ;

		$(this).find (".last").click (function() {
			
			table = $(this).parents ('.sortableListWithPagination').find ('table') ;
			nbParPage = $("select[@name=rp]").val();
			iPage = table.attr("iNbPage");
			loadSortableListWithPagination (iPage,nbParPage);
		}) ;

		//Gestion du tri
		//Si il n'y a qu'une seule ligne dans le tableau, on ne fait rien
		if($(this).find ('tbody').find ('tr').length == 1) {
			return ;
		}
		//On applique les styles et �v�nements sur chaque th
		$(this).find ('th').each (function () {
			zSortField = $(this).attr ('zSortField') ;
			if (zSortField != undefined) {
				if (zSortField == zCurrentSortField) {
					if(zCurrentSortDirection == 'DESC') {
						$(this).addClass ('sortUp') ;
					} else {
						$(this).addClass ('sortDown') ;
					}
				} else {
					$(this).addClass ('sortable') ;
				}
				$(this).click (function() {
					zSortField = $(this).attr ('zSortField') ;
					table = $(this).parents ('.sortableListWithPagination').find('table') ;
					zCurrentSortField = table.attr ('zCurrentSortField') ;
					zCurrentSortDirection = table.attr ('zCurrentSortDirection') ;
					src = table.attr ('src') ;
					if (zSortField == zCurrentSortField) {
						if (zCurrentSortDirection == 'DESC') {
							zSortDirection = 'ASC' ;
						} else {
							zSortDirection = 'DESC' ;
						}
					} else {
						zSortDirection = 'ASC' ;
					}

					$(this).parents ('.ajaxZone').load (src, {zSortField:zSortField, zSortDirection:zSortDirection}, function(){
						doOnLoad ($(this)) ;
					}) ;

				}) ;
			}
		}) ;
	}) ;
} ;