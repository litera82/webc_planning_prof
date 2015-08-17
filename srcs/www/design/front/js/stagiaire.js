/*addEvenement.js*/
$( function () {
	addEvent(window, "load", tmt_validatorInit);


	$('.submitForm').click(
		function(){
			//$('#sendMail').val(0);
			//$('#sendMailAuto').val(0);
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);


	/*$('.submitFormMail').click(
		function(){
			$('#sendMail').val(1);
			$('#sendMailAuto').val(0);
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);
	
	$('.submitFormAutoplannification').click(
		function(){
			$('#sendMail').val(0);
			$('#sendMailAuto').val(1);
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);*/

});