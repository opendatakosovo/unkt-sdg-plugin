<?php

global $wpdb;
require_once($_SERVER['DOCUMENT_ROOT']  . '/wordpress/wp-config.php');
if(isset($_GET['id'])){
    $query_indicators = array();
    $indicator_id=$_GET['id'];
    $query_indicators = $wpdb->get_results(" SELECT wp_sdg.short_name, wp_indicator.name,wp_indicator.description,wp_indicator.unit, wp_indicator.sid,wp_indicator.id,wp_sdg.s_number From wp_indicator INNER JOIN  wp_sdg ON  wp_indicator.sid=wp_sdg.s_number and wp_indicator.id=$indicator_id");

    echo json_encode($query_indicators);

}
?>