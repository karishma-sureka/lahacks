<?php
include_once "config.inc.php";
?>
<html>
    <head>
        <title>Create Users | Quick Chat</title>
        <meta charset="UTF-8" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    </head>
    <body>
        <nav class="navbar navbar-inverse ">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Quick Chat: Creating users in Moxtra for testing...</a>
            </div>
          </div>
        </nav>
		<div class="container">
				<ul class="list-group">
				<?php
				$row = 1;
				if (($handle = fopen("user_data.csv", "r")) !== FALSE) {
				    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				    	//Get each user data from every row
				    	$uniqueid = $data[0];
				    	$firstname = $data[1];
				    	$lastname = $data[2];
				    	$pictureurl = $data[3];

						//Get current UTC timestamp in milliseconds
					    date_default_timezone_set('UTC'); 
					    $timestamp = time()*1000;
					    $access_token = "";

				    	//Post data to setup/initialize user
					    $data_string = "client_id=".$CLIENT_ID."&client_secret=".$CLIENT_SECRET."&grant_type=".$USER_INITIALIZE_GRANT_TYPE."&uniqueid=".$uniqueid."&timestamp=".$timestamp."&firstname=".$firstname."&lastname=".$lastname."&pictureurl=".$pictureurl;
					    $uri = $OAUTH_ENDPOINT_DOMAIN."/oauth/token";
					    $ch = curl_init();
					    curl_setopt($ch, CURLOPT_URL,$uri);
					    curl_setopt($ch, CURLOPT_POST, 1);
					    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					    $result = curl_exec($ch);
					    echo "result: ".$result;
					    $result = json_decode($result, true);
					    //Get Access Token on Successful Setup & Initialization of the User
					    $access_token = $result['access_token'];

					    if ($access_token != "")
					    {
						    echo "<li class='list-group-item'>".$firstname." ".$lastname." <span style='color:green; float:right;'>(Create User Success)</span></li>";
					    } else {
						    echo "<li class='list-group-item'>".$firstname." ".$lastname." <span style='color:red; float:right;'>(Create User Failed)</span></li>";
					    }

				        $row++;
				    }
				    fclose($handle);
				}
				?>
				</ul>
		</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</body>   
</html>
