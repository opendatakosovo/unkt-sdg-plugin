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
<?php require_once( SDGS__PLUGIN_DIR . 'admin/add_indicator.php' ); ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {

		// Add Datatable
		var table = $('#sdg-table').DataTable({
			dom: 'Bfrtip',
			buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        	],
        	"columns": [
        		{ "width": "5%" },
			    { "width": "15%" },
			    { "width": "15%" },
			    { "width": "45%" },
			    { "width": "5%" },
			]
		});


		// Get indicators
		var indicators_array = <?php echo json_encode($query_indicators); ?>;

		// Add the registered indicators on the table.
		for(index in indicators_array){
			$('#sdg-table').DataTable().row.add([
						indicators_array[index]['id'],
						indicators_array[index]['name'] + '</br><a class="show_data_table" >Show Data</a>',
						indicators_array[index]['name'],
						indicators_array[index]['description'],
						"<i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i>" + "<i class='fa fa-trash-o fa-lg remove-indicator' aria-hidden='true'></i>"
		    ]).draw();
		}

		// Save the indicator on form submit.
		$('#add-indicator-form').submit(function(e){
			e.preventDefault();
			var post_url = "";
			var data = {
				'name':$("#indicator").val(),
				'sdg':$("#sdg-type").children(":selected").text(),
				'sid': $("#sdg-type").children(":selected").attr("id"),
				'description': $('#sdg-description').val()
			};
			var jqxhr = $.post( post_url , data, function() {
			}).done(function() {
				$('#sdg-table').DataTable().row.add(
					[	"ID",
						data['name'] + '</br><a class="show_data_table">Show Data</a>',
						data['sdg'],
						data['description'],
						"<i class='fa fa-pencil-square-o fa-lg edit-indicator' aria-hidden='true'></i>" + "<i class='fa fa-trash-o  fa-lg remove-indicator' aria-hidden='true'></i>"
					]).draw();
				location.reload();
			}).fail(function() {
			   alert( "Error adding SDG Indicator, please try again!" );
			});
			// Hide/Close modal form
			$('#add-indicator-modal').modal('hide');
			// Clear form data.
			$(this).find('input[type=text], input[type=password], input[type=number], input[type=email], textarea').val('');

		});

		$( ".show_data_table" ).bind( "click", function(e) {
		  // TODO: Implement Show data table
		});

		// Remove indicator on remove icon click
		$('body').on('click','.remove-indicator', function(){
			var id = $($(this).parent().parent().children()[0]).text();   
			var remove_post_url  = "<?php echo SDGS__PLUGIN_URL.'admin/remove_indicator.php'?>";
			var remove_data = {
				'id': id
			};
			$.post( remove_post_url , remove_data);
			// After succesfully removing the row from the database, we also remove the row in the table.
			table.row( $(this).parents('tr') ).remove().draw();
		})
		$('.edit-indicator').click(function(){
			// TODO: Implement Edit indicator
		})

	} );
</script>

<div class="row">
	<div id="wrapper" class="col-md-12">
		<table id="sdg-table" class="display " cellspacing="0" >
		    <thead>
		        <tr>
		        	<th>ID</th>
		            <th>Name</th>
		            <th>SDG</th>
		            <th>Description</th>
		            <th>Action</th>
		        </tr>
		    </thead>
		    <tbody>
		    </tbody>
		</table>
		<div class="col-md-3">
			<a class="btn btn-default" >Export</a>
			<!-- Button trigger modal -->
			<a class="btn btn-default" href="#add-indicator-modal" data-toggle="modal">+ Add Indicator</a>

			<!-- Modal -->
			<div id="add-indicator-modal" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
								<h4 class="modal-title">Add indicator</h4>
						</div>
						<div class="modal-body">
							<form id="add-indicator-form" name="add_indicator" method='POST' action=<?php echo SDGS__PLUGIN_URL.'admin/add_indicator.php' ?> >

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
									<button id="add-indicator-button" class="btn btn-default" type="submit">Save changes</button>
								</div>
							</div><!-- /.modal-content -->
							</form>
						</div>
						
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</div>
	</div>
</div>
