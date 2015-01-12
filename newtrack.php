<!DOCTYPE html>
<html lang="en-US">

<?php

include 'dbinfo.php';
include 'inputcheck.php';




if(isset($_POST['create']))
{
	$submit_message = '';
	$display = TRUE;	
	$valid = 0;

	if( ! inputcheck($_POST['track_name'],'string',64) ){ 
		$submit_message = 'Invalid Track Name';
	}elseif( ! checkNumber($_POST['length'], 0, 99999999999) ){ 
		$submit_message = 'Invalid Length';
	}elseif( ! checkNumber($_POST['type'], 1, 2) ){ 
		$submit_message = 'Invalid Track type';
	}elseif( ! checkNumber($_POST['type'], -90, 90) ){ 
		$submit_message = 'Invalid Start Latitude';
	}elseif( ! checkNumber($_POST['type'], -180, 180) ){ 
		$submit_message = 'Invalid Start Longitude';
	}elseif($_POST['type'] == '1'){
		if( ! checkNumber($_POST['type'], -90, 90) ){ 
			$submit_message = 'Invalid Finish Latitude';
		}elseif( ! checkNumber($_POST['type'], -180, 180) ){ 
			$submit_message = 'Invalid Finish Longitude';
		}
	}else{$valid = 1;}

	if($valid == 1)
	{
		try 
		{
			$db = new PDO("mysql:dbname=$dbname;host=$dbhost","$dbuser","$dbpass");
		}
		catch(PDOException $e)
	    {
			echo $e->getMessage();
	    }
		
		$create_uid = 1;
		$active = 1;
		

		$query = $db->prepare("INSERT INTO tracks (track_name, length, country, type, start_latitude, start_longitude, end_latitude, end_longitude, start_heading, active, created_uid) VALUES (:track_name, :length,:country, :type, :start_latitude, :start_longitude, :end_latitude, :end_longitude, :start_heading, :active, :created_uid)");
		$query->bindParam(':track_name', $_POST['track_name']);
		$query->bindParam(':length', $_POST['length']);
		$query->bindParam(':country', $_POST['country']);
		$query->bindParam(':type', $_POST['type']);
		$query->bindParam(':start_latitude', $_POST['start_latitude']);
		$query->bindParam(':end_latitude', $_POST['end_latitude']);
		$query->bindParam(':start_longitude', $_POST['start_longitude']);
		$query->bindParam(':end_longitude', $_POST['end_longitude']);
		$query->bindParam(':start_heading', $_POST['start_heading']);
		$query->bindParam(':active', $active);
		$query->bindParam(':created_uid', $create_uid);
		
		try
		{		
			$query->execute();
			$submit_message = "Track Added Successfully.";
		}
		catch (PDOException $e2)
		{	
			$submit_message = $e2->getMessage();
		}
		
		$db = NULL;
	}
}
?>

<div align="center" style="color:#eeeeeeeee">
	Create new Track
	<form method="post" action="<?php $_PHP_SELF ?>">
	
	<table id="form_table">

	<tr>
		<td>Track Name</td>
		<td><input name="track_name" type="text" id="track_name" size=50 maxlength=64 autofocus required value="<?php echo (isset($_POST['track_name']) && $display == FALSE) ? $_POST['track_name'] : '';?>" ></td>
	</tr>
	
	<tr>
		<td>Length</td>
		<td>
			<input name="length" type="number" id="length" size=50 maxlength=16 min="0" max="99999999999" value="<?php echo (isset($_POST['length']) && $display == FALSE) ? $_POST['length'] : '';?>">
			meters
		</td>
	</tr>
	<tr>
		<td>Track Type</td>
		<td>Circuit&nbsp;<input name="type" type="radio" id="type" value="1" checked>&nbsp;&nbsp;Sprint&nbsp;<input name="type" type="radio" id="type" value="2" selected></td>

	</tr>
	<tr>
		<td>
			Start
		</td>
		<td>
			Latitude:&nbsp;<input name="start_latitude" type="number" id="start_latitude" size=30 maxlength=11 min="-90" max="90" step="0.00001" placeholder="41.9714451" required value="<?php echo (isset($_POST['start_latitude']) && $display == FALSE) ? $_POST['start_latitude'] : '';?>">
			<br>
			Longitude:&nbsp;<input name="start_longitude" type="number" id="start_longitude" size=30 maxlength=11 min="-180" max="180" step="0.00001" placeholder="-20.6870728" required value="<?php echo (isset($_POST['start_longitude']) && $display == FALSE) ? $_POST['start_longitude'] : '';?>">
		</td>
	</tr>
	
	<tr>
		<td>
			Finish 
		</td>
		<td>
			Latitude:&nbsp;<input name="end_latitude" type="number" id="end_latitude" size=30 maxlength=11 min="-84" max="84" step="0.00001" placeholder="41.9714451" required value="<?php echo (isset($_POST['end_latitude']) && $display == FALSE) ? $_POST['end_latitude'] : '';?>">
			<br>
			Longitude:&nbsp;<input name="end_longitude" type="number" id="end_longitude" size=30 maxlength=11 min="-180" max="180" step="0.00001" placeholder="-20.6870728" required value="<?php echo (isset($_POST['end_longitude']) && $display == FALSE) ? $_POST['end_longitude'] : '';?>">
		</td>
	</tr>

	<tr>
		<td>Running Direction</td>
		<td>Clockwise<input name="start_heading" type="radio" id="start_heading" value="1" checked>&nbsp;Anti-Clockwise<input name="start_heading" type="radio" id="start_heading" value="2" selected></td>

	</tr>
	<tr>
		<td> </td>
		<td>
			<input type="text" name='country' id="country" list="someCountries" placeholder="Country" value="<?php echo (isset($_POST['country']) && $display == FALSE) ? $_POST['country'] : '';?>" />
			<datalist id="someCountries">
					<?php include ("countrydropdown.html"); ?>
			</datalist>
		</td>
	</tr>
	
	
	<tr>
		<td>
			<input name="create" type="submit" id="create" value="Create">
		</td>
		<td>
			<?php echo isset($submit_message) ? $submit_message : 'a'; ?>
		</td>
	</tr>

	</table>
	
	</form>
</div>

</html>