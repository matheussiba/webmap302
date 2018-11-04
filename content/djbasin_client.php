<?php include("../includes/init.php");?>
<?php
if (logged_in()) {
  $username=$_SESSION['username'];
  if (!verify_user_group($pdo, $username, "DJ Basin Client")) {
    set_msg("User '{$username}' does not have permission to view this page");
    redirect('../index.php');
  }
} else {
  set_msg("Please log-in and try again");
  redirect('../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DJ Basin Client</title>
  <link rel="stylesheet" href="src/leaflet.css">
  <link rel="stylesheet" href="src/css/bootstrap.css">
  <link rel="stylesheet" href="src/plugins/L.Control.MousePosition.css">
  <link rel="stylesheet" href="src/plugins/L.Control.Sidebar.css">
  <link rel="stylesheet" href="src/plugins/Leaflet.PolylineMeasure.css">
  <link rel="stylesheet" href="src/plugins/easy-button.css">
  <link rel="stylesheet" href="src/css/font-awesome.min.css">
  <link rel="stylesheet" href="src/plugins/leaflet.awesome-markers.css">
  <link rel="stylesheet" href="src/plugins/MarkerCluster.css">
  <link rel="stylesheet" href="src/plugins/MarkerCluster.Default.css">
  <link rel="stylesheet" href="src/plugins/leaflet-legend.css">
  <link rel="stylesheet" href="src/jquery-ui.min.css">

  <script src="src/leaflet.js"></script>
  <script src="src/jquery-3.3.1.min.js"></script>
  <script src="src/plugins/L.Control.MousePosition.js"></script>
  <script src="src/plugins/L.Control.Sidebar.js"></script>
  <script src="src/plugins/Leaflet.PolylineMeasure.js"></script>
  <script src="src/plugins/easy-button.js"></script>
  <script src="src/plugins/leaflet-providers.js"></script>
  <script src="src/plugins/leaflet.ajax.min.js"></script>
  <script src="src/plugins/leaflet.awesome-markers.min.js"></script>
  <script src="src/plugins/leaflet.markercluster.js"></script>
  <script src="src/plugins/leaflet-legend.js"></script>
  <script src="src/jquery-ui.min.js"></script>
  <script src="src/turf.min.js"></script>

  <style>
  #mapdiv {
    height:100vh;
  }

  .col-xs-12, .col-xs-6, .col-xs-4 {
    padding:3px;
  }

  #divProject {
    background-color: beige;
  }

  #divBUOWL {
    background-color: #ffffb3;
  }

  #divEagle {
    background-color: #ccffb3;
  }

  #divRaptor {
    background-color: #e6ffff;
  }

  .errorMsg {
    padding:0;
    text-align:center;
    background-color:darksalmon;
  }

  .text-labels {
    font-size:16px;
    font-weight: bold;
    color:red;
    background: aqua;
    text-align: center;
    display: inline-block;
    padding: 5pt;
    border: 3px double darkred;
    border-radius: 5pt;
    margin-left: -15pt;
    margin-top: -8pt;
  }

  .eagle-labels {
    font-size:12px;
    font-weight: bold;
    color:white;
    background: black;
    text-align: center;
    display: inline-block;
    padding: 5pt;
    border: 3px double red;
    border-radius: 5pt;
    margin-left: -20pt;
    margin-top: -32pt;
  }

  </style>
