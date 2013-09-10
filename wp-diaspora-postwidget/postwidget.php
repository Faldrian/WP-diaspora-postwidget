<?php 
/*
Plugin Name: WP Diaspora Postwidget
Description:  A wordpress plugin that gives you a dashboard-widget to post to Diaspora.
Version: 1.0
Author: Faldrian
Author URI: http://jenseitsderfenster.de/
*/

// Plugin URI: http://wordpress.org/plugins/show-other-images/



/**
 * Called when intializing the dashboard, adds scripts / styles & the widget itself.
 */
add_action('wp_dashboard_setup', 'postwidget_setup_dashboard');
function postwidget_setup_dashboard() {
	// *** JS hinzufügen
	wp_enqueue_script("postwidget", plugin_dir_url(__FILE__).'js/postwidget.js', array('jquery', 'markdown'));
	
	wp_enqueue_script("markdown", plugin_dir_url(__FILE__).'js/Markdown.Converter.js');

	// *** Style hinzufügen
	wp_enqueue_style('feedmanager_dashboard_style', plugin_dir_url(__FILE__).'css/postwidget.css', false, '1.0');
	
	// *** Widgets registrieren
	// Feed-Einreichen-Widget
	wp_add_dashboard_widget(
		'postwidget_widget',
		'Post to Diaspora',
		'postwidget_widget'
	);
}

/**
 * Code displaying the widget
 */
function postwidget_widget() {
	echo <<<PRE
<div><textarea id="postwidget_content"></textarea></div>
<div id="postwidget_buttons">
	<div>Preview:</div>
	<button class="button button-primary" id="postwidget_btn_preview">Vorschau</button>
	<button class="button" id="postwidget_btn_submit">Abschicken</button>
</div>
<div id="postwidget_preview"></div>
PRE;
}

/**
 * Ajax-Handler for sending the post.
 */
add_action('wp_ajax_postwidget_submit', 'postwidget_submit');
function postwidget_submit() {
	echo $_REQUEST['content'];
	// https://github.com/Javafant/diaspy/blob/master/client.py
	exit();
}



?>