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
		
		$('.category').click(function(){
				var color = $(this).data('color'),
				label = $(this).data('label'),
				id = $(this).data('id');
				
				$('#categories_form #field_1').val(label);
				$('#categories_form #color').val(color);
				$('#categories_form #field_3').val(id);
				$('#categories_form .submit').val('Edit');
		});
	}
)(jQuery);