</head>
<body>
  <div id="side-bar" class="col-md-3">
    <button id='btnLocate' class='btn btn-primary btn-block'>Locate</button>
    <button id="btnShowLegend" class='btn btn-primary btn-block'>Show Legend</button>
    <button id="btnTransparent" class='btn btn-primary btn-block'>Make Polygons Transparent</button>
    <div id="legend">
      <div id="lgndLinearProjects">
        <h4 class="text-center">Linear Projects <i id="btnLinearProjects" class="fa fa-server"></i></h4>
        <div id="lgndLinearProjectsDetail">
          <svg height="270" width="100%">
            <line x1="10" y1="10" x2="40" y2="10" style="stroke:peru; stroke-width:2;"/>
            <text x="50" y="15" style="font-family:sans-serif; font-size:16px;">Pipeline</text>
            <line x1="10" y1="40" x2="40" y2="40" style="stroke:navy; stroke-width:2;"/>
            <text x="50" y="45" style="font-family:sans-serif; font-size:16px;">Flowline</text>
            <line x1="10" y1="70" x2="40" y2="70" style="stroke:navy; stroke-width:2;stroke-dasharray: 5,5"/>
            <text x="50" y="75" style="font-family:sans-serif; font-size:16px;">Flowline - Estimated</text>
            <line x1="10" y1="100" x2="40" y2="100" style="stroke: darkgreen; stroke-width: 2;"/>
            <text x="50" y="105" style="font-family: sans-serif; font-size: 16px">Electric Line</text>
            <line x1="10" y1="130" x2="40" y2="130" style="stroke: darkred; stroke-width: 2;"/>
            <text x="50" y="135" style="font-family: sans-serif; font-size: 16px">Access Road - Confirmed</text>
            <line x1="10" y1="160" x2="40" y2="160" style="stroke: darkred; stroke-width: 2; stroke-dasharray: 5, 5;"/>
            <text x="50" y="165" style="font-family: sans-serif; font-size: 16px">Access Road - Estimated</text>
            <line x1="10" y1="190" x2="40" y2="190" style="stroke: indigo; stroke-width: 2;"/>
            <text x="50" y="195" style="font-family: sans-serif; font-size: 16px">Extraction</text>
            <line x1="10" y1="220" x2="40" y2="220" style="stroke: darkgoldenrod; stroke-width: 2;"/>
            <text x="50" y="225" style="font-family: sans-serif; font-size: 16px">Other</text>
            <rect x="10" y="240" width="30" height="20" style="stroke-width: 4; stroke: gray; stroke-dasharray: 5, 5; fill: yellow; fill-opacity:0.0;"/>
            <text x="50" y="255" style="font-family: sans-serif; font-size: 16px;">Right-of-way</text>
          </svg>
        </div>
      </div>
      <div id="lgndBurrowingOwlHabitat">
        <h4 class="text-center">Burrowing Owl Habitat <i id="btnBUOWL" class="fa fa-server"></i></h4>
        <div id="lgndBUOWLDetail">
          <svg height="90">
            <rect x="10" y="5" width="30" height="20" style="stroke-width: 4; stroke: deeppink; fill: yellow; fill-opacity:0.5;"/>
            <text x="50" y="20" style="font-family: sans-serif; font-size: 16px;">Historically Occupied</text>
            <rect x="10" y="35" width="30" height="20" style="stroke-width: 4; stroke: yellow; fill: yellow; fill-opacity:0.5;"/>
            <text x="50" y="50" style="font-family: sans-serif; font-size: 16px;">Not Historically Occupied</text>
            <rect x="10" y="65" width="30" height="20" style="stroke-width: 4; stroke: yellow; stroke-dasharray: 5, 5; fill: yellow; fill-opacity:0.0;"/>
            <text x="50" y="80" style="font-family: sans-serif; font-size: 16px;">300m Buffer</text>
          </svg>
        </div>
      </div>
      <div id="lgndEagleNests">
        <h4 class="text-center">Eagle Nests <i id="btnEagle" class="fa fa-server"></i></h4>
        <div id="lgndEagleDetail">
          <svg height="60">
            <circle cx="25" cy="15" r="10" style="stroke-width: 4; stroke: deeppink; fill: chartreuse; fill-opacity:0.5;"/>
            <text x="50" y="20" style="font-family: sans-serif; font-size: 16px;">Active Nest</text>
            <circle cx="25" cy="45" r="10" style="stroke-width: 4; stroke: chartreuse; fill: chartreuse; fill-opacity:0.5;"/>
            <text x="50" y="50" style="font-family: sans-serif; font-size: 16px;">Unknown status</text>
          </svg>
        </div>
      </div>
      <div id="lgndRaptorNests">
        <h4 class="text-center">Raptor Nests <i id="btnRaptor" class="fa fa-server"></i></h4>
        <div id="lgndRaptorDetail">
          <svg height="90">
            <circle cx="25" cy="15" r="10" style="stroke-width: 4; stroke: deeppink; fill: cyan; fill-opacity:0.5;"/>
            <text x="50" y="20" style="font-family: sans-serif; font-size: 16px;">Active Nest</text>
            <circle cx="25" cy="45" r="10" style="stroke-width: 4; stroke: deeppink; stroke-dasharray: 5, 5; fill: cyan; fill-opacity:0.5;"/>
            <text x="50" y="50" style="font-family: sans-serif; font-size: 16px;">Fledged Nest</text>
            <circle cx="25" cy="75" r="10" style="stroke-width: 4; stroke: cyan; fill: cyan; fill-opacity:0.5;"/>
            <text x="50" y="80" style="font-family: sans-serif; font-size: 16px;">Unknown status</text>
          </svg>
        </div>
      </div>
      <div id="lgndHeronRookeries">
        <h4 class="text-center">Heron Rookeries <i id="btnGBH" class="fa fa-server"></i></h4>
        <div id="lgndGBHDetail">
          <svg height="40">
            <rect x="10" y="5" width="30" height="20" style="stroke-width: 4; stroke: fuchsia; fill: fuchsia; fill-opacity:0.5;"/>
          </svg>
        </div>
      </div>
    </div>
    <div id="divProject" class="col-xs-12">
      <div id="divProjectLabel" class="text-center col-xs-12">
        <h4 id="lblProject">Linear Projects <button id="btnRefreshLinear" class="btn btn-info"><i class="fa fa-refresh"></i></button></h4>
      </div>
      <div id="divProjectError" class="errorMsg col-xs-12"></div>
      <div id="divFindProject" class="form-group has-error">
        <div class="col-xs-6">
          <input type="text" id="txtFindProject" class="form-control" placeholder="Project ID">
        </div>
        <div class="col-xs-6">
          <button id="btnFindProject" class="btn btn-primary btn-block" disabled>Find Project</button>
        </div>
      </div>
      <div id="divFilterProject" class="col-xs-12">
        <div class="col-xs-4">
          <input type='checkbox' name='fltProject' value='Pipeline' checked>Pipelines<br>
          <input type='checkbox' name='fltProject' value='Road' checked>Access Roads
          <button id="btnProjectFilterAll" class="btn btn-primary btn-block">Check All</button>
        </div>
        <div class="col-xs-4">
          <input type='checkbox' name='fltProject' value='Electric' checked>Electric Lines<br>
          <input type='checkbox' name='fltProject' value='Extraction' checked>Extractions
          <button id="btnProjectFilterNone" class="btn btn-primary btn-block">Uncheck All</button>
        </div>
        <div class="col-xs-4">
          <input type='checkbox' name='fltProject' value='Flowline' checked>Flowlines<br>
          <input type='checkbox' name='fltProject' value='Other' checked>Other
          <button id="btnProjectFilter" class="btn btn-primary btn-block">Filter</button>
        </div>
      </div>
      <div class="" id="divProjectData"></div>
    </div>
    <div id="divBUOWL" class="col-xs-12">
      <div id="divBUOWLLabel" class="text-center col-xs-12">
        <h4 id="lblBUOWL">BUOWL Habitat <button id="btnRefreshBUOWL" class="btn btn-info"><i class="fa fa-refresh"></i></button></h4>
      </div>
      <div id="divBUOWLError" class="errorMsg col-xs-12"></div>
      <div id="divFindBUOWL" class="form-group has-error">
        <div class="col-xs-6">
          <input type="text" id="txtFindBUOWL" class="form-control" placeholder="Habitat ID">
        </div>
        <div class="col-xs-6">
          <button id="btnFindBUOWL" class="btn btn-primary btn-block" disabled>Find BUOWL</button>
        </div>
      </div>
      <div id="divFilterBUOWL" class="col-xs-12">
        <div class="col-xs-4">
          <input type='radio' name='fltBUOWL' value='ALL' checked>All
        </div>
        <div class="col-xs-4">
          <input type='radio' name='fltBUOWL' value='Yes'>Historically Occupied
        </div>
        <div class="col-xs-4">
          <input type='radio' name='fltBUOWL' value='Undetermined'>Undetermined
        </div>
      </div>
      <div class="" id="divBUOWLData"></div>
    </div>
    <div id="divEagle" class="col-xs-12">
      <div id="divEagleLabel" class="text-center col-xs-12">
        <h4 id="lblEagle">Eagle Nests<button id="btnRefreshEagles" class="btn btn-info"><i class="fa fa-refresh"></i></button></h4>
      </div>
      <div id="divEagleError" class="errorMsg col-xs-12"></div>
      <div id="divFindEagle" class="form-group has-error">
        <div class="col-xs-6">
          <input type="text" id="txtFindEagle" class="form-control" placeholder="Eagle Nest ID">
        </div>
        <div class="col-xs-6">
          <button id="btnFindEagle" class="btn btn-primary btn-block" disabled>Find Eagle Nest</button>
        </div>
      </div>
      <div id="divFilterEagle" class="col-xs-12">
        <div class="col-xs-4">
          <input type='radio' name='fltEagle' value='ALL' checked>All
        </div>
        <div class="col-xs-4">
          <input type='radio' name='fltEagle' value='ACTIVE NEST'>Active
        </div>
        <div class="col-xs-4">
          <input type='radio' name='fltEagle' value='INACTIVE LOCATION'>Inactive
        </div>
      </div>
      <div class="" id="divEagleData"></div>
    </div>
    <div id="divRaptor" class="col-xs-12">
      <div id="divRaptorLabel" class="text-center col-xs-12">
        <h4 id="lblRaptor">Raptor Nests<button id="btnRefreshRaptors" class="btn btn-info"><i class="fa fa-refresh"></i></button></h4>
      </div>
      <div id="divRaptorError" class="errorMsg col-xs-12"></div>
      <div id="divFindRaptor" class="form-group has-error">
        <div class="col-xs-6">
          <input type="text" id="txtFindRaptor" class="form-control" placeholder="Raptor Nest ID">
        </div>
        <div class="col-xs-6">
          <button id="btnFindRaptor" class="btn btn-primary btn-block" disabled>Find Raptor Nest</button>
        </div>
      </div>
      <div id="divFilterRaptor" class="col-xs-12">
        <div class="col-xs-3">
          <input type='radio' name='fltRaptor' value='ALL' checked>All
        </div>
        <div class="col-xs-3">
          <input type='radio' name='fltRaptor' value='ACTIVE NEST'>Active
        </div>
        <div class="col-xs-3">
          <input type='radio' name='fltRaptor' value='INACTIVE NEST'>Inactive
        </div>
        <div class="col-xs-3">
          <input type='radio' name='fltRaptor' value='FLEDGED NEST'>Fledged
        </div>
      </div>
      <div class="" id="divRaptorData"></div>
    </div>
  </div>
  <div id="mapdiv" class="col-md-12"></div>
  <script>
  var mymap;
  var lyrOSM;
  var lyrWatercolor;
  var lyrTopo;
  var lyrImagery;
  var lyrOutdoors;
  var lyrEagleNests;
  var lyrRaptorNests;
  var lyrClientLines;
  var lyrClientLinesBuffer;
  var lyrBUOWL;
  var lyrBUOWLbuffer;
  var jsnBUOWLbuffer;
  var lyrGBH;
  var lyrSearch;
  var lyrMarkerCluster;
  var mrkCurrentLocation;
  var fgpDrawnItems;
  var ctlAttribute;
  var ctlScale;
  var ctlMouseposition;
  var ctlMeasure;
  var ctlEasybutton;
  var ctlSidebar;
  var ctlLayers;
  var ctlStyle;
  var ctlLegend;
  var objBasemaps;
  var objOverlays;
  var arProjectIDs = [];
  var arHabitatIDs = [];
  var arEagleIDs = [];
  var arRaptorIDs = [];

  //limit query FOR TEST PURPOSE to speed up the loading process on the canvas
  var queryLimitNr=2000;

  $(document).ready(function(){

    //************************ Map Initialization ****************

    mymap = L.map('mapdiv', {center:[40.18, -104.83], zoom:11, attributionControl:false});

    ctlSidebar = L.control.sidebar('side-bar').addTo(mymap);

    ctlEasybutton = L.easyButton('glyphicon-transfer', function(){
      ctlSidebar.toggle();
    }).addTo(mymap);

    ctlAttribute = L.control.attribution().addTo(mymap);
    ctlAttribute.addAttribution('OSM');
    ctlAttribute.addAttribution('&copy; <a href="http://millermountain.com">Miller Mountain LLC</a>');

    ctlScale = L.control.scale({position:'bottomleft', metric:false, maxWidth:200}).addTo(mymap);

    ctlMouseposition = L.control.mousePosition().addTo(mymap);

    ctlMeasure = L.control.polylineMeasure().addTo(mymap);

    //************************ Layer Initialization **********

    lyrOSM = L.tileLayer.provider('OpenStreetMap.Mapnik');
    lyrTopo = L.tileLayer.provider('OpenTopoMap');
    lyrImagery = L.tileLayer.provider('Esri.WorldImagery');
    lyrOutdoors = L.tileLayer.provider('Thunderforest.Outdoors');
    lyrWatercolor = L.tileLayer.provider('Stamen.Watercolor');
    mymap.addLayer(lyrOSM);

    fgpDrawnItems = new L.FeatureGroup();
    fgpDrawnItems.addTo(mymap);

    //************************  Load Data  ******
    refreshEagles();
    refreshRaptors();
    refreshLinears();
    refreshBUOWL();
    refreshGBH();

    //************************ Setup Layer Control  ***************

    objBasemaps = {
      "Open Street Maps": lyrOSM,
      "Topo Map":lyrTopo,
      "Imagery":lyrImagery,
      "Outdoors":lyrOutdoors,
      "Watercolor":lyrWatercolor
    };

    objOverlays = {
    };

    ctlLayers = L.control.layers(objBasemaps, objOverlays).addTo(mymap);

    mymap.on('overlayadd', function(e){
      var strDiv = "#lgnd"+stripSpaces(e.name);
      $(strDiv).show();
      //Adding the buffer of the layer when it's added
      //And bring the layer to front in order to the Popup to work
      if (e.name=="Linear Projects"){
        lyrClientLinesBuffer.addTo(mymap);
        lyrClientLines.bringToFront();
      }
      if (e.name=="Burrowing Owl Habitat"){
        lyrBUOWLbuffer.addTo(mymap);
        lyrBUOWL.bringToFront();
      }
    });

    mymap.on('overlayremove', function(e){
      var strDiv = "#lgnd"+stripSpaces(e.name);
      $(strDiv).hide();
      //Remove the buffer of the layer when it's removed
      if (e.name=="Linear Projects"){
        lyrClientLinesBuffer.remove();
      }
      if (e.name=="Burrowing Owl Habitat"){
        lyrBUOWLbuffer.remove();
      }
    });

    ctlLegend = new L.Control.Legend({
      position:'topright',
      controlButton:{title:"Legend"}
    }).addTo(mymap);

    $(".legend-container").append($("#legend"));
    $(".legend-toggle").append($("<i class='legend-toggle-icon fa fa-server fa-2x' style='color:#000'></i>"));
    //************************ Location Events **************

    mymap.on('locationfound', function(e) {
      console.log(e);
      if (mrkCurrentLocation) {
        mrkCurrentLocation.remove();
      }
      mrkCurrentLocation = L.circle(e.latlng, {radius:e.accuracy/2}).addTo(mymap);
      mymap.setView(e.latlng, 14);
    });

    mymap.on('locationerror', function(e) {
      console.log(e);
      alert("Location was not found");
    })

  });
  //************************ Client Linears **********
  //toggle the legend for Linear Projects
  $("#btnLinearProjects").click(function(){
    $("#lgndLinearProjectsDetail").toggle();
  });

  function styleClientLinears(json) {
    var att = json.properties;
    switch (att.type) {
      //If the color specified here is changed. It also should be changed in the legend
      case 'Pipeline':
      return {color:'peru'};
      break;
      case 'Flowline':
      return {color:'navy'};
      break;
      case 'Flowline, est.':
      return {color:'navy', dashArray:"5,5"};
      break;
      case 'Electric Line':
      return {color:'darkgreen'};
      break;
      case 'Access Road - Confirmed':
      return {color:'darkred'};
      break;
      case 'Access Road - Estimated':
      return {color:'darkred', dashArray:"5,5"};
      break;
      case 'Extraction':
      return {color:'indigo'};
      break;
      default:
      return {color:'darkgoldenrod'}
    }
  }

  function processClientLinears(json, lyr) {
    var att = json.properties;
    lyr.bindTooltip("<h4>Linear Project: "+att.project+"</h4>Type: "+att.type+"<br>ROW Width: "+att.row_width+"<br>Length: "+returnMultiLength(lyr.getLatLngs()).toFixed(0));
    arProjectIDs.push(att.project.toString());
    var jsnBuffer = turf.buffer(json, att.row_width/1000, {units:'kilometers'});
    //If the style here is changed it also should be changed in the legend
    var lyrBuffer = L.geoJSON(jsnBuffer, {style:{color:'gray', dashArray:'5,5'}});
    lyrClientLinesBuffer.addLayer(lyrBuffer);
    console.log("Buffer for lyrClientLines made on the client, using turf.js!");
  }

  function filterClientLines(json) {
    var arProjectFilter=[];
    $("input[name=fltProject]").each(function(){
      if (this.checked) {
        arProjectFilter.push(this.value);
      }
    });
    var att = json.properties;
    switch (att.type) {
      case "Pipeline":
      return (arProjectFilter.indexOf('Pipeline')>=0);
      break;
      case "Flowline":
      return (arProjectFilter.indexOf('Flowline')>=0);
      break;
      case "Flowline, est.":
      return (arProjectFilter.indexOf('Flowline')>=0);
      break;
      case "Electric Line":
      return (arProjectFilter.indexOf('Electric')>=0);
      break;
      case "Access Road - Confirmed":
      return (arProjectFilter.indexOf('Road')>=0);
      break;
      case "Access Road - Estimated":
      return (arProjectFilter.indexOf('Road')>=0);
      break;
      case "Extraction":
      return (arProjectFilter.indexOf('Extraction')>=0);
      break;
      default:
      return (arProjectFilter.indexOf('Other')>=0);
      break;
    }
  }

  $("#txtFindProject").on('keyup paste', function(){
    var val = $("#txtFindProject").val();
    testLayerAttribute(arProjectIDs, val, "PROJECT ID", "#divFindProject", "#divProjectError", "#btnFindProject");
  });

  $("#btnFindProject").click(function(){
    var val = $("#txtFindProject").val();
    var lyr = returnLayerByAttribute(lyrClientLines,'project',val);
    if (lyr) {
      if (lyrSearch) {
        lyrSearch.remove();
      }
      lyrSearch = L.geoJSON(lyr.toGeoJSON(), {style:{color:'red', weight:10, opacity:0.5}}).addTo(mymap);
      mymap.fitBounds(lyr.getBounds().pad(1));
      var att = lyr.feature.properties;
      $("#divProjectData").html("<h4 class='text-center'>Attributes</h4><h5>Type: "+att.type+"</h5><h5>ROW width: "+att.row_width+ "m </h5>");
      $("#divProjectError").html("");

      fgpDrawnItems.clearLayers();
      fgpDrawnItems.addLayer(lyr);

    } else {
      $("#divProjectError").html("**** Project ID not found ****");
    }
  });

  $("#lblProject").click(function(){
    $("#divProjectData").toggle();
  });

  $("#btnProjectFilterAll").click(function(){
    $("input[name=fltProject]").prop('checked', true);
  });

  $("#btnProjectFilterNone").click(function(){
    $("input[name=fltProject]").prop('checked', false);
  });

  //Refresh the Linears Project when the Refresh button is clicked
  $("#btnRefreshLinear").click(function(){
    refreshLinears();
  });

  $("#btnProjectFilter").click(function(){
    refreshLinears();
  });

  function refreshLinears() {
    //The BUFFER for this layer is not made on the server. As an example the buffer for the BUOWL is made on the server.
    //It was not tested on the web to check each one is faster. That's why there's both layers using different methods to be tested
    $.ajax({url:'load_data.php',
    data: {tbl:'dj_linear', flds:"id, type, row_width, project", limit:queryLimitNr},
    type: 'POST',
    success: function(response){
      //catching the ERROR (if any)
      if (response.substring(0,5)=="ERROR"){
        alert(response);
      }else {
        //Array that will be processed in the processClientLinears() and will have the Unique IDs for the existing projects
        arProjectIDs=[];
        jsnLinears = JSON.parse(response);
        //Once the response is a JSON. Use JSON.parse() to format in the right way
        console.log("refreshLinears Response:");
        console.log(jsnLinears);
        if (lyrClientLines) {
          ctlLayers.removeLayer(lyrClientLines);
          lyrClientLines.remove();
          lyrClientLinesBuffer.remove();
        }
        //buffers will be created in the processClientLinears()
        lyrClientLinesBuffer = L.featureGroup();
        lyrClientLines = L.geoJSON(jsnLinears, {style:styleClientLinears, onEachFeature:processClientLinears, filter:filterClientLines}).addTo(mymap);

        //Adds the Layer to the control Layer panel, specifying its name
        //If the name specified here is changed. It also should be changed the name of the legend div
        ctlLayers.addOverlay(lyrClientLines, "Linear Projects");

        //Sort the array, that was filled out in the processClientLinears(), with unique IDs in a crescent order
        //The arrays contain the IDs in a text format. The below code is WKformat in JS to sort in a crescent order
        arProjectIDs.sort(function(a,b){return a-b});
        //Completing the input text for the user with the ID of the existing projects
        $("#txtFindProject").autocomplete({
          source:arProjectIDs
        });
        //Add the layers to the map
        lyrClientLinesBuffer.addTo(mymap);
        lyrClientLines.bringToFront();
      }
    },
    //
    error: function(xhr, status, error){
      alert("ERROR: "+error);
    }
  });
}

