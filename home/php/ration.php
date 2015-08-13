<div style=" width:100%; height:420px; margin-bottom:10px; overflow-x:hidden; overflow-y: scroll">
	<?php
		session_start();
		include "config.php";
		 if (isset($_SESSION['user_id']))
		  {
		    $id     = $_SESSION['user_id'];
		    $loggedin = TRUE;
		  }
		  else $loggedin = FALSE;

		$res = $conn->query("select distinct ration_id from feed_type where user_id=$id order by ration_id");
		$temp_rationID=null;
		if(mysqli_num_rows($res)>0){
		while($row = $res->fetch_assoc()){
			$temp_rationID = $row['ration_id'];
		?>

	<div style="width: 820px; display:block;height:100px; border:1px solid black; -webkit-border-radius: 5px; 
	-moz-border-radius: 5px; margin-bottom: 10px; margin-left: 30px;float:left">
		<div style="float:left">
			<button type="button" class="btn btn-primary" style="float:left;height:98px;font-size:20px">Ration <?php echo $row['ration_id']	;?></button>
		</div>
		<div style="width: 90px; float:left; margin-left: 20px; margin-top:45px">
			<div>% Weight</div>
			<div>% Dry Matter</div>
		</div>
		<div style="float:left">
			<?php
				$res2 = $conn->query("select * from feed_type where ration_id=$temp_rationID AND user_id=$id");
				echo "<div style=\"width:480px;float:left\">";
				while($row2=$res2->fetch_assoc()){
					echo "<div style=\"margin-top:7px;float:left; width: 60px; text-align:center;\"><div style=\"text-decoration: underline; font-weight: bold;height:40px\" >" . 
					$row2['type'] . "</div><div> ". $row2['percent_weight']. " %</div>	
					<div> ". $row2['percent_dry'] . " %</div></div>";			
				}
				echo "</div>";
			?>
		</div>		

	</div>
	<button type="button"  data-toggle="modal" data-target="#rationEditModal<?php $temp_rationID=$row['ration_id']; echo $row['ration_id']; ?>" onclick="refresh_totalWeight(getElementsByName('rationTypesEdit<?php echo $temp_rationID?>[]'), getElementsByName('rationWeightEdit<?php echo $temp_rationID?>[]'))"  class="btn btn-primary" style="float:left;;font-size:17px;height:99px;width:60px;margin-left:5px;">Edit</button>

	<div class="modal fade" id="rationEditModal<?php echo $temp_rationID ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog editRationDialog">
					<div class="modal-content" >
					<div class="modal-header">
						<span style="font-size:24px; margin-right:20px">Ration </span>
						<input type="text"  id="rationChoiceEdit<?php echo $temp_rationID?>" value="<?php echo $temp_rationID;?>" class="form-contorl" style="text-align:center;font-size:20px;width:60px;height:40px;color:black">
					</div>
						<div class="modal-body" style ="height:400px">
						<form id="clearform2" oninput="refresh_totalWeight(getElementsByName('rationTypesEdit<?php echo $temp_rationID?>[]'), getElementsByName('rationWeightEdit<?php echo $temp_rationID?>[]'))">
							<span style="margin-right:90px;margin-left:35px">Feed Type</span><span style="margin-right:20px">Percent Weight</span>
							<?php 
							$res2 = $conn->query("select * from feedtype where user_id=$id");
							$res3 = $conn->query("select * from feed_type where user_id=$id and ration_id=".$row['ration_id']);
							$typeEdit = array();
							while($row = $res2-> fetch_assoc()){
							  	$typeEdit[] = $row['type_name'];
							}
							for( $i=0; $i<7; $i++){?>
								<div class="form-group" style="width:500px;float:left;margin-left:20px">
									<select name="rationTypesEdit<?php echo $temp_rationID?>[]" class="rationTypes123 form-contorl" style="width:150px;height:32px;float:left;">
										 	<?php 
										 	if($row = $res3-> fetch_assoc()){
										 		$temp_type123 = $row['type'];
										 		$temp_weight = $row['percent_weight'];
												$temp_typeID = $row['type_id'];
										 		echo "<option value=$temp_type123>$temp_type123</option>";
										 	}else{
										 		$temp_weight = "";
										 	}
										 	echo "<option value=\"\"></option>";
										 	?>
										  <?php for($j=0; $j<count($typeEdit); $j++){
										  	if($typeEdit[$j] != $temp_type123)
										  		echo "<option value=\"$typeEdit[$j]\"> $typeEdit[$j]</option>";
										  }
										  ?>
									</select>
										<div class="input-group">
											<span class="input-group-addon">%</span>
											<input type="number" name="rationWeightEdit<?php echo $temp_rationID?>[]" class="form-control"  value="<?php echo $temp_weight;?>"
												required style="width:100px; float:left;text-align:center">
											<input type="hidden" name="rationIDEdit<?php echo $temp_rationID?>[]" class="form-control"  value ="<?php echo $temp_typeID;?>" 
											required style="width:100px; float:left;">
										</div>									
								</div>
							<?php
							}
							?>
						</form>
						<span class="rationInfo" style="font-size:18px"></span>
					</div>
					<div style="margin-bottom:10px;margin-top:20px">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" 
						onclick="update_ration(getElementsByName('rationTypesEdit<?php echo $temp_rationID?>[]'), 
						getElementsByName('rationWeightEdit<?php echo $temp_rationID?>[]'),getElementsByName('rationIDEdit<?php echo $temp_rationID?>[]'), 
						 <?php echo $temp_rationID?> )" style="width:150px">Submit</button>
					</div>
				</div>
		</div>
	</div>
	<?php
	}
	}else{
		echo '<div class="btn_alert" style="float:left; margin-left:50px; width:90%; height:40%; font-size: 50px" >Step #1 - Add Feed Stuffs </div>';
		echo '<div class="btn_alert" style="float:left; margin-left:50px; width:90%; height:40%; font-size: 50px" >Step #2 - Add Rations </div>';
	}
	?>
	<div class="modal fade" id="addRationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog addCostDialog addRationDialog">
					<div class="modal-content" >
					<div class="modal-header">
						<span style="font-size:24px; margin-right:20px">Ration </span>
						<input type="text"  name="rationChoice" id="rationChoice" value="<?php if($temp_rationID != null) echo $temp_rationID+1; else echo 1;?>" class="form-contorl" style="text-align:center;font-size:20px;width:60px;height:40px;color:black">
					</div>
						<div class="modal-body" style ="height:370px">
						<form id="clearform2" oninput="refresh_totalWeight(getElementsByName('rationTypes[]'), getElementsByName('rationWeight[]'))">
							<span style="margin-right:90px;margin-left:35px">Feed Type</span><span style="margin-right:20px">Percent Weight</span>
							<!-- <span style="margin-right:20px">% Dry Matter</span> -->
							<?php 
							$res2 = $conn->query("select * from feedtype where user_id=".$id);
							while($row = $res2-> fetch_assoc()){
							  	$type[] = $row['type_name'];
							}
							for( $i=0; $i<7; $i++){?>
								<div class="form-group" style="width:500px;float:left;margin-left:20px">
									<select name="rationTypes[]" class="rationTypes123 form-contorl" style="width:150px;height:32px;float:left;" >
										 	<option value=""></option>;
										  <?php for($j=0; $j<count($type); $j++){
										  	echo "<option value=\"$type[$j]\"> $type[$j]</option>";
										  }
										  ?>
									</select>
									<div class="input-group">
										<span class="input-group-addon">%</span>
										<input type="text" name="rationWeight[]" class="form-control" id="cost_date"   style="width:100px; float:left;text-align:center">
									</div>
								</div>
							<?php
							}
							?>
						</form>
						<span class="rationInfo" style="font-size:18px"></span>

					</div>
					<div style="margin-bottom:10px;margin-top:20px">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="add_ration(getElementsByName('rationTypes[]'), getElementsByName('rationWeight[]'))" style="width:150px">Submit</button>
					</div>
				</div>
			</div>
	</div>
