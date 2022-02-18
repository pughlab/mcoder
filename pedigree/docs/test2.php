<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,maximum-scale=2">

	<link href="https://code.jquery.com/ui/1.12.1/themes/cupertino/jquery-ui.min.css" rel="stylesheet" type="text/css" media="all" />
	<link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="stylesheet" href="../dist/css/pedigreejs.min.css" />

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/d3@5.16.0/dist/d3.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<script src="../dist/js/pedigreejs.min.js"></script>
	<script src="../js/io.js"></script>

	<style>
		body {
		    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		    font-size: 14px;
		    line-height: 1.42857143;
		    color: #333;
		    background-color: #fff;
		}
		.container {
		    width: 90%;
		    max-width: 1140px;
		    margin-right: auto;
		    margin-left: auto;
		}

		#ped {text-align:center;}
		.btn {
		color: #333;
		    background-color: #fff;
		    border: 1px solid #ccc;
		    display: inline-block;
		    padding: 6px 12px;
		    cursor: pointer;
		    margin-left: 5px;
		    border-radius: 4px;
		}
		pre {
			background-color: #F5F5F5;
			padding: 10px;
			border: 1px solid #ccc;
			font-size: 13px;
    		line-height: 1.42857143;
			overflow: auto;word-wrap: normal;
			border-radius: 4px;
		}
		#history_ex8 {
			padding: 10px;
		}
		.text-center {
			text-align: center;
			padding: 0 20px;
		}
	</style>

	<script type="text/javascript">


	$( document ).ready(function() {

		var parent_width = $('#pedigrees').parent().width();
		var margin = ($(window).width()-parent_width > 10 ? 100 : 30);
		var svg_width = (parent_width > 750 ? (parent_width*8/12 - margin) : parent_width- margin);

		var dataset = [
			{"name": "m11", "display_name": "John",  "sex": "M", "diabetes_diagnosis_age": 55, "top_level": true},
			{"name": "f11", "display_name": "Jane",  "sex": "F", "status": 1, "top_level": true},
			{"name": "m12", "display_name": "Jack", "sex": "M", "top_level": true},
			{"name": "f12", "display_name": "Jill", "sex": "F", "top_level": true},
			{"name": "m21", "display_name": "Jim", "sex": "M", "mother": "f11", "father": "m11", "age": 56},
			{"name": "f21", "display_name": "Jan", "sex": "F", "mother": "f12", "father": "m12", "age": 63},
			{"name": "ch1", "display_name": "Ana", "sex": "F", "mother": "f21", "father": "m21", "proband": true, "age": 25}
		];
		var opts = {
			'targetDiv': 'pedigrees',
			'btn_target': 'history_ex8',
			'store_type': 'session',
			'width': svg_width,
			'height': 500,
			'symbol_size': 35,
			'edit': true,
			'diseases': [
				{'type': 'diabetes', 'colour': '#F68F35'},
			],
			'DEBUG': (pedigree_util.urlParam('debug') === null ? false : true)
		};
		//$('#opts').append(JSON.stringify(opts, null, 4));
		var local_dataset = pedcache.current(opts);
		if (local_dataset !== undefined && local_dataset !== null) {
			opts.dataset = local_dataset;

			//jsondata = JSON.parse(JSON.stringify(opts.dataset));
			jsondata = JSON.stringify(opts.dataset);

		} else {
			opts.dataset = dataset;

			//jsondata = JSON.parse(JSON.stringify(opts.dataset));
				jsondata = JSON.stringify(opts.dataset);
		}
		opts= ptree.build(opts);

 //console.log(jsondata);



		var html = "";
		for(var p=0; p<opts.dataset .length; p++){
		var attr = '';
		$.each(local_dataset[p], function(key, value) {
		attr += key + ":" + value + "; ";
		});
	}
		html += attr + "<br>";
		//document.getElementById("testinfo").innerHTML = html;
		//document.getElementById("testinfo").innerHTML =
	//	console.log(local_dataset);



function search_pedigree_data(search) {
	var pedigree = pedcache.current(opts);		// current pedigree data
	var html = "";
	for(var p=0; p<pedigree.length; p++){
		var found = false;
		var attr = '';
		$.each(pedigree[p], function(key, value) {
			if(search == key || search == value ||
				 key.indexOf(search) !== -1 || (""+value).indexOf(search) !== -1) {
				found = true;
				attr += "<strong><font color='red'>"+ key + ":" + value + "; " + "</font></strong>";
			} else {
				attr += key + ":" + value + "; ";
			}
		});
		if(found) {
			html += attr + "<br>";
		}
	}
	if(html !== '') {
		$('#search_result').html(html);
	} else {
		$('#search_result').html("No matches");
	}
}

$('button').click(function() {
	search_pedigree_data($('input[type="text"]').val());
});

$('input[type="text"]').keypress(function(e){
			if(e.which == 13) // enter key pressed
				search_pedigree_data($('input[type="text"]').val());
	});

});

	</script>


</head>
<body>

	<?php

	//echo $_COOKIE["gfgsx"];
//	echo $variable = $_GET['a'];
//echo $variable = "<script>document.write(content)</script>";
	?>

<div class="text-center">
	<h2>Example 8</h2>
	<p id="testinfo"></p>
	<div class="container">
		<div>
			<input type="text" placeholder="NF-01-01" id="patientidsource" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
		</div>
		<div class="row">
		<div class="input-group col-md-4">
			<input type="text" class="form-control" placeholder="Search for...">
		      <span class="input-group-btn">
		        <button class="btn btn-search, e.g. John" type="button"><i class="fa fa-search fa-fw"></i> Search</button>
		      </span>
		</div>
		</div>
	</div>

	<label class="btn btn-default btn-file">
		<input id="load" type="file" style="display: none;"/>Load
	</label>
	<label class="btn btn-default btn-file">
		<input id="save" type="button" style="display: none;"/>Saving
	</label>

	<div id='search_result'></div>
	<div id='history_ex8'></div>
	<div id="pedigrees"></div>
	<div id="node_properties"></div>
</div>


<div id="responsecontainer" align="center"></div>
<div class="container">
<br>
<p>This example shows how a case sensitive search of the individual's attributes (<i>e.g.</i> age, name) can be implemented.
The user can enter a search string into the 'Search for...' text area located above the pedigree.
A more sophisticated search could be based on this for example by additionally providing 'case insensitive' and 'search and replace' options.
When the 'Search' button is clicked or 'enter' pressed in the 'Search for...' text box the following search method is called:</p>
<pre>

// search pedigree data with the given search text
function search_pedigree_data(search) {
	var pedigree = pedcache.current(opts);		// current pedigree data displayed
	var html = "";
	for(var p=0; p&lt;pedigree.length; p++){		// loop over each person in the pedigree
		var found = false;
		var attr = '';
		$.each(pedigree[p], function(key, value) {	// loop over individual's data
			if(search == key || search == value ||
			   key.indexOf(search) !== -1 || (""+value).indexOf(search) !== -1) {
				found = true;
				attr += "&lt;strong>&lt;font color='red'>"+ key + ":" + value + "; " + "&lt;/font>&lt;/strong>";
			} else {
				attr += key + ":" + value + "; ";
			}
		});
		if(found) {
			html += attr + "&lt;br>";
		}
	}
	if(html !== '') {
		$('#search_result').html(html);		// display matches found
	} else {
		$('#search_result').html("No matches");	// no matches found
	}
}
</pre>
</div>

</body>
