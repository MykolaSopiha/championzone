$(document).ready(function () {



	// BEGIN Turn off hover effects on touch screens. BEGIN
	const isTouchDevice = function() {
		return ('ontouchstart' in window) || navigator.maxTouchPoints;
	}

	if ( isTouchDevice() == false ) {
		$('body').addClass('no-touch');
	}
	// END Turn off hover effects on touch screens. END



	// BEGIN Post request function BEGIN
	const post = function(path, method, parameters) {
		let form = $('<form></form>');

		form.attr("method", method);
		form.attr("action", path);

		$.each(parameters, function(key, value) {
			let field = $('<input></input>');

			field.attr("type", "hidden");
			field.attr("name", key);
			field.attr("value", value);

			form.append(field);
		});

		// The form needs to be a part of the document in
		// order for us to be able to submit it.
		$(document.body).append(form);
		form.submit();
	}
	// END Post request function END



	// $('.card_number').inputmask("9999-9999-9999-9999");
	// $('.money').inputmask('9{*}.9{2}');
	// $('new-').


	// BEGIN Initialization of datepicker BEGIN
	$(function() {
		$('.card_date').datepicker({
			dateFormat: "yy/mm",
			changeMonth: true,
			changeYear: true,
			yearRange: "-5:+5",
			showButtonPanel: true,
			onClose: function(dateText, inst) {
				function isDonePressed(){
					return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
				}
				if (isDonePressed()){
					var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
					var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
					$(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
					$('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
				}
			},
			beforeShow : function(input, inst) {
				inst.dpDiv.addClass('month_year_datepicker')
				if ((datestr = $(this).val()).length > 0) {
					year = datestr.substring(datestr.length-4, datestr.length);
					month = datestr.substring(0, 2);
					$(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
					$(this).datepicker('setDate', new Date(year, month-1, 1));
					$(".ui-datepicker-calendar").hide();
				}
			}
		});

		$('.pick_date').datepicker({
			dateFormat: "yy-mm-dd",
			changeDay: true,
			changeMonth: true,
			changeYear: true,
			yearRange: "-5:+5",
			showButtonPanel: false
		});

		$('.pick_birthday').datepicker({
			dateFormat: "yy-mm-dd",
			changeDay: true,
			changeMonth: true,
			changeYear: true,
			yearRange: "-70:+0",
			showButtonPanel: false
		});

	});
	// END Initialization of datepicker END



	$('.money_input').on('blur', function () {
		let money = $(this).val();
		money = Number.parseFloat(money);
		money = (money).toFixed(2);
		$(this).val(money);
	});



	// BEGIN Current exchange rate BEGIN
	const NBU_rate = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json';
	let current_rate = 1;

	$('#get_rate').on('click', function () {
		let currency = $('#card option:selected').attr('title');
		if (currency === 'USD') {
			$('#rate').val(1);
		} else {

			if (currency === 'UAH') {
				$.getJSON(NBU_rate, function(result){
					$.each(result, function(i, field){
						if (field.r030 === 840) {
							$('#rate').val( (1/field.rate).toFixed(6) );
						}
					});
				});
			}

			if (currency === 'EUR') {
				$.getJSON(NBU_rate, function(result){
					let USD = 1, EUR = 1;
					$.each(result, function(i, field){
						if (field.r030 === 840) {
							USD = field.rate;
						}
						if (field.r030 === 978) {
							EUR = field.rate;
						}
					});
					$('#rate').val( (EUR/USD).toFixed(6) );
				});
			}

			if (currency === 'RUB') {
				$.getJSON(NBU_rate, function(result){
					let USD = 1, RUB = 1;
					$.each(result, function(i, field){
						if (field.r030 === 840) {
							USD = field.rate;
						}
						if (field.r030 === 643) {
							RUB = field.rate;
						}
					});
					$('#rate').val( (RUB/USD).toFixed(6) );
				});
			}

		}
	});


	// END Current exchange rate END
});