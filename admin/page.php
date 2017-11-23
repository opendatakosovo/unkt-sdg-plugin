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
<div class="">
    <?php
       $args = [
           'post_type' => 'page',
           'meta_key' => '_wp_page_template',
           'meta_value' => 'templates/SDG_Page.php'
       ];
       $pages = get_posts($args);
    ?>

    <div class="col-xs-10">
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

<div class="wrap" style="margin-top:60px; height:auto; min-height:1000px;">

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
        <table id="chartsTable" class="table-bordered chart-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Target Unit</th>
                    <th>Target Year</th>
                    <th>Target Value</th>
                    <th>Chart Unit</th>
                    <th>Chart Data</th>
                    <th>Description</th>
                    <th>General Label</th>
                    <th>Actions</th>
                    <th></th>
                    <th></th>
                    <th></th>
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
                    <th>Indicator Id</th>
                    <th>SDG</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div style="padding-left: 0px;" class="col-md-12">
        <!-- Add Indicator Modal -->
        <div id="add-indicator-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header indicator-color">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Add Indicator</h4>
                    </div>
                    <div class="modal-body">
                        <form id="add-indicator-form" name="add-indicator-form" method="POST">
                            <input type="hidden" id="indicator-target-id"/>
                            <input type="hidden" id="indicator-sdg"/>
                            <div class="form-group">
                                <label for="title-indicator">Title</label>
                                <input name="title-indicator" type="text" class="form-control" id="title-indicator" required/>
                            </div>
                            <div class="form-group">
                                <label for="source-indicator">Source</label>
                                <input name="source-indicator" type="text" class="form-control" id="source-indicator"/>
                            </div>
                            <div class="form-group">
                                <label for="description-indicator">Description</label>
                                <textarea name="description-indicator" type="text" class="form-control" id="description-indicator"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                                <input type="submit" value="Save changes" name="add-measurement" class="btn btn-primary indicator-color" id="add-measuremnt-button"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of indicator modal -->

        <!-- Start of Edit Indicator Modal -->
        <div id="edit-indicator-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header indicator-color">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Edit Indicator</h4>
                    </div>
                    <div class="modal-body">
                        <form id="edit-indicator-form" name="edit-indicator">
                            <input type="hidden" id="edit-indicator-id" />
                            <input type="hidden" id="edit-indicator-sdg" />
                            <div class="form-group">
                                <label for="title-indicator">Name</label>
                                <input name="title-indicator" type="text" class="form-control" id="edit-title-indicator" required/>
                            </div>
                            <div class="form-group">
                                <label for="source-indicator">Source</label>
                                <input name="source-indicator" type="text" class="form-control" id="edit-source-indicator">
                            </div>
                            <div class="form-group">
                                <label for="description-indicator">Description</label>
                                <textarea name="description-indicator" type="text" class="form-control" id="edit-description-indicator"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                                <input type="submit" value="Save changes" name="edit-indicator" class="btn btn-primary indicator-color" id="edit-indicator-button">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of load edit indicator modal -->

        <!-- Add Target Button -->
        <div style="padding: 0px" class="col-md-2">
            <a class="btn btn-primary" href="#add-targets-modal" data-toggle="modal" id="add-targets-link" style="width:100%;">+ Add target</a>
        </div>

        <!-- Start of Add Target Modal -->
        <div id="add-targets-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header target-color">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Add New Target</h4>
                    </div>
                    <div class="modal-body">
                        <form id="add-targets-form" name="add-targets-form" method="POST">
                            <div class="form-group">
                                <label for="target-title">Target Title</label>
                                <input name="target-title" type="text" class="form-control" id="target-title" required/>
                            </div>

                            <div class="form-group">
                                <label for='sdg-type'>SDG</label>
                                <select id="sdg-type" name="sdg-type" class="form-control" required>
                                    <option value="">Select SDG</option>
                                    <option id="1" value="poverty">1-Poverty</option>
                                    <option id="2" value="zero-hunger">2-Zero hunger</option>
                                    <option id="3" value="good-health-and-well-being">3-Good health and well being </option>
                                    <option id="4" value="quality-education">4-Quality education</option>
                                    <option id="5" value="gender-equality">5-Gender equality</option>
                                    <option id="6" value="clean-water-and-sanitation">6-Clean water and sanitation </option>
                                    <option id="7" value="affordable-and-clean-energy">7-Affordable and clean energy </option>
                                    <option id="8" value="decent-work-and-economic-growth">8-Decent work and economic growth </option>
                                    <option id="9" value="industry-innovation-and-infrastructure">9-Industry innovation and infrastructure</option>
                                    <option id="10" value="reduced-inequalities">10-Reduced Inequalities</option>
                                    <option id="11" value="sustainable-cities-and-communities">11-Sustainable cities and communities </option>
                                    <option id="12" value="responsible-consumption-and-production">12-Responsible consumption and production</option>
                                    <option id="13" value="climate-action">13-Climate action</option>
                                    <option id="14" value="life-below-water">14-Life below water</option>
                                    <option id="15" value="life-on-land">15-Life on land</option>
                                    <option id="16" value="peace-justice-and-strong-institutions">16-Peace justice and strong institutions </option>
                                    <option id="17" value="partnerships-for-the-goal">17-Partnerships for the goal
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" placeholder="Description"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                                <input type="submit" value="Save changes" name="createInd" class="btn btn-primary target-color" id="add-targets-button">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Add Target Modal -->

        <!-- Start of Edit Target Modal -->
        <div id="edit-targets-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header target-color">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Edit target</h4>
                    </div>
                    <div class="modal-body">
                        <form id="edit-targets-form" name="edit_targets_form">
                            <div class="form-group">
                                <label for="edit-target-title">Targets</label>
                                <input name="edit-target-title" type="text" class="form-control" id="edit-target-title" required/>
                            </div>
                            <input type="hidden" id="edit-target-id" />
                            <div class="form-group">
                                <label for='edit-sdg-type'>SDG</label>
                                <select id="edit-sdg-type" name="edit-sdg-type" class="form-control" required>
                                    <option value=""> Select SDG </option>
                                    <option id="1" value="poverty">1-Poverty</option>
                                    <option id="2" value="zero-hunger">2-Zero hunger</option>
                                    <option id="3" value="good-health-and-well-being">3-Good health and well being</option>
                                    <option id="4" value="quality-education">4-Quality education</option>
                                    <option id="5" value="gender-equality">5-Gender equality</option>
                                    <option id="6" value="clean-water-and-sanitation">6-Clean water and sanitation</option>
                                    <option id="7" value="affordable-and-clean-energy">7-Affordable and clean energy</option>
                                    <option id="8" value="decent-work-and-economic-growth">8-Decent work and economic growth</option>
                                    <option id="9" value="industry-innovation-and-infrastructure">9-Industry innovation and infrastructure</option>
                                    <option id="10" value="reduced-inequalities">10-Reduced Inequalities</option>
                                    <option id="11" value="sustainable-cities-and-communities">11-Sustainable cities and communities</option>
                                    <option id="12" value="responsible-consumption-and-production">12-Responsible consumption and production</option>
                                    <option id="13" value="climate-action">13-Climate action</option>
                                    <option id="14" value="life-below-water">14-Life below water</option>
                                    <option id="15" value="life-on-land">15-Life on land</option>
                                    <option id="16" value="peace-justice-and-strong-institutions">16-Peace justice and strong institutions</option>
                                    <option id="17" value="partnerships-for-the-goal">17-Partnerships for the goal
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit-sdg-description">Description</label>
                                <textarea name="edit-sdg-description" class="form-control" id="edit-sdg-description"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                                <input type="submit" value="Save changes" name="createInd" class="btn btn-primary target-color" id="edit-targets-button">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Edit Target Modal -->


        <!-- Start of Add Chart Modal -->
        <div id="add-chart-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header chart-color">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Add Chart</h4>
                    </div>
                    <div class="modal-body">
                        <form id="add-chart-form" class="form-horizontal" method="POST">
                            <input type="hidden" id="chart-target-id">
                            <input type="hidden" id="chart-indicator-id">
                            <input type="hidden" id="chart-sdg-short-name">
                            <div class="form-group">
                                <label class="col-xs-3 control-label left">Title</label>
                                <div class="col-xs-9">
                                    <input type="text" class="form-control" name="title-chart" id="title-chart" required/>
                                </div>
                            </div>
                            <!-- Target Data Panel-->
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title chart-target-panel">Target Data</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-xs-3 control-label">Target Year</label>
                                            <div class="col-xs-9">
                                                <input name="target-year" type="number" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" class="form-control" id="target-year" required/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="target-unit" class="col-xs-3 control-label">Unit</label>
                                            <div class="col-xs-9">
                                                <select id="target-unit-select" name="target-unit" class="form-control" required/>
                                                    <option value="">Select Unit</option>
                                                    <option id="target-number" value="number" data-show="number"> Number</option>
                                                    <option id="target-percentage" value="percentage" data-show="percentage"> Percentage </option>
                                                    <option id="target-yes-no" value="yes-no" data-show="yes-no"> Yes/No </option>
                                                    <option id="target-comperative" value="comperative" data-show="comperative">Comperative Value</option>
                                                    <option id="target-ratio" value="ratio" data-show="ratio">Ratio</option>
                                                    <option id="target-increasing-decreasing" value="increasing-decreasing" data-show="increasing-decreasing"> Increasing/Decreasing </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group target-unit-select target-unit-number">
                                            <label class="col-xs-3 control-label" for="target-number-value">Number Value</label>
                                            <div class="col-xs-9">
                                                <input name="target-number-value" type="number" step="any" class="form-control" id="target-number-value" data-slug="value"/>
                                            </div>
                                        </div>
                                        <div class="form-group target-unit-select target-unit-percentage">
                                            <label class="col-xs-3 control-label" for="target-percentage-value">Percentage Value</label>
                                            <div class="col-xs-9">
                                                <input name="target-percentage-value" type="number" step="any" class="form-control" id="target-percentage-value" data-slug="value"/>
                                            </div>
                                        </div>
                                        <div class="target-unit-select target-unit-ratio">
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label" for="target-ratio-value-a">Number</label>
                                                <div class="col-xs-9">
                                                    <input name="target-ratio-value-a" type="number" step="any" class="form-control" id="target-ratio-value-a" data-slug="value_a"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label" for="target-ratio-value-b">Total</label>
                                                <div class="col-xs-9">
                                                    <input name="target-ratio-value-b" type="number" step="any" class="form-control" id="target-ratio-value-b" data-slug="value_b"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="target-unit-select target-unit-comperative">
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label" for="target-comperative-value-a">Current Value</label>
                                                <div class="col-xs-9">
                                                    <input name="target-comperative-current-value" step="any" type="number" class="form-control" id="target-comperative-current-value" data-slug="current_value"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label" for="target-comperative-value-b">Maximum Value</label>
                                                <div class="col-xs-9">
                                                    <input name="target-comperative-max-value" step="any" type="number" class="form-control" id="target-comperative-max-value" data-slug="max_value"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group target-unit-select target-unit-yes-no">
                                            <label class="col-xs-3 control-label" for="target-yes-no-value">Value</label>
                                            <div class="col-xs-6">
                                                <label class="radio-inline">
                                                    <input type="radio" name="target-yes-no" value="yes" data-slug="value"/>Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="target-yes-no" value="no" data-slug="value"/>No
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group target-unit-select target-unit-increasing-decreasing">
                                            <label class="col-xs-3 control-label" for="target-increasing-decreasing-value">Value</label>
                                            <div class="col-xs-6">
                                                <label class="radio-inline">
                                                    <input type="radio" name="target-increasing-decreasing" value="increasing" data-slug="value"/> Increasing
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="target-increasing-decreasing" value="decreasing" data-slug="value"/> Decreasing
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- End of Target Data Panel -->

                            <!-- Chart Data Panel-->
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title chart-target-panel">Chart Data</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-xs-3 control-label"> General Label </label>
                                            <div class="col-xs-9">
                                                <input type="text" class="form-control" name="label-chart" id="label-chart" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="chart-data-unit" class="col-xs-3 control-label">Unit</label>
                                            <div class="col-xs-9">
                                                <select id="chart-unit-select" name="chart-unit" class="form-control" required>
                                                    <option value="">Select Unit</option>
                                                    <option id="chart-number" value="number" data-show="number"> Number</option>
                                                    <option id="chart-percentage" value="percentage" data-show="percentage"> Percentage </option>
                                                    <option id="chart-yes-no" value="yes-no" data-show="yes-no"> Yes/No </option>
                                                    <!-- <option id="chart-comperative" value="comperative"  data-show="comperative">Comperative Value</option> -->
                                                    <!-- <option id="chart-ratio" value="ratio"  data-show="ratio">Ratio</option> -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-offset-12 plus-div" style="float:right;">
                                                <button type="button" class="btn btn-default addButton" data-action="add" style="margin-right: 15px;"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="div-chart-unit-number">
                                            <div class="chart-unit-select hide chart-unit-number" id="chart-unit-number">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline</label>
                                                    <div class="col-xs-9">
                                                        <input type="number" class="form-control" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" name="chart-baseline-number" data-slug="baseline"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-number-value">Number Value</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-number-value" type="number" step="any" class="form-control" id="chart-number-value" data-slug="value"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-label-value">Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" id="chart-label-value" data-slug="label" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>

                                        <div class="div-chart-unit-percentage">
                                            <div class="chart-unit-select hide chart-unit-percentage" id="chart-unit-percentage">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline </label>
                                                    <div class="col-xs-9">
                                                        <input type="number" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" class="form-control" name="chart-baseline-percentage" data-slug="baseline"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-percentage-value">Percentage Value</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-percentage-value" type="number" step="any" class="form-control" id="chart-percentage-value" data-slug="value"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-label-value"> Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" id="chart-label-value" data-slug="label"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>
                                        <div class="div-chart-unit-ratio">
                                            <div class="chart-unit-select hide chart-unit-ratio" id="chart-unit-ratio">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline </label>
                                                    <div class="col-xs-9">
                                                        <input type="number" class="form-control" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" name="chart-baseline-ratio" data-slug="baseline"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-ratio-value-a">Number</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-ratio-value-a" type="number" step="any" class="form-control" id="chart-ratio-value-a" data-slug="value_a"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-label-value">Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" id="chart-label-value" data-slug="label" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-ratio-value-b">Total</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-ratio-value-b" type="number" step="any" class="form-control" id="chart-ratio-value-b" data-slug="value_b"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>

                                        <div class="div-chart-unit-comperative">
                                            <div class="chart-unit-select hide chart-unit-comperative" id="chart-unit-comperative">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline </label>
                                                    <div class="col-xs-9">
                                                        <input type="number" class="form-control" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" name="chart-baseline-comperative" data-slug="baseline" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-comperative-value-a">Current Value</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-comperative-value-a" type="number" step="any" class="form-control" id="chart-comperative-current-value" data-slug="current_value"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-comperative-value-b">Maximum Value</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-comperative-value-b" type="number" step="any" class="form-control" id="chart-comperative-value-max-value" data-slug="max_value"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-label-value">Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" id="chart-label-value" data-slug="label" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>

                                        <div class="div-chart-unit-yes-no">
                                            <div class="chart-unit-select hide chart-unit-yes-no" id="chart-unit-yes-no">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline </label>
                                                    <div class="col-xs-9">
                                                        <input type="number" class="form-control" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" name="chart-baseline-yes-no" data-slug="baseline" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-yes-no-value">Values</label>
                                                    <div class="col-xs-6">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="chart-yes-no" value="yes" data-slug="value"/>Yes
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="chart-yes-no" value="no" data-slug="value"/>No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-label-value">Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" id="chart-label-value" data-slug="label"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Chart Data Panel -->
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="chart-description">Description</label>
                                <div class="col-xs-9">
                                    <textarea name="chart-description" type="text" class="form-control" id="chart-description"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                                <input type="submit" value="Save changes" name="add-chart-button" class="btn btn-primary chart-color" id="add-chart-button">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Add Chart Modal -->

        <!-- Start of Edit Chart Modal -->
        <div id="edit-chart-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Edit Chart</h4>
                    </div>
                    <div class="modal-body">
                        <form id="edit-chart-form" class="form-horizontal" name="edit_chart_form" method="POST">
                            <input type="hidden" id="edit-chart-id">
                            <input type="hidden" id="edit-chart-target-id">
                            <input type="hidden" id="edit-chart-indicator-id">
                            <input type="hidden" id="edit-chart-sdg-id">
                            <div class="form-group">
                                <label class="col-xs-3 control-label left">Title</label>
                                <div class="col-xs-9">
                                    <input type="text" class="form-control" name="edit-title-chart" id="edit-title-chart" required/>
                                </div>
                            </div>
                            <!-- Edit Target Data Panel-->
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title chart-target-panel">Target Data</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-xs-3 control-label">Target Year</label>
                                            <div class="col-xs-9">
                                                <input name="edit-target-year" type="number" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" class="form-control" id="edit-target-year" required/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="target-unit" class="col-xs-3 control-label">Unit</label>
                                            <div class="col-xs-9">
                                                <select id="edit-target-unit-select" name="edit-target-unit" class="form-control" required>
                                                    <option value="">Select Unit</option>
                                                    <option id="edit-target-number" value="number" data-show="number"> Number</option>
                                                    <option id="edit-target-percentage" value="percentage" data-show="percentage"> Percentage </option>
                                                    <option id="edit-target-yes-no" value="yes-no" data-show="yes-no"> Yes/No </option>
                                                    <option id="edit-target-comperative" value="comperative" data-show="comperative">Comperative Value</option>
                                                    <option id="edit-target-ratio" value="ratio" data-show="ratio">Ratio</option>
                                                    <option id="edit-target-increasing-decreasing" value="increasing-decreasing" data-show="increasing-decreasing"> Increasing/Decreasing </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group edit-target-unit-select edit-target-unit-number">
                                            <label class="col-xs-3 control-label">Number Value</label>
                                            <div class="col-xs-9">
                                                <input name="edit-target-number-value" type="number" step="any" class="form-control" id="edit-target-number-value" data-slug="value" />
                                            </div>
                                        </div>
                                        <div class="form-group edit-target-unit-select edit-target-unit-percentage">
                                            <label class="col-xs-3 control-label">Percentage Value</label>
                                            <div class="col-xs-9">
                                                <input name="edit-target-percentage-value" type="number" step="any" class="form-control" id="edit-target-percentage-value" data-slug="value" />
                                            </div>
                                        </div>
                                        <div class="edit-target-unit-select edit-target-unit-ratio">
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Number</label>
                                                <div class="col-xs-9">
                                                    <input name="edit-target-ratio-value-a" type="number" step="any" class="form-control" id="edit-target-ratio-value-a" data-slug="value_a" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Total</label>
                                                <div class="col-xs-9">
                                                    <input name="edit-target-ratio-value-b" type="number" step="any" class="form-control" id="edit-target-ratio-value-b" data-slug="value_b" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="edit-target-unit-select edit-target-unit-comperative">
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Current Value</label>
                                                <div class="col-xs-9">
                                                    <input name="edit-target-comperative-current-value" step="any" type="number" class="form-control" id="edit-target-comperative-current-value" data-slug="current_value" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Maximum Value</label>
                                                <div class="col-xs-9">
                                                    <input name="edit-target-comperative-max-value" step="any" type="number" class="form-control" id="edit-target-comperative-max-value" data-slug="max_value" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group edit-target-unit-select edit-target-unit-yes-no">
                                            <label class="col-xs-3 control-label">Value</label>
                                            <div class="col-xs-6">
                                                <label class="radio-inline">
                                                    <input type="radio" id="edit-target-yes" name="edit-target-yes-no" value="yes" data-slug="value">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="edit-target-no" name="edit-target-yes-no" value="no" data-slug="value">No
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group edit-target-unit-select edit-target-unit-increasing-decreasing">
                                            <label class="col-xs-3 control-label" for="target-increasing-decreasing-value">Value</label>
                                            <div class="col-xs-6">
                                                <label class="radio-inline">
                                                    <input type="radio" id="edit-target-increasing" name="edit-target-increasing-decreasing" value="increasing" data-slug="value"> Increasing
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="edit-target-decreasing" name="edit-target-increasing-decreasing" value="decreasing" data-slug="value"> Decreasing
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- End of Edit Target Data Panel -->

                            <!-- Edit Chart Data Panel-->
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title chart-target-panel">Chart Data</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-xs-3 control-label"> General Label </label>
                                            <div class="col-xs-9">
                                                <input type="text" class="form-control" name="edit-label-chart" id="edit-label-chart" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="chart-data-unit" class="col-xs-3 control-label">Unit</label>
                                            <div class="col-xs-9">
                                                <select id="edit-chart-unit-select" name="edit-chart-unit" class="form-control" required>
                                                    <option value="">Select Unit</option>
                                                    <option id="edit-chart-number" value="number" data-show="number"> Number</option>
                                                    <option id="edit-chart-percentage" value="percentage" data-show="percentage"> Percentage </option>
                                                    <option id="edit-chart-yes-no" value="yes-no" data-show="yes-no"> Yes/No </option>
                                                    <!-- <option id="edit-chart-comperative" value="comperative"  data-show="comperative">Comperative Value</option> -->
                                                    <!-- <option id="edit-chart-ratio" value="ratio"  data-show="ratio">Ratio</option> -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-offset-12 edit-plus-div" style="float:right;">
                                                <button type="button" class="btn btn-default addButton" data-action="edit" style="margin-right: 15px;"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="edit-div-chart-unit-number">
                                            <div class="chart-unit-select hide edit-chart-unit-number" id="edit-chart-unit-number">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-baseline-number" type="number" class="form-control" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" data-slug="baseline" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-number-value">Number Value</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-number-value" type="number" step="any" class="form-control" data-slug="value" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-label-value">Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" data-slug="label" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>

                                        <div class="edit-div-chart-unit-percentage">
                                            <div class="chart-unit-select hide edit-chart-unit-percentage" id="edit-chart-unit-percentage">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline </label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-baseline-percentage" type="number" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" class="form-control" data-slug="baseline" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label"> Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" data-slug="label" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label">Percentage Value</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-percentage-value" type="number" step="any" class="form-control" data-slug="value" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>

                                        <div class="edit-div-chart-unit-yes-no">
                                            <div class="chart-unit-select hide edit-chart-unit-yes-no" id="edit-chart-unit-yes-no">
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label left"> Baseline </label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-baseline-yes-no" type="number" class="form-control" min="1900" max="9999" step="1" onKeyDown="if(this.value.length==4 && event.keyCode>47 && event.keyCode < 58)return false;" data-slug="baseline" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-label-value">Chart Label</label>
                                                    <div class="col-xs-9">
                                                        <input name="chart-label-value" type="text" class="form-control" data-slug="label" />
                                                    </div>
                                                </div>
                                                <!-- TOCHANGE -->
                                                <div class="form-group">
                                                    <label class="col-xs-3 control-label" for="chart-yes-no-value">Values</label>
                                                    <div class="col-xs-6">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="chart-yes-no" value="yes" data-slug="value">Yes
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="chart-yes-no" value="no" data-slug="value">No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                                <hr class="separator">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Chart Data Panel -->

                            <div class="form-group">
                                <label class="col-xs-3 control-label">Description</label>
                                <div class="col-xs-9">
                                    <textarea name="edit-chart-description" type="text" class="form-control" id="edit-chart-description"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                                <input type="submit" value="Save changes" name="edit-chart-button" class="btn btn-primary" id="edit-chart-button">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Edit Chart Modal -->

    </div>
