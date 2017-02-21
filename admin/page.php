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

<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/font-awesome.min.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'css/admin-style.css' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'fonts/fontawesome-webfont.woff' ?>>
<link rel="stylesheet" href=<?php echo SDGS__PLUGIN_URL.'fonts/fontawesome-webfont.woff2' ?>>
<?php require_once( SDGS__PLUGIN_DIR . 'admin/actions.php' ); ?>

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

		// you would probably be using templates here
		detailsTableHtml = $("#detailsTable").html();

		//Insert a 'details' column to the table
		var nCloneTh = document.createElement('th');
		var nCloneTd = document.createElement('td');
		nCloneTd.innerHTML = '<img src="http://i.imgur.com/SD7Dz.png">';
		nCloneTd.className = "center";

		$('#exampleTable thead tr').each(function () {
			this.insertBefore(nCloneTh, this.childNodes[0]);
		});

		$('#exampleTable tbody tr').each(function () {
			this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
		});
		init_table(newRowData);
		// function to get the data table
		function init_sub_table() {
			$('body').on('click', '#exampleTable tbody td img', function (e) {
				e.preventDefault();
				var indicator_id = $($(this).parent().parent().children()[1]).text();
				var s_id = $($(this).parent().parent().children()[3]).text();
				var nTr = $(this).parents('tr')[0];
				var nTds = this;
				if (oTable.fnIsOpen(nTr)) {
					/* This row is already open - close it */
					this.src = "http://i.imgur.com/SD7Dz.png";
				}
				else {

					this.src = "http://i.imgur.com/d4ICC.png";
				}
				$.ajax({
					url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
					type: 'POST', //or POST
					dataType: 'json',
					data: {'id': indicator_id, 'action_measurement': 'get_indicator_measurement'},
					success: function (data) {

						if (oTable.fnIsOpen(nTr)) {
							/* This row is already open - close it */
							this.src = "http://i.imgur.com/SD7Dz.png";
							oTable.fnClose(nTr);
						}
						else {
							/* Open this row */
							var rowIndex = oTable.fnGetPosition($(nTds).closest('tr')[0]);
							this.src = "http://i.imgur.com/d4ICC.png";
							oTable.fnOpen(nTr, fnFormatDetails(indicator_id, detailsTableHtml), 'details');
							oInnerTable = $("#exampleTable_" + indicator_id).dataTable({
								"bJQueryUI": true,
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
									{"sDefaultContent": "<a data-toggle='modal' href='#edit-measurement-modal' class='edit-modal-measurement' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-measurement'><i class='fa fa-trash-o fa-lg remove-indicator' aria-hidden='true'></i></a>"},
								],
								"bPaginate": true,
								"oLanguage": {
									"sInfo": "_TOTAL_ entries"
								},
							});
							$(this).attr('id', indicator_id)
							$('tr.details .dataTables_info').html('');
							$('tr.details .dataTables_info').append("<a data-toggle='modal' id='" + indicator_id + "' data-sdg='" + s_id + "' href='#add-measurement-modal' class='add-measurment btn btn-default'>+ Add measurement</a>");
						}
					}
				});

			});
		}
		function init_table(newRowData) {
			oTable = $('#exampleTable').dataTable({
				dom: 'Bfrtip',
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
						"sDefaultContent": '<img src="http://i.imgur.com/SD7Dz.png" class="show-sub-table">'
					},
					{"mDataProp": "id"},
					{"mDataProp": "name"},
					{"mDataProp": "short_name"},
					{"mDataProp": "description"},
					{"sDefaultContent": "<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-indicator'><i class='fa fa-trash-o fa-lg' aria-hidden='true'></i></a>"},

				],
				"oLanguage": {
					"sInfo": "_TOTAL_ entries"
				},
				"aaSorting": [[1, 'asc']]
			});
			console.log(newRowData);
			init_sub_table();
		}
		$('body').on('click','.edit-modal-measurement',function(){
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


		$('body').on('click', '.add-measurment', function () {
			var indicator_id = $(this).attr('id');
			var sdg_id = $(this).attr('data-sdg');

			$('#measurement_sdg').val(sdg_id);
			$('#measurement_indicator_id').val(indicator_id);
		})
		$('#add-measurement-modal').on('submit', function (e) {
			e.preventDefault();
			var indicator_id=$('#measurement_indicator_id').val();
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
					'source-m':$("#source-measurement").val(),
					'action-measurement': 'add-measurement'
				},
				success: function (data) {
						$('#exampleTable_'+ indicator_id).dataTable().fnDestroy();
						console.log(data);
						oInnerTable = $("#exampleTable_" + indicator_id).dataTable({
							"bJQueryUI": true,
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
								{"sDefaultContent": "<a data-toggle='modal' href='#edit-measurement-modal' class='edit-modal-measurement' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-measurement'><i class='fa fa-trash-o fa-lg remove-measurement' aria-hidden='true'></i></a>"},
							],
							"bPaginate": true,
							"oLanguage": {
								"sInfo": "_TOTAL_ entries"
							},

						});

						$('#add-measurement-modal').modal('hide');
					}

			});

			$('.show-sub-table').on('click',function(e){
				e.preventDefault();
			})
		});
		$('#edit-measurement-modal').on('submit', function (e) {
			e.preventDefault();
			var measurement_id=$('#edit-measurement_id').val();
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
					'source-m':$("#edit-source-measurement").val(),
					'action-measurement': 'update-measurement'
				},
				success: function (data) {
					var indicator_id=data[0].iid;
					$('#exampleTable_'+ indicator_id).dataTable().fnDestroy();
					console.log(data);
					oInnerTable = $("#exampleTable_" + indicator_id).dataTable({
						"bJQueryUI": true,
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
							{"sDefaultContent": "<a data-toggle='modal' href='#edit-measurement-modal' class='edit-modal-measurement' id=''><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#' class='remove-measurement'><i class='fa fa-trash-o fa-lg remove-measurement' aria-hidden='true'></i></a>"},
						],
						"bPaginate": true,
						"oLanguage": {
							"sInfo": "_TOTAL_ entries"
						},

					});

					$('#add-measurement-modal').modal('hide');
					$('#edit-measurement-modal').modal('hide');
				}


			});

			$('.show-sub-table').on('click',function(e){
				e.preventDefault();
			})
		});
		$('.show-sub-table').on('click',function(e){
			e.preventDefault();
		})
		$('body').on('click', '.edit-modal-indicator', function () {
			var indicator_id = $($(this).parent().parent().children()[1]).text();
			$.ajax({
				type: "GET",
				data: 'id=' + indicator_id,
				dataType: 'json',
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/load_indicator.php' ?>",
				success: function (data) {
					$('#edit_indicator_id').val(data[0].id);
					$('#edit_indicator').val(data[0].name);
					$('#edit-sdg-type option[value="' + data[0].short_name + '"]').attr('selected', 'selected');
					$('#edit-sdg-description').val(data[0].description);
				}
			});
		})
		$('#edit-indicator-form').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
				type: 'POST', //or POST
				dataType: 'json',
				data: {
					'indicator_id': $('#edit_indicator_id').val(),
					'description': $('#edit-sdg-description').val(),
					'indicator': $('#edit_indicator').val(),
					'sdg': $("#edit-sdg-type").children(":selected").attr("id"),
					'edit_action_indicator': 'edit_indicator_form'
				},
				success: function (data) {
					$('#exampleTable').dataTable().fnDestroy();
					init_table(data);
					$('#edit-indicator-modal').modal('hide');
					init_sub_table();
				}
			});
			$('.show-sub-table').on('click',function(e){
				e.preventDefault();
			})
		});
		$('#add-indicator-form').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
				type: 'POST', //or POST
				dataType: 'json',
				data: {
					'description': $('#sdg-description').val(),
					'indicator': $('#indicator').val(),
					'sdg': $("#sdg-type").children(":selected").attr("id"),
					'action_indicator': 'add_indicator'
				},
				success: function (data) {
					$('#exampleTable').dataTable().fnDestroy();
					init_sub_table();
					init_table(data);

					$('#add-indicator-modal').modal('hide');

				}
			});
		});


		$('body').on('click', '.remove-indicator', function (e) {
			e.preventDefault();
			var indicator_id = $($(this).parent().parent().children()[1]).text();
			var r = confirm("Are you sure to delete?");
			$('.show-sub-table').on('click',function(e){
				e.preventDefault();
			})
			if (r == true) {
				$.ajax({
					url: "<?php echo SDGS__PLUGIN_URL . 'admin/actions.php' ?>", //this is the submit URL
					type: 'POST', //or POST
					dataType: 'json',
					data: {'id': indicator_id, 'action_indicator': 'remove_indicator'},
					success: function (data) {
						init_sub_table();
						$('#exampleTable').dataTable().fnDestroy();
						init_table(data);
					}
				});
			} else {

			}
		})
		var indicators_array = <?php echo json_encode($query_indicators); ?>;
	})
		//Initialse DataTables, with no sorting on the 'details' column

		/*for (index in indicators_array) {
			$('#exampleTable').DataTable().row.add([
				indicators_array[index]['id'],
				indicators_array[index]['name'] + '</br><a href="#" id="'+indicators_array[index]['id']+'" class="show_data_table" >Show Data</a>',
				capitalizeFirstLetter(indicators_array[index]['short_name'].replace(/\-/g, ' ')),
				indicators_array[index]['description'],
				"<a data-toggle='modal' href='#edit-indicator-modal' class='edit-modal-indicator' id='" + indicators_array[index]['id'] + "'><i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i></a>" + "<a href='#'><i class='fa fa-trash-o fa-lg remove-indicator' aria-hidden='true'></i></a>"
			]).draw();
		}*/


		/* Add event listener for opening and closing details
		 * Note that the indicator for showing which row is open is not controlled by DataTables,
		 * rather it is done here
		 */




	</script>