//************************ BUOWL Functions
//Refresh the BUOWL layer when the Refresh button is clicked
$("#btnRefreshBUOWL").click(function(){
  refreshBUOWL();
});

$("#btnBUOWL").click(function(){
  $("#lgndBUOWLDetail").toggle();
});

function styleBUOWL(json){
  //If the color specified here is changed. It also should be changed in the legend
  var att = json.properties;
  switch (att.hist_occup){
    case 'Yes':
    return {color:'deeppink', fillColor:'yellow'};
    break;
    case 'Undetermined':
    return {color:'yellow'};
    break;
  }
}

function processBUOWL(json, lyr){
  var att = json.properties;
  lyr.bindTooltip("<h4>Habitat ID: "+att.habitat_id+"</h4>Historically Occupied: "+att.hist_occup+"<br>Status: "+att.recentstatus);
  arHabitatIDs.push(att.habitat_id.toString())
}

$("#txtFindBUOWL").on('keyup paste', function(){
  var val = $("#txtFindBUOWL").val();
  testLayerAttribute(arHabitatIDs, val, "Habitat ID", "#divFindBUOWL", "#divBUOWLError", "#btnFindBUOWL");
});

$("#btnFindBUOWL").click(function(){
  //##FUNCTION That's called when the #btnFindBUOWL is clicked

  var val = $("#txtFindBUOWL").val();
  var lyr = returnLayerByAttribute(lyrBUOWL,'habitat_id',val);
  if (lyr) {
    if (lyrSearch) {
      lyrSearch.remove();
    }
    lyrSearch = L.geoJSON(lyr.toGeoJSON(), {style:{color:'red', weight:10, opacity:0.5, fillOpacity:0}}).addTo(mymap);
    mymap.fitBounds(lyr.getBounds().pad(1));
    var att = lyr.feature.properties;
    $("#divBUOWLData").html("<h4 class='text-center'>Attributes</h4><h5>Habitat: "+att.habitat+"</h5><h5>Historically Occupied: "+att.hist_occup+"</h5><h5>Recent Status: "+att.recentstatus+"</h5>");
    $("#divBUOWLError").html("");

    fgpDrawnItems.clearLayers();
    fgpDrawnItems.addLayer(lyr);

  } else {
    $("#divBUOWLError").html("**** Habitat ID not found ****");
  }
});

