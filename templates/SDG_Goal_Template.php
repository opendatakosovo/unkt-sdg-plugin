<?php get_header();
require_once( SDGS__PLUGIN_DIR . 'templates/functions.php' );
$scriptName = $_SERVER['SCRIPT_NAME'];
require_once($_SERVER['DOCUMENT_ROOT']  . '/'.split('/',$scriptName)[1].'/wp-config.php');

if(isset($_GET)){
	$data = get_data(sprintf("%0d", $_GET['goal']));
	
	$indicatorData = json_decode($data, true);

	$sdg_raw_data = get_sdg_data(sprintf("%0d", $_GET['goal']));
	$sdgData = json_decode($sdg_raw_data);
	$out = [];
	foreach($indicatorData as $element) {
        $out[$element['name']][] = ['date' => $element['date'], 'date' => $element['date'], 'value'=> $element['value'], 'target_value'=> $element['target_value'],  'description'=> $element['description'], 's_text'=> $element['s_text'], 'long_name'=> $element['long_name']];
	}
}
?>
<?php  ?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
  $(document).ready(function(){

  	
	var data = <?php echo json_encode($out, true); ?>;
	var sdgData = <?php echo json_encode($sdgData, true); ?>;
	var sdg_text = document.createTextNode(sdgData[0]['s_text']);
	var sdg_title =  sdgData[0]['long_name'];

  	var counter = 0;
  	for(var index in data){
  		var id = "indicator-chart-"+counter;
  		counter++;
  		generateChart(id, data[index], index);
  	}
  	$('.sdg-title').text(sdg_title);
  	$('.sdg-description').append(sdg_text);
	
  });
function generateChart(id, data, title){
	$('.indicators').append("\
	<div class='row'>\
		<div class='row'>\
			<div class='col-md-9 col-xs-7 col-sm-7 indicator-title'>\
					<h4>"+title+"</h4>\
			</div>\
			<div class='col-md-3 col-xs-12 col-sm-5'>\
				<button id='"+id+"-chart' class='show-chart' >Show chart</button>\
			</div>\
		</div>\
		<div class='row'>\
			<div class='row'>\
				<div class='col-md-11 col-xs-10 col-sm-10' id='"+id+"-description' >\
				</div>\
			</div>\
		</div>\
		<div class='row'>\
			<div id='"+id+"' class='col-md-10 col-xs-11 col-sm-11' style='display:none; width:100% !important; height: 300px'></div>\
		</div>\
	</div>");
	$('#'+id+'-chart').click(function(e){
		var displayStatus = $('#'+id).css('display');
		if(displayStatus == 'none'){
			$('#'+id).show();
		}else{
			$('#'+id).hide();
		}
	});
	$(window).resize(function(e){
		$(Highcharts.charts).each(function(i,chart){
		    var height = chart.renderTo.clientHeight; 
		    var width = chart.renderTo.clientWidth; 
		    chart.setSize($('.indicators').width()-40, height); 
		  });
	});
	$('#'+id+'-description').append("\
		<p>"+data[0]['description']+"</p>\
	");
	var chartCategories = [];
	var chartSeries = [];
	var chartTargetSeries = [];
	for(var index in data){
		chartCategories.push(data[index]['date']);
		chartSeries.push(parseInt(data[index]['value']))
		chartTargetSeries.push(parseInt(data[index]['target_value']));
	}
	var chartOptions = {
		chart: { 
			renderTo: id, 
            backgroundColor:null,
            width: $('.indicators').width()
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
	        data: chartTargetSeries,
	        name: 'value',
	        color: 'white',
	        dashStyle:'solid'
	    },
	    {
	        data: chartSeries,
	        name: 'target value',
	        color: 'white',
	        dashStyle:'dash'
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

<div class=" sdg-goal-page sdg-goal-page-<?php echo $_GET['goal'] ?>">
		<div class="row">
			<div class="col-md-12">
				<div class="sdg-title">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<img class="single-goal-image img-responsive" alt="Sustainable Developement Goals" src="<?php echo SDGS__PLUGIN_URL.'img/E_SDG_icons-'.$_GET['goal'].'.jpg' ?>" />
			</div>
			
			<div class="col-md-8 col-xs-12">
				
				
				<p class="sdg-description"></p>
			</div>
		</div>
		<div class="row indicators">
		</div>
		
	</div>
</div>
<?php get_footer(); ?>