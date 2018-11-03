//     *********** Survey functions
            
            function displaySurveys(tbl, id) {
                $.ajax({
                    url:'djbasin_resources/php_load_surveys.php',
                    data:{tbl:tbl, id:id},
                    type:'POST',
                    success:function(response){
                        $("#formSurvey").hide();
                        $("#tableData").html(response);
                        $("#tableData").append("<button id='btnInsertSurvey' class='btn btn-success'>Add New Survey</button>");
                        $("#btnInsertSurvey").click(function(){
                            insertSurvey(tbl, id);
                        });
                        $(".btnEditSurvey").click(function(){
                            editSurvey(tbl, id, $(this).attr('data-id'));
                        });
                        $(".btnDeleteSurvey").click(function(){
                            deleteSurvey(tbl, id, $(this).attr('data-id'));
                        });
                        $("#dlgModal").show();
                    },
                    error:function(xhr, status, error){
                        $("#tableData").html("ERROR: "+error);
                        $("#dlgModal").show();
                    }
                });
                
            }
            
            function insertSurvey(tbl, id) {
                alert("Insert new record for habitat "+id+"in table "+tbl);
                $("#survey_id").val("New");
                $("#survey_habitat").val(id);
                $("#survey_surveyor").val(user.firstname+" "+user.lastname);
                $("#survey_surveydate").val(returnCurrentDate());
                $("#survey_result").val("ACTIVE NEST");
                $("#tableData").html("");
                $("#formSurveyButtons").html("<button id='btnSubmitSurvey' class='btn btn-success col-sm-offset-4'>Submit Survey</button><button id='btnCancelSurvey' class='btn btn-danger col-sm-offset-1'>Cancel</button>");
                $("#btnSubmitSurvey").click(function(e){
                    e.preventDefault();
                    submitSurvey(tbl, id);
                })
                 $("#btnCancelSurvey").click(function(e){
                    e.preventDefault();
                    displaySurveys(tbl, id);
                })
                $("#formSurvey").show();
            }
            
            function submitSurvey(tbl, id){
                var jsnFormData=returnFormData('inpSurvey');
                jsnFormData.tbl=tbl;
                delete jsnFormData.id;
                $.ajax({
                    url:'php/insert_record.php',
                    data:jsnFormData,
                    type:'POST',
                    success:function(response){
                        if (response.substring(0,5)=="ERROR") {
                            alert(response);
                        } else {
                            displaySurveys(tbl, id);
                        }
                    },
                    error:function(xhr, status, error){
                        $("#tableData").html("ERROR: "+error);
                        $("#dlgModal").show();
                    }
                })
            }
            
            function editSurvey(tbl, id, survey_id) {
                returnRecordByID(tbl, survey_id, function(jsn){
                    if (jsn) {
                        $("#survey_id").val(jsn.id);
                        $("#survey_habitat").val(jsn.habitat);
                        $("#survey_surveyor").val(jsn.surveyor);
                        $("#survey_surveydate").val(jsn.surveydate);
                        $("#survey_result").val(jsn.result);
                        $("#tableData").html("");
                        $("#formSurveyButtons").html("<button id='btnUpdateSurvey' class='btn btn-warning col-sm-offset-4'>Update Survey</button><button id='btnCancelSurvey' class='btn btn-danger col-sm-offset-1'>Cancel</button>");
                        $("#btnUpdateSurvey").click(function(e){
                            e.preventDefault();
                            updateSurvey(tbl, id, survey_id);
                        })
                         $("#btnCancelSurvey").click(function(e){
                            e.preventDefault();
                            displaySurveys(tbl, id);
                        })
                        $("#formSurvey").show();
                    } else {
                        alert("Could not find record "+survey_id+" in table "+tbl);
                    }
                });
            }
            
            function updateSurvey(tbl, id, survey_id) {
                var jsnFormData=returnFormData('inpSurvey');
                jsnFormData.tbl=tbl;
                $.ajax({
                    url:'php/update_record.php',
                    data:jsnFormData,
                    type:'POST',
                    success:function(response){
                        if (response.substring(0,5)=="ERROR") {
                            alert(response);
                        } else {
                            displaySurveys(tbl, id);
                        }
                    },
                    error:function(xhr, status, error){
                        $("#tableData").html("ERROR: "+error);
                        $("#dlgModal").show();
                    }

                })
            }
            
            function deleteSurvey(tbl, id, survey_id) {
                 if (confirm("Are you sure you want to delete survey "+survey_id+" from "+tbl+"?")) {
                     $.ajax({
                        url:'php/delete_record.php',
                        data:{tbl:tbl, id:survey_id},
                        type: 'POST',
                        success:function(response){
                            displaySurveys(tbl, id);
                        },
                        error:function(xhr, status, error){
                            $("#tableData").html("ERROR: "+error);
                            $("#dlgModal").show();
                        }
                    })
                 }
            }
            
