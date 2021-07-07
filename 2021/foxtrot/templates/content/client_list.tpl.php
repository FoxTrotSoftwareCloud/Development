<div class="container">
	<div class="panel">
		<div class="panel-body">
			<form method="GET" action="client_list.php">
				<select class="custom-select states" name="states[]" multiple="multiple">
					<option value="All">All</option>
					<?php foreach($get_states as $state): ?>
						<option value="<?php echo $state['id']; ?>"><?php echo $state['name']; ?></option>
					<?php endforeach; ?>	
				</select>
				<select class="custom-select brokers" name="brokers[]" multiple="multiple">
					<option value="All">All</option>
					<?php foreach($get_brokers as $broker): ?>
						<option value="<?php echo $broker['id']; ?>"><?php echo $broker['first_name'].' '.$broker['last_name']; ?></option>
					<?php endforeach; ?>	
				</select>
				<button>Search</button>
				<a href="javascript:;" id="clear_filters">Clear Filters</a>
			</form>
			<div class="table-responsive">
				<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>CLIENT NAME</th>
							<th>CLIENT NO</th>
							<th>TELEPHONE</th>
							<th>OPEN DATE</th>
							<th>BIRTH DATE</th>
							<th>REP#</th>
							<th>ADDRESS</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$last_record=0;
					foreach($client_list as $client):
						if($client['broker_name']!=$last_record):
					 ?>
						<tr>
							<td>Broker: <?php echo $client['bfname'].' '.$client['lfname']; ?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
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
					<?php 
					
				// endif;
				$last_record=$client['broker_name'];
			endforeach; ?>
					</tbody>
				</table>
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
<script type="text/javascript">
	$(function() {
		$(".states").select2({
			placeholder:'States',
			// width:'100%'
		});
		$(".brokers").select2({
			placeholder:'Brokers',
			// width:'100%'
		});
		$('.states').on("select2:select", function (e) { 
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
      	});
      	$("#clear_filters").on('click', function(event) {
      		event.preventDefault();
      		$('.states').val(null).trigger('change');
      		$('.brokers').val(null).trigger('change');
      	});
	});
</script>