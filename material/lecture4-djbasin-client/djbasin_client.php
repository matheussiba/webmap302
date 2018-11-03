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
            <div id="legend">
                <div id="lgndClientLinears">
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
                <div id="lgndEagleNest">
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
                <div id="lgndRaptorNest">
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
                <div id="lgndGBHRookeries">
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
                    <h4 id="lblProject">Linear Projects</h4>
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
                    <h4 id="lblBUOWL">BUOWL Habitat</h4>
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
                    <h4 id="lblEagle">Eagle Nests</h4>
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
                    <h4 id="lblRaptor">Raptor Nests</h4>
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
            
            $(document).ready(function(){
                
                //  ********* Map Initialization ****************
                
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
                
                //   *********** Layer Initialization **********
                
                lyrOSM = L.tileLayer.provider('OpenStreetMap.Mapnik');
                lyrTopo = L.tileLayer.provider('OpenTopoMap');
                lyrImagery = L.tileLayer.provider('Esri.WorldImagery');
                lyrOutdoors = L.tileLayer.provider('Thunderforest.Outdoors');
                lyrWatercolor = L.tileLayer.provider('Stamen.Watercolor');
                mymap.addLayer(lyrOSM);
                
                fgpDrawnItems = new L.FeatureGroup();
                fgpDrawnItems.addTo(mymap);
                
                lyrEagleNests = L.geoJSON.ajax('data/wildlife_eagle.geojson', {pointToLayer:returnEagleMarker, filter:filterEagle}).addTo(mymap);
                lyrEagleNests.on('data:loaded', function(){
                    arEagleIDs.sort(function(a,b){return a-b});
                    $("#txtFindEagle").autocomplete({
                        source:arEagleIDs
                    });
                });
                
                lyrMarkerCluster = L.markerClusterGroup();
                lyrRaptorNests = L.geoJSON.ajax('data/wildlife_raptor.geojson', {pointToLayer:returnRaptorMarker, filter:filterRaptor});
                lyrRaptorNests.on('data:loaded', function(){
                    arRaptorIDs.sort(function(a,b){return a-b});
                    $("#txtFindRaptor").autocomplete({
                        source:arRaptorIDs
                    });
                    lyrMarkerCluster.clearLayers();
                    lyrMarkerCluster.addLayer(lyrRaptorNests);
                    lyrMarkerCluster.addTo(mymap);
                });
                
                lyrClientLinesBuffer = L.featureGroup();
                lyrClientLines = L.geoJSON.ajax('data/client_lines.geojson', {style:styleClientLinears, onEachFeature:processClientLinears, filter:filterClientLines}).addTo(mymap);
                lyrClientLines.on('data:loaded', function(){
                    arProjectIDs.sort(function(a,b){return a-b});
                    $("#txtFindProject").autocomplete({
                        source:arProjectIDs
                    });
                    lyrClientLinesBuffer.addTo(mymap);
                    lyrClientLines.bringToFront();
                });
                
                lyrBUOWL = L.geoJSON.ajax('data/wildlife_buowl.geojson', {style:styleBUOWL, onEachFeature:processBUOWL, filter:filterBUOWL}).addTo(mymap);
                lyrBUOWL.on('data:loaded', function(){
                    arHabitatIDs.sort(function(a,b){return a-b});
                    $("#txtFindBUOWL").autocomplete({
                        source:arHabitatIDs
                    });
                    jsnBUOWLbuffer = turf.buffer(lyrBUOWL.toGeoJSON(), 0.3, 'kilometers');
                    console.log(jsnBUOWLbuffer);
                    lyrBUOWLbuffer = L.geoJSON(jsnBUOWLbuffer, {style:{color:'yellow', dashArray:'5,5', fillOpacity:0}}).addTo(mymap);
                    lyrBUOWL.bringToFront();
                });
                
                lyrGBH = L.geoJSON.ajax('data/wildlife_gbh.geojson', {style:{color:'fuchsia'}}).bindTooltip("GBH Nesting Area").addTo(mymap);
                
                // ********* Setup Layer Control  ***************
                
                objBasemaps = {
                    "Open Street Maps": lyrOSM,
                    "Topo Map":lyrTopo,
                    "Imagery":lyrImagery,
                    "Outdoors":lyrOutdoors,
                    "Watercolor":lyrWatercolor
                };
                
                objOverlays = {
                    "Client Linears":lyrClientLines,
                    "Burrowing Owl Habitat":lyrBUOWL,
                    "Eagle Nest":lyrEagleNests,
                    "Raptor Nest":lyrMarkerCluster,
                    "GBH Rookeries":lyrGBH,
                    "Drawn Items":fgpDrawnItems
                };
                
                ctlLayers = L.control.layers(objBasemaps, objOverlays).addTo(mymap);
                
                mymap.on('overlayadd', function(e){
                    var strDiv = "#lgnd"+stripSpaces(e.name);
                    $(strDiv).show();
                });
                
                mymap.on('overlayremove', function(e){
                    var strDiv = "#lgnd"+stripSpaces(e.name);
                    $(strDiv).hide();
                });
                
                ctlLegend = new L.Control.Legend({
                    position:'topright',
                    controlButton:{title:"Legend"}
                }).addTo(mymap);
                
                $(".legend-container").append($("#legend"));
                $(".legend-toggle").append($("<i class='legend-toggle-icon fa fa-server fa-2x' style='color:#000'></i>"));
                // ************ Location Events **************
                
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

            //  ********* BUOWL Functions

            $("#btnBUOWL").click(function(){
               $("#lgndBUOWLDetail").toggle(); 
            });
            
            function styleBUOWL(json){
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
            
            function filterBUOWL(json){
                var att = json.properties;
                if (att.recentstatus=='REMOVED') {
                    return false;
                } else {
                    var optFilter = $("input[name=fltBUOWL]:checked").val();
                    if (optFilter=='ALL') {
                        return true;
                    } else {
                        return (att.hist_occup==optFilter);
                    }
                }
            }
            
            $("#txtFindBUOWL").on('keyup paste', function(){
                var val = $("#txtFindBUOWL").val();
                testLayerAttribute(arHabitatIDs, val, "Habitat ID", "#divFindBUOWL", "#divBUOWLError", "#btnFindBUOWL");
            });
            
            $("#btnFindBUOWL").click(function(){
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
                arHabitatIDs = [];
                lyrBUOWL.refresh();
            });
            
            // ************ Client Linears **********
            
            $("#btnLinearProjects").click(function(){
               $("#lgndLinearProjectsDetail").toggle(); 
            });
            
            function styleClientLinears(json) {
                var att = json.properties;
                switch (att.type) {
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
                lyr.bindTooltip("<h4>Linear Project: "+att.Project+"</h4>Type: "+att.type+"<br>ROW Width: "+att.row_width+"<br>Length: "+returnMultiLength(lyr.getLatLngs()).toFixed(0));
                arProjectIDs.push(att.Project.toString());
                var jsnBuffer = turf.buffer(json, att.row_width/1000, 'kilometers');
                var lyrBuffer = L.geoJSON(jsnBuffer, {style:{color:'gray', dashArray:'5,5'}});
                lyrClientLinesBuffer.addLayer(lyrBuffer);
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
                var lyr = returnLayerByAttribute(lyrClientLines,'Project',val);
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
            
            $("#btnProjectFilter").click(function(){
                arProjectIDs=[];
                lyrClientLines.refresh();
            });
            
            // *********  Eagle Functions *****************
            
            $("#btnEagle").click(function(){
               $("#lgndEagleDetail").toggle(); 
            });
            
            function returnEagleMarker(json, latlng){
                var att = json.properties;
                if (att.status=='ACTIVE NEST') {
                    var clrNest = 'deeppink';
                } else {
                    var clrNest = 'chartreuse';
                }
                arEagleIDs.push(att.nest_id.toString());
                return L.circle(latlng, {radius:804, color:clrNest,fillColor:'chartreuse', fillOpacity:0.5}).bindTooltip("<h4>Eagle Nest: "+att.nest_id+"</h4>Status: "+att.status);
            }
            
            function filterEagle(json) {
                var att=json.properties;
                var optFilter = $("input[name=fltEagle]:checked").val();
                if (optFilter=='ALL') {
                    return true;
                } else {
                    return (att.status==optFilter);
                }
            }
            
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
                arEagleIDs=[];
                lyrEagleNests.refresh();
            });
            
            //  *********** Raptor Functions
            
            $("#btnRaptor").click(function(){
               $("#lgndRaptorDetail").toggle(); 
            });
            
            function returnRaptorMarker(json, latlng){
                var att = json.properties;
                arRaptorIDs.push(att.Nest_ID.toString());
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
                return L.circle(latlng, optRaptor).bindPopup("<h4>Raptor Nest: "+att.Nest_ID+"</h4>Status: "+att.recentstatus+"<br>Species: "+att.recentspecies+"<br>Last Survey: "+att.lastsurvey);
            }
                
            function filterRaptor(json) {
                var att=json.properties;
                var optFilter = $("input[name=fltRaptor]:checked").val();
                if (optFilter=='ALL') {
                    return true;
                } else {
                    return (att.recentstatus==optFilter);
                }
            }
            
            $("#txtFindRaptor").on('keyup paste', function(){
                var val = $("#txtFindRaptor").val();
                testLayerAttribute(arRaptorIDs, val, "Raptor Nest ID", "#divFindRaptor", "#divRaptorError", "#btnFindRaptor");
            });
            
            $("#btnFindRaptor").click(function(){
                var val = $("#txtFindRaptor").val();
                var lyr = returnLayerByAttribute(lyrRaptorNests,'Nest_ID',val);
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
                arRaptorIDsIDs=[];
                lyrRaptorNests.refresh();
            });

            //  *********  jQuery Event Handlers  ************
            
            $("#btnGBH").click(function(){
               $("#lgndGBHDetail").toggle(); 
            });
            
            $("#btnLocate").click(function(){
                mymap.locate();
            });
            
            $("#btnShowLegend").click(function(){
                $("#legend").toggle();
            });
            
            //  ***********  General Functions *********
            
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
            
            function stripSpaces(str) {
                return str.replace(/\s+/g, '');
            }
            
         </script>
    </body>
</html>