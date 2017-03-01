<?php
define( 'SDGS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SDGS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 ?>


<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/font-awesome.min.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/admin-style.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'fonts/fontawesome-webfont.woff' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'fonts/fontawesome-webfont.woff2' ?>>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
<?php require_once( SDGS__PLUGIN_DIR . 'admin/actions.php' ); ?>

<div class="container" style="margin-top:60px;height:auto;min-height:2000px;">
	<table id="exampleTable" class="stripe">
		<thead>
		<tr>
			<th>ID</th>
			<th>Indicator</th>
			<th>SDG</th>
			<th>Unit</th>
			<th>Description</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody></tbody>
	</table>

	<div style="display:none" id="div-sub-table" style="background:#337ab7;height:auto;">
		<table id="detailsTable">
			<thead>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Value</th>
				<th>Target Value</th>
				<th>Notes</th>
				<th>Source</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<div id="" class="col-md-12">
		<!-- load edit measurement -->
		<div id="edit-measurement-modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Edit Measurement</h4>
					</div>
					<div class="modal-body">
						<form id="edit-measurement-form" name="edit-measurement">
							<input type="hidden" id="edit-measurement_id"/>
							<input type="hidden" id="edit-measurement_sdg"/>


							<div class="form-group">
								<label for="date">Date:</label>
								<div class="input-group input-append date dateRangePicker">
									<input name="date" type="text" class="form-control" id="edit-date-measurement" placeholder="Date">
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
							<div class="form-group">
								<label for="value-measurement">Value:</label>
								<input name="value_measurement" type="text" class="form-control number-values" id="edit-value-measurement" placeholder="Value">
							</div>
							<div class="form-group">
								<label for="value-target-measurement">Target value:</label>
								<input name="value_target_measurement" type="text" class="form-control number-values" id="edit-target-value-measurement" placeholder="Target value">
							</div>
							<div class="form-group">
								<label for="notes-measurement">Notes:</label>
								<textarea name="edit-notes-measurement" type="text" class="form-control " id="edit-notes-measurement" placeholder="Notes"></textarea>
							</div>
							<div class="form-group">
								<label for="source-measurement">Source:</label>
								<input name="edit-source-measurement" type="text" class="form-control" id="edit-source-measurement" placeholder="Source">
							</div>
							<div class="modal-footer">

								<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
								<input type="submit" value="Save changes" name="edit-measurement" class="btn btn-primary" id="edit-measuremnt-button">

							</div><!-- /.modal-content -->
						</form>
					</div>
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- end of load edit measurement modal -->
		</div>
		<div id="add-measurement-modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Add Measurement</h4>
					</div>
					<div class="modal-body">
						<form id="add-measurement-form" name="add-measurement">
							<input type="hidden" id="measurement_indicator_id"/>
							<input type="hidden" id="measurement_sdg"/>
							<div class="form-group">
								<label for="date">Date:</label>
								<div class="input-group input-append date dateRangePicker">
									<input name="date" type="text" class="form-control" id="date-measurement" placeholder="Date">
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
							<div class="form-group">
								<label for="value-measurement">Value:</label>
								<input name="value_measurement" type="text" class="form-control number-values" id="value-measurement" placeholder="Value">
							</div>
							<div class="form-group">
								<label for="value-target-measurement">Target value:</label>
								<input name="value_target_measurement" type="text" class="form-control number-values" id="target-value-measurement" placeholder="Target value">
							</div>
							<div class="form-group">
								<label for="notes-measurement">Notes:</label>
								<textarea name="notes_measurement" type="text" class="form-control" id="notes-measurement" placeholder="Notes"></textarea>
							</div>
							<div class="form-group">
								<label for="source-measurement">Source:</label>
								<input name="source_measurement" type="text" class="form-control" id="source-measurement" placeholder="Source">
							</div>
							<div class="modal-footer">

								<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
								<input type="submit" value="Save changes" name="add-measurement" class="btn btn-primary" id="add-measuremnt-button">

							</div><!-- /.modal-content -->
						</form>
					</div>
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- end of measurement modal -->
		</div>
		<!-- end of modal measuremtn -->
		<div id="edit-indicator-modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Edit indicator</h4>
					</div>
					<div class="modal-body">
						<form id="edit-indicator-form" name="edit_indicator_form">
							<div class="form-group">
								<label for="indicator">Indicator:</label>
								<input name="indicator"  type="text" class="form-control" id="edit_indicator" placeholder="Indicator">
							</div>
							<input type="hidden" id="edit_indicator_id"/>
							<div class="form-group">
								<label for='sdg'>SDG:</label>
								<select id="edit-sdg-type" name="add_sdg"  class="form-control" title="SDG is required">
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
									<option id="17" value="partnerships-for-the-goal">17-Partnerships for the goal</option>
								</select>
							</div>
							<div class="form-group">
								<label for="edit-unit">Unit:</label>
								<input type="text" name="add_unit" class="form-control number-values" id="edit-unit" placeholder="Unit"/>
							</div>
							<div class="form-group">
								<label for="description">Description:</label>
								<textarea name="description" class="form-control" id="edit-sdg-description" placeholder="Description"></textarea>
							</div>
							<div class="modal-footer">

								<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
								<input type="submit" value="Save changes" name="createInd" class="btn btn-primary" id="edit-indicator-button">

							</div><!-- /.modal-content -->
						</form>
					</div>
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<!-- end of edit modal -->
		</div>


		<div class="col-md-12" style="margin-top:20px">
			<div class="col-md-2">
				<a class="btn btn-success"  style="width:100%;">Export</a>
			</div>
			<div class="col-md-2" >
				<a class="btn btn-primary" href="#add-indicator-modal" data-toggle="modal" id="add-indicator-link" style="width:100%;">+ Add Indicator</a>
			</div>

		</div>


		<!-- Button trigger modal -->

		<div id="add-indicator-modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">x</button>
						<h4 class="modal-title">Add indicator</h4>
					</div>
					<div class="modal-body">
						<form id="add-indicator-form" name="add_indicator"  method="POST">
							<div class="form-group">
								<label for="indicator">Indicator:</label>
								<input name="indicator" type="text" class="form-control" id="indicator" placeholder="Indicator">
							</div>
							<div class="form-group">
								<label for='sdg'>SDG:</label>
								<select id="sdg-type" name="add_sdg"  class="form-control"  title="SDG is required">
									<option value="">Select SDG</option>
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
									<option id="17" value="partnerships-for-the-goal">17-Partnerships for the goal</option>
								</select>
							</div>
							<div class="form-group">
								<label for="unit">Unit:</label>
								<input type="text" name="add_unit" class="form-control number-values" id="unit" placeholder="Unit"/>
							</div>
							<div class="form-group">
								<label for="description">Description:</label>
								<textarea name="description" class="form-control" id="sdg-description" placeholder="Description"></textarea>
							</div>
							<div class="modal-footer">
								<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
								<input type="submit" value="Save changes" name="createInd" class="btn btn-primary" id="add-indicator-button">

							</div>
					</div><!-- /.modal-content -->
					</form>
				</div>

			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</div>
</div>
<script type="text/javascript" charset="utf-8">
	function fnFormatDetails(table_id, html) {
		var sOut = "<table id=\"exampleTable_" + table_id + "\">";
		sOut += html;
		sOut += "</table>";
		return sOut;
	}

	var newRowData = <?php echo json_encode($query_indicators); ?>;
	var iTableCounter = 1;
	var oTable;
	var oInnerTable;
	var detailsTableHtml;
	var oTable;
	//Run On HTML Build
	$(document).ready(function () {

		$('#edit-indicator-form').validate({
			rules: {
				edit_indicator: {
					required: true,
				},
				edit_add_sdg: {
					required: true,

				},
				edit_add_unit: {
					required: true,

				},
			},
			submitHandler: function (form) {
			$.ajax({
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
				type: 'POST', //or POST
				dataType: 'json',
				data: {
					'indicator_id': $('#edit_indicator_id').val(),
					'description': $('#edit-sdg-description').val(),
					'indicator': $('#edit_indicator').val(),
					'sdg': $("#edit-sdg-type").children(":selected").attr("id"),
					'unit':$("#edit-unit").val(),
					'edit_action_indicator': 'edit_indicator_form'
				},
				success: function (data) {
					var indicator_id=$('#edit_indicator_id').val();
					oTable.fnClearTable(0);
					oTable.fnAddData(data);
					oTable.fnDraw();
					$('#edit-indicator-modal').modal('hide');
				}
			});
		}

	});
		$('.dateRangePicker').datepicker({
			autoclose: true
		});
		// you would probably be using templates here
		detailsTableHtml = $("#detailsTable").html();
		//Insert a 'details' column to the table
		var nCloneTh = document.createElement('th');
		var nCloneTd = document.createElement('td');
		nCloneTd.innerHTML = '<img src="<?php echo SDGS__PLUGIN_URL.'img/plus.png' ?>" class="show-sub-table" style="width:20px"/>';
		nCloneTd.className = "center";
		$('#exampleTable thead tr').each(function () {
			this.insertBefore(nCloneTh, this.childNodes[0]);
		});
		$('#exampleTable tbody tr').each(function () {
			this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
		});

		function init_sub_table() {
			$('body').on('click','.show-sub-table',function(e){
				e.preventDefault();
				var indicator_id = $($(this).parent().parent().children()[1]).text();

				var s_id = $($(this).parent().parent().children()[3]).text();
				var nTr = $(this).parents('tr')[0];
				var nTds = this;
				if (oTable.fnIsOpen(nTr)) {

					/* This row is already open - close it */
					this.src = '<?php echo SDGS__PLUGIN_URL.'img/plus.png' ?>';
				}
				else {

					this.src = '<?php echo SDGS__PLUGIN_URL.'img/minus.png' ?>';
				}
				$.ajax({
					url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
					type: 'POST', //or POST
					dataType: 'json',
					data: {'id': indicator_id, 'action_measurement': 'get_indicator_measurement'},
					success: function (data) {

						if (oTable.fnIsOpen(nTr)) {

							/* This row is already open - close it */
							this.src = '<?php echo SDGS__PLUGIN_URL.'img/plus.png' ?>';
							this.id = indicator_id;
							oTable.fnClose(nTr);

						}
						else {

							this.src = '<?php echo SDGS__PLUGIN_URL.'img/minus.png' ?>';
							oTable.fnOpen(nTr, fnFormatDetails(indicator_id, detailsTableHtml), 'details');
							oInnerTable = $("#exampleTable_" + indicator_id).dataTable({
								"bJQueryUI": true,
								"bFilter": true,
								"aaData": data,
								"bSort": true, // disables sorting
								"info": true,
								"aoColumns": [
									{"mDataProp": "id"},
									{"mDataProp": "date"},
									{"mDataProp": "value"},
									{"mDataProp": "target_value"},
									{"mDataProp": "source_url"},
									{"mDataProp": "notes"},
									{"sDefaultContent": "<a data-toggle='modal' href='#edit-measurement-modal' class='edit-modal-measurement' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-measurement'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
								],
								"bPaginate": true,
								"oLanguage": {
									"sInfo": "_TOTAL_ entries"
								},
							});
							$(this).attr('id', indicator_id)
							$('tr.details .dataTables_info').html('');
							$('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + indicator_id + "' data-sdg='" + s_id + "' href='#add-measurement-modal' class='add-measurment btn btn-primary'>+ Add measurement</a>");

						}

					}
				});

			});
		}
		function init_table(newRowData) {
			oTable = $('#exampleTable').dataTable({
				buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				],
				"bJQueryUI": true,
				"aaData": newRowData,
				"bPaginate": true,

				"aoColumns": [
					{
						"mDataProp": null,
						"sClass": "control center",
						"sDefaultContent": '<img src="<?php echo SDGS__PLUGIN_URL.'img/plus.png' ?>" class="show-sub-table" style="width:20px;"/>'
					},
					{"mDataProp": "id"},
					{"mDataProp": "name"},
					{"mDataProp": "short_name"},
					{"mDataProp": "unit"},
					{"mDataProp": "description"},
					{"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},

				],
				"oLanguage": {
					"sInfo": "_TOTAL_ entries"
				},


				"aaSorting": [[1, 'asc']]
			});

		}

		init_table(newRowData);
		init_sub_table();
		$('#add-indicator-form').on('submit', function(e){
			e.preventDefault();
				$.ajax({
					url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
					type: 'POST', //or POST,
					async : true,
					dataType: 'json',
					data: {
						'description': $('#sdg-description').val(),
						'indicator': $('#indicator').val(),
						'unit': $('#unit').val(),
						'sdg': $("#sdg-type").children(":selected").attr("id"),
						'action_indicator': 'add_indicator',

					},
					success: function (data) {
						var indicator_id=$('#indicator').val();
						oTable.fnClearTable(0);
						oTable.fnAddData(data);
						oTable.fnDraw();

						$('.form-control').val('');
						$('#add-indicator-modal').modal('hide');

					}

				});



		});
		$('body').on('click','.edit-modal-measurement',function(e){
			e.preventDefault();
			var measurement_id = $($(this).parent().parent().children()[0]).text();
			$.ajax({
				type: "POST",
				data: {'id': + measurement_id,'action_measurement':'load_measurement'},
				dataType: 'json',
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>",
				success: function (data) {
					console.log(data);
					$('#edit-date-measurement').val(data[0].date);
					$('#edit-value-measurement').val(data[0].value);
					$('#edit-target-value-measurement').val(data[0].target_value);
					$('#edit-notes-measurement').val(data[0].notes);
					$('#edit-source-measurement').val(data[0].source_url);
					$('#edit-measurement_id').val(data[0].id);

					/*$('#edit_indicator').val(data[0].name);
					$('#edit-sdg-type option[value="' + data[0].short_name + '"]').attr('selected', 'selected');
					$('#edit-sdg-description').val(data[0].description);*/
				}
			});
		})


		$('body').on('click', '.add-measurment', function (e) {
			e.preventDefault();
			var indicator_id = $(this).attr('id');
			var sdg_id = $(this).attr('data-sdg');

			$('#measurement_sdg').val(sdg_id);
			$('#measurement_indicator_id').val(indicator_id);
		})

		$('#add-measurement-form').validate({
			rules: {
				date: {
					required: true,

				},
				value_measurement: {
					required: true,

				},
				value_target_measurement: {
					required: true,

				},

			},
			submitHandler: function (form) {

				var indicator_id = $('#measurement_indicator_id').val();
				$.ajax({
					url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
					type: 'POST', //or POST
					dataType: 'json',
					data: {
						'm-sdg': $('#measurement_sdg').val(),
						'indicator_id': $('#measurement_indicator_id').val(),
						'date-m': $('#date-measurement').val(),
						'value-m': $('#value-measurement').val(),
						'target-value-measurement': $('#target-value-measurement').val(),
						'notes': $("#notes-measurement").val(),
						'source-m': $("#source-measurement").val(),
						'action-measurement': 'add-measurement'
					},
					success: function (data) {
						var indicator_id = data[0].iid;
						var s_id = data[0].sid;
						$('#exampleTable_' + indicator_id).dataTable().fnDestroy();
						console.log(data);
						oInnerTable = $("#exampleTable_" + indicator_id).dataTable({

							"bFilter": true,
							"aaData": data,
							"bSort": true, // disables sorting
							"aoColumns": [
								{"mDataProp": "id"},
								{"mDataProp": "date"},
								{"mDataProp": "value"},
								{"mDataProp": "target_value"},
								{"mDataProp": "source_url"},
								{"mDataProp": "notes"},
								{"sDefaultContent": "<a data-toggle='modal' href='#edit-measurement-modal' class='edit-modal-measurement' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-measurement'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
							],
							"bPaginate": true,
							"oLanguage": {
								"sInfo": "_TOTAL_ entries"
							},

						});
						$('tr.details .dataTables_info').html('');
						$('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + indicator_id + "' data-sdg='" + s_id + "' href='#add-measurement-modal' class='add-measurment btn btn-primary'>+ Add measurement</a>");
						$('#add-measurement-modal').modal('hide');
						$('#add-measurement-form')[0].reset();
					}

				});


			}
		});
		$('#edit-measurement-form').validate({
			rules: {
				date: {
					required: true,

				},
				value_measurement: {
					required: true,

				},
				value_target_measurement: {
					required: true,

				},
			},
			submitHandler: function (form) {

				var measurement_id = $('#edit-measurement_id').val();
				$.ajax({
					url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
					type: 'POST', //or POST
					dataType: 'json',
					data: {
						'meausrement_id': measurement_id,
						'date-m': $('#edit-date-measurement').val(),
						'value-m': $('#edit-value-measurement').val(),
						'target-value-measurement': $('#edit-target-value-measurement').val(),
						'notes': $("#edit-notes-measurement").val(),
						'source-m': $("#edit-source-measurement").val(),
						'action-measurement': 'update-measurement'
					},
					success: function (data) {

						var indicator_id = data[0].iid;
						var s_id = data[0].sid;
						$('#exampleTable_' + indicator_id).dataTable().fnDestroy();
						oInnerTable = $("#exampleTable_" + indicator_id).dataTable({

							"bJQueryUI": true,
							"aaData": data,
							"bSort": true, // disables sorting
							"aoColumns": [
								{"mDataProp": "id"},
								{"mDataProp": "date"},
								{"mDataProp": "value"},
								{"mDataProp": "target_value"},
								{"mDataProp": "source_url"},
								{"mDataProp": "notes"},
								{"sDefaultContent": "<a data-toggle='modal' href='#edit-measurement-modal' class='edit-modal-measurement' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-measurement'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
							],
							"bPaginate": true,
							"oLanguage": {
								"sInfo": "_TOTAL_ entries"
							},

						});
						$('tr.details .dataTables_info').html('');
						$('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + indicator_id + "' data-sdg='" + s_id + "' href='#add-measurement-modal' class='add-measurment btn btn-primary'>+ Add measurement</a>");
						$('#edit-measurement-modal').modal('hide');
						$('.form-control').val('');
					}



				});

			}
		});


		$('body').on('click', '.edit-modal-indicator', function (e) {
			var indicator_id = $($(this).parent().parent().children()[1]).text();
			console.log(indicator_id);
			

			$.ajax({
				type: "GET",
				data: 'id=' + indicator_id,
				dataType: 'json',
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/load_indicator.php' ?>",
				success: function (data) {
					console.log(data);
					$('#edit_indicator_id').val(data[0].id);
					$('#edit_indicator').val(data[0].name);
					$('#edit-unit').val(data[0].unit);
					$('#edit-sdg-type option[value="' + data[0].short_name + '"]').attr('selected', 'selected');
					$('#edit-sdg-description').val(data[0].description);
				}
			});
			e.preventDefault();
		})




		$('body').on('click', '.remove-indicator', function (e) {
			e.preventDefault();
			var indicator_id = $($(this).parent().parent().children()[1]).text();
			var check_if_is_empty=0;
			$.ajax({
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
				type: 'POST', //or POST
				dataType: 'json',
				data: {'id': indicator_id, 'action_indicator': 'check_indicator_is_empty'},
				success: function (data) {
					check_if_is_empty=data['a'];
					if(check_if_is_empty==1){
						BootstrapDialog.show({
							message: 'The indicator has measurements, are you sure you want to delete',
							buttons: [{
								icon: 'glyphicon glyphicon-send',
								label: 'OK',
								cssClass: 'btn-primary',
								autospin: false,
								action: function(dialogRef){
									$.ajax({
										url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
										type: 'POST', //or POST
										dataType: 'json',
										data: {'id': indicator_id, 'action_indicator': 'remove_indicator_measurements'},
										success: function (data) {
											oTable.fnClearTable(0);
											oTable.fnAddData(data);
											oTable.fnDraw();


										}
									});
									setTimeout(function(){
										dialogRef.close();
									}, 100);
								}
							}, {
								label: 'Close',
								action: function(dialogRef){
									dialogRef.close();
								}
							}]
						});
					}else{
						BootstrapDialog.show({
							message: 'Are you sure you want to delete the indicator?',
							buttons: [{
								icon: 'glyphicon glyphicon-send',
								label: 'OK',
								cssClass: 'btn-primary',
								autospin: false,
								action: function(dialogRef){
									$.ajax({
										url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
										type: 'POST', //or POST
										dataType: 'json',
										data: {'id': indicator_id, 'action_indicator': 'remove_indicator'},
										success: function (data) {
											oTable.fnClearTable(0);
											oTable.fnAddData(data);
											oTable.fnDraw();

										}
									});
									setTimeout(function(){
										dialogRef.close();
									}, 100);
								}
							}, {
								label: 'Close',
								action: function(dialogRef){
									dialogRef.close();
								}
							}]
						});
					}
				}
			});


		})
		$('body').on('click','.remove-measurement',function(e){
			e.preventDefault();
			var measurement_id = $($(this).parent().parent().children()[0]).text();
			console.log(measurement_id);
			var check_if_is_empty;
			console.log(measurement_id);
			$.ajax({
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
				type: 'POST', //or POST
				dataType: 'json',
				data: {'id': measurement_id, 'action_measurement': 'check_measurement_table'},
				success: function (data) {
					check_if_is_empty=data['a'];
					console.log(check_if_is_empty);
					if(check_if_is_empty==1){
						BootstrapDialog.show({
							message: 'Are you sure you want to delete the measurement?',
							buttons: [{
								icon: 'glyphicon glyphicon-send',
								label: 'OK',
								cssClass: 'btn-primary',
								autospin: false,
								action: function(dialogRef){
									$.ajax({
										url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
										type: 'POST', //or POST
										dataType: 'json',
										data: {
											'id': measurement_id,
											'action-measurement': 'remove-measurement'
										},
										success: function (data) {
											var indicator_id = data[0].iid;
											var s_id = data[0].sid;
											$('#exampleTable_' + indicator_id).dataTable().fnDestroy();

											oInnerTable = $("#exampleTable_" + indicator_id).dataTable({

												"bJQueryUI": true,
												"aaData": data,
												"bSort": true, // disables sorting
												"aoColumns": [
													{"mDataProp": "id"},
													{"mDataProp": "date"},
													{"mDataProp": "value"},
													{"mDataProp": "target_value"},
													{"mDataProp": "source_url"},
													{"mDataProp": "notes"},
													{"sDefaultContent": "<a data-toggle='modal' href='#edit-measurement-modal' class='edit-modal-measurement' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-measurement'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},
												],
												"bPaginate": true,

												"oLanguage": {
													"sInfo": "_TOTAL_ entries"
												},


											});
											$('tr.details .dataTables_info').html('');
											$('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + indicator_id + "' data-sdg='" + s_id + "' href='#add-measurement-modal' class='add-measurment btn btn-primary'>+ Add measurement</a>");
											$('#edit-measurement-modal').modal('hide');
											$('.form-control').val('');
										}

									});
									setTimeout(function(){
										dialogRef.close();
									}, 100);
								}
							}, {
								label: 'Close',
								action: function(dialogRef){
									dialogRef.close();
								}
							}]
						});
					}else{
						BootstrapDialog.show({
							message: 'Are you sure you want to delete the indicator?',
							buttons: [{
								icon: 'glyphicon glyphicon-send',
								label: 'OK',
								cssClass: 'btn-primary',
								autospin: false,
								action: function(dialogRef){
									$.ajax({
										url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
										type: 'POST', //or POST
										dataType: 'json',
										data: {'id': measurement_id, 'action_measurement': 'remove_last_measurement_indicator'},
										success: function (data) {
											init_sub_table();
											$('#exampleTable').dataTable().fnDestroy();
											init_table(data);
										}
									});
									setTimeout(function(){
										dialogRef.close();
									}, 100);
								}
							}, {
								label: 'Close',
								action: function(dialogRef){
									dialogRef.close();
								}
							}]
						});
					}
				}
			});
			});


		var indicators_array = <?php echo json_encode($query_indicators); ?>;
	});




	</script>


<style>
#exampleTable_wrapper .col-sm-6{
	padding-bottom: 10px;
	padding-top: 10px;
}
	.show-sub-table{
		cursor: pointer;
	}
	#date-measurement-error, #edit-date-measurement-error{
		display: block;
		clear: both;
		position: relative;
		float: right;
		clear: right;
		float: left;
		margin-top: -58px;
		margin-left: 56px;
	}
	.show-hidden{

	}
	.blue-back{
		background: #00a0d2;

	}
	.btn{
		border-radius:0px;
	}
	table.dataTable.no-footer{
		border-bottom:1px solid #00a0d2;
	}
	table.dataTable.no-footer{
		border-top:1px solid #00a0d2;
	}
	table.dataTable thead th, table.dataTable thead td{
		border-bottom:#00a0d2;
	}
#exampleTable thead tr{
	background: #00a0d2;
	color:white;
}
	.modal-open .modal{
		margin-top:20px;
	}
	.modal-header{
		background:#428bca;
		color:#ffffff;
	}
	a:focus{
		-webkit-box-shadow: 0px;
	}
.wp-admin select{
	height:34px;
}
</style>
<script>
	$('.number-values').keypress(function(eve) {
		if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this)== 0)) {
			eve.preventDefault();
		}
	})

</script>

