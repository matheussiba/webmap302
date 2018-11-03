            function returnRaptorMarker(json, latlng){
                var att = json.properties;
                arRaptorIDs.push(att.nest_id.toString());
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
                return L.circle(latlng, optRaptor).bindPopup("<h4>Raptor Nest: "+att.nest_id+"</h4>Status: "+att.recentstatus+"<br>Species: "+att.recentspecies+"<br>Last Survey: "+att.lastsurvey);
            }
                
            function findRaptor(val) {
                returnLayerByAttribute("dj_raptor",'nest_id',val, function(lyr){
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
                        $("#raptor_recentspecies").val(att.recentspecies);
                        $("#raptor_recentstatus").val(att.recentstatus);
                        $("#raptor_lastsurvey").val(att.lastsurvey);
                        $("#raptorMetadata").html("Created "+att.created+" by "+att.createdby+"<br>Modified "+att.modified+" by "+att.modifiedby);
                        $("#formRaptor").show();
                        $("#divRaptorError").html("");

                        $.ajax({
                            url:'djbasin_resources/php_affected_projects.php',
                            data:{tbl:'dj_raptor', distance:radRaptor, fld:'nest_id', id:val},
                            type:'POST',
                            success:function(response){
                                $("#divRaptorAffected").html(response);
                            },
                            error:function(xhr, status, error){
                                $("#divRaptorAffected").html("ERROR: "+error);
                            }
                        });
                        
                        $("#btnRaptorSurveys").show();

                     } else {
                        $("#divRaptorError").html("**** Raptor Nest ID not found ****");
                    }
                });
                
            }
            
            function refreshRaptors(whr) {
                if (whr) {
                    var objData = {tbl:'dj_raptor', flds:"id, nest_id, recentstatus, recentspecies, lastsurvey", where:whr}
                } else {
                   var objData = {tbl:'dj_raptor', flds:"id, nest_id, recentstatus, recentspecies, lastsurvey"}
                }
                $.ajax({url:'php/load_data.php', 
                    data: objData,
                    type: 'POST',
                    success: function(response){
                        if (response.substring(0,5)=="ERROR"){
                            alert(response);
                        } else {
                            arRaptorIDs=[];
                            jsnRaptors = JSON.parse(response);
                            if (lyrMarkerCluster) {
                                ctlLayers.removeLayer(lyrMarkerCluster);
                                lyrMarkerCluster.remove();
                                lyrRaptorNests.remove();
                            }
                            lyrRaptorNests = L.geoJSON(jsnRaptors, {pointToLayer:returnRaptorMarker});
                            arRaptorIDs.sort(function(a,b){return a-b});
                            $("#txtFindRaptor").autocomplete({
                                source:arRaptorIDs
                            });
                            lyrMarkerCluster = L.markerClusterGroup();
                            lyrMarkerCluster.clearLayers();
                            lyrMarkerCluster.addLayer(lyrRaptorNests);
                            lyrMarkerCluster.addTo(mymap);
                            ctlLayers.addOverlay(lyrMarkerCluster, "Raptor Nests");
                        }
                    }, 
                    error: function(xhr, status, error){
	                   alert("ERROR: "+error);
                    } 
                });
            }
                
            function refreshLinears(whr) {
                if (whr) {
                    var objData={tbl:'dj_linear', flds:"id, type, row_width, project", where:whr}
                } else {
                    var objData={tbl:'dj_linear', flds:"id, type, row_width, project"}
                }
                $.ajax({url:'php/load_data.php', 
                    data: objData,
                    type: 'POST',
                    success: function(response){
                        if (response.substring(0,5)=="ERROR"){
                            alert(response);
                        } else {
                            arProjectIDs=[];
                            jsnLinears = JSON.parse(response);
                            if (lyrClientLines) {
                                ctlLayers.removeLayer(lyrClientLines);
                                lyrClientLines.remove();
                                lyrClientLinesBuffer.remove();
                            }
                            lyrClientLinesBuffer = L.featureGroup();
                            lyrClientLines = L.geoJSON(jsnLinears, {style:styleClientLinears, onEachFeature:processClientLinears}).addTo(mymap);
                            ctlLayers.addOverlay(lyrClientLines, "Linear Projects");
                            arProjectIDs.sort(function(a,b){return a-b});
                            $("#txtFindProject").autocomplete({
                                source:arProjectIDs
                            });
                            refreshLinearBuffers(whr);
                        }
                    }, 
                    error: function(xhr, status, error){
	                   alert("ERROR: "+error);
                    } 
                });
            }
                
            function refreshLinearBuffers(whr) {
                if (whr) {
                    var objData={tbl:'dj_linear', flds:"id, type, row_width, project", where:whr, distance:"row_width"}
                } else {
                    var objData={tbl:'dj_linear', flds:"id, type, row_width, project", distance:"row_width"}
                }
                $.ajax({url:'php/load_data.php', 
                    data: objData,
                    type: 'POST',
                    success: function(response){
                        if (response.substring(0,5)=="ERROR"){
                            alert(response);
                        } else {
                            jsnLinearBuffers = JSON.parse(response);
                            if (lyrClientLinesBuffer) {
                                lyrClientLinesBuffer.remove();
                            }
                            lyrClientLinesBuffer = L.geoJSON(jsnLinearBuffers, {style:{color:'grey', dashArray:'5,5', fillOpacity:0}}).addTo(mymap);
                            lyrClientLines.bringToFront();
                        }
                    }, 
                    error: function(xhr, status, error){
	                   alert("ERROR: "+error);
                    } 
                });
            }
                
