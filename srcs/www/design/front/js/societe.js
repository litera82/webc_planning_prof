/*addEvenement.js*/
$( function () {
	addEvent(window, "load", tmt_validatorInit);


	$('.submitForm').click(
		function(){
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);


});