<div class="row">
	<div id="wrapper" class="col-md-12">
		<!-- load edit measurement -->
		<div id="edit-measurement-modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">×</button>
						<h4 class="modal-title">Edit Measurement</h4>
					</div>
					<div class="modal-body">
						<form id="edit-measurement-form" name="edit-measurement">
							<input type="hidden" id="edit-measurement_id"/>
							<input type="hidden" id="edit-measurement_sdg"/>
							<div class="form-group">
								<label for="date">Date:</label>
								<input name="edit-date" type="date" class="form-control" id="edit-date-measurement" placeholder="Date">
							</div>
							<div class="form-group">
								<label for="value-measurement">Value:</label>
								<input name="edit-value-measurement" type="text" class="form-control" id="edit-value-measurement" placeholder="Value">
							</div>
							<div class="form-group">
								<label for="value-target-measurement">Target value:</label>
								<input name="edit-value-target-measurement" type="text" class="form-control" id="edit-target-value-measurement" placeholder="Target value">
							</div>
							<div class="form-group">
								<label for="notes-measurement">Notes:</label>
								<input name="edit-notes-measurement" type="text" class="form-control" id="edit-notes-measurement" placeholder="Notes">
							</div>
							<div class="form-group">
								<label for="source-measurement">Source:</label>
								<input name="edit-source-measurement" type="text" class="form-control" id="edit-source-measurement" placeholder="Source">
							</div>
							<div class="modal-footer">

								<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
								<input type="submit" value="Save changes" name="edit-measurement" class="btn btn-default" id="edit-measuremnt-button">

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
						<button class="close" type="button" data-dismiss="modal">×</button>
						<h4 class="modal-title">Add Measurement</h4>
					</div>
					<div class="modal-body">
						<form id="add-measurement-form" name="add-measurement">
							<input type="hidden" id="measurement_indicator_id"/>
							<input type="hidden" id="measurement_sdg"/>
							<div class="form-group">
								<label for="date">Date:</label>
								<input name="date" type="date" class="form-control" id="date-measurement" placeholder="Date">
							</div>
							<div class="form-group">
								<label for="value-measurement">Value:</label>
								<input name="value-measurement" type="text" class="form-control" id="value-measurement" placeholder="Value">
							</div>
							<div class="form-group">
								<label for="value-target-measurement">Target value:</label>
								<input name="value-target-measurement" type="text" class="form-control" id="target-value-measurement" placeholder="Target value">
							</div>
							<div class="form-group">
								<label for="notes-measurement">Notes:</label>
								<input name="notes-measurement" type="text" class="form-control" id="notes-measurement" placeholder="Notes">
							</div>
							<div class="form-group">
								<label for="source-measurement">Source:</label>
								<input name="source-measurement" type="text" class="form-control" id="source-measurement" placeholder="Source">
							</div>
							<div class="modal-footer">

								<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
								<input type="submit" value="Save changes" name="add-measurement" class="btn btn-default" id="add-measuremnt-button">

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
				<button class="close" type="button" data-dismiss="modal">×</button>
				<h4 class="modal-title">Edit indicator</h4>
			</div>
			<div class="modal-body">
				<form id="edit-indicator-form" name="edit_indicator_form">
					<div class="form-group">
						<label for="indicator">Indicator:</label>
						<input name="indicator" type="text" class="form-control" id="edit_indicator" placeholder="Indicator">
					</div>
					<input type="hidden" id="edit_indicator_id"/>
					<div class="form-group">
						<label for='sdg'>SDG:</label>
						<select id="edit-sdg-type" name="sdg"  class="form-control"  title="Choose 2-4 colors">
							<option id="1" value="poverty">Poverty</option>
							<option id="2" value="zero-hunger">Zero hunger</option>
							<option id="3" value="good-health-and-well-being">Good health and well being</option>
							<option id="4" value="quality-education">Quality education</option>
							<option id="5" value="gender-equality">Gender equality</option>
							<option id="6" value="clean-water-and-sanitation">Clean water and sanitation</option>
							<option id="7" value="affordable-and-clean-energy">Affordable and clean energy</option>
							<option id="8" value="decent-work-and-economic-growth">Decent work and economic growth</option>
							<option id="9" value="industry-innovation-and-infrastructure">Industry innovation and infrastructure</option>
							<option id="10" value="reduced-inequalities">Reduced Inequalities</option>
							<option id="11" value="sustainable-cities-and-communities">Sustainable cities and communities</option>
							<option id="12" value="responsible-consumption-and-production">Responsible consumption and production</option>
							<option id="13" value="climate-action">Climate action</option>
							<option id="14" value="life-below-water">Life below water</option>
							<option id="15" value="life-on-land">Life on land</option>
							<option id="16" value="peace-justice-and-strong-institutions">Peace justice and strong institutions</option>
							<option id="17" value="partnerships-for-the-goal">Partnerships for the goal</option>
						</select>
					</div>
					<div class="form-group">
						<label for="description">Description:</label>
						<textarea name="description" class="form-control" id="edit-sdg-description" placeholder="Description"></textarea>
					</div>
					<div class="modal-footer">

							<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
							<input type="submit" value="Save changes" name="createInd" class="btn btn-default" id="edit-indicator-button">

					</div><!-- /.modal-content -->
				</form>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- end of edit modal -->
