$(document).ready(function () {
    $(".clickable-row").click(function () {
        window.location = $(this).attr("data-href");
    });

    $('.tabs').tabs();

    $('.datepicker').datepicker({
		'maxDate': new Date(),
		'defaultDate': new Date(),
		'setDefaultDate': true,
		'format': 'yyyy-mm-dd'
	});
	$('.timepicker').timepicker({
		'twelveHour': false
	});
});