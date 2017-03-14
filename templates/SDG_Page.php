<?php get_header(); ?>

<?php
require_once(SDGS__PLUGIN_DIR . 'templates/functions.php');
global $wpdb;
$scriptName = split('/', $_SERVER['SCRIPT_NAME']);
array_pop($scriptName);
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . $scriptName[1] . '/wp-config.php');

?>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'css/style.css' ?>>
    <link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'css/responsive.css' ?>>
    <div id="main-content" class="main-content">

        <?php
        if (is_front_page() && twentyfourteen_has_featured_posts()) {
            // Include the featured content template.
            get_template_part('featured-content');
        }
        ?>
        <script>
            $(document).ready(function () {
                // Custom template javascript
                if ($('.agencies').width() > 100) {
                    $('div#primary').width("75%");
                    $(window).resize(function () {
                        console.log($('.agencies').width());
                        if ($(window).width() < 991) {
                            $('div#primary').width("100%");
                        } else {
                            $('div#primary').width("75%");
                        }
                    });
                }

            });

        </script>
        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">
                <?php
                if (isset($_GET)) {
                    if ($_GET['goal']) {
                        include('SDG_Goal_Template.php');
                    } else {
                        include('SDG_Goals.php');
                    }
                }
                ?>

            </div><!-- #content -->

        </div><!-- #primary -->
        <?php get_sidebar(); ?>

    </div><!-- #main-content -->


<?php get_footer(); ?>