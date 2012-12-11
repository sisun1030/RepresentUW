<?php
include "header.php";

if(isset($_GET['place_id'])) {
	$place_id = htmlspecialchars($_GET['place_id']); 
} else if(isset($_POST['place_id'])) {
	$place_id = htmlspecialchars($_POST['place_id']);
} else {
	exit; 
}


$data = array(); 
$data[founder_name] = array();
$data[founder_program] = array();
$c = 0;

$places = mysql_query("SELECT * FROM places WHERE id='$place_id' AND approved = '1' LIMIT 1");
if(mysql_num_rows($places) != 1) { exit; }
while($place = mysql_fetch_assoc($places)) {
	$data[id] = $place[id];
	$data[title] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[title])));
	$data[uri] = addslashes(htmlspecialchars($place[uri]));
	$data[desc] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[description])));
	$data[user_name] = $place[user_name];
	$data[user_email] = $place[user_email];
	$data[address] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[address]))); 
	$data[video] = $place[video];
	
	$founders = mysql_query("SELECT * FROM founder WHERE company = $data[id]");
	while($founder = mysql_fetch_assoc($founders)) {
		$data[founder_name][$c] = $founder[founder_name];
		$data[founder_program][$c] = $founder[founder_program];
		$c++;
	}
}


$json = array2json($data);
echo "{ \"contents\":"; 
echo $json; echo "}";


function array2json($arr) { 
    if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
    $parts = array(); 
    $is_list = false; 

    //Find out if the given array is a numerical array 
    $keys = array_keys($arr); 
    $max_length = count($arr)-1; 
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true; 
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position 
            if($i != $keys[$i]) { //A key fails at position check. 
                $is_list = false; //It is an associative array. 
                break; 
            } 
        } 
    } 

    foreach($arr as $key=>$value) { 
        if(is_array($value)) { //Custom handling for arrays 
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */ 
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */ 
        } else { 
            $str = ''; 
            if(!$is_list) $str = '"' . $key . '":'; 

            //Custom handling for multiple data types 
            if(is_numeric($value)) $str .= $value; //Numbers 
            elseif($value === false) $str .= 'false'; //The booleans 
            elseif($value === true) $str .= 'true'; 
            else $str .= '"' . addslashes($value) . '"'; //All other things 
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?) 

            $parts[] = $str; 
        } 
    } 
    $json = implode(',',$parts); 
     
    if($is_list) return '[' . $json . ']';//Return numerical JSON 
    return '{' . $json . '}';//Return associative JSON 
} 

?>