<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Airtel Calling API</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Airtel Calling API</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Airtel Calling API</h4>

                        </div>
                        <div class="box-body">
                            <div class="">
                                <form id="apiForm">
                                    <div class="form-group my-3">
                                        <label for="callerId">Caller ID:</label>
                                        <input class="form-control" type="text" id="callerId" value="8048248828" required>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-md-6">
                                            <label for="participantAddress1">Participant Address 1:</label>
                                            <input type="text" id="participantAddress1" value="8224006280" required class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="participantAddress2">Participant Address 2:</label>
                                            <input type="text" id="participantAddress2" value="7506729067" required class="form-control">

                                        </div>
                                    </div>





                                    <button type="submit" class="btn btn-warning">Initiate Call</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    // validation rules for comments
    toastr.options = {
        'closeButton': true,
        'debug': false,
        'newestOnTop': false,
        'progressBar': false,
        'positionClass': 'toast-top-right',
        'preventDuplicates': false,
        'showDuration': '1000',
        'hideDuration': '1000',
        'timeOut': '5000',
        'extendedTimeOut': '1000',
        'showEasing': 'swing',
        'hideEasing': 'linear',
        'showMethod': 'fadeIn',
        'hideMethod': 'fadeOut',
    }
</script>
<script>
    $(document).ready(function() {
        $("#apiForm").submit(function(event) {
            event.preventDefault();

            let data = {
                "callFlowId": "TUMspyjWoYb+Ul8vp2khpgWZix3lECvaXcJtTQ78KKLfDiJjaazlkJrCd2pEA3DmInBzC9KmR061nKW85NR3l4FS31CoVZBm8cPiJ7trrSE=",
                "customerId": "SAYAJI_HOT_Y6fD4Rfsi6zgGcAP6Gdg",
                "callType": "OUTBOUND",
                "callFlowConfiguration": {
                    "initiateCall_1": {
                        "callerId": $("#callerId").val(),
                        "mergingStrategy": "SEQUENTIAL",
                        "callBackURLs": [{
                                "eventType": "ALL",
                                "notifyURL": "...",
                                "method": "POST"
                            },
                            {
                                "eventType": "CDR",
                                "notifyURL": "https://shplpune.com/crm/superAdmin/CallApiController/capture_call_data",
                                "method": "POST"
                            }
                        ],
                        "participants": [{
                            "participantAddress": $("#participantAddress1").val(),
                            "callerId": $("#callerId").val(),
                            "participantName": "A",
                            "maxRetries": 1,
                            "maxTime": 0
                        }],
                        "maxTime": 0
                    },
                    "addParticipant_1": {
                        "mergingStrategy": "SEQUENTIAL",
                        "maxTime": 0,
                        "participants": [{
                            "participantAddress": $("#participantAddress2").val(),
                            "participantName": "B",
                            "maxRetries": 1,
                            "maxTime": 0
                        }]
                    }
                }
            };

            $.ajax({
                url: "https://iqtelephony.airtel.in/gateway/airtel-xchange/v2/execute/workflow",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                headers: {
                    "Cache-Control": "no-cache",
                    "Authorization": "Basic " + btoa("SAYAJI_HOT_Y6fD4Rfsi6zgGcAP6Gdg:Y6fD4Rfsi6zgGcAP6Gdg")
                },
                success: function(response) {

                    toastr.success(JSON.stringify(response))

                },
                error: function(xhr, status, error) {
                    $("#response").html("<b>Error:</b> " + xhr.responseText);
                }
            });
        });
    });
</script>