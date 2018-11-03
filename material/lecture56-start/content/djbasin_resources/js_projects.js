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
                lyr.bindTooltip("<h4>Linear Project: "+att.project+"</h4>Type: "+att.type+"<br>ROW Width: "+att.row_width+"<br>Length: "+returnMultiLength(lyr.getLatLngs()).toFixed(0));
                arProjectIDs.push(att.project.toString());
            }
            
            function findProject(val){
                returnLayerByAttribute("dj_linear",'project',val, function(lyr){
                    if (lyr) {
                        if (lyrSearch) {
                            lyrSearch.remove();
                        }
                        lyrSearch = L.geoJSON(lyr.toGeoJSON(), {style:{color:'red', weight:10, opacity:0.5}}).addTo(mymap);
                        mymap.fitBounds(lyr.getBounds().pad(1));
                        var att = lyr.feature.properties;
                        $("#linear_type").val(att.type);
                        $("#linear_row_width").val(att.row_width);
                        $("#formProject").show();
                        $("#projectMetadata").html("Created "+att.created+" by "+att.createdby+"<br>Modified "+att.modified+" by "+att.modifiedby);
                        
                        $.ajax({
                            url:'djbasin_resources/php_affected_constraints.php',
                            data:{id:val},
                            type:'POST',
                            success:function(response){
                                $("#divProjectAffected").html(response);
                            },
                            error:function(xhr, status, error){
                                $("#divProjectAffected").html("ERROR: "+error);
                            }
                        });
                    } else {
                        $("#divProjectError").html("**** Project ID not found ****");
                    }
                });
                
            }
            