</div>

<div class="modal fade" id="deleteRationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog addCostDialog delete_feed">
					<div class="modal-content">
					<div class="modal-body">
						<div style=" height:180px; width:170px; overflow-x:hidden; overflow-y: auto;margin-left:50px">
							<?php
							$res3 = $conn->query("select distinct ration_id from feed_type where user_id=".$id);
							while($row = $res3->fetch_assoc()){ ?>
							<form>
							<input class="checkBoxRation" name="checkboxRation[]" type="checkbox" value="<?php echo $row['ration_id'];?>" style="float:left">
							<span style="font-size: 20px">
								Ration <?php echo $row['ration_id'];?>
							</span>
							</form>
							<?php
							}
							?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" name ="delete" class="btn btn-primary" style="width:100px" onclick="delete_ration_help(document.getElementsByClassName('checkBoxRation'))">Delete</button>
					</div>
				</div>
			</div>
	</div>

<script type="text/javascript">
	function refresh_totalWeight(str1, str2){
		var total_percent_weight = 0;
		// alert(ration_id);
		for(i=0; i<str1.length; i++){
			if(str1[i].value != ''){
				var weight = str2[i].value; 
				if(str2[i].value =='')
					weight = 0;
				total_percent_weight += parseInt(weight);
			}
		}

		$(".rationInfo").html("Current Total Weight: " + total_percent_weight);
	}
	function update_ration(str1, str2,str4,id){
		var ration_id = id;
		var total_percent_weight = 0;
		// alert(ration_id);
		for(i=0; i<str1.length; i++){
			if(str1[i].value != ''){
				total_percent_weight += parseInt(str2[i].value);
			}
		}

		if(total_percent_weight == 100 || total_percent_weight == 0){

			$.ajax({
				type: "GET",
				url: "php/delete_ration.php?id="+id
			}).done(function(data){

			for(i=0; i<str1.length; i++){
				if(str1[i].value != ''){
					datas = "&type="+str1[i].value+"&percent_weight="+str2[i].value+"&type_id="+str4[i].value;

					$.ajax({
						type: "POST",
						url: "php/new_ration.php?id="+ration_id,
						data: datas
					}).done(function(data){
						view_ration();
						console.log(data);
					});
				}

			}

			});

		}
		else{
			$(".rationInfo").html("Invalid total percent weight. Current Total: " + total_percent_weight);
		}
	}

	function add_ration(str1, str2){
		var ration_id = $('#rationChoice').val();
		var total_percent_weight = 0;
		// alert(ration_id);
		for(i=0; i<str1.length; i++){
			if(str1[i].value != ''){
				total_percent_weight += parseInt(str2[i].value);
			}
		}

		if(total_percent_weight == 100){
			for(i=0; i<str1.length; i++){
				if(str1[i].value != ''){
					datas = "&type="+str1[i].value+"&percent_weight="+str2[i].value;

					$.ajax({
						type: "POST",
						url: "php/new_ration.php?id="+ration_id,
						data: datas
					}).done(function(data){
						console.log(data);
						view_ration();
					});
				}
			}
		}
		else{
			$(".rationInfo").html("Invalid total percent weight. Current Total: " + total_percent_weight);
		}
	 }

	function delete_ration_help(check){
		var values = [];
	    for (i = 0; i < check.length; i++) {
	        if (check[i].checked == true) {
	            values.push(check[i].value);
	        }
	    }
	    
	    while(values.length > 0){
	    	delete_ration(values.pop());
    }


	function delete_ration(str){
		var id = str;

		$.ajax({
			type: "GET",
			url: "php/delete_ration.php?id="+id
		}).done(function(data){
			view_ration();
		});
	}
}
</script>