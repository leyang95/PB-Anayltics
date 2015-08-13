
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

		$res = $conn->query("select distinct load_id,ration_id from feed_load where user_id=".$id);
		$counter = 1;
		while($row = $res->fetch_assoc()){
			$temp_loadID = $row['load_id'];
			$temp_rationID = $row['ration_id'];
		?>

	<div style="width: 820px; display:block;height:120px; border:1px solid black; 	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px; margin-bottom: 10px; margin-left: 30px;float:left">
		<div style="float:left">
			<button type="button" class="btn btn-primary" style="float:left;height:118px;font-size:14px">
				Load <?php 
				$res2 = $conn->query("select * from feed_load where user_id=$id and load_id = $temp_loadID");
				echo $counter++. "<br/>";
				$total_Weight = 0;
				while($row2 = $res2 -> fetch_assoc()){
					$total_Weight += $row2['weight1'];
					echo $row2['lot1'] ." - ". $row2['weight1'] ." ". $row2['am_pm'] ."<br/>"; 
				}
				?>
			</button>
		</div>
		<div style="width: 100px; float:left; margin-left: 20px; margin-top:39px">
			<div>Ingredient</div>
			<div style="margin-top :13px">Scale Weight</div>
		</div>
		<div style="float:left">
			<?php
				$res2 = $conn->query("select * from feed_type where ration_id=$temp_rationID AND user_id=$id");
				echo "<div style=\"width:490px;float:left\";>";
				$acc_weight = 0;
				while($row2=$res2->fetch_assoc()){
					$acc_weight += ($row2['percent_weight']/100 * $total_Weight);
					echo "<div style=\"margin-top:7px;float:left; width: 70px; text-align:center;\"><div style=\"text-decoration: underline; font-weight: bold;font-size: 15px;height:30px\" >" . 
					$row2['type'] . "</div><div style=\"color:#990000; margin-top:5px\"> ". $row2['percent_weight']/100 * $total_Weight. " </div>	
					<div style=\"color:#990000; margin-top:15px\"> ". $acc_weight . " </div></div>";			
				}
				echo "</div>";
			?>
		</div>
	</div>
	<button type="button"  data-toggle="modal" data-target="#feedEditModal<?php echo $row['load_id']; ?>" class="btn btn-primary" style="float:left;;font-size:17px;height:120px;width:60px;margin-left:5px;">Edit</button>

	<div class="modal fade" id="feedEditModal<?php echo $temp_loadID ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog editRationDialog">
					<div class="modal-content" >
					<div class="modal-header">
						<select  oninput="update_select_lots_edit(this,<?php echo $temp_loadID?>)" id="select_ration_load<?php echo $temp_rationID?>"
							 style="text-indent:23px; width:120px; height: 40px;margin-top: 15px ;border: 2px solid purple; -webkit-border-radius: 5px; -moz-border-radius: 5px; " >
						  <?php 
						  	$numRation = $conn->query("select distinct ration_id from feed_type where user_id=$id order by ration_id");
						  	echo "<option value=\"$temp_rationID\" >Ration $temp_rationID</option>";
						  	for($i=1;$i<=mysqli_num_rows($numRation); $i++){
						  		if($i != $temp_rationID)
						  	 		echo "<option value=\"$i\">Ration $i</option>";
						  	}
							?>
						</select>
					</div>
						<div class="modal-body" style ="height:300px; ">
						<form id="clearform2">
						<div style="font-size:20px; margin-right:15;">Lots Available: </div>
							<?php 
								$temp_res = $conn->query("select lot1,random from feed_load where user_id = $id and load_id = $temp_loadID");	

							for( $i=1; $i<=4; $i++){?>
								<div class="form-group">
									<div class="selection_lots">

									<select name="select_edit_option_load<?php echo $temp_loadID?>[]" class="form-contorl" style="width:150px;height:32px;">
										 		<?php
										 		$row3 = $temp_res->fetch_assoc();
										 		$temp_typeID = $row3['random'];
										 		echo '<option value="'.$row3['lot1'].'">'.$row3['lot1'].'</option>';
										 		$res2 = $conn->query("select id, name_lot from test where user_id= $id and ration_selected=". $temp_rationID);

										 		while($row2 = $res2-> fetch_assoc()){
										 			if($row2['name_lot'] != $row3['lot1'])
										 			echo '<option value="'.$row2['name_lot']. '">'.$row2['name_lot'].'</option>';

										 		}
										 		?>
										 		<input type="hidden" name="loadIDEdit<?php echo $temp_loadID?>[]" class="form-control"  value ="<?php echo $temp_typeID;?>" 
												required style="width:100px; float:left;">
									</select>	
									</div>
						
								</div>
							<?php
							}
							?>
							<div style="font-size:20px; margin-right:15;">Time:</div>	
							<select id="morning_afternoon_edit" class="form-contorl" style="width:150px;height:32px;text-indent:60px">
							 	<option value="am">AM</option>;
							 	<option value="pm">PM</option>;
							</select>	
						</form>
					</div>
					<div style="margin-bottom:20px;margin-top:20px">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<?php	
						$res3 = $conn->query("select distinct load_id from feed_load where user_id = $id");


						$num_rows = mysqli_num_rows($res3);		?>
						<button type="button" class="btn btn-primary" onclick="update_load(document.getElementsByName('select_edit_option_load<?php echo $temp_loadID?>[]'),
							<?php echo $temp_loadID?>,<?php echo $temp_rationID?>, document.getElementsByName('loadIDEdit<?php echo $temp_loadID?>[]'))" style="width:150px">Submit</button>
					</div>
				</div>
		</div>
	</div>
	<?php
	}
	?>
