<html>
<head>

	<title>PennApps Sentiment Analyzer</title>

</head>

<body>
	<?php
	
		function makeCurlCall($content) {
	    $ch = curl_init("http://sentimentanalyzer.appspot.com/api/classify");
	    $data = array('content' => $content, 'lang' => 'en');
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    $curlResults = curl_exec($ch);   
	    curl_close($ch);
	    return $curlResults;
	  }
	  
	  /* Test input via GET */
		$content = $_GET['con'];
		makeCurlCall($content);
	?>

</body>
</html>