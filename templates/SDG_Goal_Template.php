<?php get_header();
require_once( SDGS__PLUGIN_DIR . 'templates/functions.php' );
require_once($_SERVER['DOCUMENT_ROOT']  . '/plugin/wp-config.php');

if(isset($_GET)){
	$data = get_data(sprintf("%0d", $_GET['goal']));
	
	$indicatorData = json_decode($data, true);

	$sdg_raw_data = get_sdg_data(sprintf("%0d", $_GET['goal']));
	$sdgData = json_decode($sdg_raw_data);
	$out = [];
	foreach($indicatorData as $element) {
        $out[$element['name']][] = ['date' => $element['date'], 'date' => $element['date'], 'value'=> $element['value'], 'description'=> $element['description'], 's_text'=> $element['s_text'], 'long_name'=> $element['long_name']];
	}
}
?>
<?php  ?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/style.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/responsive.css' ?>>
<script>
  $(document).ready(function(){

  	
	var data = <?php echo json_encode($out, true); ?>;
	var sdgData = <?php echo json_encode($sdgData, true); ?>;

	var sdg_text = sdgData[0]['s_text'];
	var sdg_title =  sdgData[0]['long_name'];
	// console.log(data);
  	var counter = 0;
  	for(var index in data){
  		var id = "indicator-chart-"+counter;
  		counter++;
  		generateChart(id, data[index], index);
  	}
  	$('.sdg-title').text(sdg_title);
  	$('.sdg-description').text(sdg_text);
	
  });
function generateChart(id, data, title){
	$('.indicators').append("\
		<div class='row'>\
			<div class='row'>\
				<div class='col-md-8'>\
						<h4>"+title+"</h4>\
				</div>\
			</div>\
			<div class='row'>\
				<div class='col-md-12 col-xs-10 col-sm-10' id='"+id+"-description' >\
				</div>\
				<div class='row'>\
					<div class='col-md-2 col-md-offset-9 col-xs-4 col-xs-offset-7 col-sm-3 col-sm-offset-9'>\
						<button class='btn btn-default' >Show chart</button>\
					</div>\
				</div>\
			</div>\
			<div class='row'>\
				<div id='"+id+"' class='col-md-11 col-xs-11 col-sm-11' style='height: 300px'></div>\
			</div>\
		</div>");
	$('#'+id+'-description').append("\
		<p>"+data[0]['description']+"</p>\
		");
	var chartCategories = [];
	var chartSeries = [];
	for(var index in data){
		chartCategories.push(data[index]['date']);
		chartSeries.push(parseInt(data[index]['value']))
	}
	var chartOptions = {
		chart: { 
			renderTo: id, 
            backgroundColor:null
        },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: chartCategories,
			minorGridLineWidth: 1,
			lineColor: 'white',
			labels: {
			   enabled: false
			},
			tickLength: 0,
        	title: {
                enabled: false
            }
	    },
	    yAxis:{
	    	gridLineColor: 'transparent',
	    	labels: {
			   enabled: false
			},
			title: {
                enabled: false
            }
	    },
	    series: [{
	        data: chartSeries,
	        name: '',
	        style: {
	        	color: 'white'
	        } 
	    }],
	    legend:{
	    	enabled: false
	    },
	    credits: {
	      enabled: false
	  }

	}
	new Highcharts.Chart(chartOptions);
}
</script>

<div class="container sdg-goal-page sdg-goal-page-<?php echo $_GET['goal'] ?>">
	<div class="row text-center">
		<span class="sdg-title "></span>
	</div>
	<div class="row">
		<div class="col-md-4 col-xs-12">
			<img class="single-goal-image" alt="Sustainable Developement Goals" src="<?php echo SDGS__PLUGIN_URL.'img/E_SDG_icons-'.$_GET['goal'].'.jpg' ?>" />
		</div>
		
		<div class="col-md-8 col-xs-12">
			<p class="sdg-description"></p>
		</div>
	</div>
	<div class="row indicators">
	</div>
</div>
<?php get_footer(); ?>