$("#lblBUOWL").click(function(){
  $("#divBUOWLData").toggle();
});

$("input[name=fltBUOWL]").click(function(){
  var optFilter = $("input[name=fltBUOWL]:checked").val();
  if (optFilter=='ALL') {
    refreshBUOWL();
  } else {
    refreshBUOWL("hist_occup='"+optFilter+"'");
  }
});


function refreshBUOWL(whr) {
  if(whr){
    var objData = {tbl:'dj_buowl', flds:"id, habitat_id, habitat, recentstatus, hist_occup", where:whr, limit:queryLimitNr};
  }else{
    var objData = {tbl:'dj_buowl', flds:"id, habitat_id, habitat, recentstatus, hist_occup",limit:queryLimitNr};
  }
  $.ajax({url:'load_data.php',
  data: objData,
  type: 'POST',
  success: function(response){
    //catching the ERROR (if any)
    if (response.substring(0,5)=="ERROR"){
      alert(response);
    }else {
      //Array that will be processed in the processBUOWL() and will have the Unique IDs for the existing features
        arHabitatIDs=[];
        //Once the response is a JSON. Use JSON.parse() to format in the right way
        jsnBUOWL = JSON.parse(response);
        console.log("refreshBUOWL Response:");
        console.log(jsnBUOWL);
        if (lyrBUOWL) {
          ctlLayers.removeLayer(lyrBUOWL);
          lyrBUOWL.remove();
          lyrBUOWLbuffer.remove();
        }
        //Create the BUOWL layer and add tto the map
        lyrBUOWL = L.geoJSON(jsnBUOWL, {style:styleBUOWL, onEachFeature:processBUOWL}).addTo(mymap);

        //Adds the Layer to the control Layer panel, specifying its name
        //If the name specified here is changed. It also should be changed the name of the legend div
        ctlLayers.addOverlay(lyrBUOWL, "Burrowing Owl Habitat");

        //Sort the array, that was filled out in the processBUOWL(), with unique IDs in a crescent order
        //The arrays contain the IDs in a text format. The below code is WKformat in JS to sort in a crescent order
        arHabitatIDs.sort(function(a,b){return a-b});
        //Completing the input text for the user with the ID of the existing projects
        $("#txtFindBUOWL").autocomplete({
          source:arHabitatIDs
        });

        //Create the buffer for this layer, in the server side and add it to the map
        //Pass the same 'where' clause to the buffer in order to filter it
        refreshBUOWLbuffer(whr);
        //From turf.js
        // jsnBUOWLbuffer = turf.buffer(lyrBUOWL.toGeoJSON(), 0.3, {units:'kilometers'});
        // lyrBUOWLbuffer = L.geoJSON(jsnBUOWLbuffer, {style:{color:'yellow', dashArray:'5,5', fillOpacity:0}}).addTo(mymap);

        //Bring the BUOWL layer to front in order to the Popup to work
        lyrBUOWL.bringToFront();
      }
  },
  error: function(xhr, status, error){
    alert("ERROR: "+error);
  }
});
}

