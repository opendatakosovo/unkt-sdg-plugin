<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

global $wpdb;
if($_POST){

    $id = intval(htmlspecialchars($_POST['id']));
    $wpdb->query("
	    DELETE FROM `{$wpdb->prefix}indicator`

	    WHERE id='$id';
	");
}
else{
	echo "Bad request.";
}

?>