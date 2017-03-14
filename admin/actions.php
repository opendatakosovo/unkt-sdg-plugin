<?php
global $wpdb;
$scriptName = split('/', $_SERVER['SCRIPT_NAME']);
array_pop($scriptName);
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . $scriptName[1] . '/wp-config.php');

if ($_POST) {

    if ($_POST['action_indicator'] == 'add_indicator') {
        addIndicator();
        get_data();
    } else if ($_POST['edit_action_indicator'] == 'edit_indicator_form') {
        editIndicator();
        get_data();

    } else if ($_POST['action_indicator'] == 'remove_indicator') {
        removeIndicator();
        get_data();
    } else if ($_POST['action-measurement'] == 'add-measurement') {
        addMeasurement();
        $indicator_id = htmlspecialchars($_POST['indicator_id']);
        $query_indicators = $wpdb->get_results("
            SELECT * From wp_measurement WHERE iid='$indicator_id'");
        echo json_encode($query_indicators);

    } else if ($_POST['action_measurement'] == 'get_indicator_measurement') {
        $indicator_id = htmlspecialchars($_POST['id']);
        $query_indicators = $wpdb->get_results("
            SELECT * From wp_measurement WHERE iid='$indicator_id'");
        echo json_encode($query_indicators);
    } else if ($_POST['action_measurement'] == 'load_measurement') {
        load_measurement_selected();
    } else if ($_POST['action-measurement'] == 'update-measurement') {
        editMeasurement();
    } else if ($_POST['action_indicator'] == "check_indicator_is_empty") {
        IndicatorIsNotEmpty();
    } else if ($_POST['action_indicator'] == "remove_indicator_measurements") {
        removeIndicatorMeasurements();
        get_data();
    } else if ($_POST['action-measurement'] == 'remove-measurement') {
        removeMeasurement();
    } else if ($_POST['action_measurement'] == 'check_measurement_table') {
        checkSizeofMeasurement();
    } else if ($_POST['action_measurement'] == 'remove_last_measurement_indicator') {
        removeLastMeasurement();
        get_data();
    }

} else {
    global $wpdb;
    $query_indicators = $wpdb->get_results("
      SELECT wp_sdg.short_name, wp_indicator.name,wp_indicator.description,wp_indicator.sid,wp_indicator.unit, wp_indicator.id,wp_sdg.s_number 
      From wp_indicator 
      INNER JOIN  wp_sdg 
      ON  wp_indicator.sid=wp_sdg.s_number");

}
function get_data()
{
    global $wpdb;
    $query_indicators = array();
    $query_indicators = $wpdb->get_results("
      SELECT wp_sdg.short_name, wp_indicator.name,wp_indicator.description,wp_indicator.unit, wp_indicator.sid,wp_indicator.id,wp_sdg.s_number 
      From wp_indicator 
      INNER JOIN  wp_sdg 
      ON  wp_indicator.sid=wp_sdg.s_number");
    echo json_encode($query_indicators);
}

function addIndicator()
{
    global $wpdb;

    $name = htmlspecialchars($_POST["indicator"]);
    $description = htmlspecialchars($_POST['description']);
    $sid = intval(htmlspecialchars($_POST['sdg']));
    $unit = htmlspecialchars($_POST['unit']);

    $insert = " 
        INSERT INTO `{$wpdb->prefix}indicator`( sid, name, description, unit )
        VALUES('$sid','$name','$description','$unit'); ";
    $wpdb->query($insert);
}

function editIndicator()
{
    global $wpdb;
    $id = htmlspecialchars($_POST["indicator_id"]);
    $name = htmlspecialchars($_POST["indicator"]);
    $description = htmlspecialchars($_POST['description']);
    $sid = intval(htmlspecialchars($_POST['sdg']));
    $unit = htmlspecialchars($_POST['unit']);

    $update = "
    UPDATE wp_indicator 
    SET name='$name', description='$description', sid=$sid, unit='$unit' 
    WHERE id='$id'";
    $wpdb->query($update);

}

function IndicatorIsNotEmpty()
{
    global $wpdb;

    $id = intval(htmlspecialchars($_POST['id']));
    $query_indicators = $wpdb->get_results("
    SELECT iid 
    From wp_measurement 
    WHERE iid='$id'");
    $count = sizeof($query_indicators);
    if ($count > 0) {
        $arr = array('a' => 1);
        echo json_encode($arr);
    } else {
        $arr = array('a' => 0);
        echo json_encode($arr);
    }

}

function removeIndicatorMeasurements()
{
    global $wpdb;
    $id = intval(htmlspecialchars($_POST['id']));
    $wpdb->query("
	    DELETE FROM `{$wpdb->prefix}measurement`

	    WHERE iid=$id;
	");
    $wpdb->query("
	    DELETE FROM `{$wpdb->prefix}indicator`

	    WHERE id=$id;
	");

}

function removeIndicator()
{
    global $wpdb;

    $id = intval(htmlspecialchars($_POST['id']));
    $query_indicators = $wpdb->get_results("
    SELECT iid From wp_measurement WHERE iid='$id'");
    $count = sizeof($query_indicators);
    if ($count > 0) {
        $arr = array('a' => 1);
        echo json_encode($arr);
    }
    $wpdb->query("
	    DELETE FROM `{$wpdb->prefix}indicator`

	    WHERE id=$id;
	");
}

function get_measurement_data()
{
    global $wpdb;
    $query_indicators = array();
    $indicator_id = htmlspecialchars($_POST['indicator_id']);
    $query_indicators = $wpdb->get_results("
      SELECT * From wp_measurement WHERE iid='$indicator_id'");
    echo json_encode($query_indicators);
}

function editMeasurement()
{
    global $wpdb;
    $meausrement_id = htmlspecialchars($_POST["meausrement_id"]);
    $date = htmlspecialchars($_POST["date-m"]);
    $value_m = htmlspecialchars($_POST['value-m']);
    $target_value_measurement = htmlspecialchars($_POST['target-value-measurement']);
    $notes = htmlspecialchars($_POST['notes']);
    $source_m = htmlspecialchars($_POST['source-m']);

    $update = "UPDATE wp_measurement 
    SET date='$date', value='$value_m', target_value='$target_value_measurement',source_url='$source_m',notes='$notes' 
    WHERE id='$meausrement_id'";
    $wpdb->query($update);
    $query_indicators = array();
    $query_indicators = $wpdb->get_results("
      SELECT * From wp_measurement WHERE id='$meausrement_id'");
    $json = json_encode($query_indicators[0]);
    $obj = json_decode($json);
    $iid = $obj->iid;
    $query_indicators1 = $wpdb->get_results("
      SELECT * From wp_measurement WHERE iid='$iid'");
    echo json_encode($query_indicators1);

}

function load_measurement_selected()
{
    global $wpdb;
    $measurement_id = htmlspecialchars($_POST['id']);
    $query_indicators = $wpdb->get_results("
      SELECT * From wp_measurement WHERE id='$measurement_id'");
    echo json_encode($query_indicators);
}


function addMeasurement()
{
    global $wpdb;
    $indicator_id = htmlspecialchars($_POST['indicator_id']);
    $date_m = htmlspecialchars($_POST['date-m']);
    $value_m = htmlspecialchars($_POST['value-m']);
    $value_taget_m = htmlspecialchars($_POST['target-value-measurement']);
    $notes = htmlspecialchars($_POST['notes']);
    $source_m = htmlspecialchars($_POST['source-m']);
    $sdg_text = htmlspecialchars($_POST['m-sdg']);
    if (is_numeric($sdg_text)) {
        $sdg_id = htmlspecialchars($_POST['m-sdg']);
    } else {
        /* find sdg-id by short-name */
        $query_indicators = array();
        $query_indicators = $wpdb->get_results("
        SELECT short_name,id From wp_sdg WHERE short_name='$sdg_text'");
        $json = json_encode($query_indicators[0]);
        $obj = json_decode($json);
        $sdg_id = $obj->id;
    }

    /* found the id */
    $insert = " 
        INSERT INTO `{$wpdb->prefix}measurement`(sid,iid,date,value,target_value,source_url,notes)
        VALUES('$sdg_id','$indicator_id','$date_m','$value_m','$value_taget_m','$source_m','$notes'); ";
    $wpdb->query($insert);

}

function checkSizeofMeasurement()
{
    global $wpdb;
    $id = intval(htmlspecialchars($_POST['id']));
    $query_indicators = array();
    $query_indicators = $wpdb->get_results("
    SELECT * From wp_measurement WHERE id='$id'");
    $json = json_encode($query_indicators[0]);
    $obj = json_decode($json);
    $iid = $obj->iid;
    $query_indicators1 = $wpdb->get_results("
        SELECT * From wp_measurement WHERE iid='$iid'");
    if (sizeof($query_indicators1) > 1) {
        $arr = array('a' => 1);
        echo json_encode($arr);
    } else {
        $arr = array('a' => 0);
        echo json_encode($arr);
    }

}

function removeMeasurement()
{
    global $wpdb;
    $id = intval(htmlspecialchars($_POST['id']));
    $query_indicators = array();
    $query_indicators = $wpdb->get_results("
    SELECT * From wp_measurement WHERE id='$id'");
    $json = json_encode($query_indicators[0]);
    $obj = json_decode($json);
    $iid = $obj->iid;
    $wpdb->query("
	    DELETE FROM `{$wpdb->prefix}measurement`
	    WHERE id=$id;
	");
    $query_indicators1 = $wpdb->get_results("
        SELECT * From wp_measurement WHERE iid='$iid'");
    echo json_encode($query_indicators1);
}

function removeLastMeasurement()
{
    global $wpdb;
    $id = intval(htmlspecialchars($_POST['id']));
    $wpdb->query("
	    DELETE FROM `{$wpdb->prefix}measurement`
	    WHERE id=$id;
	");
}


?>