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
			<th width="90px">Total Ration</th>
			<th width="90px">Total Dry</th>
			<th width="90px">DMI</th>
			<?php 
				$type_name = array();
				$res2 = $conn->query("select type_name from feedtype where user_id=$user_id");
				$counter = 0;

				while($row2 = $res2->fetch_assoc()){
					$type_name[] = $row2['type_name'];
					echo "<th width=\"70px\">".$row2['type_name']."</th>";	
					$counter++;
				}
					
			?>
		</tr>
	</thead>

	<tbody>
		<?php 	
			$temp_Date = "";
			$date_res = $conn->query("select distinct date_type from test_ration where lot_id =$id order by date_type asc");
			$row_count =0;

		while($row = $date_res->fetch_assoc()){
			
			$row_count++;
			$date = $row['date_type']; 
			$newDate = date("m-d-Y", strtotime($date));
			$result = $conn->query("select percent_dry, type, percent_weight,total_dry, dmi from test_ration where lot_id=$id and date_type='$date'");
			$result_id = $conn->query("select table_id from test_ration where lot_id=$id and date_type='$date'");
			$dry = array();
			$percent_weight = array();
			$type = array();
			$row100 = $result_id -> fetch_assoc();
			$row_id = $row100['table_id'];	
			$temp_dry=0;
			$temp_dmi=0;
			while($row0 = $result->fetch_assoc()){
				// $dry[] = $row0['percent_dry'];
				$percent_weight[] = $row0['percent_weight'];
				$type[] = $row0['type'];
				$temp_dry=$row0['total_dry'];
				$temp_dmi =$row0['dmi'];
			}

				echo "<tr id=\"ration_row_$date\">";
				echo "<td class=\"checkBoxColumn\"style=\"border:none\"><input type=\"checkbox\" class=\"ration_edit ration_checkBox\" value=\"$date\" onchange=\"change_checkBox(this)\"></td>";
				echo "<td >". $newDate. "</td>";
				// echo "<td contenteditable=\"true\">". $result->num_rows ."</td>";

				// $total_dry = 0;
				$total_weight = 0;

				// foreach($dry as $dries){
				// 	$total_dry += $dries;
				// }

				foreach($percent_weight as $weights)
					$total_weight += $weights;					

				echo "<td >". $total_weight ."</td>";

				// if($total_dry == 0) 
				// 	echo "<td >". $row['total_dry'] ."</td>";
				// else
				echo "<td >". $temp_dry ."</td>";
				echo "<td >". $temp_dmi ."</td>";

				foreach($type_name as $typeNames){
					$check = false;
					for($i=0; $i<count($type); $i++){
						if($type[$i] == $typeNames){
							echo "<td >". $percent_weight[$i] ."</td>";
							$check=true;
							break;
						}		
					}
					if(!$check)
						echo "<td ></td>";
				}
				echo "</tr>";

		}
		echo "<tr class=\"ration_edit\">";
		echo "<td style=\"border:none\" ></td>";		
		echo "<td><input type=\"date\" class=\"form-control newRationData\"></td>";
		for($i=1; $i<$counter+4; $i++){
			echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		}
		echo "</tr>";
		
		for($i=0; $i<16-$row_count; $i++){
			echo "<tr>";
				echo '<td style="border:none"></td>';

			for($j=1; $j<$counter+5; $j++){
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
					$("#ration_row_"+str.value +" td:not(.checkBoxColumn)").each(function(){
	        	 		$(this).replaceWith("<td class=\"newRationData"+str.value+"\"><input type='text' class=\"form-control\"value='" + $(this).text() + "'></td>");
	        	 	});
				}
				else{
					$("#ration_row_"+str.value +" td:not(.checkBoxColumn)").each(function(){
	        	 		$(this).replaceWith("<td>"+ $(this).children().val() + "</td>");
	        	 	});
				}
			}

			function add_row_ration(str){
				var input = [];
				$('#ration-table .newRationData').each(function(){
					input.push($(this).val());
				});

				var date_type = input[0];
				var weight = 0;
				<?php $type_to_json=json_encode($type_name);
					echo "var type_name = ". $type_to_json ."\n";
				?>
				$.ajax({
					type: "POST",
					url: "php/delete_ration_detail.php?lot_id="+str,
					data: "&date_type="+date_type
				}).done(function(data){
					for(i=4; i<input.length; i++){
					if(input[i] != ""){
						var datas = "&date_type="+date_type+"&weight="+weight+"&type="+type_name[i-4]+
						"&percent_weight="+input[i];
						// alert(datas);
						$.ajax({
								type: "POST",
								url: "php/new_ration_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								console.log(data);
								view_ration_table(str);
							});
					}
				}
				});

				// alert(type_name);
				
			}	
			
			function delete_row_ration(str){
				var check = $(".ration_checkBox");
				for (i = 0; i < check.length; i++) {
			        if (check[i].checked == true) {
			        	var datas = "&date_type=" + check[i].value;
			          $.ajax({
								type: "POST",
								url: "php/delete_ration_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_ration_table(str);
								$(".ration_edit").show();
							});
			        }
   				}
			}

			function edit_row_ration(str){
				var check = $(".ration_checkBox");
				<?php $type_to_json=json_encode($type_name);
					echo "var type_name = ". $type_to_json .";\n";
				?>
				for (j = 0; j < check.length; j++) {
					var input = new Array();

			        if (check[j].checked == true) {
			        	$('.newRationData'+check[j].value).each(function(){
							input.push($(this).children().val());
						});
			        	var previous_date = check[j].value;
			   			var date_type = input[0];
			   			var result = date_type.split("-");
			   			date_type = result[2] +"-"+result[0]+"-"+result[1];
						var weight = 0;
						for(i=3; i<input.length; i++){
							if(input[i] !="")
								weight += input[i];
						}
						var dry = input[2];
						// alert(date_type+weight+dry);
						if(previous_date != date_type){
							$.ajax({
							type: "POST",
							url: "php/delete_ration_detail.php?lot_id="+str,
							data: "&date_type="+previous_date
						}).done(function(data){
							});
						}
						$.ajax({
							type: "POST",
							url: "php/delete_ration_detail.php?lot_id="+str,
							data: "&date_type="+date_type
						}).done(function(data){
							console.log(data);
						});

						for(i=4; i<input.length; i++){
							if(input[i] != ""){
								var datas = "&date_type="+date_type+"&weight="+weight+"&dry="+dry+"&type="+type_name[i-4]+
								"&percent_weight="+input[i]
								// alert(datas);
								$.ajax({
										type: "POST",
										url: "php/new_ration_detail.php?lot_id="+str,
										data: datas
									}).done(function(data){
										console.log(data);
										view_ration_table(str);
									});
							}
						}
						
			        }
			        // alert("loop exit");
   				}
			}

			function edit_rationTable(str){
				$(".ration_edit").toggle(0, function(){
					if($(".ration_edit").is(':visible')){
						$(".not_ration_edit").hide();
						$(str).html("Cancel");
					}
					else{
						$(".not_ration_edit").show();
						$(str).html("Edit Table");
					}
				});
			}
		</script>
	</tbody>
</table>
</div>