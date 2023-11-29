<?php
if (isset($_SESSION['zero_exception'])) {
    $fileid = isset($_GET['id']) ? $_GET['id'] : $_SESSION['last_id'];
    $filetype = isset($_GET['file_type']) ? $_GET['file_type'] : 1;
    $redirecturl = SITE_URL . 'import.php';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo "<script>
            $(document).ready(function(){
            Swal.fire({
                icon: 'success',
                title: 'Congratulations!',
                text: 'All trades have been successfully resolved.',
                confirmButtonText:'OK'
            }).then((result)=>{
                if(result.isConfirmed){
                    window.location.href = '$redirecturl?tab=processed_files&id=$fileid&file_type=$filetype'
                }
            });
        });
        </script>";

    unset($_SESSION['zero_exception']);
}
?>

<div class="sectionwrapper" style="flex: 1; overflow: auto">
    <div class="container">
        <?php require_once(DIR_FS_INCLUDES . "alerts.php"); ?>

        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if (isset($_GET['exception_file_id']) && $_GET['exception_file_id'] != '') { ?>
                    <div class="alert alert-warning" role="alert" style="margin-top:20px">Some exceptions are generated. Please look at <a href="<?php echo CURRENT_PAGE . '?tab=review_files&id=' . $_GET['exception_file_id'] . '&file_type=' . $_GET['file_type'] ?>">View/Print</a> page.</div>
                <?php } ?>
                <div class="graphbox">

                    <div class="graphboxtitle">Import </div>
                    <div class="graphboxcontent">
                        <div class="tab-content col-md-12">


                            <!-- Import File Table -->
                            <div class="tab-pane active" id="tab_a"><?php if (isset($_GET['tab']) && $_GET['tab'] == "current_files" || !isset($_GET['tab'])) { ?>
                                    <ul class="nav nav-tabs ">
                                        <li class="<?php if (isset($_GET['tab']) && $_GET['tab'] == "current_files") {
                                                                            echo "active";
                                                                        } else if (!isset($_GET['tab'])) {
                                                                            echo "active";
                                                                        } else {
                                                                            echo '';
                                                                        } ?>"><a href="#current_files" id="current_files" onclick="tabfunction(this.id)" data-toggle="tab">Current Files</a></li>
                                        <li class="<?php if (isset($_GET['tab']) && $_GET['tab'] == "archived_files") {
                                                                            echo "active";
                                                                        } ?>"><a href="#archived_files" id="archived_files" onclick="tabfunction(this.id)" data-toggle="tab">Archived Files</a></li>
                                    </ul> <?php } ?> <br />
                                <!-- Tab 1 is started -->
                                <div class="tab-content">
                                    <div class="tab-pane <?php if (isset($_GET['tab']) && $_GET['tab'] == "current_files") {
                                                                echo "active";
                                                            } else if (!isset($_GET['tab'])) {
                                                                echo "active";
                                                            } else {
                                                                echo '';
                                                            } ?>" id="current_files">

                                        <div class="panel-overlay-wrap">
                                            <div class="panel-body" style="border: 1px solid #DFDFDF; margin-top: 17px;">
                                                <div class="row">
                                                    <!--<div class="row">
                                        <div class="col-md-5"></div>
                                            <a class="btn btn-sm btn-warning col-md-1" href="<?php echo CURRENT_PAGE; ?>?action=open_ftp"> Fetch</a>
                                            <!--<a href="<?php echo CURRENT_PAGE; ?>?action=open_ftp"><!--<button type="button"  name="fetch" value="fetch" style="display: inline;"> Fetch</button></a>-->
                                                    <!--<button type="submit" class="btn btn-sm btn-default col-md-2"  name="progress_all" value="progress_all" style="display: inline;"> Process All</button>
                                        </div>
                                        <br />-->
                                                    <div class="table-responsive" style="margin: 0px 5px 0px 5px;">
                                                        <table id="data-table" class="table table-bordered table-stripped table-hover">
                                                            <thead>
                                                                <th>Action</th>
                                                                <th>Source</th>
                                                                <th>File Name</th>
                                                                <th>File Type</th>
                                                                <!--<th>Imported</th>-->
                                                                <th>Last Processed</th>
                                                                <th>Sponsor</th>
                                                                <th>Batch#</th>
                                                                <th>Total/ <br> Exceptions</th>
                                                                <th>Results</th>
                                                                <th>Notes</th>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $count = 0;
                                                                if (isset($return) && $return != array()) {        
                                                                    $return = $instance->select_current_files(1);

                                                                    foreach ($return as $key => $val) {
                                                                        $return_file_data_array = $instance->get_file_array($val['id']);
                                                                        $isImportCompleted = $val['processed'] == 1 && $val['process_completed'] == 1;
                                                                        $isImportArchived = $val['is_archived'] == 1;
                                                                        $isImportNotStart = $val['processed'] == 0;
                                                                        $isImportStarted = $val['processed'] == 1 && $val['process_completed'] != 1;

                                                                        $system_id = isset($return_file_data_array[0]['dst_system_id']) ? $return_file_data_array[0]['dst_system_id'] : '';
                                                                        $management_code = isset($return_file_data_array[0]['dst_management_code']) ? $return_file_data_array[0]['dst_management_code'] : '';
                                                                        $sponsor_detail = $instance_sponsor->edit_sponsor($val['sponsor_id']);
                                                                        $sponsor = isset($sponsor_detail['name']) ? $sponsor_detail['name'] : '';

                                                                        $file_batch_id = $instance->get_file_batch($val['id']);
                                                                        if (!isset($val['file_type'])) {
                                                                            $file_type_id = 1;
                                                                        } else if ($val['file_type'] == 'DST Commission' or $val['file_type_code'] == 2) {
                                                                            $file_type_id = 2;
                                                                        } else if (stripos($val['file_type'], 'securit') !== false or $val['file_type_code'] == 3) {
                                                                            $file_type_id = 3;
                                                                        } else if (stripos($val['file_type'], 'generic commission') !== false or $val['file_type_code'] == 9) {
                                                                            $file_type_id = 9;
                                                                        } else if (stripos($val['file_type'], 'Orion') !== false or $val['file_type_code'] == 11) {
                                                                            $file_type_id = 11;
                                                                        } else {
                                                                            $file_type_id = 1;
                                                                        }

                                                                        $fid_to_send = $val['id'];
                                                                        if ($file_type_id == 2 || $file_type_id == 9 ||  $file_type_id == 11) {

                                                                            $total_unprocessed_commission_for_import = $instance->select_total_commission("`at`.`is_delete`=0 AND `at`.`file_id`=$fid_to_send AND `at`.`file_type`=$file_type_id AND `at`.`solved`=0");

                                                                            $total_processed_commission_for_import = $instance->select_total_commission("`at`.`is_delete`=0 AND `at`.`file_id`=$fid_to_send AND `at`.`file_type`=$file_type_id AND `at`.`solved`=1");

                                                                            $total_Check_Amount = $total_unprocessed_commission_for_import + $total_processed_commission_for_import;
                                                                        } else {
                                                                            $total_unprocessed_import = $instance->select_total_records("`is_delete`=0 AND `file_id`=$fid_to_send AND `file_type`=$file_type_id AND `solved`=0");
                                                                            $total_processed_import = $instance->select_total_records("`is_delete`=0 AND `file_id`=$fid_to_send AND `file_type`=$file_type_id AND `solved`=1");
                                                                            // below line comment on 23-05-2023
                                                                            // $total_import = $total_unprocessed_import + $total_processed_import;
                                                                            $total_import = $instance->total_detail_record("`is_delete`=0 AND `file_id`=$fid_to_send");
                                                                            $total_import_unique = $instance->unique_trades_count("`at`.`is_delete`=0 AND `at`.`file_id`=$fid_to_send AND `at`.`file_type`=$file_type_id AND `at`.`solved`=0");
                                                                        }


                                                                        if (isset($val['imported_date']) && $val['imported_date'] != '') {
                                                                            $check_file_exception_process = $instance->check_file_exception_process($val['id']);
                                                                            if ($check_file_exception_process == 1) {
                                                                                $page_go_to = "review_files";
                                                                            } else {
                                                                                $page_go_to = "processed_files";
                                                                            }
                                                                            $sponsorLink = CURRENT_PAGE . "?tab=$page_go_to&id={$val['id']}&file_type=$file_type_id";
                                                                ?>
                                                                            <tr id="<?php echo '$key' . $key ?>">

                                                                                <td class="options">
                                                                                    <form method="post">
                                                                                        <select name="process_file_<?php echo $val['id']; ?>" id="process_file_<?php echo $val['id']; ?>" class="form-control form-go-action" style=" width: 100% !important;display: inline;">
                                                                                            <option value="0">Select Option</option>
                                                                                            <option value="2" <?php echo $isImportNotStart ? "selected='selected'" : "disabled='disabled'"; ?>>Process</option>
                                                                                            <option value="5" <?php echo !$isImportNotStart && !$isImportCompleted ? "" : "disabled='disabled'";  ?>>Reprocess</option>
                                                                                            <option value="3" <?php if ($val['processed'] == 0) {
                                                                                                                    echo 'disabled="true"';
                                                                                                                } ?>>View/Print</option>
                                                                                            <option value="4" <?php if ($val['processed'] == 0) {
                                                                                                                    echo 'disabled="true"';
                                                                                                                } ?>>Resolve Exceptions</option>
                                                                                            <option value="6" <?php echo ($isImportCompleted && !$isImportArchived) ? "" : "disabled='disabled'" ?>>Move To Archived</option>
                                                                                            <option value="1">Delete File</option>
                                                                                            <!-- <option value="7" >Preview</option> -->
                                                                                        </select>
                                                                                        <input type="hidden" name="id" id="id" value="<?php echo $val['id']; ?>" />
                                                                                        <input type="hidden" name="process_file_type" id="process_file_type" value="<?php echo $val['file_type']; ?>" />
                                                                                        <input type="hidden" name="process_file_type_code" id="process_file_type_code" value="<?php echo $val['file_type_code']; ?>" />
                                                                                        <input type="hidden" name="go" value="go" />
                                                                                    </form>
                                                                                </td>

                                                                                <td class="source" onclick="waitingDialog.show('a'),window.location.href='<?php echo $sponsorLink ?>'" style="cursor:pointer"><?php echo $val['source'] ?></td>
                                                                                <td class="filenm" onclick="waitingDialog.show('a'),window.location.href='<?php echo $sponsorLink ?>'" style="cursor:pointer" title="<?= $val['file_name'] ?>">
                                                                                    <?php
                                                                                    $filename = $val['file_name'];
                                                                                    echo $str = (strlen($filename) > 22) ? substr($filename, 0, 20) . '...' : $filename;
                                                                                    ?></td>
                                                                                <td onclick="waitingDialog.show('a'),window.location.href='<?php echo $sponsorLink ?>'" style="cursor:pointer"><?php if ($val['source'] == 'DAZL Daily') {
                                                                                                                                                                                                    echo 'DAZL ';
                                                                                                                                                                                                }
                                                                                                                                                                                                echo $val['file_type']; ?></td>
                                                                                <td onclick="waitingDialog.show('a'),window.location.href='<?php echo $sponsorLink ?>'" style="cursor:pointer"><?php if (isset($val['last_processed_date']) && $val['last_processed_date'] != '0000-00-00 00:00:00') {
                                                                                                                                                                                                    echo date('m/d/Y', strtotime($val['last_processed_date']));
                                                                                                                                                                                                } ?></td>
                                                                                <td onclick="waitingDialog.show('a'),window.location.href='<?php echo $sponsorLink ?>'" style="cursor:pointer"><?php echo $sponsor; ?></td>
                                                                                <td onclick="waitingDialog.show('a'),window.location.href='<?php echo $sponsorLink ?>'" style="cursor:pointer"><?php echo in_array($file_type_id, [2, 9, 11]) ? $file_batch_id : 'N/A'; ?></td>
                                                                                <!--<td style="width: 15%;"><?php echo date('m/d/Y', strtotime($val['imported_date'])); ?></td>-->

                                                                                <?php
                                                                                // Client & Security data are in the same file, but in different Detail tables so separate the processed/exceptions counts(i.e. different tables)
                                                                                $detailTable = $instance->import_table_select($val['id'], $file_type_id);
                                                                                $detailTable = $detailTable['table'];


                                                                                $total_processed_data = $instance->check_file_exception_process($val['id'], 1, $detailTable);
                                                                                $process_status = $instance->check_file_process_status($val['id']);

                                                                                $count_processed_data = $total_processed_data['processed'];
                                                                                $count_exception_data = $total_processed_data['exceptions'];

                                                                                if ($count_processed_data + $count_exception_data > 0) {
                                                                                    $total_process = $count_processed_data + $count_exception_data;
                                                                                    $total_processed_per = ($count_processed_data * 100) / $total_process;
                                                                                    $total_complete_process = round($total_processed_per);
                                                                                } else {
                                                                                    if ($count_processed_data == 0 && $count_exception_data == 0 && $process_status == 1) {
                                                                                        $total_complete_process = 100;
                                                                                    } else {
                                                                                        $total_complete_process = 0;
                                                                                    }
                                                                                }
                                                                                ?>

                                                                                <td style="text-align:right ;" class="chkamt">
                                                                                    <?php

                                                                                    if ($file_type_id == 2 || $file_type_id == 9 || $file_type_id == 11) {

                                                                                        if ($total_processed_commission_for_import == $total_Check_Amount && $process_status == 1) {
                                                                                            echo '<i class="fa fa-check text-success"></i>';
                                                                                        }

                                                                                        echo ' $' . number_format($total_processed_commission_for_import, 2);
                                                                                        echo "<br>";
                                                                                        echo ' $' . number_format($total_Check_Amount, 2);
                                                                                    } else {

                                                                                // below comment on 23-06-2023 as per client comment
                                                                                        // if ($total_processed_import == $total_import_unique && $process_status == 1) {
                                                                                        //     echo '<i class="fa fa-check text-success"></i>';
                                                                                        // }
                                                                                        
                                                                                        // echo $total_processed_import;
                                                                                        // echo "<br>";
                                                                                        // echo $total_import_unique;
                                                                                        
                                                                                        if ($total_unprocessed_import ==0  && $process_status == 1) {
                                                                                            echo '<i class="fa fa-check text-success"></i>';
                                                                                        }
                                                                                        echo $total_import;
                                                                                        echo "<br>";
                                                                                        echo $total_unprocessed_import;
                                                                                    }

                                                                                    ?>

                                                                                </td>
                                                                                <td style="cursor:pointer">

                                                                                    <div class="progress">

                                                                                        <?php if (isset($total_complete_process) && $total_complete_process < 100) { ?>
                                                                                            <?php $progress_bar_style = ($count_exception_data > 0) ? 'danger' : 'warning'; ?>
                                                                                            <div class="progress-bar progress-bar-<?php echo $progress_bar_style; ?> progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $total_complete_process; ?>%">
                                                                                                <?php echo $total_complete_process . '%'; ?> Complete
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $total_complete_process; ?>%">
                                                                                                <?php echo $total_complete_process . '%'; ?> Complete
                                                                                            </div>

                                                                                        <?php } ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="addnote"> <?php
                                                                                                        $check_exception_data = $instance->check_exception_data($val['id']);
                                                                                                        $check_processed_data = $instance->check_processed_data($val['id']);
                                                                                                        $check_file_exception_process = $instance->check_file_exception_process($val['id']);
                                                                                                        ?>

                                                                                    <form method="post" id="noteform_<?php echo $val['id']; ?>">
                                                                                        <input type="hidden" name="id" id="id" value="<?php echo $val['id']; ?>" />
                                                                                        <input type="hidden" name="note" value="save_note" />
                                                                                        <input type="text" maxlength="100" class="notes" value="<?php echo isset($val['note']) ? $val['note'] : ''; ?>" name="note_<?php echo $val['id']; ?>" onchange="save_note_change(<?php echo $val['id']; ?>)">
                                                                                        <input type="hidden" name="process_file_type" id="process_file_type" value="<?php echo $val['file_type']; ?>" />
                                                                                    </form>
                                                                                </td>

                                                                            </tr>
                                                                <?php }
                                                                    }
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span>
                                                    <h4 class="panel-overlay-title"></h4>
                                                    <p></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function tabfunction(idget){
        window.location.href="<?php echo CURRENT_PAGE; ?>?tab="+idget;
    }
</script>
<style>
    #table-scroll {
        height: 500px;
        overflow: auto;
        margin-top: 20px;
    }

    .btn-primary {
        color: #fff;
        background-color: #337ab7 !important;
        border-color: #2e6da4 !important;
    }

    #data-table .progress,
    #data-table .notes {
        width: 100%;
    }

    #data-table .filenm {
        width: 5%;
    }

    #data-table .options {
        width: 10%;
    }

    #data-table .addnote {
        width: 13%;
    }

    #data-table .chkamt {
        width: 12%;
    }

    #data-table .source {
        width: 8%;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('.form-go-action').on('onselect change', function() {
            var form = this.closest('form');
            form.submit();
        })

        $('#data-table').DataTable({
            "pageLength": 25,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "dom": '<"toolbar">frtip',
            "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [0, 9]
                },
                {
                    "bSearchable": false,
                    "aTargets": [0, 9]
                },
                {
                    "type": 'date',
                    "targets": [4]
                },
            ],
            // "columnDefs": [{
            //        "width": "40%",
            //        "targets": 0,
            //    },],
            "order": [<?php echo !empty($dataTableOrder) ? $dataTableOrder : '[4, "desc"]'; ?>],

        });
        $("div.toolbar").html('<a class="btn btn-sm btn-warning" href="<?php echo CURRENT_PAGE; ?>?action=open_ftp"> Fetch</a>' +
            '<a class="btn btn-sm btn-default" href="<?php echo CURRENT_PAGE; ?>?action=process_all" style="display:inline;">Reprocess All</a>');

        $('#data-table1').DataTable({
            "pageLength": 25,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "dom": '<"toolbar1">frtip',
            "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [6, 7]
                },
                {
                    "bSearchable": false,
                    "aTargets": [6, 7]
                }
            ]
        });

    });

