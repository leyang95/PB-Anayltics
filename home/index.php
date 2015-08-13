<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />		
		 <title>Performance Beef Analytics</title>

		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	</head>
	<?php
	 require_once '../connectivity.php';

	  $userstr = 'Guest';

	  if (isset($_SESSION['user_id']))
	  {
	    $id     = $_SESSION['user_id'];
	    $loggedin = TRUE;
	    $userstr  = $_SESSION['lastName'];
	  }
	  else $loggedin = FALSE;	

	if($loggedin){
	?>
	<body onload="viewdata()">
		<div class="container">
		<div id="top">
			<div id="top-left">
				<img src="../logo.png" alt="Performance Livestock Analytics" style="width:80%;height:135px;margin-top:0px">			
						</div>

			<div id="top-right">
				
				<div class="dropdown">
				  <button id="dLabel" type="button" data-toggle="dropdown" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="padding: 0;
					border: none;
					background: none;">
				    <h1 ><?php echo $userstr ?>'s Farms       <span class="caret"></span></h1>
				  </button>
				  <ul class="dropdown-menu" role="menu" style="margin-left:290px">
				    <li><a href="#">Account</a></li>
				    <li><a href="#">Settings</a></li>
				    <li><a href="../signup/logout.php">Logout</a></li>
				  </ul>
				</div>
				<form>
 					<input id="searchForm" type="search" placeholder="Search" name="googlesearch">
				</form>
			</div>
		</div>

		<div class="modal fade" id="myAddModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog addLot_dialog">
				<div class="modal-content" style="width:400px">
				<div class="modal-body" >
					<form id="addLotForm" class="addLotForm" autocomplete="off" >
						
						<div class="form-group">			
							<label for="nameOfLots">Name of Lot</label>
							<input type="text" class="form-control" id="nameOfLots" maxlength="24" required>
						</div>
						<div class="form-group">
							<label for="creationDate"  >In Date</label>
							<input type="date" class="form-control" id="creationDate" required>
						</div>
						<div class="form-group">
							<label for="source"  >Source</label>
							<input type="text" class="form-control" id="source" maxlength="24" required >
						</div>
						<div class="input-group-parent">
							<label for="total_cost"  >Total Cost</label>
							<div class="input-group" style="width:200px;float:right">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" id="total_cost"  required>
							</div>
						</div>
						<div class="input-group-parent">
							<label for="purchase_weight"  >Purchase Weight</label>
							<div class="input-group" style="width:200px;float:right">
								<input type="text" class="form-control" id="purchase_weight"  required>
								<span class="input-group-addon">lbs</span>
							</div>
						</div>
						<div class="input-group-parent">
							<label for="farm_weight"  >Farm Weight</label>
							<div class="input-group" style="width:200px;float:right">
								<input type="text" class="form-control" id="farm_weight"  required>
								<span class="input-group-addon">lbs</span>
							</div>
						</div>
						<div class="form-group">
							<label for="numOfHead"  ># of Head</label>
							<input type="text" class="form-control" id="numOfHead"  required>
						</div>
						<div class="form-group"  >
							<label for="breed"  >Breed</label>
							<input type="text" class="form-control" id="breed" maxlength="24" required>
						</div>
						<div class="form-group">
							<label for="gender"  >Gender</label>
							<select name="gender" id="gender" class="form-contorl" >
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
								<input type="text" class="form-control" id="trucking"  required>
							</div>
						</div>
						<div class="form-group">
							<label for="cattleCondition" >Cattle Condition</label>
							<select name="cattleCondition" id="cattleCondition" class="form-contorl" >
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
								<input type="text" class="form-control" id="yardage" data-toggle="tooltip" data-placement="right" title="per head per day"  required >
							</div>
						</div>	
						<div class="input-group-parent">
							<label for="interest" >Interest</label>
							<div class="input-group" style="width:200px;float:right">
								<span class="input-group-addon">%</span>
								<input type="text" class="form-control" id="interest" required >
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" style="width:150px;float:left; margin-top:12px">Cancel</button>
					<input form="addLotForm" class="btn btn-primary" id="save" type="submit" name="addLot" value="Submit" style="margin-top:10px" onclick="addData()">
				</div>
			</div>
		</div>
	</div>


		<div id="viewdata"></div>
</div>

		<div id="bottom-buttons2">
		</div>

		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.price_format.2.0.min.js"></script>
		<script>

			$(function () {
			  $('[data-toggle="tooltip"]').tooltip()
			  // $('.input-group input[type=number]').priceFormat();
			})

			$('.input-group input[type=text]').keypress(function(event) {
			 // alert("hello");

			 $(this).priceFormat({
			 	prefix: '',
			 });

			  // if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
			  //   ((event.which < 48 || event.which > 57) &&
			  //     (event.which != 0 && event.which != 8))) {
			  //   event.preventDefault();
			  // }

			  // var text = $(this).val();

			  // if ((text.indexOf('.') != -1) &&
			  //   (text.substring(text.indexOf('.')).length > 2) &&
			  //   (event.which != 0 && event.which != 8)) {
			  //   event.preventDefault();
			  // }

			});

			function viewdata(){

				$.ajax({
					type:"GET",
					url: "php/getdata.php"
				}).done(function(data){
					$('#viewdata').html(data);

				});
			}

			function addData(){
				var nameOfLots = $('#nameOfLots').val();
				var creationDate = $('#creationDate').val();
				var source = $('#source').val();
				var total_cost = $('#total_cost').val().replace(/[^\d\.\-\ ]/g, '');
				var purchase_weight = $('#purchase_weight').val().replace(/[^\d\.\-\ ]/g, '');
				var farm_weight = $('#farm_weight').val().replace(/[^\d\.\-\ ]/g, '');
				var numOfHead = $('#numOfHead').val();
				var breed = $('#breed').val();
				var gender = $('#gender').val();
				var trucking = $('#trucking').val().replace(/[^\d\.\-\ ]/g, '');
				var cattleCondition = $('#cattleCondition').val();
				var yardage = $('#yardage').val().replace(/[^\d\.\-\ ]/g, '');
				var interest = $('#interest').val().replace(/[^\d\.\-\ ]/g, '');

				var datas ="&nameOfLots="+nameOfLots+"&creationDate="+creationDate+"&source="+source+"&total_cost="+total_cost+
						"&purchase_weight="+purchase_weight+"&farm_weight="+farm_weight+"&numOfHead="+numOfHead+"&breed="+breed+"&gender="+gender+"&trucking="+trucking
						+"&cattleCondition="+cattleCondition+"&yardage="+yardage+"&interest="+interest;
				// alert(datas);
				event.preventDefault();
				if(nameOfLots !="" && creationDate != "" && source != "" && total_cost !="" && purchase_weight !="" && farm_weight !="" && numOfHead !="" && breed !="" && gender !="" && trucking != "" && cattleCondition != "" && yardage !="" && interest !=""){
				$.ajax({
					type: "POST",
					url: "php/newdata.php",
					data: datas
				}).done(function(data){
					viewdata();
					console.log(data);
					$("#addLotForm")[0].reset();
					$('#myAddModal').modal('hide');
				});
				}
			}

			function deletedata(str){
				var id = str;

				$.ajax({
					type: "GET",
					url: "php/deletedata.php?id="+id
				}).done(function(data){
					viewdata();
				});
			}

			
			function logout(){
				$.ajax({
					type: "GET",
					url: "../signup/logout.php"
				}).done(function(data){
					return data;
				});
			     
			}

			 // var time = new Date().getTime();
		  //    $(document.body).bind("mousemove keypress", function(e) {
		  //        time = new Date().getTime();
		  //    });

		  //    function refresh() {
		  //        if(new Date().getTime() - time >= 60000) 
		  //            window.location.reload(true);
		  //        else 
		  //            setTimeout(refresh, 10000);
		  //    }

		  //    setTimeout(refresh, 10000);

		</script>
		<?php 
		}
		else{
			die(header("Location:../index.html"));
		}?>
	</body>
</html>