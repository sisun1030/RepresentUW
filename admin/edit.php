<?php
include "header.php";


if(isset($_GET['place_id'])) {
  $place_id = htmlspecialchars($_GET['place_id']); 
} else if(isset($_POST['place_id'])) {
  $place_id = htmlspecialchars($_POST['place_id']);
} else {
  exit; 
}


// get place info
$place_query = mysql_query("SELECT * FROM places WHERE id='$place_id' LIMIT 1");
if(mysql_num_rows($place_query) != 1) { exit; }
$place = mysql_fetch_assoc($place_query);

//get founder info
$founder = array(array());
$founder[0][name] = ""; $founder[0][program] = "";
$founder[1][name] = ""; $founder[1][program] = "";
$founder[2][name] = ""; $founder[2][program] = "";
$founder[3][name] = ""; $founder[3][program] = "";
$counter = 0;
$founder_query = mysql_query("SELECT * FROM founder WHERE company='$place_id'");
while($info = mysql_fetch_assoc($founder_query)) {
  $founder[$counter][name]  = $info[founder_name];
  $founder[$counter][program] = $info[founder_program];
  $counter++;
}

// do place and founder edit if requested
if($task == "doedit") {
  $title = $_POST['title'];
  $type = $_POST['type'];
  $address = $_POST['address'];
  $uri = $_POST['uri'];
  $description = $_POST['description'];
  $owner_name = $_POST['user_name'];
  $owner_email = $_POST['user_email'];
  $type = $_POST['type'];
  $video = $_POST['video'];
  
  mysql_query("UPDATE places SET title='$title', video='$video', type='$type', address='$address', uri='$uri', description='$description', user_name='$owner_name', user_email='$owner_email' WHERE id='$place_id' LIMIT 1") or die(mysql_error());
  
  mysql_query("DELETE FROM founder WHERE company='$place_id'");  
  if (($_POST['founder_name'] <> "") || ($_POST['founder_program'] <> "")){
    mysql_query("INSERT INTO founder VALUES ('$_POST[founder_name]', '$_POST[founder_program]', '$place_id')") or die(mysql_error());
  }
  if (($_POST['founder_name2'] <> "") || ($_POST['founder_program2'] <> "")){
	mysql_query("INSERT INTO founder VALUES ('$_POST[founder_name2]', '$_POST[founder_program2]', '$place_id')") or die(mysql_error());
  }
  if (($_POST['founder_name3'] <> "") || ($_POST['founder_program3'] <> "")){
	mysql_query("INSERT INTO founder VALUES ('$_POST[founder_name3]', '$_POST[founder_program3]', '$place_id')") or die(mysql_error());
  }
  if (($_POST['founder_name4'] <> "") || ($_POST['founder_program4'] <> "")){
	mysql_query("INSERT INTO founder VALUES ('$_POST[founder_name4]', '$_POST[founder_program4]', '$place_id')") or die(mysql_error());
  }
	
  
  
  // geocode
  $hide_geocode_output = true;
  include "../geocode.php";
  
  header("Location: index.php?view=$view&search=$search&p=$p");
  exit;
}

?>



<? echo $admin_head; ?>

<form id="admin" class="form-horizontal" action="edit.php" method="post">
  <h1>
    Edit Place
  </h1>
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="">Title</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="title" value="<?=$place[title]?>" id="">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="">Type</label>
      <div class="controls">
        <select class="input input-xlarge" name="type">
          <option<? if($place[type] == "startup") {?> selected="selected"<? } ?>>startup</option>
          <option<? if($place[type] == "accelerator") {?> selected="selected"<? } ?>>accelerator</option>
          <option<? if($place[type] == "incubator") {?> selected="selected"<? } ?>>incubator</option>
          <option<? if($place[type] == "coworking") {?> selected="selected"<? } ?>>coworking</option>
          <option<? if($place[type] == "investor") {?> selected="selected"<? } ?>>investor</option>
          <option<? if($place[type] == "service") {?> selected="selected"<? } ?>>service</option>
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="">Address</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="address" value="<?=$place[address]?>" id="">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="">URL</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="uri" value="<?=$place[uri]?>" id="">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="">Description</label>
      <div class="controls">
        <textarea class="input input-xlarge" name="description"><?=$place[description]?></textarea>
      </div>
    </div>
	<div class="control-group">
      <label class="control-label" for="">Intro Video</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="video" value="<?=$place[video]?>" id="">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="">Submitter Name</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="user_name" value="<?=$place[user_name]?>" id="">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="">Submitter Email</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="user_email" value="<?=$place[user_email]?>" id="">
      </div>
    </div>
	
	<h1>
      Edit Founder Info
    </h1>
	<div class="control-group">
      <label class="control-label" for="">Founder Name 1:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_name" value="<?=$founder[0][name]?>" id="">
      </div>
    </div>
	<div class="control-group" style="margin-top:1px;">
      <label class="control-label" for="">Founder Program 1:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_program" value="<?=$founder[0][program]?>" id="">
      </div>
    </div>
	
	<div class="control-group">
      <label class="control-label" for="">Founder Name 2:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_name2" value="<?=$founder[1][name]?>" id="">
      </div>
    </div>
	<div class="control-group" style="margin-top:1px;">
      <label class="control-label" for="">Founder Program 2:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_program2" value="<?=$founder[1][program]?>" id="">
      </div>
    </div>
	
	<div class="control-group">
      <label class="control-label" for="">Founder Name 3:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_name3" value="<?=$founder[2][name]?>" id="">
      </div>
    </div>
	<div class="control-group" style="margin-top:1px;">
      <label class="control-label" for="">Founder Program 3:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_program3" value="<?=$founder[2][program]?>" id="">
      </div>
    </div>
	
	<div class="control-group">
      <label class="control-label" for="">Founder Name 4:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_name4" value="<?=$founder[3][name]?>" id="">
      </div>
    </div>
	<div class="control-group" style="margin-top:1px;">
      <label class="control-label" for="">Founder Program 4:</label>
      <div class="controls">
        <input type="text" class="input input-xlarge" name="founder_program4" value="<?=$founder[3][program]?>" id="">
      </div>
    </div>
	
	
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save Changes</button>
      <input type="hidden" name="task" value="doedit" />
      <input type="hidden" name="place_id" value="<?=$place[id]?>" />
      <input type="hidden" name="view" value="<?=$view?>" />
      <input type="hidden" name="search" value="<?=$search?>" />
      <input type="hidden" name="p" value="<?=$p?>" />
      <a href="index.php" class="btn" style="float: right;">Cancel</a>
    </div>
  </fieldset>  
</form>



<? echo $admin_foot; ?>
