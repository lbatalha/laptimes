<?php

//This Function validates user input for type and length

function inputcheck($post,$type,$length,$minlength, $min, $max)
{

	$type = 'is_'.$type;

	if(!isset($_POST[$post]))
		return FALSE;
	else
		$string = $_POST[$post]);
	if ($type == 'is_numeric') 
	{
		if($string >= $min && $string <= $max)
			return TRUE;
		else
			return FALSE;
	}
	elseif(empty($string) || strlen($string) > $length || strlen($string) < $minlength)
		return FALSE;
	else
		return TRUE;
}

  // check number is >= than 0 and $length digits long
  // returns TRUE on success
// function checknumber($num, $min, $max)
// {
// 	if($num >= $min && $num <= $max)
// 	{
// 		return TRUE;
// 	}
// 	else{return FALSE;}
// }

function checkEmail($email)
{
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,8}$/iU', $email) ? TRUE : FALSE;
}

// $str = "-91";
// $result = inputcheck($str, 'numeric', 11, 0, -90, 90);
// echo $result;
?>