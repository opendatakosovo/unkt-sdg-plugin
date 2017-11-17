<?php
require_once(SDGS__PLUGIN_DIR . 'templates/functions.php');

if (isset($_GET)) {

   $data = get_targets(sprintf("%0d", $_GET['goal']));
   $targetsData = json_decode($data, true);
   $sdg_raw_data = get_sdg_data(sprintf("%0d", $_GET['goal']));
   $sdgJsonData = json_decode($sdg_raw_data);

   $targets_indicators = [];
   foreach($targetsData as $target) {
      $targets_indicators[
         $target['target_title']][] = [
            'indicator_id' => $target['indicator_id'],
            'indicator_tile' => $target['indicator_title'],
            'indicator_description' => $target['indicator_description'],
            'indicator_source' => $target['indicator_source'],
            'target_id' => $target['target_id'],
            'target_description' => $target['target_description']
         ];
   }

   $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="http://github.highcharts.com/master/modules/exporting.src.js"></script>

<script>
    $(document).ready(function () {

        var data = <?php echo json_encode($targets_indicators, true); ?>;
        var sdgData = <?php echo json_encode($sdgJsonData, true); ?>;
         // console.log(data);
        var sdg_text = document.createTextNode(sdgData[0]['s_text']);
        var sdg_title = sdgData[0]['long_name'];

        $('.sdg-title').text(sdg_title);
        $('.sdg-description').append('<span>');
        $('.sdg-description').append(sdgData[0]['s_text']);
        $('.sdg-description').append('</span>');

      var sdg_text = document.createTextNode(sdgData[0]['s_text']);
      var sdg_title = sdgData[0]['long_name'];

      // Populating SDG with informations from API
      $('.sdg-title').text(sdg_title);
      $('.sdg-description').append('<span>');
      $('.sdg-description').append(sdgData[0]['s_text']);
      $('.sdg-description').append('</span>');

      var counter = 0;
      Object.keys(data).forEach(key => {
         const indicator_id = [];
         for(var i = 0; i < data[key].length; i++) {
            indicator_id.push(data[key][i].indicator_id);
         }

         var openPanel = '';
         if(counter == 0){
            openPanel = 'in';
         }

         $('#accordion').append("<div class='panel'>\
            <div class='panel-heading'>\
               <h4 class='panel-title'>\
                 <a data-toggle='collapse' data-clicked='' id='panel-title' data-target-id='" + data[key][0].target_id + "' data-indicator-id='"+ indicator_id +"'  data-parent='#accordion' href='#panel-"+counter+"'>\
                  " + key + "</a>\
               </h4>\
            </div>\
            <div id='panel-"+counter+"' class='panel-collapse collapse "+ openPanel +"'>\
               <div data-target-id='" + data[key][0].target_id + "' class='panel-cont panel-body row'>\
               " + data[key][0].target_description + "</div>\
               <br/>\
            </div>\
         </div>\
         ");
         counter++;
      });

      const firstTargetId = $('.panel-heading h4 a').first().data('target-id');
      const firstIndicatorsId = $('.panel-heading h4 a').first().data('indicator-id');

      // ES7 async request
      const getChart = async (targetId, indicatorsId) => {
         let datachart = await $.ajax({
            type: 'GET',
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            dataType: 'json',
            data: {'target_id': targetId, 'id': indicatorsId, 'action': 'get_target_indicator_charts'}
         });
         return datachart;
      }

      const dataChartRequest = (firstTargetId, firstIndicatorsId) => {
         if(firstIndicatorsId.toString().indexOf(',') > -1) {
            var promises = [];
            const indicatorsIdArray = firstIndicatorsId.split(',');
            let count = 0;
            for(var i = 0; i < indicatorsIdArray.length; i++) {
               let request = getChart(firstTargetId, parseInt(indicatorsIdArray[i]));
               promises.push(request);
            }
            // Handling all promises values
            Promise.all(promises).then(promisesResponses => {
               prepareDataChart(promisesResponses);
            });

         } else {
            getChart(firstTargetId, firstIndicatorsId).then(result => {
               prepareDataChart(result);
            });
         }
      };

      // Immediately call
      dataChartRequest(firstTargetId, firstIndicatorsId);
      $('.panel-heading h4 a').first().attr('data-clicked', 'clicked');

      // Call on click
      $('.panel-heading h4').on('click', (e) => {
         $target = $(e.target);
         let target_id = $target.attr('data-target-id'),
             indicator_id = $target.attr('data-indicator-id'),
             isClicked = $target.attr('data-clicked');
         if (isClicked == '') {
            dataChartRequest(target_id, indicator_id);
            $target.attr('data-clicked', 'clicked');
         }
      });

      const JSONifyString = (entryString) => {
         // Decode HTML entity and JSON parse the decoded string
         return JSON.parse(entryString.replace(/&quot;/g, '\"'));
      }

      const buildFinalChartData = (currentObj) => {
         let JSONTargetValue = JSONifyString(currentObj.target_value),
             JSONDataChart = JSONifyString(currentObj.chart_data),

            finalChartObj = {
               title: currentObj.title,
               description: currentObj.description,
               sdg_id: currentObj.sdg_id,
               indicator_id: currentObj.indicator_id,
               target_id: currentObj.target_id,
               target_year: currentObj.target_year,
               target_unit: currentObj.target_unit,
               target_value: JSONTargetValue,
               id: currentObj.id,
               label: currentObj.label,
               chart_unit: currentObj.chart_unit,
               chart_data: JSONDataChart
            };
         return finalChartObj;
      }

      const prepareDataChart = (data) => {
         if (data.length >= 2) {
            data.map(entry => {
               if(entry != '') {
                  entry.map(chartData => {
                     let finalChartObj = buildFinalChartData(chartData);
                     generateChartContainer(finalChartObj);
                  });
               }
            });
         } else {
            if(data != '') {
               let finalChartObj = buildFinalChartData(data[0]);
               generateChartContainer(finalChartObj);
            }
         }
      }

      const generateChartContainer = (dataChartObj) => {
         $('.panel-collapse').find("[data-target-id='" + dataChartObj.target_id + "']").append("\
            <div style='border-bottom: 2px solid #fff'>\
            <div id='container-" + dataChartObj.id + "' style='min-width: 310px; height: 400px; margin: 0 auto' style='margin: 30px 0px' data-chart-id='" + dataChartObj.id + "'></div>\
         </div>");
         prepareAndRenderChart(dataChartObj);
      }

      // Get max number from array, need for target values in earlier years
      Array.prototype.max = function() {
        return Math.max.apply(null, this);
      };

      const prepareAndRenderChart = (dataChart) => {
         // Main data
         let chartTitle = dataChart.title,
             chartId = dataChart.id,
             chartDescription = dataChart.description;
             // chartLabel = dataChart.label;

         // Target data
         let targetUnit = dataChart.target_unit,
             targetValue = dataChart.target_value.value,
             targetYear = dataChart.target_year;

         // HANDLING DATA CHARTS //

         // Data Chart
         let chart_data = dataChart.chart_data;

         // Get first year
         let firstObjectBaseline = Object.keys(chart_data)[0];
         let series = [];
         let baselines = [];
         let labelArray = [];
         let targetData = [];
         let obj = {};
         let targetBaselinesData = {};

         // Take only first year and create an array with its labels, and
         // create Object where keys are labels with empty arrays
         Object.keys(chart_data).forEach(baseline => {
            // Adding years in a object and set value to empty arrays
            targetBaselinesData[baseline] = [];

            if(baseline == firstObjectBaseline) {
               chart_data[firstObjectBaseline].map((item, i)=> {
                  labelArray.push(chart_data[firstObjectBaseline][i].label);
                  obj[chart_data[firstObjectBaseline][i].label] = [];
               });
            }
         });

         // For each year we push in created obj the values from same labels in their array
         // Also we take the array of all years to pass in the categories of chart
         Object.keys(chart_data).forEach(baseline => {
            baselines.push(baseline);

            // Grouping together values per each same labels for baselines in order
            labelArray.map((item, i) => {
               chart_data[baseline].map((element, j) => {
                  if(chart_data[baseline][j].label == item){
                     obj[chart_data[baseline][j].label].push(parseInt(chart_data[baseline][j].value));
                  }
               });
            });

            // Grouping together values per each baseline
            chart_data[baseline].map(columnData => {
               targetBaselinesData[baseline].push(parseInt(columnData.value));
            });
         });

         // Foreach labels in obj create column for series, and push in targetData biggest values
         Object.keys(obj).forEach(label => {
            series.push({
               type: 'column',
               name: label,
               data: obj[label]
            });
         });

         // Getting the biggest values in years and pushing in target
         Object.keys(targetBaselinesData).forEach(baseline => {
            // Pushing biggest values from columns data in target data
            targetData.push(targetBaselinesData[baseline].max());
         });

         // Pushing the target value in targetData
         targetData.push(targetValue);

         // Making the target line
         let targetSpline = {
            type: 'spline',
            name: 'Target',
            data: targetData,
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0],
                fillColor: 'white'
            }
         }

         // Adding the target year to the baselines array
         baselines.push(targetYear);

         // Pushing the targetSpline into series
         series.push(targetSpline);

         // Render the chart
         Highcharts.chart('container-'+chartId, {
             title: {
                 text: 'Poverty'
             },
             xAxis: {
                 categories: baselines
             },
             labels: {
                 items: [{
                     html: '',
                     style: {
                         left: '50px',
                         top: '18px',
                         color: (Highcharts.theme && Highcharts.theme.textColor) || 'red'
                     }
                 }]
             },
             series: series
         });
      }

      // Styling the borders of panels
      $('.panel').last().css('border-bottom', '1px solid #fff');
      $('.panel-collapse').last().css('border-bottom', '1px solid #fff');

      $('.panel-heading h4').last().click(() => {
         $('.panel-heading').last().css('border-bottom', 'none');
         $('.panel-collapse').last().css('border-bottom', 'none');
      });
      $('.indicators').css('min-height', $('.sidebar').height() - $('.sdg-goal-page').height());
    });


    function convertToSlug(Text) {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }
