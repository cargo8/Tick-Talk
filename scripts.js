/* Fetch Data and Populate Reviews */
$(document).ready(function(){
	$('#loadingDiv')
	    .hide()  // hide it initially
	    .ajaxStart(function() {
	        $(this).show();
	    })
	    .ajaxStop(function() {
	        $(this).hide();
	    });
	
	if(url_q != null & url_zip != null) {
	    $.getJSON("data.php?a=getTextNodes&q=" + url_q + "&zip=" + url_zip, function(data) {
		    	$('#posTickerHeader').show();
				$('#negTickerHeader').show();
				$('#compHeader').show();
				
				if(data == null) {
					$('#noResults').show();
					$('#loadingDiv').hide();
				} else {
					var nodes = []
					$.each(data, function(i, val) {
						nodes.push(new textNode(val['timestamp'], val['text'], val['highlightedText'], val['source'], val['sentiment']));
					});
					loadSnippets(nodes);
					drawChart(nodes);
				}
	    });
	    
	    $.getJSON("data.php?a=getCompetitors&q=" + url_q + "&zip=" + url_zip, function(data) {
			var nodes = []
			$.each(data, function(i, val) {
				nodes.push([val["name"], val["zip"]]);
			});
			loadCompetitors(nodes);
	    });
	} else {
		$('#posTickerHeader').hide();
		$('#negTickerHeader').hide();
		$('#compHeader').hide();
	}
});

function newSearch() {
	var q = document.getElementById("search_place").value;
	var zip = document.getElementById("search_zip").value;

	if(q != null && q != "My Place" && zip != null && zip != "Zip Code") {
    	$('#posTickerHeader').show();
		$('#negTickerHeader').show();
		$('#compHeader').show();
		$('#noResults').hide();
		$('#pos_reviews_content').html("");
		$('#neg_reviews_content').html("");
		$('#competitors_content').html("");
		$('#chart_div').html("");
		$('#loadingDiv').show();
		
		for(var i = 0; i < 100; i++) {
			$(".flip_" + i).die();
		}
		
		$.getJSON("data.php?a=getTextNodes&q=" + q + "&zip=" + zip, function(data) {
			if(data == null) {
				$('#noResults').show();
				$('#loadingDiv').hide();
			} else {
				var nodes = []
				$.each(data, function(i, val) {
					nodes.push(new textNode(val['timestamp'], val['text'], val['highlightedText'], val['source'], val['sentiment']));
				});
				loadSnippets(nodes);
				drawChart(nodes);
			}
	    });
	    
	    $.getJSON("data.php?a=getCompetitors&q=" + q + "&zip=" + zip, function(data) {
			var nodes = []
			$.each(data, function(i, val) {
				nodes.push([val["name"], val["zip"]]);
			});
			loadCompetitors(nodes);
	    });
	} else {
		alert("That is not a valid query.");
	}
}

function drawChart(nodes) {
	console.log("drawChart method");
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Your Rating');
	for(var i = 0; i < nodes.length; i++) {
        var date = new Date();
        date.setTime(nodes[i].timestamp * 1000);
        data.addRow([date, nodes[i].sentiment]);
		/*sentimentSum += nodes[i].sentiment;
		if(i % 5 == 0) {
			var date = new Date();
			date.setTime(nodes[i].timestamp * 1000);
			data.addRow([date, (sentimentSum / Math.max(1,(sentimentSum / (i - lastBucket))))]);
			sentimentSum = 0;
			lastBucket = i;
		}*/
    }
	
    var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
    chart.draw(data, {displayAnnotations: true, fill: 50, thickness: 3, max: 1, min: 0});
}

function textNode(time, text, highlighted, source, rating) {
	this.timestamp = time;
	this.text = text;
	this.highlightedText = highlighted;
	this.source = source;
	this.sentiment = rating;
}

