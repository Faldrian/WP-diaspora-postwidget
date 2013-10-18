jQuery(function() {
	// Hooks an die Buttons setzen
	jQuery('#postwidget_btn_preview').click(function() {
		var converter = new Markdown.Converter();
		var html = converter.makeHtml(jQuery('#postwidget_content').val());
		
		jQuery('#postwidget_buttons div').css('display','block');
		jQuery('#postwidget_preview').css('display','block').removeClass('result_ok').removeClass('result_error');
		jQuery('#postwidget_preview').html(html);
	});
	
	jQuery('#postwidget_btn_submit').click(function() {
		// Ajax-Abschicken
		var data = {
			'action': 'postwidget_submit',
			'content': jQuery('#postwidget_content').val()
		};
		jQuery.post(ajaxurl, data, function(response) {
			if(response == "ok") {
				jQuery('#postwidget_preview').css('display','block').addClass('result_ok').html('Post sent.');
				jQuery('#postwidget_content').val('');
			} else {
				jQuery('#postwidget_preview').css('display','block').addClass('result_error').html(response);
			}
			jQuery('#postwidget_buttons div').css('display','none');
		});
	});
});