function refreshBUOWLbuffer(whr) {
  if(whr){
    var objData = {tbl:'dj_buowl', flds:"id, habitat_id, habitat, recentstatus, hist_occup", distance:300, where:whr, limit:queryLimitNr};
  }else{
    var objData = {tbl:'dj_buowl', flds:"id, habitat_id, habitat, recentstatus, hist_occup", distance:300, limit:queryLimitNr};
  }
  //The BUFFER for this layer is made on the server. As an example the buffer for the Linears Project is made on the client, using turf.js.
  //It was not tested on the web, to check each one is faster. That's why there's both layers using different methods to be tested.
  $.ajax({url:'load_data.php',
  data: objData,
  type: 'POST',
  success: function(response){
    //catching the ERROR (if any)
    if (response.substring(0,5)=="ERROR"){
      alert(response);
    }else {
        //Once the response is a JSON. Use JSON.parse() to format in the right way
        jsnBUOWLbuffer = JSON.parse(response);
        console.log("refreshBUOWLBuffer Response:");
        console.log(jsnBUOWLbuffer);
        if (lyrBUOWLbuffer) {
          lyrBUOWLbuffer.remove();
        }
        //Create the BUOWL buffer layer and add to the map
        lyrBUOWLbuffer = L.geoJSON(jsnBUOWLbuffer, {style:{color:'yellow', dashArray:'5,5', fillOpacity:0}}).addTo(mymap);
        console.log("Buffer for lyrBUOWL made on the server, using ST_Buffer!");

        //Bring the BUOWL layer to front in order to the Popup to work
        lyrBUOWL.bringToFront();
      }
  },
  error: function(xhr, status, error){
    alert("ERROR: "+error);
  }
});
}

