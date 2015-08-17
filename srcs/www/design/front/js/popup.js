/*
 *
 *	@titre: popup.
 *	@description: javascript popup
 *	@auteur: neov - http://www.neov.net.
 *	@creation: 20090522.
 *	@modification: -.
 *
*/

var fadeTime = 300;
var lastOpen = null;
var wopener = null;

function afficherMasque()
{
	var w = $('body').width();
	var mh = $('body').height();
	var ih = $(document).height();

	if ( mh < ih ) mh = ih;		

	$('#masque')
		.css({width: w + 'px', height: mh +'px', opacity: 0.5, filter:'Alpha(Opacity=50)'})
		.fadeIn(fadeTime);

}

// affichage popup

$.fn.showPop = function(el, f)

{
	
	$(this).each(
		function()
		{
			$(this).click(
				function()
				{
					
					/* if( $(window).height() < $(el).height() ) 
                        extraPos = 150; */
					var lien = $(this).attr('title');
					$('.titreLien').html(lien);
					$('.ok').attr('href','http://' + lien);

					var tPos = ( $(window).height() - $(el).height() )/2 + $(window).scrollTop();
					
					if (tPos < 0)
					   tPos = 0;
					   
					var lPos = ( $(window).width() - $(el).width() )/2;
					if (lastOpen != null) { 
						$(lastOpen).fadeOut(fadeTime);
						wopener = lastOpen;
					}
					
					$(el)
						.fadeIn(fadeTime)
						.css({ top: tPos + 'px', left: lPos + 'px' });
					
					//$('select').css({visibility:'hidden'});					
					afficherMasque();
					
					lastOpen = el;
					
					if (f) f.call();
					
					return false;
				}
			);
		}
	);
	
}

$.fn.showElem = function(url)
{ 
	var tPos = ( $(window).height() - $(this).height() )/2 + $(window).scrollTop();
	var lPos = ( $(window).width() - $(this).width() )/2;
	
	$(this)
		.fadeIn(fadeTime)
		.css({ top: tPos + 'px', left: lPos + 'px' });
	
	lastOpen = this;
	//$('select').css({visibility:'hidden'});	
		
	afficherMasque();
	
	return false;
}



$.fn.hidePop = function(f)
{
	$(this).each(
		function()
		{
			$(this).click(
				function()
				{
					$('#masque').fadeOut(fadeTime);
					$(lastOpen).fadeOut(fadeTime);
					$('select').css({visibility:'visible'});
					if (f) f.call();
					return false;
				}
			);
		}
	);
	
	
	
}

function showBTPProduitCoord () {
	$(wopener).showElem ();
}

/*function showBTPProduitCoord () {
	$('#BTP-produits-LB-demande-pro').showElem ();
}*/

$(function() {
	$('.pop-up').hide();
	
	// ouverture popup
	$('.create').showPop('#periodepop');
	$('.sendCoursByMail').showPop('#periodepopsendmail');
	$('.sendCoursByMail').showPop('#periodepop');
	
	// fermeture popup
	$('.fermer').hidePop();
	$('.fermerPop').hidePop();
	$('.modifierEvent').showPop('#periodepop');
	$('.validateCours').showPop('#validatepop');

});