</div>
<table id="exampleTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>Indicator</th>
		<th>SDG</th>
		<th>Description</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody></tbody>
</table>

<div style="display:none" id="div-sub-table">
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
		<div class="col-md-3">
			<a class="btn btn-default" >Export</a>
			<!-- Button trigger modal -->
			<a class="btn btn-default" href="#add-indicator-modal" data-toggle="modal" id="add-indicator-link">+ Add Indicator</a>
			<div id="add-indicator-modal" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">Add indicator</h4>
						</div>
						<div class="modal-body">
							<form id="add-indicator-form" name="add_indicator">
								<div class="form-group">
									<label for="indicator">Indicator:</label>
									<input name="indicator" type="text" class="form-control" id="indicator" placeholder="Indicator">
								</div>
								<div class="form-group">
									<label for='sdg'>SDG:</label>
									<select id="sdg-type" name="sdg"  class="form-control"  title="Choose 2-4 colors">
										<option id="1" value="poverty">Poverty</option>
										<option id="2" value="zero-hunger">Zero hunger</option>
										<option id="3" value="good-health-and-well-being">Good health and well being</option>
										<option id="4" value="quality-education">Quality education</option>
										<option id="5" value="gender-equality">Gender equality</option>
										<option id="6" value="clean-water-and-sanitation">Clean water and sanitation</option>
										<option id="7" value="affordable-and-clean-energy">Affordable and clean energy</option>
										<option id="8" value="decent-work-and-economic-growth">Decent work and economic growth</option>
										<option id="9" value="industry-innovation-and-infrastructure">Industry innovation and infrastructure</option>
										<option id="10" value="reduced-inequalities">Reduced Inequalities</option>
										<option id="11" value="sustainable-cities-and-communities">Sustainable cities and communities</option>
										<option id="12" value="responsible-consumption-and-production">Responsible consumption and production</option>
										<option id="13" value="climate-action">Climate action</option>
										<option id="14" value="life-below-water">Life below water</option>
										<option id="15" value="life-on-land">Life on land</option>
										<option id="16" value="peace-justice-and-strong-institutions">Peace justice and strong institutions</option>
										<option id="17" value="partnerships-for-the-goal">Partnerships for the goal</option>
									</select>
								</div>
								<div class="form-group">
									<label for="description">Description:</label>
									<textarea name="description" class="form-control" id="sdg-description" placeholder="Description"></textarea>
								</div>
								<div class="modal-footer">
									<button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
									<input type="submit" value="Save changes" name="createInd" class="btn btn-default" id="add-indicator-button">

								</div>
						</div><!-- /.modal-content -->
						</form>
					</div>

				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
</div>
	</div>

