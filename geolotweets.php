<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta charset="UTF-8">
		<title>GeoloTweets</title>
	</head>
	<body>
		<div class="notTooWide">
		<button onclick="getLocation()">Let's Geolotwitter!</button>
		<div id="displayLocation">
		</div>
		
		<script>
		var geo = document.getElementById("displayLocation");
		
		function getLocation()
		{
    	
	    	if (navigator.geolocation)
	    	{
	        	navigator.geolocation.getCurrentPosition(sendPosition, showError);
	    	}
	    	else
	    	{
	        	geo.innerHTML = "Geolocation is not supported by this browser.";
	    	}
		}
		
		function sendPosition(position) 
		{
			window.location.href="geolotweets.php?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude;
		}

		function showError(error)
		{
			if(error.code == error.PERMISSION_DENIED)
			{
            	geo.innerHTML = "User denied the request for Geolocation."
            }
            else if(error.code == error.POSITION_UNAVAILABLE)
            {
            	geo.innerHTML = "Location information is unavailable."	
            }
            else if(error.code == error.TIMEOUT)
            {
            	geo.innerHTML = "The request to get user location timed out."	
            }
            else if(error.code == error.UNKNOWN_ERROR)
        	{
            	geo.innerHTML = "An unknown error occurred."
            }
        }
		</script>

		<?php
		include 'storedInfo.php';
		session_start();
		require "twitteroauth/twitteroauth-0.5.1/twitteroauth-0.5.1/autoload.php";
		use Abraham\TwitterOAuth\TwitterOAuth;

		if ($_GET["latitude"] && $_GET["longitude"]) {
			$latitude = htmlspecialchars($_GET["latitude"]);
		
			$longitude = htmlspecialchars($_GET["longitude"]);
		
 
			function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  				$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  				return $connection;
			}

			$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
			$tweets = json_encode($connection->GET("search/tweets", array("q" => "geocode:" . $latitude . "," . $longitude . ",5mi")));

			$tweetsArray = json_decode($tweets, true);

			foreach($tweetsArray["statuses"] as $element)
   			{
        		echo "<p>" . $element["user"]["screen_name"] . " tweeted " . $element["text"] . " at " . $element["created_at"] . "</p>";
    		}
		}
		?>
	</div>
	</body>
</html>