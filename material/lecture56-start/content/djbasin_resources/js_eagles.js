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
            
            function findEagle(val){
                returnLayerByAttribute("dj_eagle",'nest_id',val, function(lyr){
                    if (lyr) {
                        if (lyrSearch) {
                            lyrSearch.remove();
                        }
                        lyrSearch = L.circle(lyr.getLatLng(), {radius:800, color:'red', weight:10, opacity:0.5, fillOpacity:0}).addTo(mymap);
                        mymap.setView(lyr.getLatLng(), 14);
                        var att = lyr.feature.properties;
                        $("#eagle_status").val(att.status);
                        $("#eagle_lastsurvey").val(att.lastsurvey);
                        $("#eagleMetadata").html("Created "+att.created+" by "+att.createdby+"<br>Modified "+att.modified+" by "+att.modifiedby);
                        $("#formEagle").show();
                        
                        $.ajax({
                            url:'djbasin_resources/php_affected_projects.php',
                            data:{tbl:'dj_eagle', distance:804, fld:'nest_id', id:val},
                            type:'POST',
                            success:function(response){
                                $("#divEagleAffected").html(response);
                            },
                            error:function(xhr, status, error){
                                $("#divAffectedAffected").html("ERROR: "+error);
                            }
                        });
                        
                        $("#divEagleError").html("");

                        $("#btnEagleSurveys").show();


                     } else {
                        $("#divEagleError").html("**** Eagle Nest ID not found ****");
                    }
                });
                
            }
            
            function refreshEagles(whr) {
                if (whr) {
                    var objData = {tbl:'dj_eagle', flds:"id, status, nest_id", where:whr};
                } else {
                    var objData = {tbl:'dj_eagle', flds:"id, status, nest_id"};
                }
                $.ajax({url:'php/load_data.php', 
                    data: objData,
                    type: 'POST',
                    success: function(response){
                        if (response.substring(0,5)=="ERROR"){
                            alert(response);
                        } else {
                            arEagleIDs=[];
                            jsnEagles = JSON.parse(response);
                            if (lyrEagleNests) {
                                ctlLayers.removeLayer(lyrEagleNests);
                                lyrEagleNests.remove();
                            }
                            lyrEagleNests = L.geoJSON(jsnEagles, {pointToLayer:returnEagleMarker}).addTo(mymap);
                            ctlLayers.addOverlay(lyrEagleNests, "Eagle Nests");
                            arEagleIDs.sort(function(a,b){return a-b});
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
            
