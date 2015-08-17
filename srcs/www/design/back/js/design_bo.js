// JavaScript Document

/* document Ready */
$( function () {
	addEvent(window, "load", tmt_validatorInit);
	doOnLoad($("body"));
});

function getUrlVars()
{
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	 
	for(var i = 0; i < hashes.length; i++)
	{
	hash = hashes[i].split('=');
	vars.push(hash[0]);
	vars[hash[0]] = hash[1];
	}
	 
	return vars;
}
function doOnLoad(context){

	$('.errorMessage', context).hide();
	var vars = getUrlVars();
	if (vars['action'] != 'utilisateurs:edit'){
		$('.sortableListWithPagination', context).sortableListWithPagination();
	}
	
	$(".submit", context).click(function(){
		$('#errorMessage').html('');
		$(this).parents("form").children('.errorMessage').hide();;
		if($(this).parents("form")[0].onsubmit()){
			$(this).parents("form")[0].submit();
		}
	});
	
	$("a[alt=supprimer]", context).click(function(){
		return (confirm("Etes-vous sûr de vouloir supprimer cet élément ?"));
	});
	
	$('.imageDate').mouseover(function(){
		$(this).prev('input[type=text]').val('');
	});
	
	// Tabs
	//$('#tabs').tabs();
}

/* Suppression d'un element d'une liste */
function deleteEntry(_zDeleteActionUrl, _zMessageConfirm)
{
    if(confirm(_zMessageConfirm))
    {
        document.location.href = _zDeleteActionUrl;
    }
}

function deleteEntryTypeEvenemt(_zDeleteActionUrl, _zMessageConfirm, _iTypeEvenementId)
{

    if(confirm(_zMessageConfirm))
    {
        if (_iTypeEvenementId > 0)
        {
			$.ajax({
				 type:"POST",
				 url: j_basepath+'admin.php?module=typeEvenement&action=typeEvenement:testSupprimable',
				 data: {
					 iTypeEvenementId: _iTypeEvenementId
				 },
				 async:false,
				 success:function(resultat){
					if (resultat == 1)
					{
						alert("Impossible de supprimer cet type d'événement, il est associé à un événement");
					}else{
						document.location.href = _zDeleteActionUrl;
					}
				 }
			});	
        }
    }
}

function createMenu (menuName) {
	src = '<ul>' + createMenuItem (menuName, -1, 0, 0) + '</ul>' ;
	return (src) ;
}

function createMenuItem (menuName, parentIndex, itemIndex, level) {
	src = '' ;
	selected = '';
	// if ((menuName[itemIndex][3] != undefined) && (menuName[itemIndex][3].length > 0) &&  (menuactive[level] ) == itemIndex) {
	if ((menuName[itemIndex][3] != undefined) && (menuName[itemIndex][3].length > 0)) {
		src = '<ul>' + createMenuItem (menuName[itemIndex][3], itemIndex, 0, level + 1) + '</ul>';
	}
	
	if (parentIndex == -1) {
		if ( menuactive[level] == itemIndex ) {
			selected = ' class="select"' ;
		}
	} else {
		if ((menuactive[level] == itemIndex) && (menuactive[level - 1] == parentIndex)) {
			selected = ' class="select"' ;
		}
	}

	if ( itemIndex == menuName.length - 1 ) {
		src1 = '<li>';
		
		if(menuName[itemIndex][4] != undefined && menuName[itemIndex][4] == 'hidden'){
			src1 = '<li style="display:none;">'
		}
		src = src1 + '<a href="' + menuName[itemIndex][1] + '"' + selected + ' target="'+ menuName[itemIndex][2] +'">' + menuName[itemIndex][0] + '</a>\n' + src + '</li>\n' ;
	}else{
		src1 = '<li>';
		if(menuName[itemIndex][4] != undefined && menuName[itemIndex][4] == 'hidden'){
			src1 = '<li style="display:none;">'
		}
		src = src1 + '<a href="' + menuName[itemIndex][1] + '"' + selected + ' target="'+ menuName[itemIndex][2] +'">' + menuName[itemIndex][0] + '</a>\n' + src + '</li>\n' + createMenuItem (menuName, parentIndex, itemIndex + 1, level) ;
	}
//	alert (level + '\n-' + src) ;
	return (src) ;
}

var popUpWin=0;

function popUpWindow(URLStr, width, height) {
	if(popUpWin) {
		if(!popUpWin.closed) popUpWin.close();
	}
		
		w_left = (screen.width - width)/2;
		w_top = (screen.height - height)/2;
		
  	popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+w_left+', top='+w_top+',screenX='+w_left+',screenY='+w_top+'');
}
//Différence de 2 dates
function diffDate(dateDeb,dateFin,sep)
{
	// Attention, en javascript les mois commencent é zéro
	var nbj=new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	
	datedeb = dateDeb.split(sep);
	datefin = dateFin.split(sep);
		
	
	var datedeb=new Date(datedeb[2],datedeb[1],datedeb[0],00,00,00); // Année, Mois, Jour, Heure, Minutes, Secondes
	var datefin=new Date(datefin[2],datefin[1],datefin[0],00,00,00); // Vous pouvez prendre la date du jour : var datefin=new Date();
	aad=datedeb.getYear();mmd=datedeb.getMonth()+1;jjd=datedeb.getDate();hhd=datedeb.getHours();mnd=datedeb.getMinutes();ssd=datedeb.getSeconds();
	aaf=datefin.getYear();mmf=datefin.getMonth()+1;jjf=datefin.getDate();hhf=datefin.getHours();mnf=datefin.getMinutes();ssf=datefin.getSeconds();
	if(aaf<1900){aaf=aaf+1900;}
	if(aad<1900){aad=aad+1900;}
	if(aaf%4==0){nbj[2]=29;}
	if((aaf%100==0)&&(aaf%400!=0)){nbj[2]=28;}
	if(ssf<ssd){ssf=ssf+60;mnf=mnf-1;}
	if(mnf<mnd){mnf=mnf+60;hhf=hhf-1;}
	if(hhf<hhd){hhf=hhf+24;jjf=jjf-1;}
	if(jjf<jjd){jjf=jjf+nbj[mmf];mmf=mmf-1;}
	if(mmf<mmd){mmf=mmf+12;aaf=aaf-1;}
	//Diff en année,mois,jours,min,sec
	//mes=(aaf-aad)+" ans "+(mmf-mmd)+" mois "+(jjf-jjd)+" jours "+(hhf-hhd)+" heures "+(mnf-mnd)+" minutes "+(ssf-ssd)+" secondes";
	return (aaf-aad);
}

$(document).ready( function () {
    $("#navContent li ul").each( function ()
    {
        $(this).addClass("subMenu").hide();

    } ) ;
    
    $("#navContent li ul li a.select").each( function ()
    {
        $(this).parents("ul").fadeIn();
    } ) ;

    $("#navContent li > a").click( function ()
    {

		// Si le sous-menu était déjà ouvert, on le referme :
		if ($(this).next("ul.subMenu:visible").length != 0)
        {
			$(this).next("ul.subMenu").fadeOut();
        }
        // Si le sous-menu est caché, on ferme les autres et on l'affiche :
        else
        {
			$(this).next("ul.subMenu").fadeIn();
        }

    });

} ) ;