//************************  Eagle Functions *****************
//Refresh the Eagles when the Refresh button is clicked
$("#btnRefreshEagles").click(function(){
  refreshEagles();
});

$("#btnEagle").click(function(){
  $("#lgndEagleDetail").toggle();
});

function returnEagleMarker(json, latlng){
  var att = json.properties;
  //If the color specified here is changed. It also should be changed in the legend
  if (att.status=='ACTIVE NEST') {
    var clrNest = 'deeppink';
  } else {
    var clrNest = 'chartreuse';
  }
  arEagleIDs.push(att.nest_id.toString());
  return L.circle(latlng, {radius:804, color:clrNest,fillColor:'chartreuse', fillOpacity:0.5}).bindTooltip("<h4>Eagle Nest: "+att.nest_id+"</h4>Status: "+att.status);
}

//FILTER FUNCTION FOR EAGLE NESTS THROUGH CLIENT SIDE
// function filterEagle(json) {
//   var att=json.properties;
//   var optFilter = $("input[name=fltEagle]:checked").val();
//   if (optFilter=='ALL') {
//     return true;
//   } else {
//     return (att.status==optFilter);
//   }
// }

$("#txtFindEagle").on('keyup paste', function(){
  var val = $("#txtFindEagle").val();
  testLayerAttribute(arEagleIDs, val, "Eagle Nest ID", "#divFindEagle", "#divEagleError", "#btnFindEagle");
});

$("#btnFindEagle").click(function(){
  var val = $("#txtFindEagle").val();
  var lyr = returnLayerByAttribute(lyrEagleNests,'nest_id',val);
  if (lyr) {
    if (lyrSearch) {
      lyrSearch.remove();
    }
    lyrSearch = L.circle(lyr.getLatLng(), {radius:800, color:'red', weight:10, opacity:0.5, fillOpacity:0}).addTo(mymap);
    mymap.setView(lyr.getLatLng(), 14);
    var att = lyr.feature.properties;
    $("#divEagleData").html("<h4 class='text-center'>Attributes</h4><h5>Status: "+att.status+"</h5>");
    $("#divEagleError").html("");

    fgpDrawnItems.clearLayers();
    fgpDrawnItems.addLayer(lyr);

  } else {
    $("#divEagleError").html("**** Eagle Nest ID not found ****");
  }
});

$("#lblEagle").click(function(){
  $("#divEagleData").toggle();
});

