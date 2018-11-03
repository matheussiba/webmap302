//      ************* GBH Functions
            
            function refreshGBH() {
                $.ajax({url:'php/load_data.php', 
                    data: {tbl:'dj_gbh', flds:"id, activity"},
                    type: 'POST',
                    success: function(response){
                        if (response.substring(0,5)=="ERROR"){
                            alert(response);
                        } else {
                            jsnGBH = JSON.parse(response);
                            if (lyrGBH) {
                                ctlLayers.removeLayer(lyrGBH);
                                lyrGBH.remove();
                            }
                            lyrGBH = L.geoJSON(jsnGBH, {style:{color:'fuchsia'}}).bindTooltip("GBH Nesting Area").addTo(mymap);
                            ctlLayers.addOverlay(lyrGBH, "Heron Rookeries");
                        }
                    }, 
                    error: function(xhr, status, error){
	                   alert("ERROR: "+error);
                    } 
                });
            }

