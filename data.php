<?php

session_start();

$GLOBALS["highlight_length"] = 100;
$GLOBALS["competitors_count"] = 15;

interface DataSource {	
	public function getName();
	public function getBaseUrL();
	public function getData($key = null);
	public function popData($entity, $geo = null, $extra = null);
}

class TextNode {
	public $source;
	public $sentiment;
	public $text;
	public $highlightedText;
	public $timestamp;
}

class HunchGetResults implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
	
	function __construct() {
		$this->name = "Hunch API Get Results Endpoint";
		$this->baseUrl = "http://api.hunch.com/api/v1/get-results/";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: hgr->popData("Smokey Joe's", array(39.9537,-75.2029));
	function popData($entity, $geo = null, $extra = null) {
		$urlParams = array("name" => $entity,
						   "lat" => $geo[0],
						   "lng" => $geo[1],
						   "radius" => 3,
						   "auth_token" => "1385476dbc2e45b8cd16838eb5a6f68fbc44c3eb");
						   
		$url = $this->baseUrl . "?" . http_build_query($urlParams);
				
		$response = file_get_contents($url);
		$this->data = json_decode($response, true);
	}
}

class HunchGetSimilarResults implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
	
	function __construct() {
		$this->name = "Hunch API Get Similar Results Endpoint";
		$this->baseUrl = "http://api.hunch.com/api/v1/get-similar-results/";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: hgsr->popData("yelp_-KWel73upKHxstRCr9JGLw", array(39.9537,-75.2029));
	function popData($entity, $geo = null, $extra = null) {
		$urlParams = array("result_id" => $entity,
						   "lat" => $geo[0],
						   "lng" => $geo[1],
						   "radius" => 3,
						   "auth_token" => "1385476dbc2e45b8cd16838eb5a6f68fbc44c3eb");

		if(!empty($extra)) {
			$urlParams["topic_ids"] = $extra;
		}
						   
		$url = $this->baseUrl . "?" . http_build_query($urlParams);
		$response = file_get_contents($url);
		$this->data = json_decode($response, true);
	}
}

class CityGridPlacesWhere implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
	
	function __construct() {
		$this->name = "CityGrid Places API Where Endpoint";
		$this->baseUrl = "http://api.citygridmedia.com/content/places/v2/search/where";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: $cgpw->popData("bikram-yoga-of-philadelphia", "19104");
	function popData($entity, $geo = null, $extra = null) {
		$urlParams = array("what" => urlencode($entity),
						   "where" => urlencode($geo),
						   "format" => "json",
						   "publisher" => "10000001319");
						   
		$url = $this->baseUrl . "?" . http_build_query($urlParams);
		
		$response = file_get_contents($url);
		$this->data = json_decode($response, true);
	}
}

