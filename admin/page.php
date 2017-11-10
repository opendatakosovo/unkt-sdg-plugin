<script type="text/javascript" src="//cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css"/>

<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'css/font-awesome.min.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'css/admin-style.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'fonts/fontawesome-webfont.woff' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL . 'fonts/fontawesome-webfont.woff2' ?>>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="//cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Header admin page -->
<div class="row">
    <?php
       $args = [
           'post_type' => 'page',
           'meta_key' => '_wp_page_template',
           'meta_value' => 'templates/SDG_Page.php'
       ];
       $pages = get_posts($args);
    ?>

    <div class="col-md-12">
        <h3>Welcome, you can view your SDG Goals page/s below:</h3><br/>
    </div>
    <!-- <div class="col-md-11 col-md-offset-1">
        <ol>
           <?php
              //foreach ($pages as $page)
                  //echo '<li><a target="_blank" href="' . get_page_link($page->ID) . '" >' . get_the_title($page->ID) . '</a></li>';
           ?>
        </ol>
    </div> -->
</div>

<!-- Main Container -->
<div class="container wrap" style="margin-top:60px; height:auto; min-height:2000px;">

   <!-- Target Table -->
   <table id="exampleTable" class="table-bordered">
        <thead>
           <tr>
               <th>ID</th>
               <th>Target Title</th>
               <th>SDG</th>
               <th>Updated date</th>
               <th>Description</th>
               <th>Actions</th>
           </tr>
        </thead>
        <tbody></tbody>
   </table>

   <!-- Chart Table -->
   <div style="display:none" id="div-sub-sub-table" style="background:#337ab7;height:auto;">
        <table id="chartsTable" class="table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Unit</th>
                <th>Target Date</th>
                <th>Target Value</th>
                <th>Data</th>
                <th>Description</th>
                <th>Disaggregated by</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
   </div>

   <!-- Indicator Table -->
   <div style="display:none" id="div-sub-table" style="background:#337ab7;height:auto;">
        <table id="detailsTable" class="table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Indicator Title</th>
                <th>Source</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
   </div>

      <div class="col-md-12">
         <!-- Add Indicator Modal -->
         <div id="add-indicator-modal" class="modal fade" tabindex="-1"> <!-- old: add-measurement-modal -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Add Indicator</h4>
                    </div>
                    <div class="modal-body">
                        <form id="add-indicator-form" name="add-indicator"> <!-- add-measurement-form -->
                            <input id="indicator-target-id"/> <!-- measurement_targets_id -->
                            <input id="indicator-sdg"/><!-- measurement_sdg  -->
                            <div class="form-group">
                                <label for="title-indicator">Title:</label>
                                <input name="title-indicator" type="text" class="form-control"
                                          id="title-indicator" placeholder="Title"></input>
                            </div>
                            <div class="form-group">
                                <label for="source-indicator">Source:</label>
                                <input name="source-indicator" type="text" class="form-control"
                                       id="source-indicator" placeholder="Source">
                            </div>
                            <div class="form-group">
                                <label for="description-indicator">Description:</label>
                                <textarea name="description-indicator" type="text" class="form-control"
                                          id="description-indicator" placeholder="Description"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                                <input type="submit" value="Save changes" name="add-measurement" class="btn btn-primary"
                                       id="add-measuremnt-button">

                            </div><!-- /.modal-content -->
                        </form>
                    </div>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- end of indicator modal -->
         </div>

         <!-- Edit Indicator Modal -->
         <div id="edit-indicator-modal" class="modal fade" tabindex="-1"> <!-- edit-measurement-modal -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Edit Indicator</h4>
                    </div>
                    <div class="modal-body">
                        <form id="edit-indicator-form" name="edit-indicator">
                            <input type="" id="edit-indicator-id"/> <!-- old: indicator_targets_id -->
                            <input type="" id="edit-indicator-sdg"/> <!-- old: measurement_sdg -->
                            <div class="form-group">
                                <label for="title-indicator">Name:</label>
                                <input name="title-indicator" type="text" class="form-control"
                                          id="edit-title-indicator" placeholder="Name"></input>
                            </div>
                            <div class="form-group">
                                <label for="source-indicator">Source:</label>
                                <input name="source-indicator" type="text" class="form-control"
                                       id="edit-source-indicator" placeholder="Source">
                            </div>
                            <div class="form-group">
                                <label for="description-indicator">Description:</label>
                                <textarea name="description-indicator" type="text" class="form-control"
                                          id="edit-description-indicator" placeholder="Description"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                                <input type="submit" value="Save changes" name="edit-indicator"
                                       class="btn btn-primary" id="edit-indicator-button">

                            </div><!-- /.modal-content -->
                        </form>
                    </div>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- end of load edit indicator modal -->
         </div>

         <!-- Add Target Button -->
         <div class="col-md-12" style="margin-top:20px">
            <div class="col-md-2">
                <a class="btn btn-primary" href="#add-targets-modal" data-toggle="modal" id="add-targets-link"
                   style="width:100%;">+ Add target</a>
            </div>
         </div>

         <!-- Add Target Modal -->
         <div id="add-targets-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                     <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Add New Target</h4>
                     </div>
                     <div class="modal-body">
                        <form id="add-targets-form" name="add_targets" method="POST">
                           <div class="form-group">
                              <label for="target-title">Target Title:</label>
                              <input name="target-title" type="text" class="form-control" id="target-title" placeholder="Target Title">
                           </div>

                           <div class="form-group">
                              <label for='sdg-type'>SDG:</label>
                              <select id="sdg-type" name="sdg-type" class="form-control" title="SDG is required">
                              <option value="">Select SDG</option>
                              <option id="1" value="poverty">1-Poverty</option>
                              <option id="2" value="zero-hunger">2-Zero hunger</option>
                              <option id="3" value="good-health-and-well-being">3-Good health and well being
                              </option>
                              <option id="4" value="quality-education">4-Quality education</option>
                              <option id="5" value="gender-equality">5-Gender equality</option>
                              <option id="6" value="clean-water-and-sanitation">6-Clean water and sanitation
                              </option>
                              <option id="7" value="affordable-and-clean-energy">7-Affordable and clean energy
                              </option>
                              <option id="8" value="decent-work-and-economic-growth">8-Decent work and economic
                                  growth
                              </option>
                              <option id="9" value="industry-innovation-and-infrastructure">9-Industry innovation
                                  and infrastructure
                              </option>
                              <option id="10" value="reduced-inequalities">10-Reduced Inequalities</option>
                              <option id="11" value="sustainable-cities-and-communities">11-Sustainable cities and
                                  communities
                              </option>
                              <option id="12" value="responsible-consumption-and-production">12-Responsible
                                  consumption and production
                              </option>
                              <option id="13" value="climate-action">13-Climate action</option>
                              <option id="14" value="life-below-water">14-Life below water</option>
                              <option id="15" value="life-on-land">15-Life on land</option>
                              <option id="16" value="peace-justice-and-strong-institutions">16-Peace justice and
                                  strong institutions
                              </option>
                              <option id="17" value="partnerships-for-the-goal">17-Partnerships for the goal
                              </option>
                           </select>
                           </div>

                           <div class="form-group">
                              <label for="description">Description:</label>
                              <textarea name="description" class="form-control" id="description" placeholder="Description"></textarea>
                           </div>

                           <div class="modal-footer">
                              <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                              <input type="submit" value="Save changes" name="createInd" class="btn btn-primary" id="add-targets-button">
                           </div>
                        </form>
                     </div>
                </div>
            </div>
         </div>

         <!-- Edit Target Modal -->
         <div id="edit-targets-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Edit target</h4>
                    </div>
                    <div class="modal-body">
                        <form id="edit-targets-form" name="edit_targets_form">
                            <div class="form-group">
                                <label for="edit-target-title">Targets:</label>
                                <input name="edit-target-title" type="text" class="form-control" id="edit-target-title"
                                       placeholder="Target">
                            </div>
                            <input  id="edit-target-id"/>
                            <div class="form-group">
                                <label for='edit-sdg-type'>SDG:</label>
                                <select id="edit-sdg-type" name="edit-sdg-type" class="form-control" title="SDG is required">
                                    <option id="1" value="poverty">1-Poverty</option>
                                    <option id="2" value="zero-hunger">2-Zero hunger</option>
                                    <option id="3" value="good-health-and-well-being">3-Good health and well being
                                    </option>
                                    <option id="4" value="quality-education">4-Quality education</option>
                                    <option id="5" value="gender-equality">5-Gender equality</option>
                                    <option id="6" value="clean-water-and-sanitation">6-Clean water and sanitation
                                    </option>
                                    <option id="7" value="affordable-and-clean-energy">7-Affordable and clean energy
                                    </option>
                                    <option id="8" value="decent-work-and-economic-growth">8-Decent work and economic
                                        growth
                                    </option>
                                    <option id="9" value="industry-innovation-and-infrastructure">9-Industry innovation
                                        and infrastructure
                                    </option>
                                    <option id="10" value="reduced-inequalities">10-Reduced Inequalities</option>
                                    <option id="11" value="sustainable-cities-and-communities">11-Sustainable cities and
                                        communities
                                    </option>
                                    <option id="12" value="responsible-consumption-and-production">12-Responsible
                                        consumption and production
                                    </option>
                                    <option id="13" value="climate-action">13-Climate action</option>
                                    <option id="14" value="life-below-water">14-Life below water</option>
                                    <option id="15" value="life-on-land">15-Life on land</option>
                                    <option id="16" value="peace-justice-and-strong-institutions">16-Peace justice and
                                        strong institutions
                                    </option>
                                    <option id="17" value="partnerships-for-the-goal">17-Partnerships for the goal
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="edit-sdg-description">Description:</label>
                                <textarea name="edit-sdg-description" class="form-control" id="edit-sdg-description" placeholder="Description"></textarea>
                            </div>

                            <div class="modal-footer">
                             <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                             <input type="submit" value="Save changes" name="createInd" class="btn btn-primary"
                                    id="edit-targets-button">

                            </div><!-- /.modal-content -->
                        </form>
                    </div>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- end of edit modal -->
        </div>

        <!-- Add Chart Modal -->
        <div id="add-chart-modal" class="modal fade" tabindex="-1">
           <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <button class="close" type="button" data-dismiss="modal">x</button>
                       <h4 class="modal-title">Add Chart</h4>
                   </div>
                   <div class="modal-body">
                       <form id="add-chart-form" name="add-chart">
                           <input id="chart-target-id" >
                           <input id="chart-indicator-id">
                           <input id="chart-sdg-id">
                           <div class="form-group">
                               <label for="title-chart">Title:</label>
                               <input name="title-chart" type="text" class="form-control"
                                         id="title-chart" placeholder="Title"></input>
                           </div>
                           <!-- Target data panel -->
                           <div class="form-group">
                             <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h3 class="panel-title chart-target-panel">Target Data</h3>
                                </div>
                                <div class="panel-body">
                                  <div class="form-group">
                                      <label for="date">Target Date:</label>
                                      <div class="input-group ">
                                          <input name="date" type="text" class="col-md-12 col-sm-12 col-xs-12 date-chart" id="target-date-chart" placeholder="Date">
                                          <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar" onkeydown="return false"></span></span>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                     <label for='chart-target-unit'>Unit:</label>
                                     <select id="chart-target-unit-select" name="chart-target-unit" class="form-control">
                                        <option value="">Select Unit</option>
                                        <option id="target-number" value="number" data-show="number"> Number</option>
                                        <option id="target-percentage" value="ercentage"  data-show="percentage"> Percentage </option>
                                        <option id="target-boolean" value="boolean"  data-show="boolean"> Yes/No </option>
                                        <option id="target-comperative" value="omperative"  data-show="comperative">Comperative Value</option>
                                        <option id="target-ratio" value="ratio"  data-show="ratio">Ratio</option>
                                      </select>
                                  </div>

                                  <div class="form-group target-unit-select tu-number">
                                    <label for="number-value">Number Value:</label>
                                    <input name="number-value" type="number" class="form-control" id="number-value" placeholder="Number"/>
                                  </div>
                                  <div class="form-group target-unit-select tu-percentage">
                                    <label for="percentage-value">Percentage Value:</label>
                                    <input name="percentage-value" type="number" class="form-control" id="percentage-value" placeholder="Percentage"/>
                                  </div>
                                  <div class="form-group form-inline target-unit-select tu-ratio">
                                    <label for="ratio-value">Ratio Values:</label>
                                    <input name="ratio-value-a" type="number" class="form-control" id="ratio-value-a" placeholder="A"/> :
                                    <input name="ratio-value-b" type="number" class="form-control" id="ratio-value-b" placeholder="B"/>
                                  </div>
                                  <div class="form-group form-inline target-unit-select tu-boolean">
                                    <label class="radio-inline">
                                      <input type="radio" name="boolean-true" value="true">True
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="boolean-true" value="false"> False
                                    </label>
                                  </div>
                                  <div class="form-group form-inline target-unit-select tu-comperative">
                                    <label for="comperative-value">Comperative Values:</label>
                                    <input name="comperative-value-a" type="number" class="form-control" id="comperative-value-a" placeholder="Current Value"/>
                                    <input name="comperative-value-b" type="number" class="form-control" id="comperative-value-b" placeholder="Maximum Value"/>
                                  </div>

                                </div>
                              </div>
                           </div>
                           <!-- End of Target data panel  -->
                           <!-- Chart data panel -->
                           <div class="form-group">
                             <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h3 class="panel-title chart-target-panel">Chart Data</h3>
                                </div>
                                <div class="panel-body">
                                  <div class="form-group">
                                      <label for="date"> Date:</label>
                                      <div class="input-group ">
                                          <input name="date" type="text"
                                                 class="col-md-12 col-sm-12 col-xs-12 date-chart" id="date-chart"
                                                 placeholder="Date">
                                          <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar" onkeydown="return false"></span></span>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="chart-disaggregated-by">Disaggregated by:</label>
                                    <input name="chart-disaggregated-by" type="text" class="form-control" id="chart-disaggregated-by" placeholder=""/>
                                  </div>
                                  <div class="form-group">
                                     <label for='chart-data-unit'>Unit:</label>
                                     <select id="chart-data-unit-select" name="chart-data-unit" class="form-control">
                                        <option value="">Select Unit</option>
                                        <option id="chart-data-number" value="number" data-show="number"> Number</option>
                                        <option id="chart-data-percentage" value="ercentage"  data-show="percentage"> Percentage </option>
                                        <option id="chart-data-boolean" value="boolean"  data-show="boolean"> Boolean </option>
                                        <option id="chart-data-comperative" value="omperative"  data-show="comperative">Comperative Value</option>
                                        <option id="chart-data-ratio" value="ratio"  data-show="ratio">Ratio</option>
                                        <option id="chart-data-increasing-decreasing" value="increasing-decreasing"  data-show="increasing-decreasing"> Increasing/Decreasing </option>
                                      </select>
                                  </div>

                                  <div class="form-group chart-unit-select chu-number">
                                    <label for="number-value">Number:</label>
                                    <input name="number-value" type="number" class="form-control" id="number-value" placeholder="Number"/>
                                  </div>
                                  <div class="form-group chart-unit-select chu-percentage">
                                    <label for="chart-data-value">Percentage:</label>
                                    <input name="chart-data-value" type="number" class="form-control" id="ch-percentage-value" placeholder="Percentage"/>
                                  </div>
                                  <div class="form-group form-inline chart-unit-select chu-ratio">
                                    <label for="ratio-value">Ratio:</label>
                                    <input name="ratio-value-a" type="number" class="form-control" id="chu-ratio-value-a" placeholder="A"/> :
                                    <input name="ratio-value-b" type="number" class="form-control" id="chu-ratio-value-b" placeholder="B"/>
                                  </div>
                                  <div class="form-group form-inline chart-unit-select chu-boolean">
                                    <label class="radio-inline">
                                      <input type="radio" name="boolean-true" id="chu-coolean-true" value="true">True
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="boolean-true" id="chu-coolean-true" value="false"> False
                                    </label>
                                  </div>
                                  <div class="form-group form-inline chart-unit-select chu-comperative">
                                    <label for="comperative-value">Comperative Values:</label>
                                    <input name="comperative-value-a" type="number" class="form-control" id="chu-comperative-value-a" placeholder="Current Value"/>
                                    <input name="comperative-value-b" type="number" class="form-control" id="chu-comperative-value-b" placeholder="Maximum Value"/>
                                  </div>
                                  <div class="form-group form-inline chart-unit-select chu-increasing-decreasing">
                                    <label for="increasing-decreasing-value">Increasing / Decreasing:</label>
                                    <input name="increasing-decreasing-value-a" type="number" class="form-control" id="chu-increasing-value" placeholder="Increasing"/> :
                                    <input name="increasing-decreasing-value-b" type="number" class="form-control" id="chu-decreasing-value" placeholder="Decreasing"/>
                                  </div>

                                </div>
                              </div>
                           </div>
                           <!-- End of Chart data panel  -->
                           <div class="form-group">
                               <label for="description-indicator">Description:</label>
                               <textarea name="description-indicator" type="text" class="form-control"
                                         id="description-chart" placeholder="Description"></textarea>
                           </div>
                           <div class="modal-footer">
                               <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                               <input type="submit" value="Save changes" name="add-chart-button" class="btn btn-primary"
                                      id="add-chart-button">

                           </div><!-- /.modal-content -->
                       </form>
                   </div>
               </div><!-- /.modal-dialog -->
           </div><!-- /.modal -->
           <!-- end of indicator modal -->
        </div>

      </div>