</div>

<div style="float:left; margin-left:3%; margin-bottom: 20px; border-right:solid black 1px; padding: 10px;margin-top:-10px" >
	<button type="button" class="btn btn-primary" onclick="viewdata()" style="height:60px;width:170px;font-size:20px">Feedlots</button>
</div>
<div style="float:left; margin-bottom: 20px; margin-left:10px" >
	<button type="button" class="btn btn-primary btn-secondary" data-toggle="modal" data-target="#addLoadModal" style="height:60px;width:170px;margin-right:10px;font-size:20px">Add Load</button>
	<button type="button" class="btn btn-primary btn-secondary" data-toggle="modal" data-target="#deleteLoadModal" style="height:60px;width:170px;font-size:20px" >Delete Load</button>
</div>

	<div class="modal fade" id="addLoadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog addLot_dialog addLoadDialog">
					<div class="modal-content" >
					<div class="modal-header">
						<span style="font-size:20px; margin-right:15;">Choose Ration: </span>
						<select  oninput="update_select_lots(this)" id="select_ration_load"
							 style="text-indent:23px; width:120px; height: 40px;margin-top: 15px ;border: 2px solid purple; -webkit-border-radius: 5px; -moz-border-radius: 5px; " >
						  <?php 
						  	$numRation = $conn->query("select distinct ration_id from feed_type where user_id=$id order by ration_id");
						  	while($numResult = $numRation->fetch_assoc()){
						  		$i = $numResult['ration_id'];
						  	 	echo "<option value=\"$i\" >Ration $i</option>";
						  	}
							?>
						</select>
					</div>
						<div class="modal-body" style ="height:300px; ">
						<form id="clearform2">
						<div style="font-size:20px; margin-right:15;">Lots Available: </div>
							<?php 
							for( $i=1; $i<=4; $i++){?>
								<div class="form-group">
									<div class="selection_lots">

									<select name="select_option_load[]" class="form-contorl" style="width:150px;height:32px;">
										 	<option value=""></option>
										 		<?php
										 		$res2 = $conn->query("select id, name_lot from test where user_id= $id and ration_selected=". 1);
										 		while($row = $res2-> fetch_assoc())
										 			echo '<option value="'.$row['name_lot'].'">'.$row['name_lot'].'</option>';
										 		?>
									</select>	
									</div>
						
								</div>
							<?php
							}
							?>
							<div style="font-size:20px; margin-right:15;">Time:</div>	
							<select id="morning_afternoon" class="form-contorl" style="width:150px;height:32px;text-indent:60px">
							 	<option value="am">AM</option>;
							 	<option value="pm">PM</option>;
							</select>	
						</form>
					</div>
					<div style="margin-bottom:20px;margin-top:20px">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<?php	
						$res = $conn->query("select distinct load_id from feed_load where user_id = $id");
						$num_rows=0;
						while($rows_num=$res->fetch_assoc()){
							$num_rows = $rows_num['load_id'];
						}
							?>
						<button type="button" class="btn btn-primary" onclick="add_load(document.getElementsByName('select_option_load[]'), <?php echo $num_rows+1;?>)" style="width:150px">Submit</button>
					</div>
				</div>
			</div>
	</div>

