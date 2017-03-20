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
        if ( is_admin() ) {
            add_action('wp_ajax_add_indicator', array('Unkt','add_indicator'));
            add_action('wp_ajax_edit_indicator', array('Unkt','edit_indicator'));
            add_action('wp_ajax_remove_indicator_measurements', array('Unkt','remove_indicator_measurements'));
            add_action('wp_ajax_remove_indicator', array('Unkt','remove_indicator'));
            add_action('wp_ajax_get_measurement_data', array('Unkt','get_measurement_data'));
            add_action('wp_ajax_edit_measurement', array('Unkt','edit_measurement'));
            add_action('wp_ajax_load_measurement_selected', array('Unkt','load_measurement_selected'));
            add_action('wp_ajax_add_measurement', array('Unkt','add_measurement'));
            add_action('wp_ajax_check_size_of_measurement', array('Unkt','check_size_of_measurement'));
            add_action('wp_ajax_remove_measurement', array('Unkt','remove_measurement'));
            add_action('wp_ajax_remove_last_measurement_indicator', array('Unkt','remove_last_measurement_indicator'));
            add_action('wp_ajax_get_indicator_measurement', array('Unkt','get_indicator_measurement'));
            add_action('wp_ajax_check_indicator_is_empty', array('Unkt','check_indicator_is_empty'));
            add_action('wp_ajax_get_indicator', array('Unkt','get_indicator'));
        }

        add_action('get_header', array('Unkt','clean_meta_generators'), 100);
        add_action('admin_menu', array('Unkt', 'SDGPluginMenu'));
        add_action('wp_head', array('Unkt','prefix_enqueue_tools'));

        // REMOVE WP EMOJI
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );

        add_action('wp_enqueue_scripts', array('Unkt','prefix_enqueue_tools'));
        register_activation_hook(__FILE__, array('Unkt','on_activate'));
    }

    //Remove All Meta Generators
    public static function remove_meta_generators($html) {
        $sdgJsonData = json_decode(self::get_goal_data());
        $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $pattern_title = '/<meta property(.*)=(.*)"og:title"(.*)>/i';
        $html = preg_replace($pattern_title, '<meta property="og:title" content="'.  $sdgJsonData[0]->long_name.'" />', $html);

        $pattern_description = '/<meta property(.*)=(.*)"og:description"(.*)>/i';
        $html = preg_replace($pattern_description, '<meta property="og:description" content="'.  $sdgJsonData[0]->s_text.'" />', $html);

        $pattern_image = '/<meta property(.*)=(.*)"og:image"(.*)>/i';
        $html = preg_replace($pattern_image, '<meta property="og:image" content="'.  SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg'.'" />', $html);

        $pattern_url = '/<meta property(.*)=(.*)"og:url"(.*)>/i';
        $html = preg_replace($pattern_url, '<meta property="og:url" content="'.$url.'" />', $html);

        $pattern_meta_twitter_title = '/<meta name(.*)=(.*)"twitter:title"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_title, '<meta property="twitter:title" content="'.  $sdgJsonData[0]->long_name.'" />', $html);

        $pattern_meta_twitter_description = '/<meta name(.*)=(.*)"twitter:description"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_description, '<meta property="twitter:description" content="'.  $sdgJsonData[0]->s_text.'" />', $html);

        $pattern_meta_twitter_image = '/<meta name(.*)=(.*)"twitter:image"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_image, '<meta property="twitter:image" content="'.  SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg'.'" />', $html);

        $pattern_meta_twitter_url = '/<meta name(.*)=(.*)"twitter:url"(.*)>/i';
        $html = preg_replace($pattern_meta_twitter_url, '<meta property="twitter:url" content="'.$url.'" />', $html);


        return $html;
    }

    public static function clean_meta_generators($html) {
        ob_start(array('UNKT','remove_meta_generators'));
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
        $indicator_id=$_POST['id'];
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
    public static function edit_measurement(){
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
    public static function load_measurement_selected(){
        global $wpdb;

        $measurement_id = htmlspecialchars($_POST['id']);
        $query_indicators = $wpdb->get_results("
          SELECT * From wp_measurement WHERE id='$measurement_id'");
        echo json_encode($query_indicators);
        die();
    }
    public static function add_measurement(){
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
    public static function check_size_of_measurement(){

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
    public static function get_indicator_measurement(){
        global $wpdb;
        $indicator_id=htmlspecialchars($_POST['id']);
        $query_indicators = $wpdb->get_results("
            SELECT * From wp_measurement WHERE iid='$indicator_id'");
        echo json_encode($query_indicators);
        die();
    }
    public static function check_indicator_is_empty(){
        global $wpdb;

        $id = intval(htmlspecialchars($_POST['id']));
        $query_indicators = $wpdb->get_results("
            SELECT iid 
            From wp_measurement 
            WHERE iid='$id'");
        $count=sizeof($query_indicators);
        if ($count>0){
            $arr = array ('a'=>1);
            echo json_encode($arr);
        }else{
            $arr = array ('a'=>0);
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
    public static function on_activate()
    {
        global $wpdb;
        // Register On activation actions.
        // Everything inside the on_activate function is executed
        // once, when the plugin is activated.
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $create_sdg_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sdg` (
              `id` bigint(20)  NOT NULL  AUTO_INCREMENT,
              `s_number` bigint(20) NOT NULL,
              `short_name` text NOT NULL,
              `long_name` text NOT NULL,
              `s_text` text NOT NULL,
              UNIQUE KEY (s_number),
              PRIMARY KEY  (id)
            ) ENGINE=INNODB  DEFAULT CHARSET=utf8;
        ";
        dbDelta($create_sdg_table_query);

        $create_indicators_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}indicator` (
              `id` bigint(20)  NOT NULL AUTO_INCREMENT,
              `sid` bigint(20)  NOT NULL,
              `name` text NOT NULL,
              `description` text NOT NULL,
              `unit` text NOT NULL,
              PRIMARY KEY  (id),
              FOREIGN KEY (sid) REFERENCES {$wpdb->prefix}sdg(s_number)
            ) ENGINE=INNODB  DEFAULT CHARSET=utf8;
        ";
        dbDelta($create_indicators_table_query);

        $create_measurement_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}measurement` (
              `id` bigint(20)  NOT NULL AUTO_INCREMENT,
              `sid` bigint(20) NOT NULL,
              `iid` bigint(20) NOT NULL,
              `date` text NOT NULL,
              `value` text NOT NULL,
              `target_value` text NOT NULL,
              `source_url` text NOT NULL,
              `notes` text NOT NULL,
              PRIMARY KEY  (id),
              FOREIGN KEY (sid) REFERENCES {$wpdb->prefix}sdg(s_number),
              FOREIGN KEY (iid) REFERENCES {$wpdb->prefix}indicator(id)
            ) ENGINE=INNODB  DEFAULT CHARSET=utf8;
        ";
        dbDelta($create_measurement_table_query);

        // Insert dummy data.
        $insert_sdgs = "
            INSERT INTO `{$wpdb->prefix}sdg`( s_number, short_name, long_name, s_text )
            VALUES(1,'poverty','End poverty in all its forms everywhere','Extreme poverty rates have been cut by more than half since 1990. While this is a remarkable achievement, one in five people in developing regions still live on less than $1.25 a day, and there are millions more who make little more than this daily amount, plus many people risk slipping back into poverty.Poverty is more than the lack of income and resources to ensure a sustainable livelihood. Its manifestations include hunger and malnutrition, limited access to education and other basic services, social discrimination and exclusion as well as the lack of participation in decision-making. Economic growth must be inclusive to provide sustainable jobs and promote equality.'),
            (2,'zero-hunger','End hunger, achieve food security and improved nutrition and promote sustainable agriculture','It is time to rethink how we grow, share and consume our food.If done right, agriculture, forestry and fisheries can provide nutritious food for all and generate decent incomes, while supporting people-centred rural development and protecting the environment.Right now, our soils, freshwater, oceans, forests and biodiversity are being rapidly degraded. Climate change is putting even more pressure on the resources we depend on, increasing risks associated with disasters such as droughts and floods. Many rural women and men can no longer make ends meet on their land, forcing them to migrate to cities in search of opportunities.A profound change of the global food and agriculture system is needed if we are to nourish today’s 795 million hungry and the additional 2 billion people expected by 2050.The food and agriculture sector offers key solutions for development, and is central for hunger and poverty eradication.'),
            (3,'good-health-and-well-being','Ensure healthy lives and promote well-being for all at all ages','Ensuring healthy lives and promoting the well-being for all at all ages is essential to sustainable development. Significant strides have been made in increasing life expectancy and reducing some of the common killers associated with child and maternal mortality. Major progress has been made on increasing access to clean water and sanitation, reducing malaria, tuberculosis, polio and the spread of HIV/AIDS. However, many more efforts are needed to fully eradicate a wide range of diseases and address many different persistent and emerging health issues.'),
            (4,'quality-education','Ensure inclusive and quality education for all and promote lifelong learning','Obtaining a quality education is the foundation to improving people’s lives and sustainable development. Major progress has been made towards increasing access to education at all levels and increasing enrolment rates in schools particularly for women and girls. Basic literacy skills have improved tremendously, yet bolder efforts are needed to make even greater strides for achieving universal education goals. For example, the world has achieved equality in primary education between girls and boys, but few countries have achieved that target at all levels of education.'),
            (5,'gender-equality','Achieve gender equality and empower all women and girls','While the world has achieved progress towards gender equality  and women’s empowerment under the Millennium Development Goals (including equal access to primary education between girls and boys), women and girls continue to suffer discrimination and violence in every part of the world.Gender equality is not only a fundamental human right, but a necessary foundation for a peaceful, prosperous and sustainable world.Providing women and girls with equal access to education, health care, decent work, and representation in political and economic decision-making processes will fuel sustainable economies and benefit societies and humanity at large.'),
            (6,'clean-water-and-sanitation','Ensure access to water and sanitation for all','Clean, accessible water for all is an essential part of the world we want to live in. There is sufficient fresh water on the planet to achieve this. But due to bad economics or poor infrastructure, every year millions of people, most of them children, die from diseases associated with inadequate water supply, sanitation and hygiene. Water scarcity, poor water quality and inadequate sanitation negatively impact food security, livelihood choices and educational opportunities for poor families across the world. Drought afflicts some of the world’s poorest countries, worsening hunger and malnutrition.\nBy 2050, at least one in four people is likely to live in a country affected by chronic or recurring shortages of fresh water.'),
            (7,'affordable-and-clean-energy','Ensure access to affordable, reliable, sustainable and modern energy for all','Energy is central to nearly every major challenge and opportunity the world faces today. Be it for jobs, security, climate change, food production or increasing incomes, access to energy for all is essential.\nSustainable energy is opportunity – it transforms lives, economies and the planet.\nUN Secretary-General Ban Ki-moon is leading a Sustainable Energy for All initiative to ensure universal access to modern energy services, improve efficiency and increase use of renewable sources.'),
            (8,'decent-work-and-economic-growth','Promote inclusive and sustainable economic growth, employment and decent work for all','Roughly half the world’s population still lives on the equivalent of about US$2 a day. And in too many places, having a job doesn’t guarantee the ability to escape from poverty. This slow and uneven progress requires us to rethink and retool our economic and social policies aimed at eradicating poverty.\nA continued lack of decent work opportunities, insufficient investments and under-consumption lead to an erosion of the basic social contract underlying democratic societies: that all must share in progress. . The creation of quality jobs will remain a major challenge for almost all economies well beyond 2015.\nSustainable economic growth will require societies to create the conditions that allow people to have quality jobs that stimulate the economy while not harming the environment. Job opportunities and decent working conditions are also required for the whole working age population.'),
            (9,'industry-innovation-and-infrastructure','Build resilient infrastructure, promote sustainable industrialization and foster innovation','Investments in infrastructure – transport, irrigation, energy and information and communication technology – are crucial to achieving sustainable development and empowering communities in many countries. It has long been recognized that growth in productivity and incomes, and improvements in health and education outcomes require investment in infrastructure.\nInclusive and sustainable industrial development is the primary source of income generation, allows for rapid and sustained increases in living standards for all people, and provides the technological solutions to environmentally sound industrialization.\nTechnological progress is the foundation of efforts to achieve environmental objectives, such as increased resource and energy-efficiency. Without technology and innovation, industrialization will not happen, and without industrialization, development will not happen.'),
            (10,'reduced-inequalities','Reduce inequality within and among countries','The international community has made significant strides towards lifting people out of poverty.  The most vulnerable nations – the least developed countries, the landlocked developing countries and the small island developing states – continue to make inroads into poverty reduction.  However, inequality still persists and large disparities remain in access to health and education services and other assets.\nAdditionally, while income inequality between countries may have been reduced, inequality within countries has risen. There is growing consensus that economic growth is not sufﬁcient to reduce poverty if it is not inclusive and if it does not involve the three dimensions of sustainable development – economic, social and environmental.\nTo reduce inequality, policies should be universal in principle paying attention to the needs of disadvantaged and marginalized populations.'),
            (11,'sustainable-cities-and-communities','Make cities inclusive, safe, resilient and sustainable','Cities are hubs for ideas, commerce, culture, science, productivity, social development and much more. At their best, cities have enabled people to advance socially and economically.\nHowever, many challenges exist to maintaining cities in a way that continues to create jobs and prosperity while not straining land and resources. Common urban challenges include congestion, lack of funds to provide basic services, a shortage of adequate housing and declining infrastructure.\nThe challenges cities face can be overcome in ways that allow them to continue to thrive and grow, while improving resource use and reducing pollution and poverty. The future we want includes cities of opportunities for all, with access to basic services, energy, housing, transportation and more.'),
            (12,'responsible-consumption-and-production','Ensure sustainable consumption and production patterns','Sustainable consumption and production is about promoting resource and energy efficiency, sustainable infrastructure, and providing access to basic services, green and decent jobs and a better quality of life for all. Its implementation helps to achieve overall development plans, reduce future economic, environmental and social costs, strengthen economic competitiveness and reduce poverty.\nSustainable consumption and production  aims at “doing more and better with less,” increasing net welfare gains from economic activities by reducing resource use, degradation and pollution along the whole lifecycle, while increasing quality of life. It involves different stakeholders, including business, consumers, policy makers, researchers, scientists, retailers, media, and development cooperation agencies, among others.\nIt also requires a systemic approach and cooperation among actors operating in the supply chain, from producer to final consumer. It involves engaging consumers through awareness-raising and education on sustainable consumption and lifestyles, providing consumers with adequate information through standards and labels and engaging in sustainable public procurement, among others.'),
            (13,'climate-action','Take urgent action to combat climate change and its impacts','Climate change is now affecting every country on every continent. It is disrupting national economies and affecting lives, costing people, communities and countries dearly today and even more tomorrow.\nPeople are experiencing the significant impacts of climate change, which include changing weather patterns, rising sea level, and more extreme weather events. The greenhouse gas emissions from human activities are driving climate change and continue to rise. They are now at their highest levels in history. Without action, the world’s average surface temperature is projected to rise over the 21st century and is likely to surpass 3 degrees Celsius this century—with some areas of the world expected to warm even more. The poorest and most vulnerable people are being affected the most.'),
            (14,'life-below-water','Conserve and sustainably use the oceans, seas and marine resources','The world’s oceans – their temperature, chemistry, currents and life – drive global systems that make the Earth habitable for humankind.\nOur rainwater, drinking water, weather, climate, coastlines, much of our food, and even the oxygen in the air we breathe, are all ultimately provided and regulated by the sea. Throughout history, oceans and seas have been vital conduits for trade and transportation.\nCareful management of this essential global resource is a key feature of a sustainable future.'),
            (15,'life-on-land','Sustainably manage forests, combat desertification, halt and reverse land degradation, halt biodiversity loss','Forests cover 30 per cent of the Earth’s surface and in addition to providing food security and shelter, forests are key to combating climate change, protecting biodiversity and the homes of the indigenous population.  Thirteen million hectares of forests are being lost every year while the persistent degradation of drylands has led to the desertification of 3.6 billion hectares.\nDeforestation and desertification – caused by human activities and climate change – pose major challenges to sustainable development and have affected the lives and livelihoods of millions of people in the fight against poverty. Efforts are being made to manage forests and combat desertification.'),
            (16,'peace-justice-and-strong-institutions','Promote just, peaceful and inclusive societies','Goal 16 of the Sustainable Development Goals is dedicated to the promotion of peaceful and inclusive societies for sustainable development, the provision of access to justice for all, and building effective, accountable institutions at all levels.'),
            (17,'partnerships-for-the-goal','Revitalize the global partnership for sustainable development','A successful sustainable development agenda requires partnerships between governments, the private sector and civil society. These inclusive partnerships built upon principles and values, a shared vision, and shared goals that place people and the planet at the centre, are needed at the global, regional, national and local level.\nUrgent action is needed to mobilize, redirect and unlock the transformative power of trillions of dollars of private resources to deliver on sustainable development objectives. Long-term investments, including foreign direct investment, are needed in critical sectors, especially in developing countries. These include sustainable energy, infrastructure and transport, as well as information and communications technologies. The public sector will need to set a clear direction. Review and monitoring frameworks, regulations and incentive structures that enable such investments must be retooled to attract investments and reinforce sustainable development. National oversight mechanisms such as supreme audit institutions and oversight functions by legislatures should be strengthened.')
            ON DUPLICATE KEY UPDATE `s_number` = `s_number`;
        ";
        dbDelta($insert_sdgs);

    }

}