$("input[name=fltEagle]").click(function(){
  //Filtering Eagle Nests. It will be mande on the server side.
  var optFilter = $("input[name=fltEagle]:checked").val();
  if(optFilter=="ALL"){
    refreshEagles();
  }else{
    refreshEagles("status='"+optFilter+"'");
  }
});

function refreshEagles(whr) {
  if(whr){ //The data will be filter using 'where' clause
    var objData = {tbl:'dj_eagle', flds:"id, status, nest_id", where:whr, limit:queryLimitNr};
  }else{
    var objData = {tbl:'dj_eagle', flds:"id, status, nest_id", limit:queryLimitNr};
  }
  $.ajax({url:'load_data.php',
  data: objData,
  type: 'POST',
  success: function(response){
    //catching the ERROR (if any)
    if (response.substring(0,5)=="ERROR"){
      alert(response);
    }else {
      //Array that will be filled out in the returnEagleMarker() and will have the Unique IDs for the existing features
      arEagleIDs=[];
      //Once the response is a JSON. Use JSON.parse() to format in the right way
      jsnEagles = JSON.parse(response);
      console.log("refreshEagles Response:");
      console.log(jsnEagles);
      if (lyrEagleNests) {
        ctlLayers.removeLayer(lyrEagleNests);
        lyrEagleNests.remove();
      }
      //Create the BUOWL layer and add tto the map
      lyrEagleNests = L.geoJSON(jsnEagles, {pointToLayer:returnEagleMarker
        //, filter:filterEagle //Filter Function through the client side
      }).addTo(mymap);

      //Adds the Layer to the control Layer panel, specifying its name
      //If the name specified here is changed. It also should be changed the name of the legend div
      ctlLayers.addOverlay(lyrEagleNests, "Eagle Nests");

      //Sort the array, that was filled out in the returnEagleMarker(), with unique IDs in a crescent order
       //The arrays contain the IDs in a text format. The below code is WKformat in JS to sort in a crescent order
      arEagleIDs.sort(function(a,b){return a-b});
      //Completing the input text for the user with the ID of the existing projects
      $("#txtFindEagle").autocomplete({
        source:arEagleIDs
      });
    }
  },
  error: function(xhr, status, error){
    alert("ERROR: "+error);
  }
});
}

//************************ Raptor Functions *****************
//Refresh the Raptor Nests  when the Refresh button is clicked
$("#btnRefreshRaptors").click(function(){
  refreshRaptors();
});

$("#btnRaptor").click(function(){
  $("#lgndRaptorDetail").toggle();
});

function returnRaptorMarker(json, latlng){
  var att = json.properties;
  arRaptorIDs.push(att.nest_id.toString());
  switch (att.recentspecies) {
    //If the color specified here is changed. It also should be changed in the legend
    case 'Red-tail Hawk':
    var radRaptor = 533;
    break;
    case 'Swainsons Hawk':
    var radRaptor = 400;
    break;
    default:
    var radRaptor = 804;
    break;
  }
  switch (att.recentstatus) {
    case 'ACTIVE NEST':
    var optRaptor = {radius:radRaptor, color:'deeppink', fillColor:"cyan", fillOpacity:0.5};
    break;
    case 'INACTIVE NEST':
    var optRaptor = {radius:radRaptor, color:'cyan', fillColor:'cyan', fillOpacity:0.5};
    break;
    case 'FLEDGED NEST':
    var optRaptor = {radius:radRaptor, color:'deeppink', fillColor:"cyan", fillOpacity:0.5, dashArray:"2,8"};
    break;
  }
  return L.circle(latlng, optRaptor).bindPopup("<h4>Raptor Nest: "+att.nest_id+"</h4>Status: "+att.recentstatus+"<br>Species: "+att.recentspecies+"<br>Last Survey: "+att.lastsurvey);
}

$("#txtFindRaptor").on('keyup paste', function(){
  var val = $("#txtFindRaptor").val();
  testLayerAttribute(arRaptorIDs, val, "Raptor Nest ID", "#divFindRaptor", "#divRaptorError", "#btnFindRaptor");
});

$("#btnFindRaptor").click(function(){
  var val = $("#txtFindRaptor").val();
  var lyr = returnLayerByAttribute(lyrRaptorNests,'nest_id',val);
  if (lyr) {
    if (lyrSearch) {
      lyrSearch.remove();
    }
    var att = lyr.feature.properties;
    switch (att.recentspecies) {
      case 'Red-tail Hawk':
      var radRaptor = 533;
      break;
      case 'Swainsons Hawk':
      var radRaptor = 400;
      break;
      default:
      var radRaptor = 804;
      break;
    }
    lyrSearch = L.circle(lyr.getLatLng(), {radius:radRaptor, color:'red', weight:10, opacity:0.5, fillOpacity:0}).addTo(mymap);
    mymap.setView(lyr.getLatLng(), 14);
    $("#divRaptorData").html("<h4 class='text-center'>Attributes</h4><h5>Status: "+att.recentstatus+"</h5><h5>Species: "+att.recentspecies+"</h5><h5>Last Survey: "+att.lastsurvey+"</h5>");
    $("#divRaptorError").html("");

    fgpDrawnItems.clearLayers();
    fgpDrawnItems.addLayer(lyr);

  } else {
    $("#divRaptorError").html("**** Raptor Nest ID not found ****");
  }
});

$("#lblRaptor").click(function(){
  $("#divRaptorData").toggle();
});

$("input[name=fltRaptor]").click(function(){
  //Filtering Eagle Nests. It will be mande on the server side.
  var optFilter = $("input[name=fltRaptor]:checked").val();
  if(optFilter=="ALL"){
    refreshRaptors();
  }else{
    refreshRaptors("recentstatus='"+optFilter+"'");
  }
});

