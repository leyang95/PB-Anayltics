<?php
	include "config.php";
	$id = $_GET['id'];
	$res2 = $conn->query("select * from feed_type where lot_id=" . $id);
?>	

<form oninput="calculate_weight(a.value)">
	<table class="table table-hover" >
		<thead>
			<tr><th>
				<select style="float:left; margin-left: 20px">
				  <option value="ration1" selected>Ration 1</option>
				  <option value="ration2">Ration 2</option>
				  <option value="ration3">Ration 3</option>
				  <option value="ration4">Ration 4</option>
				</select>
				<th></th>
				</th>			
				
		 	 </tr>
		 </thead>
		<tbody>
			<tr>
				<td rowspan ="2"><input type="number" name="a"  value="5000" step="10" style="float:left"></td>
				<td style="border:none;">Weights</td>
				
		 	</tr>
		 	<tr>
		 		<td style="border:none;">Loaded</td>
		 	</tr>

		</tbody>
	</table>
</form>

