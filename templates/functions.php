<?php

function get_data($sid)
{
    global $wpdb;
    $query_targets = $wpdb->get_results("
      SELECT wp_sdg.s_text, 
      wp_sdg.long_name,
      wp_sdg.short_name, 
      wp_targets.name,
      wp_targets.description,
      wp_targets.unit, 
      wp_targets.sid,
      wp_targets.id,
      wp_targets.target_value, 
      wp_targets.target_date,
      TIMESTAMP (wp_targets.updated_date) AS updated_date,
      wp_sdg.s_number, 
      wp_measurement.date, 
      wp_measurement.value, 
      wp_measurement.source_url,
      wp_measurement.notes
      From wp_targets
      INNER JOIN  wp_sdg 
      ON  wp_targets.sid=wp_sdg.s_number
      INNER JOIN  wp_measurement 
      ON  wp_targets.id=wp_measurement.iid
      WHERE wp_targets.sid = $sid
      ORDER BY updated_date DESC
      ");

    return json_encode($query_targets, JSON_PRETTY_PRINT);

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