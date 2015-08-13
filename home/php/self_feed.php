<?php
	session_start();
	include "config.php";
	 if (isset($_SESSION['user_id']))
	  {
	    $user_id     = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

if(!$loggedin)
	die("Please Log in to continue...");

	$id = $_GET['id'];

	$res = $conn->query("SELECT * from self_feed where lot_id = $id order by date_filled desc");
	$row = $res -> fetch_assoc();

?>		
<div style="width: 1000px;">
	<form>
		<div style="float:left;width:50%;margin-top:13px">
			<div id="weight_<?php echo $row['id']?>"
					class="btn btn-primary" style="float:left;height: 30px;margin-left:50px;width:138px;margin-top:10px;"><?php 
					$originalDate = $row['date_filled'];
					$newDate = date("m-d-Y", strtotime($originalDate));
					echo $newDate;?></div>
			<div id="weight_<?php echo $row['id']?>"
					class="btn btn-primary" style="float:left;width: 110px; height: 30px; margin-left:20px;margin-top:10px"><?php echo $row['weight']?> lbs</div>
		</div>
		<div style="width:50%">
			<input type="date" id="date_<?php echo $id?>" value="" placeholder="Date Filled" style="float:left; border: 1px solid black; 
		    -webkit-border-radius: 5px;  margin-left:50px;margin-top:10px;
		    -moz-border-radius: 5px;
		    border-radius: 5pxs; text-indent:10px; height:30px;">
	    	<span id = "slide_self<?php echo $id?>"class="weight_input" style="float:left;height:30px; margin-left: 20px;margin-top:10px;">
	    		<input type="number" id="total_weight_<?php echo $id?>" oninput="update_weight(<?php echo $id?>,this)"  value="" step="10" style="height:20px; ">lbs</span>
	    	<button type="button"  class="btn btn-primary" style="float:left;width:100px; margin-left:20px; margin-top:10px" onclick="updateSelfFeed(<?php echo $id?>);">Submit</button>
    	</div>
	</form>
</div>


<script type="text/javascript">
	function update_weight(str,total){
		var str_name2 = "#weight_" + str;
		// $(str_name2).html(total.value + " lbs");
	}

	function updateSelfFeed(str){
		var date = $('#date_' +str).val();
		var weight= $('#total_weight_' + str).val();
		var id = str;
		if(date == ""){
			return;
		}

		$.ajax({
				type: "POST",
				url: "php/new_self_feed.php?id="+id,
				data: "&date_id=" + date + "&weight=" + weight 
			}).done(function(data){
				console.log(data);
				change_front_page(str);
			});
	}
</script>