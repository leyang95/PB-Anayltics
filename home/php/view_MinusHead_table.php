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
			<th width="100px">Type</th>
			<th width="90px">Number of Head</th>
			<th width="90px">Price</th>
			<th width="90px">Move to</th>
			<th width="190px">Notes</th>
		</tr>
	</thead>

	<tbody>
		<?php 	
			$date_res = $conn->query("select * from table_minushead where lot_id=$id order by date asc ");
			$row_count=0;
		while($row = $date_res->fetch_assoc()){
			$row_count++;
			$date = $row['date']; 
			$newDate = date("m-d-Y", strtotime($date));
			$move_res = $conn->query("select name_lot from test where id =". $row['move_to']);
			$move_to = "";
			$table_id = $row['table_id'];
			if($move_res == TRUE){
				$move_row = $move_res->fetch_assoc();
				$move_to = $move_row['name_lot'];
			}

				echo "<tr id=\"ration_row_$table_id\">";
				echo "<td class=\"checkBoxColumn\"style=\"border:none\"><input type=\"checkbox\" class=\"ration_edit ration_checkBox\" value=\"$table_id\" onchange=\"change_checkBox(this)\"></td>";
				echo "<td >". $newDate. "</td>";
				echo "<td >". $row['type'] ."</td>";
				echo "<td >". $row['num_head'] ."</td>";
				echo "<td >". $row['price'] ."</td>";
				echo "<td >". $move_to ."</td>";
				echo "<td >". $row['notes'] ."</td>";
		}
		echo "<tr class=\"ration_edit\">";
		echo "<td style=\"border:none\" ></td>";
		
		echo "<td><input type=\"date\" class=\"form-control newRationData\"></td>";
		for($i=0; $i<5; $i++){
			echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		}
		echo "</tr>";
		
		for($i=0; $i<16-$row_count; $i++){
			echo "<tr>";
				echo '<td style="border:none"></td>';

			for($j=1; $j<7; $j++){
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
			
			function add_row_minusHead(str){
				var input = [];
				$('#ration-table .newRationData').each(function(){
					input.push($(this).val());
				});

				var date = input[0];
				var type = input[1];
				var num_head = input[2];
				var price = input[3];
				var move_to = input[4];
				var notes = input[5];

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
							});
				}
		}	
			
			function delete_row_minusHead(str){
				var check = $(".ration_checkBox");
				for (i = 0; i < check.length; i++) {
			        if (check[i].checked == true) {
			        	var datas = "&table_id=" + check[i].value;
			          $.ajax({
								type: "POST",
								url: "php/delete_minusHead_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_MinusHead_table(str);
							});
			        }
   				}
			}

			function edit_row_minusHead(str){
				var check = $(".ration_checkBox");
				for (j = 0; j < check.length; j++) {
					var input = new Array();

			        if (check[j].checked == true) {
			        	$('.newRationData'+check[j].value).each(function(){
							input.push($(this).children().val());
						});

					   	var date = input[0];
						var type = input[1];
						var num_head = input[2];
						var price = input[3];
						var move_to = input[4];
						var notes = input[5];
						var table_id = check[j].value;

						if(date != ""){
							var datas = "&date="+ date+"&type="+type+"&num_head="+num_head+"&price="+price+"&move_to="+move_to+"&notes="+notes+"&table_id="+table_id;
							// alert(datas);
							$.ajax({
									type: "POST",
									url: "php/update_minusHead_detail.php?lot_id="+str,
									data: datas
								}).done(function(data){
									console.log(data);
									view_MinusHead_table(str);
								});
						}
		   			}
				}
			}
		</script>