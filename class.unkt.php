<?php

/**
 * Created by PhpStorm.
 * User: partin
 * Date: 3/15/17
 * Time: 2:24 PM
 */
class Unkt
{
    private $my_plugin_screen_name;
    private static $instance;
    private static $initiated = false;

    public static function init()
    {
        if (!self::$initiated) {
            self::init_hooks();
        }
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks()
    {
        self::$initiated = true;



        if (is_admin()) {
            add_action('wp_ajax_add_indicator', array('Unkt', 'add_indicator'));
            add_action('wp_ajax_edit_indicator', array('Unkt', 'edit_indicator'));
            add_action('wp_ajax_remove_indicator_measurements', array('Unkt', 'remove_indicator_measurements'));
            add_action('wp_ajax_remove_indicator', array('Unkt', 'remove_indicator'));
            add_action('wp_ajax_get_measurement_data', array('Unkt', 'get_measurement_data'));
            add_action('wp_ajax_edit_measurement', array('Unkt', 'edit_measurement'));
            add_action('wp_ajax_load_measurement_selected', array('Unkt', 'load_measurement_selected'));
            add_action('wp_ajax_add_measurement', array('Unkt', 'add_measurement'));
            add_action('wp_ajax_check_size_of_measurement', array('Unkt', 'check_size_of_measurement'));
            add_action('wp_ajax_remove_measurement', array('Unkt', 'remove_measurement'));
            add_action('wp_ajax_remove_last_measurement_indicator', array('Unkt', 'remove_last_measurement_indicator'));
            add_action('wp_ajax_get_indicator_measurement', array('Unkt', 'get_indicator_measurement'));
            add_action('wp_ajax_check_indicator_is_empty', array('Unkt', 'check_indicator_is_empty'));
            add_action('wp_ajax_get_indicator', array('Unkt', 'get_indicator'));
        }

        add_action('get_header', array('Unkt', 'clean_meta_generators'), 100);
        add_action('admin_menu', array('Unkt', 'SDGPluginMenu'));
        add_action('wp_head', array('Unkt', 'prefix_enqueue_tools'));

        // REMOVE WP EMOJI
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');

        add_action('wp_enqueue_scripts', array('Unkt', 'prefix_enqueue_tools'));

    }

    //Remove All Meta Generators
    public static function remove_meta_generators($html)
    {
        $sdgJsonData = json_decode(self::get_goal_data());
        $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $pattern_title = '/<meta property(.*)=(.*)"og:title"(.*)>/i';
        $html = preg_replace($pattern_title, '<meta property="og:title" content="' . $sdgJsonData[0]->long_name . '" />', $html);

        $pattern_description = '/<meta property(.*)=(.*)"og:description"(.*)>/i';
        $html = preg_replace($pattern_description, '<meta property="og:description" content="' . $sdgJsonData[0]->s_text . '" />', $html);

        $pattern_image = '/<meta property(.*)=(.*)"og:image"(.*)>/i';
        $html = preg_replace($pattern_image, '<meta property="og:image" content="' . SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' . '" />', $html);

        $pattern_url = '/<meta property(.*)=(.*)"og:url"(.*)>/i';
        $html = preg_replace($pattern_url, '<meta property="og:url" content="' . $url . '" />', $html);

        $pattern_meta_twitter_title = '/<meta name(.*)=(.*)"twitter:title"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_title, '<meta property="twitter:title" content="' . $sdgJsonData[0]->long_name . '" />', $html);

        $pattern_meta_twitter_description = '/<meta name(.*)=(.*)"twitter:description"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_description, '<meta property="twitter:description" content="' . $sdgJsonData[0]->s_text . '" />', $html);

        $pattern_meta_twitter_image = '/<meta name(.*)=(.*)"twitter:image"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_image, '<meta property="twitter:image" content="' . SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' . '" />', $html);

        $pattern_meta_twitter_url = '/<meta name(.*)=(.*)"twitter:url"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_url, '<meta property="twitter:url" content="' . $url . '" />', $html);


        return $html;
    }

    public static function clean_meta_generators($html)
    {
        ob_start(array('UNKT', 'remove_meta_generators'));
    }


    public static function SDGPluginMenu()
    {
        add_menu_page(
            'SDGs & Indicators',
            'SDGs & Indicators',
            'manage_options',
            __FILE__,
            array('Unkt', 'RenderPage'),
            SDGS__PLUGIN_URL . 'img/icon.png'
        );
    }

    public static function RenderPage()
    {
        global $wpdb;
        $query_indicators = $wpdb->get_results("
          SELECT wp_sdg.short_name, wp_indicator.name,wp_indicator.description,wp_indicator.sid,wp_indicator.unit, wp_indicator.id,wp_sdg.s_number 
          From wp_indicator 
          INNER JOIN  wp_sdg 
          ON  wp_indicator.sid=wp_sdg.s_number");
        // Include the admin HTML page
        require_once(SDGS__PLUGIN_DIR . 'admin/page.php');

    }

    public static function get_goal_data()
    {
        $sid = sprintf("%0d", $_GET['goal']);
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

    public static function get_sdg_data($sid)
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

    public static function prefix_enqueue_tools()
    {
        // jQuery
        wp_register_script('prefix_jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
        wp_enqueue_script('prefix_jquery');

        // JS
        wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
        wp_enqueue_script('prefix_bootstrap');


        // CSS
        wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
        wp_enqueue_style('prefix_bootstrap');

        // Datatables CSS
        wp_register_style('prefix_datatables', '//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css');
        wp_enqueue_style('prefix_datatables');
    }

    public static function add_indicator()
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
        echo self::get_data();
        die();
    }

    public static function edit_indicator()
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
        echo self::get_data();
        die();
    }

    public static function get_indicator()
    {

        global $wpdb;
        $indicator_id = $_POST['id'];
        $query_indicators = $wpdb->get_results(" SELECT wp_sdg.short_name, wp_indicator.name,wp_indicator.description,wp_indicator.unit, wp_indicator.sid,wp_indicator.id,wp_sdg.s_number From wp_indicator INNER JOIN  wp_sdg ON  wp_indicator.sid=wp_sdg.s_number and wp_indicator.id=$indicator_id");
        echo json_encode($query_indicators);
        die();
    }

    public static function remove_indicator_measurements()
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
        echo self::get_data();
        die();
    }

    public static function remove_indicator()
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
        echo self::get_data();
        die();
    }

    public static function get_measurement_data()
    {

        global $wpdb;

        $indicator_id = htmlspecialchars($_POST['indicator_id']);
        $query_indicators = $wpdb->get_results("
        SELECT * From wp_measurement WHERE iid='$indicator_id'");
        echo json_encode($query_indicators);
        die();
    }

    public static function edit_measurement()
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
        die();

    }

    public static function load_measurement_selected()
    {
        global $wpdb;

        $measurement_id = htmlspecialchars($_POST['id']);
        $query_indicators = $wpdb->get_results("
          SELECT * From wp_measurement WHERE id='$measurement_id'");
        echo json_encode($query_indicators);
        die();
    }

    public static function add_measurement()
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

        $query_indicators = $wpdb->get_results("
            SELECT * From wp_measurement WHERE iid='$indicator_id'");
        echo json_encode($query_indicators);
        die();
    }

    public static function check_size_of_measurement()
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
        die();
    }

    public static function remove_measurement()
    {
        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
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

    public static function remove_last_measurement_indicator()
    {
        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}measurement`
            WHERE id=$id;
        ");
        echo self::get_data();
        die();
    }

    public static function get_indicator_measurement()
    {
        global $wpdb;
        $indicator_id = htmlspecialchars($_POST['id']);
        $query_indicators = $wpdb->get_results("
            SELECT * From wp_measurement WHERE iid='$indicator_id'");
        echo json_encode($query_indicators);
        die();
    }

    public static function check_indicator_is_empty()
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
        die();
    }

    public static function get_data()
    {
        global $wpdb;
        $query_indicators = $wpdb->get_results("
          SELECT wp_sdg.short_name, wp_indicator.name,wp_indicator.description,wp_indicator.unit, wp_indicator.sid,wp_indicator.id,wp_sdg.s_number 
          From wp_indicator 
          INNER JOIN  wp_sdg 
          ON  wp_indicator.sid=wp_sdg.s_number");
        return json_encode($query_indicators);

    }


}