function refreshRaptors(whr) {
  if(whr){
    var objData = { tbl:'dj_raptor',
                    flds:"id, nest_id, recentstatus, recentspecies, lastsurvey",
                    where:whr,
                    limit:queryLimitNr};
  }else {
    var objData = {tbl:'dj_raptor', flds:"id, nest_id, recentstatus, recentspecies, lastsurvey", limit:queryLimitNr};
  }
  $.ajax({url:'load_data.php',
  data: objData,
  type: 'POST',
  success: function(response){
    //catching the ERROR (if any)
    if (response.substring(0,5)=="ERROR"){
      alert(response);
    }else {
      arRaptorIDs=[];
      //Once the response is a JSON. Use JSON.parse() to format in the right way
      jsnRaptors = JSON.parse(response);
      console.log("refreshRaptors Response:");
      console.log(jsnRaptors);
      if (lyrMarkerCluster) {
        ctlLayers.removeLayer(lyrMarkerCluster);
        lyrMarkerCluster.remove();
        lyrRaptorNests.remove();
      }
      //The layer won't be added to the map, because it'll be added by the Masker CLuster.
      lyrRaptorNests = L.geoJSON(jsnRaptors, {pointToLayer:returnRaptorMarker});

      //Sort the array, that was filled out in the returnRaptorMarker(), with unique IDs in a crescent order
      //The arrays contain the IDs in a text format. The below code is WKformat in JS to sort in a crescent order
      arRaptorIDs.sort(function(a,b){return a-b});
      //Completing the input text for the user with the ID of the existing projects
      $("#txtFindRaptor").autocomplete({
        source:arRaptorIDs
      });

      //Create the MarkerClusterGroup for add the lyrRaptorNests
      lyrMarkerCluster = L.markerClusterGroup();
      lyrMarkerCluster.clearLayers();
      lyrMarkerCluster.addLayer(lyrRaptorNests);
      lyrMarkerCluster.addTo(mymap);

      //Adds the Layer to the control Layer panel, specifying its name
      //If the name specified here is changed. It also should be changed the name of the legend div
      ctlLayers.addOverlay(lyrMarkerCluster, "Raptor Nests");
    }
  },
  error: function(xhr, status, error){
    alert("ERROR: "+error);
  }
});
}


//************************ GBH Functions ******
function refreshGBH() {
  $.ajax({url:'load_data.php',
  data: {tbl:'dj_gbh', flds:"id, activity", limit:queryLimitNr},
  type: 'POST',
  success: function(response){
    //catching the ERROR (if any)
    if (response.substring(0,5)=="ERROR"){
      alert(response);
    }else {
      jsnGBH = JSON.parse(response);
      if (lyrGBH) {
        ctlLayers.removeLayer(lyrGBH);
        lyrGBH.remove();
      }
      //If the color specified here is changed. It also should be changed in the legend
      lyrGBH = L.geoJSON(jsnGBH, {style:{color:'fuchsia'}}).bindTooltip("GBH Nesting Area").addTo(mymap);

      //Adds the Layer to the control Layer panel, specifying its name
      //If the name specified here is changed. It also should be changed the name of the legend div
      ctlLayers.addOverlay(lyrGBH, "Heron Rookeries");
    }
  },
  error: function(xhr, status, error){
    alert("ERROR: "+error);
  }
});
}

//************************  jQuery Event Handlers  ************

$("#btnGBH").click(function(){
  $("#lgndGBHDetail").toggle();
});

$("#btnLocate").click(function(){
  mymap.locate();
});

$("#btnShowLegend").click(function(){
  $("#legend").toggle();
});

$("#btnTransparent").click(function(){
  //Make Layer Transparent by toggling opacity
  if ($("#btnTransparent").html()=="Fill Polygons"){
    lyrRaptorNests.setStyle({fillOpacity:0.5});
    lyrEagleNests.setStyle({fillOpacity:0.5});
    lyrBUOWL.setStyle({fillOpacity:0.5});
    lyrGBH.setStyle({fillOpacity:0.5});
    $("#btnTransparent").html("Make Polygons Transparent");
  }else{
    lyrRaptorNests.setStyle({fillOpacity:0});
    lyrEagleNests.setStyle({fillOpacity:0});
    lyrBUOWL.setStyle({fillOpacity:0});
    lyrGBH.setStyle({fillOpacity:0});
    $("#btnTransparent").html("Fill Polygons");
  }

});

//************************  General Functions *********

function LatLngToArrayString(ll) {
  return "["+ll.lat.toFixed(5)+", "+ll.lng.toFixed(5)+"]";
}

function returnLayerByAttribute(lyr,att,val) {
  var arLayers = lyr.getLayers();
  for (i=0;i<arLayers.length-1;i++) {
    var ftrVal = arLayers[i].feature.properties[att];
    if (ftrVal==val) {
      return arLayers[i];
    }
  }
  return false;
}

function returnLayersByAttribute(lyr,att,val) {
  var arLayers = lyr.getLayers();
  var arMatches = [];
  for (i=0;i<arLayers.length-1;i++) {
    var ftrVal = arLayers[i].feature.properties[att];
    if (ftrVal==val) {
      arMatches.push(arLayers[i]);
    }
  }
  if (arMatches.length) {
    return arMatches;
  } else {
    return false;
  }
}

function testLayerAttribute(ar, val, att, fg, err, btn) {
  if (ar.indexOf(val)<0) {
    $(fg).addClass("has-error");
    $(err).html("**** "+att+" NOT FOUND ****");
    $(btn).attr("disabled", true);
  } else {
    $(fg).removeClass("has-error");
    $(err).html("");
    $(btn).attr("disabled", false);
  }
}

function returnLength(arLL) {
  var total=0;

  for (var i=1;i<arLL.length;i++) {
    total = total + arLL[i-1].distanceTo(arLL[i]);
  }

  return total;

}

function returnMultiLength(arArLL) {
  var total=0;

  for (var i=0; i<arArLL.length;i++) {
    total = total + returnLength(arArLL[i]);
  }

  return total;
}

//Removes all the spaces of a string
function stripSpaces(str) {
  return str.replace(/\s+/g, '');
}

</script>
</body>
</html>
