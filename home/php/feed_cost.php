<div style="margin-left: 90px; font-size: 30px">
	<span style="float:left">Feed Type </span><span style="float:left; margin-left : 180px"> Cost</span>
	<span style="float:left; margin-left : 180px">Dry Matter</span>
</div>


<div style="height:378px; width:900px; overflow-x:hidden; overflow-y: scroll; margin-bottom:10px">
	<form class="form-inline">
		<?php
			session_start();
			include "config.php";
			 if (isset($_SESSION['user_id']))
			  {
			    $id     = $_SESSION['user_id'];
			    $loggedin = TRUE;
			  }
			  else $loggedin = FALSE;

			$res = $conn->query("select * from feedtype where user_id=".$id);
			
		while($row = $res->fetch_assoc()){
		?>		
	  	<div class="form-group">
	  		<button type="button" class="btn btn-primary btn-type" style="width: 300px;margin-right:30px"><?php echo $row['type_name']?></button>	
	    	<label class="sr-only" for="<?php echo $row['type_name']?>"><?php echo $row['type_name']?></label>
	    	<div class="input-group feed-type" style="margin-right:30px">
	      		<input type="number" oninput="update_feed_cost('<?php echo $row['type_name']?>',this)" 
	      		class="form-control feed-type" value="<?php echo $row['cost_per_ton']?>" style="text-align:center;" >
	     		<div class="input-group-addon ">per ton</div>
	     	</div>	
	     	<div class="input-group feed-type">	
	     		<input type="number" oninput="update_feed_dry('<?php echo $row['type_name']?>',this)" 
	      		class="form-control feed-type" value="<?php echo $row['percent_dry']?>" style="text-align:center;" >
	     		<div class="input-group-addon ">%</div>
	    	</div>

	  	<?php
	  	}
	  	mysqli_data_seek($res,0);
	  	?>

	  	<div class="add_new_cost" style="float:left">
	  		<input type="text" class="btn btn-primary btn-type" style="width: 300px;margin-right:30px" id="new_feedtype_name" maxlength="24">
	    	<label class="sr-only" for="new input">new input</label>
	    	<div class="input-group feed-type" style="margin-right:30px">
	      		<input type="number" class="form-control feed-type" id="new_feedtype_cost" style="text-align:center" >
	     		<div class="input-group-addon ">per ton</div>
	     	</div>	
	     	<div class="input-group feed-type">	
	     		<input type="number" class="form-control feed-type" id="new_feedtype_dry" style="text-align:center" >
	     		<div class="input-group-addon ">%</div>
	    	</div>
	  	</div>
	  	</div>
	</form>
	
	<div class="modal fade" id="deleteFeedTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog addCostDialog delete_feed">
					<div class="modal-content">
					<div class="modal-body">
						<div style=" height:180px; width:170px; overflow-x:hidden; overflow-y: auto;margin-left:50px">
							<?php while($row = $res->fetch_assoc()){ ?>
							<form id="myForm" method="post" action="">
							<input class="checkBoxFeed" name="checkbox[]" type="checkbox" value="<?php echo $row['type_name'];?>" style="float:left">
							<span style="font-size: 20px">
								<?php echo $row['type_name'];?>
							</span>
							</form>
							<?php
							}
							?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" name ="delete" class="btn btn-primary" style="width:100px" onclick="delete_feed_cost(document.getElementsByClassName('checkBoxFeed'))">Delete</button>
					</div>
				</div>
			</div>
	</div>
	
</div>
<script type="text/javascript">
	function update_feed_cost(str,cost){
		$.ajax({
			type: "POST",
			url: "php/update_feed_cost.php?type_name="+str,
			data: "&cost_per_ton="+cost.value
		}).done(function(data){
		});
	}

	function update_feed_dry(str, dry){
		$.ajax({
			type: "POST",
			url: "php/update_feed_dry.php?type_name="+str,
			data: "&percent_dry="+dry.value
		}).done(function(data){
		});
	}

	function add_feed_cost(){
		// alert("hi");
		var type_name = $('#new_feedtype_name').val();
		if(type_name == ''){
			return;
		}
		var cost_per_ton = $('#new_feedtype_cost').val();
		var percent_dry = $('#new_feedtype_dry').val();
		var datas = "&type_name="+type_name+"&cost_per_ton="+cost_per_ton+"&percent_dry="+percent_dry;
		// alert(datas)
		$.ajax({
			type: "POST",
			url: "php/new_feed_cost.php",
			data: datas
		}).done(function(data){
			// console.log(data);
			view_feed_cost();
		});
	}

	function delete_feed_cost(check){
		var values = [];
	    for (i = 0; i < check.length; i++) {
	        if (check[i].checked == true) {
	            values.push(check[i].value);
	        }
	    }
	    
	    while(values.length > 0){
	    	delete_feed_data(values.pop());
    }


	function delete_feed_data(str){
		var id = str;

		$.ajax({
			type: "GET",
			url: "php/delete_feed_data.php?id="+id
		}).done(function(data){
			view_feed_cost();
		});
	}
}
</script>