
<?php get_header(); ?>
<?php $header =  get_header(); ?>
<?php
require_once(SDGS__PLUGIN_DIR . 'templates/functions.php');
global $wpdb;
$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
?>

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'css/style.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'css/responsive.css' ?>>

<div class="content content-article">
    <div class="container-fluid">
        <div class="row">
            <div class="article-wrapper">
                <div class="article-inner">
                    <div class="article-sidebar">
                    </div>
                    <div class="article-content">
                        <?php
                        if (isset($_GET)) {
                            if ($_GET['goal']) {
                                include('SDG_Goal_Template.php');
                            } else {
                                include('SDG_Goals.php');
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php get_sidebar(); ?>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>