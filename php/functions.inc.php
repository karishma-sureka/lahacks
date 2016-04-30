<?php
function show_user_contacts($uid) {
	?>
	<div class="panel panel-default" style="height:600px;">
	  <div class="panel-heading">
	    <h3 class="panel-title">My Contacts</h3>
	  </div>
	  <ul class="list-group">
	<?php
	$row = 1;
	if (($handle = fopen("user_data.csv", "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

	    	//Print Users Contact List
	    	if ($data[0] != $uid) {
		    	$firstname = $data[1];
		    	$lastname = $data[2];
		    	$uniqueid = $data[0];
		    	?>
		    	<li class='list-group-item'>
	    		<a href="javascript:start_chat('<?php echo $uniqueid;?>');"><?php echo $firstname." ".$lastname; ?></a>
	    		</li>
		    	<?
	    	}
	        $row++;
	    }
    	echo "<li class='list-group-item'></li>";
	    fclose($handle);
	}
	?>
	  </ul>
	</div>
	<?php
}

function get_access_token($uid) {
		global $CLIENT_ID, $CLIENT_SECRET, $USER_INITIALIZE_GRANT_TYPE, $OAUTH_ENDPOINT_DOMAIN;
		//Get current UTC timestamp in milliseconds
	    date_default_timezone_set('UTC'); 
	    $timestamp = time()*1000;

    	//Post data to setup/initialize user
	    $data_string = "client_id=".$CLIENT_ID."&client_secret=".$CLIENT_SECRET."&grant_type=".$USER_INITIALIZE_GRANT_TYPE."&uniqueid=".$uid."&timestamp=".$timestamp;
	    $uri = $OAUTH_ENDPOINT_DOMAIN."/oauth/token";
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$uri);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    $result = json_decode($result, true);

	    //Get Access Token on Successful Setup & Initialization of the User
	    $access_token = $result['access_token'];

	    if ($access_token != "")
	    {
		    return $access_token;
	    } else {
		    return "";
	    }
}
?>