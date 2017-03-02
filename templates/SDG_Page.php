<?php get_header(); ?>

<?php
global $wpdb;
$scriptName = $_SERVER['SCRIPT_NAME'];
require_once($_SERVER['DOCUMENT_ROOT']  . '/'.split('/',$scriptName)[1].'/wp-config.php');

?>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/style.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/responsive.css' ?>>
<?php
if(isset($_GET)){
	if($_GET['goal']){
		include('SDG_Goal_Template.php');
	}else{
		include('SDG_Goals.php');
	}
}
?>
<?php get_footer(); ?>