</script>
<style type="text/css">
    .toolbar {
        float: left;
    }

    .toolbar2 {
        float: right;
        padding-left: 5px;
    }

    .toolbar3 {
        float: right;
        padding-left: 5px;
    }

    .toolbar4 {
        float: right;
        padding-left: 5px;
    }

    .toolbar5 {
        float: right;
        padding-left: 5px;
    }

    .toolbar6 {
        float: right;
        padding-left: 5px;
    }
</style>
<script type="text/javascript">
    function save_note_change(id) {
        $("#noteform_" + id).submit();
    }

</script>

<script type="text/javascript">
    var waitingDialog = waitingDialog || (function($) {
        'use strict';

        // Creating modal dialog's DOM
        var $dialog = $(
            '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
            '<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
            '<div class="modal-body">' +
            '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
            '</div>' +
            '</div></div></div>');

        return {
            /**
             * Opens our dialog
             * @param message Custom message
             * @param options Custom options:
             * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
             * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
             */
            show: function(message, options) {
                // Assigning defaults
                if (typeof options === 'undefined') {
                    options = {};
                }
                if (typeof message === 'undefined') {
                    message = 'Saving...';
                }
                if (message === 'a') {
                    message = 'Working on it...';
                }
                var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);

                // Configuring dialog
                $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
                $dialog.find('.progress-bar').attr('class', 'progress-bar');
                if (settings.progressType) {
                    $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
                }
                $dialog.find('h3').text(message);
                // Adding callbacks
                if (typeof settings.onHide === 'function') {
                    $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function(e) {
                        settings.onHide.call($dialog);
                    });
                }
                // Opening dialog
                $dialog.modal();
            },
            /**
             * Closes dialog
             */

        };

    })(jQuery);
</script>
<script>
    //submit add notes form data
    function formsubmitfiles() {
        $('#msg_files').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

        var myForm = document.getElementById('form_import_files');
        form_data = new FormData(myForm);
        $.ajax({
            url: 'import.php', // point to server-side PHP script

            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data) {

                if (data == '1') {
                    window.location.href = "import.php";

                    /*$('#msgnotes').html('<div class="alert alert-success">Thank you.</div>');
                $('#add_notes')[0].reset();
                setTimeout(function(){
    				$('#myModalShare').modal('hide');
    			}, 2000);*/

                } else {
                    $('#msg_files').html('<div class="alert alert-danger">' + data + '</div>');
                }

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#msg_files').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
            }

        });

        //e.preventDefault(); // avoid to execute the actual submit of the form.
        return false;

    }
</script>