<?php
include "header.php";

?>

<!DOCTYPE html>
<html>
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-34242147-1']);
	  _gaq.push(['_setDomainName', 'representuw.com']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	

  <head>
    <!--
	UI design of RepresentUW was inspired by the following source:
	
    This site was based on the Represent.LA project by:
    - Alex Benzer (@abenzer)
    - Tara Tiger Brown (@tara)
    - Sean Bonner (@seanbonner)
    
    Create a map for your startup community!
    https://github.com/abenzer/represent-map
    -->
    <title>RepresentUW - Map of UW student founded startups</title>
    <meta name="viewport" content="width=1024; height=1024;">
    <meta charset="UTF-8">
	
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700|Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="./bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="./bootstrap/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="map.css?nocache=289671982568" type="text/css" />
	
    <script src="./scripts/jquery-1.7.1.js" type="text/javascript" charset="utf-8"></script>
    <script src="./bootstrap/js/bootstrap.js" type="text/javascript" charset="utf-8"></script>
    <script src="./bootstrap/js/bootstrap-typeahead.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="./scripts/label.js"></script>
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>
	
    <script type="text/javascript">
      var map;
      var infowindow = null;
      var gmarkers = [];
      var markerTitles =[];
      var highestZIndex = 0;  
      var agent = "default";
      var zoomControl = true;


      // detect browser agent
      // $(document).ready(function(){
        // if(navigator.userAgent.toLowerCase().indexOf("iphone") > -1 || navigator.userAgent.toLowerCase().indexOf("ipod") > -1) {
          // agent = "iphone";
          // zoomControl = false;
        // }
        // if(navigator.userAgent.toLowerCase().indexOf("ipad") > -1) {
          // agent = "ipad";
          // zoomControl = false;
        // }
      // }); 
	  
	  function addfounderinfo(counter, page) {

		if(counter>4){
					alert("Only 4 founder info allowed.");
					return false;
			}   
		
			<!-- Remove button -->
			// var exists = false;
			// if($('removeButton').length > 0) {
				// exists = true;
			// }
			
			// if(counter>1 && exists == false){
				// document.getElementById('founderButton').innerHTML += '<input type="button" value="Remove founder" id="removeButton">';
			// }

		var newTextBoxLabel = $(document.createElement('label'))
			 .attr({
			  class: 'control-label',
			  for: page +'_founder' + counter,
			  style: 'margin-left:-30%; margin-top:5%;'
		});

		//newTextBoxLabel.html('Founder Name'); 
		newTextBoxLabel.appendTo("#" + page + "founder_program");
		var newTextBoxDiv = $(document.createElement('div'))
			.attr({
			  class: 'controls',
			  style: 'margin:1%;'
			});	
		var newTextBoxDiv2 = $(document.createElement('div'))
			.attr({
			  class: 'controls',
			  style: 'margin:1%;'
			});	

		newTextBoxDiv.html(
		  '<label class="control-label" for="' + page + '_founder">Founder Name</label><div class="controls"><input type="text" class="input-xlarge" name="founder' + counter + 
	      '" id="' + page + '_founder' + counter + '"></div>');
		newTextBoxDiv2.html(
		  '<label class="control-label" for="' + page + '_program">UW Program</label><div class="controls"><input type="text" class="input-xlarge" name="program' + counter + 
	      '" id="' + page + '_program' + counter + '"></div>');

		newTextBoxDiv.appendTo("#" + page + "founder_program");
		newTextBoxDiv2.appendTo("#" + page + "founder_program");	 

	  }
      

      // resize marker list onload/resize
      $(document).ready(function(){
		var counteradd = 2;
		var counteredit = 2;
		$("#addButton").click(function () {

			addfounderinfo(counteradd, "add");
			counteradd++;
		 });
		 
		 for ( var i = 2; i < 5; i++) {
			addfounderinfo(i, "edit");
		 }
		 // $("#editButton").click(function () {
			// addfounderinfo(counteredit, "edit");
			// counteredit++;
		 // });
	 
		 // $("#removeButton").click(function () {
			 // alert('blah: ' + counter);
			// if(counter==1){
				  // alert("No more textbox to remove");
				  // return false;
			   // }   
			
			// counter--;
			// alert(counter);
				// $("#add_founder" + counter).remove();
		 // });
		
        // newHeight = $('html').height() - $('#menu > .wrapper').height();
        // $('#list').css('height', newHeight + "px"); 	
		
      }); <!-- End of Ready -->
	  
	  
      $(window).resize(function() {
        resizeList();
      });
      
      // resize marker list to fit window
      function resizeList() {
        newHeight = $('html').height() - $('#menu > .wrapper').height();
        $('#list').css('height', newHeight + "px"); 
      }


      // initialize map
      function initialize() {
        // set map styles
         var mapStyles = [
          {
            featureType: "administrative.land_parcel",
            stylers: [
              { visibility: "off" }
            ]
          },{
            featureType: "water",
            stylers: [
              { visibility: "on" },
              { saturation: 31 },
              { lightness: 39 }
            ]
          },{
            featureType: "road.highway",
            stylers: [
              { visibility: "simplified" },
              { lightness: 18 }
            ]
          },{
			featureType: "poi",
			elementType: "labels",
			stylers: [
					{visibility: "off"}
			]
		  }
        ];

        // set map options
        var myOptions = {
          zoom: 9,
		  maxZoom: 17,
          //minZoom: 10,
          center: new google.maps.LatLng(43.625241,-79.777908),
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          panControl: false,
		  featureType: "poi.business",
		  elementType: "labels",
          streetViewControl: true,
          mapTypeControl: false,
          zoomControl: zoomControl,
          styles: mapStyles,
          zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.TOP_LEFT
          }
        };
        map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        zoomLevel = map.getZoom();

        // prepare infowindow
        infowindow = new google.maps.InfoWindow({
          content: "holding..."
        });

        // only show marker labels if zoomed in
        google.maps.event.addListener(map, 'zoom_changed', function() {
          zoomLevel = map.getZoom();
          if(zoomLevel <= 15) {
            $(".marker_label").css("display", "none");
          } else {
            $(".marker_label").css("display", "inline");
          }
        });

        // markers array: name, type (icon), lat, long, description, uri, address
        markers = new Array();
        <?php
          $types = Array(
              Array('startup', 'Startups'),
              /*Array('accelerator','Accelerators'),
              Array('incubator', 'Incubators'), 
              Array('coworking', 'Coworking'), 
              Array('investor', 'Investors'),
              Array('service', 'Consulting'),
              Array('event', 'Events'),*/
          );
			  
          $marker_id = 0;
		  $m = array(array());
          foreach($types as $type) {
            $places = mysql_query("SELECT * FROM places WHERE approved='1' AND type='startup' ORDER BY title ASC");
            while($place = mysql_fetch_assoc($places)) {					
              /*$place[title] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[title])));
              $place[description] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[description])));
              $place[uri] = addslashes(htmlspecialchars($place[uri]));
			  $place[video] = addslashes(htmlspecialchars($place[video]));
              $place[address] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[address])));
			  //$place[industry] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[industry])));
			  //$place[city] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[city])));
			  //$place[founder_name] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[address])));
			  //$place[founder_program] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[address
			  */

			  $m[$marker_id][title] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[title])));
			  $m[$marker_id][description] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[description])));
			  $m[$marker_id][uri] = addslashes(htmlspecialchars($place[uri]));
			  $m[$marker_id][address] = htmlspecialchars_decode(addslashes(htmlspecialchars($place[address]))); 
			  $m[$marker_id][lat] = $place[lat];
			  $m[$marker_id][lng] = $place[lng];
			  $m[$marker_id][video] = $place[video];
			  $m[$marker_id][type] = $place[type];
			  $m[$marker_id][id] = $place[id];
			  
			  $founders = mysql_query("SELECT * FROM founder WHERE company = $place[id]");
			  if(mysql_num_rows($founders) == 0){
				$m[$marker_id][founder_info] = "No founder information.";
			  }
			  else{
			    $m[$marker_id][founder_info] = "";
				while($founder = mysql_fetch_assoc($founders)) {
				  $m[$marker_id][founder_info] .= "<b>" . $founder[founder_name] . "</b> | " .  $founder[founder_program] . "<br/>";
				}
			  }
			  
              echo "
                markers.push(['".$m[$marker_id][title]."', '".$m[$marker_id][type]."', '".$m[$marker_id][lat]."', '".$m[$marker_id][lng]."', '".$m[$marker_id][description]."', '".$m[$marker_id][uri]."', '".$m[$marker_id][address]."', '".$m[$marker_id][video]."', '".$m[$marker_id][founder_info]."', '".$m[$marker_id][id]."']); 
                markerTitles[".$marker_id."] = '".$m[$marker_id][title]."';
              "; 
              $count[$place[type]]++;
              $marker_id++;
            }
			
          }
		  
		  /*
          if($show_events = true) {
            $place[type] = "event";
            $events = mysql_query("SELECT * FROM events WHERE start_date < ".(time()+4838400)." ORDER BY id DESC");
            $events_total = mysql_num_rows($events);
            while($event = mysql_fetch_assoc($events)) {
              $event[title] = htmlspecialchars_decode(addslashes(htmlspecialchars($event[title])));
              $event[description] = htmlspecialchars_decode(addslashes(htmlspecialchars($event[description])));
              $event[uri] = addslashes(htmlspecialchars($event[uri]));
              $event[address] = htmlspecialchars_decode(addslashes(htmlspecialchars($event[address])));
              $event[start_date] = date("D, M j @ g:ia", $event[start_date]);
              echo "
                markers.push(['".$event[title]."', 'event', '".$event[lat]."', '".$event[lng]."', '".$event[start_date]."', '".$event[uri]."', '".$event[address]."']); 
                markerTitles[".$marker_id."] = '".$event[title]."';
              "; 
              $count[$place[type]]++;
              $marker_id++;
            }
          }
		  */

        ?>
		var markerTitle = new Array();
        // add markers
        jQuery.each(markers, function(i, val) {
          infowindow = new google.maps.InfoWindow({
            content: ""
          });
		  markerTitle[i] = val[0];

          // offset latlong ever so slightly to prevent marker overlap
          // rand_x = Math.random();
          // rand_y = Math.random();
          // val[2] = parseFloat(val[2]) + parseFloat(parseFloat(rand_x) / 6000);
          // val[3] = parseFloat(val[3]) + parseFloat(parseFloat(rand_y) / 6000);

          // show smaller marker icons on mobile
          // if(agent == "iphone") {
            // var iconSize = new google.maps.Size(16,19);
          // } else {
            // iconSize = null;
          // }

          // build this marker
          var markerImage = new google.maps.MarkerImage("./images/icons/"+val[1]+".png", null, null, null, null);
          var marker = new google.maps.Marker({
            position: new google.maps.LatLng(val[2],val[3] ), //val[2]=lat, val[3]=long
            map: map,
            title: '',
            clickable: true,
            infoWindowHtml: '',
            zIndex: 10 + i,
            icon: markerImage,
			id: val[9],
			list: i
          });
          marker.type = val[1];
          gmarkers.push(marker);

          // add marker hover events (if not viewing on mobile)
          if(agent == "default") {
            google.maps.event.addListener(marker, "mouseover", function() {
              this.old_ZIndex = this.getZIndex(); 
              this.setZIndex(9999); 
              $("#marker"+i).css("display", "inline");
              $("#marker"+i).css("z-index", "99999");
            });
            google.maps.event.addListener(marker, "mouseout", function() { 
              if (this.old_ZIndex && zoomLevel <= 15) {
                this.setZIndex(this.old_ZIndex); 
                $("#marker"+i).css("display", "none");
              }
            }); 
          }

          // format marker URI for display and linking
          var markerURI = val[5];
          if(markerURI.substr(0,7) != "http://") {
            markerURI = "http://" + markerURI; 
          }
          var markerURI_short = markerURI.replace("http://", "");
          var markerURI_short = markerURI_short.replace("www.", "");
		 

          // add marker click effects (open infowindow)
		  if (val[7] == ""){
			val[7] = markerURI;
		  }
			
          google.maps.event.addListener(marker, 'click', function () {
			infowindowlist.close();
			infowindow.setContent(
              "<div class='marker_title'>"+val[0]+"</div>"
              + "<div class='marker_uri'><a target='_blank' href='"+markerURI+"'>"+markerURI_short+"</a><span class='edit'><a href='#modal_edit' data-toggle='modal' id='crappy'>Edit</a></span></div>"
              + "<div class='marker_desc'>"+val[4]+"</div>"
              + "<div class='marker_address'>"+val[6]+"</div>"
			  + "<div class='marker_founders'>"+val[8]+"</div>"
			  + "<div class='marker_video'><iframe src='"+val[7]+"' frameborder='0' allowfullscreen></iframe></div>"
            );
            infowindow.open(map, this);
			
			editCompany(val[9]);
          });
		
			function editCompany(id) {
				urlEdit = "http://representuw.com/backup/edit_startup.php";
				$.ajax({
				  url: urlEdit,
				  dataType: "json",
				  type: "POST",
				  data: { place_id: id }
				}).done(function(json) { 
				console.log(json);
				  //document.getElementById('edit_user_name').value = json.contents.user_name;
				  //document.getElementById('edit_user_email').value = json.contents.user_email;
				  document.getElementById('edit_title').value = json.contents.title;
				  document.getElementById('edit_address').value = json.contents.address;
				  var foundersNum = json.contents.founder_name.length;
				  var counteredit = foundersNum;
				
				  for (var j = 1; j < 5; j++) {
					document.getElementById('edit_founder'+j).value = "";
					document.getElementById('edit_program'+j).value = "";
				  }
				  for (var i = 1; i <= foundersNum; i++) {

					  // if (i > 1 && $('#edit_founder'+j).length == 0 && counteredit <5) {
						  // alert("Creating node "+i);
						  // addfounderinfo(i, "edit");
					  // }
					  console.log(i);
					  console.log(json.contents.founder_name[i-1]);
					  document.getElementById('edit_founder'+i).value = json.contents.founder_name[i-1];
					  document.getElementById('edit_program'+i).value = json.contents.founder_program[i-1];
				  }
				  document.getElementById('edit_uri').value = json.contents.uri;
				  document.getElementById('edit_description').value = json.contents.desc;
				  document.getElementById('edit_video').value = json.contents.video;
				  
				}).fail(function() { 	  
				});
			}

          // add marker label
          var latLng = new google.maps.LatLng(val[2], val[3]);
          var label = new Label({
            map: map,
            id: i
          });
          label.bindTo('position', marker);
          label.set("text", val[0]);
          label.bindTo('visible', marker);
          label.bindTo('clickable', marker);
          label.bindTo('zIndex', marker);
        });
		var markerCluster = new MarkerClusterer(map, gmarkers);
		var infowindowlist = new google.maps.InfoWindow({
					content: ""
		});
		google.maps.event.addListener(markerCluster, "clusterclick", function(c) {
			var currentZoom = map.getZoom();
			infowindowlist.close();
			if(currentZoom >= 17) {
				var myLatlng = new google.maps.LatLng(c.getCenter().lat(), c.getCenter().lng());
				var n = "";
				var markerId = new Array();
				
				infowindowlist.close();
				
				n += '<div class="clusterList"><ul>';

				for (var i = 0; i < c.markerClusterer_.clusters_[0].markers_.length; i++) {
					markerId = (c.markerClusterer_.clusters_[0].markers_[i].list);

					n += '<li class="clusterListItem"><a href="#" onclick="goToMarker(\'' + markerId + '\')">' + markerTitle[markerId] +'</a></li>'
				}
				
				n += '</ul></div>';

				infowindowlist.setContent(n);
				infowindowlist.setPosition(myLatlng);
				infowindowlist.open(map);
			}
		});
		
		MarkerClusterer.prototype.onClick = function() { 
			return true; 
		};

        // zoom to marker if selected in search typeahead list
        $('#search').typeahead({
          source: markerTitles, 
          onselect: function(obj) {
            marker_id = jQuery.inArray(obj, markerTitles);
            if(marker_id) {
              map.panTo(gmarkers[marker_id].getPosition());
              map.setZoom(15);
              google.maps.event.trigger(gmarkers[marker_id], 'click');
            }
            $("#search").val("");
          }
        });
      } 


      // zoom to specific marker
      function goToMarker(marker_id) {
        if(marker_id) {
          map.panTo(gmarkers[marker_id].getPosition());
          if (map.getZoom() >= 15) {
			map.setZoom(map.getZoom());
		  } else {
			map.setZoom(15);
		  }
          google.maps.event.trigger(gmarkers[marker_id], 'click');
        }
      }

      // toggle (hide/show) markers of a given type (on the map)
      function toggle(type) {
        if($('#filter_'+type).is('.inactive')) {
          show(type); 
        } else {
          hide(type); 
        }
      }

      // hide all markers of a given type
      function hide(type) {
        for (var i=0; i<gmarkers.length; i++) {
          if (gmarkers[i].type == type) {
            gmarkers[i].setVisible(false);
          }
        }
        $("#filter_"+type).addClass("inactive");
      }

      // show all markers of a given type
      function show(type) {
        for (var i=0; i<gmarkers.length; i++) {
          if (gmarkers[i].type == type) {
            gmarkers[i].setVisible(true);
          }
        }
        $("#filter_"+type).removeClass("inactive");
      }
      
      // toggle (hide/show) marker list of a given type
      function toggleList(type) {
        $("#list .list-"+type).toggle();
      }


      // hover on list item
      function markerListMouseOver(marker_id) {
        $("#marker"+marker_id).css("display", "inline");
      }
      function markerListMouseOut(marker_id) {
        $("#marker"+marker_id).css("display", "none");
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    
    <? 
		echo $head_html; 
	?>
  </head>
  <body>
  
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=418852658152073";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	  
    
    <!-- google map    I added top menu bar. Add a logic to remove it for mobile browsing-->
    <div id="map_canvas"></div>
	
	<!-- For the header and footer, make sure to remove them for mobile browsing -->
	<!--<div class="header">
		<ul>
			<li style="padding-top:0%; float:left; padding-left:0%;"><a href="./"><img src="images/logo.png" height="42" width="42" alt="" /></a></li>
		</ul>-->
	
		<!--
		<div class="title" style="float:left; padding-left:25px;">
			RepresentUW  
			<a href="#modal_add" style="float:left; padding-left:25px;" class="btn btn-large btn-inverse" data-toggle="modal">Add Your Company</a> &nbsp;
			
		</div>-->
		
	<!--</div>-->
	
	
	
    <!-- main menu bar -->
    <div class="menu" id="menuRight" style="right:0;">
	
	<div class="toggle" id="toggleRight" style="right:250px;">
	<img src="images/icons/gray-right-double-arrow-md.png" width="20" height="20">
	</div>
	
      <div class="wrapper">
        <div class="logo">
          <a href="./">
            <img src="images/logo_transparent.png" alt="" />
          </a>
        </div>
        
		<!--
  	    <div class="buttons">
          <a href="#modal_info" class="btn btn-large btn-info" data-toggle="modal">More Info</a>
          <a href="#modal_add" class="btn btn-large btn-inverse" data-toggle="modal">Add Something!</a>
        </div>
        -->
<!--Share-->
		<div class="share">
			<!--Facebook and Twitter-->	
			<div class="fb-like" data-href="http://representuw.com" data-send="false" data-layout="button_count" data-width="500" data-show-faces="true"></div>
	
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://representuw.com" data-via="SisunLee">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){
			js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			
			<a href="https://twitter.com/RepresentUW" class="twitter-follow-button" data-show-count="false">Follow @RepresentUW</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		
<!--Buttons-->	
		<div id="buttonMenuInfo" style="margin-left:17%;margin-right;padding-bottom:3%;padding-top:3%;">
			<a href="#modal_add" class="btn" data-toggle="modal">+ Add</a>
			&nbsp
			<a href="#modal_info" class="btn" data-toggle="modal">More Info</a>
		</div>
		
		<div class="quickinfo">
			Created by <a href="http://sisunlee.com/"><b>Sisun Lee</a> / <a href="http://ca.linkedin.com/in/kevinzhang10">Kevin Zhang</a></b>
		</div>	
		<div class="blurb">
			<!-- per our license, you may not remove this line -->
			<?=$attribution?>
        </div>
      </div>
      <ul class="list" id="list">
		<li class="search">
          <input type="text" name="search" id="search" placeholder="  Type a company name..." data-provide="typeahead" autocomplete="off" />
        </li>
        <?php
          $types = Array(
              Array('startup', 'Startups')
              /*
			  Array('accelerator','Accelerators'),
              Array('incubator', 'Incubators'), 
              Array('coworking', 'Coworking'), 
              Array('investor', 'Investors'),
              Array('service', 'Consulting')
			  */
              );
          
		  /*
		  if($show_events == true) {
            $types[] = Array('event', 'Events'); 
          }
		  */
		  
          $marker_id = 0;
          foreach($types as $type) {
            //if($type[0] != "event") {
              $markers = mysql_query("SELECT * FROM places WHERE approved='1' AND type='$type[0]' ORDER BY title");
            //} else {
            //  $markers = mysql_query("SELECT * FROM events WHERE start_date < ".(time()+4838400)." ORDER BY id DESC");
            //}
            $markers_total = mysql_num_rows($markers);
            echo "
              <li class='category'>
                <div class='category_item'>
                  <div class='category_toggle' onClick=\"toggle('$type[0]')\" id='filter_$type[0]'></div>
                  <a href='#' onClick=\"toggleList('$type[0]');\" class='category_info'><img src='./images/icons/$type[0].png' alt='' />$type[1]<span class='total'> ($markers_total)</span></a>
                </div>
                <ul class='list-items list-$type[0]'>
            ";
            while($marker = mysql_fetch_assoc($markers)) {
              echo "
                  <li class='".$marker[type]."'>
                    <a href='#' onMouseOver=\"markerListMouseOver('".$marker_id."')\" onMouseOut=\"markerListMouseOut('".$marker_id."')\" onClick=\"goToMarker('".$marker_id."');\">".$marker[title]."</a>
                  </li>
              ";
              $marker_id++;
            }
            echo "
                </li>
              </ul>
            ";
          }
		  
		  
        ?>
      </ul>
    </div>
    
    <!-- main menu bar (mobile) -->
    <div class="menu_mobile">
      <div class="wrapper">
        <div class="buttons">
          <a href="#modal_add" class="btn btn-large btn-inverse" data-toggle="modal">Add</a>
          <a href="#modal_info" class="btn btn-large" data-toggle="modal">Info</a>
        </div>
        <div class="logo">
          <a href="http://representuw.com/">
            <img src="images/logo_transparent.png" alt="RepresentLA" />
          </a>
        </div>
      </div>
    </div>
	

    
	
    <!-- more info modal -->
    <div class="modal hide" id="modal_info">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>About This Map</h3>
      </div>
      <div class="modal-body">
        <p>
          Let's put UW startups on the map together!
		</p>
		<p>
		  We built this map to connect and promote startups found by
		  Univiersity of Waterloo students! If you don't see your company,
          please <a href="#modal_add" data-toggle="modal" data-dismiss="modal">submit it here</a>.
        </p>
		<p>
		  Sisun and Kevin are 3rd year Systems Design Engineering students from UW. This website started as a side project 
		  as a way for learning web development. We are still newbs - and every feature release would take some time.
		</p>
		<p>
		  Do you have feature suggestions we can incorporate into our site? Or other questions / feedback? Contact us <a href="mailto:sisun1030@gmail.com; kevin.zhang10@gmail.com">here</a>
		</p>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" style="float: right;">Close</a>
      </div>
    </div>
    
    
    <!-- add something modal -->
    <div class="modal hide" id="modal_add">
      <form action="add.php" id="modal_addform" class="form-horizontal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h3>Add a Company!</h3>
        </div>
        <div class="modal-body">
          <p style="text-align:center;">
            Want to add your company to this map? Submit it below and we'll review it ASAP.
          </p>
          <fieldset>
            <div class="control-group">
              <label class="control-label" for="add_owner_name">Your Name</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="user_name" id="add_user_name" maxlength="100">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="add_owner_email">Your Email</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="user_email" id="add_user_email" maxlength="100">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="add_title">Company Name</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="title" id="add_title" maxlength="100">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="add_address">Address</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="address" id="add_address">
                <p class="help-block">
                  Should be your <b>full street address (including city and zip)</b>.
                  If it works on Google Maps, it will work here.
                </p>
              </div>
            </div>
			<div class="control-group" id="addfounder" style="margin-bottom:0px;">
              <label class="control-label" for="add_founder1">Founder Name</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="founder1" id="add_founder1">
              </div>
			</div>
			<div class="control-group" id="addfounder_program" style="margin-bottom:0px;">
			  <label class="control-label" for="add_program1">UW Program</label>
              <div class="controls" style="margin-top:5px;">
                <input type="text" class="input-xlarge" name="program1" id="add_program1">
				<p class="help-block">
                  <font color="blue"><b>Optional.</b></font> Founder's name and academic program attended at UW.
                </p>
              </div>
            </div>
			<div id="founderButton" style="padding-left:32%; padding-bottom:5%;">
				<input type='button' value='Add more founders' id='addButton'>
			</div>
            <div class="control-group">
              <label class="control-label" for="add_uri">Website URL</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="add_uri" name="uri" placeholder="http://">
                <p class="help-block">
                  Should be your full URL with no trailing slash, e.g. "http://www.yoursite.com"
                </p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="add_description">Description</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="add_description" name="description" maxlength="200">
                <p class="help-block">
                  Brief, concise description. What's your product? What problem do you solve? Max 200 chars.
                </p>
              </div>
            </div>
			<div class="control-group">
              <label class="control-label" for="add_video">Intro Video</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="add_video" name="video">
                <p class="help-block">
                  <font color="blue"><b>Optional.</b></font> Link to an embedded video about your company. Embedded source link only: ex. http://www.youtube.com/embed/mo1yFIKxPfc 
                </p>
              </div>
            </div>
          </fieldset>
        </div>
		
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit for Review</button>
          <a href="#" class="btn" data-dismiss="modal" style="float: right;">Close</a>
		  </br>
		  </br>
		  <div id="result"></div>
        </div>
		
      </form>
    </div>
	
	    <!-- edit something modal -->
    <div class="modal hide" id="modal_edit">
      <form action="add.php" id="modal_editform" class="form-horizontal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h3>Edit a Company!</h3>
        </div>
        <div class="modal-body">
          
          <fieldset>
            <div class="control-group">
              <label class="control-label" for="edit_owner_name">Your Name</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="user_name" id="edit_user_name" maxlength="100">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="edit_owner_email">Your Email</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="user_email" id="edit_user_email" maxlength="100">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="edit_title">Company Name</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="title" id="edit_title" maxlength="100">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="edit_address">Address</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="address" id="edit_address">
                <p class="help-block">
                  Should be your <b>full street address (including city and zip)</b>.
                  If it works on Google Maps, it will work here.
                </p>
              </div>
            </div>
			<div class="control-group" id="editfounder" style="margin-bottom:0px;">
              <label class="control-label" for="edit_founder1">Founder Name</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="founder1" id="edit_founder1">
              </div>
			</div>
			<div class="control-group" id="editfounder_program" style="margin-bottom:0px;">
			  <label class="control-label" for="edit_program1">UW Program</label>
              <div class="controls" style="margin-top:5px;">
                <input type="text" class="input-xlarge" name="program1" id="edit_program1">
				<p class="help-block">
                  <font color="blue"><b>Optional.</b></font> Founder's name and academic program attended at UW.
                </p>
              </div>
            </div>
			<br>
            <div class="control-group">
              <label class="control-label" for="edit_uri">Website URL</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="edit_uri" name="uri" placeholder="http://">
                <p class="help-block">
                  Should be your full URL with no trailing slash, e.g. "http://www.yoursite.com"
                </p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="edit_description">Description</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="edit_description" name="description" maxlength="200">
                <p class="help-block">
                  Brief, concise description. What's your product? What problem do you solve? Max 200 chars.
                </p>
              </div>
            </div>
			<div class="control-group">
              <label class="control-label" for="edit_video">Intro Video</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="edit_video" name="video">
                <p class="help-block">
                  <font color="blue"><b>Optional.</b></font> Link to an embedded video about your company. Embedded source link only: ex. http://www.youtube.com/embed/mo1yFIKxPfc 
                </p>
              </div>
            </div>
          </fieldset>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit for Review</button>
          <a href="#" class="btn" data-dismiss="modal" style="float: right;">Close</a>
		  <br>
		  <br>
		  <div id="editresult"></div>
        </div>
      </form>
    </div>
	
	
    <script>
      // add modal form submit
      $("#modal_addform").submit(function(event) {
        event.preventDefault(); 
        // get values
        var $form = $( this ),
            user_name = $form.find( '#add_user_name' ).val(),
            user_email = $form.find( '#add_user_email' ).val(),
            title = $form.find( '#add_title' ).val(),
            address = $form.find( '#add_address' ).val(),
			founder_name1 = $form.find( '#add_founder1' ).val(),
			founder_program1 = $form.find( '#add_program1' ).val(),
			founder_name2 = $form.find( '#add_founder2' ).val(),
			founder_program2 = $form.find( '#add_program2' ).val(),
			founder_name3 = $form.find( '#add_founder3' ).val(),
			founder_program3 = $form.find( '#add_program3' ).val(),
			founder_name4 = $form.find( '#add_founder4' ).val(),
			founder_program4 = $form.find( '#add_program4' ).val(),
            uri = $form.find( '#add_uri' ).val(),
            description = $form.find( '#add_description' ).val(),
			video = $form.find('#add_video').val(),
            url = $form.attr( 'action' );
			edit = 0;

        // send data and get results
        $.post( url, { user_name: user_name, user_email: user_email, title: title, founder_name: founder_name1, founder_program: founder_program1, founder_name2: founder_name2, founder_program2: founder_program2, founder_name3: founder_name3, founder_program3: founder_program3, founder_name4: founder_name4, founder_program4: founder_program4, address: address, uri: uri, description: description, video: video, edit: edit },
          function( data ) {
            var content = $( data ).find( '#content' );
            
            // if submission was successful, show info alert
            if(data == "success") {
              $("#modal_addform #result").html("We've received your submission and will review it shortly. Thanks!"); 
              $("#modal_addform #result").addClass("alert alert-info");
              $("#modal_addform p").css;
              $("#modal_addform fieldset").css;
              $("#modal_addform .btn-primary").css;
              
            // if submission failed, show error
            } else {
              $("#modal_addform #result").html(data); 
              $("#modal_addform #result").addClass("alert alert-danger");
            }
          }
        );
      });
    </script>
	
	<script>
      // add modal form submit
      $("#modal_editform").submit(function(event) {
        event.preventDefault(); 
        // get values
        var $form = $( this ),
            user_name = $form.find( '#edit_user_name' ).val(),
            user_email = $form.find( '#edit_user_email' ).val(),
            title = $form.find( '#edit_title' ).val(),
            address = $form.find( '#edit_address' ).val(),
			founder_name1 = $form.find( '#edit_founder1' ).val(),
			founder_program1 = $form.find( '#edit_program1' ).val(),
			founder_name2 = $form.find( '#edit_founder2' ).val(),
			founder_program2 = $form.find( '#edit_program2' ).val(),
			founder_name3 = $form.find( '#edit_founder3' ).val(),
			founder_program3 = $form.find( '#edit_program3' ).val(),
			founder_name4 = $form.find( '#edit_founder4' ).val(),
			founder_program4 = $form.find( '#edit_program4' ).val(),
            uri = $form.find( '#edit_uri' ).val(),
            description = $form.find( '#edit_description' ).val(),
			video = $form.find('#edit_video').val(),
            url = $form.attr( 'action' );
			edit = 1;
        // send data and get results
        $.post( url, { user_name: user_name, user_email: user_email, title: title, founder_name: founder_name1, founder_program: founder_program1, founder_name2: founder_name2, founder_program2: founder_program2, founder_name3: founder_name3, founder_program3: founder_program3, founder_name4: founder_name4, founder_program4: founder_program4, address: address, uri: uri, description: description, video: video, edit: edit },
          function( data ) {
            var content = $( data ).find( '#content' );
            // if submission was successful, show info alert
            if(data == "success") {
              $("#modal_editform #editresult").html("We've received your submission and will review it shortly. Thanks!"); 
              $("#modal_editform #editresult").addClass("alert alert-info");
              $("#modal_editform p").css;
              $("#modal_editform fieldset").css;
              $("#modal_editform .btn-primary").css;
              
            // if submission failed, show error
            } else {
              $("#modal_editform #editresult").html(data); 
              $("#modal_editform #editresult").addClass("alert alert-danger");
            }
          }
        );
      });
    </script>
	

	<script>
		$('#toggleRight').toggle(function() {
			$('#menuRight').animate({
				right: -230
			}, 'slow', function() {
			});
			$('#toggleRight').animate({
				right: 20
			}, 'slow', function() {
			});
			document.getElementById('toggleRight').innerHTML = "<img src=\"images/icons/gray-left-double-arrow-md.png\" width=\"20\" height=\"20\">";
		}, function() {
			$('#menuRight').animate({
				right: 0
			}, 'slow', function() {
			});
			$('#toggleRight').animate({
				right: 250
			}, 'slow', function() {
			});
			document.getElementById('toggleRight').innerHTML = "<img src=\"images/icons/gray-right-double-arrow-md.png\" width=\"20\" height=\"20\">";
		});
	</script>
    
  </body>
</html>
