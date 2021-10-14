<?php 

function get_state_name($state) {
	return in_array($state['id'],$_GET['states']) ? true :false;
}
function get_name_only($state) {
	return $state['name'];
}
$queried_states=isset($_GET['states']) ? implode(",",array_map("get_name_only",array_filter($get_states,"get_state_name"))) : 'ALL';
?>
<div class="container">
	<div class="panel">
		<div class="panel-body">
			<form method="GET" action="client_list.php">
				<div class="row">
					<div class="col-lg-4">
						<div class="form-group">
							<select class="states" name="states[]" multiple="multiple">
								<?php foreach($get_states as $state): ?>
									<option value="<?php echo $state['id']; ?>" <?php //echo in_array($state['id'],$_GET['states']) ? 'selected' : '' ?>><?php echo $state['name']; ?></option>
								<?php endforeach; ?>	
							</select>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<select class="brokers" name="brokers[]" multiple="multiple">
								<?php foreach($get_brokers as $broker): ?>
									<option value="<?php echo $broker['id']; ?>"><?php echo $broker['first_name'].' '.$broker['last_name']; ?></option>
								<?php endforeach; ?>	
							</select>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<button>Search</button>
							<a href="javascript:;" id="clear_filters" style="margin-left: 10px;">Clear Filters</a>
						</div>
					</div>
				</div>
			</form>
			<div class="print_section">
		<div class="print_head">
			<div class="date">
				<p><?php echo date('d/m/Y'); ?></p>
			</div>
			<div class="main_title">
				<p>
					Client List By State <span>State: <?php echo $queried_states; ?></span>
				</p>
			</div>
			<div class="page_no">
				<p>
					Page No.<?php echo $page_no; ?>
				</p>
			</div>
		</div>
		<div class="print_content">
			<table>
				<thead>
					<tr>
						<th>Client Name/Long Name</th>
						<th>Client No.</th>
						<th>Telephone</th>
						<th>Open Date</th>
						<th>Birth Date</th>
						<th>Rep #</th>
						<th>Address</th>
					</tr>
					<tr>
						<th colspan="7">
                           <hr>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$last_record=0;
					foreach($client_list as $client):
						if($client['broker_name']!=$last_record):
					 ?>
					<tr>
						<td colspan="7">
							<h6>Broker: <?php echo $client['bfname'].' '.$client['lfname']; ?></h6>
							<?php endif; ?>
							<tr>
								<td><?php echo $client['first_name'].' '.$client['last_name']; ?></td>
								<td><?php echo $client['client_ssn']; ?></td>
								<td><?php echo $client['telephone']; ?></td>
								<td><?php echo date('d-m-Y H:i',strtotime($client['open_date'])); ?></td>
								<td><?php echo date('d-m-Y H:i',strtotime($client['birth_date'])); ?></td>
								<td><?php echo $client['file_id']; ?></td>
								<td><?php echo $client['address1']; ?></td>
							</tr>
						</td>
					</tr>
					<?php 
				$last_record=$client['broker_name'];
			endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
			<ul class="pagination">
				<?php 
				$query_string=[];
				$data=[];
				if (isset($_GET['brokers'])) {
					$data=[
						'brokers'=>$_GET['brokers'],
					];
				}
				if (isset($_GET['states'])) {
					$data=[
						'states'=>$_GET['states'],
					];
				}
				if($page_no > 1) {
					$data['page_no']=1;
					$query= http_build_query($data,'','&amp;');
					echo "<li><a href=?'".$query.">First Page</a></li>";
				} ?>
				    
				<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
				<a <?php if($page_no > 1) {
					$data['page_no']=$previous_page;
					$query= http_build_query($data,'','&amp;');
					echo "href=?".$query;
				} ?>>Previous</a>
				</li>
				    
				<li <?php if($page_no >= $total_no_of_pages){
				echo "class='disabled'";
				} ?>>
				<a <?php if($page_no < $total_no_of_pages) {
				$data['page_no']=$next_page;
				$query= http_build_query($data,'','&amp;');
				echo "href=?".$query;
				} ?>>Next</a>
				</li>

				<?php if($page_no < $total_no_of_pages){
					$data['page_no']=$total_no_of_pages;
					$query= http_build_query($data,'','&amp;');
				echo "<li><a href=?".$query.">Last &rsaquo;&rsaquo;</a></li>";
				} ?>
			</ul>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		
		$(".states").multiselect({
			columns: 3,
            placeholder: 'Select States',
            search: true,
            searchOptions: {
                'default': 'Search States'
            },
            selectAll: true
			// width:'100%'
		});
		$(".brokers").multiselect({
			columns: 3,
            placeholder: 'Select Brokers',
            search: true,
            searchOptions: {
                'default': 'Search Brokers'
            },
            selectAll: true
			// width:'100%'
		});
	});
		/*$('.states').on("select2:select", function (e) { 
           var data = e.params.data.text;
           if(data=='All'){
            $(".states > option").prop("selected","selected");
            $(".states").trigger("change");
           }
      	});
      	$('.brokers').on("select2:select", function (e) { 
           var data = e.params.data.text;
           if(data=='All'){
            $(".brokers > option").prop("selected","selected");
            $(".brokers").trigger("change");
           }
      	});*/
      	$("#clear_filters").on('click', function(event) {
      		event.preventDefault();
      		$('.states').val(null).trigger('change');
      		$('.brokers').val(null).trigger('change');
      	});
</script>