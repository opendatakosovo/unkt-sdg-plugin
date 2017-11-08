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
            add_action('wp_ajax_add_targets', array('Unkt', 'add_targets'));
            add_action('wp_ajax_update_target', array('Unkt', 'update_target'));
            add_action('wp_ajax_remove_targets_measurements', array('Unkt', 'remove_targets_measurements'));
            add_action('wp_ajax_remove_targets', array('Unkt', 'remove_targets'));
            add_action('wp_ajax_get_measurement_data', array('Unkt', 'get_measurement_data'));
            add_action('wp_ajax_edit_indicator', array('Unkt', 'edit_indicator')); //edit indicator
            add_action('wp_ajax_load_indicator_selected', array('Unkt', 'load_indicator_selected')); //edit indicator
            add_action('wp_ajax_add_indicator', array('Unkt', 'add_indicator')); //add indicator
            add_action('wp_ajax_check_size_of_measurement', array('Unkt', 'check_size_of_measurement'));
            add_action('wp_ajax_remove_measurement', array('Unkt', 'remove_measurement'));
            add_action('wp_ajax_remove_last_measurement_targets', array('Unkt', 'remove_last_measurement_targets'));
            add_action('wp_ajax_get_targets_indicators', array('Unkt', 'get_targets_indicators')); //get indicators
            add_action('wp_ajax_check_targets_is_empty', array('Unkt', 'check_targets_is_empty'));
            add_action('wp_ajax_get_targets', array('Unkt', 'get_targets'));
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
        $sdgJsonData = json_decode(self::get_sdg_goal_data());
        $url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $pattern_title = '/<meta property(.*)=(.*)"og:title"(.*)>/i';
        $html = preg_replace($pattern_title, '<meta property="og:title" content="' . $sdgJsonData[0]->long_name . '" />', $html);

        $pattern_description = '/<meta property(.*)=(.*)"og:description"(.*)>/i';
        $html = preg_replace($pattern_description, '<meta property="og:description" content="' . $sdgJsonData[0]->s_text . '" />', $html);

        $pattern_image = '/<meta property(.*)=(.*)"og:image"(.*)>/i';
        $html = preg_replace($pattern_image, '<meta property="og:image" content="' . SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' . '" />', $html);

        $pattern_url = '/<meta property(.*)=(.*)"og:url"(.*)>/i';
        $html = preg_replace($pattern_url, '<meta property="og:url" content="' . $url . '" />', $html);

        $pattern_meta_twitter_title = '/<meta name(.*)=(.*)"twitter:title"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_title, '<meta property="twitter:text:title" content="' . $sdgJsonData[0]->long_name . '" />', $html);

        $pattern_meta_twitter_description = '/<meta name(.*)=(.*)"twitter:description"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_description, '<meta property="twitter:text:description" content="' . $sdgJsonData[0]->s_text . '" />', $html);

        $pattern_meta_twitter_image = '/<meta name(.*)=(.*)"twitter:image"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_image, '<meta property="twitter:image" content="' . SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' . '" />', $html);

        $pattern_meta_twitter_url = '/<meta name(.*)=(.*)"twitter:url"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_url, '<meta property="twitter:url" content="' . $url . '" />', $html);


        return $html;
    }

    public static function clean_meta_generators($html)
    {
        $template = get_post_meta(get_the_ID(), '_wp_page_template');
        if ($template[0] == 'templates/SDG_Page.php') {
            ob_start(array('UNKT', 'remove_meta_generators'));
        }
    }


    public static function SDGPluginMenu()
    {
        add_menu_page(
            'SDGs & targets',
            'SDGs & targets',
            'manage_options',
            __FILE__,
            array('Unkt', 'RenderPage'),
            SDGS__PLUGIN_URL . 'img/icon.png'
        );
    }

    public static function RenderPage()
    {
        global $wpdb;
        $query_targets = $wpdb->get_results("
          SELECT wp_sdg.short_name, wp_targets.name, wp_targets.description,wp_targets.sid, wp_targets.updated_date, wp_targets.id, wp_sdg.s_number
          From wp_targets
          INNER JOIN wp_sdg
          ON  wp_targets.sid=wp_sdg.s_number");
        // Include the admin HTML page
        require_once(SDGS__PLUGIN_DIR . 'admin/page.php');

    }

    public static function get_goal_data()
    {

        $sid = sprintf("%0d", $_GET['goal']);

        global $wpdb;
        $query_targets = array();
        $query_targets = $wpdb->get_results("
            SELECT wp_sdg.s_text,
            wp_sdg.long_name,
            wp_sdg.short_name,
            wp_targets.name,
            wp_targets.description,
            wp_targets.unit,
            wp_targets.sid,
            wp_targets.id,
            wp_sdg.s_number,
            wp_measurement.date,
            wp_measurement.value,
            wp_measurement.source_url,
            wp_measurement.notes
            FROM wp_targets
            INNER JOIN wp_sdg
            ON wp_targets.sid = wp_sdg.s_number
            INNER JOIN  wp_measurement
            ON  wp_targets.id=wp_measurement.iid
            WHERE wp_targets.sid = $sid
          ");
        return json_encode($query_targets, JSON_PRETTY_PRINT);

    }

    public static function get_sdg_goal_data(){
        $sid = sprintf("%0d", $_GET['goal']);

        global $wpdb;
        $query_targets = $wpdb->get_results("
            SELECT wp_sdg.s_text,
            wp_sdg.long_name,
            wp_sdg.short_name,
            wp_sdg.s_number
            FROM wp_sdg
            WHERE wp_sdg.s_number = $sid
          ");
        return json_encode($query_targets, JSON_PRETTY_PRINT);
    }
    public static function get_sdg_data($sid)
    {
        global $wpdb;
        $query_sdg = array();
        $query_sdg = $wpdb->get_results("
          SELECT *
          FROM wp_sdg
          WHERE s_number = $sid;
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
//        wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
//        wp_enqueue_style('prefix_bootstrap');

        // Datatables CSS
        wp_register_style('prefix_datatables', '//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css');
        wp_enqueue_style('prefix_datatables');
    }

    public static function add_targets() {
        global $wpdb;

        $name = htmlspecialchars($_POST["targets"]);
        $description = htmlspecialchars($_POST['description']);
        $sid = intval(htmlspecialchars($_POST['sdg']));
        $insert = "
        INSERT INTO `{$wpdb->prefix}targets`( sid, name, description, updated_date )
        VALUES('$sid', '$name', '$description', NOW()); ";
        $wpdb->query($insert);
        echo self::get_data();
        die();
    }

    public static function update_target() {

        global $wpdb;
        $id = htmlspecialchars($_POST["targets_id"]);
        $name = htmlspecialchars($_POST["targets"]);
        $description = htmlspecialchars($_POST['description']);
        $sid = intval(htmlspecialchars($_POST['sdg']));

        $update = "
           UPDATE wp_targets
           SET name = '$name', description = '$description', sid = $sid, updated_date = NOW()
           WHERE id='$id'";
        $wpdb->query($update);
        echo self::get_data();
        die();
    }

    public static function get_targets()
    {

        global $wpdb;
        $targets_id = $_POST['id'];
        $query_targets = $wpdb->get_results(" SELECT wp_sdg.short_name, wp_targets.name,wp_targets.description,wp_targets.unit, wp_targets.target_value, wp_targets.target_date, wp_targets.updated_date, wp_targets.sid,wp_targets.id,wp_sdg.s_number From wp_targets INNER JOIN  wp_sdg ON  wp_targets.sid=wp_sdg.s_number and wp_targets.id=$targets_id");
        echo json_encode($query_targets);
        die();
    }

    public static function remove_targets_measurements()
    {

        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}measurement`

            WHERE iid=$id;
        ");
        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}targets`

            WHERE id=$id;
        ");
        echo self::get_data();
        die();
    }

    public static function remove_targets()
    {

        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
        $query_targets = $wpdb->get_results("
        SELECT iid From wp_measurement WHERE iid='$id'");
        $count = sizeof($query_targets);
        if ($count > 0) {
            $arr = array('a' => 1);
            echo json_encode($arr);
        }
        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}targets`

            WHERE id=$id;
        ");
        echo self::get_data();
        die();
    }

    public static function get_measurement_data()
    {

        global $wpdb;

        $targets_id = htmlspecialchars($_POST['targets_id']);
        $query_targets = $wpdb->get_results("
        SELECT * From wp_measurement WHERE iid='$targets_id'");
        echo json_encode($query_targets);
        die();
    }

    public static function edit_indicator()
    {
        global $wpdb;

        $indicator_id= htmlspecialchars($_POST["indicator_id"]);
        $name = htmlspecialchars($_POST["name"]);
        $source = htmlspecialchars($_POST['source']);
        $description = htmlspecialchars($_POST['description']);

        $update = "UPDATE wp_indicators
            SET name='$name', source='$source', description='$description'
            WHERE id='$indicator_id'";
        $wpdb->query($update);
        $query_targets = array();
        $query_targets = $wpdb->get_results("
          SELECT * From wp_indicators WHERE id='$indicator_id'");
        $json = json_encode($query_targets[0]);
        $obj = json_decode($json);
        $target_id = $obj->target_id;
        $query_targets1 = $wpdb->get_results("
          SELECT * From wp_indicators WHERE target_id='$target_id'");
        echo json_encode($query_targets1);
        die(); 

    }

    // Edit Indicator: Get the selected indicator's data
    public static function load_indicator_selected() //load_measurement_selected
    {
        global $wpdb;

        $indicator_id = htmlspecialchars($_POST['id']);
        $query_targets = $wpdb->get_results("
          SELECT * FROM wp_indicators WHERE id='$indicator_id'");
        echo json_encode($query_targets);
        die();
    }
    // Add Indicator: Get the selected indicator's data
    public static function add_indicator()
    {
        global $wpdb;
        $name = htmlspecialchars($_POST['name']);
        $source = htmlspecialchars($_POST['source']);
        $description = htmlspecialchars($_POST['description']);
        $sdg_text = htmlspecialchars($_POST['sdg_id']);
        $target_id = htmlspecialchars($_POST['target_id']);
        if (is_numeric($sdg_text)) {
            $sdg_id = htmlspecialchars($_POST['sdg_id']);
        } else {
            /* find sdg-id by short-name */
            $query_targets = array();
            $query_targets = $wpdb->get_results("
        SELECT short_name,id From wp_sdg WHERE short_name='$sdg_text'");
            $json = json_encode($query_targets[0]);
            $obj = json_decode($json);
            $sdg_id = $obj->id;
        }

        /* found the id */
        $insert = "
        INSERT INTO `{$wpdb->prefix}indicators`(sdg_id,target_id,name,source,description)
        VALUES('$sdg_id','$target_id','$name','$source','$description'); ";
        $wpdb->query($insert);

        // TODO: query_targets
        $query_targets = $wpdb->get_results("
            SELECT * From wp_indicators WHERE target_id='$target_id'");
        echo json_encode($query_targets);
        die();
    }

    public static function check_size_of_measurement()
    {

        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
        $query_targets = array();
        $query_targets = $wpdb->get_results("
          SELECT * From wp_measurement WHERE id='$id'");
        $json = json_encode($query_targets[0]);
        $obj = json_decode($json);
        $iid = $obj->iid;
        $query_targets1 = $wpdb->get_results("
        SELECT * From wp_measurement WHERE iid='$iid'");
        if (sizeof($query_targets1) > 1) {
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
        $query_targets = $wpdb->get_results("
          SELECT * From wp_measurement WHERE id='$id'");
        $json = json_encode($query_targets[0]);
        $obj = json_decode($json);
        $iid = $obj->iid;
        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}measurement`
            WHERE id=$id;
        ");
        $query_targets1 = $wpdb->get_results("
        SELECT * From wp_measurement WHERE iid='$iid'");
        echo json_encode($query_targets1);

    }

    public static function remove_last_measurement_targets()
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
    // GET indicator's data based on selected target id 
    public static function get_targets_indicators()
    {
        global $wpdb;
        $target_id = htmlspecialchars($_GET['id']);
        $query_targets = $wpdb->get_results("
            SELECT * From wp_indicators WHERE target_id='$target_id'");
        echo json_encode($query_targets);
        die();
    }

    public static function check_targets_is_empty()
    {
        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
        $query_targets = $wpdb->get_results("
            SELECT iid
            From wp_measurement
            WHERE iid='$id'");
        $count = sizeof($query_targets);
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
        $query_targets = $wpdb->get_results("
          SELECT wp_sdg.short_name, wp_targets.name,wp_targets.description,wp_targets.unit, wp_targets.target_value,wp_targets.target_date,wp_targets.updated_date, wp_targets.sid,wp_targets.id,wp_sdg.s_number
          From wp_targets
          INNER JOIN  wp_sdg
          ON  wp_targets.sid=wp_sdg.s_number");
        return json_encode($query_targets);

    }


}