</div>


<!-- Successfully Updated Modal -->
<div class="modal fade" id="alert-success-modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> Information! </h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success">
          <strong>Success!</strong> Record has been successfully updated.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
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
	var sOut = "<table id='chartTable_" + table_id + "'class='table-bordered dataTable no-footer' role='grid' aria-describedby='chartTable_info' w>";
	sOut += html;
	sOut += "</table>";
	return sOut;
}
var newRowData = <?php echo json_encode($query_targets); ?> ;
//console.log(newRowData);

var iTableCounter = 1;
var oTable;
var oInnerTable;
var oInnerInnerTable;
var detailsTableHtml;
var chartTableHtml;

//Run On HTML Build
$(document).ready(function () {
	var addChartIndex = 0;
	$('.plus-div').hide();

	// On removeButton click, delete the appended input group
	$(document).on('click', '.removeButton', function () {
		$(this).parent().parent().parent().remove();
	});

	$('.addButton').click(function () {
		addChartIndex++;
		var action = '';
		var editValue = false;
		// Check if action is edit and not add
		if($(this).data('action') === 'edit') {
			action = 'edit-';
			editValue = true;
		}
		var selectId = '#' + action + 'chart-unit-select';
		var selectedUnit = $(selectId).find(":selected").data('show');
		var divId = '#' + action + 'chart-unit-' + selectedUnit;
		manageUnits.addButton(selectedUnit, divId, editValue);
	});
	var manageUnits = {
		addButton: function (unit, divId, editValue, editData = 0, chartUnit = 0) {
			// clone the div based on id
			var $template = $(divId),
				$clone = $template
				.clone()
				.removeClass('hide')
				.addClass('addedItem')
				.removeAttr('id')
				.insertAfter($template);
			// Update the id
			$clone.attr("id", divId + '-' + addChartIndex);

			// get all the inputs inside the clone
			var inputs = $clone.find('input');

			// for each input change its name/id appending the index value and values
			$.each(inputs, function (index, elem) {
				var jElem = $(elem);
				var name = jElem.prop('name');
				var slug = jElem.data('slug');
				// Change id and name of input
				jElem.prop('id', name + '-' + addChartIndex);
				jElem.prop('name', name + '-' + addChartIndex);
        jElem.attr("required", "true");
				// when action is editing, check if argument for edit values is given
				if(editValue === true) {
					if(editData != 0) {
						// if slug is exists ad json key then add value for that input
						if(editData.hasOwnProperty(slug)) {
							if(chartUnit === 'yes-no' && name === 'chart-yes-no') {
								$('input[name=chart-' + chartUnit + '-' + addChartIndex + '][value=' + editData[slug] + ']').attr('checked', true);
							} else {
								jElem.val(editData[slug]);
							}
						};
					}
				}
				// Add generated attr
				jElem.attr('data-generated', addChartIndex);
			});
		},
		targetValue: function (targetUnit, action) {
			var targetValue = {};
			if(action === 'edit') {
				action = 'edit-';
			}
			$('.' + action + 'target-unit-' + targetUnit + " :input").each(function (e) {
				var slug = $(this).data("slug");
				var value = $(this).val();
				if(parseInt(value, 10).toString() === value) {
					value = parseInt(value);
				}
				targetValue[slug] = parseInt(value);

				if(targetUnit == 'yes-no' || targetUnit == 'increasing-decreasing') {
					slug = $('input[type=radio][name=target-' + targetUnit + ']:checked').data("slug");
					value = $('input[type=radio][name=target-' + targetUnit + ']:checked').val();
					targetValue[slug] = value;
					return false;
				}
			});

			return JSON.stringify(targetValue);
		},
		chartData: function (chartUnit) {

			var chartElement = {};
			var textInputs = $(':input');
			var datas = textInputs.filter('[data-generated]');
			var previewIndex, inputYear;

			var allData = {};
			var years = [];
			datas.each(
				function (i, e) {
					if(e.getAttribute('data-slug') === 'baseline') {
						if(years.indexOf(e.value) === -1) {
							years.push(e.value);
							allData[e.value] = [];
						}
					}
				}
			);
			var length = datas.length;
			datas.each(
				function (i, e) {
					var currentIndex = parseInt(e.getAttribute('data-generated'));
					var slug, value, insert;

					if(previewIndex != currentIndex && typeof previewIndex != 'undefined') {
						allData[inputYear].push(chartElement);
						chartElement = {};
					}
					slug = e.getAttribute('data-slug');
					if(slug === 'baseline') {
						inputYear = e.value;
					} else {
						value = e.value;
						if(parseInt(value, 10).toString() === value) {
							value = parseInt(value);
						}
						insert = true;

						if(chartUnit === 'yes-no' && slug != 'baseline' && slug != 'label') {
							if(e.checked === false) {
								insert = false;
							}
						}

						if(insert === true) {
							chartElement[slug] = value;
						}
					}
					//insert if it's last element
					if(i === (length - 1)) {
						allData[inputYear].push(chartElement);
					}
					previewIndex = currentIndex;

				});
			return JSON.stringify(allData);
		}
	};

	function onTargetUnitSelect(selectedUnit, label) {
			$('.' + label + 'target-unit-select').hide();
			$('#' + label + 'chart-unit-select').attr('disabled', false);
			$('.' + label + 'target-unit-' + selectedUnit).show();
			if(selectedUnit === 'yes-no') {
				addChartIndex = 0;
				$('#' + label + 'chart-unit-select').val('yes-no');
				$('.addedItem').remove();
				$('.plus-div').show();
				manageUnits.addButton('yes-no', '#' + label + 'chart-unit-yes-no', false);
				$('#' + label + 'chart-unit-select').attr('disabled', true);
			}
		}
		// Hide all taget unit fields and show them based on selected unit
	$('.target-unit-select').hide();
	$('#target-unit-select').change(function () {
		onTargetUnitSelect($('option:selected', this).val(), '');
	});
	// Hide all edit taget unit fields and show them based on selected unit
	$('#edit-target-unit-select').change(function () {
		onTargetUnitSelect($('option:selected', this).val(), 'edit-');
	});

	function onChartUnitSelect(selectedUnit, label, actionFlag) {
		addChartIndex = 0;
		$('.addedItem').remove();
		var divId = '#' + label + 'chart-unit-' + selectedUnit;
		manageUnits.addButton(selectedUnit, divId, actionFlag);
	}

	// Chart unit select box on change
	$('#chart-unit-select').change(function () {
		$('.plus-div').show();
		onChartUnitSelect($('option:selected', this).val(), '', false);
	});

	// Chart unit select box on change
	$('#edit-chart-unit-select').change(function () {
		onChartUnitSelect($('option:selected', this).val(), 'edit-', true);
	});

	// when chart modal is closed remove all added fields and hide displayed divs
	$('#add-chart-modal').on('hidden.bs.modal', function () {
		$(this).find('form')[0].reset();
		$('.addedItem').remove();
		$('.target-unit-select').hide();
		$('.plus-div').hide();
		$("#chart-unit-select").val("");
		$("#target-unit-select").val("");
	});

	$('#edit-chart-modal').on('hidden.bs.modal', function () {
		$(this).find('form')[0].reset();
		$('.edit-target-unit-select').hide();
		$('.plus-div').hide();
		$('.addedItem').remove();
	});

	// Adding new target from modal
	$('#add-targets-form').validate({
		rules: {

		},
		submitHandler: function (form) {
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
			// e.preventDefault();
		}
	});

	// Edit existing target from modal
	$('#edit-targets-form').validate({
		rules: {

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
					$('#alert-success-modal').modal('show');
					setTimeout(function () {
						$('#alert-success-modal').modal('hide')
					}, 3000);
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
	$('#exampleTable thead tr').each(function () {
		this.insertBefore(nCloneTh, this.childNodes[0]);
	});

	// This will add "+" sign for each row in first data column
	$('#exampleTable tbody tr').each(function (index, element) {
		this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
	});

	// Initialize the sub sub table for charts
	function init_sub_sub_table() {
		$('body').on('click', '.show-sub-sub-table', function (e) {

			e.preventDefault();

			// Getting the ID of clicked target's "+"
			var indicator_id = $($(this).parent().parent().children()[1]).text();

			// // Getting the ID of clicked SDG's "+"
			var target_id = $($(this).parent().parent().children()[6]).text();

			// // Getting the ID of clicked SDG's "+"
			var sdg_short_name = $($(this).parent().parent().children()[7]).text();

			// Getting the row of target
			var nTr = $(this).parents('tr')[0];

			// Getting the "+" sign of target
			var nTds = this;

			// Checking the table if it's opened or closed for "+" and "-"
			if(oInnerTable.fnIsOpen(nTr)) {
				/* This row is already open - close it */
				// If the sub table is closed make the "-" to "+"
				this.src = '<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>';
			} else {
				// If the sub table is opened make the "+" to "-"
				this.src = '<?php echo SDGS__PLUGIN_URL . 'img/minus.png' ?>';
			}
			// GET Request for rendering chart table
			$.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
				type: 'GET',
				dataType: 'json',
				data: {
					'id': indicator_id,
					'target_id': target_id,
					'action': 'get_target_indicator_charts'
				},
				success: function (data) {
					var sdg_id;
					if(!data) {
						sdg_id = data[0].sdg_id;
					}
					// Checking if table is closed or opened
					if(oInnerTable.fnIsOpen(nTr)) {
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
						oInnerTable.fnOpen(nTr, fnFormatCharts(indicator_id + '_' + target_id, chartTableHtml), 'chart-details chart-table');
						// Rendering the chart data in inner table of selected indicator
						oInnerInnerTable = $("#chartTable_" + indicator_id + '_' + target_id).dataTable({
							"bJQueryUI": true,
							"bFilter": true,
							"aaData": data,
							"bSort": true, // disables sorting
							"info": true,
							"paging": false,
							"aoColumns": [{
								"mDataProp": "id"
							}, {
								"mDataProp": "title"
							}, {
								"mDataProp": "target_unit"
							}, {
								"mDataProp": "target_year"
							}, {
								"mDataProp": "target_value"
							}, {
								"mDataProp": "chart_unit"
							}, {
								"mDataProp": "chart_data"
							}, {
								"mDataProp": "description"
							}, {
								"mDataProp": "label"
							}, {
								"sDefaultContent": "<a data-toggle='modal' href='#edit-chart-modal' class='edit-modal-chart' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-chart'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"
							}, {
								"sDefaultContent": target_id
							}, {
								"sDefaultContent": indicator_id
							}, {
								"sDefaultContent": sdg_short_name
							}, ],
							"bPaginate": true,
							"oLanguage": {
								"sInfo": "_TOTAL_ entries"
							},
							"dom": 'Bfrtip',
							"buttons": [{
								"extend": 'copyHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}, {
								"extend": 'excelHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}, {
								"extend": 'pdfHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}, {
								"extend": 'csvHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}],
							"columnDefs": [{
								"targets": [0, 4, 6, 10, 11, 12],
								className: 'hidden'
							}],
						});

						$(this).attr('id', indicator_id);
						$(this).attr('colspan', 7);
						// Updating the info of datatable with the button to create new indicator
						$('tr.chart-details .dataTables_info').html('');
						$('tr.chart-details .dataTables_info').append("<a data-toggle='modal' href='#add-chart-modal' data-indicator-id='" + indicator_id + "' data-target-id='" + target_id + "' data-sdg-short-name='" + sdg_short_name + "' class='add-chart btn btn-primary'> + Add Chart </a>");
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
			if(oTable.fnIsOpen(nTr)) {
				/* This row is already open - close it */
				// If the sub table is closed make the "-" to "+"
				this.src = '<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>';
			} else {
				// If the sub table is opened make the "+" to "-"
				this.src = '<?php echo SDGS__PLUGIN_URL . 'img/minus.png' ?>';
			}

			// GET Request for rendering indicator table
			$.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
				type: 'GET',
				dataType: 'json',
				data: {
					'id': targets_id,
					'action': 'get_targets_indicators'
				},
				success: function (data) {
					// Checking if table is closed or opened
					if(oTable.fnIsOpen(nTr)) {
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
						oTable.fnOpen(nTr, fnFormatDetails(targets_id, detailsTableHtml), 'details indicator-table');

						// Rendering the indicator data in inner table
						oInnerTable = $("#exampleTable_" + targets_id).dataTable({
							"bJQueryUI": true,
							"bFilter": true,
							"aaData": data,
							"paging": false,
							"bSort": true, // disables sorting
							"info": true,
							"aoColumns": [
								{
									"mDataProp": null,
									"sClass": "control text-center",
									"sDefaultContent": '<img title="Indicators" src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-sub-table" style="width:20px;"/>'
								}, {
									"mDataProp": "id"
								}, {
									"mDataProp": "title"
								}, {
									"mDataProp": "source"
								}, {
									"mDataProp": "description"
								}, {
									"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"
								}, {
									"sDefaultContent": targets_id
								}, {
									"sDefaultContent": s_id
								},
							],
							columnDefs: [{
								render: function (data, type, full, meta) {
									return "<div class='text-wrap width-200'>" + data + "</div>";
								},
								targets: 0
							}],
							"bPaginate": true,
							"oLanguage": {
								"sInfo": "_TOTAL_ entries"
							},
							"dom": 'Bfrtip',
							"buttons": [{
								"extend": 'copyHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}, {
								"extend": 'excelHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}, {
								"extend": 'pdfHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}, {
								"extend": 'csvHtml5',
								"exportOptions": {
									"columns": [1, 2, 3, 4, 5]
								}
							}],
							"columnDefs": [{
								"targets": [1, 6, 7],
								className: 'hidden'
							}],
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
			'columnDefs': [{
				'max-width': '20%',
				'targets': 0
			}],
			"paging": false,
			"order": [1, 'asc'],
			"aoColumns": [{
				"mDataProp": null,
				"sClass": "control text-center",
				"sDefaultContent": '<img title="Indicators" src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-table" style="width:20px;"/>'
			}, {
				"mDataProp": "id"
			}, {
				"mDataProp": "title"
			}, {
				"mDataProp": "short_name"
			}, {
				"mDataProp": "updated_date"
			}, {
				"mDataProp": "description"
			}, {
				"sDefaultContent": "<a data-toggle='modal' href='#edit-targets-modal' class='edit-modal-targets' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-targets'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"
			}, ],
			"oLanguage": {
				"sInfo": "_TOTAL_ entries"
			},
			"dom": 'Bfrtip',
			"buttons": [{
				"extend": 'copyHtml5',
				"exportOptions": {
					"columns": [1, 2, 3, 4, 5]
				}
			}, {
				"extend": 'excelHtml5',
				"exportOptions": {
					"columns": [1, 2, 3, 4, 5]
				}
			}, {
				"extend": 'pdfHtml5',
				"exportOptions": {
					"columns": [1, 2, 3, 4, 5]
				}
			}, {
				"extend": 'csvHtml5',
				"exportOptions": {
					"columns": [1, 2, 3, 4, 5]
				}
			}],
			"aaSorting": [
				[7, 'desc']
			],
			"columnDefs": [{
				"targets": [1],
				className: 'hidden'
			}],
		});
	}

	// Invoking the initialize function for main datatable, passing the JSON with all targets from query
	init_table(newRowData);

	// Invoking the sub_sub_table function when plus is clicked
	init_sub_sub_table();

	// Invoking the sub_table function when plus is clicked
	init_sub_table();

	// Add New Chart
	$('body').on('click', '.add-chart', function (e) {

		// Get clicked targets ID
		var target_id = $(this).data("target-id")

		// Get clicked Indicator ID
		var indicator_id = $(this).data("indicator-id")

		// Get clicked Indicator ID
		var sdg_short_name = $(this).data("sdg-short-name")

		// Set target ID
		$('#chart-target-id').val(target_id);

		// Set indicator ID
		$('#chart-indicator-id').val(indicator_id);

		// Set chart sdg ID
		$('#chart-sdg-short-name').val(sdg_short_name);

		e.preventDefault();
	});

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

		e.preventDefault();
	});

	// Getting the indicator data to edit
	$('body').on('click', '.edit-modal-indicator', function (e) {
		e.preventDefault();
		// Get the id of indicator which we want to edit
		var indicator_id = $($(this).parent().parent().children()[1]).text();
		$.ajax({
			type: "POST",
			data: {
				'id': +indicator_id,
				'action': 'load_indicator_selected'
			},
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

	// Adding new chart
	$('#add-chart-form').validate({
		rules: {

		},
		submitHandler: function (form) {
			var indicator_id = $('#chart-indicator-id').val();
			var target_id = $('#chart-target-id').val();
			var sdg_short_name = $('#chart-sdg-short-name').val();
			var targetUnit = $("#target-unit-select").val();
			var chartUnit = $("#chart-unit-select").val();

			$.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
				type: 'POST',
				dataType: 'json',
				data: {
					'sdg_id': sdg_short_name,
					'target_id': target_id,
					'indicator_id': indicator_id,
					'title': $('#title-chart').val(),
					'target_year': $("#target-year").val(),
					'target_unit': targetUnit,
					'target_value': manageUnits.targetValue(targetUnit, ''),
					'chart_unit': chartUnit,
					'chart_data': manageUnits.chartData(chartUnit),
					'description': $("#chart-description").val(),
					'label': $("#label-chart").val(),
					'action': 'add_chart'
				},
				success: function (data) {
					var indicator_id = data[0].indicator_id;
					var target_id = data[0].target_id;
					var s_id = data[0].sdg_id;
					$("#chartTable_" + indicator_id + '_' + target_id).dataTable().fnDestroy();

					oInnerInnerTable = $("#chartTable_" + indicator_id + '_' + target_id).dataTable({
						"bJQueryUI": true,
						"bFilter": true,
						"aaData": data,
						"bSort": true, // disables sorting
						"info": true,
						"aoColumns": [{
							"mDataProp": "id"
						}, {
							"mDataProp": "title"
						}, {
							"mDataProp": "target_unit"
						}, {
							"mDataProp": "target_year"
						}, {
							"mDataProp": "target_value"
						}, {
							"mDataProp": "chart_unit"
						}, {
							"mDataProp": "chart_data"
						}, {
							"mDataProp": "description"
						}, {
							"mDataProp": "label"
						}, {
							"sDefaultContent": "<a data-toggle='modal' href='#edit-chart-modal' class='edit-modal-chart' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-chart'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"
						}, {
							"sDefaultContent": target_id
						}, {
							"sDefaultContent": indicator_id
						}, {
							"sDefaultContent": sdg_short_name
						}, ],
						"bPaginate": true,
						"oLanguage": {
							"sInfo": "_TOTAL_ entries"
						},
						"dom": 'Bfrtip',
						"buttons": [{
							"extend": 'copyHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'excelHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'pdfHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'csvHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}],
						"columnDefs": [{
							"targets": [0, 4, 6, 10, 11, 12],
							className: 'hidden'
						}],
					});

					$(this).attr('id', indicator_id);
					// Updating the info of datatable with the button to create new indicator
					$('tr.chart-details .dataTables_info').html('');
					$('tr.chart-details .dataTables_info').append("<a data-toggle='modal' href='#add-chart-modal' data-indicator-id='" + indicator_id + "' data-target-id='" + target_id + "' data-sdg-short-name='" + sdg_short_name + "' class='add-chart btn btn-primary'> + Add Chart </a>");

					$('#add-chart-modal').modal('hide');
					$('#add-chart-form')[0].reset();
				}
			});
		}
	});

	// Adding new chart
	$('#edit-chart-form').validate({
		rules: {

		},
		submitHandler: function (form) {
			var chart_id = $('#edit-chart-id').val();
			var indicator_id = $('#edit-chart-indicator-id').val();
			var target_id = $('#edit-chart-target-id').val();
			var sdg_id = $('#edit-chart-sdg-id').val();
			var targetUnit = $("#edit-target-unit-select").val();
			var chartUnit = $("#edit-chart-unit-select").val();

			$.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>",
				type: 'POST',
				dataType: 'json',
				data: {
					'chart_id': chart_id,
					'sdg_id': sdg_id,
					'target_id': target_id,
					'indicator_id': indicator_id,
					'title': $('#edit-title-chart').val(),
					'target_year': $("#edit-target-year").val(),
					'target_unit': targetUnit,
					'target_value': manageUnits.targetValue(targetUnit, 'edit'),
					'chart_unit': chartUnit,
					'chart_data': manageUnits.chartData(chartUnit),
					'description': $("#edit-chart-description").val(),
					'label': $("#edit-label-chart").val(),
					'action': 'update_chart'
				},
				success: function (data) {
					var indicator_id = data[0].indicator_id;
					var target_id = data[0].target_id;
					var sdg_id = data[0].sdg_id;
					$("#chartTable_" + indicator_id + '_' + target_id).dataTable().fnDestroy();

					oInnerInnerTable = $("#chartTable_" + indicator_id + '_' + target_id).dataTable({
						"bJQueryUI": true,
						"bFilter": true,
						"aaData": data,
						'columnDefs': [{
							'max-width': '20%',
							'targets': 0
						}],
						"bSort": true, // disables sorting
						"info": true,
						"aoColumns": [{
							"mDataProp": "id"
						}, {
							"mDataProp": "title"
						}, {
							"mDataProp": "target_unit"
						}, {
							"mDataProp": "target_year"
						}, {
							"mDataProp": "target_value"
						}, {
							"mDataProp": "chart_unit"
						}, {
							"mDataProp": "chart_data"
						}, {
							"mDataProp": "description"
						}, {
							"mDataProp": "label"
						}, {
							"sDefaultContent": "<a data-toggle='modal' href='#edit-chart-modal' class='edit-modal-chart' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-chart'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"
						}, {
							"sDefaultContent": target_id
						}, {
							"sDefaultContent": indicator_id
						}, {
							"sDefaultContent": sdg_id
						}, ],
						"bPaginate": true,
						"oLanguage": {
							"sInfo": "_TOTAL_ entries"
						},
						"dom": 'Bfrtip',
						"buttons": [{
							"extend": 'copyHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'excelHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'pdfHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'csvHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}],
						"columnDefs": [{
							"targets": [0, 4, 6, 10, 11, 12],
							className: 'hidden'
						}],
					});

					$(this).attr('id', indicator_id);
					// Updating the info of datatable with the button to create new indicator
					$('tr.chart-details .dataTables_info').html('');
					$('tr.chart-details .dataTables_info').append("<a data-toggle='modal' href='#add-chart-modal' data-indicator-id='" + indicator_id + "' data-target-id='" + target_id + "' data-sdg-short-name='" + sdg_id + "' class='add-chart btn btn-primary'> + Add Chart </a>");

					$('#edit-chart-modal').modal('hide');
					$('#edit-chart-form')[0].reset();
					$('#alert-success-modal').modal('show');
					setTimeout(function () {
						$('#alert-success-modal').modal('hide')
					}, 3000);
				}

			});
		}
	});

	// Adding new Indicator
	$('#add-indicator-form').validate({
		rules: {

		},
		submitHandler: function (form) {

			var targets_id = $('#indicator-target-id').val();
			var sdg_id = $('#indicator-sdg').val();
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
						'columnDefs': [{
							'max-width': '20%',
							'targets': 0
						}],
						"bSort": true, // disables sorting
						"aoColumns": [{
							"mDataProp": null,
							"sClass": "control text-center",
							"sDefaultContent": '<img title="Indicators" src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-sub-table" style="width:20px;"/>'
						}, {
							"mDataProp": "id"
						}, {
							"mDataProp": "title"
						}, {
							"mDataProp": "source"
						}, {
							"mDataProp": "description"
						}, {
							"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"
						}, {
							"sDefaultContent": targets_id
						}, {
							"sDefaultContent": sdg_id
						}],
						"bPaginate": true,
						"oLanguage": {
							"sInfo": "_TOTAL_ entries"
						},
						"dom": 'Bfrtip',
						"buttons": [{
							"extend": 'copyHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'excelHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'pdfHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'csvHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}],
						"columnDefs": [{
							"targets": [1, 6, 7],
							className: 'hidden'
						}],

					});
					$('tr.details .dataTables_info').html('');
					$('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + targets_id + "' data-sdg='" + sdg_id + "' href='#add-indicator-modal' class='add-indicator btn btn-primary'>+ Add indicator</a>");
					$('#add-indicator-modal').modal('hide');
					$('#add-indicator-form')[0].reset();
					setTimeout(function () {
						$('#').fadeOut('fast');
					}, 1000);

				}
			});
		}
	});

	// Posting the edited Data
	$('#edit-indicator-form').validate({
		rules: {

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
						'columnDefs': [{
							'max-width': '20%',
							'targets': 0
						}],
						"bSort": true, // disables sorting
						"aoColumns": [
							{
								"mDataProp": null,
								"sClass": "control text-center",
								"sDefaultContent": '<img title="Indicators" src="<?php echo SDGS__PLUGIN_URL . 'img/plus.png' ?>" class="show-sub-sub-table" style="width:20px;"/>'
							}, {
								"mDataProp": "id"
							}, {
								"mDataProp": "title"
							}, {
								"mDataProp": "source"
							}, {
								"mDataProp": "description"
							}, {
								"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-targets' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"
							}, {
								"sDefaultContent": target_id
							}, {
								"sDefaultContent": sdg_id
							}
						],
						"bPaginate": true,
						"oLanguage": {
							"sInfo": "_TOTAL_ entries"
						},
						"dom": 'Bfrtip',
						"buttons": [{
							"extend": 'copyHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'excelHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'pdfHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}, {
							"extend": 'csvHtml5',
							"exportOptions": {
								"columns": [1, 2, 3, 4, 5]
							}
						}],
						"columnDefs": [{
							"targets": [1, 6, 7],
							className: 'hidden'
						}],

					});
					$('tr.details .dataTables_info').html('');
					$('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + target_id + "' data-sdg='" + sdg_id + "' href='#add-indicator-modal' class='add-indicator btn btn-primary'>+ Add indicator</a>");
					$('#edit-indicator-modal').modal('hide');
					$('.form-control').val('');
					$('#alert-success-modal').modal('show');
					setTimeout(function () {
						$('#alert-success-modal').modal('hide')
					}, 3000);
				}
			});
		}
	});

	// Getting the chart data to edit
	$('body').on('click', '.edit-modal-chart', function (e) {
		e.preventDefault();
		// Get the id of chart which we want to edit
		var chart_id = $($(this).parent().parent().children()[0]).text();
		$.ajax({
			type: "POST",
			data: {
				'id': +chart_id,
				'action': 'load_chart_selected'
			},
			dataType: 'json',
			url: "<?php echo admin_url('admin-ajax.php'); ?>",
			success: function (data) {

				var targetUnit = data[0].target_unit;
				var chartUnit = data[0].chart_unit;
				var targetValue = JSON.parse(data[0].target_value.replace(/&quot;/g, '"'));
				var chartData = JSON.parse(data[0].chart_data.replace(/&quot;/g, '"'));

				$('#edit-title-chart').val(data[0].title);
				$('#edit-target-year').val(data[0].target_year);
				$('#edit-target-unit-select').val(targetUnit);

				$.each(targetValue, function (key, value) {
					if(targetUnit === 'increasing-decreasing' || targetUnit === 'yes-no') {
						$('input[name=edit-target-' + targetUnit + '][value=' + value + ']').attr('checked', true);
					} else {
						key = key.replace('_', '-');
						$('#edit-target-' + targetUnit + '-' + key).val(value);
					}
					$('.edit-target-unit-' + targetUnit).show();
				});

				$('#edit-label-chart').val(data[0].label);
				$('#edit-chart-unit-select').val(chartUnit);
				$('#edit-chart-description').val(data[0].description);

				var divId = '#edit-chart-unit-' + chartUnit;
				var selectId = '#chart-unit-select';

				$.each(chartData, function (key, value) {
					var year = key;
					$.each(value, function (index, inputJson) {
						inputJson['baseline'] = parseInt(key);
						manageUnits.addButton(chartUnit, divId, true, inputJson, chartUnit);
					});
				});
				// Hidden fiels
				$('#edit-chart-id').val(data[0].id);
				$('#edit-chart-target-id').val(data[0].target_id);
				$('#edit-chart-indicator-id').val(data[0].indicator_id);
				$('#edit-chart-sdg-id').val(data[0].sdg_id);
			}
		});

	});

	// Getting the target data to edit
	$('body').on('click', '.edit-modal-targets', function (e) {
		var targets_id = $($(this).parent().parent().children()[1]).text();

		$.ajax({
			type: "POST",
			data: {
				'id': targets_id,
				'action': 'get_targets'
			},
			dataType: 'json',
			url: "<?php echo admin_url('admin-ajax.php'); ?>",
			success: function (data) {
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
	});

	// Remove target
	$('body').on('click', '.remove-targets', function (e) {
		e.preventDefault();
		var row = $($(this).parent().parent());
		var target_id = $($(this).parent().parent().children()[1]).text();

		BootstrapDialog.show({
			title: 'Confirm Delete',
			type: BootstrapDialog.TYPE_DANGER,
			message: 'When you delete a specific target of the SDG, all of other data including indicators and charts will be erased. Are you sure you want to delete this target?',
			buttons: [{
				icon: 'glyphicon glyphicon-remove',
				label: 'Delete',
				cssClass: 'btn btn-danger',
				autospin: false,
				action: function (dialogRef) {
					$.ajax({
						url: "<?php echo admin_url('admin-ajax.php'); ?>",
						type: 'POST', //or POST
						dataType: 'json',
						data: {
							'id': target_id,
							'action': 'remove_targets'
						},
						success: function (data) {
							oTable.fnClearTable(0);
							if(!jQuery.isEmptyObject(data)) {
								oTable.fnAddData(data);
							}
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
	});

	// Remove indicator
	$('body').on('click', '.remove-indicator', function (e) {
		e.preventDefault();
		var row = $($(this).parent().parent());
		var indicator_id = $($(this).parent().parent().children()[1]).text();

		BootstrapDialog.show({
			title: 'Confirm Delete',
			type: BootstrapDialog.TYPE_DANGER,
			message: 'When you delete a specific indicator, all of other data including charts will be erased. Are you sure you want to delete this indicator?',
			buttons: [{
				icon: 'glyphicon glyphicon-remove',
				label: 'Delete',
				cssClass: 'btn btn-danger',
				autospin: false,
				action: function (dialogRef) {
					$.ajax({
						url: "<?php echo admin_url('admin-ajax.php'); ?>",
						type: 'POST', //or POST
						dataType: 'json',
						data: {
							'id': indicator_id,
							'action': 'remove_indicator'
						},
						success: function (data) {}
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

	// Remove chart
	$('body').on('click', '.remove-chart', function (e) {
		e.preventDefault();
		var row = $($(this).parent().parent());
		var id = $($(this).parent().parent().children()[0]).text();
		var target_id = $($(this).parent().parent().children()[10]).text();
		var indicator_id = $($(this).parent().parent().children()[11]).text();
		var sdg_short_name = $($(this).parent().parent().children()[12]).text();
		BootstrapDialog.show({
			title: 'Confirm Delete',
			type: BootstrapDialog.TYPE_DANGER,
			message: 'Are you sure you want to delete this chart?',
			buttons: [{
				icon: 'glyphicon glyphicon-remove',
				label: 'Delete',
				cssClass: 'btn btn-danger',
				autospin: false,
				action: function (dialogRef) {
					$.ajax({
						url: "<?php echo admin_url('admin-ajax.php'); ?>", //this is the submit URL
						type: 'POST', //or POST
						dataType: 'json',
						data: {
							'id': id,
							'target_id': target_id,
							'indicator_id': indicator_id,
							'sdg_short_name': sdg_short_name,
							'action': 'remove_chart'
						},
						success: function (data) {}
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
});

</script>

<style>
    .removeButton{
      float: right;
    }
    #exampleTable_wrapper .col-sm-6, .dataTables_wrapper .col-sm-6{
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .show-sub-table, .show-sub-sub-table {
        cursor: pointer;
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
        background: #0e4588;
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
    .edit-target-unit-select{
      display: none;
    }
    .target-color{
      background: #0e4588 !important;
    }
    .indicator-color{
      background: #1c67c3 !important;
    }
    .chart-color{
      background: #2084ff !important;
    }
</style>
<script>
    $('.number-values').keypress(function (eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this) == 0)) {
            eve.preventDefault();
        }
    })

</script>
