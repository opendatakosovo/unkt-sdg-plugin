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
            'indicator_title' => $target['indicator_title'],
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
    $(document).ready(() => {

        var data = <?php echo json_encode($targets_indicators, true); ?>;
        var sdgData = <?php echo json_encode($sdgJsonData, true); ?>;
        var sdg_text = document.createTextNode(sdgData[0]['s_text']);
        var sdg_title = sdgData[0]['long_name'];

        if(data.length <= 0) {
           $('.tabs').hide();
        }

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


      var sdgNumberId = <?php echo $_GET['goal'] ?>;
      var sdgColor;
      switch (sdgNumberId) {
           case 1:
             sdgColor = '#e5233b';
             break;
           case 2:
             sdgColor = '#dda73a';
             break;
           case 3:
             sdgColor = '#4ca146';
             break;
           case 4:
             sdgColor = '#c7212f';
             break;
           case 5:
             sdgColor = '#ef402d';
             break;
           case 6:
             sdgColor = '#27bfe6';
             break;
           case 7:
             sdgColor = '#fbc412';
             break;
           case 8:
             sdgColor = '#a31c44';
             break;
           case 9:
             sdgColor = '#f26a2e';
             break;
           case 10:
             sdgColor = '#de1768';
             break;
           case 11:
             sdgColor = '#f89d2a';
             break;
           case 12:
             sdgColor = '#bf8d2c';
             break;
           case 13:
             sdgColor = '#407f46';
             break;
           case 14:
             sdgColor = '#1f97d4';
             break;
           case 15:
             sdgColor = '#59ba47';
             break;
           case 16:
             sdgColor = '#136a9f';
             break;
           case 17:
             sdgColor = '#14496b';
             break;
      }

      var counter = 0;
      Object.keys(data).forEach(key => {
         const indicator_id = [];
         for(var i = 0; i < data[key].length; i++) {
            indicator_id.push(data[key][i].indicator_id);

            var eachIndicatorContainer = "<div data-indicator-id='"+ data[key][i].indicator_id +"' ></div>"

         }

         var openPanel = '';
         if(counter == 0){
            openPanel = 'in';
         }

         $('#accordion').append("<div class='panel'>\
            <div class='panel-heading'>\
               <h4 class='panel-title'>\
                 <a class='target-title' data-toggle='collapse' data-clicked='' id='panel-title' data-target-id='" + data[key][0].target_id + "' data-indicator-id='" + indicator_id + "'  data-parent='#accordion' href='#panel-"+counter+"'>\
                  <i class='fa ico fa-arrow-right' aria-hidden='true'></i> " + key + "</a>\
               </h4>\
            </div>\
            <div id='panel-" + counter + "' class='panel-collapse collapse "+ openPanel +"'>\
               <div data-target-id='" + data[key][0].target_id + "' class='panel-cont panel-body row'>\
               " + data[key][0].target_description + " <div data-targetId-indicators='" + data[key][0].target_id + "' id='indicators-container' </div>\
               <br/>\
            </div>\
         </div>\
         ");

         // Adding indicator divs foreach indicator-id
         for(var i = 0; i < data[key].length; i++) {
            $('.panel-collapse').find("[data-targetId-indicators='" + data[key][i].target_id + "']").append("<div style='margin-bottom: 20px; padding: 10px 0 10px 7px' data-indicator-id='"+ data[key][i].indicator_id +"' >\
               <p style='margin-bottom: 5px; font-size: 18px; font-weight: bold;'>" + data[key][i].indicator_title + "</p>\
               <p style='font-size: 15px;margin-bottom: 0px;'>" + data[key][i].indicator_description + "</p>\
               <p style='border-bottom: 1px solid rgba(0, 0, 0, 0.1); width: 99%; border-top: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 30px 10px 0px; text-align: left; display: inline-block; font-size: 15px; margin-bottom: 0px; margin-top: 15px;'>Source: " + data[key][i].indicator_source + "</p>\
               <div style='margin-top: 20px;' class='panel-group' id='" + data[key][i].indicator_id + "'>\
               </div>\
            </div>");
         }
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

         let JSONTargetValue = JSONifyString(currentObj.target_value);
             JSONDataChart = JSONifyString(currentObj.chart_data),
             JSONBaseline = JSONDataChart.baseline,
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
               chart_data: JSONDataChart,
               JSONBaseline:JSONBaseline
            };
         return finalChartObj;
      }

      const prepareDataChart = (data) => {
         // Looping in the array of data
         // it can be nested array when there are more indicators and one array when there is one indicators
         data.map(chartData => {
            // Checking if there are more indicators than one
            if(Array.isArray(chartData)) {
               // Handle data charts with more indicators
               if(chartData != '') {
                  chartData.map(chartDataObj => {
                     let finalChartObj = buildFinalChartData(chartDataObj);
                     generateChartContainer(finalChartObj);
                  });
               }
            } else {
               let finalChartObj = buildFinalChartData(chartData);
               generateChartContainer(finalChartObj);
            }
         });
      }


      const generateChartContainer = (dataChartObj) => {
         // console.log(dataChartObj);
         $('.panel-collapse').find("[id='" + dataChartObj.indicator_id + "']").append("\
            <div class='panel'>\
               <div class='panel-heading'>\
                  <h4 class='panel-title'>\
                    <a style='' class='chart-title' data-toggle='collapse' data-parent='#"+ dataChartObj.indicator_id +"' id='panel-title' href='#panel-" + dataChartObj.id + "'>\
                    <i class='fa fa-bar-chart' aria-hidden='true'></i> " + dataChartObj.title +"</a>\
                  </h4>\
               </div>\
               <div id='panel-" + dataChartObj.id + "' data-chart-indicator-id='"+ dataChartObj.indicator_id +"' style='border: none!important' class='panel-collapse collapse chart-panel'>\
                  <div class='panel-body row'>\
                     <div id='container-" + dataChartObj.id + "' style='min-width: 310px; height: 400px; margin: 0 auto' style='margin: 30px 0px' data-chart-id='" + dataChartObj.id + "'></div>\
                     <p style='text-align: center; font-size: 15px; margin-bottom: 0px;'><b>"+ dataChartObj.label +"</b></p>\
                     <i><span style='float: right; font-size: 13px;'>Baseline: "+ JSONBaseline +"</span></i>\
                        </div>\
                     </div>\
                  </div>\
               </div>\
            </div>\
         ");
         $('.panel-collapse').find("[data-chart-indicator-id='" + dataChartObj.indicator_id + "']").first().addClass('in');

         $('.target-title').first().addClass('active-target').css('color', sdgColor).find('.ico').removeClass('fa-arrow-right').addClass('fa-arrow-down');

         $('.chart-title').first().addClass('active-target').css('color', sdgColor)

         prepareAndRenderChart(dataChartObj);
      }

      // Get max number from array, need for target values in earlier years
      Array.prototype.max = function() {
        return Math.max.apply(null, this);
      };

      const prepareAndRenderChart = (dataChart) => {
         // console.log(dataChart);
         // Main data
         let chartTitle = dataChart.title,
             chartId = dataChart.id,
             chartDescription = dataChart.description,
             chartUnit = dataChart.chart_unit,
             chartBaseline = dataChart.chart_data.baseline;
             // chartLabel = dataChart.label;

         // Target data
         let targetUnit = dataChart.target_unit,
             targetValue = dataChart.target_value.value,
             targetRatioFirstVal = dataChart.target_value.value_a,
             targetRatioValue = dataChart.target_value.value_b,
             currentTargetVal = dataChart.target_value.current_value,
             maxTargetVal = dataChart.target_value.max_value,
             targetYear = dataChart.target_year;

         // HANDLING DATA CHARTS //
         // Data Chart
         let chart_data = dataChart.chart_data;

         // Get first year
         let firstObjectYear = Object.keys(chart_data)[0];
         let series = [];
         let years = [];
         let labelArray = [];
         let targetData = [];
         let obj = {};
         let targetYearsData = {};

         // Take only first year and create an array with its labels, and
         // create Object where keys are labels with empty arrays
         Object.keys(chart_data).forEach(year => {
            // Adding years in a object and set value to empty arrays
            targetYearsData[year] = [];

            if(year == firstObjectYear) {
               chart_data[firstObjectYear].map((item, i)=> {
                  labelArray.push(chart_data[firstObjectYear][i].label);
                  obj[chart_data[firstObjectYear][i].label] = [];
               });
            }
         });

         function shadeColor2(color, percent) {
            var f=parseInt(color.slice(1),16),t=percent<0?0:255,p=percent<0?percent*-1:percent,R=f>>16,G=f>>8&0x00FF,B=f&0x0000FF;
            return "#"+(0x1000000+(Math.round((t-R)*p)+R)*0x10000+(Math.round((t-G)*p)+G)*0x100+(Math.round((t-B)*p)+B)).toString(16).slice(1);
         }

         // For each year we push in created obj the values from same labels in their array
         // Also we take the array of all years to pass in the categories of chart
         Object.keys(chart_data).forEach(year => {
            if(year !== 'baseline'){
               years.push(year);
            }

            // Grouping together values per each same labels for years in order
            labelArray.map((item, i) => {
               if(year !== 'baseline'){
                  chart_data[year].map((element, j) => {
                     if(chart_data[year][j].label == item){
                        obj[chart_data[year][j].label].push(parseFloat(chart_data[year][j].value));
                     }
                  });
               }
            });

           // Grouping together values per each year
           if(targetUnit != 'ratio') {
                  if(year !== 'baseline'){
                     chart_data[year].map(columnData => {
                     targetYearsData[year].push(parseFloat(columnData.value));
                  });
               }
            }
         });

         // if ratio
         ratioTargetsSplines = []

         var targetUnitText = targetUnit;
         // Foreach labels in obj create column for series, and push in targetData biggest values
         Object.keys(obj).forEach(label => {

            // If ratio
            if(targetUnit == 'ratio') {
               finalTargetData = [];
               ffTargetData = [];

               obj[label].map(value => {
                  finalTargetData.push(value);
               });

               finalTargetData.map(value => {
                  let tarObj = {
                     'name': 'first',
                     y: value
                  }
                  ffTargetData.push(tarObj);
               });

               let finalTargetValue = Math.round((finalTargetData[finalTargetData.length - 1] * targetRatioFirstVal ) / targetRatioValue);
               ffTargetData.push({'name': label + ' target', y: finalTargetValue});


               // Define Target Line Points for Ratio
               let baselineIndexYearRatio = $.inArray(chartBaseline, years);
               let targetIndexYearRatio = years.length;
               let targetValueRatio = Math.round((finalTargetData[baselineIndexYearRatio] * targetRatioFirstVal ) / targetRatioValue);
               let ratioBaselineValue = finalTargetData[baselineIndexYearRatio];
               if(targetUnit === 'ratio') {
                 ratioBaselineValue =  finalTargetData[baselineIndexYearRatio];
               }
               var ratioTargetLinePoints = [[baselineIndexYearRatio, ratioBaselineValue], [targetIndexYearRatio, targetValueRatio]];

               let ratioTargetLine = {
                 name: 'Target',
                 dashStyle: 'dash',
                 lineWidth: 2,
                 shadow: false,
                 zIndex: 2,
                 color: '#000e3e',
                 data: ratioTargetLinePoints,
                 label: "ratio"
               };
               ratioTargetsSplines.push(ratioTargetLine);

               let ratioTrendData = ffTargetData.splice(0, ffTargetData.length-1);
               // TODO: trend
               let ratioTargetSpline = {
                  type: 'spline',
                  name: 'Trend Line',
                  data: ratioTrendData,
                  lineWidth: 1,
                  zIndex: 2,
                  color: '#000e3e',
                  marker: {
                     lineWidth: 1,
                     lineColor: Highcharts.getOptions().colors[0],
                     fillColor: sdgColor
                  }
               }
               if(ratioTrendData.length > 1){
                 series.push(ratioTargetSpline);
               }
            }

            // Getting the index of baseline column
            let baselineIndexYear = $.inArray(chartBaseline, years);
            let lighterSDGColor = shadeColor2(sdgColor, '0.4');

            // Changing the color of baseline column
            obj[label][baselineIndexYear] = {y: obj[label][baselineIndexYear], color: sdgColor};

            series.push({
               type: 'column',
               name: label,
               data: obj[label],
               color: lighterSDGColor,
               zIndex: 1
            });
         });

         // Getting the biggest values in years and pushing in target
         if(targetUnit != 'ratio') {
            Object.keys(targetYearsData).forEach(year => {
               // Pushing biggest values from columns data in target data
               if(year !== 'baseline'){
               if(targetValue == 'increasing' || targetValue == 'decreasing') {
                  let incValue = {
                     name: 'first',
                     y: targetYearsData[year].max()
                  }
                  targetData.push(incValue);
               } else {
                  targetData.push(targetYearsData[year].max());
               }
            }
            });
         }

         //Pushing the target value in targetData
         if(targetUnit == 'increasing-decreasing') {
            if(targetValue == 'increasing') {
              targetUnitText = 'increasing';
               let incValue = {
                  name: 'Increasing',
                  y: targetData[targetData.length-1].y + parseInt(Math.round(targetData[0].y).toFixed(2))
               }
               targetValue = incValue.y;
               targetData.push(incValue);

            } else if (targetValue == 'decreasing') {
              targetUnitText = 'decreasing';
              var currentDecreasingValue = targetData[targetData.length-1].y - parseInt(Math.round(targetData[0].y).toFixed(2));
              var finalDecreasingValue = currentDecreasingValue < 0 ? 0 : currentDecreasingValue;

               let decValue = {
                  name: 'Decreasing',
                  y: finalDecreasingValue
               }
               targetValue = decValue.y;
               targetData.push(decValue);
            }

         } else if (targetUnit == 'percentage' && chartUnit == 'number') {

            // Calculate percentage of chart data
            targetNumberPer = targetValue / 100 * targetData[targetData.length-1].toFixed(2);

            // If negative num
            if(targetValue < 0){
               var finalValue = targetData[targetData.length-1] - Math.abs(targetNumberPer);
            } else {
               var finalValue = targetNumberPer;
            }
            targetValue = finalValue;
            targetData.push(finalValue);

         } else if (targetUnit == 'percentage' && chartUnit == 'percentage') {

            // Calculate percentage of chart data
            targetNumberPer = targetValue / 100 * targetData[targetData.length-1].toFixed(2);

            if(targetValue < 0) {
               var finalValue = targetData[targetData.length-1] - Math.abs(targetNumberPer);
               targetValue = finalValue;
            } else {
               var finalValue = targetValue;
            }
            targetValue = finalValue;
            targetData.push(finalValue);

         } else if (targetUnit == 'comperative') {
            targetValue = currentTargetVal;
            targetData.push(currentTargetVal);
         }
         else {
            targetData.push(targetValue);
         }

         // When target unit is comperative add max target value at tooltip
         let maxTargetValueString = '';
         if (targetUnit == 'comperative') {
            maxTargetValueString = ' per ' + maxTargetVal;
         } else if (targetUnit == 'percentage' && chartUnit == 'percentage') {
            maxTargetValueString = ' %';
         }
         else {
            maxTargetValueString = '';
         }

         // Define Target Line Points
         let baselineIndexYear = $.inArray(chartBaseline, years);
         let targetIndexYear = years.length;
         var baselineValue = targetData[baselineIndexYear];
         if(targetUnit == 'increasing-decreasing') {
           baselineValue =  targetData[baselineIndexYear].y;
         }
         // Set target points
         var targetLinePoints = [[baselineIndexYear, baselineValue], [targetIndexYear, targetValue]];

         // Making the target line
         if(targetUnit == 'ratio') {
            ratioTargetsSplines.map(ratioTarget => {
               series.push(ratioTarget);
            });
         } else {
            let targetLine = {
              name: 'Target',
              dashStyle: 'dash',
              lineWidth: 2,
              shadow: false,
              zIndex: 2,
              color: '#000e3e',
              data: targetLinePoints,
              label: targetUnitText
            };

            let trendData = targetData.splice(0, targetData.length-1);

            let trendSpline = {
               type: 'spline',
               name: 'Trend Line',
               data: trendData,
               zIndex: 2,
               lineWidth: 1,
               color: '#000e3e',
               marker: {
                  lineWidth: 1,
                  lineColor: Highcharts.getOptions().colors[0],
                  fillColor: sdgColor
               },
            }
            // Pushing the trendSpline and targetLine into series
            if(trendData.length > 1){
              series.push(trendSpline);
            }
            series.push(targetLine);
         }

         // Adding the target year to the years array
         years.push(targetYear);

         // console.log(series);

         // Render the chart
         if(targetUnit != 'yes-no') {
               Highcharts.chart('container-'+chartId, {
               chart: {
                  // backgroundColor: null
               },
               // legend: {
               //    itemStyle: {
               //       color: sdgColor
               //    }
               // },
               // plotOptions: {
               //      series: {
               //          marker: {
               //              enabled: false
               //          },
               //          states: {
               //              hover: {
               //                  enabled: false
               //              }
               //          }
               //      }
               //  },

               tooltip: {
                 formatter: function() {
                   if (this.series.options.type === 'spline') { // the spline chart
                       return "Trend Line";
                   } else {
                     if(this.point.series.userOptions.label === 'decreasing'  || this.point.series.userOptions.label === 'increasing') {
                        return '<b> Target: ' + this.point.series.userOptions.label +'</b>';
                     } else if (this.point.name == 'first') {
                        return false;
                     } else {
                        return '<b>'+ this.x +'</b><br/>' + this.series.name +': '+ this.y + maxTargetValueString;
                     }
                   }
                  }
               },
               title: {
                  text: "",
               //    style: {
               //       color: sdgColor
               //    }
                },
               subtitle: {
                  text: chartDescription,
                  // style: {
                  //    color: sdgColor
                  // }
                },
               yAxis: {
                   labels: {
                      style: {
                         color: '#373a3c'
                      }
                   },
                   title: {
                      style: {
                         color: '#373a3c'
                      }
                   }
                },
               exporting: {
                     enabled: false,
                     buttons: {
                         contextButton: {
                             symbolFill: '#373a3c',
                             symbolStroke: '#373a3c'
                         }
                     }
               },
               xAxis: {
                    categories: years,
                    labels: {
                       style: {
                          color: '#373a3c'
                       }
                    }
               },
               credits: {
                   enabled: false
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
         } else {
            $('#container-'+chartId).append('<p style="margin-bottom: 10px; font-size: 16px; text-align: center">'+ chartDescription +'</p>');
            $('#container-'+chartId).css('height', '240px').append('<div style="margin-left: 20px; margin-bottom: 10px;" id="chart-data-boolean"></div>');

            Object.keys(chart_data).forEach(year => {
               if(year != 'baseline') {
                  $('#chart-data-boolean').append('<div style="float: left; text-align: center;padding: 30px; margin-right: 20px; height: 170px; border: 2px solid #eaeaea; border-radius: 5px;">\
                     <h4 style="margin-bottom: 10px">Year: ' + year + '</h4>\
                     <p style="margin-bottom: 0px; font-size: 15px;">'+ chart_data[year][0].label +'</p>\
                     <h1 style="text-transform: uppercase;"><b>'+ chart_data[year][0].value +'</b></h1>\
                  ');
               }
            });

            // Add target
            $('#chart-data-boolean').append('<div style="float: left; text-align: center; padding: 30px; margin-right: 20px; height: 170px; border: 3px dotted #eaeaea; border-radius: 5px;">\
               <h4 style="margin-bottom: 10px">Target Year: ' + targetYear + '</h4>\
               <div class="labels-cont"></div>\
               <h1 style="text-transform: uppercase;"><b>'+ targetValue +'</b></h1>\
            ');

            Object.keys(chart_data).forEach(year => {
               if(year != 'baseline') {
                  $('.labels-cont').append('<p style="display: inline; font-size: 15px;">' + chart_data[year][0].label +' </p>');
               }
            });
         }

         $('.collapse').on('shown.bs.collapse', function(event){
            event.stopPropagation();
            // console.log('opened');
            // $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
         }).on('hidden.bs.collapse', function(){
            // console.log('closed');
            // $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
         });

         $(".target-title, .chart-title").mouseover(function() {
            $(this).css('color', sdgColor);
         }).mouseout(function() {
            $(this).css('color', '#373a3c');
            $('.active-target').css('color', sdgColor);
         });

         $(document).on('click', '.chart-title', function() {
            $('.chart-title.active-target').removeClass('active-target').css('color', '#373a3c');

            $(this).addClass('active-target');
            $('.active-target').css('color', sdgColor);
         });


         $(document).on('click', '.target-title', function() {
            setTimeout(() => {
               $('.target-title').each(function(i, e) {
                  $(e).removeClass('active-target').css('color', '#373a3c');
               });
               $('.target-title').find('.ico').removeClass('fa-arrow-down').addClass('fa-arrow-right');
            }, 200)

            setTimeout(() => {
               $(this).addClass('active-target');
               $('.active-target').css('color', sdgColor);
               $('.target-title.active-target').find('.ico').removeClass('fa-arrow-right').addClass('fa-arrow-down');
            }, 350);
         });

          // $('.fa-arrow-right').css('color', sdgColor);


         // //yearsTargetData[chartBaseline.toString()] = "xona";
         // $.map( targetArray, function(value, index){
         //       if (index < baselineIndex){
         //           targetArray[index]= 0;
         //       }else if (index === baselineIndex){
         //           targetArray[index] = baselineValue;
         //       }else if (index === countTargetItems){
         //           targetArray[index] = targetValue;
         //       }else{
         //          targetArray[index] = 0;
         //       }
         //  });
         //  generateLinePoints(10, 0, )
         //  function generateLinePoints(start, end, length){
         //    var arrayPoints = [];
         //    for(var i == 1;i<length; i++){
         //      var devide = i+2;
         //      arrayPoints.push(start/i+2);
         //    }
         //    arrayPoints[0] = start;
         //    arrayPoints[length-1] = end;
         //  }
         //
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

<div class="sdg-goal-page" style="padding-top: 0px !important;">
  <div class="sdg-goal-page sdg-description-part sdg-goal-page-<?php echo $_GET['goal'] ?>">
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
 </div>
   <!-- Indicators -->
   <div class="tabs">
      <h2>Targets with indicators</h2>
      <div class="panel-group" id="accordion">
      </div>
      <div class="well">
          <p style="float:right;font-style:italic;">These data are not finalized. We are still working on! </p>
      </div>
   </div>
</div>

<style>

   .sdg-goal-page {
      overflow-x: hidden;
   }

   .target-title {
      font-weight: bold;
   }

   .panel-heading {
      padding: 10px;
      border-top: 1px solid #fff;
      border-right: 1px solid #fff;
      border-left: 1px solid #fff;
   }

   .panel-body {
      padding: 15px 30px;
      color: #373a3c;
   }

   .panel-collapse {
      border: 1px solid rgba(0, 0, 0, 0.1);
      border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
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
      color: #373a3c;
   }

   .tabs a:hover {
      color: #000e3e; /*TODO*/
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
        background-color:  white !important;
    }
</style>
