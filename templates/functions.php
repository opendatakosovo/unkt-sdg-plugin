<?php
global $wpdb;
$scriptName = split('/', $_SERVER['SCRIPT_NAME']);
array_pop($scriptName);
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . $scriptName[1] . '/wp-config.php');

function get_data($sid)
{
    global $wpdb;
    $query_indicators = array();
    $query_indicators = $wpdb->get_results("
      SELECT wp_sdg.s_text, 
      wp_sdg.long_name,
      wp_sdg.short_name, 
      wp_indicator.name,
      wp_indicator.description,
      wp_indicator.unit, 
      wp_indicator.sid,
      wp_indicator.id, 
      wp_sdg.s_number, 
      wp_measurement.date, 
      wp_measurement.value, 
      wp_measurement.target_value, 
      wp_measurement.notes
      From wp_indicator
      INNER JOIN  wp_sdg 
      ON  wp_indicator.sid=wp_sdg.s_number
      INNER JOIN  wp_measurement 
      ON  wp_indicator.id=wp_measurement.iid
      WHERE wp_indicator.sid = $sid
      ");

    return json_encode($query_indicators, JSON_PRETTY_PRINT);

}

function get_sdg_data($sid)
{
    global $wpdb;
    $query_sdg = array();
    $query_sdg = $wpdb->get_results("
      SELECT *
      FROM wp_sdg
      WHERE s_number = $sid
      ");
    return json_encode($query_sdg, JSON_PRETTY_PRINT);
}

?>