<?php
	include "config.php";
	$id = $_GET['id'];
?>

<div class="bigBossTable">
	<table class="table table-hover table-bordered" id ="ration-table">
		<thead style="color:#fff; background-color:#8B0000;text-align:center">
			<tr>
				<th width="15px"></th>
				<th width="125px">Date</th>
				<th width="100px">Type of Cost</th>
				<th width="100px">Cost Amount</th>
				<th width="140px">Notes</th>
			</tr>
		</thead>
		<tbody>
		<?php 	
			$date_res = $conn->query("select * from feed_cost where lot_id = $id order by date asc");
			$row_count=0;
		while($row = $date_res->fetch_assoc()){
			$row_count++;
			$type_name = array();
			$date = $row['date']; 
			$newDate = date("m-d-Y", strtotime($date));
			$cost_id = $row['cost_id'];

				echo "<tr id=\"ration_row_$cost_id\">";
				echo "<td class=\"checkBoxColumn\"style=\"border:none\"><input type=\"checkbox\" class=\"ration_edit ration_checkBox\" value=\"$cost_id\" onchange=\"change_checkBox(this)\"></td>";
				echo "<td >". $newDate. "</td>";
				echo "<td >". $row['type'] ."</td>";
				echo "<td >". $row['cost_amount'] ."</td>";
				echo "<td >". $row['notes'] ."</td>";

		}
		echo "<tr class=\"ration_edit\">";
		echo "<td style=\"border:none\" ></td>";
		
		echo "<td><input type=\"date\" class=\"form-control newRationData\"></td>";
		for($i=0; $i<3; $i++){
			echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		}
		echo "</tr>";

		for($i=0; $i<16-$row_count; $i++){
			echo "<tr>";
				echo '<td style="border:none"></td>';

			for($j=1; $j<5; $j++){
				echo "<td></td>";
			}

			echo "</tr>";
		}
		?>
		

		</tbody>
	</table>
	<script type="text/javascript">
			if($(".not_ration_edit").is(':visible'))
				$(".ration_edit").hide();
			else
				$(".ration_edit").show();

			function add_row_otherCost(str){
				var input = [];
				$('#ration-table .newRationData').each(function(){
					input.push($(this).val());
				});

				var date = input[0];
				var type = input[1];
				var cost_amount = input[2];
				var notes = input[3];


				if(date != ""){
					var datas = "&date="+ date+"&type="+type+"&cost_amount="+cost_amount+"&notes="+notes;
					$.ajax({
							type: "POST",
							url: "php/new_otherCost_detail.php?lot_id="+str,
							data: datas
						}).done(function(data){
							// console.log(data);
							view_OtherCosts_table(str);
						});
				}
			}

			function delete_row_otherCost(str){
				var check = $(".ration_checkBox");
				for (i = 0; i < check.length; i++) {
			        if (check[i].checked == true) {
			        	var datas = "&cost_id=" + check[i].value;
			          $.ajax({
							type: "POST",
							url: "php/delete_otherCost_detail.php?lot_id="+str,
							data: datas
						}).done(function(data){
							// console.log(data);
							view_OtherCosts_table(str);
						});
			        }
   				}
			}	

			function edit_row_otherCost(str){
				var check = $(".ration_checkBox");
				for (j = 0; j < check.length; j++) {
					var input = new Array();

			        if (check[j].checked == true) {
			        	$('.newRationData'+check[j].value).each(function(){
							input.push($(this).children().val());
						});

					   	var date = input[0];
						var type = input[1];
						var cost_amount = input[2];
						var notes = input[3];


						if(date != ""){
							var datas = "&date="+ date+"&type="+type+"&cost_amount="+cost_amount+"&notes="+notes+"&cost_id="+check[j].value;
							$.ajax({
									type: "POST",
									url: "php/update_otherCost_detail.php?lot_id="+str,
									data: datas
								}).done(function(data){
									// console.log(data);
									view_OtherCosts_table(str);
								});
						}
					}
				}
			}

			
	</script>

</div>

