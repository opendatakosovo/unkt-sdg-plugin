<?php get_header(); ?>

<?php
global $wpdb;
$scriptName = $_SERVER['SCRIPT_NAME'];
require_once($_SERVER['DOCUMENT_ROOT']  . '/'.split('/',$scriptName)[1].'/wp-config.php');

?>
<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/style.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/responsive.css' ?>>
<div id="content" class="site-content" role="main">
	<?php
	if(isset($_GET)){
		if($_GET['goal']){
			include('SDG_Goal_Template.php');
		}else{
			include('SDG_Goals.php');
		}
	}
	?>
	<?php get_sidebar(); ?>
	<?php get_sidebar( 'left' ); ?>
	<?php get_sidebar( 'right' ); ?>
</div>

<?php get_footer(); ?>