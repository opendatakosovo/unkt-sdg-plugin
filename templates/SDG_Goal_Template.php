<?php
require_once(SDGS__PLUGIN_DIR . 'templates/functions.php');

if (isset($_GET)) {

   $data = get_data(sprintf("%0d", $_GET['goal']));
   $targetsData = json_decode($data, true);

   $sdg_raw_data = get_sdg_data(sprintf("%0d", $_GET['goal']));
   $sdgJsonData = json_decode($sdg_raw_data);

   $out = [];
   foreach ($targetsData as $element) {
   $out[$element['name']][] = ['date' => $element['date'],
      'target_date' => $element['target_date'], 'updated_date' => $element['updated_date'], 'source' => $element['source_url'], 'value' => $element['value'], 'target_value' => $element['target_value'], 'description' => $element['description'], 's_text' => $element['s_text'], 'long_name' => $element['long_name'], 'unit' => $element['unit']
      ];
   }
   $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="http://github.highcharts.com/master/modules/exporting.src.js"></script>

<script>
    $(document).ready(function () {

        var data = <?php echo json_encode($out, true); ?>;

        var sdgData = <?php echo json_encode($sdgJsonData, true); ?>;

        var sdg_text = document.createTextNode(sdgData[0]['s_text']);
        var sdg_title = sdgData[0]['long_name'];

        var counter = 0;
        for (var index in data) {
            var id = "indicator-chart-" + counter;
            counter++;
            generateChart(id, data[index], index);
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

      var counter = 0;
      // Going through each indicator
      Object.keys(data).forEach(function(key) {
         var openPanel = '';
         if(counter == 0){
            openPanel = 'in';
         }

         $('#accordion').append("<div class='panel'>\
            <div class='panel-heading'>\
               <h4 class='panel-title'>\
                 <a data-toggle='collapse' id='panel-title' data-parent='#accordion' href='#panel-"+counter+"'>\
                  "+key+"</a>\
               </h4>\
            </div>\
            <div id='panel-"+counter+"' class='panel-collapse container collapse "+ openPanel +"'>\
               <div class='panel-body row'>\
               "+key+"</div>\
            </div>\
         </div>\
         ");
         counter++;
      });

      $('.indicators').css('min-height', $('.sidebar').height() - $('.sdg-goal-page').height());
    });

   //  function generateChart(id, data, title) {
   //      $('#' + id + '-chart').click(function (e) {
   //          var displayStatus = $('#' + id).css('display');
   //          if (displayStatus == 'none') {
   //              $('#' + id).show();
   //              $('#' + id + '-chart').text("Hide chart");
   //          } else {
   //              $('#' + id).hide();
   //              $('#' + id + '-chart').text("Show chart");
   //          }
   //      });
   //      $(window).resize(function (e) {
   //          $(Highcharts.charts).each(function (i, chart) {
   //              var height = chart.renderTo.clientHeight;
   //              var width = chart.renderTo.clientWidth;
   //              chart.setSize($('.indicators').width() - 40, height);
   //          });
   //      });
   //      $('#' + id + '-description').append("\
   //          <span class='indicator-description'>" + data[0]['description'] + "</span>\
   //      ");
   //      var chartCategories = [];
   //      var chartSeries = [];
   //      var chartTargetSeries = [];
   //      for (var index in data) {
   //          chartCategories.push(data[index]['date']);
   //          chartSeries.push({'name':'Value','y':parseInt(data[index]['value']), 'source': data[index]['source']});
   //          if (index == 0) {
   //              chartTargetSeries.push({'name':'Starting target value:','y':parseInt(data[0]['value']), 'source': ''});
   //          }
   //          if (index == data.length - 1) {
   //              chartTargetSeries.push({'name':'Target value','y':parseInt(data[0]['target_value']), 'source': ''});
    //
   //              chartCategories.push(data[index]['target_date'])
   //          } else {
   //              chartTargetSeries.push(null);
   //          }
   //      }
   //      var chartOptions = {
   //              chart: {
   //                  renderTo: id,
   //                  backgroundColor: null,
   //                  width: $('.indicators').width() - 30
   //              },
   //              title: {
   //                  text: ''
   //              },
   //              xAxis: {
   //                  categories: chartCategories,
   //                  lineWidth: 0,
   //                  minorGridLineWidth: 1,
   //                  lineColor: 'white',
   //                  labels: {
   //                      enabled: true,
   //                      style: {"color": "white", "cursor": "default", "fontSize": "11px"}
   //                  },
   //                  tickLength: 0,
   //                  title: {
   //                      enabled: false
   //                  }
   //              },
   //              yAxis: {
   //                  gridLineColor: 'transparent',
   //                  labels: {
   //                      enabled: true,
   //                      style: {"color": "white", "cursor": "default", "fontSize": "11px"}
   //                  },
   //                  title: {
   //                      enabled: false
   //                  }
   //              },
   //              exporting: {
   //                  filename: convertToSlug(title),
   //                  buttons: {
   //                      contextButton: {
   //                          symbol: "url(<?php echo SDGS__PLUGIN_URL . 'img/download-2-xxl.png' ?>)"
   //                      }
   //                  },
   //                  chartOptions: {
   //                      plotOptions: {
   //                          series: {
   //                              dataLabels: {
   //                                  enabled: true
   //                              }
   //                          }
   //                      },
   //                      xAxis: {
   //                          lineWidth: 1,
   //                          minorGridLineWidth: 1,
   //                          lineColor: 'white',
   //                          labels: {
   //                              style: {"color": "white", "cursor": "default", "fontSize": "11px"}
   //                          },
   //                          tickLength: 1,
   //                          title: {
   //                              enabled: true
   //                          }
   //                      },
   //                      yAxis: {
   //                          gridLineColor: 'white',
   //                          labels: {
   //                              style: {"color": "white", "cursor": "default", "fontSize": "11px"}
   //                          },
   //                          title: {
   //                              enabled: true
   //                          }
   //                      },
   //                      chart: {
   //                          backgroundColor: 'lightblue',
   //                      }
    //
   //                  }
   //              },
   //              navigation: {
   //                  buttonOptions: {
   //                      verticalAlign: 'right',
   //                      x:-15
   //                  }
   //              },
   //              series: [{
   //                  data: chartSeries,
   //                  name: 'Value',
   //                  color: 'white',
   //                  dashStyle: 'solid'
   //              },
   //                  {
   //                      data: chartTargetSeries,
   //                      name: 'Target value',
   //                      color: 'white',
   //                      dashStyle: 'dash'
   //                  }],
   //              legend: {
   //                  enabled: false
   //              },
   //              credits: {
   //                  enabled: false
   //              },
   //              tooltip: {
   //                  formatter: function () {
   //                      var s = '<b>Date: ' + this.x + '</b>';
    //
   //                      $.each(this.points, function () {
   //                          s += '<br/>' + this.series.name + ': ' +
   //                              this.y + ' ' +data[0]['unit'];
   //                          if(this.point.source != ''){
   //                              s += '<br/>Source: ' + this.point.source;
   //                          }
   //                      });
    //
   //                      return s;
   //                  },
   //                  shared: true
   //              },
   //              plotOptions: {
   //                  series: {
   //                      connectNulls: true
   //                  }
   //              }
   //              ,
    //
    //
   //          }
   //          ;
   //      new Highcharts.Chart(chartOptions);
   //  }
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
      border-radius: 10px;
      padding: 10px;
      border-top: 1px solid #fff;
      border-right: 1px solid #fff;
      border-left: 1px solid #fff;
   }

   .panel-body {
      padding: 15px;
   }

   .panel-title {
      margin: 0!important;
   }

   .tabs {
      clear: both;
      padding: 20px;
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
