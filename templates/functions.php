<?php

function get_targets($sdg_id) {
   global $wpdb;
   $query_targets = $wpdb->get_results("
      SELECT * FROM wp_targets WHERE wp_targets.sdg_id = $sdg_id ORDER BY id ASC
   ");
   return json_encode($query_targets, JSON_PRETTY_PRINT);
}

// function get_data($sdg_id) {
//     global $wpdb;
//     $query_targets = $wpdb->get_results("
//       SELECT wp_sdg.s_text,
//       wp_sdg.long_name,
//       wp_sdg.short_name,
//       wp_targets.title,
//       wp_targets.description,
//       wp_targets.sdg_id,
//       wp_targets.id,
//       TIMESTAMP (wp_targets.updated_date) AS updated_date,
//       wp_sdg.s_number,
//       wp_indicators.title,
//       wp_indicators.source,
//       wp_indicators.description,
//       FROM wp_targets
//       INNER JOIN wp_sdg
//       ON  wp_targets.sdg_id=wp_sdg.s_number
//       INNER JOIN  wp_indicators
//       ON  wp_targets.id=wp_indicators.target_id
//       WHERE wp_targets.sdg_id = $sdg_id
//       ORDER BY updated_date ASC
//       ");
//     return json_encode($query_targets, JSON_PRETTY_PRINT);
// }

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
