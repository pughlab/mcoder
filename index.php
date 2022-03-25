<?php
 ob_start();
 require 'vendor/autoload.php';
 include('configuration/mcode.php');
 include('configuration/map.php');

 function getIp(){
   if(!empty($_SERVER['HTTP_CLIENT_IP'])){
     $ip = $_SERVER['HTTP_CLIENT_IP'];
   }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
   }else{
     $ip = $_SERVER['REMOTE_ADDR'];
   }
   return $ip;
 }
 $ip = getIp();

 ini_set('session.cookie_httponly', 1);
 ini_set('session.use_only_cookies', 1);
 ini_set('session.cookie_secure', 1);

 session_start();
 include('configuration/server.php');

 if (!isset($_SESSION['oauth2state'])) {
     header('Location: login.php');
 }

 $userName = NULL;
 $userMail = NULL;
 $accessToken = NULL;
 $roles = NULL;
 $hasRoleNew = NULL;
 $hasRoleNF1 = NULL;
 $hasRoleDemo = NULL;

 if (isset($_GET['code']) && !(empty($_GET['state']) || ($_GET['state'] !== @$_SESSION['oauth2state']))) {

     // Try to get an access token (using the authorization coe grant)
     try {
         $token = $provider->getAccessToken('authorization_code', [
             'code' => $_GET['code'],
        ]);

         $_SESSION['accessToken'] = $token->getToken();
         $accessToken = $_SESSION['accessToken'];

     } catch (Exception $e) {
         echo "<pre>{$e->getTraceAsString()}</pre><br />";
         exit('Failed to get access token: ' . $e->getMessage());
     }

     // Use a token to look up a users profile data
     try {

         // We got an access token, let's now get the user's details
         $user                 = $provider->getResourceOwner($token);
         $_SESSION['userName'] = $user->getName();
         $_SESSION['userMail'] = $user->getEmail();
         $_SESSION['roles'] = $user->getRoles();

         $userName = $_SESSION['userName'];
         $userMail = $_SESSION['userMail'];
         $roles = $_SESSION['roles'];


        // Retrieve the NF1 users
        $hasRoleNF1 = $user->hasRoleForClient($clientID, "NF1");

        // Retrieve the new users with no roles
        $hasRoleNew = $user->hasRoleForClient($clientID, "New");

        // Retrieve the Demo users
        $hasRoleDemo = $user->hasRoleForClient($clientID, "Demo");

        // Retrieve all user groups as s list
         $groups = "";
         foreach($roles[$clientID] as $group) {
           foreach($group as $role) {
             $groups .=  $role .",";

           }
         }

        ?>

        <!DOCTYPE html>
        <html lang="en" >
           <head>
              <meta charset="UTF-8">
              <title>mCODER</title>
              <style>
              </style>
              <link rel="stylesheet" type="text/css" href="css/modal.css">
              <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
              <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
              <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
              <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
              <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

              <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
              <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
              <link rel="stylesheet" href="css/bootstrap/css/boostrap-iso.css" />
              <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
              <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
              <meta name="viewport" content="width=device-width,initial-scale=1">
              <link rel="stylesheet" type="text/css" media="screen" href="css/menu.css" />
              <link rel="stylesheet" type="text/css" media="screen" href="css/form.css" />
              <link rel="stylesheet" type="text/css" href="css/jquerysctipttop.css">
              <link rel="stylesheet" type="text/css" href="jquery-ui-1.12.1.custom/jquery-ui.css">
              <script src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
              <script src="js/prefixfree.min.js"></script>
              <script src="js/tabs.js"></script>
              <link rel="stylesheet" href="css/usermenu.css" /> 
              <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
              <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api; ?>&libraries=places"></script>
              <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css" rel="stylesheet" type="text/css">
              <link rel="stylesheet" href="date/css/datepicker.css">
              <script src="js/sweetalert-dev.js"></script>
              <link rel="stylesheet" href="css/sweetalert.css">
              <script src='https://kit.fontawesome.com/a076d05399.js'></script>
              <script src="js/bootstrap/js/bootstrap-input-spinner.js"></script>

              <style>
                 fieldset.coll {
                 border: 1px groove #DEE2DD !important;
                 padding: 0 1.4em 1.4em 1.4em !important;
                 margin: 0 0 1.5em 0 !important;
                 }
                 body { font-family: 'Roboto Condensed'; background: #fafafa; }
              </style>

           </head>
           <body>
              <div id="ipaddress" style="display: none;"> <?php echo $ip; ?> </div>
              <div id="datesystem" style="display: none;"> <?php date_default_timezone_set('America/Toronto'); echo date("Y-m-d-H:i:s"); ?> </div>
              <div id="email" style="display: none;"> <?php echo $userMail; ?> </div>
              <div id="username" style="display: none;"> <?php echo $username; ?> </div>
              <div id="roles" style="display: none;"> <?php echo $groups; ?> </div>

              <script>
                 // Show other race
                 function otherRaceShow() {
                   var x = document.getElementById("addotherRace");
                   var y = document.getElementById("other");
                   if (y.checked == true) {
                     x.style.display = "block";
                   }
                   else {
                     x.style.display = "none";
                   }
                 }

                 // Hide other race
                 function otherRaceHide() {
                   var x = document.getElementById("addotherRace");
                   if (x.style.display === "block") {
                     x.style.display = "none";
                   }
                 }

                 // Show other commorbidities
                 function showDiv(divId, element)
                 {
                     document.getElementById(divId).style.display = element.value == "other" ? 'block' : 'none';
                 }

                 // NF1 clinical features
                 function clinShow() {
                   var x = document.getElementById("showClinFeatures");
                   if (x.style.display === "none") {
                     x.style.display = "block";
                   }
                 }


                 function clinHide() {
                   var x = document.getElementById("showClinFeatures");
                   if (x.style.display === "block") {
                     x.style.display = "none";
                   }
                 }

                 // NF1 family
                 function familyShow() {
                   var x = document.getElementById("showfamily");
                   if (x.style.display === "none") {
                     x.style.display = "block";
                   }
                 }


                 function familyHide() {
                   var x = document.getElementById("showfamily");
                   if (x.style.display === "block") {
                     x.style.display = "none";
                     $(this).closest('div').remove();
                   }
                 }

                 // Update patient id at the top of the form
                 $(document).ready(function(){
                     $("#patientidsource").on("input", function(){
                         // Print entered value in a div box
                         $("#patientidresult1").text($(this).val());
                         $("#patientidresult2").text($(this).val());
                         $("#patientidresult3").text($(this).val());
                         $("#patientidresult4").text($(this).val());
                         $("#patientidresult5").text($(this).val());
                         $("#patientidresult6").text($(this).val());
                     });
                 });

                 // Google map googleapis
                 function initialize() {
                   var input = document.getElementById('zip');
                   //var autocomplete = new google.maps.places.Autocomplete(input);
                   var autocomplete = new google.maps.places.Autocomplete(
                           /** @type {!HTMLInputElement} */(document.getElementById('zip')),
                           {types: ['(regions)'], componentRestrictions: {'country': "ca"}});
                 }
                 google.maps.event.addDomListener(window, 'load', initialize);

                 // variant autocomplete
                 $(document).ready(function()
                 {
                   $('#hgvst').autocomplete(
                   {
                     source: "autocompletion/searchhgvs.php",
                     minLength: 1
                   });

                   $('#testf').autocomplete(
                   {
                     source: "autocompletion/searchtest.php",
                     minLength: 1
                   });

                   $('#testcodef').autocomplete(
                   {
                     source: "autocompletion/searchtestcode.php",
                     minLength: 1
                   });

                   $('#locationf').autocomplete(
                   {
                     source: "autocompletion/searchbody.php",
                     minLength: 1
                   });

                 });

                 // Set the patient ID to inherit from the other tabs
                 function updateID() {
                 //Patient
                 document.getElementById("patientidsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;}

                 //Disease
                 document.getElementById("diseaseidsource").onkeyup=function() {
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;}

                 //Genomic
                 document.getElementById("genomicidsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;}

                 //Treatment
                 document.getElementById("treatmentidsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;}


                 //Outcome
                 document.getElementById("outcomeidsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;}

                 //Lab
                 document.getElementById("labidsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;}

                 //Biospecimens
                 document.getElementById("biospecimensidsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;}


                 //Pedigree
                 document.getElementById("pedigreeidsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;
                 document.getElementById("nf1idsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;}

                 //NF1
                 document.getElementById("nf1idsource").onkeyup=function() {
                 document.getElementById("diseaseidsource").value=this.value;
                 document.getElementById("genomicidsource").value=this.value;
                 document.getElementById("treatmentidsource").value=this.value;
                 document.getElementById("outcomeidsource").value=this.value;
                 document.getElementById("patientidsource").value=this.value;
                 document.getElementById("pedigreeidsource").value=this.value;
                 document.getElementById("labidsource").value=this.value;
                 document.getElementById("biospecimensidsource").value=this.value;}

               }

              </script>

<div style="background-color: #f1f1f1; height:115px;border: thin solid lightgray;">
      <div class="logomcoder" style="background-color: #E7E7E6;height:113px;" >
        <img src="logo.png" alt="mcoderlogo" style="width:130px;max-width:130px;">
      </div>


      <div style="margin-left:100px">
        <strong><?php date_default_timezone_set('America/Toronto'); echo date("F j, Y, g:i a"); ?></strong>
      </div>

<div style="float: right;margin-left:1px">
<a href='https://app.asana.com/0/1187650412767761/1187650412767761https://app.asana.com/0/1187650412767761/1187650412767761' target="_blank">
      <div class='panel noselect' style="float:right;margin-right:10px">
       <div class='admin-panel'>
         <label class='text' for='bugs'><?php echo "Report a bug"; ?></label>

         <label class='fas fa-bug' for='bugs'></label>
       </div>
      </div>
</a>


      <div class='panel noselect' style="float:left;margin-right:10px">
       <div class='admin-panel'>
         <label class='text' for='toggle'><?php echo $userMail; ?></label>
         <label class='fas fa-cog' for='toggle'></label>
       </div>
       <input type='checkbox' id='toggle' checked="no">
       <div class='menu-panel'>
         <a href='logout.php' class='row'>
           <div class='column-left'>Sign out</div>
           <div class='column-right'><i class='fas fa-sign-out-alt'></i></div>
         </a>
      </div>
      </div>

</div>



    </div>

    <br>

            <?php if($hasRoleNew == 1) { ?>
            <div class="infoaccess" style="position: absolute;" >
            <a href="#info-account-mcoder" class="link-1" id="newuser-closed">Access to mCODER</a>

            <div class="newuser-container" id="info-account-mcoder">
              <div class="newuser">

                <div class="newuser__details">
                  <h1 class="newuser__title">Access to mCODER</h1>
                  <p class="newuser__description">This message concerns information about your account.</p>
                </div>

                <p class="newuser__text">You don't have access to any studies.<br> Please email us at <a style="color: #FFFFFF; text-decoration: none" href="mailto:mcoder@uhn.ca"><u>mcoder@uhn.ca</u></a> and specify which studies you are working on.</p>

                <a href="#" class="link-2"></a>

              </div>
            </div>

            </div>
            <?php } ?>


              <?php if($hasRoleNew == 0) { ?>
              <!-- TAB TEMPLATE -->
              <svg style="position: absolute; margin-left: -200%; fill:transparent; stroke: none" >
                 <polygon id="tab-shape-left" class="tab-shape tab-shape-left" points="900,30 0,30 10,0 900,0 " />
                 <polygon id="tab-shape-right" class="tab-shape tab-shape-right" points="20,30 0,30 0,0 " />
              </svg>


              <div class="box">
                 <nav role="navigation" class="main-navigation">

                    <ul id="tabs">


                       <li class="tab-1 active" data-bg-color="hsl(180,100%,30%)">
                          <a href="#patient">
                             <span>Patient</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                       <li class="tab-6" data-bg-color="hsl(30,100%,40%)">
                          <a href="#disease">
                             <span>Disease</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                       <li class="tab-3" data-bg-color="hsl(300, 100%, 40%)">
                          <a href="#outcome">
                             <span>Outcome</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                       <li class="tab-5" data-bg-color="hsl(120,100%,30%)">
                          <a href="#genomics">
                             <span>Genomic</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                       <li class="tab-2" data-bg-color="hsl(58, 94%, 48%)">
                          <a href="#treatment">
                             <span>Treatment</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                       <li class="tab-4" data-bg-color="hsl(210,100%,50%)">
                          <a href="#labvital">
                             <span>Lab/Vital</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                       <li class="tab-7" data-bg-color="hsl(262,100%,50%)">
                          <a href="#biospecimens">
                             <span>Biospecimens</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                       <li class="tab-8" data-bg-color="hsl(352,96%,47%)">
                          <a href="#family">
                             <span>Family pedigree</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>


                       <?php if($hasRoleNF1 == 1) { ?>
                       <li class="tab-9" data-bg-color="hsl(20,80%,43%)">
                          <a href="#nf1">
                             <span>NF1 Clinical Features</span>
                             <svg class="tab-left">
                                <use xlink:href="#tab-shape-left"></use>
                             </svg>
                             <svg class="tab-right">
                                <use xlink:href="#tab-shape-right"></use>
                             </svg>
                          </a>
                       </li>
                     <?php } ?>

                    </ul>
                 </nav>
                 <div class="main-content">
                    <article class="article">
                       <ul id="tab" >
                       <!-- ########## PATIENT ########## -->
                       <li class="tab-1 active">
                          <!--  <div id="patientidresult1" align="center" STYLE="font-size:200%;font-weight:bold;font-family:sans-serif";></div>-->
                          <div class="containers">
                             <form>
                               <div class="row">
                                  <h4>Patient Identifier</h4>
                                  <div class="row">
                                     <div class="bootstrap-iso">
                                      <div class="input-group">
                                       <span class="input-group-addon glyphicon glyphicon-user"></span>
                                       <input type="text" name="patientidsource" placeholder="NF-01-01" id="patientidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                                     </div>
                                   </div>
                                  </div>
                               </div>
                               <br>
                               <div class="row">
                                  <h4>Study</h4>
                                  <div class="input-group">
                                     <input class="greenradio33" type="radio" name="study" value="CHARM" id="study-charm"  />
                                     <label for="study-charm">CHARM</label>
                                     <input class="greenradio33" type="radio" name="study" value="LFS" id="study-lfs" />
                                     <label for="study-lfs">LFS</label>
                                     <input class="greenradio33" type="radio" name="study" value="NF1" id="study-nf1" />
                                     <label for="study-nf1">NF1</label>
                                  </div>
                               </div>
                               <br>
                             <fieldset class="coll">
                                <legend>
                                   <h4>General information</h4>
                                </legend>
                                <br>
                                <div class="bootstrap-iso" id="listpatient"></div>
                                <br>
                                <div class="row">
                                   <div class="col-third">
                                      <h4>Date of birth</h4>
                                      <div class="c-datepicker-date-editor c-datepicker-single-editor J-yearMonthPicker-single" style="height: 42.5px; width:100%;">
                                        <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                         <input id="birthday" type="text" autocomplete="off" name="birthday" placeholder="YYYY-MM" class="c-datepicker-data-input" value="" >
                                      </div>
                                   </div>
                                   <div class="col-2third">
                                      <h4>Gender</h4>
                                      <div class="input-group">
                                         <input class="greenradio25" type="radio" name="gender" value="Male" id="gender-male"  />
                                         <label for="gender-male">Male</label>
                                         <input class="greenradio25" type="radio" name="gender" value="Female" id="gender-female" />
                                         <label for="gender-female">Female</label>
                                         <input class="greenradio25" type="radio" name="gender" value="Other" id="gender-other" />
                                         <label for="gender-other">Other</label>
                                         <input class="greenradio25" type="radio" name="gender" value="Unknown" id="gender-unknown" />
                                         <label for="gender-unknown">Unknown</label>
                                      </div>
                                   </div>
                                </div>
                                <br>
                                <div class="row">
                                   <h4>Race</h4>
                                   <div class="input-group">
                                      <input class="greenradio33" type="radio" name="race" value="White" id="white" onclick="otherRaceHide()"/>
                                      <label for="white">White</label>
                                      <input class="greenradio33" type="radio" name="race" value="Asian" id="asian" onclick="otherRaceHide()"/>
                                      <label for="asian">Asian</label>
                                      <input class="greenradio33" type="radio" name="race" value="Black-African" id="black" onclick="otherRaceHide()"/>
                                      <label for="black">Black or African</label>
                                      <input class="greenradio33" type="radio" name="race" value="Filipino" id="filipino" onclick="otherRaceHide()"/>
                                      <label for="filipino">Filipino</label>
                                      <input class="greenradio33" type="radio" name="race" value="Latin-American" id="latin" onclick="otherRaceHide()"/>
                                      <label for="latin">Latin American</label>
                                      <input class="greenradio33" type="radio" name="race" value="Other" id="other" onclick="otherRaceShow()"/>
                                      <label for="other">Other</label>
                                   </div>
                                   <div class="row" id="addotherRace" name="addotherRace" style="display:none">
                                      <h4>Specify</h4>
                                      <input type="text" placeholder="Any other race" id="otherRace" name="otherRace"/>
                                   </div>
                                </div>
                                <br>
                                <div class="row">
                                   <div class="col-third">
                                      <h4>Recruitment location: City or Postal/Zip code</h4>
                                      <div class="row">
                                         <div class="bootstrap-iso">
                                            <input class="autotypeahead" type="text" placeholder="T0J 0A0" id="zip" name="zip"/>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="col-2third">
                                      <h4>Institution</h4>
                                      <div class="bootstrap-iso">
                                         <select class="selectpicker show-tick" name="institution" id="institution" data-width="100%">
                                            <option name="institution" value="NA" >NA</option>
                                            <option name="institution" value="UHN" >University Health Network</option>
                                            <option name="institution" value="Sickkids" >The Hospital for Sick Children</option>
                                            <option name="institution" value="Sinai" >Sinai Health (or Mount Sinai Hospital)</option>
                                            <option name="institution" value="Womenhosp" >Women's College Hospital</option>
                                            <option name="institution" value="BCCA" >British Columbia Cancer Agency</option>
                                            <option name="institution" value="JGH" >Jewish General Hospital</option>
                                            <option name="institution" value="EH" >Eastern Health</option>
                                            <option name="institution" value="IHC" >IWK Health Centre</option>
                                         </select>
                                      </div>
                                   </div>
                                </div>
                                <br>

                                <div class="row">
                                   <h4>Family ID</h4>
                                   <input type="text" placeholder="Optional" id="family" name="family"/>
                                </div>
                                <br>
                                <!-- for US deployment
                                   <div class="row">
                                      <h4>Ethnicity</h4>
                                      <div class="input-group">
                                         <input class="greenradio33" type="radio" name="ethnicity" value="european" id="european"/>
                                         <label for="european">European</label>
                                         <input class="greenradio33" type="radio" name="ethnicity" value="non-european" id="non-european"/>
                                         <label for="non-european">Non European</label>
                                         <input class="greenradio33" type="radio" name="ethnicity" value="na" id="na" />
                                         <label for="na">NA</label>
                                      </div>
                                   </div>
                                   <br> -->
                                <div class="row">
                                  <div class="bootstrap-iso" align="center">
                                    <button type="button" class="btn btn-primary btn-lg" id="savepatient"><span class="fas fa-head-side-cough"></span> Add patient</button>
                                  </div>
                                </div>
                              </fieldset>
                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>Comorbid condition</h4>
                                   </legend>
                                   <br>
                                      <div class="bootstrap-iso" id="listcomorbid"></div>
                                      <br>
                                               <div class="row">
                                                  <div class="row">
                                                     <div class="col-third">
                                                        <h4>Date of evaluation</h4>
                                                        <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 43px; width:100%;">
                                                           <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                                           <input type="text" autocomplete="off" name="comdate" id="comdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                                        </div>
                                                     </div>
                                                     <div class="col-2third">
                                                        <h4>Comorbid condition code</h4>
                                                        <div>
                                                           <div class="bootstrap-iso">
                                                              <input class="autotypeaheadfirst" type="text" placeholder="Tuberculosis of spine" id="comorbid" name="comorbid" style="height: 43px;width:100%;">
                                                           </div>
                                                        </div>
                                                     </div>
                                                  </div>
                                                  <br>
                                                  <!-- <div class="row" id="otherCommor" name="otherCommor[]" style="display:none">
                                                     <h4>Specify</h4>
                                                     <input type="text" placeholder="Any other commorbidities"/>
                                                     <br><br>
                                                  </div> -->
                                                  <div class="row">
                                                     <h4>Condition clinical status</h4>
                                                     <div class="input-group">
                                                        <input class="greenradio33 cl1" type="radio" name="cl" value="Active" id="cl1_0"  />
                                                        <label for="cl1_0">Active</label>
                                                        <input class="greenradio33 cl2" type="radio" name="cl" value="Recurrence" id="cl2_0"/>
                                                        <label for="cl2_0">Recurrence</label>
                                                        <input class="greenradio33 cl3" type="radio" name="cl" value="Relapse" id="cl3_0"/>
                                                        <label for="cl3_0">Relapse</label>
                                                        <input class="greenradio33 cl4" type="radio" name="cl" value="Inactive" id="cl4_0"/>
                                                        <label for="cl4_0">Inactive</label>
                                                        <input class="greenradio33 cl5" type="radio" name="cl" value="Remission" id="cl5_0"/>
                                                        <label for="cl5_0">Remission</label>
                                                        <input class="greenradio33 cl6" type="radio" name="cl" value="Resolved" id="cl6_0"/>
                                                        <label for="cl6_0">Resolved</label>
                                                     </div>
                                                  </div>

                                                  <div class="row">
                                                    <div class="bootstrap-iso">
                                                      <div class="md-form">
                                                        <i class="fas fa-pencil-alt prefix"> Comments</i>
                                                        <textarea id="comorbidcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <br>
                                                  <div class="repeater-heading" align="center">
                                                     <div class="bootstrap-iso">
                                                       <button type="button" class="btn btn-primary btn-lg" id="savecomorbid"><i class='fas fa-lungs-virus'></i> Add a comorbid condition</button>
                                                     </div>
                                                  </div>
                                               </div>
                                </fieldset>
                                <br>

                                <fieldset class="coll">
                                   <legend>
                                      <h4>General clinical evaluation</h4>
                                   </legend>
                                   <br>
                                   <div class="bootstrap-iso" id="liststatus"></div>
                                   <br>
                                            <div class="row">
                                              <div class="row">
                                                <h4>Date of evaluation</h4>
                                                <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:100%;">
                                                   <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                                   <input type="text" autocomplete="off" name="statusdate" id="statusdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                                </div>
                                              </div>
                                              <br>
                                              <div class="row">
                                                 <div class="col-half">
                                                    <h4>ECOG performance status</h4>
                                                    <div class="bootstrap-iso">
                                                       <select class="selectpicker show-tick" name="ecog" id="ecog" data-width="99%">
                                                          <option name="ecog" value="ecogNA">NA</option>
                                                          <option name="ecog" value="ecog0">ECOG Grade 0: Asymptomatic</option>
                                                          <option name="ecog" value="ecog1">ECOG Grade 1: Symptomatic but completely ambulatory</option>
                                                          <option name="ecog" value="ecog2">ECOG Grade 2: Symptomatic, less than 50% in bed during the day</option>
                                                          <option name="ecog" value="ecog3">ECOG Grade 3: Symptomatic, more than 50% in bed, but not bedbound</option>
                                                          <option name="ecog" value="ecog4">ECOG Grade 4: Bedbound</option>
                                                          <option name="ecog" value="ecog5">ECOG Grade 5: Death</option>
                                                       </select>
                                                    </div>
                                                 </div>
                                                 <div class="col-half">
                                                    <h4>Karnofsky performance status</h4>
                                                    <div class="bootstrap-iso">
                                                       <select class="selectpicker show-tick" name ="karnofsky" id="karnofsky" data-width="100%">
                                                          <option name ="karnofsky" value="kNA">NA</option>
                                                          <option name ="karnofsky" value="k100">KPS 100: Normal; no complaints; no evidence of disease</option>
                                                          <option name ="karnofsky" value="k90">KPS 90: Able to carry on normal activity; minor signs or symptoms of disease</option>
                                                          <option name ="karnofsky" value="k80">KPS 80: Normal activity with effort; some signs or symptoms of disease</option>
                                                          <option name ="karnofsky" value="k70">KPS 70: Cares for self; unable to carry on normal activity or do active work</option>
                                                          <option name ="karnofsky" value="k60">KPS 60: Requires occasional assistance but is able to care for most needs</option>
                                                          <option name ="karnofsky" value="k50">KPS 50: Requires considerable assistance and frequent medical care</option>
                                                          <option name ="karnofsky" value="k40">KPS 40: Disabled; requires special care and assistance</option>
                                                          <option name ="karnofsky" value="k30">KPS 30: Severely disabled; hospitalization is indicated,  although death not imminent</option>
                                                          <option name ="karnofsky" value="k20">KPS 20: Very sick; hospitalization necessary; active supportive treatment necessary</option>
                                                          <option name ="karnofsky" value="k10">KPS 10: Moribund; fatal processes progressing rapidly</option>
                                                          <option name ="karnofsky" value="k0">KPS 0: Dead</option>
                                                       </select>
                                                    </div>
                                                 </div>
                                              </div>
                                              <br>
                                              <div class="row">
                                                <div class="bootstrap-iso">
                                                  <div class="md-form">
                                                    <i class="fas fa-pencil-alt prefix"> Comments</i>
                                                    <textarea id="statuscom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                                  </div>
                                                </div>
                                              </div>
                                              <br>
                                              <div class="repeater-heading" align="center">
                                                 <div class="bootstrap-iso">
                                                   <button type="button" class="btn btn-primary btn-lg" id="savestatus"><i class='fas fa-stethoscope'></i> Add a clinical evaluation</button>
                                                 </div>
                                              </div>
                                            </div>
                                </fieldset>
                             </form>
                          </div>
                       </li>
                       <!-- ########## DISEASE ########## -->
                       <li class="tab-6">
                          <!--  <div id="patientidresult2" align="center" STYLE="font-size:200%;font-weight:bold;font-family:sans-serif";></div> -->
                          <div class="row">
                             <h4>Patient Identifier</h4>
                             <div class="row">
                                <div class="bootstrap-iso">
                                 <div class="input-group">
                                  <span class="input-group-addon glyphicon glyphicon-user"></span>
                                  <input type="text" name="diseaseidsource" placeholder="NF-01-01" id="diseaseidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                                </div>
                              </div>
                             </div>
                          </div>
                          <br>

                          <div class="box">
                            <form>
                               <fieldset class="coll">
                                  <legend>
                                     <h4>Tumor data</h4>
                                  </legend>
                                  <br>
                                  <div class="bootstrap-iso" id="listdiseases"></div>
                                  <br>
                                  <div class="row">
                                     <h4>Date of diagnosis</h4>
                                     <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                        <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                        <input type="text" autocomplete="off" name="cancerdate" id="cancerdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                     </div>
                                  </div>
                                  <br>
                             <div class="row">
                                <h4>Tumor type</h4>
                                <div class="row">
                                   <input class="orangeradio" type="radio" name="cancer_type" value="primary" id="primary" />
                                   <label for="primary">Primary</label>
                                   <input class="orangeradio" type="radio" name="cancer_type" value="secondary" id="secondary"/>
                                   <label for="secondary">Secondary</label>
                                   <input class="orangeradio" type="radio" name="cancer_type" value="neoplasm" id="neoplasm" />
                                   <label for="neoplasm">Neoplasm of unspecified behavior</label>
                                   <input class="orangeradio" type="radio" name="cancer_type" value="benign" id="benign"/>
                                   <label for="benign">Benign</label>
                                   <input class="orangeradio" type="radio" name="cancer_type" value="unknown" id="unknown"/>
                                   <label for="unknown">Unknown</label>
                                   <input class="orangeradio" type="radio" name="cancer_type" value="na" id="na"/>
                                   <label for="na">Not provided</label>
                                </div>
                             </div>
                             <br>
                             <div class="row">
                                <h4>Tumor histology</h4>
                                <div class="row">
                                   <input class="orangeradio" type="radio" name="cancer_histology" value="biopsy" id="histbiopsy" />
                                   <label for="histbiopsy">Biopsy</label>
                                   <input class="orangeradio" type="radio" name="cancer_histology" value="surgery" id="histsurgery"/>
                                   <label for="histsurgery">Surgery</label>
                                   <input class="orangeradio" type="radio" name="cancer_histology" value="unknown" id="histunknown" />
                                   <label for="histunknown">Unknown</label>
                                </div>
                             </div>
                             <br>
                             <div class="row">
                                <h4>Clinical status</h4>
                                <div class="input-group">
                                   <input class="orangeradio" type="radio" name="cl_disease" value="Active" id="active_p" />
                                   <label for="active_p">Active</label>
                                   <input class="orangeradio" type="radio" name="cl_disease" value="Recurrence" id="recurrence_p"/>
                                   <label for="recurrence_p">Recurrence</label>
                                   <input class="orangeradio" type="radio" name="cl_disease" value="Relapse" id="relapse_p"/>
                                   <label for="relapse_p">Relapse</label>
                                   <input class="orangeradio" type="radio" name="cl_disease" value="Inactive" id="inactive_p"/>
                                   <label for="inactive_p">Inactive</label>
                                   <input class="orangeradio" type="radio" name="cl_disease" value="Remission" id="remission_p"/>
                                   <label for="remission_p">Remission</label>
                                   <input class="orangeradio" type="radio" name="cl_disease" value="Resolved" id="resolved_p"/>
                                   <label for="resolved_p">Resolved</label>
                                </div>
                             </div>
                             <br>
                             <div class="row">
                               <div class="col-half">
                                 <h4>Body location code</h4>
                                 <div>
                                    <div class="bootstrap-iso">
                                       <input class="autotypeaheadfirst" type="text" placeholder="Lip" name="location" id="location" style="height: 43px;">
                                    </div>
                                 </div>
                               </div>
                               <div class="col-half">
                                  <h4>Body location side</h4>
                                  <div class="row">
                                     <input class="orangeradio" type="radio" name="location_side" value="Right" id="right" />
                                     <label for="right">Right</label>
                                     <input class="orangeradio" type="radio" name="location_side" value="Left" id="left" />
                                     <label for="left">Left</label>
                                     <input class="orangeradio" type="radio" name="location_side" value="Bilateral" id="bilateral" />
                                     <label for="bilateral">Bilateral</label>
                                  </div>
                               </div>
                             </div>
                             <br>
                             <div class="row">
                                <h4>OncoTree code</h4>
                                <div >
                                   <div class="bootstrap-iso">
                                      <input class="autotypeahead" id="info" type="text" placeholder="Breast"/>
                                   </div>
                                </div>
                             </div>
                             <br>
                             <div class="row" align="center">
                                <button class="onco" style="vertical-align:middle" onclick="openChild(); return false;"><span>Open the OncoTree</span></button>
                             </div>
                             <br>
                             <!--  <div class="row">
                                <div class="col-half">
                                    <h4>Condition code</h4>

                                    <?php
                                   $query = "SELECT distinct code FROM condition_primary";
                                   $result = $mysqli->query($query);
                                   ?>

                                    <div class="bootstrap-iso">
                                    <select class="selectpicker show-tick" name="condition_primary" id="condition_primary" data-width="99%">

                                      <?php
                                   while($row = $result->fetch_assoc())
                                   {
                                     //echo $row['code'];
                                   ?>

                                        <option name="condition_primary" value="<?php  //echo $row['code']; ?>" ><?php //echo $row['code']; ?></option>

                                      <?php
                                   }
                                   ?>

                                    </select>
                                  </div>
                                </div>

                                <div class="col-half">
                                     <h4>Histology/Morphology</h4>
                                     <?php
                                   $query = "SELECT distinct name FROM histology";
                                   $result = $mysqli->query($query);
                                   ?>

                                     <div class="bootstrap-iso">
                                     <select class="selectpicker show-tick" name="histology" id="histology" data-width="100%">

                                       <?php
                                   while($row = $result->fetch_assoc())
                                   {
                                     //echo $row['name'];
                                   ?>

                                         <option name="histology" value="<?php  //echo $row['name']; ?>" ><?php //echo $row['name']; ?></option>

                                       <?php
                                   }
                                   ?>

                                     </select>
                                   </div>
                                </div>

                                </div> -->
                             <br>
                             <div class="row">
                                <div class="col-half">
                                   <h4>Clinical stage group</h4>
                                   <div class="bootstrap-iso">
                                      <select class="selectpicker show-tick" name="stagegp" id="stagegp" data-width="99%">
                                         <option name="stagegp" value="NA">NA</option>
                                         <option name="stagegp" value="0">0</option>
                                         <option name="stagegp" value="I">I</option>
                                         <option name="stagegp" value="II">II</option>
                                         <option name="stagegp" value="IIA">IIA</option>
                                         <option name="stagegp" value="IIB">IIB</option>
                                         <option name="stagegp" value="IIC">IIC</option>
                                         <option name="stagegp" value="III">III</option>
                                         <option name="stagegp" value="IIIA">IIIA</option>
                                         <option name="stagegp" value="IIIB">IIIB</option>
                                         <option name="stagegp" value="IIIC">IIIC</option>
                                         <option name="stagegp" value="IV">IV</option>
                                         <option name="stagegp" value="IVA">IVA</option>
                                         <option name="stagegp" value="IVB">IVB</option>
                                         <option name="stagegp" value="IVC">IVC</option>
                                      </select>
                                   </div>
                                </div>
                                <div class="col-half">
                                   <h4>Clinical stage system</h4>
                                   <div class="bootstrap-iso">
                                      <select class="selectpicker show-tick" name="stages" id="stages" data-width="100%">
                                         <option name="stages" value="NA">NA</option>
                                         <option name="stages" value="stages0">International Union Against Cancer</option>
                                         <option name="stages" value="stagesI">American Joint Commission on Cancer,6th edition</option>
                                         <option name="stages" value="stagesII">American Joint Commission on Cancer,7th edition</option>
                                         <option name="stages" value="stagesII">American Joint Commission on Cancer,8th edition</option>
                                      </select>
                                   </div>
                                </div>
                             </div>
                             <br>
                             <div class="row">
                                <div class="col-half">
                                   <h4>Pathologic stage group</h4>
                                   <div class="bootstrap-iso">
                                      <select class="selectpicker show-tick" name="pstagegp" id="pstagegp" data-width="99%">
                                        <option name="pstagegp" value="NA">NA</option>
                                        <option name="pstagegp" value="0">0</option>
                                        <option name="pstagegp" value="I">I</option>
                                        <option name="pstagegp" value="II">II</option>
                                        <option name="pstagegp" value="IIA">IIA</option>
                                        <option name="pstagegp" value="IIB">IIB</option>
                                        <option name="pstagegp" value="IIC">IIC</option>
                                        <option name="pstagegp" value="III">III</option>
                                        <option name="pstagegp" value="IIIA">IIIA</option>
                                        <option name="pstagegp" value="IIIB">IIIB</option>
                                        <option name="pstagegp" value="IIIC">IIIC</option>
                                        <option name="pstagegp" value="IV">IV</option>
                                        <option name="pstagegp" value="IVA">IVA</option>
                                        <option name="pstagegp" value="IVB">IVB</option>
                                        <option name="pstagegp" value="IVC">IVC</option>
                                      </select>
                                   </div>
                                </div>
                                <div class="col-half">
                                   <h4>Pathologic stage system</h4>
                                   <div class="bootstrap-iso">
                                      <select class="selectpicker show-tick" name="pstages" id="pstages" data-width="100%">
                                         <option name="pstages" value="stagesNA">NA</option>
                                         <option name="pstages" value="stages0">International Union Against Cancer</option>
                                         <option name="pstages" value="stagesI">American Joint Commission on Cancer,6th edition</option>
                                         <option name="pstages" value="stagesII">American Joint Commission on Cancer,7th edition</option>
                                         <option name="pstages" value="stagesII">American Joint Commission on Cancer,8th edition</option>
                                      </select>
                                   </div>
                                </div>
                             </div>
                             <br>
                             <div class="row">
                               <div class="bootstrap-iso">
                                 <div class="md-form">
                                   <i class="fas fa-pencil-alt prefix"> Comments</i>
                                   <textarea id="cancercom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                 </div>
                               </div>
                             </div>
                             <br>
                             <div class="repeater-heading" align="center">
                                <div class="bootstrap-iso">
                                  <button type="button" class="btn btn-primary btn-lg" id="savecancer"><i class="fas fa-ribbon"></i> Add cancer</button>
                                </div>
                             </div>
                           </fieldset>
                             </form>
                          </div>
                       </li>

                       <!-- ########## OUTCOME ########## -->
                       <li class="tab-3">
                         <div class="row">
                            <h4>Patient Identifier</h4>
                            <div class="row">
                               <div class="bootstrap-iso">
                                <div class="input-group">
                                 <span class="input-group-addon glyphicon glyphicon-user"></span>
                                 <input type="text" name="outcomeidsource" placeholder="NF-01-01" id="outcomeidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                               </div>
                             </div>
                            </div>
                         </div>
                         <br>
                          <div class="containers">
                             <form>
                               <fieldset class="coll">
                                  <legend>
                                     <h4>Cancer disease status</h4>
                                  </legend>
                                  <br>
                                     <div class="bootstrap-iso" id="listoutcome"></div>
                                  <br>
                                  <div class="row">
                                     <h4>Date of evaluation</h4>
                                     <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:100%;">
                                        <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                        <input type="text" autocomplete="off" name="outcomedate" id="outcomedate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                     </div>
                                  </div>
                                  <br>
                                  <div class="row">
                                     <h4>Disease status</h4>
                                     <div class="input-group">
                                        <input class="pinkradio" type="radio" name="cancerstatus" value="Equivocal" id="equivocal" />
                                        <label for="equivocal">Equivocal</label>
                                        <input class="pinkradio" type="radio" name="cancerstatus" value="Stable" id="stable" />
                                        <label for="stable">Stable</label>
                                        <input class="pinkradio" type="radio" name="cancerstatus" value="Indeterminate" id="indeterminate"/>
                                        <label for="indeterminate">Indeterminate</label>
                                        <input class="pinkradio" type="radio" name="cancerstatus" value="Worsening" id="worsening"/>
                                        <label for="worsening">Worsening</label>
                                        <input class="pinkradio" type="radio" name="cancerstatus" value="Notdetected" id="notdetected" />
                                        <label for="notdetected">Not detected</label>
                                        <input class="pinkradio" type="radio" name="cancerstatus" value="Improving" id="improving" />
                                        <label for="improving">Improving</label>
                                     </div>
                                  </div>
                                  <div class="row">
                                    <div class="bootstrap-iso">
                                      <div class="md-form">
                                        <i class="fas fa-pencil-alt prefix"> Comments</i>
                                        <textarea id="cancerstatuscom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                  <br>
                                  <div class="repeater-heading" align="center">
                                     <div class="bootstrap-iso">
                                       <button type="button" class="btn btn-primary btn-lg" id="saveoutcome"><i class='fa fa-medkit'></i> Add a cancer disease status</button>
                                     </div>
                                  </div>
                                </fieldset>
                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>Death</h4>
                                   </legend>
                                   <br>
                                      <div class="bootstrap-iso" id="listdeath"></div>
                                   <br>
                                   <div class="row">
                                      <h4>Date</h4>
                                      <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:100%;">
                                         <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                         <input type="text" autocomplete="off" name="death" id="death" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                      </div>
                                   </div>
                                   <br>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="deathcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="savedeath"><i class="fas fa-book-dead"></i> Add the death date</button>
                                      </div>
                                   </div>

                                 </fieldset>

                             </form>
                          </div>
                       </li>

                       <!-- ########## GENOMICS ########## -->
                       <li class="tab-5">
                         <div class="row">
                            <h4>Patient Identifier</h4>
                            <div class="row">
                               <div class="bootstrap-iso">
                                <div class="input-group">
                                 <span class="input-group-addon glyphicon glyphicon-user"></span>
                                 <input type="text" name="genomicidsource" placeholder="NF-01-01" id="genomicidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                               </div>
                             </div>
                            </div>
                         </div>
                          <br>
                          <div class="box">
                            <form>
                               <!-- <fieldset class="coll">
                                  <legend>
                                     <h4>Genetic mutation test</h4>
                                  </legend>
                                  <br>
                                  <div class="bootstrap-iso" id="listmutations"></div>
                                  <br>
                                  <div class="row">
                                     <div class="col-half">
                                        <h4>Date</h4>
                                        <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                           <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                           <input type="text" autocomplete="off" name="mutationdate" id="mutationdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                        </div>
                                     </div>
                                     <div class="col-half">
                                       <h4>Test name</h4>
                                       <div class="row">
                                          <div class="bootstrap-iso" >
                                             <input autocomplete="off" class="autotypeahead" id="testmutations" name="testmutations" type="text" placeholder="22q11.2 deletion/duplication"/>
                                          </div>
                                       </div>
                                     </div>
                                  </div>
                                  <br>

                                  <div class="row">
                                    <div class="bootstrap-iso">
                                      <div class="md-form">
                                        <i class="fas fa-pencil-alt prefix"> Comments</i>
                                        <textarea id="mutationcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                  <br>
                                  <div class="repeater-heading" align="center">
                                     <div class="bootstrap-iso">
                                       <button type="button" class="btn btn-primary btn-lg" id="savemutation"><i class="fas fa-dna"></i> Add genetic mutation test</button>
                                     </div>
                                  </div>
                           </fieldset>
                           <br> -->
                           <fieldset class="coll">
                              <legend>
                                 <h4>Genetic variant found</h4>
                              </legend>
                              <br>
                              <div class="bootstrap-iso" id="listvariants"></div>
                              <br>
                              <div class="row">
                                 <div class="col-half">
                                    <h4>Date</h4>
                                    <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                       <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                       <input type="text" autocomplete="off" name="variantdate" id="variantdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                    </div>
                                 </div>
                                 <div class="col-half">
                                   <h4>Test name</h4>
                                   <div class="row">
                                      <div class="bootstrap-iso" >
                                         <input autocomplete="off" class="autotypeahead" id="testvariants" name="testvariants" type="text" placeholder="22q11.2 deletion/duplication"/>
                                      </div>
                                   </div>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <div class="col-third">
                                    <h4>Gene</h4>
                                    <div class="bootstrap-iso" >
                                       <input autocomplete="off" class="autotypeaheadfirst" id="idgenevariant" name="idgenevariant" type="text" placeholder="A1BG"/>
                                    </div>
                                 </div>
                                 <div class="col-third">
                                    <h4>cDNA</h4>
                                    <div class="bootstrap-iso" >
                                       <input class="autotypeahead" id="cdnavariant" name="cdnavariant" type="text" placeholder=""/>
                                    </div>
                                 </div>
                                 <div class="col-third">
                                    <h4>Protein</h4>
                                    <div class="bootstrap-iso" >
                                       <input class="autotypeahead" id="proteinvariant" name="proteinvariant" type="text" placeholder=""/>
                                    </div>
                                 </div>
                              </div>
                              <br>

                              <div class="row">
                                 <div class="col-half">
                                    <h4>Variant found ID</h4>
                                    <div class="bootstrap-iso" >
                                       <input autocomplete="off" class="autotypeaheadfirsthgvs" id="idhgvsvariant" name="idhgvsvariant" type="text" placeholder="151003"/>
                                    </div>
                                 </div>
                                 <div class="col-half">
                                    <h4>Variant found NM number</h4>
                                    <div class="bootstrap-iso" >
                                       <input autocomplete="off" class="autotypeahead" id="hgvsvariant" name="hgvsvariant" type="text" placeholder="NR_144383.1:n.84G>C"/>
                                    </div>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <h4>Variant found interpretation</h4>
                                 <div class="input-group">
                                   <input class="greenradio" type="radio" name="mutinterpvar" value="NA" id="variant_na" />
                                   <label for="variant_na">No interpretation provided</label>
                                   <input class="greenradio" type="radio" name="mutinterpvar" value="Pathogenic" id="variant_path" />
                                   <label for="variant_path">Pathogenic</label>
                                   <input class="greenradio" type="radio" name="mutinterpvar" value="LikelyPathogenic" id="variant_lapth"/>
                                   <label for="variant_lapth">Likely Pathogenic</label>
                                   <input class="greenradio" type="radio" name="mutinterpvar" value="Unknown" id="variant_u"/>
                                   <label for="variant_u">Unknown Significance</label>
                                   <input class="greenradio" type="radio" name="mutinterpvar" value="Benign" id="variant_b"/>
                                   <label for="variant_b">Benign</label>
                                   <input class="greenradio" type="radio" name="mutinterpvar" value="LikelyBenign" id="variant_lb"/>
                                   <label for="variant_lb">Likely Benign</label>
                                 </div>
                              </div>
                              <div class="row">
                                 <h4>Genomic source class</h4>
                                 <div class="input-group">
                                    <input class="greenradio20" type="radio" name="gen_ori" value="Germline" id="gen_ori_g" />
                                    <label for="gen_ori_g">Germline</label>
                                    <input class="greenradio20" type="radio" name="gen_ori" value="Somatic" id="gen_ori_s" />
                                    <label for="gen_ori_s">Somatic</label>
                                    <input class="greenradio20" type="radio" name="gen_ori" value="LikelyGermline" id="gen_ori_lg" />
                                    <label for="gen_ori_lg">Likely germline</label>
                                    <input class="greenradio20" type="radio" name="gen_ori" value="LikelySomatic" id="gen_ori_ls" />
                                    <label for="gen_ori_ls">Likely somatic</label>
                                    <input class="greenradio20" type="radio" name="gen_ori" value="Fetal" id="gen_ori_f"  />
                                    <label for="gen_ori_f">Fetal</label>
                                    <input class="greenradio20" type="radio" name="gen_ori" value="LikelyFetal" id="gen_ori_lf" />
                                    <label for="gen_ori_lf">Likely fetal</label>
                                    <input class="greenradio20" type="radio" name="gen_ori" value="Denovo" id="gen_ori_dn" />
                                    <label for="gen_ori_dn">De novo</label>
                                    <input class="greenradio20" type="radio" name="gen_ori" value="Unknown" id="gen_ori_u" />
                                    <label for="gen_ori_u">Unknown origin</label>
                                 </div>
                              </div>
                              <div class="row">
                                <div class="bootstrap-iso">
                                  <div class="md-form">
                                    <i class="fas fa-pencil-alt prefix"> Comments</i>
                                    <textarea id="variantcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                  </div>
                                </div>
                              </div>
                              <br>
                              <div class="repeater-heading" align="center">
                                 <div class="bootstrap-iso">
                                   <button type="button" class="btn btn-primary btn-lg" id="savevariant"><i class="fas fa-dna"></i> Add genetic variant</button>
                                 </div>
                              </div>
                       </fieldset>



                           </form>
                          </div>
                       </li>
                       <!-- ########## TREATMENT ########## -->
                       <li class="tab-2">
                         <div class="row">
                            <h4>Patient Identifier</h4>
                            <div class="row">
                               <div class="bootstrap-iso">
                                <div class="input-group">
                                 <span class="input-group-addon glyphicon glyphicon-user"></span>
                                 <input type="text" name="treatmentidsource" placeholder="NF-01-01" id="treatmentidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                               </div>
                             </div>
                            </div>
                         </div>

                          <br>
                          <div class="box">
                             <form>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>Radiation</h4>
                                   </legend>
                                   <br>
                                   <div class="bootstrap-iso" id="listradiation"></div>
                                   <br>
                                   <div class="row">
                                      <div class="row">
                                         <div class="col-half">
                                            <h4>Date</h4>
                                            <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                               <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                               <input type="text" autocomplete="off" name="radiationdate" id="radiationdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                            </div>
                                         </div>
                                         <div class="col-half">
                                            <h4>Location</h4>
                                            <div class="bootstrap-iso">
                                               <input class="autotypeahead" type="text" id="radiationlocation" placeholder="Indicate here where the procedure has taken place"/>
                                            </div>
                                         </div>
                                      </div>
                                      <br>
                                      <div class="row">
                                        <div class="col-half">
                                           <h4>Procedure</h4>
                                           <div class="bootstrap-iso">
                                             <select class="selectpicker show-tick" name="radiationpro" id="radiationpro" data-width="99%">
                                                <option name="radiationpro" value="NA" >NA</option>
                                                <option name="radiationpro" value="Protons" >Teleradiotherapy protons</option>
                                                <option name="radiationpro" value="Electrons" >Teleradiotherapy using electrons</option>
                                                <option name="radiationpro" value="Neutrons" >Teleradiotherapy neutrons</option>
                                                <option name="radiationpro" value="Brachytherapy" >Brachytherapy</option>
                                                <option name="radiationpro" value="Photons" >Megavoltage radiation therapy using photons</option>
                                             </select>
                                           </div>
                                        </div>
                                         <div class="col-half">
                                            <h4>Body site</h4>
                                            <div class="bootstrap-iso">
                                               <input autocomplete="off" class="autotypeahead" id="radiobodysite" name="radiobodysite" type="text" placeholder="Brain structure"/>
                                            </div>
                                         </div>
                                      </div>
                                      <br>
                                      <div class="row">
                                         <h4>Treatment intent</h4>
                                         <div class="input-group">
                                            <input class="yellowradio" type="radio" name="treatment_intent_radio" value="NA" id="na_radio"/>
                                            <label for="na_radio">NA</label>
                                            <input class="yellowradio" type="radio" name="treatment_intent_radio" value="Palliative" id="palliative_radio"/>
                                            <label for="palliative_radio">Palliative</label>
                                            <input class="yellowradio" type="radio" name="treatment_intent_radio" value="Curative" id="curative_radio"/>
                                            <label for="curative_radio">Curative</label>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="radiationcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="saveradiation"><i class="fas fa-radiation"></i> Add radiation</button>
                                      </div>
                                   </div>
                                </fieldset>
                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>Surgery</h4>
                                   </legend>
                                   <br>
                                   <div class="bootstrap-iso" id="listsurgery"></div>
                                   <br>
                                   <div class="row">
                                      <div class="row">
                                         <div class="col-half">
                                            <h4>Date</h4>
                                            <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                               <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                               <input type="text" autocomplete="off" name="surgerydate" id="surgerydate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                            </div>
                                         </div>
                                         <div class="col-half">
                                            <h4>Location</h4>
                                            <div class="bootstrap-iso">
                                               <input class="autotypeahead" type="text" id="surgerylocation" placeholder="Indicate here where the procedure has taken place"/>
                                            </div>
                                         </div>
                                      </div>
                                      <br>
                                      <div class="row">
                                        <div class="col-half">
                                           <h4>Procedure</h4>
                                           <div class="bootstrap-iso">
                                             <input autocomplete="off" class="autotypeaheadfirst" id="surgery" name="surgery" type="text" placeholder="Bilobectomy of lung"/>
                                           </div>
                                        </div>
                                         <div class="col-half">
                                            <h4>Body site</h4>
                                            <div class="bootstrap-iso">
                                               <input autocomplete="off" class="autotypeahead" id="surgerybodysite" name="surgerybodysite" type="text" placeholder="Brain"/>
                                            </div>
                                         </div>
                                      </div>
                                      <br>
                                      <div class="row">
                                         <h4>Treatment intent</h4>
                                         <div class="input-group">
                                            <input class="yellowradio" type="radio" name="treatment_intent_surgery" value="NA" id="na_surgery"/>
                                            <label for="na_surgery">NA</label>
                                            <input class="yellowradio" type="radio" name="treatment_intent_surgery" value="Palliative" id="palliative_surgery"/>
                                            <label for="palliative_surgery">Palliative</label>
                                            <input class="yellowradio" type="radio" name="treatment_intent_surgery" value="Curative" id="curative_surgery"/>
                                            <label for="curative_surgery">Curative</label>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="surgerycom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="savesurgery"><i class="fas fa-hospital-symbol"></i> Add surgery</button>
                                      </div>
                                   </div>
                                </fieldset>
                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>Medication</h4>
                                   </legend>
                                   <br>
                                   <div class="bootstrap-iso" id="listmedication"></div>
                                   <br>
                                   <div class="row">
                                      <div class="row">
                                         <div class="col-third">
                                           <h4>Medication</h4>
                                           <div class="bootstrap-iso">
                                              <input autocomplete="off" class="autotypeahead" type="text" id="medication" placeholder="Betaxolol Oral Tablet [Kerlone]"/>
                                           </div>
                                         </div>
                                         <div class="col-third">
                                            <h4>Period</h4>
                                            <div class="c-datepicker-date-editor  J-datepicker-range-day" style="height: 37px; width:99%;">
                                              <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                              <input placeholder="Start" name="" class="c-datepicker-data-input only-date" value="" name="medicationstart" id="medicationstart">
                                              <span class="c-datepicker-range-separator">-</span>
                                              <input placeholder="End" name="" class="c-datepicker-data-input only-date" value="" name="medicationstop" id="medicationstop">
                                            </div>
                                         </div>
                                         <div class="col-third">
                                            <h4>Termination reason</h4>
                                            <div class="bootstrap-iso">
                                              <select class="selectpicker show-tick" name="termination" id="termination" data-width="100%">
                                                 <option name="termination" value="NA" >NA</option>
                                                 <option name="termination" value="Refusal" >Refusal of treatment by patient</option>
                                                 <option name="termination" value="Transfer" >Patient transfer</option>
                                                 <option name="termination" value="Financial" >Financial problem</option>
                                                 <option name="termination" value="Completed" >Treatment completed</option>
                                                 <option name="termination" value="Reaction" >Adverse reaction</option>
                                                 <option name="termination" value="Unavailable" >Treatment not available</option>
                                                 <option name="termination" value="Uneffective" >Lack of drug action</option>
                                              </select>
                                            </div>
                                         </div>
                                      </div>
                                      <br>
                                      <div class="col-row">
                                         <h4>Treatment intent</h4>
                                         <div class="input-group">
                                            <input class="yellowradio" type="radio" name="treatment_intent_medication" value="NA" id="na_medication"/>
                                            <label for="na_medication">NA</label>
                                            <input class="yellowradio" type="radio" name="treatment_intent_medication" value="Palliative" id="palliative_medication"/>
                                            <label for="palliative_medication">Palliative</label>
                                            <input class="yellowradio" type="radio" name="treatment_intent_medication" value="Curative" id="curative_medication"/>
                                            <label for="curative_medication">Curative</label>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="medicationcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="savemedication"><i class="fas fa-capsules"></i> Add medication</button>
                                      </div>
                                   </div>
                                </fieldset>
                             </form>
                          </div>
                       </li>
                       <!-- ########## LABVITAL ########## -->
                       <li class="tab-4">
                         <div class="row">
                            <h4>Patient Identifier</h4>
                            <div class="row">
                               <div class="bootstrap-iso">
                                <div class="input-group">
                                 <span class="input-group-addon glyphicon glyphicon-user"></span>
                                 <input type="text" name="labidsource" placeholder="NF-01-01" id="labidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                               </div>
                             </div>
                            </div>
                         </div>

                          <br>
                          <div class="box">
                            <form>
                              <fieldset class="coll">
                                 <legend>
                                    <h4>General metrics</h4>
                                 </legend>
                                 <br>
                                    <div class="bootstrap-iso" id="listlabs"></div>
                                 <br>
                                <div class="row">
                                  <div class="col-half">
                                     <h4>Date of evaluation</h4>
                                     <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                        <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                        <input type="text" autocomplete="off" name="blooddate" id="blooddate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                     </div>
                                  </div>
                                   <div class="col-half">
                                      <h4>Location</h4>
                                      <div class="bootstrap-iso">
                                         <input class="autotypeahead" type="text" id="bloodlocation" placeholder="Indicate here where the blood draw has taken place"/>
                                      </div>
                                   </div>
                                </div>
                                <br>
                                <div class="row">
                                   <div class="col-half">
                                      <h4>Height (cm)</h4>
                                      <div class="bootstrap-iso">
                                         <input class="autotypeaheadfirsthgvs" id="height" data-decimals="2" min="0" max="300" step="1" type="number" placeholder="180 cm" />
                                      </div>
                                   </div>
                                   <div class="col-half">
                                      <h4>Weight (kg)</h4>
                                      <div class="bootstrap-iso">
                                         <input class="autotypeahead" id="weight" data-decimals="2" min="0" max="600" step="1" type="number" placeholder="78 kg" />
                                      </div>
                                   </div>
                                </div>
                                <br>
                                <div class="row">
                                   <div class="col-half">
                                      <h4>Blood pressure diastolic (mmHg)</h4>
                                      <div class="bootstrap-iso">
                                         <input class="autotypeaheadfirsthgvs" id="diastolic" data-decimals="2" min="0" max="1000" step="1" type="number" placeholder="80 mmHg" />
                                      </div>
                                   </div>
                                   <div class="col-half">
                                      <h4>Blood pressure systolic (mmHg)</h4>
                                      <div class="bootstrap-iso">
                                         <input class="autotypeahead" id="systolic" data-decimals="2" min="0" max="1000" step="1" type="number" placeholder="120 mmHg" />
                                      </div>
                                   </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="bootstrap-iso">
                                    <div class="md-form">
                                      <i class="fas fa-pencil-alt prefix"> Comments</i>
                                      <textarea id="labcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                    </div>
                                  </div>
                                </div>
                                <br>
                                <div class="repeater-heading" align="center">
                                   <div class="bootstrap-iso">
                                     <button type="button" class="btn btn-primary btn-lg" id="savelab"><i class="fas fa-vials"></i> Add general lab metrics</button>
                                   </div>
                                </div>

                                </fieldset>
                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>CBC</h4>
                                   </legend>
                                   <br>
                                      <div class="bootstrap-iso" id="listcbc"></div>
                                   <br>
                                   <div class="row">
                                     <div class="col-third">
                                       <h4>Date</h4>
                                       <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                          <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                          <input type="text" autocomplete="off" name="cbcdate" id="cbcdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                       </div>
                                     </div>
                                      <div class="col-third">
                                         <h4>CBC type</h4>
                                         <div class="bootstrap-iso">
                                            <select class="selectpicker show-tick" name="cbctype" id="cbctype" data-width="100%">
                                               <option name="cbctype" value="Redblood" >Red blood cell count (cells/mcL)</option>
                                               <option name="cbctype" value="Whiteblood" >White blood cell count (cells/mcL)</option>
                                               <option name="cbctype" value="Hematocrit" >Hematocrit (%)</option>
                                               <option name="cbctype" value="Hemoglobin" >Hemoglobin (grams/L)</option>
                                               <option name="cbctype" value="Platelet" >Platelet count (/mcL)</option>
                                            </select>
                                         </div>
                                      </div>
                                      <div class="col-third">
                                         <h4>CBC count</h4>
                                         <div class="bootstrap-iso">
                                           <input class="autotypeahead" id="cbccount" name="cbccount" data-decimals="2" min="0" max="100000" step="1" type="number" placeholder="3.2-16.6 grams/dL"/>
                                         </div>
                                      </div>
                                   </div>
                                   <br>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="cbccom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="savecbc"><i class="fas fa-vials"></i> Add a CBC test</button>
                                      </div>
                                   </div>
                                 </fieldset>

                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>CMP</h4>
                                   </legend>
                                   <br>
                                      <div class="bootstrap-iso" id="listcmp"></div>
                                   <br>

                                   <div class="row">
                                     <div class="col-third">
                                       <h4>Date</h4>
                                       <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                          <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                          <input type="text" autocomplete="off" name="cmpdate" id="cmpdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                       </div>
                                     </div>
                                      <div class="col-third">
                                         <h4>CMP type</h4>
                                         <div class="bootstrap-iso">
                                            <select class="selectpicker show-tick" name="cmptype" id="cmptype" data-width="100%">
                                              <optgroup label="General">
                                                <option name="cmptype" value="Glucose" >Glucose</option>
                                                <option name="cmptype" value="Calcium" >Calcium</option>
                                              </optgroup>
                                              <optgroup label="Proteins">
                                                <option name="cmptype" value="Albumin" >Albumin</option>
                                                <option name="cmptype" value="Total" >Total protein</option>
                                              </optgroup>
                                              <optgroup label="Electrolytes">
                                                <option name="cmptype" value="Sodium" >Sodium</option>
                                                <option name="cmptype" value="Potassium" >Potassium</option>
                                                <option name="cmptype" value="Bicarbonate" >Bicarbonate (Total CO2)</option>
                                                <option name="cmptype" value="Chloride" >Chloride</option>
                                              </optgroup>
                                              <optgroup label="Kidney Tests">
                                                <option name="cmptype" value="BUN" >Blood urea nitrogen (BUN)</option>
                                                <option name="cmptype" value="Creatinine" >Creatinine</option>
                                              </optgroup>
                                              <optgroup label="Liver Tests">
                                                <option name="cmptype" value="ALP" >Alkaline phosphatase (ALP)</option>
                                                <option name="cmptype" value="ALT" >Alanine amino transferase (ALT, SGPT)</option>
                                                <option name="cmptype" value="AST" >Aspartate amino transferase (AST, SGOT)</option>
                                                <option name="cmptype" value="Bilirubin" >Bilirubin</option>
                                              </optgroup>

                                            </select>
                                         </div>
                                      </div>
                                      <div class="col-third">
                                         <h4>CMP count</h4>
                                         <div class="bootstrap-iso">
                                           <input class="autotypeahead" id="cmpcount" name="cmpcount" data-decimals="2" min="0" max="100000" step="1" type="number" placeholder="3.2-16.6 grams/dL"/>
                                         </div>
                                      </div>

                                   </div>
                                   <br>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="cmpcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="savecmp"><i class="fas fa-vials"></i> Add a CMP test</button>
                                      </div>
                                   </div>
                                 </fieldset>

                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>Tumor test</h4>
                                   </legend>
                                   <br>
                                      <div class="bootstrap-iso" id="listtumors"></div>
                                   <br>
                                   <div class="row">
                                     <div class="col-third">
                                       <h4>Date</h4>
                                       <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                          <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                          <input type="text" autocomplete="off" name="tumordate" id="tumordate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                       </div>
                                     </div>
                                      <div class="col-third">
                                         <h4>Tumor test code</h4>
                                         <div class="bootstrap-iso">
                                            <input autocomplete="off" id="testcode" class="autotypeaheadfirst" type="text" id="testcode" name="testcode" placeholder="Cancer Ag 125 [Units/volume]"/>
                                         </div>
                                      </div>
                                      <div class="col-third">
                                         <h4>Tumor test result</h4>
                                         <div class="row">
                                            <input class="blueradio" type="radio" name="testresults" value="test_positive" id="test_positive" />
                                            <label for="test_positive">Positive</label>
                                            <input class="blueradio" type="radio" name="testresults" value="test_negative" id="test_negative"/>
                                            <label for="test_negative">Negative</label>
                                         </div>
                                      </div>
                                   </div>
                                   <br>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="tumorcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="savetumors"><i class="fas fa-vials"></i> Add a tumor test</button>
                                      </div>
                                   </div>
                                </fieldset>

                                <br>
                                <fieldset class="coll">
                                   <legend>
                                      <h4>Investigations</h4>
                                   </legend>
                                   <br>
                                      <div class="bootstrap-iso" id="listinvestigation"></div>
                                   <br>
                                   <div class="row">
                                     <div class="col-half">
                                        <h4>Date</h4>
                                        <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                           <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                           <input type="text" autocomplete="off" name="nf1proceduredate" id="nf1proceduredate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                        </div>
                                     </div>
                                     <div class="col-half">
                                        <h4>Procedure</h4>
                                        <div class="bootstrap-iso">
                                           <select class="selectpicker show-tick" name="nf1procedure" id="nf1procedure" data-width="100%">
                                             <option name="nf1procedure" value="7" >Cerebral angiogram</option>
                                             <option name="nf1procedure" value="22" >Colonoscopy</option>
                                             <option name="nf1procedure" value="9" >CT chest</option>
                                             <option name="nf1procedure" value="10" >CT abdomen and pelvis</option>
                                             <option name="nf1procedure" value="11" >CT brain</option>
                                             <option name="nf1procedure" value="12" >CT head and neck</option>
                                             <option name="nf1procedure" value="13" >CT colonography</option>
                                             <option name="nf1procedure" value="21" >Cystoscopy</option>
                                             <option name="nf1procedure" value="8" >EMG</option>
                                              <option name="nf1procedure" value="5" >Mammography</option>
                                              <option name="nf1procedure" value="1" >MRI brain</option>
                                              <option name="nf1procedure" value="20" >MRI head and neck</option>
                                              <option name="nf1procedure" value="2" >MRI spine</option>
                                              <option name="nf1procedure" value="3" >MRI breast</option>
                                              <option name="nf1procedure" value="4" >MRI full body</option>
                                              <option name="nf1procedure" value="19" >MRI extremity</option>
                                              <option name="nf1procedure" value="6" >PET scan</option>
                                              <option name="nf1procedure" value="25" >Retrograde pyelogram</option>
                                              <option name="nf1procedure" value="23" >Upper endoscopy</option>
                                              <option name="nf1procedure" value="14" >US pelvis</option>
                                              <option name="nf1procedure" value="15" >US transvaginal</option>
                                              <option name="nf1procedure" value="16" >US abdomen</option>
                                              <option name="nf1procedure" value="17" >US thyroid</option>
                                              <option name="nf1procedure" value="18" >US breast</option>
                                              <option name="nf1procedure" value="24" >X-ray chest</option>

                                           </select>
                                        </div>
                                     </div>
                                 </div>
                                 <br>
                                 <div class="row">
                                   <div class="bootstrap-iso">
                                     <div class="md-form">
                                       <i class="fas fa-pencil-alt prefix"> Findings</i>
                                       <textarea id="nf1procedurecom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                     </div>
                                   </div>
                                 </div>
                                 <br>
                                 <div class="repeater-heading" align="center">
                                    <div class="bootstrap-iso">
                                      <button type="button" class="btn btn-primary btn-lg" id="saveprocedure"><i class="fas fa-vials"></i> Add procedure</button>
                                    </div>
                                 </div>
                                </fieldset>

                             </form>
                          </div>
                       </li>
                       <!-- ########## Biospecimens ########## -->
                       <li class="tab-7">
                         <div class="row">
                            <h4>Patient Identifier</h4>
                            <div class="row">
                               <div class="bootstrap-iso">
                                <div class="input-group">
                                 <span class="input-group-addon glyphicon glyphicon-user"></span>
                                 <input type="text" name="biospecimensidsource" placeholder="NF-01-01" id="biospecimensidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                               </div>
                             </div>
                            </div>
                         </div>
                          <br>
                          <div class="box">
                             <form>
                               <fieldset class="coll">
                                  <legend>
                                     <h4>Biospecimens</h4>
                                  </legend>
                                  <br>
                                     <div class="bootstrap-iso" id="listbiospecimens"></div>
                                  <br>
                                   <div class="row">
                                     <div class="col-third">
                                        <h4>Date of collection</h4>
                                        <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:100%;">
                                           <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                           <input type="text" autocomplete="off" name="biodate" id="biodate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                        </div>
                                     </div>
                                      <div class="col-third">
                                         <h4>Specimen type</h4>
                                         <div class="bootstrap-iso">
                                            <select class="selectpicker show-tick" name="specimentype" id="specimentype" data-width="100%">
                                               <option name="specimentype" value="specimentype1">Fresh</option>
                                               <option name="specimentype" value="specimentype2">Frozen</option>
                                               <option name="specimentype" value="specimentype3">Formalin-Fixed Paraffin-Embedded (FFPE)</option>
                                            </select>
                                         </div>
                                      </div>
                                      <div class="col-third">
                                         <h4>Specimen cellularity (%)</h4>
                                         <div class="bootstrap-iso">
                                            <input id="cellularity" placeholder="20" data-decimals="2" min="0" max="100" step="1" type="number" />
                                         </div>
                                      </div>
                                   </div>
                                   <br>
                                   <div class="row">
                                      <div class="col-third">
                                         <h4>Location of collection</h4>
                                         <div class="bootstrap-iso">
                                            <input class="autotypeaheadfirst" id="collection" type="text" placeholder="Participating sites"/>
                                         </div>
                                      </div>
                                      <div class="col-third">
                                         <h4>Location of storage</h4>
                                         <div class="bootstrap-iso">
                                            <input class="autotypeahead" id="storage" type="text" placeholder="Storage sites"/>
                                         </div>
                                      </div>
                                      <div class="col-third">
                                         <h4>Banking ID</h4>
                                         <div class="bootstrap-iso">
                                            <input class="autotypeahead" id="bankingid" type="text" placeholder="Optional"/>
                                         </div>
                                      </div>
                                   </div>
                                   <br><br>
                                   <div class="bootstrap-iso">
                                      <b>Is the tumor paired with blood sample?</b>
                                      <div class="btn-group" id="paired" data-toggle="buttons" style="float: right; margin-right: 550px;">
                                         <label class="btn btn-default btn-on-1 btn-sm active">
                                         <input id="matched_y" type="radio" value="Yes" name="pairedopts" checked="checked">Yes</label>
                                         <label class="btn btn-default btn-off-1 btn-sm ">
                                         <input id="matched_n" type="radio" value="No" name="pairedopts">No</label>
                                      </div>
                                   </div>
                                   <br>
                                   <div class="bootstrap-iso">
                                      <b>Is there imaging available on the date of the specimen collection?</b>
                                      <div class="btn-group" id="imaging" data-toggle="buttons" style="float: right; margin-right: 550px;">
                                         <label class="btn btn-default btn-on-1 btn-sm active">
                                         <input id="imaging_y"  type="radio" value="Yes" name="imagingopts" checked="checked">Yes</label>
                                         <label class="btn btn-default btn-off-1 btn-sm ">
                                         <input id="imaging_n" type="radio" value="No" name="imagingopts">No</label>
                                      </div>
                                   </div>
                                   <br>
                                   <div class="row">
                                     <div class="bootstrap-iso">
                                       <div class="md-form">
                                         <i class="fas fa-pencil-alt prefix"> Comments</i>
                                         <textarea id="biospecimencom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                       </div>
                                     </div>
                                   </div>
                                   <br>
                                   <div class="repeater-heading" align="center">
                                      <div class="bootstrap-iso">
                                        <button type="button" class="btn btn-primary btn-lg" id="savebiospecimens"><i class="fas fa-flask"></i> Add a biospecimen</button>
                                      </div>
                                   </div>


                                 </fieldset>
                             </form>
                          </div>
                       </li>

                       <!-- ########## CHARM ########## -->
                       <li class="tab-8">

                         <div class="row">
                            <h4>Patient Identifier</h4>
                            <div class="row">
                               <div class="bootstrap-iso">
                                <div class="input-group">
                                 <span class="input-group-addon glyphicon glyphicon-user"></span>
                                 <input type="text" name="pedigreeidsource" placeholder="NF-01-01" id="pedigreeidsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                               </div>
                             </div>
                            </div>
                         </div>
                         <br>
                          <div class="containers">
                             <form  method="post">
                               <br>
                               <div class="row" align="center">
                                  <button class="onco" style="vertical-align:middle" onclick="openPedigree(); return false;"><span>Open the Pedigree Application</span></button>
                               </div>
                               <br>
                             </form>
                          </div>
                       </li>
                       <!-- ########## NF1 ########## -->
                       <li class="tab-9">
                         <div class="row">
                            <h4>Patient Identifier</h4>
                            <div class="row">
                               <div class="bootstrap-iso">
                                <div class="input-group">
                                 <span class="input-group-addon glyphicon glyphicon-user"></span>
                                 <input type="text" name="nf1idsource" placeholder="NF-01-01" id="nf1idsource" class="form-control" onclick="updateID()" style="height: 60px; font-size:200%;font-weight:bold;font-family:sans-serif"/>
                               </div>
                             </div>
                            </div>
                         </div>
                         <br>
                          <div class="containers">
                            <form>
                              <fieldset class="coll">
                                 <legend>
                                    <h4>Diagnostic</h4>
                                 </legend>
                                 <br>
                                    <div class="bootstrap-iso" id="listnf1diag"></div>
                                 <br>

                                 <div class="row">
                                 <div class="col-half">
                                    <h4>Date of diagnosis</h4>
                                    <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                       <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                       <input type="text" autocomplete="off" name="nf1date" id="nf1date" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                    </div>
                                 </div>
                                <div class="col-half">
                                  <h4>Clinical diagnosis</h4>
                                    <div class="bootstrap-iso">
                                      <select id="nf1diag" name="nf1diag" class="selectpicker show-tick" data-width="100%" >
                                        <option value="1">NF1</option>
                                        <option value="2">Spinal NF</option>
                                        <option value="3">NF Noonan</option>
                                        <option value="4">Segmental/mosaic NF1</option>
                                        <option value="5">Noonan syndrome</option>
                                        <option value="6">Noonan syndrome with multiple lentigines (LEOPARD) syndrome</option>
                                        <option value="7">Cardio-facio-cutaneous syndrome (CFC)</option>
                                        <option value="8">Costello syndrome</option>
                                        <option value="9">Multiple CAL spots-only</option>
                                        <option value="10">Familial multiple CAL spots-only</option>
                                        <option value="11">Legius syndrome</option>
                                        <option value="12">Isolated neurofibromas</option>
                                        <option value="13">Single NF1 feature</option>
                                        <option value="14">Unknown</option>
                                    </select>
                                  </div>
                              </div>
                            </div>
                                <br>
                            <div class="row">
                                <div class="col-half" width="99%">
                                   <h4>Mode of transmission</h4>
                                   <div class="input-group">
                                      <input class="brownradio99" type="radio" name="mode" value="Familial" id="familialtr"  />
                                      <label for="familialtr">Familial</label>
                                      <input class="brownradio99" type="radio" name="mode" value="Denovo" id="denovotr" />
                                      <label for="denovotr">De novo</label>
                                      <input class="brownradio99" type="radio" name="mode" value="Unknown" id="unknowntr" />
                                      <label for="unknowntr">Unknown</label>
                                   </div>
                                </div>
                                <div class="col-half">
                                  <h4>Diagnostic criteria</h4>
                                    <div class="bootstrap-iso">
                                      <select id="nf1diagcri" name="nf1diagcri" class="selectpicker show-tick" data-width="100%" >
                                        <option value="1">Six or more café-au-lait macules with diameter >5mm in a prepubertal patient and >15mm in a post-pubertal patient</option>
                                        <option value="2">Two or more neurofibromas or one plexiform neurofibroma</option>
                                        <option value="3">Skinfold (axillary or inguinal) freckling</option>
                                        <option value="4">Optic glioma</option>
                                        <option value="5">Two or more Lisch nodules | 6, Characteristic bony lesion</option>
                                        <option value="6">First degree relative with NF1</option>
                                    </select>
                                  </div>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                               <h4>Severity (Riccardi scale)</h4>
                               <div class="input-group">
                                  <input class="brownradio20" type="radio" name="severity" value="grade1" id="severity1" />
                                  <label for="severity1">Grade 1 (minimal)</label>
                                  <input class="brownradio20" type="radio" name="severity" value="grade2" id="severity2" />
                                  <label for="severity2">Grade 2 (mild)</label>
                                  <input class="brownradio20" type="radio" name="severity" value="grade3" id="severity3" />
                                  <label for="severity3">Grade 3 (moderate)</label>
                                  <input class="brownradio20" type="radio" name="severity" value="grade4" id="severity4" />
                                  <label for="severity4">Grade 4 (severe)</label>
                                  <input class="brownradio20" type="radio" name="severity" value="grade1" id="severity0"  />
                                  <label for="severity0">Unknown</label>
                               </div>
                            </div>
                            <br>
                            <div class="row">
                               <h4>Visibility (Ablon scale)</h4>
                               <div class="input-group">
                                  <input class="brownradio25" type="radio" name="visibility" value="Germline" id="visibility1" />
                                  <label for="visibility1">Grade 1 (mild)</label>
                                  <input class="brownradio25" type="radio" name="visibility" value="Somatic" id="visibility2" />
                                  <label for="visibility2">Grade 2 (moderate)</label>
                                  <input class="brownradio25" type="radio" name="visibility" value="LikelyGermline" id="visibility3" />
                                  <label for="visibility3"> Grade 3 (severe)</label>
                                  <input class="brownradio25" type="radio" name="visibility" value="Fetal" id="visibility4"  />
                                  <label for="visibility4">Unknown</label>
                               </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-half">
                                 <h4>Age of puberty/menarche</h4>
                                 <div class="bootstrap-iso">
                                    <input id="puberty" placeholder="14" data-decimals="2" min="0" max="30" step="1" type="number" style="width:99%;"/>
                                 </div>
                              </div>
                              <div class="col-half">
                                 <h4>Head circumference (cm)</h4>
                                 <div class="bootstrap-iso">
                                    <input id="circumference" placeholder="30" data-decimals="2" min="0" max="500" step="1" type="number"/>
                                 </div>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="bootstrap-iso">
                                <div class="md-form">
                                  <i class="fas fa-pencil-alt prefix"> Comments</i>
                                  <textarea id="nf1com" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                                </div>
                              </div>
                            </div>
                            <br>
                            <div class="repeater-heading" align="center">
                               <div class="bootstrap-iso">
                                 <button type="button" class="btn btn-primary btn-lg" id="savediag"><i class="fas fa-vials"></i> Add diagnostic</button>
                               </div>
                            </div>
                          </fieldset>
                          <br>
                          <fieldset class="coll">
                             <legend>
                                <h4>Manifestations</h4>
                             </legend>
                             <br>
                                <div class="bootstrap-iso" id="listmanifestations"></div>
                             <br>
                             <div class="row">
                               <div class="col-third">
                                  <h4>Date of diagnosis</h4>
                                  <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                     <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                     <input type="text" autocomplete="off" name="nf1manifdate" id="nf1manifdate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                  </div>
                               </div>
                               <div class="col-third">
                                  <h4>Type</h4>
                                  <div class="bootstrap-iso">
                                     <select class="selectpicker show-tick" name="manifestations" id="manifestations" data-width="100%">
                                       <optgroup label="Ocular Manifestations">
                                         <option name="manifestations" value="1" >Lisch nodules</option>
                                       </optgroup>
                                       <optgroup label="Neurologic Manifestations">
                                          <option name="manifestations" value="2" >Headache/Migraine</option>
                                          <option name="manifestations" value="3" >Epilepsy</option>
                                          <option name="manifestations" value="4" >Hydrocephalus</option>
                                          <option name="manifestations" value="5" >Medullary compression by neurofibroma</option>
                                          <option name="manifestations" value="6" >Cerebral vascular complication</option>
                                          <option name="manifestations" value="7" >Neuropathic pain</option>
                                          <option name="manifestations" value="8" >Neuropathic pain medications</option>
                                       </optgroup>
                                       <optgroup label="Psychiatric Manifestations">
                                          <option name="manifestations" value="9" >Learning difficulties</option>
                                          <option name="manifestations" value="10" >Attention deficit hyperactivity disorder (ADHD)</option>
                                          <option name="manifestations" value="11" >Autism spectrum disorder (ASD)</option>
                                          <option name="manifestations" value="12" >Mood disorders</option>
                                       </optgroup>
                                       <optgroup label="Orthopedic Manifestations">
                                          <option name="manifestations" value="13" >Pseudarthrosis</option>
                                          <option name="manifestations" value="14" >Congenital pseudarthrosis of the tibia</option>
                                          <option name="manifestations" value="15" >Scoliosis and/or kyphosis</option>
                                          <option name="manifestations" value="16" >Sphenoid wing dysplasia</option>
                                          <option name="manifestations" value="17" >Long bone dysplasia</option>
                                          <option name="manifestations" value="18" >Macrocephaly</option>
                                          <option name="manifestations" value="19" >Short stature</option>
                                          <option name="manifestations" value="20" >Osteoporosis</option>
                                          <option name="manifestations" value="21" >Fractures</option>
                                       </optgroup>
                                       <optgroup label="Vascular Manifestations">
                                          <option name="manifestations" value="22" >Hypertension</option>
                                          <option name="manifestations" value="23" >Renal artery stenosis</option>
                                          <option name="manifestations" value="24" >Aortic stenosis</option>
                                          <option name="manifestations" value="25" >Pulmonic stenosis</option>
                                          <option name="manifestations" value="26" >Aneurysm</option>
                                          <option name="manifestations" value="27" >Moyamoya disease</option>
                                       </optgroup>
                                       <optgroup label="Neoplastic Manifestations">
                                          <option name="manifestations" value="28" >Optic nerve glioma</option>
                                          <option name="manifestations" value="29" >Brainstem glioma</option>
                                          <option name="manifestations" value="30" >Astrocytoma</option>
                                          <option name="manifestations" value="31" >Breast cancer</option>
                                          <option name="manifestations" value="32" >Pheochromocytoma</option>
                                          <option name="manifestations" value="33" >Paraganglioma</option>
                                          <option name="manifestations" value="34" >Gastrointestinal stromal tumour (GIST)</option>
                                          <option name="manifestations" value="35" >Neuroendocrine tumour</option>
                                          <option name="manifestations" value="36" >Glomus tumour</option>
                                          <option name="manifestations" value="37" >Rhabdomyosarcoma</option>
                                          <option name="manifestations" value="38" >Leukemia</option>
                                          <option name="manifestations" value="39" >Myelodysplastic syndrome</option>
                                          <option name="manifestations" value="40" >Malignant peripheral nerve sheath tumour (MPNST)</option>
                                       </optgroup>
                                       <optgroup label="Endocrinologic Manifestations">
                                          <option name="manifestations" value="41" >Growth delay</option>
                                          <option name="manifestations" value="42" >Vitamin D deficiency</option>
                                       </optgroup>
                                     </select>
                                  </div>
                               </div>
                               <div class="col-third">
                                  <h4>Evaluation</h4>
                                  <div class="input-group">
                                     <input class="brownradio99" type="radio" name="evaluation" value="Absent" id="absent"  />
                                     <label for="absent">Absent</label>
                                     <input class="brownradio99" type="radio" name="evaluation" value="Present" id="present" />
                                     <label for="present">Present</label>
                                     <input class="brownradio99" type="radio" name="evaluation" value="Unknown" id="unknownev" />
                                     <label for="unknownev">Unknown</label>
                                  </div>
                               </div>
                           </div>
                           <div class="row">
                             <div class="bootstrap-iso">
                               <div class="md-form">
                                 <i class="fas fa-pencil-alt prefix"> Comments</i>
                                 <textarea id="nf1manifcom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                               </div>
                             </div>
                           </div>
                           <br>
                           <div class="repeater-heading" align="center">
                              <div class="bootstrap-iso">
                                <button type="button" class="btn btn-primary btn-lg" id="savemanifestation"><i class="fas fa-vials"></i> Add manifestation</button>
                              </div>
                           </div>
                          </fieldset>
                          <br>
                          <fieldset class="coll">
                             <legend>
                                <h4>Skin lesions</h4>
                             </legend>
                             <br>
                                <div class="bootstrap-iso" id="listskin"></div>
                             <br>
                             <div class="row">
                               <div class="col-third">
                                  <h4>Date of diagnosis</h4>
                                  <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day comdates" style="height: 37px; width:99%;">
                                     <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
                                     <input type="text" autocomplete="off" name="nf1skindate" id="nf1skindate" placeholder="YYYY-MM-DD" class="c-datepicker-data-input only-date" value="">
                                  </div>
                               </div>
                               <div class="col-third">
                                  <h4>Type</h4>
                                  <div class="bootstrap-iso">
                                     <select class="selectpicker show-tick" name="skin" id="skin" data-width="100%">
                                         <option name="skin" value="1" >Café-au-lait macules</option>
                                         <option name="skin" value="2" >Axillary freckling</option>
                                         <option name="skin" value="3" >Inguinal freckling</option>
                                         <option name="skin" value="4" >Submammary freckling</option>
                                         <option name="skin" value="5" >Cutaneous neurofibromas</option>
                                         <option name="skin" value="6" >Histopathologically confirmed</option>
                                         <option name="skin" value="7" >Intradermal neurofibromas</option>
                                         <option name="skin" value="8" >Subdermal neurofibromas</option>
                                         <option name="skin" value="9" >Plexiform neurofibromas</option>
                                     </select>
                                  </div>
                               </div>
                               <div class="col-third">
                                  <h4>Evaluation</h4>
                                  <div class="input-group">
                                     <input class="brownradio99" type="radio" name="skinevaluation" value="Absent" id="absentsk"  />
                                     <label for="absentsk">Absent</label>
                                     <input class="brownradio99" type="radio" name="skinevaluation" value="Present" id="presentsk" />
                                     <label for="presentsk">Present</label>
                                     <input class="brownradio99" type="radio" name="skinevaluation" value="Unknown" id="unknownsk" />
                                     <label for="unknownsk">Unknown</label>
                                  </div>
                               </div>
                           </div>
                           <br>
                           <div class="row">
                             <div class="col-half">
                                <h4>Number</h4>
                                <div class="bootstrap-iso">
                                   <input id="skinnb" placeholder="14" data-decimals="2" min="0" max="3000" step="1" type="number" style="width:99%;"/>
                                </div>
                             </div>
                             <div class="col-half">
                                <h4>Location</h4>
                                <div class="input-group">
                                   <input class="brownradio25" type="radio" name="skinlocation" value="Left" id="leftsk"  />
                                   <label for="leftsk">Left</label>
                                   <input class="brownradio25" type="radio" name="skinlocation" value="Right" id="rightsk" />
                                   <label for="rightsk">Right</label>
                                   <input class="brownradio25" type="radio" name="skinlocation" value="Bilateral" id="bilateralsk" />
                                   <label for="bilateralsk">Bilateral</label>
                                   <input class="brownradio25" type="radio" name="skinlocation" value="Unknown" id="unknownskl" />
                                   <label for="unknownskl">Unknown</label>
                                </div>
                             </div>
                           </div>
                           <div class="row">
                             <div class="bootstrap-iso">
                               <div class="md-form">
                                 <i class="fas fa-pencil-alt prefix"> Comments</i>
                                 <textarea id="nf1skincom" class="md-textarea form-control" rows="3" style="resize: none;"></textarea>
                               </div>
                             </div>
                           </div>
                           <br>
                           <div class="repeater-heading" align="center">
                              <div class="bootstrap-iso">
                                <button type="button" class="btn btn-primary btn-lg" id="saveskin"><i class="fas fa-vials"></i> Add skin lesions</button>
                              </div>
                           </div>
                          </fieldset>
                          </div>
                       </li>
                    </article>
                 </div>
                 <!-- /.main content -->
              </div>
            <?php } ?> <!-- End condition of New user -->

              <!-- /.container -->
              <script type="text/javascript">
                 var max_input_fields = 10;
                 var wrapper         = $(".input_testmore");
                 var add_button      = $(".testmore");

                 var add_input_count = 1;
                 $(add_button).click(function(e){
                 e.preventDefault();
                 if(add_input_count < max_input_fields){
                   add_input_count++;
                   var newElement='<div class="row"><div class="smallinput"><div class="bootstrap-iso"><input id="test_'+add_input_count+'" class="smallinputboostrap" type="text" name="testcode[]" placeholder="Cancer Ag 125 [Units/volume]" /></div></div><div class="smallinput"><div class="row"><input class="blueradio" type="radio" name="testresults_'+add_input_count+'" value="test_positive_'+add_input_count+'" id="test_positive_'+add_input_count+'" /><label for="test_positive_'+add_input_count+'">Positive</label><input class="blueradio" type="radio" name="testresults_'+add_input_count+'" value="test_negative_'+add_input_count+'" id="test_negative_'+add_input_count+'"/><label for="test_negative_'+add_input_count+'">Negative</label></div></div><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="date" name="labdatetest[]" value="2017-06-01" style="height:37px"></div></div><button class="testless">Remove</button><br><br></div>';
                   $(wrapper).append(newElement);

                 }
                 });


                 $(wrapper).on('click', '.smallinputboostrap', function(){
                     $(this).typeahead({
                      source: function(query, result)
                      {
                       $.ajax({
                        url:"autocompletion/fetch_testcode.php",
                        method:"POST",
                        data:{query:query},
                        dataType:"json",
                        success:function(data)
                        {
                         result($.map(data, function(item){
                          return item;
                         }));
                        }
                       })
                      }
                     }
                     );
                 });

                 $(wrapper).on('click', '.testless', function(e){
                     $(this).closest('div').remove();
                     add_input_count--;
                 });

                 // CBC
                 var wrapper_cbc         = $(".input_cbcmore");
                 var add_button_cbc      = $(".cbcmore");

                 var add_input_count_cbc = 1;
                 $(add_button_cbc).click(function(e){
                 e.preventDefault();
                 if(add_input_count_cbc < max_input_fields){
                 add_input_count_cbc++;
                 var newElement='<div class="row"><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="text" name="cbctype[]" placeholder="Hemoglobin" /></div></div><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="text" name="cbccount[]" placeholder="3.2-16.6 grams/dL" /></div></div><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="date" name="labdatecbc[]" value="2017-06-01" style="height:37px"></div></div><button class="cbcless">Remove</button><br><br></div>';
                 $(wrapper_cbc).append(newElement);
                 }
                 });

                 $(wrapper_cbc).on('click', '.cbcless', function(e){
                 $(this).closest('div').remove();
                 add_input_count_cbc--;
                 });

                 // CMP
                 var wrapper_cmp         = $(".input_cmpmore");
                 var add_button_cmp      = $(".cmpmore");

                 var add_input_count_cmp = 1;
                 $(add_button_cmp).click(function(e){
                 e.preventDefault();
                 if(add_input_count_cmp < max_input_fields){
                 add_input_count_cmp++;
                 var newElement='<div class="row"><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="text" name="cmptype[]" placeholder="Glucose" /></div></div><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="text" name="cmpcount[]" placeholder="200 mg/dL" /></div></div><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="date" name="labdatecmp[]" value="2017-06-01" style="height:37px"></div></div><button class="cmpless">Remove</button><br><br></div>';
                 $(wrapper_cmp).append(newElement);
                 }
                 });

                 $(wrapper_cmp).on('click', '.cmpless', function(e){
                 $(this).closest('div').remove();
                 add_input_count_cmp--;
                 });

                 // Radiation
                 var wrapper_radio        = $(".input_radiomore");
                 var add_button_radio      = $(".btnradiation");

                 var add_input_count_radio = 0;
                 var counter_rad = 0;
                 $(add_button_radio).click(function(e){
                 e.preventDefault();
                 if(add_input_count_radio < max_input_fields){
                 add_input_count_radio++;
                 counter_rad += 1;
                 var newElement='<div class="row"><div class="row"><div class="col-half"><h4>Date</h4><div class="bootstrap-iso"><input class="autotypeaheadfirst" type="date" name="radiation_date[]" value="2017-06-01"></div></div><div class="col-half"><h4>Location</h4><div class="bootstrap-iso"><input class="autotypeaheadlast" type="text" placeholder="Indicate here where the procedure has taken place"/></div></div></div><br><div class="row"><div class="col-half"><h4>Radiation procedure</h4><div class="bootstrap-iso"><input class="autotypeaheadfirstradio" name="procedure[]" type="text" placeholder="Brachytherapy"/></div></div><div class="col-half"><h4>Body site</h4><div class="bootstrap-iso"><input class="autotypeaheadlastsite" name="bodysite[]" type="text" placeholder="Brain structure"/></div></div></div><br><div class="row"><h4>Treatment intent</h4><div class="input-group"><input class="yellowradio" type="radio" name="treatment_intent_'+counter_rad+'" value="na_treatment_'+counter_rad+'" id="na_treatment_'+counter_rad+'"/><label for="na_treatment_'+counter_rad+'">NA</label><input class="yellowradio" type="radio" name="treatment_intent_'+counter_rad+'" value="palliative_'+counter_rad+'" id="palliative_'+counter_rad+'"/><label for="palliative_'+counter_rad+'">Palliative</label><input class="yellowradio" type="radio" name="treatment_intent_'+counter_rad+'" value="curative_'+counter_rad+'" id="curative_'+counter_rad+'"/><label for="curative_'+counter_rad+'">Curative</label></div></div><button class="radioless">Remove</button><br><br><br></div>';

                 $(wrapper_radio).append(newElement);
                 }
                 });

                 $(wrapper_radio).on('click', '.autotypeaheadfirstradio', function(){
                 $(this).typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_radioprocedure.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );
                 });

                 $(wrapper_radio).on('click', '.autotypeaheadlastsite', function(){
                 $(this).typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_radiation.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );
                 });

                 $(wrapper_radio).on('click', '.radioless', function(e){
                 $(this).closest('div').remove();
                 add_input_count_radio--;
                 });

                 // Surgery
                 var add_button_surgery      = $(".btnsurgery");

                 var add_input_count_surgery = 1;
                 var counter = 0;
                 $(add_button_surgery).click(function(e){
                 e.preventDefault();
                 if(add_input_count_surgery < max_input_fields){
                 add_input_count_surgery++;
                 counter += 1;
                 var newElement='<div class="row"><div class="row"><div class="col-half"><h4>Date</h4><div class="bootstrap-iso"><input class="autotypeaheadfirst" type="date" name="surgery_date[]" value="2017-06-01"></div></div><div class="col-half"><h4>Location</h4><div class="bootstrap-iso"><input class="autotypeaheadlast" type="text" placeholder="Indicate here where the procedure has taken place"/></div></div></div><br><div class="row"><h4>Surgery procedure</h4><div class="bootstrap-iso"><input class="autotypeaheadsurgery" name="procedure_surgery[]" type="text" placeholder="Thyroidectomy"/></div></div><br><div class="row"><h4>Treatment intent</h4><div class="input-group"><input class="yellowradio" type="radio" name="surgery_intent_'+counter+'" value="na_surgery_'+counter+'" id="na_surgery_'+counter+'"/><label for="na_surgery_'+counter+'">NA</label><input class="yellowradio" type="radio" name="surgery_intent_'+counter+'" value="s_palliative_'+counter+'" id="s_palliative_'+counter+'"/><label for="s_palliative_'+counter+'">Palliative</label><input class="yellowradio" type="radio" name="surgery_intent_'+counter+'" value="s_curative_'+counter+'" id="s_curative_'+counter+'"/><label for="s_curative_'+counter+'">Curative</label></div></div><button class="surgeryless">Remove</button><br><br><br></div>';

                 $(wrapper_radio).append(newElement);
                 }
                 });

                 $(wrapper_radio).on('click', '.autotypeaheadsurgery', function(){
                 $(this).typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_surgery.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );
                 });

                 $(wrapper_radio).on('click', '.surgeryless', function(e){
                 $(this).closest('div').remove();
                 add_input_count_surgery--;
                 });

                 // Medication
                 var add_button_medication      = $(".btnmedication");

                 var add_input_count_medication = 1;
                 var counter = 0;
                 $(add_button_medication).click(function(e){
                 e.preventDefault();
                 if(add_input_count_medication < max_input_fields){
                 add_input_count_medication++;
                 counter += 1;
                 var newElement='<div class="row"><div class="row"><h4>Medication</h4><div class="bootstrap-iso"><input class="autotypeaheadmed" type="text" placeholder="Triamcinolone Oral Paste"/></div></div><br><div class="row"><div class="col-half"><h4>Intent</h4><div class="bootstrap-iso"><input class="autotypeaheadfirst" type="text" placeholder="Brain structure"/></div></div><div class="col-half"><h4>Termination reason</h4><div class="bootstrap-iso"><input class="autotypeaheadlastmed" type="text" placeholder="Financial problem"/></div></div></div><br><div class="row"><div class="col-half"><h4>Start Date</h4><div class="bootstrap-iso"><input class="autotypeaheadfirst" id="start_date" type="date" name="start_date" value="2017-06-01"></div></div><div class="col-half"><h4>End Date</h4><div class="bootstrap-iso"><input class="autotypeaheadlast" id="end_date" type="date" name="end_date" value="2017-06-01"></div></div></div><br><button class="medicationless">Remove</button><br><br><br></div>';


                 $(wrapper_radio).append(newElement);
                 }
                 });

                 $(wrapper_radio).on('click', '.autotypeaheadlastmed', function(){
                 $(this).typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_medtermnination.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );
                 });

                 $(wrapper_radio).on('click', '.autotypeaheadmed', function(){
                   $(this).typeahead({
                    source: function(query, result)
                    {
                     $.ajax({
                      url:"autocompletion/fetch_medication.php",
                      method:"POST",
                      data:{query:query},
                      dataType:"json",
                      success:function(data)
                      {
                       result($.map(data, function(item){
                        return item;
                       }));
                      }
                     })
                    }
                   }
                   );
                 });

                 $(wrapper_radio).on('click', '.medicationless', function(e){
                 $(this).closest('div').remove();
                 add_input_count_medication--;
                 });

                 // biospecimens
                 var wrapper_bio         = $(".input_biomore");
                 var add_button_bio      = $(".biomore");

                 var counter_bio=0;

                 var add_input_count_bio = 1;
                 $(add_button_bio).click(function(e){
                 e.preventDefault();
                 if(add_input_count_bio < max_input_fields){
                 add_input_count_bio++;
                 counter_bio++;
                 var newElement='<div class="row"><div class="row"><div class="col-third"><h4>Date</h4><div class="bootstrap-iso"><input class="autotypeaheadfirst" id="biodate" type="date" name="biodate[]" value="2017-06-01"></div></div><div class="col-third"><h4>Specimen type</h4><div class="bootstrap-iso"><select class="selectpicker show-tick" name="specimentype[]" id="specimentype" data-width="100%"><option value="fresh">Fresh</option><option value="frozen">Frozen</option><option value="ffpe">Formalin-Fixed Paraffin-Embedded (FFPE)</option></select></div></div><div class="col-third"><h4>Specimen cellularity (%)</h4><div class="bootstrap-iso"><input class="autotypeahead" type="text" placeholder="20"/></div></div></div><br><div class="row"><div class="col-third"><h4>Location of collection</h4><div class="bootstrap-iso"><input class="autotypeaheadfirst" type="text" placeholder="Participating sites"/></div></div><div class="col-third"><h4>Location of storage</h4><div class="bootstrap-iso"><input class="autotypeahead" type="text" placeholder="Participating sites"/></div></div><div class="col-third"><h4>Banking ID</h4><div class="bootstrap-iso"><input class="autotypeahead" type="text" placeholder="Optional"/></div></div></div><br><div class="bootstrap-iso">Tumor paired with blood sample?<div class="btn-group" id="status" data-toggle="buttons" style="float: right; margin-right: 300px;"><label class="btn btn-default btn-on-1 btn-sm active"><input id="matched_y" type="radio" value="1" name="multifeatured_module[module_id][status]" checked="checked">YES</label><label class="btn btn-default btn-off-1 btn-sm "><input id="matched_n" type="radio" value="0" name="multifeatured_module[module_id][status]">NO</label></div></div><br><div class="bootstrap-iso">Imaging available on the date of the specimen collection?<div class="btn-group" id="status" data-toggle="buttons" style="float: right; margin-right: 300px;"><label class="btn btn-default btn-on-1 btn-sm active"><input id="imaging_y"  type="radio" value="1" name="multifeatured_module[module_id][status]" checked="checked">YES</label><label class="btn btn-default btn-off-1 btn-sm "><input id="imaging_n" type="radio" value="0" name="multifeatured_module[module_id][status]">NO</label></div></div><br><button class="bioless">Remove</button><br><br><br></div>';


                 $(wrapper_bio).append(newElement);
                 $('.selectpicker').selectpicker('refresh');

                 }
                 });

                 $(wrapper_bio).on('click', '.bioless', function(e){
                 $(this).closest('div').remove();
                 add_input_count_bio--;
                 });

                 //nf1
                 var wrapper_nf1_clin         = $(".nf1_more");
                 var add_button_nf1_clin      = $(".btntumourcharacteristics");

                 var add_input_count_nf1_clin = 1;
                 $(add_button_nf1_clin).click(function(e){
                 e.preventDefault();
                 if(add_input_count_nf1_clin < max_input_fields){
                 add_input_count_nf1_clin++;
                 var newElement='<div class="row"><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="text" name="cbctype[]" placeholder="Hemoglobin" /></div></div><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="text" name="cbccount[]" placeholder="3.2-16.6 grams/dL" /></div></div><div class="smallinput"><div class="bootstrap-iso"><input class="smallinputboostrap" type="date" name="labdatecbc[]" value="2017-06-01" style="height:37px"></div></div><button class="cbcless">Remove</button><br><br></div>';
                 $(wrapper_nf1_clin).append(newElement);
                 }
                 });

                 $(wrapper_nf1_clin).on('click', '.cbcless', function(e){
                 $(this).closest('div').remove();
                 add_input_count_nf1_clin--;
                 });

                 function openChild() {
                 childWindow = open('Oncotree/tree.html', 'pagename', "height=1000,width=1000");
                 //  childWindow = open('http://www.mcoder.ca/tree.html', 'NA', "height=1000,width=1000");
                 }

                 function openPedigree() {
                   var ipdiv = document.getElementById("ipaddress");
                   var ip = ipdiv.textContent.replace( /\s+/g, '');
                   var datediv = document.getElementById("datesystem");
                   var datesystem = datediv.textContent.replace( /\s+/g, '');
                   var emaildiv = document.getElementById("email");
                   var email = emaildiv.textContent.replace( /\s+/g, '');
                   var userdiv = document.getElementById("username");
                   var username = userdiv.textContent.replace( /\s+/g, '');
                   var rolesdiv = document.getElementById("roles");
                   var roles = rolesdiv.textContent.replace( /\s+/g, '');

                 childWindow = open('pedigree/docs/pedigree.php?ip='+ip+'&datesystem='+datesystem+'&email='+email+'&username='+username+'&roles='+roles, 'pagename', "height=1000,width=1000");
                 //  childWindow = open('http://www.mcoder.ca/tree.html', 'NA', "height=1000,width=1000");
                 }

                 function logout() {
                 location.assign('http://localhost:8080/auth/realms/testRealm/protocol/openid-connect/logout?redirect_uri=http://localhost/test.php');
                 //  childWindow = open('http://www.mcoder.ca/tree.html', 'NA', "height=1000,width=1000");
                 }

                 function setValue(myVal) {
                 document.getElementById('info').value = myVal;
                 }
              </script>
              <script src="date/js/datepicker.all.js"></script>
              <!-- <script src="js/datepicker.all.min.js"></script> -->
              <script src="date/js/datepicker.en.js"></script>
              <script type="text/javascript">
                 $(function(){
                   $('.J-datepicker-time').datePicker({
                     format: 'HH:mm:ss',
                     min: '04:23:11',
                     language: 'en'
                   });
                   $('.J-datepicker-time-range').datePicker({
                     format: 'HH:mm:ss',
                     isRange: true,
                     min: '04:23:11',
                     max: '20:59:59',
                     language: 'en'
                   });

                   var today = new Date();
                   var today2 = new Date();
                   var dd = String(today.getDate()).padStart(2, '0');
                   var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                   var yyyy = today.getFullYear();

                   today = yyyy + '-' + mm;
                   today2 = yyyy + '-' + mm + '-' + dd ;


                   var DATAPICKERAPI = {
                     activeMonthRange: function () {
                       return {
                         begin: moment().set({ 'date': 1, 'hour': 0, 'minute': 0, 'second': 0 }).format('YYYY-MM-DD HH:mm:ss'),
                         end: moment().set({ 'hour': 23, 'minute': 59, 'second': 59 }).format('YYYY-MM-DD HH:mm:ss')
                       }
                     },
                     shortcutMonth: function () {
                       var nowDay = moment().get('date');
                       var prevMonthFirstDay = moment().subtract(1, 'months').set({ 'date': 1 });
                       var prevMonthDay = moment().diff(prevMonthFirstDay, 'days');
                       return {
                         now: '-' + nowDay + ',0',
                         prev: '-' + prevMonthDay + ',-' + nowDay
                       }
                     },
                     shortcutPrevHours: function (hour) {
                       var nowDay = moment().get('date');
                       var prevHours = moment().subtract(hour, 'hours');
                       var prevDate=prevHours.get('date')- nowDay;
                       var nowTime=moment().format('HH:mm:ss');
                       var prevTime = prevHours.format('HH:mm:ss');
                       return {
                         day: prevDate + ',0',
                         time: prevTime+',' + nowTime,
                         name: 'Nearly '+ hour+' Hours'
                       }
                     },
                     rangeMonthShortcutOption1: function () {
                       var result = DATAPICKERAPI.shortcutMonth();
                       var resultTime= DATAPICKERAPI.shortcutPrevHours(18);
                       return [{
                         name: 'Yesterday',
                         day: '-1,-1',
                         time: '00:00:00,23:59:59'
                       }, {
                         name: 'This Month',
                         day: result.now,
                         time: '00:00:00,'
                       }, {
                         name: 'Lasy Month',
                         day: result.prev,
                         time: '00:00:00,23:59:59'
                       }, {
                         name: resultTime.name,
                         day: resultTime.day,
                         time: resultTime.time
                       }];
                     },
                     rangeShortcutOption1: [{
                       name: 'Last week',
                       day: '-7,0'
                     }, {
                       name: 'Last Month',
                       day: '-30,0'
                     }, {
                       name: 'Last Three Months',
                       day: '-90, 0'
                     }],
                     singleShortcutOptions1: [{
                       name: 'Today',
                       day: '0',
                       time: '00:00:00'
                     }, {
                       name: 'Yesterday',
                       day: '-1',
                       time: '00:00:00'
                     }, {
                       name: 'One Week Ago',
                       day: '-7'
                     }]
                   };
                     $('.J-datepicker').datePicker({
                       hasShortcut:true,
                       language: 'en',
                       min:'2018-01-01 04:00:00',
                       max:'2029-10-29 20:59:59',
                       shortcutOptions:[{
                         name: 'Today',
                         day: '0'
                       }, {
                         name: 'Yesterday',
                         day: '-1',
                         time: '00:00:00'
                       }, {
                         name: 'One Week Ago',
                         day: '-7'
                       }],
                       hide:function(){
                         console.info(this)
                       }
                     });


                     $('.J-datepicker-day').datePicker({
                       hasShortcut: true,
                       language: 'en',
                       format: 'YYYY-MM-DD',
                       min:'1900-01-01 04:00:00',
                       max: today2,

                       shortcutOptions: [{
                         name: 'Today',
                         day: '0'
                       }, {
                         name: 'Yesterday',
                         day: '-1'
                       }, {
                         name: 'One week ago',
                         day: '-7'
                       }]
                     });


                     $('.J-datepicker-range-day').datePicker({
                       hasShortcut: true,
                       language: 'en',
                       format: 'YYYY-MM-DD',
                       isRange: true,
                       shortcutOptions: DATAPICKERAPI.rangeShortcutOption1
                     });


                     $('.J-datepickerTime-single').datePicker({
                       format: 'YYYY-MM-DD',
                       language: 'en',
                     });


                     $('.J-datepickerTime-range').datePicker({
                       format: 'YYYY-MM-DD',
                       isRange: true,
                       language: 'en'
                     });


                     $('.J-datepicker-range').datePicker({
                       hasShortcut: true,
                       language: 'en',
                       min: '2018-01-01 06:00:00',
                       max: '2029-04-29 20:59:59',
                       isRange: true,
                       shortcutOptions: [{
                         name: 'Yesterday',
                         day: '-1,-1',
                         time: '00:00:00,23:59:59'
                       },{
                         name: 'Last Week',
                         day: '-7,0',
                         time:'00:00:00,'
                       }, {
                         name: 'Last Month',
                         day: '-30,0',
                         time: '00:00:00,'
                       }, {
                         name: 'Last Three Months',
                         day: '-90, 0',
                         time: '00:00:00,'
                       }],
                       hide: function (type) {
                         console.info(this.$input.eq(0).val(), this.$input.eq(1).val());
                         console.info('Type:',type)
                       }
                     });
                     $('.J-datepicker-range-betweenMonth').datePicker({
                       isRange: true,
                       between:'month',
                       language: 'en',
                       hasShortcut: true,
                       shortcutOptions: DATAPICKERAPI.rangeMonthShortcutOption1()
                     });


                     $('.J-datepicker-range-between30').datePicker({
                       isRange: true,
                       language: 'en',
                       between: 30
                     });


                     $('.J-yearMonthPicker-single').datePicker({
                       format: 'YYYY-MM',
                       language: 'en',
                       min: '1900-01',
                       max: today,
                       hide: function (type) {
                         console.info(this.$input.eq(0).val());
                       }
                     });

                     $('.J-yearPicker-single').datePicker({
                       format: 'YYYY',
                       language: 'en',
                       min: '2018',
                       max: '2029'
                     });


                 });
              </script>


              <?php if($hasRoleDemo == 0) { ?>
              <script src="insert/addpatient.js"></script>
              <script src="insert/addcomorbid.js"></script>
              <script src="insert/addstatus.js"></script>
              <script src="insert/addcancer.js"></script>
              <script src="insert/addmutation.js"></script>
              <script src="insert/addvariant.js"></script>
              <script src="insert/addradiation.js"></script>
              <script src="insert/addsurgery.js"></script>
              <script src="insert/addmedication.js"></script>
              <script src="insert/addlabs.js"></script>
              <script src="insert/addcbc.js"></script>
              <script src="insert/addcmp.js"></script>
              <script src="insert/addtumor.js"></script>
              <script src="insert/addbiospecimen.js"></script>
              <script src="insert/addoutcome.js"></script>
              <script src="insert/adddeath.js"></script>
              <script src="insert/adddiagnostic.js"></script>
              <script src="insert/addmanifestation.js"></script>
              <script src="insert/addlesion.js"></script>
              <script src="insert/addprocedure.js"></script>

              <script src="fetch/patient.js"></script>
              <script src="fetch/comorbid.js"></script>
              <script src="fetch/status.js"></script>
              <script src="fetch/cancer.js"></script>
              <script src="fetch/mutation.js"></script>
              <script src="fetch/variant.js"></script>
              <script src="fetch/radiation.js"></script>
              <script src="fetch/surgery.js"></script>
              <script src="fetch/medication.js"></script>
              <script src="fetch/labs.js"></script>
              <script src="fetch/cbc.js"></script>
              <script src="fetch/cmp.js"></script>
              <script src="fetch/tumor.js"></script>
              <script src="fetch/biospecimen.js"></script>
              <script src="fetch/outcome.js"></script>
              <script src="fetch/death.js"></script>
              <script src="fetch/nf1diag.js"></script>
              <script src="fetch/nf1skin.js"></script>
              <script src="fetch/nf1manif.js"></script>
              <script src="fetch/nf1procedure.js"></script>
              <?php } ?>

              <script>
              $(document).ready(function(){

               //load_patient();

               // $('#nf1diag').multiselect({
               //   nonSelectedText: 'Select clinical diagnosis',
               //   enableFiltering: true,
               //   enableCaseInsensitiveFiltering: true,
               //   buttonWidth:'100%'
               // });
               //
               // $('#nf1diagcri').multiselect({
               //   nonSelectedText: 'Select diagnostic criteria',
               //   enableFiltering: true,
               //   enableCaseInsensitiveFiltering: true,
               //   buttonWidth:'100%'
               // });

              });
              </script>

           </body>
        </html>
        <script>
           $(document).ready(function(){

                 $('#testcode').typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_testcode.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );

                 $('#radiationprocedure').typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_radioprocedure.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );

                 $('#radiobodysite').typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_radiation.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );

                 $('#surgery').typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_surgery.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );

                 $('#medication').typeahead({
                  source: function(query, result)
                  {
                   $.ajax({
                    url:"autocompletion/fetch_medication.php",
                    method:"POST",
                    data:{query:query},
                    dataType:"json",
                    success:function(data)
                    {
                     result($.map(data, function(item){
                      return item;
                     }));
                    }
                   })
                  }
                 }
                 );

            $('#hgvsmut').typeahead({
             source: function(query, result)
             {
              $.ajax({
               url:"autocompletion/fetch_hgvs.php",
               method:"POST",
               data:{query:query},
               dataType:"json",
               success:function(data)
               {
                result($.map(data, function(item){
                 return item;
                }));
               }
              })
             }
            }
           );

           $('#hgvsvariant').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_hgvs.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
          );

           $('#idhgvsmut').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_idhgvs.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           $('#idhgvsvariant').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_idhgvs.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           $('#genemut').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_gene.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           $('#idgenevariant').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_gene.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           $('#location').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_location.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           $('#comorbid').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_comorbidities.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           $('#testmutations').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_test.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           $('#testvariants').typeahead({
            source: function(query, result)
            {
             $.ajax({
              url:"autocompletion/fetch_test.php",
              method:"POST",
              data:{query:query},
              dataType:"json",
              success:function(data)
              {
               result($.map(data, function(item){
                return item;
               }));
              }
             })
            }
           }
           );

           });
        </script>
        <script>
           $(document).ready(function(){


            $('#ecog').selectpicker();

            $('#framework_form').on('submit', function(event){
             event.preventDefault();
             var form_data = $(this).serialize();
             $.ajax({
              url:"insert.php",
              method:"POST",
              data:form_data,
              success:function(data)
              {
               $('#framework option:selected').each(function(){
                $(this).prop('selected', false);
               });
               $('#framework').multiselect('refresh');
               alert(data);
              }
             });
            });


           });

        </script>


        <?php

     } catch (Exception $e) {
         exit('Failed to get resource owner: ' . $e->getMessage());
     }

 } else {

  header('Location: login.php');

     if (isset($_SESSION['userName'])) {
         $userName = $_SESSION['userName'];
     }

     if (isset($_SESSION['userMail'])) {
         $userMail = $_SESSION['userMail'];
     }

     if (isset($_SESSION['roles'])) {
         $roles = $_SESSION['roles'];
     }

     if (isset($_SESSION['accessToken'])) {
         $accessToken = $_SESSION['accessToken'];
     }
 }

 if (isset($_REQUEST['logout'])) {

 ob_start();
   print_r($_SESSION);
   unset($_SESSION['oauth2state']);
   unset($_SESSION['userName']);
   unset($_SESSION['userMail']);
   unset($_SESSION['roles']);
   session_unset();
   session_destroy();
  $_SESSION = array();
   header('Location: index.php');
   exit();
 }

?>
