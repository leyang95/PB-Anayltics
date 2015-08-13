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
?>
<div class="bigBossTable">
<table class="table table-hover table-bordered" id ="ration-table" style="table-layout: fixed;">
	<thead style="color:#fff; background-color:#8B0000;text-align:center">
		<tr>
			<th width="15px"></th>
			<th width="125px">Date</th>
			<th width="100px">Source</th>
			<th width="90px">Total Cost</th>
			<th width="90px">Purchase Weight</th>
			<th width="90px">Farm Weight</th>
			<th width="90px">Number of Head</th>
			<th width="90px">Breed</th>
			<th width="90px">Gender</th>
			<th width="90px">Trucking Cost</th>
			<th width="90px">Cattle Condition</th>
			<th width="90px">Cost / Head</th>
			<th width="90px">Cost / Pound</th>
		</tr>
	</thead>

	<tbody>
		<?php 	 
			$date_res = $conn->query("select * from `table_addhead`  where lot_id =$id order by date asc");
			$row_count=0;

		while($row = $date_res->fetch_assoc()){
			$row_count++;
			$date = $row['date']; 
			$newDate = date("m-d-Y", strtotime($date));
			$table_id = $row['table_id'];
				echo "<tr id=\"ration_row_$table_id\">";
				echo "<td class=\"checkBoxColumn\"style=\"border:none\"><input type=\"checkbox\" class=\"ration_edit ration_checkBox\" value=\"$table_id\" onchange=\"change_checkBox(this)\"></td>";
				echo "<td >". $newDate. "</td>";
				echo "<td >". $row['source'] ."</td>";
				echo "<td >". $row['total_cost'] ."</td>";
				echo "<td >". $row['purchase_weight'] ."</td>";
				echo "<td >". $row['farm_weight'] ."</td>";
				echo "<td >". $row['num_head'] ."</td>";
				echo "<td >". $row['breed'] ."</td>";
				echo "<td >". $row['gender'] ."</td>";
				echo "<td >". $row['trucking_cost'] ."</td>";
				echo "<td >". $row['cattle_condition'] ."</td>";
				echo "<td >". $row['cost_per_head'] ."</td>";
				echo "<td >". $row['cost_per_pound'] ."</td>";
		}
		echo "<tr class=\"ration_edit\">";
		echo "<td style=\"border:none\" ></td>";
		
		echo "<td><input type=\"date\" class=\"form-control newRationData\"></td>";
		for($i=0; $i<11; $i++){
			echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		}
		echo "</tr>";

		for($i=0; $i<16-$row_count; $i++){
			echo "<tr>";
				echo '<td style="border:none"></td>';

			for($j=1; $j<13; $j++){
				echo "<td></td>";
			}

			echo "</tr>";
		}
		?>
		
	</tbody>
	</div>
</table>
</div>

		<script type="text/javascript">
			if($(".not_ration_edit").is(':visible'))
				$(".ration_edit").hide();
			else
				$(".ration_edit").show();

			function change_checkBox(str){
				// alert(str.value);
				checkBox = 	$("#ration_row_"+str.value +" td").first().children();

				if(checkBox.is(":checked")){
					// alert("checkbox is checked.");
					$("#ration_row_"+str.value +" td:not(.checkBoxColumn").each(function(index){
	        	 		// alert(index);	        
	        	 		if(index == 6)
	        	 			$(this).replaceWith("<td class=\"newRationData"+str.value+"\"><select name=\"gender\" id=\"gender\" class=\"form-contorl\"><option value=\"Steer\">Steer</option><option value=\"Heifer\">Heifer</option><option value=\"Bulls\">Bulls</option><option value=\"Cows\">Cows</option><option selected value='" + $(this).text() + "'>"+ $(this).text() +"</select></td>");
	        	 		else	
	        	 			$(this).replaceWith("<td class=\"newRationData"+str.value+"\"><input type='text' class=\"form-control\"value='" + $(this).text() + "'></td>");
	        	 	});

				}
				else{
					$("#ration_row_"+str.value +" td:not(.checkBoxColumn)").each(function(){
	        	 		$(this).replaceWith("<td>"+ $(this).children().val() + "</td>");
	        	 	});
				}
	        	 

			}
			
			function add_row_addHead(str){
				var input = [];
				$('#ration-table .newRationData').each(function(){
					input.push($(this).val());
				});

				var date = input[0];
				var source = input[1];
				var total_cost = input[2];
				var purchase_weight = input[3];
				var farm_weight = input[4];
				var num_head = input[5];
				var breed = input[6];
				var gender = input[7];
				var trucking_cost = input[8];
				var cattle_cond = input[9];
				var cost_head = input[10];
				var cost_pound = input[11];

				if(date != "" && source != ""){
						var datas = "&date="+ date+"&source="+source+"&total_cost="+total_cost+"&purchase_weight="+purchase_weight+"&farm_weight="+farm_weight+"&num_head="+num_head+"&breed="+breed+"&gender="+gender+"&trucking_cost="+trucking_cost+"&cattle_cond="+cattle_cond+"&cost_head="+cost_head+"&cost_pound="+cost_pound;
						// alert(datas);
						$.ajax({
								type: "POST",
								url: "php/new_addHead_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_AddHead_table(str);
							});
				}
		}	
			
			function delete_row_addHead(str){
				var check = $(".ration_checkBox");
				for (i = 0; i < check.length; i++) {
			        if (check[i].checked == true) {
			        	var datas = "&table_id=" + check[i].value;
			          $.ajax({
								type: "POST",
								url: "php/delete_addHead_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_AddHead_table(str);
							});
			        }
   				}
			}

			function edit_row_addHead(str){
				var check = $(".ration_checkBox");
				for (j = 0; j < check.length; j++) {
					var input = new Array();

			        if (check[j].checked == true) {
			        	$('.newRationData'+check[j].value).each(function(){
							input.push($(this).children().val());
						});

					    var date = input[0];
						var source = input[1];
						var total_cost = input[2];
						var total_weight = input[3];
						var num_head = input[4];
						var breed = input[5];
						var gender = input[6];
						var trucking_cost = input[7];
						var cattle_cond = input[8];
						var cost_head = input[9];
						var cost_pound = input[10];
						var table_id = check[j].value;
						
						if(date != "" && source != ""){
								var datas = "&date="+ date+"&source="+source+"&total_cost="+
								total_cost+"&total_weight="+total_weight+"&num_head="+num_head+"&breed="+breed+
								"&gender="+gender+"&trucking_cost="+trucking_cost+"&cattle_cond="+cattle_cond+
								"&cost_head="+cost_head+"&cost_pound="+cost_pound+"&table_id="+table_id;
								// alert(datas);
								$.ajax({
										type: "POST",
										url: "php/update_addHead_detail.php?lot_id="+str,
										data: datas
									}).done(function(data){
										// console.log(data);
										view_AddHead_table(str);
									});
						}
		   			}
				}
			}
		</script>