$(window).on("load", function () {
	$('a[href="#convert"]').on('click', function (elem) {
		elem.preventDefault();
		Convert();
	});
	$('a[href="#clear"]').on('click', function (elem) {
		elem.preventDefault();
		Clear();
	});
});

function Convert () {
	var data = {};

	data.from = {}
	data.from.count = $('#currency_from_count').val();
	data.from.code = $('#currency_from').val();
	
	data.to = {};
	data.to.code = $('#currency_to').val();


	$.ajax({
		type:"POST",
		url: "/ajax/convert/"+data.from.code+"/"+data.to.code+"/",
		data: JSON.stringify(data),
		contentType: 'application/json',
		success: function (res) {
			if (res.length > 0) {
				var data = JSON.parse(res);
				add_history_item(data);
			}
		}
	});
}

function add_history_item (ob) {
	var Main = $('tbody', 'table');
	$(Main).parent().attr("style", '');
	$('h2.void_history').attr('style', 'display:none');
	var Block = $('<tr>').appendTo(Main);
	$('<td>',{html:ob.id}).appendTo(Block);
	$('<td>',{html:ob.in}).appendTo(Block);
	$('<td>',{html:ob.count}).appendTo(Block);
	$('<td>',{html:ob.out}).appendTo(Block);
	$('<td>',{html:ob.result}).appendTo(Block);
	$('<td>',{html:ob.date}).appendTo(Block);
	$('#currency_to_count').val(ob.result);
}

function Clear () {
	$.ajax({
		type:"GET",
		url: "/ajax/clear/history",
		data: "",
		contentType: 'application/json',
		success: function (res) {
			if (res.length > 0) {
				var message = JSON.parse(res);
				if (message.success == "true") {
					$('tbody', 'table').empty();
				}
				else {
					alert("Произошла ошибка попробуйте еще раз!");
				}
			}
		}
	});
}

