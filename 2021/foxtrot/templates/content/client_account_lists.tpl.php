<div class="container">
	<div class="panel">
		<div class="panel-body">
			<form method="GET" action="client_account_lists.php">
				<select class="custom-select sponsors" name="sponsors[]" multiple="multiple">
					<option value="All">All</option>
					<?php foreach($get_sponsors as $get_sponsor): ?>
						<option value="<?php echo $get_sponsor['id']; ?>"><?php echo $get_sponsor['name']; ?></option>
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
							<th>ACCOUNT #</th>
							<th>COMPANY</th>
							<th>CLEARING ACCOUNT</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$last_record=0;
					foreach($client_list as $client):
						if($client['client_id']!=$last_record):
					 ?>
						<tr>
							<td> <?php echo $client['first_name'].' '.$client['last_name']; ?></td>
							<td><?php echo $client['account_no']; ?></td>
							<td><?php echo $client['sponsor_name']; ?></td>
							<td><?php echo $client['clearing_account']; ?></td>
						</tr>
					<?php else: ?>
						<tr>
							<td></td>
							<td><?php echo $client['account_no']; ?></td>
							<td><?php echo $client['sponsor_name']; ?></td>
							<td></td>
							<td></td>
						</tr>	
					<?php 
					
				endif;
				$last_record=$client['client_id'];
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
				if (isset($_GET['sponsors'])) {
					$data=[
						'sponsors'=>$_GET['sponsors'],
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
		$(".sponsors").select2({
			placeholder:'Sponsors',
			// width:'100%'
		});
		$(".brokers").select2({
			placeholder:'Brokers',
			// width:'100%'
		});
		$('.sponsors').on("select2:select", function (e) { 
           var data = e.params.data.text;
           if(data=='All'){
            $(".sponsors > option").prop("selected","selected");
            $(".sponsors").trigger("change");
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
      		$('.sponsors').val(null).trigger('change');
      		$('.brokers').val(null).trigger('change');
      	});
	});
</script>