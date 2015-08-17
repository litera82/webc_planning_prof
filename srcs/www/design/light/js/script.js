function doOnLoad(context){
	// --- accordion
	$("#accordion", context).accordion({ header: "h3", autoHeight: false });
	// --- boutton
    $('.menu a, button', context).button();
    $('.deconnexion', context).button({icons: { primary: "ui-icon-locked" }});
    
    $( "#loader", context ).dialog({
		autoOpen: false,
        resizable: false,
        height: 49,
        width: 235,
        title: 'opération en cours',               
        modal: true
	});
}
$(function(){
    
	doOnLoad($("body"));

});