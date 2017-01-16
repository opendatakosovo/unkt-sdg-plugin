<?php
global $wpdb;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
if($_POST){
    $name = htmlspecialchars($_POST["name"]);
    $description = htmlspecialchars($_POST['description']);
    $sid = intval(htmlspecialchars($_POST['sid']));
    $insert = "
        INSERT INTO `{$wpdb->prefix}indicator`( sid, name, description, unit )
        VALUES('$sid','$name','$description','t');
    ";
    dbDelta( $insert );

}else{
    $query_indicators = $wpdb->get_results("
        SELECT * FROM `{$wpdb->prefix}indicator`;
      ");
}
?>