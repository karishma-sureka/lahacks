<html>
    <head>
        <title>Login | Quick Chat</title>
        <meta charset="UTF-8" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <style type="text/css">
        .form-signin {
		    max-width: 330px;
		    padding: 15px;
		    margin: 0 auto;
		}
		.form-control {
			margin-bottom: 20px;
		}
		</style>
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
              <a class="navbar-brand" href="#">Quick Chat</a>
            </div>
          </div>
        </nav>
		<div class="container">

	      <form class="form-signin" style="max-width:300px;" method="POST" action="my_contacts.php">
	        <h2 class="form-signin-heading">Please sign in</h2>
	        <label for="inputEmail" class="sr-only">User</label>
			<select class="form-control" name="uid">
    		<?php 
				$row = 1;
				if (($handle = fopen("user_data.csv", "r")) !== FALSE) {
				    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					    	$uniqueid = $data[0];
					    	$firstname = $data[1];
					    	$lastname = $data[2];
					    	?>
					    	<option value="<?php echo $uniqueid;?>"><?php echo $firstname." ".$lastname; ?></option>
					    	<?
				        $row++;
				    }
				    fclose($handle);
				}	
    		?>
    		</select>
	        <label for="inputPassword" class="sr-only">Password</label>
	        <input type="password" id="inputPassword" class="form-control" value="password" placeholder="Password" required="">
	        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	      </form>

	    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>
        	$("#contact_list").height($(window).height()-120);
        </script>
	</body>   
</html>