function loadSnippets(nodes) {
	console.log("loadSnippets method");
	$('#pos_reviews_content').html("");
	$('#neg_reviews_content').html("");
	$('#chart_div').html("");
	$.each(nodes, function(i, node) {
		
/* 		Long Corpus of Text */
		if (node['highlightedText'] != node['text']) {
	/* 		Positive Review */
			if (node['sentiment'] > .65 && (node['highlightedText'].indexOf("watch out") === -1 &&
					node['highlightedText'].indexOf("Watch out") === -1 && 
					node['highlightedText'].indexOf("sassy") === -1)) {
				console.log("positive: " + node['sentiment']);
				$('#pos_reviews_content').append(
					"<p class='flip_" + i + "'><img src='images/"+node['source']+".jpg'/>" + node['highlightedText'] + "</p>"
					+ "<div class='panel panel_" + i + "'>" + node['text'] + "</div>" +
					"<script type='text/javascript'>$('.flip_" + i + "').live('click', function(){ console.log('flipper'); $('.panel_" + i + "').slideToggle('fast'); });</script>");
			}
					
	/* 		Negative Review */
			else if (node['sentiment'] < .4 && ((node['highlightedText'].indexOf("Try") === -1) &&
							(node['highlightedText'].indexOf("try") === -1) && (node['highlightedText'].indexOf("Good") === -1) &&
							(node['highlightedText'].indexOf("go") === -1) &&
							(node['highlightedText'].indexOf("Great") === -1) &&
							(node['highlightedText'].indexOf("great") === -1) &&
							(node['highlightedText'].indexOf("Free") === -1) &&
							(node['highlightedText'].indexOf("free") === -1) &&
							(node['highlightedText'].indexOf("Cheap") === -1) &&
							(node['highlightedText'].indexOf("cheap") === -1) &&
							(node['highlightedText'].indexOf("yum") === -1) &&
							(node['highlightedText'].indexOf("Yum") === -1) &&
							(node['highlightedText'].indexOf("trust") === -1) &&
							(node['highlightedText'].indexOf("fine") === -1) &&
							(node['highlightedText'].indexOf("rder") === -1) &&
							(node['highlightedText'].indexOf("yea") === -1) &&
							(node['highlightedText'].indexOf("yea") === -1) &&
							(node['highlightedText'].indexOf("wow") === -1) &&
							(node['highlightedText'].indexOf("Wow") === -1))) {
				console.log("negative: " + node['sentiment']);
				$('#neg_reviews_content').append(
					"<p class='flip_" + i + "'><img src='images/"+node['source']+".jpg'/>" + node['highlightedText'] + "</p>"
					+ "<div class='panel panel_" + i + "'>" + node['text'] + "</div>" +
					"<script type='text/javascript'>$('.flip_" + i + "').live('click', function(){ console.log('flipper'); $('.panel_" + i + "').slideToggle('fast'); });</script>");
			}
/* 					Short Corpus of Text */
		} else {
				/* 		Positive Review */
			if (node['sentiment'] > .65 && (node['highlightedText'].indexOf("watch out") === -1 &&
					node['highlightedText'].indexOf("Watch out") === -1 && 
					node['highlightedText'].indexOf("sassy") === -1)) {
				console.log("positive: " + node['sentiment']);
				$('#pos_reviews_content').append(
					"<p class='flip_" + i + "'><img src='images/"+node['source']+".jpg'/>" + node['highlightedText'] + "</p>"
					+ "<div class='panel'>" + node['text'] + "</div>");
			}
					
	/* 		Negative Review */
			else if (node['sentiment'] < .4 && ((node['highlightedText'].indexOf("Try") === -1) &&
							(node['highlightedText'].indexOf("try") === -1) && (node['highlightedText'].indexOf("Good") === -1) &&
							(node['highlightedText'].indexOf("go") === -1) &&
							(node['highlightedText'].indexOf("Great") === -1) &&
							(node['highlightedText'].indexOf("great") === -1) &&
							(node['highlightedText'].indexOf("Free") === -1) &&
							(node['highlightedText'].indexOf("free") === -1) &&
							(node['highlightedText'].indexOf("Cheap") === -1) &&
							(node['highlightedText'].indexOf("cheap") === -1) &&
							(node['highlightedText'].indexOf("yum") === -1) &&
							(node['highlightedText'].indexOf("Yum") === -1) &&
							(node['highlightedText'].indexOf("trust") === -1) &&
							(node['highlightedText'].indexOf("fine") === -1) &&
							(node['highlightedText'].indexOf("rder") === -1) &&
							(node['highlightedText'].indexOf("yea") === -1) &&
							(node['highlightedText'].indexOf("yea") === -1) &&
							(node['highlightedText'].indexOf("wow") === -1) &&
							(node['highlightedText'].indexOf("Wow") === -1))) {
				console.log("negative: " + node['sentiment']);
				$('#neg_reviews_content').append(
					"<p class='flip_" + i + "'><img src='images/"+node['source']+".jpg'/>" + node['highlightedText'] + "</p>"
					+ "<div class='panel'>" + node['text'] + "</div>");
			}
		}

/* 		Full Print Out of Comments + Sentiment for Debugging*/
/* 		console.log(node['text'] + ": Sentiment=" + node['sentiment']); */
	});
}

function loadCompetitors(nodes) {
	console.log("loadCompetitors method");
	$('#competitors_content').html("");
	$.each(nodes, function(i, node) {
		$('#competitors_content').append("<span style=\"margin-right: 40px;\"><a href=\"index.php?q=" + node[0] + "&zip=" + node[1] + "\">" + node[0] + "</a></span>");
	});
}

function toggleCheckbox(elt) {
	var color;
	var id = "#"+elt;
	console.log(id);
	if (id == '#pos_check') {color = 'green';}
	else if (id == '#neg_check') {color = 'red';}
	else if (id == '#comp_check') {color = 'blue';}
	else if (id == '#comp1') {color = 'orange';}
	else if (id == '#comp2') {color = 'yellow';}
	else if (id == '#comp3') {color = 'gray';}
	else if (id == '#comp4') {color = 'purple';}
	console.log(color);
	console.log($(elt).css('background-color'));
	if ($(id).css('background-color') != color) {
		console.log('hi');
		$(id).css('background-color', color);
	} else {
			console.log('bye');
		$(id).css('background-color', 'white');
	}
}

function compete() {
	toggleCheckbox('comp_check');
	if ($('#comp_switcher').css('display') == 'none') {
		$('#comp_switcher').show();
		$('#switcher').css('top', -15);
		$('#main_switcher').hide();
	} else {
		$('#comp_switcher').hide();
		$('#switcher').css('top', 0);
		$('#main_switcher').show();
	}
}

function switchFilter(elt) {
	//TODO(jmow): switch graph when selector is switched
	
}