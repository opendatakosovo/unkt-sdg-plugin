<?php
$scriptName = split('/', $_SERVER['SCRIPT_NAME']);
array_pop($scriptName);
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . $scriptName[1] . '/wp-config.php');

global $wpdb;
if ($_POST) {

    $id = intval(htmlspecialchars($_POST['id']));
    $wpdb->query("
	    DELETE FROM `{$wpdb->prefix}indicator`

	    WHERE id='$id';
	");
} else {
    echo "Bad request.";
}

?>