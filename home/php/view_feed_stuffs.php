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

			<?php 
				$res2 = $conn->query("select type_name from feedtype where user_id=$user_id");
				$type_name = array();
				while($row2 = $res2->fetch_assoc()){
					$type_name[] = $row2['type_name'];
				}

				echo "<tr>";
				echo '<th width="15px"></th>
					<th width="125px" style="text-align:center;">Date</th>';
				for($i=0; $i<count($type_name); $i++){		
					echo '<th width="100px">Price<br>per ton<br>'.$type_name[$i].'</th>';
				}
				for($i=0; $i<count($type_name); $i++){
					echo '<th width="100p">DM %<br>of<br>'.$type_name[$i].'</th>';
				}
				echo "</tr>";
			?>
	</thead>

	<tbody>
		<?php 	
			$temp_Date = "";
			$date_res = $conn->query("select distinct date from table_feedstuffs where lot_id = $id order by date asc");
			$row_count=0;

		while($row = $date_res->fetch_assoc()){
			$row_count++;
			$date = $row['date']; 
			$newDate = date("m-d-Y", strtotime($date));
			$result = $conn->query("select dm, type, price from table_feedstuffs where lot_id=$id and date='$date'");
			$dry = array();
			$price = array();
			$type = array();
		
			while($row0 = $result->fetch_assoc()){
				$dry[] = $row0['dm'];
				$price[] = $row0['price'];
				$type[] = $row0['type'];
			}

				echo "<tr id=\"ration_row_$date\">";
				echo "<td class=\"checkBoxColumn\"style=\"border:none\"><input type=\"checkbox\" class=\"ration_edit ration_checkBox\" value=\"$date\" onchange=\"change_checkBox(this)\"></td>";
				echo "<td >". $newDate. "</td>";

				foreach($type_name as $typeNames){
					$check = false;
					for($i=0; $i<count($type); $i++){
						if($type[$i] == $typeNames){
							echo "<td >". $price[$i] ."</td>";
							$check=true;
							break;
						}		
					}
					if(!$check)
						echo "<td ></td>";
				}

				foreach($type_name as $typeNames){
					$check = false;
					for($i=0; $i<count($type); $i++){
						if($type[$i] == $typeNames){
							echo "<td >". $dry[$i] ."</td>";
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
		for($i=0; $i<count($type_name)*2; $i++){
			echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		}
		echo "</tr>";

		for($i=0; $i<16-$row_count; $i++){
			echo "<tr>";
				echo '<td style="border:none"></td>';

			for($j=1; $j<count($type_name)*2+2; $j++){
				echo "<td></td>";
			}

			echo "</tr>";
			}

		?>


		<script type="text/javascript">
			if($(".not_ration_edit").is(':visible'))
				$(".ration_edit").hide();
			else
				$(".ration_edit").show();

			function add_row_feedStuff(str){
				var input = [];
				$('#ration-table .newRationData').each(function(){
					input.push($(this).val());
				});
				var date_type = input[0];
	
				<?php $type_to_json=json_encode($type_name);
					echo "var type_name = ". $type_to_json .";\n";
				?>
				$.ajax({
					type: "POST",
					url: "php/delete_feedStuff_detail.php?lot_id="+str,
					data: "&date_type="+date_type
				}).done(function(data){
					for(i=1; i<=type_name.length; i++){
					if(input[i] != "" || input[i+type_name.length] != ""){
						var datas = "&date_type="+date_type+"&dry="+input[i+type_name.length]+"&type="+type_name[i-1]+
						"&price="+input[i];
						// alert(datas);
						$.ajax({
								type: "POST",
								url: "php/new_feedStuff_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_feed_stuffs(str);
							});
					}
					}
				});
				
			}

			function edit_row_feedStuff(str){
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

			   			 if (check[j].checked == true) {
				        	$('.newRationData'+check[j].value).each(function(){
								input.push($(this).children().val());
							});
				        	var previous_date = check[j].value;
				   			var date_type = input[0];
				   			var result = date_type.split("-");
				   			date_type = result[2] +"-"+result[0]+"-"+result[1];

						// alert(date_type+previous_date);
							if(previous_date != date_type){
								$.ajax({
									type: "POST",
									url: "php/delete_feedStuff_detail.php?lot_id="+str,
									data: "&date_type="+previous_date
								}).done(function(data){
									console.log(data);
								});
							}
							$.ajax({
								type: "POST",
								url: "php/delete_feedStuff_detail.php?lot_id="+str,
								data: "&date_type="+date_type
							}).done(function(data){
								console.log(data);
							});

							for(i=1; i<=type_name.length; i++){
								if(input[i] != "" || input[i+type_name.length] != ""){
									var datas = "&date_type="+date_type+"&dry="+input[i+type_name.length]+"&type="+type_name[i-1]+
									"&price="+input[i];
									// alert(datas);
									$.ajax({
											type: "POST",
											url: "php/new_feedStuff_detail.php?lot_id="+str,
											data: datas
										}).done(function(data){
											console.log(data);
											view_feed_stuffs(str);
										});
								}
							}
											        
						
			        }
			        // alert("loop exit");
   				}
			}
		}

			function delete_row_feedStuff(str){
				var check = $(".ration_checkBox");
				for (i = 0; i < check.length; i++) {
			        if (check[i].checked == true) {
			        	var datas = "&date_type=" + check[i].value;
			          $.ajax({
								type: "POST",
								url: "php/delete_feedStuff_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_feed_stuffs(str);
								$(".ration_edit").show();
							});
			        }
   				}
			}	
		</script>
	</tbody>
</table>
</div>
