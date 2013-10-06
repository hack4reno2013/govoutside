(function($)
	{
		console.log($('#color_input').val());
$('#color_picker').ColorPicker({
	color: $('#color_input').val(),
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#color_picker div').css('backgroundColor', '#' + hex);
		$('#color_input').val('#'+hex);
	}
});
	}
)(jQuery);