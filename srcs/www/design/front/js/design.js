$(function () {

		$('#planning-content').find('td').hover(
			function ()	{
				$(this).addClass('activetd');
				
				
			}, function () {
				$(this).removeClass('activetd');
			}
		);
		
		$('.bloccalendar').find('table').find('td').hover(
			function ()	{
				$(this).addClass('activehover');
				
				
			}, function () {
				$(this).removeClass('activehover');
			}
		);
		
		$('.titleselection').find('a.close').click(function() {
		  $('.contentselect').slideToggle('slow', function() {											   
				$('.titleselection').find('li.choice').toggleClass("choicehide");
				if ($('.choicehide').html() != null)
				{
					$.getJSON(j_basepath + "index.php", {module:"jelix_calendar", action:"FoCalendar:afficherChacherBlocSelection", i:0}, function(datas){
						$('.contentselect').hide();
					});
				}else{
					$.getJSON(j_basepath + "index.php", {module:"jelix_calendar", action:"FoCalendar:afficherChacherBlocSelection", i:1}, function(datas){
						$('.contentselect').show();
					});
				}
		  });
		});
}) ;