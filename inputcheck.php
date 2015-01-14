<?php

//This Function validates user input

function inputcheck($post, $length, $minlength, $min = NULL, $max = NULL)
{
	if(!isset($_POST[$post])){
		echo 'shit';
		return FALSE;
	}
	else
	{
		$string = $_POST[$post];
	}
	if($min !== NULL) 
	{
		if($string >= $min && $string <= $max)
			return TRUE;
		else
			return FALSE;
	}
	elseif(empty($string) || strlen($string) > $length || strlen($string) < $minlength)
	{
		return FALSE;
	}
	else
	{
		return TRUE;
	}
}

function checkEmail($email)
{
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,8}$/iU', $email) ? TRUE : FALSE;
}

// $str = "-91";
// $result = inputcheck($str, 11, 0, -90, 90);
// echo $result;
?>