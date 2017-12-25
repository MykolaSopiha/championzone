window.$ = window.jQuery = require('jquery');
window.$ = $.extend(require('jquery-ui-bundle'));
var dt = require('datatables');
var bootstrap = require('bootstrap-sass');
var chosen_js = require('chosen-js');



$(document).ready(function () {


	// BEGIN Menu
	const menu = $('#menu');
	const menu_icon = $('.menu_icon');

	$('#menu_btn').click(function() {
		menu.slideToggle();
		menu_icon.toggleClass('open');
	});
	// END Menu


	// BEGIN Card type select
	const checkCardType = function (el) {

		var payment_sys = $(el).val();
		var $inputs     = $('input#date, input#cw2');
		var $blocks     = $inputs.parent('div');
		var $options    = $('select#currency option:not([value="USD"],[value="RUB"])');

		if (payment_sys == '1') {
			$inputs.removeAttr('required');
			$blocks.slideUp();
			$options.attr('disabled','disabled');
		} else {
			$inputs.attr('required', 'true');
			$blocks.slideDown();
			$options.removeAttr('disabled');
		}

	}
	$('.card_type input[name="type"]').on('click', function () {
		checkCardType(this);
	});

	var payment_sys = $('.card_type input[name="type"]').val();
	var $inputs     = $('input#date, input#cw2');
	var $blocks     = $inputs.parent('div');
	var $options    = $('select#currency option:not([value="USD"],[value="RUB"])');

	if (payment_sys == '1') {
		$inputs.removeAttr('required');
		$blocks.slideUp();
		$options.attr('disabled','disabled');
	}
	// END Card type select


	// BEGIN Chosen-JS Init
	$('.chosen-js-select').chosen();
	// END Chosen-JS Init


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
		var $form = $("<form></form>");

		$form.attr("method", method);
		$form.attr("action", path);

		$.each(parameters, function(key, value) {
			var $field = $("<input></input>");

			$field.attr("type", "hidden");
			$field.attr("name", key);
			$field.attr("value", value);

			$form.append($field);
		});

		// The form needs to be a part of the document in
		// order for us to be able to submit it.
		$(document.body).append($form);
		$form.submit();
	}
	// END Post request function END


	// BEGIN Initialization of datepicker BEGIN
	$(function() {
		$('.card_date').datepicker({
			dateFormat: "yy/mm",
			changeMonth: true,
			changeYear: true,
			yearRange: "-5:+10",
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


	// BEGIN Make 2 decimals for money value BEGIN
	$('.money_input').on('blur', function () {
		var money = $(this).val();
		money = Number.parseFloat(money);
		money = (money).toFixed(2);
		$(this).val(money);
	});
	// END Make 2 decimals for money value END


	// BEGIN Current exchange rate BEGIN
	const NBU_rate = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json';
	var current_rate = 1;

	const checkExchangeRate = function() {
		var currency = $('#card option:selected').attr('title');
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
				}).fail( function () {
					$('#rate').val(0.035899);
				} );
			}

			if (currency === 'EUR') {
				$.getJSON(NBU_rate, function(result){
					var USD = 1, EUR = 1;
					$.each(result, function(i, field){
						if (field.r030 === 840) {
							USD = field.rate;
						}
						if (field.r030 === 978) {
							EUR = field.rate;
						}
					});
					$('#rate').val( (EUR/USD).toFixed(6) );
				}).fail( function () {
					$('#rate').val(1.185900);
				} );
			}

			if (currency === 'RUB') {
				$.getJSON(NBU_rate, function(result){
					var USD = 1, RUB = 1;
					$.each(result, function(i, field){
						if (field.r030 === 840) {
							USD = field.rate;
						}
						if (field.r030 === 643) {
							RUB = field.rate;
						}
					});
					$('#rate').val( (RUB/USD).toFixed(6) );
				}).fail( function () {
					$('#rate').val(0.017077);
				} );
			}

		}
	}

	$('#get_rate').on('click', checkExchangeRate);
	$('select#card').on('change', checkExchangeRate);
	checkExchangeRate();
	// END Current exchange rate END


	// BEGIN Multiple checkbox selection BEGIN
    // let $chkboxes = $('.shift_select');
    // let lastChecked = null;

    // $chkboxes.click(function(e) {
    //     if(!lastChecked) {
    //         lastChecked = this;
    //         return;
    //     }

    //     if(e.shiftKey) {
    //         let start = $chkboxes.index(this);
    //         let end = $chkboxes.index(lastChecked);

    //         $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);

    //     }

    //     lastChecked = this;
    // });
	// END Multiple checkbox selection END


	// BEGIN Select all checkboxes in table BEGIN
	const selectManyCheckboxes = function () {

		var form = $(this).closest('form');
		var all_checkbxs = form.find("input[type='checkbox']");

		if ( $(this).prop('checked') === false ) {
			all_checkbxs.prop('checked', false);
		} else {
			all_checkbxs.prop('checked', true);
		}

		return all_checkbxs;
	}

	$('.table').on('click', '.all_select', selectManyCheckboxes);
	// END Select all checkboxes in table END



	// BEGIN tables
	$('.js-table').DataTable();
	$('#tokens_list').DataTable({
		"order": [8, "asc"]
	});
	// END tables


	// BEGIN tables
	// $('.modal_token').modal();
	// END tables

	// BEGIN Delete resource request BEGIN
	$('.js-form').on('click', '.remove-btn', function (e) {
		e.preventDefault();
		var url = $(this).attr('href');
		var form = $('.js-form').attr({
			'action':url
		});
		var token = $(form).find('#token').val();
		var deleteInput = $('<input>').attr({
			'type' : 'hidden',
			'name' : '_method',
			'value' : 'delete'
		});
		$(form).append(deleteInput);
		$(form).submit();
	});
	// END Delete resource request END



	// BEGIN Delete resource request BEGIN
	$('.js-submit').on('click', function (e) {

		e.preventDefault();
		var form = $('.js-form');
		var action = $('.js-action').val();

		form.submit();

	});
	// END Delete resource request END



	// BEGIN Multiple assignment of cards  BEGIN
	const users_select = $('.js-users');
	users_select.hide();

	$('.js-action').change(function () {
		if ( $(this).val() == '1' ) {
			users_select.show();
		} else {
			users_select.hide();
		}
			
	});
	// END Multiple assignment of cards  END



});