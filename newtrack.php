<!DOCTYPE html>
<html lang="en-US">

<?php

require 'dbinfo.php';
require 'inputcheck.php';


if(isset($_POST['create']))
{
	$valid = FALSE;
	$success = FALSE;
	// inputcheck($string,$type,$length,$minlength, $min, $max)
	if( !isset($_POST['track_name']) || !inputcheck($_POST['track_name'],'string',64, 5, NULL, NULL) ){ 
		$submit_message = 'Invalid Track Name';
	}elseif( !isset($_POST['country']) || !inputcheck($_POST['country'],'string',64, 3, NULL, NULL) ){ 
		$submit_message = 'Invalid Country Name';
	}elseif( !isset($_POST['length']) || !inputcheck($_POST['length'], 'numeric', 11, 0, 0, 1000000000)){ 
		$submit_message = 'Invalid Length';
	}elseif( !isset($_POST['type']) || !inputcheck($_POST['type'], 'numeric', 2, 1, 1, 2)){ 
		$submit_message = 'Invalid Track type';
	}elseif( !isset($_POST['start_heading']) || !inputcheck($_POST['start_heading'], 'numeric', 2, 1, 1, 2)){ 
		$submit_message = 'Invalid Track Direction';
	}elseif( !isset($_POST['start_latitude']) || !inputcheck($_POST['start_latitude'], 'numeric', 11, 0, -90, 90)){ 
		$submit_message = 'Invalid Start Latitude';
	}elseif( !isset($_POST['start_longitude']) || !inputcheck($_POST['start_longitude'], 'numeric', 11, 0, -180, 180)){ 
		$submit_message = 'Invalid Start Longitude';
	}elseif($_POST['type'] == '2'){
		if( !isset($_POST['end_latitude']) || !inputcheck($_POST['end_latitude'], 'numeric', 11, 0, -90, 90)){ 
			$submit_message = 'Invalid Finish Latitude';
		}elseif( !isset($_POST['end_longitude']) || !inputcheck($_POST['end_longitude'], 'numeric', 11, 0, -180, 180)){ 
			$submit_message = 'Invalid Finish Longitude';
		}else{
			$valid = TRUE;
			$end_latitude = $_POST['end_latitude'];
			$end_longitude = $_POST['end_longitude'];
		}
	}else{
		$valid = TRUE;
		$end_latitude = 0;
		$end_longitude = 0;
	}

	if($valid)
	{
		try 
		{
			$db = new PDO("mysql:dbname=$dbname;host=$dbhost","$dbuser","$dbpass");
		}
		catch(PDOException $e)
	    {
			echo $e->getmessage();
	    }
		
		$create_uid = 1;
		$active = TRUE;


		$query = $db->prepare("INSERT INTO tracks (track_name, length, country, type, start_latitude, start_longitude, end_latitude, end_longitude, start_heading, active, created_uid) 
											VALUES (:track_name, :length,:country, :type, :start_latitude, :start_longitude, :end_latitude, :end_longitude, :start_heading, :active, :created_uid)");
		
		$query->bindparam(':track_name', $_POST['track_name']);
		$query->bindparam(':length', $_POST['length']);
		$query->bindparam(':country', $_POST['country']);
		$query->bindparam(':type', $_POST['type']);
		$query->bindparam(':start_latitude', $_POST['start_latitude']);
		$query->bindparam(':start_longitude', $_POST['start_longitude']);
		$query->bindparam(':end_latitude', $end_latitude);
		$query->bindparam(':end_longitude', $end_longitude);
		$query->bindparam(':start_heading', $_POST['start_heading']);
		$query->bindparam(':active', $active);
		$query->bindparam(':created_uid', $create_uid);
		
		try
		{		
			$query->execute();
			$submit_message = "Track Added Successfully.";
		}
		catch (PDOException $e2)
		{	
			$submit_message = $e2->getmessage();
		}
		$success = TRUE;	
		$db = NULL;
	}
}
?>

<div align="center">
	Create new Track
	<form method="post" action="<?php $_PHP_SELF ?>">
	
	<table id="form_table">

	<tr>
		<td>Track Name</td>
		<td>
			<input name="track_name" type="text" id="track_name" size="50" maxlength="64" required>
		</td>
	</tr>
	
	<tr>
		<td>Length</td>
		<td>
			<input name="length" type="number" id="length" size=50 maxlength="11" min="0" max="1000000000">
			meters
		</td>
	</tr>
	<tr>
		<td>Track Type</td>
		<td>Circuit
			<input name="type" type="radio" id="type" value="1" checked>
			Sprint
			<input name="type" type="radio" id="type" value="2">
		</td>
	</tr>
<?php
	$latitude_atributes = 'type="number" size="30" maxlength="11" min="-90" max="90" step="any" placeholder="41.9714451"';
	$longitude_atributes = 'type="number" size="30" maxlength=11 min="-180" max="180" step="any" placeholder="-20.6870728"';
	$start_end = 'start';
	$coord_type = 'Start';

	for($i = 0; $i < 2; $i++)
	{
?>
		<tr>
			<td>
				<?=$coord_type?>
			</td>
			<td>
				Latitude:
				<input name="<?=$start_end.'_latitude'?>" id="<?=$start_end.'_latitude'?>" <?=$latitude_atributes?> required>
				<br>
				Longitude:
				<input name="<?=$start_end.'_longitude'?>" id="<?=$start_end.'_longitude'?>" <?=$longitude_atributes?> required>
			</td>
		</tr>
<?php	
	$start_end = 'end';
	$coord_type = 'Finish';

	}
?>

	<tr>
		<td>Running Direction</td>
		<td>Clockwise
					<input name="start_heading" type="radio" id="start_heading" value="1" checked>
			Anti-Clockwise
					<input name="start_heading" type="radio" id="start_heading" value="2">
		</td>

	</tr>
	<tr>
		<td> </td>
		<td>
			<input type="text" name='country' id="country" list="someCountries" placeholder="Country" required>
			<datalist id="someCountries">
				<?php require ("countrydropdown.html"); ?>
			</datalist>
		</td>
	</tr>
	
	
	<tr>
		<td id="form_error">
			<?php echo isset($submit_message) ? $submit_message : ''; ?>
		</td>
		<td>
			<input name="create" type="submit" id="create" value="Create">
		</td>

	</tr>

	</table>
	
	</form>
</div>

</html>