<div class="modal fade" id="deleteLoadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog delete delete-feed-delivery">
					<div class="modal-content">
					<div class="modal-body">
						<div style=" height:180px; width:170px; overflow-x:hidden; overflow-y: auto;margin-left:50px">
							<?php
							$res3 = $conn->query("select distinct load_id from feed_load where user_id=".$id);
							$counter=1;
							while($row = $res3->fetch_assoc()){ ?>
							<form>
							<input class="checkBoxLoad" name="checkboxLoad[]" type="checkbox" value="<?php echo $row['load_id'];?>" style="float:left">
							<span style="font-size: 20px">
								Load <?php echo $counter++;?>
							</span>
							</form>
							<?php
							}
							?>						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" name ="delete" class="btn btn-primary" style="width:100px" onclick="delete_load_help(document.getElementsByClassName('checkBoxLoad'))">Delete</button>
					</div>
				</div>
			</div>
	</div>

<script type="text/javascript">

	function add_load(str,load_id){
		var ration_id = $('#select_ration_load').val();
		var time = $('#morning_afternoon').val();
		for(i=0; i<str.length; i++){
			if(str[i].value != ''){
				datas = "&lot_id="+str[i].value + "&time=" + time +"&load_id=" +load_id;
				$.ajax({
					type: "POST",	
					url: "php/new_load.php?id="+ration_id,
					data: datas
				}).done(function(data){
					feed_delivery();
					// console.log(data);
				});
			}

		}
		
	 }

	function update_load(str,load_id,ration_id,type_id){
		var time = $('#morning_afternoon_edit').val();
		$.ajax({
			type: "GET",
			url: "php/delete_load.php?id="+load_id
		}).done(function(data){
			// console.log(data);
		});

		for(i=0; i<str.length; i++){

			if(str[i].value != ''){
				datas = "&lot_id="+str[i].value + "&time=" + time +"&load_id=" +load_id + "&type_id=" + type_id[i].value;
				// alert(datas)
				$.ajax({
						type: "POST",	
						url: "php/new_load.php?id="+ration_id,
						data: datas
					}).done(function(data){
						feed_delivery();
						// console.log(data);
					});
			}

		}
	}

	function delete_load_help(check){
		var values = [];
	    for (i = 0; i < check.length; i++) {
	        if (check[i].checked == true) {
	            values.push(check[i].value);
	        }
	    }
	    
	    while(values.length > 0){
	    	delete_load(values.pop());
    }

	}

	function delete_load(str){
		var id = str;

		$.ajax({
			type: "GET",
			url: "php/delete_load.php?id="+id
		}).done(function(data){
			feed_delivery();
		});
	}


	function update_select_lots(str){
		var ration_id = str.value;
	$.ajax({
		type:"GET",
		url: "php/add_load_selection.php?id="+ration_id
		}).done(function(data){
			$('.selection_lots').html(data);
		});
	}

	function update_select_lots_edit(str, load_id){
		var ration_id = str.value;
	$.ajax({
		type:"GET",
		url: "php/add_load_selection_edit.php?id="+ration_id,
		data: "&load_id=" + load_id
		}).done(function(data){
			$('.selection_lots').html(data);

		});
	}
</script>