function permuter(_iTestId, _iAction){
			var zUrlTraitement = $('#urlTraitementOrdre').val();

				$.ajax({
					 type:"POST",
					 url: zUrlTraitement,
					 data: {
							iTestId:_iTestId, 
							iAction:_iAction
						  },
					 async:false,
					 success:function(resultat){
						$('.liste').html('');
						$('.liste').html(resultat);
					 }
				});	

		}