<div style="width:100%; height:400px; ; overflow-y:scroll; overflow-x:hidden; margin-bottom:10px;">
	<div class="table table-hover" class="fixed" style="width:1050px" >
<?php
	session_start();
	include "config.php";
	 if (isset($_SESSION['user_id']))
	  {
	    $id     = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	$res = $conn->query("select test.id, test.name_lot, test.seller, test.location, test.date , weight, weight_am, weight_pm, ration_selected, sell_date from test where user_id=".$id);
	$row2 = null;
	while($row = $res->fetch_assoc()){
		$temp_lotID =  $row['id'];
?>		
	<div name="main_lot[]" style="width: 99%; display:block;height:100px; border:1px solid black; -webkit-border-radius: 5px; 
	-moz-border-radius: 5px; margin-bottom: 10px;">
		<div style="float:left">
			<button id="btn-primary" onclick ="detail(<?php echo $row['id']; ?>)" style="font-size:10px;height:98px; -webkit-border-radius: 5px;
	-moz-border-radius: 5px;">
				<span style="font-size:18px;color:white"><b><?php echo $row['name_lot']; ?><b></span><br/>
			   	<?php echo $row['seller']; ?>
			    <?php echo $row['location']; ?>
			    <?php  $date = $row['date']; 
						$newDate = date("m-d-Y", strtotime($date));
						echo $newDate ?><br/>
				    Head, Days<br/>
				Projected Weight:<br/>
				Projected Rate of Gain: 

			</button>
			<input type="hidden" name="sell_date[]" value = "<?php echo $row['sell_date']?> ">
		</div>

		<?php	$numRation = $conn->query("select distinct ration_id from feed_type where user_id=$id order by ration_id asc");
			if(mysqli_num_rows($numRation) > 0){
		?>

		<div style="width: 10%; float:left; margin-left: 0.5%; margin-right:0.5%; margin-top:10px">
			<form id ="form1">
			<select  oninput="change_front_page(<?php echo $row['id']?>); update_RationWeight(<?php echo $row['id']?>)"
				id="ration_<?php echo $row['id']?>" style="font-size: 13px;height:65px;text-indent:10px;float:left; margin-top: 15px ;border: 1px solid black; -webkit-border-radius: 5px; -moz-border-radius: 5px; width:95px; " >
			  <?php 
			  	while($res_numRow = $numRation -> fetch_assoc()){
			  		$ration_selected = $res_numRow['ration_id'];
			  		if($ration_selected == $row['ration_selected'])
			  			echo "<option value=\"$ration_selected\" selected >Ration $ration_selected</option>";
			  		else
			  	 		echo "<option value=\"$ration_selected\" >Ration $ration_selected</option>";	
			  	}

			  	if($row['ration_selected'] == -1)
			  			echo "<option value=\"-1\" selected >Self Feed</option>";
			  	else
			  			echo "<option value=\"-1\" >Self Feed</option>";
			?>
			</select>
				
			</form>
		</div>
		<div style="width: 13%;margin-top:10px; float:left;" id="self_feed_inputs<?php echo $row['id']?>" >
			<span class= "weight_input"><input type="number" id="weight1_<?php echo $row['id']?>" onchange="update_RationWeight(<?php echo $row['id']?>)"  
				oninput="change_front_page(<?php echo $row['id']?>)"  value="<?php echo $row['weight_am']?>" step="10" >AM</span>
			<span class="weight_input"><input type="number" id="weight2_<?php echo $row['id']?>" onchange="update_RationWeight(<?php echo $row['id']?>)"  
				oninput="change_front_page(<?php echo $row['id']?>)" value="<?php echo $row['weight_pm']?>" step="10">PM</span>
		</div>
		<div id="front-page-table_<?php echo $row['id']?>" style="width:48.5%;float:left">
			<script type="text/javascript">
				function view_front_page_table(str){
					var id=str;
					var weight1 = $('#weight1_' + str).val();
					var weight2 = $('#weight2_' + str).val();
					var ration = <?php echo $row['ration_selected'];?>;
					if(ration != -1){
					$.ajax({
						type:"POST",
						url: "php/front_page_table.php?id="+str,
						data: "&weight1="+ weight1 +"&ration=" + ration +"&weight2=" +weight2
						}).done(function(data){
							var str_name = "#front-page-table_" + str;
							var str_name2 = "#weight_" + str;
							var total = parseInt(weight1)+parseInt(weight2)+ " lbs";
							$(str_name).html(data);
							$(str_name2).html(total);
							$('#self_feed_inputs'+str).show();
						});
					}
					else{
						$.ajax({
						type:"GET",
						url: "php/self_feed.php?id="+str,
						}).done(function(data){
							var str_name = "#front-page-table_" + str;
							var str_name2 = "#weight_" + str;
							$(str_name).html(data);
							$('#self_feed_inputs'+str).hide();
						});
					}
				}

				view_front_page_table(<?php echo $row['id']?>);
			</script>
		</div>
		<?php }
		else{
			echo '<button class="btn_alert" style="float:left; margin-left:30px;" onclick="redirect_addStuff('.$temp_lotID.')">Step #1 - Add Feed Stuffs </button>';
			echo '<button class="btn_alert" style="float:left; margin-left:30px;" onclick="redirect_addRation('.$temp_lotID.')">Step #2 - Add Rations </button>';
		}
		?>
	</div>

	<?php
	}	
		mysqli_data_seek($res,0);
?>
	</div>
</div>

<div style="height:100px">
<div style="float:left;border-right:1px solid black;height:80px">
	<select class = "yearRecord" id="open_close" onchange="open_close(this,getElementsByName('sell_date[]'))">
		  <option value="open" selected>Open</option>
		  <option value="close">Close</option>
	</select>
	<select class = "yearRecord" >
		  <option value="icon" selected>Icon View</option>
		  <option value="list">List View</option>
	</select>
</div>
<div style="width:40%;margin-left:20px;float:left;height:80px; border-right:1px solid black">
	<button type="button" class="btn btn-primary btn-secondary" style="width:30%;margin-right:10px">Sheets</button>
	<button type="button" class="btn btn-primary btn-secondary" style="width:30%;margin-right:10px" onclick="redirect_addStuff(<?php echo $temp_lotID;?>)">Feed</button>
	<button type="button" class="btn btn-primary btn-secondary" style="width:30%;margin-right:10px">Reports</button>
	<div style="margin-top:10px">
	<button type="button" class="btn btn-primary btn-secondary" style="width:30%;margin-right:10px;margin-top:10px"><span class="glyphicon glyphicon-stats" style="float:left;font-size:22px"></span>Analytics</button>
	<button type="button" class="btn btn-primary btn-secondary" style="width:30%;margin-right:10px;margin-top:10px" onclick="redirect_addRation(<?php echo $temp_lotID;?>)">Rations</button>
	<button type="button" class="btn btn-primary btn-secondary" style="width:30%;margin-right:10px;margin-top:10px"><span class="glyphicon glyphicon-print" style="float:left;font-size:22px"></span>Print</button>
	</div>
	<!-- <div style="height:100px; "></div> -->
</div>
<div style="width:30%;margin-left:20px;float:left">
	<button type="button" class="btn btn-primary" onclick="feed_delivery()" style="width:130px;height:75px;float:left;margin-right:10px">Feed Delivery</button>
	<button type="button" class="btn btn-primary" id="addlotbutton" data-toggle="modal"  data-target="#myAddModal" style="width:110px;float:left">Add lot</button>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myDeleteModal" style="width:110px;float:left;margin-top:10px">Delete lot</button>
</div>
</div>

	<div class="modal fade" id="myDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog delete">
					<div class="modal-content">
					<div class="modal-body">
						<div style=" height:180px;  overflow-x:hidden; overflow-y: auto">
						<?php while($row = $res->fetch_assoc()){ ?>
						<form id="myForm" method="post" action="">
						<input class="checkBox123" name="checkbox[]" type="checkbox" value="<?php echo $row['id']; ?>" style="float:left">
						<span style="font-size: 20px">
							<?php echo $row['name_lot'];?><br>
							<?php echo $row['seller'] . ","; ?>
		    				<?php echo $row['location'] . " "; ?>
		   					<?php echo $row['date']; ?>
							<hr style="height:1px;border:none;color:#fff;background-color:#fff;margin:0;margin-bottom:5px" />
						</span>
						</form>
						<?php
						}
						?>
					</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" name ="delete" class="btn btn-primary" style="width:150px" onclick="helpdeletedata(document.getElementsByClassName('checkBox123'))">Delete</button>
					</div>
				</div>
			</div>
	</div>
		

<?php	
		mysqli_close($conn);
		mysqli_free_result($res);
?>


<script>
function helpdeletedata(check){
	var values = [];
    for (i = 0; i < check.length; i++) {
        if (check[i].checked == true) {
            values.push(check[i].value);
        }
    }
    
    while(values.length > 0){
    	deletedata(values.pop());
    }
}

function redirect_addRation(str){
	var id = str;
	$.ajax({
		type:"GET",
		url: "php/detail.php?id="+id
	}).done(function(data){
		$('#viewdata').html(data);
		view_ration();
		$('#addRationModal').modal('show');
	});
}

function redirect_addStuff(str){
	var id = str;
	$.ajax({
		type:"GET",
		url: "php/detail.php?id="+id
	}).done(function(data){
		$('#viewdata').html(data);
		view_feed_cost();
	});
}

function detail(str){
	var id = str;
	$.ajax({
		type:"GET",
		url: "php/detail.php?id="+id
	}).done(function(data){
		$('#viewdata').html(data);
	});
}


function update_RationWeight(str_id){
	// alert("hi");
	var id = str_id;
	var weight1 = $('#weight1_' + str_id).val();
	var weight2 = $('#weight2_' + str_id).val();	
	var ration = $('#ration_'+ str_id).val();
	var str_weight = parseInt(weight1)+parseInt(weight2);
	var datas = "&weight="+str_weight+"&weight1="+ weight1 +"&weight2=" + weight2+"&ration="+ration;
	// alert(datas);
	$.ajax({
			type: "POST",
			url: "php/updateweight.php?id="+id,
			data: datas
		}).done(function(data){
			console.log(data);
		});
}

function change_front_page(str){

	var weight1 = $('#weight1_' + str).val();
	var weight2 = $('#weight2_' + str).val();
	var ration = $('#ration_' + str).val();
	if(ration != -1){
	$.ajax({
		type:"POST",
		url: "php/front_page_table.php?id="+str,
		data: "&weight1="+ weight1 +"&ration=" + ration +"&weight2=" +weight2
		}).done(function(data){
			var str_name = "#front-page-table_" + str;
			var str_name2 = "#weight_" + str;
			var total = parseInt(weight1)+parseInt(weight2)+ " lbs";
			$(str_name).html(data);
			$(str_name2).html(total);
			$('#self_feed_inputs'+str).show();
		});
	}
	else{
		$.ajax({
		type:"GET",
		url: "php/self_feed.php?id="+str,
		}).done(function(data){
			var str_name = "#front-page-table_" + str;
			var str_name2 = "#weight_" + str;
			$(str_name).html(data);
			$('#self_feed_inputs'+str).hide();
		});
	}
}

function update_selection(str_id,selection){
	var id = str_id;
	$.ajax({
		type: "POST",
		url: "php/update_selection.php?id="+id,
		data: "&selection="+selection
	}).done(function(data){
	});
}

function feed_delivery(){
	$.ajax({
		type:"GET",
		url: "php/feed_delivery.php"
	}).done(function(data){
		$('#viewdata').html(data);
		var button1 = '<button type="button" class="btn btn-primary btm" data-toggle="modal" data-target="#addRationModal" style="width:250px">Add Ration</button>';
		var button2 = '<button type="button" class="btn btn-primary btm" data-toggle="modal" data-target="#deleteRationModal" style="width:250px">Delete Ration</button>';	
		$('#bottom-buttons').html(button1+button2);
	});
}

function open_close(str,check){
	var values = [];
	var name = document.getElementsByName("main_lot[]");
	// var dates = new Date("0000-00-00")
	// alert(str.value);
    for (i = 0; i < check.length; i++) {
        if(check[i].value == '0000-00-00 ' && str.value == "open"){
        	$(name[i]).show();
        }
        else if(check[i].value != '0000-00-00' && str.value == "close"){
        	$(name[i]).hide();
        }	

    }
}


</script>