<?php

// Pandora FMS - http://pandorafms.com
// ==================================================
// Copyright (c) 2005-2011 Artica Soluciones Tecnologicas
// Please see http://pandorafms.org for full contribution list

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation for version 2.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Load global vars
global $config;

check_login ();

$table = new StdClass();
$table->class = 'databox filters';
$table->id = 'setup_general';
$table->width = '100%';
$table->data = array ();
$table->size = array();
$table->size[0] = '30%';
$table->style[0] = 'font-weight:bold';
$table->size[1] = '70%';

// Current config["language"] could be set by user, not taken from global setup !

switch ($config["dbtype"]) {
	case "mysql":
		$current_system_lang = db_get_sql ('SELECT `value`
			FROM tconfig WHERE `token` = "language"');
		break;
	case "postgresql":
		$current_system_lang = db_get_sql ('SELECT "value"
			FROM tconfig WHERE "token" = \'language\'');
		break;
	case "oracle":
		$current_system_lang = db_get_sql ('SELECT value
			FROM tconfig WHERE token = \'language\'');
		break;
}

if ($current_system_lang == "") {
	$current_system_lang = "en";
}

$table->data[0][0] = __('Language code');
$table->data[0][1] = html_print_select_from_sql (
	'SELECT id_language, name FROM tlanguage',
	'language', $current_system_lang , '', '', '', true);

$table->data[1][0] = __('Remote config directory') .
	ui_print_help_tip (__("Directory where agent remote configuration is stored."), true);

$table->data[1][1] = html_print_input_text ('remote_config', io_safe_output($config["remote_config"]), '', 30, 100, true);

$table->data[2][0] = __('Phantomjs bin directory') . ui_print_help_tip (__("Directory where phantomjs binary file exists and has execution grants."), true);

$table->data[2][1] = html_print_input_text ('phantomjs_bin', io_safe_output($config["phantomjs_bin"]), '', 30, 100, true);

$table->data[6][0] = __('Auto login (hash) password');
$table->data[6][1] = html_print_input_password ('loginhash_pwd', io_output_password($config["loginhash_pwd"]), '', 15, 15, true);

$table->data[9][0] = __('Time source') . ui_print_help_icon ("timesource", true);
$sources["system"] = __('System');
$sources["sql"] = __('Database');
$table->data[9][1] = html_print_select ($sources, 'timesource', $config["timesource"], '', '', '', true);

$table->data[10][0] = __('Automatic check for updates');
$table->data[10][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('autoupdate', 1, '', $config["autoupdate"], true).'&nbsp;&nbsp;';
$table->data[10][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('autoupdate', 0, '', $config["autoupdate"], true);

$table->data[11][0] = __('Enforce https');
$table->data[11][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button_extended ('https', 1, '', $config["https"], false, "if (! confirm ('" . __('If SSL is not properly configured you will lose access to %s Console. Do you want to continue?', get_product_name()) . "')) return false", '', true) .'&nbsp;&nbsp;';
$table->data[11][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('https', 0, '', $config["https"], true);

$table->data[12][0] = __('Use cert of SSL');
$table->data[12][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button_extended ('use_cert', 1, '', $config["use_cert"], false, '', '', true) .'&nbsp;&nbsp;';
$table->data[12][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('use_cert', 0, '', $config["use_cert"], true);

$table->rowstyle[13] = 'display: none;';
$table->data[13][0] = __('Path of SSL Cert.') . ui_print_help_tip (__("Path where you put your cert and name of this cert. Remember your cert only in .pem extension."), true);
$table->data[13][1] = html_print_input_text ('cert_path', io_safe_output($config["cert_path"]), '', 50, 255, true);

$table->data[14][0] = __('Attachment store') . ui_print_help_tip (__("Directory where temporary data is stored."), true);
$table->data[14][1] = html_print_input_text ('attachment_store', io_safe_output($config["attachment_store"]), '', 50, 255, true);

$table->data[15][0] = __('IP list with API access') . ui_print_help_icon ("ip_api_list", true);
if (isset($_POST["list_ACL_IPs_for_API"])) {
	$list_ACL_IPs_for_API = get_parameter_post('list_ACL_IPs_for_API');
}
else {
	$list_ACL_IPs_for_API = get_parameter_get('list_ACL_IPs_for_API', implode("\n", $config['list_ACL_IPs_for_API']));
}
$table->data[15][1] = html_print_textarea('list_ACL_IPs_for_API', 2, 25, $list_ACL_IPs_for_API, 'style="height: 50px; width: 300px"', true);

$table->data[16][0] = __('API password') . 
	ui_print_help_tip (__("Please be careful if you put a password put https access."), true);
$table->data[16][1] = html_print_input_password('api_password', io_output_password($config['api_password']), '', 25, 255, true);

$table->data[17][0] = __('Enable GIS features');
$table->data[17][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('activate_gis', 1, '', $config["activate_gis"], true).'&nbsp;&nbsp;';
$table->data[17][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('activate_gis', 0, '', $config["activate_gis"], true);

$table->data[19][0] = __('Enable Netflow');
$rbt_disabled = false;
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
	$rbt_disabled = true;
	$table->data[19][0] .= ui_print_help_tip (__('Not supported in Windows systems'), true);
}
$table->data[19][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button_extended ('activate_netflow', 1, '', $config["activate_netflow"], $rbt_disabled, '', '', true).'&nbsp;&nbsp;';
$table->data[19][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button_extended ('activate_netflow', 0, '', $config["activate_netflow"], $rbt_disabled, '', '', true);

$zone_name = array('Africa' => __('Africa'), 'America' => __('America'), 'Antarctica' => __('Antarctica'), 'Arctic' => __('Arctic'), 'Asia' => __('Asia'), 'Atlantic' => __('Atlantic'), 'Australia' => __('Australia'), 'Europe' => __('Europe'), 'Indian' => __('Indian'), 'Pacific' => __('Pacific'), 'UTC' => __('UTC'));

$zone_selected = get_parameter('zone');
if ($zone_selected == "") {
	if ($config["timezone"] != "") {
		$zone_array = explode("/", $config["timezone"]);
		$zone_selected = $zone_array[0];
	}
	else {
		$zone_selected = 'Europe';
	}
}

$timezones = timezone_identifiers_list();
foreach ($timezones as $timezone) {
	if (strpos($timezone, $zone_selected) !== false) {
		$timezone_n[$timezone] = $timezone;
	}
}

$table->data[23][0] = __('Timezone setup'). ' ' . ui_print_help_tip(
	__('Must have the same time zone as the system or database to avoid mismatches of time.'), true);
$table->data[23][1] = html_print_input_text_extended(
	'timezone_text', $config["timezone"], 'text-timezone_text', '', 25,
	25, false, '', 'readonly', true);
$table->data[23][1] .= '<a id="change_timezone">'.html_print_image ('images/pencil.png', true, array ('title' => __('Change timezone'))).'</a>';
$table->data[23][1] .= "&nbsp;&nbsp;". html_print_select($zone_name, 'zone', $zone_selected, 'show_timezone();', '', '', true);
$table->data[23][1] .= "&nbsp;&nbsp;". html_print_select($timezone_n, 'timezone', $config["timezone"], '', '', '', true);

$sounds = get_sounds();
$table->data[24][0] = __('Sound for Alert fired');
$table->data[24][1] = html_print_select($sounds, 'sound_alert', $config['sound_alert'], 'replaySound(\'alert\');', '', '', true);
$table->data[24][1] .= ' <a href="javascript: toggleButton(\'alert\');">' . html_print_image("images/control_play_col.png", true, array("id" => "button_sound_alert", "style" => "vertical-align: middle;", "width" => "16", "title" => __('Play sound'))) . '</a>';
$table->data[24][1] .= '<div id="layer_sound_alert"></div>';

$table->data[25][0] = __('Sound for Monitor critical');
$table->data[25][1] = html_print_select($sounds, 'sound_critical', $config['sound_critical'], 'replaySound(\'critical\');', '', '', true);
$table->data[25][1] .= ' <a href="javascript: toggleButton(\'critical\');">' . html_print_image("images/control_play_col.png", true, array("id" => "button_sound_critical", "style" => "vertical-align: middle;", "width" => "16", "title" => __('Play sound'))) . '</a>';
$table->data[25][1] .= '<div id="layer_sound_critical"></div>';

$table->data[26][0] = __('Sound for Monitor warning');
$table->data[26][1] = html_print_select($sounds, 'sound_warning', $config['sound_warning'], 'replaySound(\'warning\');', '', '', true);
$table->data[26][1] .= ' <a href="javascript: toggleButton(\'warning\');">' . html_print_image("images/control_play_col.png", true, array("id" => "button_sound_warning", "style" => "vertical-align: middle;", "width" => "16", "title" => __('Play sound'))) . '</a>';
$table->data[26][1] .= '<div id="layer_sound_warning"></div>';

$table->data[28][0] = __('Public URL');
$table->data[28][0] .= ui_print_help_tip(__('Set this value when your %s across inverse proxy or for example with mod_proxy of Apache.', get_product_name()) .
	' '.__('Without the index.php such as http://domain/console_url/'), true);
$table->data[28][1] = html_print_input_text ('public_url', $config['public_url'], '', 40, 255, true);

$table->data[29][0] = __('Referer security');
$table->data[29][0] .= ui_print_help_tip(__("If enabled, actively checks if the user comes from %s's URL", get_product_name()), true);
$table->data[29][1] = __('Yes') . '&nbsp;&nbsp;&nbsp;' .
	html_print_radio_button ('referer_security', 1, '', $config["referer_security"], true) .
	'&nbsp;&nbsp;';
$table->data[29][1] .= __('No') . '&nbsp;&nbsp;&nbsp;' .
	html_print_radio_button ('referer_security', 0, '', $config["referer_security"], true);

$table->data[30][0] = __('Event storm protection');
$table->data[30][0] .= ui_print_help_tip(__('If set to yes no events or alerts will be generated, but agents will continue receiving data.'), true);
$table->data[30][1] = __('Yes') . '&nbsp;&nbsp;&nbsp;' .
	html_print_radio_button ('event_storm_protection', 1, '', $config["event_storm_protection"], true) .
	'&nbsp;&nbsp;';
$table->data[30][1] .= __('No') . '&nbsp;&nbsp;&nbsp;' .
	html_print_radio_button ('event_storm_protection', 0, '', $config["event_storm_protection"], true);


$table->data[31][0] = __('Command Snapshot') .
	ui_print_help_tip(__('The string modules with several lines show as command output'), true);
$table->data[31][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('command_snapshot', 1, '', $config["command_snapshot"], true).'&nbsp;&nbsp;';
$table->data[31][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('command_snapshot', 0, '', $config["command_snapshot"], true);

$table->data[32][0] = __('Server logs directory') . ui_print_help_tip (__("Directory where the server logs are stored."), true);
$table->data[32][1] = html_print_input_text ('server_log_dir',
	$config["server_log_dir"], '', 50, 255, true);

$table->data[33][0] = __('Log size limit in system logs viewer extension') . ui_print_help_tip (__("Max size (in bytes) for the logs to be shown."), true);
$table->data[33][1] = html_print_input_text ('max_log_size',
	$config["max_log_size"], '', 10, 255, true) . html_print_label(" x1000", "max_log_size", true);

$modes_tutorial = array(
	'full' => __('Full mode'),
	'on_demand' => __('On demand'),
	'expert' => __('Expert')
	);
$table->data['tutorial_mode'][0] = __('Tutorial mode') .
	ui_print_help_tip (__("Configuration of our clippy, 'full mode' show the icon in the header and the contextual helps and it is noise, 'on demand' it is equal to full but it is not noise and 'expert' the icons in the header and the context is not."), true);
$table->data['tutorial_mode'][1] = 
	html_print_select($modes_tutorial, 'tutorial_mode',
		$config["tutorial_mode"], '', '', 0, true);

$config["past_planned_downtimes"] = isset($config["past_planned_downtimes"]) ? $config["past_planned_downtimes"] : 1;
$table->data[34][0] = __('Allow create planned downtimes in the past') .
	ui_print_help_tip(__('The planned downtimes created in the past will affect the SLA reports'), true);
$table->data[34][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('past_planned_downtimes', 1, '', $config["past_planned_downtimes"], true).'&nbsp;&nbsp;';
$table->data[34][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('past_planned_downtimes', 0, '', $config["past_planned_downtimes"], true);

$table->data[35][0] = __('Limit for bulk operations') .
	ui_print_help_tip(__('Your PHP environment is set to 1000 max_input_vars. This parameter should have the same value or lower.', ini_get("max_input_vars")), true);
$table->data[35][1] = html_print_input_text('limit_parameters_massive',
	$config['limit_parameters_massive'], '', 10, 10, true);

$table->data[36][0] = __('Include agents manually disabled');
$table->data[36][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('include_agents', 1, '', $config["include_agents"], true).'&nbsp;&nbsp;';
$table->data[36][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('include_agents', 0, '', $config["include_agents"], true);

$table->data[37][0] = __('Audit log directory') .
	ui_print_help_tip (__("Directory where audit log is stored."), true);
$table->data[37][1] = html_print_input_text ('auditdir', io_safe_output($config["auditdir"]), '', 30, 100, true);

$table->data[38][0] = __('Set alias as name by default in agent creation');
$table->data[38][1] = __('Yes').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('alias_as_name', 1, '', $config["alias_as_name"], true).'&nbsp;&nbsp;';
$table->data[38][1] .= __('No').'&nbsp;&nbsp;&nbsp;'.html_print_radio_button ('alias_as_name', 0, '', $config["alias_as_name"], true);

echo '<form id="form_setup" method="post" action="index.php?sec=gsetup&sec2=godmode/setup/setup&amp;section=general&amp;pure='.$config['pure'].'">';

echo "<fieldset>";
echo "<legend>" . __('General options') . "</legend>";

	html_print_input_hidden ('update_config', 1);
	html_print_table ($table);

echo "</fieldset>";

echo '<div class="action-buttons" style="width: '.$table->width.'">';
html_print_submit_button (__('Update'), 'update_button', false, 'class="sub upd"');
echo '</div>';
echo '</form>';


?>
<script type="text/javascript">
function toggleButton(type) {
	if ($("#button_sound_" + type).attr('src') == 'images/control_pause_col.png') {
		$("#button_sound_" + type).attr('src', 'images/control_play_col.png');
		$('#layer_sound_' + type).html("");
	}
	else {
		$("#button_sound_" + type).attr('src', 'images/control_pause_col.png');
		$('#layer_sound_' + type).html("<audio src='" + $("#sound_" + type).val() + "' autoplay='true' hidden='true' loop='true'>");
	}
}

function replaySound(type) {
	if ($("#button_sound_" + type).attr('src') == 'images/control_pause_col.png') {
		$('#layer_sound_' + type).html("");
		$('#layer_sound_' + type).html("<audio src='" + $("#sound_" + type).val() + "' autoplay='true' hidden='true' loop='true'>");
	}
}

function show_timezone () {
	zone = $("#zone").val();
	$.ajax({
		type: "POST",
		url: "ajax.php",
		data: "page=<?php echo $_GET['sec2']; ?>&select_timezone=1&zone=" + zone,
		dataType: "json",
		success: function(data) {
			$("#timezone").empty();
			jQuery.each (data, function (id, value) {
				timezone = value;
				$("select[name='timezone']").append($("<option>").val(timezone).html(timezone));
			});
		}
	});
}

$(document).ready (function () {

	$("#zone").attr("hidden", true);
	$("#timezone").attr("hidden", true);

	$("#change_timezone").click(function () {
		$("#zone").attr("hidden", false);
		$("#timezone").attr("hidden", false);
	});
	$("input[name=use_cert]").change(function () {
		if( $(this).is(":checked") ){
            var val = $(this).val();
            if (val == 1) {
				$('#setup_general-13').show();
			}
			else
				$('#setup_general-13').hide();
        }
	});
});
</script>
<?php



function get_sounds() {
	global $config;
	
	$return = array();
	
	$files = scandir($config['homedir'] . '/include/sounds');
	
	foreach ($files as $file) {
		if (strstr($file, 'wav') !== false) {
			$return['include/sounds/' . $file] = $file;
		}
	}
	
	return $return;
}

?>
