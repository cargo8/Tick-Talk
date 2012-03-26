<?php
	require_once("data.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>
	<title>Tick Talk - Sentiment Analysis for Your Business</title>
	<link rel="stylesheet" type="text/css" href="style/style.css" />
	<link rel="stylesheet" type="text/css" href="style/comfortaa/stylesheet.css" />
	<link rel="icon" type="image/ico" href="images/favicon.ico">
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="scripts.js"></script>
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	<script type='text/javascript'>
		google.load('visualization', '1', {'packages':['annotatedtimeline']});
		var url_q;
		var url_zip;
		<?php
			if(!empty($_REQUEST["q"]) && !empty($_REQUEST["zip"])) {
				echo "url_q = \"" . $_REQUEST["q"] . "\";\n";
				echo "url_zip = \"" . $_REQUEST["zip"] . "\";";
			}
		?>
	</script>	
	<!-- Google Analytics -->
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25862673-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>
	<div id='loadingDiv'><br><br><br><span class='load'>Loading...</span></div>
	<div id='noResults' style="display:none;"><br><br><br><span class='load'>We couldn't find what you were looking for. Please try another query.</span></div>

	<div id='logo' style="padding-left: 30px;">
		<a href="/dev/pennapps11/index.php" style="text-decoration:none; color:white;">Tick Talk</a>
		<input class="searchbox" id='search_place' type="text" name="query" value="My Place" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'My Place':this.value;"/>
		<input class="searchbox" id='search_zip' type="text" name="query" value="Zip Code" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'Zip Code':this.value;"/>
		<input id='search_submit' type="submit" onclick="javascript:newSearch();" name="submit" value="Submit"/>
	</div>
	
	<!--<div style="text-align: right; margin-right: 200px; margin-top: 30px;">
		<h3 style="font-size: 25px;">Enter the name of your venue above. <img style="vertical-align: middle;" src="http://dclips.fundraw.com/zobo500dir/1uparrow.jpg" style="height: 60px; width: 60px;"></h3>
	</div>-->
	
	<div id='graph_analysis'>
		<center><div id='chart_div' style='width: 800px; height: 350px;'></div></center>
	</div>
		
	<!--<div id='switcher'>
		<div id='main_switcher'>
			<div id='pos_check' class='checkbox' onclick="toggleCheckbox('pos_check');" style="background-color: green;"></div><span class='graph_opt'>Positive</span><br/>
			<div id='neg_check' class='checkbox' onclick="toggleCheckbox('neg_check');" style="background-color: red;"></div><span class='graph_opt'>Negative</span><br/>
		</div>
		<div id='comp_check' class='checkbox' onclick="compete();"></div><span class='graph_opt'>Show Competitive Sentiments</span><br/>
		<div id='comp_switcher'>
			<div id='comp1' class='sub checkbox' onclick="toggleCheckbox('comp1');" style="background-color: orange;"></div><span class='graph_opt'>Sweetgreen</span><br/>
			<div id='comp2' class='sub checkbox' onclick="toggleCheckbox('comp2');" style="background-color: yellow;"></div><span class='graph_opt'>Bobby's Burger Palace</span><br/>
			<div id='comp3' class='sub checkbox' onclick="toggleCheckbox('comp3');" style="background-color: gray;"></div><span class='graph_opt'>New Delhi India Restaurant</span><br/>
			<div id='comp4' class='sub checkbox' onclick="toggleCheckbox('comp4');" style="background-color: purple;"></div><span class='graph_opt'>Sitar India</span><br/>
		</div>
	</div>-->
	
	<div id='highlights'>
		<div id='pos_reviews'>
		<h3 id="posTickerHeader"><center>Positive Ticker</center></h3>
		<div id='pos_reviews_content'>&nbsp;</div>
		</div>
		
		<div id='neg_reviews'>
		<h3 id="negTickerHeader"><center>Negative Ticker</center></h3>
		<div id='neg_reviews_content'>&nbsp;</div>
		</div>
		
		<center>
		<div id="competitors" style="clear: both; padding-top: 50px; width: 90%; text-align: center; margin: 0px auto;">
		<h3 id="compHeader"><center>What is your competition up to?</h3>
		<div id="competitors_content">&nbsp;</div>
		</div>
		</center>
	</div>
</body>

</html>