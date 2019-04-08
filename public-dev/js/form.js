/** global: jQuery */
jQuery(function($) {
	function heartbeat()
	{
		$.post('/heartbeat');
	}

	setInterval(heartbeat, 300000);
	$('#url').attr('disabled', !$('#present').is(':checked'));
	$('#present, #absent').change(function() {
		$('#url').attr('disabled', !$('#present').is(':checked'));
	});

	$.datepicker.regional.ru = {
		closeText: "Закрыть",
		prevText: "&#x3C;Пред",
		nextText: "След&#x3E;",
		currentText: "Сегодня",
		monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
		monthNamesShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", 	"Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
		dayNames: ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"],
		dayNamesShort: ["вск", "пнд", "втр", "срд", "чтв", "птн", "сбт"],
		dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
		weekHeader: "Нед",
		dateFormat: "dd.mm.yy",
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ""
	};

	$.datepicker.setDefaults($.datepicker.regional["ru"]);
	$("#dob").datepicker({ dateFormat: "dd.mm.yy", maxDate: "-10y" });
});
