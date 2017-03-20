<?php
require_once(SDGS__PLUGIN_DIR . 'templates/functions.php');
if (isset($_GET)) {
    $data = get_data(sprintf("%0d", $_GET['goal']));

    $indicatorData = json_decode($data, true);

    $sdg_raw_data = get_sdg_data(sprintf("%0d", $_GET['goal']));
    $sdgJsonData = json_decode($sdg_raw_data);
    $out = [];
    foreach ($indicatorData as $element) {
        $out[$element['name']][] = ['date' => $element['date'], 'date' => $element['date'], 'value' => $element['value'], 'target_value' => $element['target_value'], 'description' => $element['description'], 's_text' => $element['s_text'], 'long_name' => $element['long_name']];
    }
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}
?>
<?php ?>
<head>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="http://github.highcharts.com/master/modules/exporting.src.js"></script>
    <!-- Facebook Graph Data -->
    <meta name="description" content="<?php echo $sdgJsonData[0]->s_text; ?>"/>
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo $sdgJsonData[0]->long_name; ?>" />
    <meta property="og:description" content="<?php echo $sdgJsonData[0]->s_text; ?>" />
    <meta property="og:url" content="<?php echo $url ?>" />
    <meta property="og:site_name" content="UNKT" />
    <meta property="og:image" content="<?php echo SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' ?>" />


    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="<?php echo $sdgJsonData[0]->long_name; ?>">
    <meta name="twitter:description" content="<?php echo $sdgJsonData[0]->s_text; ?>">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="<?php echo SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' ?>">
</head>


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
        $('.sdg-description').append(sdg_text);

        $('.indicators').css('min-height', $('.sidebar').height() - $('.sdg-goal-page').height());
    });
    function generateChart(id, data, title) {
        $('.indicators').append("\
        <div class='row'>\
            <div class='row'>\
                <div class='col-md-9 col-xs-8 col-sm-7 indicator-title'>\
                        <h4>" + title + "</h4>\
                </div>\
                <div class='col-md-2 col-xs-2 col-sm-5'>\
                    <button id='" + id + "-chart' class='show-chart' >Show chart</button>\
                </div>\
            </div>\
            <div class='row'>\
                <div class='row'>\
                    <div class='col-md-11 col-xs-10 col-sm-10' id='" + id + "-description' >\
                    </div>\
                </div>\
            </div>\
            <div class='row'>\
                <div id='" + id + "' class='col-md-10 col-xs-11 col-sm-11' style='display:none; width:100% !important; height: 300px'></div><hr class='white-line'>\
            </div>\
        </div>");
        $('#' + id + '-chart').click(function (e) {
            var displayStatus = $('#' + id).css('display');
            if (displayStatus == 'none') {
                $('#' + id).show();
                $('#' + id + '-chart').text("Hide chart");
            } else {
                $('#' + id).hide();
                $('#' + id + '-chart').text("Show chart");
            }
        });
        $(window).resize(function (e) {
            $(Highcharts.charts).each(function (i, chart) {
                var height = chart.renderTo.clientHeight;
                var width = chart.renderTo.clientWidth;
                chart.setSize($('.indicators').width() - 40, height);
            });
        });
        $('#' + id + '-description').append("\
		<p>" + data[0]['description'] + "</p>\
	");
        var chartCategories = [];
        var chartSeries = [];
        var chartTargetSeries = [];
        for (var index in data) {
            chartCategories.push(data[index]['date']);
            chartSeries.push(parseInt(data[index]['value']))
            chartTargetSeries.push(parseInt(data[index]['target_value']));
        }
        var chartOptions = {
            chart: {
                renderTo: id,
                backgroundColor: null,
                width: $('.indicators').width()-30
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: chartCategories,
                lineWidth: 0,
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
            yAxis: {
                gridLineColor: 'transparent',
                labels: {
                    enabled: false
                },
                title: {
                    enabled: false
                }
            },
            exporting: {
                filename: convertToSlug(title),
                buttons: {
                    contextButton: {
                        symbol: "url(<?php echo SDGS__PLUGIN_URL .'img/download-2-xxl.png' ?>)"
                    }
                }
            },
            series: [{
                    data: chartTargetSeries,
                    name: 'value',
                    color: 'white',
                    dashStyle: 'solid'
                },
                {
                    data: chartSeries,
                    name: 'target value',
                    color: 'white',
                    dashStyle: 'dash'
                }],
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            }

        }
        new Highcharts.Chart(chartOptions);
    }
    function convertToSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-')
            ;
    }
</script>
<div class="row">
    <div class="sdg-goal-page sdg-goal-page-<?php echo $_GET['goal'] ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="sdg-title">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <img class="single-goal-image img-responsive" alt="Sustainable Developement Goals"
                     src="<?php echo SDGS__PLUGIN_URL . 'img/E_SDG_Icons-' . $_GET['goal'] . '.jpg' ?>"/>
            </div>

            <div class="col-md-8 col-xs-12">
                <p class="sdg-description"></p>
            </div>
        </div>
        <div class="row indicators">
        </div>

    </div>
</div>
</div>