class CityGridPlacesDetail implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
		
	function __construct() {
		$this->name = "CityGrid Places API Detail Endpoint";
		$this->baseUrl = "http://api.citygridmedia.com/content/places/v2/detail";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: $cgpd->popData("8950754");
	function popData($entity, $geo = null, $extra = null) {		
		// $entity should be a CityGrid place identifier
		$urlParams = array("listing_id" => $entity,
						   "client_ip" => (empty($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1",
						   "format" => "json",
						   "publisher" => "10000001319");
		
		$url = $this->baseUrl . "?" . http_build_query($urlParams);
				
		$response = file_get_contents($url);
		
		$this->data = json_decode($response, true);
	}
}

class GooglePlaceSearches implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
	
	function __construct() {
		$this->name = "Google Place Searches API";
		$this->baseUrl = "https://maps.googleapis.com/maps/api/place/search/json";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: gps->popData("Smokey Joe's", array(39.9537,-75.2029));
	function popData($entity, $geo = null, $extra = null) {
		$urlParams = array("name" => urlencode($entity),
						   "location" => $geo[0] . "," . $geo[1],
						   "sensor" => "false",
						   "radius" => 5000,
						   "key" => "AIzaSyDTs6jXRmNEhVR7scGGIh79oKadX8GHRow");
						   
		$url = $this->baseUrl . "?" . http_build_query($urlParams);
		$response = file_get_contents($url);
		$this->data = json_decode($response, true);
	}
}

class FourSquareSearchVenues implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
		
	function __construct() {
		$this->name = "FourSquare Search Venues API";
		$this->baseUrl = "https://api.foursquare.com/v2/venues/search";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: fssv->popData("Smokey Joe's", array(39.9537,-75.2029));
	function popData($entity, $geo = null, $extra = null) {		
		$urlParams = array("query" => $entity,
						   "intent" => "match",
						   "ll" => $geo[0] . "," . $geo[1],
						   "client_id" => "KQBF0TU4CBPQYIIBKJDVK355NRI1IJOX1HD3R5P4S1O1OTRZ",
						   "client_secret" => "UQRDUDAETPX45B5D5WLMW5RWOYO2RPXWFYI52XBMZCJ2ZM05");

		// $entity should be a FourSquare place identifier		
		$url = $this->baseUrl . "?" . http_build_query($urlParams);
		
		$response = file_get_contents($url);
		$this->data = json_decode($response, true);
	}
}

class FourSquareVenues implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
		
	function __construct() {
		$this->name = "FourSquare Venues API";
		$this->baseUrl = "https://api.foursquare.com/v2/venues/";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: fsv->popData("5104");
	function popData($entity, $geo = null, $extra = null) {		
		$urlParams = array("client_id" => "KQBF0TU4CBPQYIIBKJDVK355NRI1IJOX1HD3R5P4S1O1OTRZ",
						   "client_secret" => "UQRDUDAETPX45B5D5WLMW5RWOYO2RPXWFYI52XBMZCJ2ZM05");

		// $entity should be a FourSquare place identifier		
		$url = $this->baseUrl . urlencode($entity) . "?" . http_build_query($urlParams);
		
		$response = file_get_contents($url);
		$this->data = json_decode($response, true);
	}
}

class TwitterSearch implements DataSource {
	private $name;
	private $baseUrl;
	private $data = array();
		
	function __construct() {
		$this->name = "Twitter Search API";
		$this->baseUrl = "http://search.twitter.com/search.json";
	}
	
	function getName() { return $this->name; }
	function getBaseUrl() { return $this->baseUrl; }
	function getData($key = null) {
		return $this->data;
	}
	
	// Sample: ts->popData("smokey-joes", array(39.9537,-75.2029));
	function popData($entity, $geo = null, $extra = null) {		
		$url = $this->baseUrl . "?q=" . urlencode($entity) . "&rpp=100&geocode%3A" . $geo[0] . "," . $geo[1] . ",15mi";
		$response = file_get_contents($url);
		$this->data = json_decode($response, true);
	}
}

function populateData($entityID) {
	if(empty($entityID))
		return -1;
	
	$cgpd = new CityGridPlacesDetail();
	$cgpd->popData($entityID);
	$_SESSION["data"]["cgpd"] = $cgpd->getData();
	$cgName = $_SESSION["data"]["cgpd"]["locations"][0]["name"];
	$cgLat = $_SESSION["data"]["cgpd"]["locations"][0]["address"]["latitude"];
	$cgLong = $_SESSION["data"]["cgpd"]["locations"][0]["address"]["longitude"];
	$cgReviewCount = count($_SESSION["data"]["cgpd"]["locations"][0]["review_info"]["total_user_reviews"]);
	$cgOverallRating = $_SESSION["data"]["cgpd"]["locations"][0]["review_info"]["overall_review_rating"];
	// echo "Found " . $cgName . " with CityGrid ID " . $entityID . ", lat-long (" . $cgLat . ", " . $cgLong . "). There are " . $cgReviewCount . " review(s), with an average rating of " . $cgOverallRating . ".<br><br>";
	
	if(!empty($cgName) && !empty($cgLat) && !empty($cgLong)) {
		$gps = new GooglePlaceSearches();
		$gps->popData($cgName, array($cgLat, $cgLong));
		$_SESSION["data"]["gps"] = $gps->getData();
		
		$ts = new TwitterSearch();
		$ts->popData($cgName, array($cgLat,$cgLong));
		$_SESSION["data"]["ts"] = $ts->getData();
		// echo "Got " . count($_SESSION["data"]["ts"]["results"]) . " possibly related tweets on Twitter.<br><br>";
		
		$fssv = new FourSquareSearchVenues();
		$fssv->popData($cgName, array($cgLat, $cgLong));
		$_SESSION["data"]["fssv"] = $fssv->getData();
		$fsId = $_SESSION["data"]["fssv"]["response"]["groups"][0]["items"][0]["id"];
		// echo "Four Square ID is " . $fsId . ".<br><br>";
		
		if(!empty($fsId)) {
			$fsv = new FourSquareVenues();
			$fsv->popData($fsId);
			$_SESSION["data"]["fsv"] = $fsv->getData();
			$fsStats = $_SESSION["data"]["fsv"]["response"]["venue"]["stats"];
			$fsTipCount = $_SESSION["data"]["fsv"]["response"]["venue"]["tips"]["count"];
			// echo "Four Square shows " . $fsStats["checkinsCount"] . " checkins by " . $fsStats["usersCount"] . " users. There are " . $fsTipCount . " tips.<br><br>";
		}
	}
}

function getCompetitors($entityID) {
	if(empty($_SESSION["data"]) || $_SESSION["data"]["entityID"] != $entityID || true || $entityID < null) {
		if(populateData($entityID) < 0) {
			return null;
		}
	}
	
	$cgName = $_SESSION["data"]["cgpd"]["locations"][0]["name"];
	$cgLat = $_SESSION["data"]["cgpd"]["locations"][0]["address"]["latitude"];
	$cgLong = $_SESSION["data"]["cgpd"]["locations"][0]["address"]["longitude"];
	
	$hgr = new HunchGetResults();
	$hgr->popData($cgName, array($cgLat, $cgLong));
	$_SESSION["data"]["hgr"] = $hgr->getData();	
	$lists = $_SESSION["data"]["hgr"]["results"][0]["lists"];
	$listsStr;
	if(!empty($lists)) {
		$keys = array_keys($lists);
		for($i = 0; $i < count($lists); $i++) {
			$listsStr .= $keys[$i] . ",";
		}
		$listsStr = substr($listsStr, 0, strlen($listsStr) - 1);
	}
	
	$hId = $_SESSION["data"]["hgr"]["results"][0]["result_id"];
	$hgsr = new HunchGetSimilarResults();
	$hgsr->popData($hId, array($cgLat, $cgLong), $listsStr);
	$_SESSION["data"]["hgsr"] = $hgsr->getData();
	
	$competitors = array();
	for($i = 0; $i < min($GLOBALS["competitors_count"], count($_SESSION["data"]["hgsr"]["results"])); $i++) {
		$competitor = $_SESSION["data"]["hgsr"]["results"][$i];
		$competitors[] = array("name" => $competitor["name"], "zip" => $competitor["zip"]);
	}
	
	return $competitors;
}

function getTextNodes($entityID, $filter=null) {	
	if(empty($_SESSION["data"]) || $_SESSION["data"]["entityID"] != $entityID || true || $entityID < 0) {
		if(populateData($entityID) < 0) {
			return null;
		}
	}

	$textNodes = array();
		
	// CityGrid reviews
	$reviews = $_SESSION["data"]["cgpd"]["locations"][0]["review_info"]["reviews"];
	
	if(!empty($reviews)) {
		foreach($reviews as $r) {
			$tn = new TextNode();
			$tn->source = "Citysearch";
			$tn->timestamp = strtotime($r["review_date"]);
			$tn->sentiment = $r["review_rating"];
			$tn->text = $r["review_title"] . " " . $r["review_text"];
			if(!empty($r["pros"])) {
				$tn->text .= " pros: " . $r["pros"];
			}
			if(!empty($r["cons"])) {
				$tn->text .= " cons: " . $r["cons"];
			}
			// Only show a review if it shows the requisite filter (if one's been set)
			if(empty($filter) || (!empty($filter) && (strpos(strtolower($tn->text), strtolower($filter)) > -1))) {
				$textNodes[] = $tn;
			}
		}
	}
	
	// Tweets (only if geo or place is defined)
	$tweets = $_SESSION["data"]["ts"]["results"];
	if(!empty($tweets)) {
		foreach($tweets as $tweet) {
			if(!empty($tweet["place"]) || !empty($tweet["geo"])) {
				$tn = new TextNode();
				$tn->source = "Twitter";
				$tn->timestamp = strtotime($tweet["created_at"]);
				$tn->text = $tweet["text"];
				// Only show a review if it shows the requisite filter (if one's been set)
				if(empty($filter) || (!empty($filter) && (strpos(strtolower($tn->text), strtolower($filter)) > -1))) {
					$textNodes[] = $tn;
				}
			}
		}
	}
	
	// Four Square tips
	$tips = $_SESSION["data"]["fsv"]["response"]["venue"]["tips"]["groups"][0]["items"];
	
	if(!empty($tips)) {
		foreach($tips as $tip) {
			$tn = new TextNode();
			$tn->source = "Four Square";
			$tn->timestamp = $tip["createdAt"];
			$tn->text = $tip["text"];
			// Only show a review if it shows the requisite filter (if one's been set)
			if(empty($filter) || (!empty($filter) && (strpos(strtolower($tn->text), strtolower($filter)) > -1))) {
				$textNodes[] = $tn;
			}
		}
	}
	
	$data = array();
	foreach($textNodes as $tn) {
		$data[] = array("content" => $tn->text, "lang" => "en");
	}
	$container = array("data" => $data);
	$sentiments = json_decode(curl_post("http://sentimentanalyzer.appspot.com/api/classify.json", json_encode($container)), true);
	$sentiments = $sentiments["data"];
	
	$i = 0;
	
	/*
	 * Populate the highlighted text snippet for each text node
	 */
	foreach($textNodes as $tn) {
		if($tn->source == "Citysearch" && $tn->sentiment > 0) {
			$tn->sentiment = ($sentiments[$i]["score"] + (0.1 * $tn->sentiment)) / 2;
		} else {
			$tn->sentiment = $sentiments[$i]["score"];
		}
		$i++;
		
		if(!empty($filter)) {
			$pos = strpos(strtolower($tn->text), strtolower($filter));
			if($pos > -1) {
				for($j = $pos; $j > 0; $j--) {
					if($tn->text[$j] == "." || $tn->text[$j] == "!" || $tn->text[$j] == "?" || $tn->text[$j] == ";") {
						$tn->highlightedText = substr($tn->text, ($j+1), ($j+1+$GLOBALS["highlight_length"]));
						break;
					}
				}
				if(empty($tn->highlightedText))
					$tn->highlightedText = substr($tn->text, 0, $GLOBALS["highlight_length"]);
			}
		} else {
			$tn->highlightedText = substr($tn->text, 0, $GLOBALS["highlight_length"]);
		}
		if(strlen($tn->highlightedText) == $GLOBALS["highlight_length"]) {
			$tn->highlightedText .= "...";
		}
	}
	
	return $textNodes;
}

function getEntityID($entity, $zip) {
	$entity = str_replace(" ", "-", $entity);
	$cgpw = new CityGridPlacesWhere();
	$cgpw->popData($entity, $zip);
	$data = $cgpw->getData();
	return $data["results"]["locations"][0]["id"];
}

/** 
 * Send a POST requst using cURL 
 * @param string $url to request 
 * @param array $post values to send 
 * @param array $options for cURL 
 * @return string 
 */ 
function curl_post($url, $post, array $options = array()) { 
    $defaults = array( 
        CURLOPT_POST => 1, 
        CURLOPT_HEADER => 0, 
        CURLOPT_URL => $url, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 4, 
        CURLOPT_POSTFIELDS => $post
    ); 

    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 
    return $result; 
}

$action = $_REQUEST["a"];
switch($action) {
	case "getTextNodes":
		// http://localhost/PennApps_1/data.php?a=getTextNodes&q=marathon%20grill&zip=19102
		$id = getEntityID($_REQUEST["q"], $_REQUEST["zip"]);
		print json_encode(getTextNodes($id, (empty($_REQUEST["filter"])) ? null : $_REQUEST["filter"]));
		break;
	case "getCompetitors":
		$id = getEntityID($_REQUEST["q"], $_REQUEST["zip"]);
		print json_encode(getCompetitors($id));
		break;
	case "getTextNodes_debug":
		$id = getEntityID($_REQUEST["q"], $_REQUEST["zip"]);
		getTextNodes($id, (empty($_REQUEST["filter"])) ? null : $_REQUEST["filter"]);
		print_r($_SESSION["data"]);
		break;
}

?>