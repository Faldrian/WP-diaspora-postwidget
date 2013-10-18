<?php 
/*
Plugin Name: WP Diaspora Postwidget
Description:  A wordpress plugin that gives you a dashboard-widget to post to Diaspora.
Version: 1.0
Author: Faldrian
Author URI: http://jenseitsderfenster.de/
*/

// Plugin URI: http://wordpress.org/plugins/show-other-images/
require_once('diasphp.php');

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
	
	// Admin-Konfiguration nur anzeigen, wenn man auch Admin ist
	if(current_user_can('manage_options')) {
		wp_add_dashboard_widget(
			'postwidget_widget',
			'Post to Diaspora',
			'postwidget_widget',
			'postwidget_widget_options'
		);
	} else {
		wp_add_dashboard_widget(
			'postwidget_widget',
			'Post to Diaspora',
			'postwidget_widget'
		);
	}
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
 * Code for configuration of the widget
 */
function postwidget_widget_options() {
	// Hole die Optionen
	$opts = get_option( 'dashboard_widget_options' );
	
	// Wenns noch nicht eingestellt ist, dann liefer einen Fehler zurück
	if(isset($opts['postwidget'])) {
		$config = $opts['postwidget'];
 	} else {
 		$config = array('pod' => '', 'poduser' => '', 'podpass' => '');
 	}
	
	// Speicher die geänderten Optionen
	foreach(array('pod', 'poduser', 'podpass') as $val) {
		if(isset($_POST[$val])) {
			$config[$val] = $_POST[$val];
		}
	}
	$opts['postwidget'] = $config;
	update_option( 'dashboard_widget_options', $opts );
	
	// Formular anzeigen
	echo <<<PRE
<table>
	<tr>
		<th><label for="pod">Diaspora-Pod<br />(Format: https://host:port)</label></td>
		<td><input name="pod" type="text" value="$config[pod]" /></td>
	</tr>
	<tr>
		<th><label for="poduser">Username</label></th>
		<td><input name="poduser" type="text" value="$config[poduser]" /></td>
	</tr>
	<tr>
		<th><label for="podpass">Password</label></th>
		<td><input name="podpass" type="password" placeholder="******" /></td>
	</tr>
</table>
PRE;
	
}

/**
 * Ajax-Handler for sending the post.
 */
add_action('wp_ajax_postwidget_submit', 'postwidget_submit');
function postwidget_submit() {
	// Optionen zum Einloggen holen
	$opts = get_option( 'dashboard_widget_options' );
	
	// Wenns noch nicht eingestellt ist, dann liefer einen Fehler zurück
	if(!isset($opts['postwidget'])) {
		die("Please configure the Diaspora-Pod first!");
	}
	$config = $opts['postwidget'];
	
	try {
		$conn = new Diasphp($config['pod']);
		$conn->login($config['poduser'], $config['podpass']);
		$conn->post($_REQUEST['content']);
	} catch (Exception $e) {
		die("Error submitting the post: " . $e->getMessage());
	}
	
	die("ok");
}
?>