</script>

<div class="sdg-goal-page sdg-goal-page-<?php echo $_GET['goal'] ?>">

   <!-- SDG information -->
   <div class="col-md-11 col-sm-12 col-xs-12">
      <div class="sdg-title">
      </div>
   </div>

   <div class="col-md-4 col-xs-11">
      <img class="single-goal-image img-responsive" alt="Sustainable Developement Goals" src="<?php echo SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' ?>"/>
   </div>

   <div class="col-md-8 col-md-offset-0 col-xs-11 col-xs-offset-1 sdg-description">
   </div>

   <!-- Indicators -->
   <div class="tabs">
      <h2>Indicators</h2>
      <div class="panel-group" id="accordion">
      </div>
   </div>

</div>

<style>
   .sdg-goal-page {
      overflow-x: hidden;
   }

   .panel-heading {
      padding: 10px;
      border-top: 1px solid #fff;
      border-right: 1px solid #fff;
      border-left: 1px solid #fff;
   }

   .panel-body {
      padding: 15px 30px;
      color: #fff;
   }

   .panel-collapse {
      border-right: 1px solid #fff;
      border-left: 1px solid #fff;
   }

   .panel-title {
      margin: 0!important;
   }

   .tabs {
      clear: both;
      padding: 20px;
      margin-bottom: 100px;
   }

   .panel-title {
   }

   .tabs a {
      color: #fff;
   }

   .tabs a:hover {
      color: #000e3e;
   }

   .tabs h2 {
      margin-bottom: 15px;
   }
    .article-content {
        padding: 0 !important;
    }

    .sidebar {
        /*margin-left: 16px !important;*/
        width: 26%;
    }
</style>
