jQuery(function() {
	// Hooks an die Buttons setzen
	jQuery('#postwidget_btn_preview').click(function() {
		var converter = new Markdown.Converter();
		var html = converter.makeHtml(jQuery('#postwidget_content').val());
		
		jQuery('#postwidget_buttons div').css('display','block');
		jQuery('#postwidget_preview').css('display','block');
		jQuery('#postwidget_preview').html(html);
	});
	
	jQuery('#postwidget_btn_submit').click(function() {
		// Ajax-Abschicken
		var data = {
			'action': 'postwidget_submit',
			'content': jQuery('#postwidget_content').val()
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
		});
	});
});