</div>

<script type="text/javascript" charset="utf-8">


   function fnFormatDetails(table_id, html) {
      var sOut = "<table id='exampleTable_" + table_id + "'class='table-bordered dataTable no-footer' role='grid' aria-describedby='chartTable_info'>";
      sOut += html;
      sOut += "</table>";
      return sOut;
   }


  function fnFormatCharts(table_id, html) {
     var sOut = "<table id='chartTable_" + table_id + "'class='table-bordered dataTable no-footer' role='grid' aria-describedby='chartTable_info'>";
     sOut += html;
     sOut += "</table>";
     return sOut;
  }
    var newRowData = <?php echo json_encode($query_targets); ?>;
    //console.log(newRowData);

    var iTableCounter = 1;
    var oTable;
    var oInnerTable;
    var oInnerInnerTable;
    var detailsTableHtml;
    var chartTableHtml;

    //Run On HTML Build
    $(document).ready(function () {

      // Hide all taget unit fields and show them based on selected unit
      $('.target-unit-select').hide();
      $('#chart-target-unit-select').change(function() {
         $('.target-unit-select').hide();
         $('.tu-' + $('option:selected', this).data('show')).show();
      });
      // Hide all chart unit fields and show them based on selected unit
      $('.chart-unit-select').hide();
      $('#chart-data-unit-select').change(function() {
         $('.chart-unit-select').hide();
         $('.chu-' + $('option:selected', this).data('show')).show();
      });

        // Target date with datepicker
        $('#target_date').datepicker({dateFormat: "mm/dd/yy"});

        // Adding new target from modal
        $('#add-targets-form').on('submit', function (e) {
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                type: 'POST',
                dataType: 'json',
                data: {
                    'title': $('#target-title').val(),
                    'description': $('#description').val(),
                    'sdg_id': $("#sdg-type").children(":selected").attr("id"),
                    'action': 'add_targets'
                },
                success: function (data) {
                    // Setting the new target id in hidden field
                    var targets_id = $('#edit-target-id').val();
                    oTable.fnClearTable(0);
                    oTable.fnAddData(data);
                    oTable.fnDraw();
                    $('.form-control').val('');
                    $('#add-targets-modal').modal('hide');
                }
            });
            e.preventDefault();
        });

        // Edit existing target from modal
        $('#edit-targets-form').validate({
            rules: {
                // edit-target-title: {
                //     required: true,
                // },
                // edit_add_sdg: {
                //     required: true,
                // },
                // edit_add_unit: {
                //     required: true,
                // },
            },
            submitHandler: function (form) {
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                    type: 'POST', //or POST
                    dataType: 'json',
                    data: {
                        'target_id': $('#edit-target-id').val(),
                        'description': $('#edit-sdg-description').val(),
                        'title': $('#edit-target-title').val(),
                        'sdg_id': $("#edit-sdg-type").children(":selected").attr("id"),
                        'action': 'update_target'
                    },
                    success: function (data) {
                        var targets_id = $('#edit-target-id').val();
                        oTable.fnClearTable(0);
                        oTable.fnAddData(data);
                        oTable.fnDraw();
                        $('#edit-targets-modal').modal('hide');
                    }
                });
            }
        });

        // Get the Indicator table example for indicator table
        detailsTableHtml = $("#detailsTable").html();

        // Get the Charts table example for indicator table
        chartTableHtml = $("#chartsTable").html();

        //Insert a 'details' column to the table
        var nCloneTh = document.createElement('th');
        var nCloneTd = document.createElement('td');

        // Making table data as "+" sign
        nCloneTd.innerHTML = '<img src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-table" style="width:20px"/>';
        nCloneTd.className = "text-center";

        // Adding an empty column table head for "+" and "-"
        $('#exampleTable thead tr').each(function() {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        // This will add "+" sign for each row in first data column
        $('#exampleTable tbody tr').each(function(index, element) {
           console.log(element);
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
        });

        // Initialize the sub sub table for charts
        function init_sub_sub_table(){
          $('body').on('click', '.show-sub-sub-table', function (e) {

            e.preventDefault();

            // Getting the ID of clicked target's "+"
            var indicator_id = $($(this).parent().parent().children()[1]).text();

            // // Getting the ID of clicked SDG's "+"
            var target_id = $($(this).parent().parent().children()[6]).text();

            // Getting the row of target
            var nTr = $(this).parents('tr')[0];

            // Getting the "+" sign of target
            var nTds = this;

            // Checking the table if it's opened or closed for "+" and "-"
            if (oInnerTable.fnIsOpen(nTr)) {
                /* This row is already open - close it */
                // If the sub table is closed make the "-" to "+"
                this.src = '<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>';
            }
            else {
                // If the sub table is opened make the "+" to "-"
                this.src = '<?php echo SDGS__PLUGIN_URL . 'img/minus.png' ?>';
            }
            // GET Request for rendering chart table

            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                type: 'GET',
                dataType: 'json',
                data: {'id': indicator_id, 'target_id': target_id, 'action': 'get_target_indicator_charts'},
                success: function (data) {
                    // Checking if table is closed or opened
                    if (oInnerTable.fnIsOpen(nTr)) {
                        /* This row is already open - close it */
                        this.src = '<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>';
                        this.id = indicator_id;
                        oInnerTable.fnClose(nTr);
                    }
                    // Opened
                    else {
                        // Changing the plus to minus
                        this.src = '<?php echo SDGS__PLUGIN_URL . 'img/minus.png' ?>';

                        // Adding new row below the indicator row for inner table
                        oInnerTable.fnOpen(nTr, fnFormatCharts(indicator_id + '_' + target_id, chartTableHtml), 'chart-details');

                        // Rendering the chart data in inner table of selected indicator
                        oInnerInnerTable = $("#chartTable_" + indicator_id + '_' + target_id).dataTable({
                            "bJQueryUI": true,
                            "bFilter": true,
                            "aaData": data,
                            "bSort": true, // disables sorting
                            "info": true,
                            "aoColumns": [
                                {"mDataProp": "id"},
                                {"mDataProp": "title"},
                                {"mDataProp": "unit"},
                                {"mDataProp": "target_date"},
                                {"mDataProp": "target_value"},
                                {"mDataProp": "chart_data"},
                                {"mDataProp": "description"},
                                {"mDataProp": "disaggregated_by"},
                                {"sDefaultContent": "<a data-toggle='modal' href='#edit-chart-modal' class='edit-chart-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-chart'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
                                {"sDefaultContent": target_id},
                                {"sDefaultContent": indicator_id},
                            ],
                            "bPaginate": true,
                            "oLanguage": {
                                "sInfo": "_TOTAL_ entries"
                            },
                            "dom": 'Bfrtip',
                            "buttons": [
                                {
                                    "extend": 'copyHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'excelHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'pdfHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'csvHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                }
                            ],
                            "columnDefs": [
                                    {
                                        "targets": [ 9,10 ],
                                        className: 'hidden'
                                    }
                                ],
                        });

                        $(this).attr('id', indicator_id);
                        // Updating the info of datatable with the button to create new indicator
                        $('tr.chart-details .dataTables_info').html(''); //data-indicator='" + indicator_id + "' data-target='" + target_id  + "'
                        $('tr.chart-details .dataTables_info').append("<a data-toggle='modal' href='#add-chart-modal' class='add-chart btn btn-primary'> + Add Chart </a>");
                    }
                }
            });
        });
        }

        // Initialize the sub table if the plus is clicked
        function init_sub_table() {
            $('body').on('click', '.show-sub-table', function (e) {
                e.preventDefault();

                // Getting the ID of clicked target's "+"
                var targets_id = $($(this).parent().parent().children()[1]).text();

                // Getting the ID of clicked SDG's "+"
                var s_id = $($(this).parent().parent().children()[3]).text();

                // Getting the row of target
                var nTr = $(this).parents('tr')[0];

                // Getting the "+" sign of target
                var nTds = this;

                // Checking the table if it's opened or closed for "+" and "-"
                if (oTable.fnIsOpen(nTr)) {
                    /* This row is already open - close it */
                    // If the sub table is closed make the "-" to "+"
                    this.src = '<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>';
                }
                else {
                    // If the sub table is opened make the "+" to "-"
                    this.src = '<?php echo SDGS__PLUGIN_URL . 'img/minus.png' ?>';
                }

                // GET Request for rendering indicator table
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                    type: 'GET',
                    dataType: 'json',
                    data: {'id': targets_id, 'action': 'get_targets_indicators'},
                    success: function (data) {
                        // Checking if table is closed or opened
                        if (oTable.fnIsOpen(nTr)) {
                            /* This row is already open - close it */
                            this.src = '<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>';
                            this.id = targets_id;
                            oTable.fnClose(nTr);
                        }
                        // Opened
                        else {
                            // Changing the plus to minus
                            this.src = '<?php echo SDGS__PLUGIN_URL . 'img/minus.png' ?>';

                            // Adding new row below the target row for inner table
                            oTable.fnOpen(nTr, fnFormatDetails(targets_id, detailsTableHtml), 'details');

                            // Rendering the indicator data in inner table
                            oInnerTable = $("#exampleTable_" + targets_id).dataTable({
                                "bJQueryUI": true,
                                "bFilter": true,
                                "aaData": data,
                                "bSort": true, // disables sorting
                                "info": true,
                                "aoColumns": [
                                    {"sDefaultContent": '<img src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-sub-table" style="width:20px"/>'},
                                    {"mDataProp": "id"},
                                    {"mDataProp": "title"},
                                    {"mDataProp": "source"},
                                    {"mDataProp": "description"},
                                    {"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
                                    {"sDefaultContent": targets_id},
                                ],
                                "bPaginate": true,
                                "oLanguage": {
                                    "sInfo": "_TOTAL_ entries"
                                },
                                "dom": 'Bfrtip',
                                "buttons": [
                                    {
                                        "extend": 'copyHtml5',
                                        "exportOptions": {
                                            "columns": [1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        "extend": 'excelHtml5',
                                        "exportOptions": {
                                            "columns": [1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        "extend": 'pdfHtml5',
                                        "exportOptions": {
                                            "columns": [1, 2, 3, 4, 5]
                                        }
                                    },
                                    {
                                        "extend": 'csvHtml5',
                                        "exportOptions": {
                                            "columns": [1, 2, 3, 4, 5]
                                        }
                                    }
                                ],
                                "columnDefs": [
                                        {
                                            "targets": [ 6 ],
                                            className: 'hidden'
                                        }
                                    ],
                            });

                            $(this).attr('id', targets_id);
                            // Updating the info of datatable with the button to create new indicator
                            $('tr.details .dataTables_info').html('');
                            $('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + targets_id + "' data-sdg='" + s_id + "' href='#add-indicator-modal' class='add-indicator btn btn-primary'>+ Add Indicator</a>");
                        }
                    }
                });
            });
        }

        // Initialize the main datatable
        function init_table(newRowData) {


            oTable = $('#exampleTable').dataTable({

                "bJQueryUI": true,
                "aaData": newRowData,
                "bPaginate": true,
                "order": [1, 'asc'],
                "aoColumns": [
                    {
                        "mDataProp": null,
                        "sClass": "control text-center",
                        "sDefaultContent": '<img title="Indicators" src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-table" style="width:20px;"/>'
                    },
                    {"mDataProp": "id"},
                    {"mDataProp": "title"},
                    {"mDataProp": "short_name"},
                    {"mDataProp": "updated_date"},
                    {"mDataProp": "description"},
                    {"sDefaultContent": "<a data-toggle='modal' href='#edit-targets-modal' class='edit-modal-targets' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-targets'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
                ],
                "oLanguage": {
                    "sInfo": "_TOTAL_ entries"
                },
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        "extend": 'copyHtml5',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        "extend": 'excelHtml5',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        "extend": 'pdfHtml5',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        "extend": 'csvHtml5',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    }
                ],
                "aaSorting": [[7, 'desc']]
            });

        }

        // Invoking the initialize function for main datatable, passing the JSON with all targets from query
        init_table(newRowData);

        // Invoking the sub_sub_table function when plus is clicked
        init_sub_sub_table();

        // Invoking the sub_table function when plus is clicked
        init_sub_table();

        $('.date-chart').datepicker({dateFormat: "mm/dd/yy"});

        // Add New Indicator
        $('body').on('click', '.add-indicator', function (e) {

            // Get clicked targets ID
            var targets_id = $(this).attr('id');

            // Get clicked SDG ID
            var sdg_id = $(this).attr('data-sdg');

            // Set measurement SDG ID
            $('#indicator-sdg').val(sdg_id);

            // Set measurement targets ID
            $('#indicator-target-id').val(targets_id);

            // Get the measurements table id
            var table_id = $(this).parent()[0].id.replace('_info', '');

            // Get unavailable dates by getting the dates column array of the measurements dates
            var unavailableDates = $('#' + table_id).DataTable().columns(1).data()[0];

            // Add unavailable dates option on the calendar view.
            $('.date-chart').datepicker('option', 'beforeShowDay', get_unavailable_dates);

            // Unavailable dates generation
            function get_unavailable_dates(date) {
                // Get month
                var month = date.getMonth() + 1;

                // Get day
                var day = date.getDate();

                // Modify month value by adding a 0 before if it's from 1-9
                if (month < 10) {
                    month = '0' + month;
                }

                // Modify day value by adding a 0 before if it's from 1-9
                if (day < 10) {
                    day = '0' + day;
                }

                // Generate the date
                var dmy = month + "/" + day + "/" + date.getFullYear();

                // Check if date is in unavailable arrays and disable or enable otherwise
                if ($.inArray(dmy, unavailableDates) < 0) {
                    return [true, "", "Choose date"];
                } else {
                    return [false, "", "There is a measurement with the same date."];
                }
            }

            e.preventDefault();
        });

        // Getting the data to edit
        $('body').on('click', '.edit-modal-indicator', function (e) {
            e.preventDefault();

            var indicator_id = $($(this).parent().parent().children()[1]).text();
            $.ajax({
               type: "POST",
               data: {'id': + indicator_id, 'action': 'load_indicator_selected'},
               dataType: 'json',
               url: "<?php echo admin_url('admin-ajax.php'); ?>",
               success: function (data) {
                  $('#edit-title-indicator').val(data[0].title);
                  $('#edit-source-indicator').val(data[0].source);
                  $('#edit-description-indicator').val(data[0].description);
                  $('#edit-indicator-id').val(data[0].id);
                  $('#edit-indicator-sdg').val(data[0].sdg_id);
               }
            });
        });
        // Fixing data to edit
        $('body').on('click', '.edit-modal-indicator', function (e) {
            // Get clicked targets ID
            var targets_id = $(this).attr('id');

            // Get clicked SDG ID
            var sdg_id = $(this).attr('data-sdg');

            // Set measuremend SDG ID
            $('#indicator-sdg').val(sdg_id);

            // Set measurement targets ID
            $('#indicator-target-id').val(targets_id);

            // Get the current date of the measurement
            var currentDate = $($($(this)[0]).parent().parent().children()[1]).text();

            // Put current date value on the date input
            $('.edit-date-chart').val(currentDate);

            // Get the measurements table id
            var table_id = $($(this)[0]).parent().parent().parent().parent()[0].id;

            // Get unavailable dates by getting the dates column array of the measurements dates
            var unavailableDates = $('#' + table_id).DataTable().columns(1).data()[0];

            // Add unavailable dates option on the calendar view.
            $('.date-chart').datepicker('option', 'beforeShowDay', unavailable_dates);

            // Unavailable dates generation
            function unavailable_dates(date) {
                // Get month
                var month = date.getMonth() + 1;

                // Get day
                var day = date.getDate();

                // Modify month value by adding a 0 before if it's from 1-9
                if (month < 10) {
                    month = '0' + month;
                }

                // Modify day value by adding a 0 before if it's from 1-9
                if (day < 10) {
                    day = '0' + day;
                }

                // Generate the date
                var dmy = month + "/" + day + "/" + date.getFullYear();

                // Check if date is in unavailable arrays and disable or enable otherwise
                if ($.inArray(dmy, unavailableDates) < 0) {
                    return [true, "", "Choose date"];
                } else {
                    if (dmy == currentDate) {
                        return [true, "", "Choose date"];
                    } else {
                        return [false, "", "There is a measurement with the same date."];
                    }
                }
            }

            e.preventDefault();
        });

        // Adding new chartTable_
        // $("#add-chart-modal").validate({
        //   rules: {
        //       name: {
        //           required: true,
        //       }
        //   },
        //   submitHandler: function (form) {
        //
        //      var indicator_id = $('#indicator-target-id').val();
        //      var target_id = $('#indicator-target-id').val();
        //      var sdg_id = $('#sdg-id').val();
        //       $.ajax({
        //           url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
        //           type: 'POST', //or POST
        //           dataType: 'json',
        //           data: {
        //               'sdg_id': $('#indicator-sdg').val(),
        //               'target_id': $('#indicator-target-id').val(),
        //               'indicator_id': $('#indicator-target-id').val(),
        //               'title': $('#title-indicator').val(),
        //               'source': $("#source-indicator").val(),
        //               'description': $("#description-indicator").val(),
        //               'action': 'add_chart'
        //           },
        //           success: function (data) {
        //               var target_id = data[0].target_id;
        //               var s_id = data[0].sdg_id;
        //               $('#exampleTable_' + targets_id).dataTable().fnDestroy();
        //               oInnerTable = $("#exampleTable_" + targets_id).dataTable({
        //                   "bFilter": true,
        //                   "aaData": data,
        //                   "bSort": true, // disables sorting
        //                   "aoColumns": [
        //                       {"sDefaultContent": '<img src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-sub-table" style="width:20px"/>'},
        //                       {"mDataProp": "id"},
        //                       {"mDataProp": "title"},
        //                       {"mDataProp": "source"},
        //                       {"mDataProp": "description"},
        //                       {"sDefaultContent": "<a data-toggle='modal' href='#edit-chart-modal' class='edit-modal-chart' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-chart'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
        //                       {"sDefaultContent": targets_id},
        //                   ],
        //                   "bPaginate": true,
        //                   "oLanguage": {
        //                       "sInfo": "_TOTAL_ entries"
        //                   },
        //                   "dom": 'Bfrtip',
        //                   "buttons": [
        //                       {
        //                           "extend": 'copyHtml5',
        //                           "exportOptions": {
        //                               "columns": [1, 2, 3, 4, 5]
        //                           }
        //                       },
        //                       {
        //                           "extend": 'excelHtml5',
        //                           "exportOptions": {
        //                               "columns": [1, 2, 3, 4, 5]
        //                           }
        //                       },
        //                       {
        //                           "extend": 'pdfHtml5',
        //                           "exportOptions": {
        //                               "columns": [1, 2, 3, 4, 5]
        //                           }
        //                       },
        //                       {
        //                           "extend": 'csvHtml5',
        //                           "exportOptions": {
        //                               "columns": [1, 2, 3, 4, 5]
        //                           }
        //                       }
        //                   ],
        //                   "columnDefs": [
        //                           {
        //                               "targets": [ 6 ],
        //                               className: 'hidden'
        //                           }
        //                       ],
        //
        //               });
        //               $('tr.details1 .dataTables_info').html('');
        //               $('tr.details1 .dataTables_info').append("<a data-toggle='modal' id='" + targets_id + "' data-sdg='" + s_id + "' href='#add-indicator-modal' class='add-indicator btn btn-primary'>+ Add indicator</a>");
        //               $('#add-chart-modal').modal('hide');
        //               $('#add-chart-form')[0].reset();
        //
        //           }
        //       });
        //   }
        // });


        // Adding new Indicator
        $('#add-indicator-form').validate({
            rules: {
                name: {
                    required: true,
                }
            },
            submitHandler: function (form) {

               var targets_id = $('#indicator-target-id').val();
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                    type: 'POST', //or POST
                    dataType: 'json',
                    data: {
                        'sdg_id': $('#indicator-sdg').val(),
                        'target_id': $('#indicator-target-id').val(),
                        'title': $('#title-indicator').val(),
                        'source': $("#source-indicator").val(),
                        'description': $("#description-indicator").val(),
                        'action': 'add_indicator' //add_measurement
                    },
                    success: function (data) {
                        var target_id = data[0].target_id;
                        var s_id = data[0].sdg_id;
                        $('#exampleTable_' + targets_id).dataTable().fnDestroy();
                        oInnerTable = $("#exampleTable_" + targets_id).dataTable({
                            "bFilter": true,
                            "aaData": data,
                            "bSort": true, // disables sorting
                            "aoColumns": [
                                {"sDefaultContent": '<img src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-sub-table" style="width:20px"/>'},
                                {"mDataProp": "id"},
                                {"mDataProp": "title"},
                                {"mDataProp": "source"},
                                {"mDataProp": "description"},
                                {"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
                                {"sDefaultContent": targets_id},
                            ],
                            "bPaginate": true,
                            "oLanguage": {
                                "sInfo": "_TOTAL_ entries"
                            },
                            "dom": 'Bfrtip',
                            "buttons": [
                                {
                                    "extend": 'copyHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'excelHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'pdfHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'csvHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                }
                            ],
                            "columnDefs": [
                                    {
                                        "targets": [ 6 ],
                                        className: 'hidden'
                                    }
                                ],

                        });
                        $('tr.details .dataTables_info').html('');
                        $('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + targets_id + "' data-sdg='" + s_id + "' href='#add-indicator-modal' class='add-indicator btn btn-primary'>+ Add indicator</a>");
                        $('#add-indicator-modal').modal('hide');
                        $('#add-indicator-form')[0].reset();

                    }
                });
            }
        });

        // Posting the edited Data
        $('#edit-indicator-form').validate({
            rules: {
                name: {
                    required: true,

                }
            },
            submitHandler: function (form) {
                var indicator_id = $('#edit-indicator-id').val();
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                    type: 'POST', //or POST
                    dataType: 'json',
                    data: {
                        'indicator_id': indicator_id,
                        'title': $('#edit-title-indicator').val(),
                        'description': $('#edit-description-indicator').val(),
                        'source': $("#edit-source-indicator").val(),
                        'action': 'edit_indicator'
                    },
                    success: function (data) {
                        var target_id = data[0].target_id;
                        var sdg_id = data[0].sdg_id;
                        $('#exampleTable_' + target_id).dataTable().fnDestroy();
                        oInnerTable = $("#exampleTable_" + target_id).dataTable({
                            "bJQueryUI": true,
                            "aaData": data,
                            "bSort": true, // disables sorting
                            "aoColumns": [
                                {"sDefaultContent": '<img src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-sub-table" style="width:20px"/>'},
                                {"mDataProp": "id"},
                                {"mDataProp": "title"},
                                {"mDataProp": "source"},
                                {"mDataProp": "description"},
                                {"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
                                {"sDefaultContent": target_id},
                            ],
                            "bPaginate": true,
                            "oLanguage": {
                                "sInfo": "_TOTAL_ entries"
                            },
                            "dom": 'Bfrtip',
                            "buttons": [
                                {
                                    "extend": 'copyHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'excelHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'pdfHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                },
                                {
                                    "extend": 'csvHtml5',
                                    "exportOptions": {
                                        "columns": [1, 2, 3, 4, 5]
                                    }
                                }
                            ],
                            "columnDefs": [
                                    {
                                        "targets": [ 6 ],
                                        className: 'hidden'
                                    }
                                ],

                        });
                        $('tr.details .dataTables_info').html('');
                        $('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + target_id + "' data-sdg='" + sdg_id + "' href='#add-indicator-modal' class='add-indicator btn btn-primary'>+ Add indicator</a>");
                        $('#edit-indicator-modal').modal('hide');
                        $('.form-control').val('');
                    }
                });
            }
        });

        // Getting the target data to edit
        $('body').on('click', '.edit-modal-targets', function (e) {
            var targets_id = $($(this).parent().parent().children()[1]).text();

            $.ajax({
                type: "POST",
                data: {'id': targets_id, 'action': 'get_targets'},
                dataType: 'json',
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                success: function (data) {
                  console.log("D", data);
                    $('#edit-target-id').val(data[0].id);
                    $('#edit-target-title').val(data[0].title);
                    $('#edit-sdg-type option[value="' + data[0].short_name + '"]').attr('selected', 'selected');
                    $('#edit-sdg-description').val(data[0].description);
                },
                error: function (errorThrown) {
                    alert(errorThrown);
                }
            });
            e.preventDefault();
        })

        // Remove target
        $('body').on('click', '.remove-targets', function (e) {
            e.preventDefault();
            var targets_id = $($(this).parent().parent().children()[1]).text();
            var check_if_is_empty = 0;
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                type: 'POST', //or POST
                dataType: 'json',
                data: {'id': targets_id, 'action': 'check_targets_is_empty'},
                success: function (data) {
                    check_if_is_empty = data['a'];
                    if (check_if_is_empty == 1) {
                        BootstrapDialog.show({
                            message: 'The targets has measurements, are you sure you want to delete',
                            buttons: [{
                                icon: 'glyphicon glyphicon-send',
                                label: 'OK',
                                cssClass: 'btn-primary',
                                autospin: false,
                                action: function (dialogRef) {
                                    $.ajax({
                                        url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                                        type: 'POST', //or POST
                                        dataType: 'json',
                                        data: {'id': targets_id, 'action': 'remove_targets_measurements'},
                                        success: function (data) {
                                            oTable.fnClearTable(0);
                                            oTable.fnAddData(data);
                                            oTable.fnDraw();
                                        }
                                    });
                                    setTimeout(function () {
                                        dialogRef.close();
                                    }, 100);
                                }
                            }, {
                                label: 'Close',
                                action: function (dialogRef) {
                                    dialogRef.close();
                                }
                            }]
                        });
                    } else {
                        BootstrapDialog.show({
                            message: 'Are you sure you want to delete the targets?',
                            buttons: [{
                                icon: 'glyphicon glyphicon-send',
                                label: 'OK',
                                cssClass: 'btn-primary',
                                autospin: false,
                                action: function (dialogRef) {
                                    $.ajax({
                                        url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                                        type: 'POST', //or POST
                                        dataType: 'json',
                                        data: {'id': targets_id, 'action': 'remove_targets'},
                                        success: function (data) {
                                            oTable.fnClearTable(0);
                                            oTable.fnAddData(data);
                                            oTable.fnDraw();

                                        }
                                    });
                                    setTimeout(function () {
                                        dialogRef.close();
                                    }, 100);
                                }
                            }, {
                                label: 'Close',
                                action: function (dialogRef) {
                                    dialogRef.close();
                                }
                            }]
                        });
                    }
                }
            });


        });

        // Remove indicator
        $('body').on('click', '.remove-indicator', function (e) {
            e.preventDefault();
            var row = $($(this).parent().parent());
            var indicator_id = $($(this).parent().parent().children()[1]).text();

            BootstrapDialog.show({
                message: 'Are you sure you want to delete the indicator?',
                buttons: [{
                    icon: 'glyphicon glyphicon-send',
                    label: 'OK',
                    cssClass: 'btn-primary',
                    autospin: false,
                    action: function (dialogRef) {
                        $.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
                            type: 'POST', //or POST
                            dataType: 'json',
                            data: {
                                'id': indicator_id,
                                'action': 'remove_indicator'
                            },
                            success: function(data) {
                            }
                        });
                        setTimeout(function () {
                            dialogRef.close();
                            row.fadeOut().remove();
                        }, 100);
                    }
                }, {
                    label: 'Close',
                    action: function (dialogRef) {
                        dialogRef.close();
                    }
                }]
            });
         });

        var targets_array = <?php echo json_encode($query_targets); ?>;
      //   console.log(targets_array);
    });
</script>

<style>
    #exampleTable_wrapper .col-sm-6 {
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .show-sub-table, .show-sub-sub-table {
        cursor: pointer;
    }

    #date-chart-error, #edit-date-chart-error {
        display: block;
        clear: both;
        position: relative;
        float: right;
        clear: right;
        float: left;
        margin-top: -58px;
        margin-left: 56px;
    }

    .blue-back {
     background: #00a0d2;
    }

    .btn {
        border-radius: 0px;
    }

    table.dataTable {
      border-color: #fff;
    }

    #exampleTable thead tr {
        background: #00a0d2;
    }

    #exampleTable thead tr th {
        color: #fff;
    }

    table.dataTable.no-footer {
      border: none;
   }

    table.dataTable thead th, table.dataTable thead td {
      border-color: #ddd!important;
   }

    .modal-open .modal {
        margin-top: 20px;
    }

    .modal-header {
        background: #428bca;
        color: #ffffff;
    }

    a:focus {
        -webkit-box-shadow: 0px;
    }

    .wp-admin select {
        height: 34px;
    }
    .inline {
        display:inline-block;
    }
</style>
<script>
    $('.number-values').keypress(function (eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this) == 0)) {
            eve.preventDefault();
        }
    })

</script>
