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

	<script src="../../js/sweetalert-dev.js"></script>
	<link rel="stylesheet" href="../../css/sweetalert.css">

	<link rel="stylesheet" href="../../css/bootstrap/css/boostrap-iso.css" />

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
      {"name":"m21","display_name":"father","sex":"M","top_level":true},{"name":"f21","display_name":"mother","sex":"F","top_level":true},{"name":"ch1","display_name":"me","sex":"F","mother":"f21","father":"m21","proband":true}
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
				{'type': 'NF1', 'colour': '#F8EF31'},
				{'type': 'LFS', 'colour': '#994D16'},
				{'type': 'breast_cancer', 'colour': '#F68F35'},
				{'type': 'breast_cancer2', 'colour': 'pink'},
				{'type': 'ovarian_cancer', 'colour': '#4DAA4D'},
				{'type': 'pancreatic_cancer', 'colour': '#4289BA'},
				{'type': 'prostate_cancer', 'colour': '#D5494A'},
			],
			labels: ['famid', 'id', 'alleles'],
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
		for(var p=0; p<opts.dataset.length; p++){
		var attr = '';
		$.each(opts.dataset[p], function(key, value) {
		attr += key + ":" + value + "; ";
		});
	}
		html += attr + "<br>";
		//document.getElementById("testinfo").innerHTML = html;
		//document.getElementById("testinfo").innerHTML =
	//	console.log(local_dataset);



function search_pedigree_data(search) {
	var pedigree = pedcache.current(opts);		// current pedigree data

  console.log("search: " + search);
	console.log(pedigree);
	var html = "";

	if (search != "") {
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
}
else {
	swal("Error!", "Please fill the search field!", "error");
}


	if(html !== '') {
		$('#search_result').html(html);
	} else {
		$('#search_result').html("No matches");
	}
}

$('#searchbutton').click(function() {
	search_pedigree_data($('#searchtext').val());
});

$('#searchtext').keypress(function(e){
			if(e.which == 13) // enter key pressed
				search_pedigree_data($('#searchtext').val());
	});

});

	</script>


</head>
<body>

	<?php


	$ip=$_GET['ip'];
	$datesystem=$_GET['datesystem'];
	$email=$_GET['email'];
	$username=$_GET['username'];
	$roles=$_GET['roles'];
	//$demo=$_GET['demo'];

	 // echo $ip." ";
	 // echo $datesystem." ";
	 // echo $email." ";
	 // echo $username." ";
	 // echo $roles." ";

	//echo $_COOKIE["gfgsx"];
//	echo $variable = $_GET['a'];
//echo $variable = "<script>document.write(content)</script>";
	?>

	<div id="ipaddress" style="display: none;"> <?php echo $ip; ?> </div>
	<div id="datesystem" style="display: none;"> <?php echo $datesystem; ?> </div>
	<div id="email" style="display: none;"> <?php echo $email; ?> </div>
	<div id="username" style="display: none;"> <?php echo $username; ?> </div>
	<div id="roles" style="display: none;"> <?php echo $roles; ?> </div>

<div class="text-center">
	<h2>Family Pedigree</h2>
	<br>
	<p id="testinfo"></p>
	<div class="container">
		<div class="row">
			<div class="row">

				<div class="row">
					 <div class="bootstrap-iso">
					 <h4 align="left"><b>Patient Identifier</b></h4>
						<div class="input-group">
						 <span class="input-group-addon glyphicon glyphicon-user"></span>
						 <input type="text" name="patientidsource" placeholder="NF-01-01" id="patientidsource" class="form-control" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
					 </div>
				 </div>
				</div>

			</div>
		<br>
		<br>
		<div class="row">
		<div class="input-group col-md-4">
			<input type="text" class="form-control" placeholder="Search for..." id="searchtext">
		      <span class="input-group-btn">
		        <button class="btn btn-search, e.g. John" type="button" id="searchbutton"><i class="fa fa-search fa-fw"></i> Search</button>
		      </span>
		</div>
		</div>
	</div>

  <br>
	<?php

		if ($roles != "Demo," && $roles != "") {
	 ?>
	<label class="btn btn-search">
		<input id="load" type="button" style="display: none;"/>Load
	</label>

	<label class="btn btn-default btn-file">
		<input id="save" type="button" style="display: none;"/>Save
	</label>
	<?php
	}
	 ?>

	<label class="btn">
		<input id="print" type="button" style="display: none;"/>Print
	</label>
	<label class="btn">
		<input id="svg_download" type="button" style="display: none;"/>SVG
	</label>
	<label class="btn">
		<input id="png_download" type="button" style="display: none;">PNG
	</label>

	<div id='search_result'></div>
	<div id='history_ex8'></div>
	<div id="pedigrees"></div>
	<div id="node_properties"></div>
</div>


<div id="responsecontainer" align="center"></div>


</body>
