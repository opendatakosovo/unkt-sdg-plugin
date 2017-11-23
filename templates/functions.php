<?php

function get_targets($sdg_id) {
    global $wpdb;
    $query_targets = $wpdb->get_results("
      SELECT wp_targets.id AS target_id,
      wp_targets.title AS target_title,
      wp_targets.description AS target_description,
      wp_indicators.id AS indicator_id,
      wp_indicators.title AS indicator_title,
      wp_indicators.description AS indicator_description,
      wp_indicators.source AS indicator_source
      FROM wp_targets
      INNER JOIN wp_indicators
      ON wp_targets.id = wp_indicators.target_id
      WHERE wp_targets.sdg_id = $sdg_id
      ORDER BY wp_targets.id ASC;
      ");
    return json_encode($query_targets, JSON_PRETTY_PRINT);
}


function get_indicators($target_id) {
   global $wpdb;
   $query_indicators = $wpdb->get_results("
      SELECT * FROM wp_indicators WHERE wp_indicators.target_id = $target_id;
   ");
   return json_encode($query_indicators, JSON_PRETTY_PRINT);
}

function get_sdg_data($sid){
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
