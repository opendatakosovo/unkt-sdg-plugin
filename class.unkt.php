<?php
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

            add_action('wp_ajax_add_targets', array('Unkt', 'add_targets'));
            add_action('wp_ajax_update_target', array('Unkt', 'update_target'));
            add_action('wp_ajax_remove_targets_measurements', array('Unkt', 'remove_targets_measurements'));
            add_action('wp_ajax_remove_targets', array('Unkt', 'remove_targets'));
            add_action('wp_ajax_edit_indicator', array('Unkt', 'edit_indicator')); //edit indicator
            add_action('wp_ajax_load_indicator_selected', array('Unkt', 'load_indicator_selected')); //edit indicator
            add_action('wp_ajax_add_indicator', array('Unkt', 'add_indicator')); //add indicator
            add_action('check_size_of_indicator', array('Unkt', 'check_size_of_indicator'));
            add_action('wp_ajax_remove_indicator', array('Unkt', 'remove_indicator'));
            add_action('wp_ajax_remove_last_indicator_targets', array('Unkt', 'remove_last_indicator_targets'));
            add_action('wp_ajax_get_targets_indicators', array('Unkt', 'get_targets_indicators')); //get indicators
            add_action('wp_ajax_get_targets', array('Unkt', 'get_targets'));

            add_action('wp_ajax_get_target_indicator_charts', array('Unkt','get_target_indicator_charts'));
            add_action('wp_ajax_nopriv_get_target_indicator_charts', array('Unkt','get_target_indicator_charts'));

            add_action('wp_ajax_add_chart', array('Unkt', 'add_chart'));
            add_action('wp_ajax_remove_chart', array('Unkt', 'remove_chart'));
            add_action('wp_ajax_load_chart_selected', array('Unkt', 'load_chart_selected'));
            add_action('wp_ajax_update_chart', array('Unkt', 'update_chart'));
            add_filter( 'admin_footer_text', '__return_false' );

            function change_footer_version() {
             return '';
           }
           add_filter( 'update_footer', 'change_footer_version', 9999 );

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
          SELECT wp_sdg.short_name, wp_targets.title, wp_targets.description, wp_targets.sdg_id, wp_targets.updated_date, wp_targets.id, wp_sdg.s_number
          From wp_targets
          INNER JOIN wp_sdg
          ON  wp_targets.sdg_id=wp_sdg.s_number
          ORDER BY wp_sdg.id ");
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

        $title = htmlspecialchars($_POST["title"]);
        $description = htmlspecialchars($_POST['description']);
        $sdg_id = intval(htmlspecialchars($_POST['sdg_id']));
        $insert = "
        INSERT INTO `{$wpdb->prefix}targets`( sdg_id, title, description, updated_date )
        VALUES('$sdg_id', '$title', '$description', NOW()); ";
        $wpdb->query($insert);
        echo self::get_data();
        die();
    }

    public static function update_target() {

        global $wpdb;
        $id = htmlspecialchars($_POST["target_id"]);
        $title = htmlspecialchars($_POST["title"]);
        $description = htmlspecialchars($_POST['description']);
        $sdg_id = intval(htmlspecialchars($_POST['sdg_id']));

        $update = "
           UPDATE wp_targets
           SET title = '$title', description = '$description', sdg_id = $sdg_id, updated_date = NOW()
           WHERE id='$id'";
        $wpdb->query($update);
        echo self::get_data();
        die();
    }

    public static function get_targets()
    {
        global $wpdb;
        $targets_id = $_POST['id'];
        $query_targets = $wpdb->get_results(" SELECT wp_sdg.short_name,wp_targets.id,wp_targets.title ,wp_targets.description,wp_targets.updated_date,wp_targets.sdg_id,wp_sdg.s_number FROM wp_targets INNER JOIN wp_sdg ON wp_targets.sdg_id = wp_sdg.s_number AND wp_targets.id = $targets_id");
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

    public static function edit_indicator() {
        global $wpdb;
        $indicator_id = htmlspecialchars($_POST['indicator_id']);
        $title = htmlspecialchars($_POST['title']);
        $source = htmlspecialchars($_POST['source']);
        $description = htmlspecialchars($_POST['description']);

        $update = "UPDATE wp_indicators
            SET title='$title', source='$source', description='$description'
            WHERE id='$indicator_id'";
        $wpdb->query($update);
        $query_targets = array();
        $query_targets = $wpdb->get_results("
          SELECT * FROM wp_indicators WHERE id='$indicator_id'");
        $json = json_encode($query_targets[0]);
        $obj = json_decode($json);
        $target_id = $obj->target_id;
        $query_targets1 = $wpdb->get_results("
          SELECT * FROM wp_indicators WHERE target_id='$target_id'");
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
    public static function add_indicator() {
        global $wpdb;
        $title = htmlspecialchars($_POST['title']);
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
        INSERT INTO `{$wpdb->prefix}indicators`(sdg_id, target_id, title, description, source)
        VALUES('$sdg_id','$target_id','$title','$description','$source'); ";
        $wpdb->query($insert);

        // todo: query_targets
        $query_targets = $wpdb->get_results("
            SELECT * From wp_indicators WHERE target_id='$target_id'");
        echo json_encode($query_targets);
        die();
    }

    // public static function check_size_of_indicator() {
    //     global $wpdb;
    //     $id = intval(htmlspecialchars($_POST['id']));
    //     $query_targets = array();
    //     $query_targets = $wpdb->get_results("
    //       SELECT * From wp_indicators WHERE id='$id'");
    //     $json = json_encode($query_targets[0]);
    //     $obj = json_decode($json);
    //     $target_id = $obj->target_id;
    //     $query_targets1 = $wpdb->get_results("
    //     SELECT * From wp_indicators WHERE target_id='$target_id'");
    //     if (sizeof($query_targets1) > 1) {
    //         $arr = array('a' => 1);
    //         echo json_encode($arr);
    //     } else {
    //         $arr = array('a' => 0);
    //         echo json_encode($arr);
    //     }
    //     die();
    // }

    public static function remove_indicator() {
        global $wpdb;
        $id = intval(htmlspecialchars($_POST['id']));
        $query_targets = $wpdb->get_results("
          SELECT * From wp_indicators WHERE id='$id'");

        $json = json_encode($query_targets[0]);
        $obj = json_decode($json);
        $target_id = $obj->target_id;

        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}indicators`
            WHERE id=$id;
        ");

        $query_targets1 = $wpdb->get_results("
        SELECT * FROM wp_indicators WHERE target_id='$target_id'");
        echo json_encode($query_targets1);
    }

    public static function remove_last_indicator_targets() {
        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}indicators`
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
    public static function add_chart() {
        global $wpdb;

        $sdg_text = htmlspecialchars($_POST["sdg_id"]);
        $target_id = htmlspecialchars($_POST["target_id"]);
        $indicator_id = htmlspecialchars($_POST["indicator_id"]);
        $title = htmlspecialchars($_POST["title"]);
        $target_year = htmlspecialchars($_POST["target_year"]);
        $target_unit = htmlspecialchars($_POST["target_unit"]);
        $target_value = htmlspecialchars($_POST["target_value"]);
        $chart_unit = htmlspecialchars($_POST["chart_unit"]);
        $chart_data = htmlspecialchars($_POST["chart_data"]);
        $label = htmlspecialchars($_POST["label"]);
        $description = htmlspecialchars($_POST["description"]);

        if (is_numeric($sdg_text)) {
            $sdg_id = $sdg_text;
        } else {
            /* find sdg-id by short-name */
            $query_targets = array();
            $query_targets = $wpdb->get_results("
               SELECT short_name,id From wp_sdg WHERE short_name='$sdg_text'");
            $json = json_encode($query_targets[0]);
            $obj = json_decode($json);
            $sdg_id = $obj->id;
        }

        $insert = "
        INSERT INTO `{$wpdb->prefix}charts`( sdg_id, target_id, indicator_id, title, target_year, target_unit, target_value, chart_unit, chart_data, description,label, updated_date )
        VALUES('$sdg_id','$target_id','$indicator_id', '$title', '$target_year', '$target_unit', '$target_value', '$chart_unit' ,'$chart_data', '$description','$label', NOW()); ";
        $wpdb->query($insert);

        $query_charts = $wpdb->get_results("
              SELECT * From wp_charts WHERE indicator_id='$indicator_id' AND target_id='$target_id'");
        echo json_encode($query_charts);
        die();
    }
    public static function update_chart() {

        global $wpdb;
        $chart_id = htmlspecialchars($_POST["chart_id"]);
        $sdg_id = htmlspecialchars($_POST["sdg_id"]);
        $target_id = htmlspecialchars($_POST["target_id"]);
        $indicator_id = htmlspecialchars($_POST["indicator_id"]);
        $title = htmlspecialchars($_POST["title"]);
        $target_year = htmlspecialchars($_POST["target_year"]);
        $target_unit = htmlspecialchars($_POST["target_unit"]);
        $target_value = htmlspecialchars($_POST["target_value"]);
        $chart_unit = htmlspecialchars($_POST["chart_unit"]);
        $chart_data = htmlspecialchars($_POST["chart_data"]);
        $label = htmlspecialchars($_POST["label"]);
        $description = htmlspecialchars($_POST["description"]);

        $update = "
           UPDATE wp_charts
           SET sdg_id='$sdg_id',target_id='$target_id',indicator_id='$indicator_id', title='$title', target_year='$target_year', target_unit='$target_unit', target_value='$target_value', chart_unit='$chart_unit' ,chart_data='$chart_data', description='$description',label='$label',updated_date=NOW()
           WHERE id='$chart_id'";
        $wpdb->query($update);
        $query_charts = $wpdb->get_results("
              SELECT * From wp_charts WHERE indicator_id='$indicator_id' AND target_id='$target_id'");
        echo json_encode($query_charts);
        die();
    }
    // Edit Chart: Get the selected chart's data
    public static function load_chart_selected()
    {
        global $wpdb;
        $chart_id = htmlspecialchars($_POST['id']);
        $query_chart = $wpdb->get_results("
          SELECT * FROM wp_charts WHERE id='$chart_id'");
        echo json_encode($query_chart);
        die();
    }
    public static function remove_chart() {
        global $wpdb;
        $id = intval(htmlspecialchars($_POST['id']));
        $sdg_text = htmlspecialchars($_POST["sdg_id"]);
        $target_id = intval(htmlspecialchars($_POST["target_id"]));
        $indicator_id = intval(htmlspecialchars($_POST["indicator_id"]));

        if (is_numeric($sdg_text)) {
            $sdg_id = $sdg_text;
        } else {
            /* find sdg-id by short-name */
            $query_sdg_id = array();
            $query_sdg_id = $wpdb->get_results("
               SELECT id From wp_sdg WHERE short_name='$sdg_text'");
            $json = json_encode($query_sdg_id[0]);
            $obj = json_decode($json);
            $sdg_id = $obj->id;
        }

        $wpdb->query("
            DELETE FROM `{$wpdb->prefix}charts`
            WHERE id=$id;
        ");

        $query_target_indicator_charts = $wpdb->get_results("
            SELECT * From wp_charts WHERE indicator_id='$indicator_id' AND target_id='$target_id' AND sdg_id='$sdg_id'");
        echo json_encode($query_target_indicator_charts);
        die();
    }

    public function get_target_indicator_charts()
    {
      global $wpdb;
      $indicator_id = htmlspecialchars($_GET['id']);
      $target_id = htmlspecialchars($_GET['target_id']);
      $query_target_indicator_charts = $wpdb->get_results("
          SELECT * From wp_charts WHERE indicator_id='$indicator_id' AND target_id='$target_id'");
      echo json_encode($query_target_indicator_charts);
      die();
    }

    public static function get_data()
    {
        global $wpdb;
        $query_targets = $wpdb->get_results("
          SELECT wp_sdg.short_name, wp_targets.title, wp_targets.description, wp_targets.updated_date, wp_targets.sdg_id,wp_targets.id,wp_sdg.s_number
          From wp_targets
          INNER JOIN  wp_sdg
          ON  wp_targets.sdg_id=wp_sdg.s_number
          ORDER BY wp_sdg.id");
        return json_encode($query_targets);

    }


}
