<?php
include "header.php";

// This is used to submit new markers for review.
// Markers won't appear on the map until they are approved.

function parseInput($value) {
  $value = htmlspecialchars($value);
  $value = str_replace("\r", "", $value);
  $value = str_replace("\n", "", $value);
  return $value;
}
$counter = 0;
$founder_info = array(array());

//Check if this is a new startup being added or existing one being edited:
$edit = $_POST['edit'];

$user_name = parseInput($_POST['user_name']);
$user_email = parseInput($_POST['user_email']);
$title = parseInput($_POST['title']); if ($edit == 1){ $title = $title . " edit requested to kevin and sisun"; }
$address = parseInput($_POST['address']);


if ($_POST['founder_name'] || $_POST['founder_program']){
	$founder_info[$counter]['name'] = parseInput($_POST['founder_name']);
	$founder_info[$counter]['program'] = parseInput($_POST['founder_program']);
	$counter++;
}
if ($_POST['founder_name2'] || $_POST['founder_program2']){
	$founder_info[$counter]['name'] = parseInput($_POST['founder_name2']);
	$founder_info[$counter]['program'] = parseInput($_POST['founder_program2']);
	$counter++;
}
if ($_POST['founder_name3'] || $_POST['founder_program3']){
	$founder_info[$counter]['name'] = parseInput($_POST['founder_name3']);
	$founder_info[$counter]['program'] = parseInput($_POST['founder_program3']);
	$counter++;
}
if ($_POST['founder_name4'] || $_POST['founder_program4']){
	$founder_info[$counter]['name'] = parseInput($_POST['founder_name4']);
	$founder_info[$counter]['program'] = parseInput($_POST['founder_program4']);
	$counter++;
}

$uri = parseInput($_POST['uri']);
$description = parseInput($_POST['description']);
$video = parseInput($_POST['video']);


// validate fields
$exist = mysql_query("SELECT * FROM places WHERE title = '$title' LIMIT 1");
if(mysql_num_rows($exist) == 1) { 
  $existing = mysql_fetch_assoc($exist);
  if ($existing[id] == 0){
    echo "This company has already been added and is under review.";
  }
  else{
    echo "This company has already been added. Check our list on the right.";
  }
  
  exit;
}

else if (empty($title) || empty($address) || empty($uri) || empty($description) || empty($user_email) || empty($user_name)) {
  echo "Please fill in all required fields.";  
  exit;
  
} else {

  //separate logic for editing startup information:


  // insert into db, wait for approval
  $insert_company = mysql_query("INSERT INTO places (approved, title, address, uri, description, user_name, user_email ,video, lat, lng, type) 
  VALUES (0, '$title', '$address', '$uri', '$description', '$user_name', '$user_email', '$video', 0, 0, 'startup')") or die(mysql_error());
    
  foreach ($founder_info as $m){
	$insert_founder = mysql_query("INSERT INTO founder VALUES ('$m[name]', '$m[program]', (SELECT id FROM places WHERE approved = 0 AND title='$title'))") or die(mysql_error());
  }

  // geocode new submission
  $hide_geocode_output = true;
  include "geocode.php";
  geocode("places");
  
  // if we got here, let the user know everything's OK
  echo "success";
  exit;
  
}


?>
