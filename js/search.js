window.onload = function()
{
	var settings = JSON.parse(localStorage.getItem('settings')) || {};
	let search;
	$.fn.form = function() {
		return Object.fromEntries(new FormData(this[0]));
	}
	function refresh() {
		var data = {...$('#search').form(), ...settings};
		search = $.post('search.php', data, function(data) {
			$('.results').html(data);
			$('.game-details').on('show.bs.collapse', function() {
				search.abort();
				var id = $(this).data('id');
				var blur = !settings.extreme;
				$(this).load(`view.php?id=${id}&blur=${blur}`, function() {
					$(this).find('.blur').on('click', function() {
						$(this).removeClass('blur');
					});
				});
			});
		});
	}
	$('.setting').each(function() {
		var val = settings[this.name];
		if (this.type == 'checkbox') {
			$(this).prop('checked', !!val);
		} else {
			$(this).val(val);
		}
	});
	$('#settings-dialog').on('hidden.bs.modal', function() {
		settings = $('#settings').form();
		localStorage.setItem('settings', JSON.stringify(settings));
		refresh();
	});
	$('#search').on('submit', function(e) {
		e.preventDefault();
	});
	$('#search .form-control').on('input change', refresh);
}
