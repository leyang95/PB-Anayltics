<?php
session_start();

	include "config.php";
	$id = $_GET['id'];

	 if (isset($_SESSION['user_id']))
		  {
		    $user_id     = $_SESSION['user_id'];
		    $loggedin = TRUE;
		  }
		  else $loggedin = FALSE;
	$id = $_GET['id'];
	$res = $conn->query("select * from test where id=$id");
	// $res2 = $conn->query("select * from output_data where id=" . $id);

	$row = mysqli_fetch_row($res);
	// $row2 = mysqli_fetch_row($res2);
?>
	

	<div id="view_detail" style="height:95%;display:block;">
		<script type="text/javascript">
			function view_table(str){
				var id = str;

				$.ajax({
					type:"GET",
					url: "php/table.php?id="+id
				}).done(function(data){
					$('#view_detail').html(data);
					var button1 = '<button type="button" class="btn btn-primary btm not_ration_edit" data-toggle="modal" data-target="#AddCostModal">Add Cost</button>';
					var button2 = '<button type="button" class="btn btn-primary btm ration_edit" data-toggle="modal" onclick="add_row(<?php echo $row[1]; ?>)">Add Row</button>';	
					var button3 = '<button type="button" class="btn btn-primary btm not_ration_edit"  data-toggle="modal" data-target="#addHeaderButton">Add Head</button>';
					var button4 = '<button type="button" class="btn btn-primary btm not_ration_edit" data-toggle="modal" data-target="#HeaderButton">Minus Head</button>';	
					var button5 = '<button type="button" class="btn btn-primary btm " onclick="edit_rationTable(this)">Edit Table</button>';	
					var button6 = '<button type="button" class="btn btn-primary btm ration_edit" data-toggle="modal" onclick="edit_table(<?php echo $row[1]; ?>)">Edit Row</button>';
					var button7 = '<button type="button" class="btn btn-primary btm ration_edit" onclick="delete_table(<?php echo $row[1]; ?>)" onclick="">Delete Row</button>';	
					$('#bottom-buttons').html(button5+button1+button2+button3+button4+button6+button7);
				});
			}

			function view_ration(){
				$.ajax({
					type:"GET",
					url: "php/ration.php"
				}).done(function(data){
					$('#view_detail').html(data);
					var button1 = '<button type="button" class="btn btn-primary btm" data-toggle="modal" data-target="#addRationModal" onclick="refresh_totalWeight(0, 0)" style="width:250px">Add Ration</button>';
					var button2 = '<button type="button" class="btn btn-primary btm" data-toggle="modal" data-target="#deleteRationModal" style="width:250px">Delete Ration</button>';	
					$('#bottom-buttons').html(button1+button2);
				});
			}

			function view_report(str){
				$.ajax({
					type:"GET",
					url: "php/report.php?id="+str
				}).done(function(data){
					$('#view_detail').html(data);
				});
			}

			

			function view_feed_cost(){
				$.ajax({
					type:"GET",
					url: "php/feed_cost.php"
				}).done(function(data){
					$('#view_detail').html(data);
					var button1 = '<button type="button" class="btn btn-primary btm" onclick="add_feed_cost()" style="width:250px">Add Feed Type</button>';
					var button2 = '<button type="button" class="btn btn-primary btm" data-toggle="modal" data-target="#deleteFeedTypeModal" style="width:250px">Delete Feed Type</button>';	
					$('#bottom-buttons').html(button1+button2);
					$('#ration_edit').hide();
				});
			}

			view_table(<?php echo $id ?>);
		</script>
	</div>
	<div >
		<div id ="button_left" style="">
		<button type="button" class="btn btn-primary btm" onclick="viewdata()" style="height:60px;width:170px;font-size:20px;">Feedlots</button>
		</div>
		<div id="bottom-center-buttons">
			<button type="button" class="btn btn-primary btm btn-secondary" onclick="view_table(<?php echo $id ?>)">Sheets</button>
			<button type="button" class="btn btn-primary btm btn-secondary" data-toggle="modal" onclick="view_feed_cost()">Feed</button>
			<button type="button" class="btn btn-primary btm btn-secondary" onclick="view_report(<?php echo $id?>)">Reports</button>
			<button type="button" class="btn btn-primary btm btn-secondary" onclick="view_chart()">Analytics</button>
			<button type="button" class="btn btn-primary btm btn-secondary" onclick="view_ration()" id="ration_button">Rations</button>
			<button type="button" class="btn btn-primary btm btn-secondary" onclick="view_chart()">Print</button>
		</div>
		<div id="bottom-buttons">
		</div>
	</div>


	<div class="modal fade" id="AddCostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog addCostDialog">
			<div class="modal-content">
				<div class="modal-body">
					<form id="clearform2">
						<div class="form-group">
							<select name="costType" id="cost_type" class="form-contorl" style="width:200px;height:40px">
							  <option value="medication">Medication</option>
							  <option value="implants">Implants</option>
							  <option value="delivery">Delivery</option>
							  <option value="vetCost">Vet Cost</option>
							</select>
						</div>								
						<div class="form-group">
							<input type="date" class="form-control" id="cost_date" placeholder="Date" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="cost_amount" placeholder="Cost" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="notes" placeholder="Notes" required>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" id="add_cost" onclick="add_type_cost(<?php echo $id ?>)"style="width:150px">Submit</button>
				</div>
			</div>
		</div>
	</div>
		<div class="modal fade" id="addHeaderButton" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog addLot_dialog addheaderbutton">
					<div class="modal-content" style="width:400px;">
					<div class="modal-body">
						<form id="add_form" class="addLotForm" autocomplete="off" style="height:400px">
							<div class="form-group">
								<label for="creationDate" >In Date</label>
								<input type="date" class="form-control" id="headAdd_date" required>
							</div>
							<div class="form-group">
								<label for="source"  >Source</label>
								<input type="text" class="form-control" id="headAdd_seller" maxlength="24" required >
							</div>
							<div class="input-group-parent">
								<label for="total_cost"  >Total Cost</label>
								<div class="input-group" style="width:200px;float:right">
									<span class="input-group-addon">$</span>
									<input type="text" class="form-control" id="headAdd_cost"  required>
								</div>
							</div>
							<div class="input-group-parent">
								<label for="purchase_weight"  >Purchase Weight</label>
								<div class="input-group" style="width:200px;float:right">
									<input type="text" class="form-control" id="headAdd_purchaseWeight"  required>
									<span class="input-group-addon">lbs</span>
								</div>
							</div>
							<div class="input-group-parent">
								<label for="farm_weight"  >Farm Weight</label>
								<div class="input-group" style="width:200px;float:right">
									<input type="text" class="form-control" id="headAdd_farmWeight"  required>
									<span class="input-group-addon">lbs</span>
								</div>
							</div>
							<div class="form-group">
								<label for="numOfHead"  ># of Head</label>
								<input type="text" class="form-control" id="headAdd_amount"  required>
							</div>
							<div class="form-group"  >
								<label for="breed"  >Breed</label>
								<input type="text" class="form-control" id="headAdd_breed" maxlength="24" required>
							</div>
							<div class="form-group">
								<label for="gender"  >Gender</label>
								<select name="gender" id="headAdd_gender" class="form-contorl" >
								  <option value="steer">Steer</option>
								  <option value="heifer">Heifer</option>
								  <option value="bulls">Bulls</option>
								  <option value="cows">Cows</option>
								</select>
							</div>
							<div class="input-group-parent">
								<label for="trucking"  >Trucking Cost</label>
								<div class="input-group" style="width:200px;float:right">
									<span class="input-group-addon">$</span>
									<input type="text" class="form-control" id="headAdd_truck"  required>
								</div>
							</div>
							<div class="form-group">
								<label for="cattleCondition" >Cattle Condition</label>
								<select name="cattleCondition" id="headAdd_cattle" class="form-contorl" >
								  <option value="green">Green</option>
								  <option value="lite flesh">Lite Flesh</option>
								  <option value="medium">Medium</option>
								  <option value="heavy">Heavy</option>
								</select>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" style="width:150px;float:left;">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="add_head_cost(<?php echo $id ?>)" style="width:150px">Submit</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="HeaderButton" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog addCostDialog addheaderbutton headerbutton">
					<div class="modal-content">
					<div class="modal-body" style="height:250px">
						<div class="form-group">
							 <select id="delete_choices" style="width:150px;float:left;margin:5px;height:30px">
					            <option value="sold">Sold</option>
					            <option value="death">Death</option>
					            <option value="move">Move</option>
						     </select>
						     <input type="date" class="form-control" id="headDelete_date" placeholder="Date" required style="width:160px;float:left;margin:5px">
						</div>

						<form class="sold_form box">
							<div class="form-group">
								<input type="number" class="form-control" id="headDelete_amount" placeholder="Amount" required style="width:150px;float:left;margin:5px">
								<input type="text-area" class="form-control" id="headDelete_price" placeholder="Price" required style="width:160px;float:left;margin:5px">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="headDelete_notes" placeholder="Notes" style="width:320px;height:70px;float:left;margin:5px" required>
							</div>
						</form>
						<form  class="death_form box">
							<div class="form-group">
								<input type="number" class="form-control" id="headDelete_amount" placeholder="Amount" required style="width:320px;float:left;margin:5px">
							</div>
							<div class="form-group">
								<input type="text-area" class="form-control" id="headDelete_notes" placeholder="Notes" style="width:320px;height:70px;float:left;margin:5px" required>
							</div>
						</form>
						<form class="move_form box">
							<div class="form-group">
								<input type="number" class="form-control" id="headDelete_amount" placeholder="Amount" required style="width:150px;float:left;margin:5px">
								<input type="text" class="form-control" id="headDelete_price" placeholder="Price" required style="width:160px;float:left;margin:5px">
							</div>
							<div class="form-group"><span style="width:100px;float:left;font-size:16px;text-align:right;margin-top:10px">Move to:</span> 
								<?php $res_moving = $conn->query("select name_lot, id from test where user_id=$user_id");
									echo  '<select id="headDelete_lots" style="width:160px;float:left;margin:5px;height:30px">';
									while($row_moving = $res_moving->fetch_assoc()){
										$name_lot = $row_moving['name_lot'];
										$lot_id = $row_moving['id'];
									    echo  "<option value=\"$lot_id\">$name_lot</option>";
									}
								?>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="headDelete_notes" placeholder="Notes"   style="width:320px;height:70px;float:left;margin:5px" required>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="delete_head_cost(<?php echo $id ?>)" style="width:150px">Submit</button>
					</div>
				</div>
			</div>
		</div>

	<div class="modal fade" id="myEditModal<?php echo $row[0]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog edit">
				<div class="modal-content">

				<div class="modal-body">
					<form id="clearform" class="addLotForm">
						<div class="form-group">
							<label for="nameOfLots">Name</label>
							<input type="text" class="form-control" id="nameOfLots<?php echo $row[1];?>" value="<?php echo $row[2];?>" required>
						</div>
						<div class="form-group">
							<label for="creationDate">Date</label>
							<input type="date" class="form-control" id="creationDate<?php echo $row[1];?>" value="<?php echo $row[6];?>" required>
						</div>
						<div class="form-group">
							<label for="source">Source</label>
							<input type="text" class="form-control" id="source<?php echo $row[1];?>" value="<?php echo $row[3];?>" required>
						</div>
						<div class="form-group">
							<label for="total_cost">Total Cost</label>
							<input type="text" class="form-control" id="total_cost<?php echo $row[1];?>" value="<?php echo $row[5];?>" required>
						</div>
						<div class="input-group-parent">
							<label for="purchase_weight"  >Purchase Weight</label>
							<div class="input-group" style="width:200px;float:right">
								<input type="text" class="form-control" id="purchase_weight<?php echo $row[1];?>" value="<?php echo $row[8];?>" required>
								<span class="input-group-addon">lbs</span>
							</div>
						</div>
						<div class="input-group-parent">
							<label for="farm_weight"  >Farm Weight</label>
							<div class="input-group" style="width:200px;float:right">
								<input type="text" class="form-control" id="farm_weight<?php echo $row[1];?>" value="<?php echo $row[9];?>" required>
								<span class="input-group-addon">lbs</span>
							</div>
						</div>
						<div class="form-group">
							<label for="numOfHead"># of Head</label>
							<input type="text" class="form-control" id="numOfHead<?php echo $row[1];?>" value="<?php echo $row[7];?>" required>
						</div>
						<div class="form-group">
							<label for="breed">Breed</label>
							<input type="text" class="form-control" id="breed<?php echo $row[1];?>" value="<?php echo $row[11];?>" required>
						</div>
						<div class="form-group">
							<label for="gender"  >Gender</label>
							<select name="gender" id="gender<?php echo $row[1];?>" class="form-contorl" >
							  <option value="<?php echo $row[12];?>"><?php echo $row[12];?></option>
							  <option value="steer">Steer</option>
							  <option value="heifer">Heifer</option>
							  <option value="bulls">Bulls</option>
							  <option value="cows">Cows</option>
							</select>
						</div>
						<div class="input-group-parent">
							<label for="trucking"  >Trucking Cost</label>
							<div class="input-group" style="width:200px;float:right">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" id="trucking<?php echo $row[1];?>" value="<?php echo $row[10];?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="cattleCondition" >Cattle Condition</label>
							<select name="cattleCondition" id="cattleCondition<?php echo $row[1];?>" class="form-contorl" >
							  <option value="<?php echo $row[13];?>" selected><?php echo $row[13];?></option>
							  <option value="green">Green</option>
							  <option value="lite flesh">Lite Flesh</option>
							  <option value="medium">Medium</option>
							  <option value="heavy">Heavy</option>
							</select>
						</div>
						<div class="input-group-parent">
							<label for="yardage" >Yardage</label>
							<div class="input-group" style="width:200px;float:right">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" id="yardage<?php echo $row[1];?>" value="<?php echo $row[14];?>" data-toggle="tooltip" data-placement="right" title="per head per day"  required >
							</div>
						</div>	
						<div class="input-group-parent">
							<label for="interest" >Interest</label>
							<div class="input-group" style="width:200px;float:right">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control"  id="interest<?php echo $row[1];?>" value="<?php echo $row[15];?>" required >
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" style="width:150px;float:left; margin-top:12px">Cancel</button>
					<button type="button" onclick="updatedata(<?php echo $row[1]; ?>)" class="btn btn-primary" style="margin-top:10px" onclick="addData()">Submit</button>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">

	function add_type_cost(str){
		var type_of_cost = $('#cost_type').val();
		var cost_date = $('#cost_date').val();
		var cost_amount = $('#cost_amount').val();
		var notes = $('#notes').val();

		var datas ="&type_of_cost="+type_of_cost+"&cost_date="+cost_date+"&cost_amount="+cost_amount+"&notes="+notes;

		$.ajax({
			type: "POST",
			url: "php/new_cost_data.php?id="+str,
			data: datas
		}).done(function(data){
			$("#clearform2")[0].reset();
			view_OtherCosts_table(str);
			$('#AddCostModal').modal('hide');
		});
	}

	$("#delete_choices").change(function(){
        $(this).find("option:selected").each(function(){
            if($(this).attr("value")=="death"){
                $(".box").not(".death_form").hide();
                $(".box").not(".death_form")[0].reset();
                $(".death_form").show();
            }
            else if($(this).attr("value")=="sold"){
                $(".box").not(".sold_form").hide();
                $(".box").not(".sold_form")[0].reset();
                $(".sold_form").show();
            }
           	else{
            	$(".box").not(".move_form").hide();
            	$(".box").not(".move_form")[0].reset();
                $(".move_form").show();
            }
        });
    }).change();

	function add_head_cost(str){
		var amount = $('#headAdd_amount').val();
		var date = $('#headAdd_date').val();
		var source = $('#headAdd_seller').val();
		var total_cost = $('#headAdd_cost').val();
		var purchase_weight = $('#headAdd_purchaseWeight').val();
		var farm_weight = $('#headAdd_farmWeight').val();
		var num_head = $('#headAdd_amount').val();
		var breed = $('#headAdd_breed').val();
		var gender = $('#headAdd_gender').val();
		var trucking_cost = $('#headAdd_truck').val();
		var cattle_cond = $('#headAdd_cattle').val();

		var currentTime = new Date();
		var month = currentTime.getMonth();
		var day = currentTime.getDate();
		var year = currentTime.getFullYear();
		var currentTime = new Date(year, month, day);
		var parts = date.split("-");
		var inputTime = new Date(parts[0], parts[1]-1, parts[2]);

		if(currentTime < inputTime){
			alert("Invalid date! Future dates are not useable");
		}
		else if(date != "" && source != "" ){
			var datas = "&date="+ date+"&source="+source+"&total_cost="+total_cost+"&purchase_weight="+
			purchase_weight+"&farm_weight="+farm_weight+"&num_head="+num_head+"&breed="+breed+"&gender="+gender+"&trucking_cost="+
			trucking_cost+"&cattle_cond="+cattle_cond;
			// alert(datas);
			$.ajax({
					type: "POST",
					url: "php/new_addHead_detail.php?lot_id="+str,
					data: datas
				}).done(function(data){
					// console.log(data);
					view_AddHead_table(str);
					document.getElementById('ratio').value = "AddHead";
					$("#add_form")[0].reset();
					$('#addHeaderButton').modal('hide');
				});
		}
	}

	function delete_head_cost(str){
		var type = $('#delete_choices').val();
		var date = $('#headDelete_date').val();
		var num_head = $('.'+type+'_form #headDelete_amount').val();
		var price = $('.'+type+'_form #headDelete_price').val();
		var move_to = $('.'+type+'_form #headDelete_lots').val();
		var notes = $('.'+type+'_form #headDelete_notes').val();

		var currentTime = new Date();
		var month = currentTime.getMonth();
		var day = currentTime.getDate();
		var year = currentTime.getFullYear();
		var currentTime = new Date(year, month, day);
		var parts = date.split("-");
		var inputTime = new Date(parts[0], parts[1]-1, parts[2]);

		if(currentTime < inputTime){
			alert("Invalid date! Future dates are not useable");
		}

		if(type != "move")
			move_to = 0;
		if(date != ""){
			var datas = "&date="+ date+"&type="+type+"&num_head="+num_head+"&price="+price+"&move_to="+move_to+"&notes="+notes;
			// alert(datas);
			$.ajax({
					type: "POST",
					url: "php/new_minusHead_detail.php?lot_id="+str,
					data: datas
				}).done(function(data){
					// console.log(data);
					view_MinusHead_table(str);
					document.getElementById('ratio').value = "MinusHead";
					$("."+type+"_form")[0].reset();
					$('#HeaderButton').modal('hide');
				});
		}
	}

	function update_head_table(str, buy_sell,amount){
		var id = str;
		$.ajax({
			type:"POST",
			url: "php/update_head_table.php?id="+id,
			data: "&buy_sell="+buy_sell+"&amount="+amount
		}).done(function(data){
		});	
	}

    function updatedata(str){
    	var id = str;
		var nameOfLots = $('#nameOfLots' + str).val();
		var creationDate = $('#creationDate' + str).val();
		var source = $('#source' + str).val();
		var total_cost = $('#total_cost' + str).val();
		var purchase_weight = $('#purchase_weight' + str).val();
		var farm_weight = $('#farm_weight' + str).val();
		var numOfHead = $('#numOfHead' + str).val();
		var breed = $('#breed' + str).val();
		var gender = $('#gender' + str).val();
		var trucking = $('#trucking' + str).val();
		var cattleCondition = $('#cattleCondition' + str).val();
		var yardage = $('#yardage' + str).val();
		var interest = $('#interest' + str).val();


		var datas ="&nameOfLots="+nameOfLots+"&creationDate="+creationDate+"&source="+source+"&total_cost="+total_cost+
				"&purchase_weight="+purchase_weight+"&farm_weight="+farm_weight+"&numOfHead="+numOfHead+"&breed="+breed+"&gender="+gender+"&trucking="+trucking
				+"&cattleCondition="+cattleCondition+"&yardage="+yardage+"&interest="+interest;

		$.ajax({
			type: "POST",
			url: "php/updatedata.php?id="+id,
			data: datas
		}).done(function(data){
			// $('#info').html(data);
			detail(id);
			// console.log(data);
		});
	}

	function add_row(str){
		if($('#ratio').val() == "Ration")
			add_row_ration(str);
		else if($('#ratio').val() == "feedStuffs")
			add_row_feedStuff(str);
		else if($('#ratio').val() == "FeedCosts")
			add_row_feedCost(str);
		else if($('#ratio').val() == "AddHead")
			add_row_addHead(str);
		else if($('#ratio').val() == "MinusHead")
			add_row_minusHead(str);
		else if($('#ratio').val() == "DryMatter")
			add_row_dryMatter(str);
		else
			add_row_otherCost(str);
	}

	function edit_table(str){
		if($('#ratio').val() == "Ration")
			edit_row_ration(str);
		else if($('#ratio').val() == "feedStuffs")
			edit_row_feedStuff(str);
		else if($('#ratio').val() == "FeedCosts")
			edit_row_feedCost(str);
		else if($('#ratio').val() == "AddHead")
			edit_row_addHead(str);
		else if($('#ratio').val() == "MinusHead")
			edit_row_minusHead(str);
		else if($('#ratio').val() == "DryMatter")
			edit_row_dryMatter(str);
		else
			edit_row_otherCost(str);
	}

	function delete_table(str){
		if($('#ratio').val() == "Ration")
			delete_row_ration(str);
		else if($('#ratio').val() == "feedStuffs")
			delete_row_feedStuff(str);
		else if($('#ratio').val() == "FeedCosts")
			delete_row_feedCost(str);
		else if($('#ratio').val() == "AddHead")
			delete_row_addHead(str);
		else if($('#ratio').val() == "MinusHead")
			delete_row_minusHead(str);
		else if($('#ratio').val() == "DryMatter")
			delete_row_dryMatter(str);
		else
			delete_row_otherCost(str);
	}
</script>