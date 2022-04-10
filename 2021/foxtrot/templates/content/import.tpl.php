<script language="javascript">
function GetFileList()
{
    document.getElementsByName("HTTPDL_result").value="";
    console.log(HTTPDL,"HTTPDL")
    //HTTPDL_result.value = "";
    HTTPDL.Host = "filetransfer.financialtrans.com";
    HTTPDL.UseProxy = false;
    HTTPDL.LocalDirectory = "E:\foxtrot_idc_file";
    HTTPDL.UseHttps = true;
    // Note: Test System Information
    HTTPDL.Target = "/tf/FANMail";
    //HTTPDL.Client = "419041819";
	HTTPDL.Client = "415171403";
	//415171403
    // Note: For testing UserID and Password will be supplied by DST
    HTTPDL.UserID = UserID.value;
    HTTPDL.ftpType = ftpType.value;
    HTTPDL.Password = Password.value;
    var list = HTTPDL.GetFileListAsXML();
    //alert(list);
    var dlist = "No file list returned";
    //HTTPs Download Guide Product Guide
    var xmldoc = MSXML3;
    xmldoc.async = false;
    xmldoc.preserveWhiteSpace = true;
    xmldoc.loadXML(list);

    var docelement = xmldoc.documentElement;

    if(docelement.hasChildNodes())
    {
            dlist = "<form name=\"Selection\">";
            var nodeList = docelement.childNodes;
            var node = nodeList.nextNode();
            while ( node != null )
            {
                var file = node.getAttribute("name");
                var display = node.getAttribute("short-name");
                if(HTTPDL.ftpType == 1)
                {
                    var file_type_array = ["07", "08", "09", "C1"];
                    var file_name_array = display.split('.');
                    var get_file_first_string = file_name_array[0];
                    var get_file_last_character = get_file_first_string.slice(-2);
                    //alert(file_type_array);
                    for (var i = 0; i < file_type_array.length; i++) {
                        if (file_type_array[i] === get_file_last_character) {

                            dlist += "<input type=\"checkbox\" class=\"checkbox\" name=\"sfile\" style=\"display:inline;\" value=\"";
                            dlist += file;
                            dlist += "\">&nbsp;";
                            dlist += display;
                            dlist += "<br>";
                        }
                    }
                }
                else
                {
                    dlist += "<input type=\"checkbox\" class=\"checkbox\" name=\"sfile\" style=\"display:inline;\" value=\"";
                    dlist += file;
                    dlist += "\">&nbsp;";
                    dlist += display;
                    dlist += "<br>";
                }
                node = nodeList.nextNode();
            }
            //HTTPs Download Guide Product Guide
            dlist += "<br>";
            dlist += "<div class=\"panel-footer\">";
            dlist += "<div class=\"selectwrap\">";
            dlist += "<input type=\"button\" value=\"Download Files\" onclick=\"Download()\">";
            dlist += "&nbsp;<input type=\"button\" value=\"Cancel\" onclick=\"CancelDownload()\">";
            dlist += "</div>";
            dlist += "<br>";
            dlist += "</div>";
            dlist += "</form>";
    }
	console.log(dlist);
	//document.getElementByID("FileList").innerHTML=dlist;
	document.getElementById("FileList").innerHTML=dlist;
    Download();
    //FileList.innerHTML = dlist;
}
function Download()
{
    HTTPDL.LocalDirectory = DestDir.value;
	document.getElementById('subscribe_frm');
	var selection = document.forms["Selection"].sfile;

    //var selection = document.forms[0].sfile;console.log(selection);
    var flist = "";
    for ( index=0; index<selection.length; ++index)
    {//alert(selection[index]);
        /*if ( selection[index].checked )
        {*/
            flist += selection[index].value;
            flist += ";";
        /*}*/
    }
    PostResult("Begin Download");
    //HTTPs Download Guide Product Guide
    HTTPDL.DownloadFiles( flist );
}
function CancelDownload()
{
    HTTPDL.CancelRequest();
}
function TerminateDownload()
{
    HTTPDL.Terminate();
}
function PostResult( msg )
{
    content = HTTPDL_result.value;
    content += msg;
    content += "\r\n";
    HTTPDL_result.value = content;
}
</script>
<script for="HTTPDL" event="DownloadError( code, msg )" language="javascript">
    PostResult(msg);
</script>
<script for="HTTPDL" event="DownloadComplete()" language="javascript">
//HTTPs Download Guide Product Guide
PostResult("DownloadComplete");
</script>
<script for="HTTPDL" event="DownloadProgress( msg )" language="javascript">
PostResult( msg );
</script>
<!--<div class="container">
<h1>Import</h1>

<div class="col-lg-8 well">
<div class="tab-content col-md-12">
</div>
</div>

<div class="col-lg-4 well">-->

<!--<div class="tab-content col-md-12">
</div>
</div>
</div>-->
<div class="sectionwrapper" style="flex: 1; overflow: auto;">
  <div class="container">
  <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		  <div class="graphbox">
		        <div class="graphboxtitle">Import </div>
				<div class="graphboxcontent">
                <div class="tab-content col-md-12">

                <!-- Record Grid -> RESOLVE EXCEPTIONS & PROCESSED Tabs -->
                <?php
                if(isset($_GET['tab']) && ($_GET['tab']=="review_files" || $_GET['tab']=="processed_files") && $_GET['id']>0){
                    $get_file_data = $instance->select_user_files($_GET['id']);
                    $get_file_type = empty($_GET['file_type']) ? $instance->get_file_type($_GET['id']) : $_GET['file_type'];
                    $fileTypeDescription = ($get_file_type==3) ? 'Security File' : $get_file_data['file_type'] ;

                    if ($_GET['tab']=="review_files") { ?>
                        <h3>Review & Resolve Exceptions</h3><br />
                        <h4 style="margin-right: 5% !important; display: inline;">File: <?php if(isset($get_file_data['file_name'])){ echo $get_file_data['file_name']; } ?></h4>
                        <h4 style="margin-right: 5% !important; display: inline;">Source: <?php if(isset($get_file_data['source'])){ echo $get_file_data['source']; } ?></h4>
                        <h4 style="margin-right: 5% !important; display: inline;">File Type: <?php echo $fileTypeDescription ?></h4>
                        <h4 style="margin-right: 5% !important; display: inline;">Date: <?php if(isset($get_file_data['last_processed_date']) && $get_file_data['last_processed_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($get_file_data['last_processed_date']));}else echo '00-00-0000' ?></h4>

                        <?php if(in_array($get_file_type, [2, 9])){ ?>
                            <h4 style="margin-right: 0% !important; display: inline;">Amount: <?php echo '$'.number_format($total_commission_amount,2);?></h4>
                        <?php }
                    } else { ?>
                        <h3>Preview Data</h3><br />
                        <h4 style="margin-right: 5% !important; display: inline;">File: <?php if(isset($get_file_data['file_name'])){ echo $get_file_data['file_name']; } ?></h4>
                        <h4 style="margin-right: 5% !important; display: inline;">Source: <?php if(isset($get_file_data['source'])){ echo $get_file_data['source']; } ?></h4>
                        <h4 style="margin-right: 5% !important; display: inline;">File Type: <?php echo $fileTypeDescription ?></h4>
                        <h4 style="margin-right: 5% !important; display: inline;">Date: <?php if(isset($get_file_data['last_processed_date']) && $get_file_data['last_processed_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($get_file_data['last_processed_date']));}else echo '00-00-0000' ?></h4>
                        <?php
                        $file_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
                        $get_file_type =  '';
                        $get_file_type_source = $instance->get_current_file_type($file_id);

                        if($get_file_type_source == 'DSTFANMail') {
                            $get_file_type = 1;
                        } else if($get_file_type_source == 'DSTIDC') {
                            $get_file_type = 2;
                        } else if($get_file_type_source == 'GENERIC') {
                            $get_file_type = 9;
                        }

                        if(isset($get_file_type) && in_array($get_file_type, [2, 9])){
                            $total_amount = 0;
                            
                            if ($get_file_type == 9){
                                $return_file_data_array = $instance->get_gen_detail_data($file_id);
                            } else {
                                $return_file_data_array = $instance->get_file_array($file_id);
                            }

                            foreach($return_file_data_array as $preview_key=>$preview_val)
                            {
                                $total_amount += $preview_val['dealer_commission_amount'];
                            }
                            $total_commission_amount = $total_amount; ?>

                            <h4 style="margin-right: 0% !important; display: inline;">Amount: <?php echo '$'.number_format($total_commission_amount/100,2);?></h4>
                        <?php }
                    }
                } ?>

                <!-- Import Grid -->
                <div class="tab-pane active" id="tab_a"><?php if(isset($_GET['tab']) && ($_GET['tab']=="current_files" || $_GET['tab']=="archived_files") || !isset($_GET['tab'])){?>
                    <ul class="nav nav-tabs ">
                      <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="current_files"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>" ><a href="#current_files" data-toggle="tab">Current Files</a></li>
                      <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="archived_files"){ echo "active"; } ?>" ><a href="#archived_files" data-toggle="tab">Archived Files</a></li>
                     </ul> <?php } ?> <br />
                      <!-- Tab 1 is started -->
                        <div class="tab-content">
                        <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="current_files"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>" id="current_files">

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
                                                    <th>Source</th>
                                                    <th>File Name</th>
                                                    <th>File Type</th>
                                                    <!--<th>Imported</th>-->
                                                    <th>Last Processed</th>
                                                    <th>Sponsor</th>
                                                    <th>Batch#</th>
                                                    <th>Results</th>
                                                    <th>Note</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $count = 0;
                                                if(isset($return) && $return != array())
                                                {
                                                    $return = $instance->select_current_files(1);

                                                    foreach($return as $key=>$val){
                                                        $return_file_data_array = $instance->get_file_array($val['id']);
                                                        $isImportCompleted=$val['processed'] == 1 && $val['process_completed']==1;
                                                        $isImportArchived= $val['is_archived']==1;
                                                        $isImportNotStart= $val['processed']==0;
                                                        $isImportStarted= $val['processed']==1 && $val['process_completed']!=1;

                                                        $system_id = isset($return_file_data_array[0]['dst_system_id'])?$return_file_data_array[0]['dst_system_id']:'';
                                                        $management_code = isset($return_file_data_array[0]['dst_management_code'])?$return_file_data_array[0]['dst_management_code']:'';
                                                        $sponsor_detail = $instance_sponsor->edit_sponsor($val['sponsor_id']);
                                                        $sponsor = isset($sponsor_detail['name'])?$sponsor_detail['name']:'';
                                                        //echo '<pre>';print_r($system_id.','.$management_code);
                                                        $file_batch_id = $instance->get_file_batch($val['id']);
                                                        if (!isset($val['file_type'])) {
                                                            $file_type_id = 1;
                                                        } else if ($val['file_type'] == 'DST Commission'){
                                                            $file_type_id = 2;
                                                        } else if ($val['file_type'] == 'Security File'){
                                                            $file_type_id = 3;
                                                        } else if (stripos($val['file_type'], 'generic commission')!==false){
                                                            $file_type_id = 9;
                                                        } else {
                                                            $file_type_id = 1;
                                                        }

                                                        if(isset($val['imported_date']) && $val['imported_date']!= '') {?>
                                                            <tr id="<?php echo '$key'.$key ?>">
                                                                <td><?php echo $val['source'];?></td>
                                                                <td style="width: 10%;"><?php echo $val['file_name'];?></td>
                                                                <td style="width: 15%;"><?php echo $val['file_type'];?></td>
                                                                <td style="width: 10%;"><?php if(isset($val['last_processed_date']) && $val['last_processed_date'] != '0000-00-00 00:00:00'){echo date('m/d/Y H:i:s',strtotime($val['last_processed_date']));}?></td>
                                                                <td style="width: 15%;"><a href="<?php echo CURRENT_PAGE."?tab=preview_files&id={$val['id']}&file_type=$file_type_id" ;?>"><?php echo $sponsor;?></a></td>
                                                                <td><?php echo in_array($file_type_id, [2, 9]) ? $file_batch_id : 'N/A';?></td>
                                                                <!--<td style="width: 15%;"><?php echo date('m/d/Y',strtotime($val['imported_date']));?></td>-->

                                                                <?php
                                                                    // Client & Security data are in the same file, but in different Detail tables so separate the processed/exceptions counts(i.e. different tables)
                                                                    $detailTable = '';

                                                                    if (!in_array($val['file_type'], ['C1', 'DST Commission'])) {
                                                                        $detailTable = ($val['file_type']=='Security File') ? IMPORT_SFR_DETAIL_DATA : IMPORT_DETAIL_DATA;
                                                                    }

                                                                    $total_processed_data = $instance->check_file_exception_process($val['id'], 1, $detailTable);
                                                                    $count_processed_data = $total_processed_data['processed'];
                                                                    $count_exception_data = $total_processed_data['exceptions'];

                                                                    if($count_processed_data + $count_exception_data > 0)
                                                                    {
                                                                        $total_process = $count_processed_data+$count_exception_data;
                                                                        $total_processed_per = ($count_processed_data*100)/$total_process;
                                                                        $total_complete_process = round($total_processed_per);
                                                                    }
                                                                    else {
                                                                        $total_complete_process=0;
                                                                    }
                                                                ?>

                                                                <td style="width: 15%;">
                                                                    <div class="progress">
                                                                        <?php //echo $count_processed_data."/".$count_exception_data;?>

                                                                        <?php if(isset($total_complete_process) && $total_complete_process < 100){?>
                                                                            <?php $progress_bar_style = ($count_exception_data>0) ? 'danger' : 'warning';?>
                                                                            <div class="progress-bar progress-bar-<?php echo $progress_bar_style;?> progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $total_complete_process;?>%">
                                                                            <?php echo $total_complete_process.'%';?> Complete
                                                                            </div>
                                                                        <?php }else{ ?>
                                                                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $total_complete_process;?>%">
                                                                            <?php echo $total_complete_process.'%';?> Complete
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </td>
                                                                <?php
                                                                    $check_exception_data = $instance->check_exception_data($val['id']);
                                                                    $check_processed_data = $instance->check_processed_data($val['id']);
                                                                    $check_file_exception_process = $instance->check_file_exception_process($val['id']);
                                                                ?>
                                                                <td style="width: 10%;"><form method="post">
                                                                    <input type="hidden" name="id" id="id" value="<?php echo $val['id'];?>" />
                                                                    <input type="hidden" name="note" value="save_note" />
                                                                    <input type="text" maxlength="20" value="<?php echo isset($val['note'])?$val['note']:'';?>" name="note_<?php echo $val['id'];?>">
                                                                    <input type="hidden" name="process_file_type" id="process_file_type" value="<?php echo $val['file_type'];?>" />
                                                                    </form>
                                                                </td>
                                                                <td style="width: 25%;">
                                                                    <form method="post">
                                                                        <select name="process_file_<?php echo $val['id'];?>" id="process_file_<?php echo $val['id'];?>" class="form-control form-go-action" style=" width: 100% !important;display: inline;">
                                                                            <option value="0">Select Option</option>
                                                                            <option value="2" <?php echo $isImportNotStart? "selected='selected'" : "disabled='disabled'"; ?>>Process</option>
                                                                            <option value="5" <?php  echo !$isImportNotStart && !$isImportCompleted ? "":"disabled='disabled'";  ?>>Reprocess</option>
                                                                            <option value="3" <?php if($val['processed']==0){echo 'disabled="true"';}?> >View/Print</option>
                                                                            <option value="4" <?php if($val['processed']==0){echo 'disabled="true"';}?>>Resolve Exceptions</option>
                                                                            <option value="6" <?php  echo ($isImportCompleted && !$isImportArchived) ? "" : "disabled='disabled'" ?>>Move To Archived</option>
                                                                            <option value="1" >Delete File</option>
                                                                            <!-- <option value="7" >Preview</option> -->
                                                                        </select>
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $val['id'];?>" />
                                                                        <input type="hidden" name="process_file_type" id="process_file_type" value="<?php echo $val['file_type'];?>" />
                                                                        <input type="hidden" name="go" value="go" />
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                    <?php }}
                                                }?>
                                              </tbody>
                                            </table>
                                        </div>
                                    </div>
                                 </div>
                                    <div class="panel-overlay">
                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                    </div>
                                 </div>

                            </div>
                            <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="archived_files"){ echo "active"; } ?>" id="archived_files">
                                <div class="panel-overlay-wrap">
                                    <div class="panel-body" style="border: 1px solid #DFDFDF; margin-top: 17px;">
                                        <div class="row">
                                            <div class="table-responsive" style="margin: 0px 5px 0px 5px;">
                                                <table id="data-table1" class="table table-bordered table-stripped table-hover">
                                                    <thead>
                                                        <th>Batch#</th>
                                                        <th>Imported</th>
                                                        <th>Last Processed</th>
                                                        <th>File Name</th>
                                                        <th>File Type</th>
                                                        <th>Source</th>
                                                        <th></th>
                                                        <th>Action</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $count = 0;
                                                    if(isset($return) && $return != array())
                                                    {
                                                        $return = $instance->select_archive_files();
                                                        foreach($return as $key=>$val){
                                                            $file_batch_id = $instance->get_file_batch($val['id']);
                                                            $check_exception_data = $instance->check_exception_data($val['id']);

                                                            if(isset($val['imported_date']) && $val['imported_date']!= '' && $check_exception_data <= 0){ ?>
                                                                <tr>
                                                                    <td><?php echo $file_batch_id; ?></td>
                                                                    <td style="width: 15%;"><?php echo date('m/d/Y',strtotime($val['imported_date']));?></td>
                                                                    <td style="width: 10%;"><?php if(isset($val['last_processed_date']) && $val['last_processed_date'] != '0000-00-00'){echo date('m/d/Y',strtotime($val['last_processed_date']));}?></td>
                                                                    <td style="width: 10%;"><?php echo $val['file_name'];?></td>
                                                                    <td style="width: 15%;"><?php echo $val['file_type'];?></td>
                                                                    <td><?php echo $val['source'];?></td>

                                                                    <?php
                                                                        $total_processed_data = $instance->check_file_exception_process($val['id'],1);
                                                                        $count_processed_data = $total_processed_data['processed'];
                                                                        $count_exception_data = $total_processed_data['exceptions'];

                                                                        if($count_processed_data + $count_exception_data > 0) {
                                                                            $total_process = $count_processed_data+$count_exception_data;
                                                                            $total_processed_per = ($count_processed_data*100)/$total_process;
                                                                            $total_complete_process = round($total_processed_per);
                                                                        } else {
                                                                            $total_complete_process=0;
                                                                        }
                                                                    ?>

                                                                    <td style="width: 20%;">
                                                                    <div class="progress">
                                                                        <?php if(isset($total_complete_process) && $total_complete_process < 100) { ?>
                                                                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $total_complete_process;?>%">
                                                                            <?php echo $total_complete_process.'%';?> Complete
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $total_complete_process;?>%">
                                                                            <?php echo $total_complete_process.'%';?> Complete
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                    </td>

                                                                    <td style="width: 30%;">
                                                                        <form method="post">
                                                                            <select name="archive_option" id="archive_option" class="form-control" style=" width: 78% !important;display: inline;">
                                                                                <option value="0">Select Option</option>
                                                                                <option value="1" selected="true" >View/Print</option>
                                                                            </select>
                                                                            <input type="hidden" name="id" id="id" value="<?php echo $val['id'];?>" />
                                                                            <button type="submit" class="btn btn-sm btn-warning" name="go_archive" value="go_archive" style="display: inline;"> Go</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            <?php }}
                                                        }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="panel-overlay">
                                         <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="tab-content col-md-12">
                    <div class="tab-pane <?php if(isset($_GET['tab']) && ($_GET['tab']=="review_files" || $_GET['tab']=="processed_files")){ echo "active"; } ?>" id="tab_review"><?php if(isset($_GET['tab']) && ($_GET['tab']=="review_files" || $_GET['tab']=="processed_files") && $_GET['id']>0){?>
                        <ul class="nav nav-tabs ">
                          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="review_files"){ echo "active"; }?>" ><a href="#review_files" data-toggle="tab">Exceptions For Review</a></li>
                          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="processed_files"){ echo "active"; } ?>" ><a href="#processed_files" data-toggle="tab">Processed</a></li>
                        </ul> <?php } ?> <br />
                           
                        <!-- Tab 1 is started -->
                            <div class="tab-content">
                            <div class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="review_files" && $_GET['id']>0){ echo "active"; } ?>" id="review_files">

                                <div class="panel-overlay-wrap">
                                    <div class="panel-body" style="border: 1px solid #DFDFDF; margin-top: 17px;">
                                        <div class="row">
                                            <!--<div class="row">
                                            <div class="col-md-5"></div>
                                                <a class="btn btn-sm btn-warning col-md-1" href="<?php echo CURRENT_PAGE; ?>?action=open_ftp"> Fetch</a>
                                                <!--<a href="<?php echo CURRENT_PAGE; ?>?action=open_ftp"><button type="button"  name="fetch" value="fetch" style="display: inline;"> Fetch</button></a>-->
                                                <!--<button type="submit" class="btn btn-sm btn-default col-md-2"  name="progress_all" value="progress_all" style="display: inline;"> Process All</button>
                                            </div>
                                            <br />-->
                                            <?php
                                            if (isset($_GET['file_type'])) {
                                                $get_file_type = $_GET['file_type'];
                                            } else {
                                                $get_file_type = $instance->get_file_type($_GET['id']);
                                            }
                                            ?>

                                            <div class="table-responsive" style="margin: 0px 5px 0px 5px;">
                                                <table id="data-table3" class="table table-bordered table-stripped table-hover">
                                                    <thead>
                                                        <th>Date</th>
                                                        <?php if(isset($get_file_type) && in_array($get_file_type, ['1', '2', '9'])) { ?>
                                                            <th>Rep#</th>
                                                            <th>Rep Name</th>
                                                            <th>Account#</th>
                                                            <th>Client Name</th>
                                                        <?php } ?>
                                                        <?php if(isset($get_file_type) && $get_file_type == '1'){?>
                                                            <th>Client Address</th>
                                                        <?php } else if(isset($get_file_type) && in_array($get_file_type, ['2', '9'])){?>
                                                            <th>CUSIP</th>
                                                            <th>Principal</th>
                                                            <th>Commission</th>
                                                        <?php } else if(isset($get_file_type) && $get_file_type == '3'){?>
                                                            <th>Fund Name</th>
                                                            <th>CUSIP</th>
                                                            <th>Ticker Symbol</th>
                                                            <th>Security Type</th>
                                                        <?php } ?>

                                                        <th>Issue</th>
                                                        <th>Action</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $existing_field_value = '';
                                                        $existingDetailValues = [];
                                                        $file_id = (isset($_GET['id'])) ? (int)$instance->re_db_input($_GET['id']) : 0;
                                                        $file_type = (isset($_GET['file_type'])) ? (int)$instance->re_db_input($_GET['file_type']) : 1;
                                                        $return_exception = $instance->select_exception_data(0, 0, "`at`.`is_delete`=0 AND `at`.`file_id`=$file_id AND `at`.`file_type`=$file_type AND `at`.`solved`=0");

                                                        foreach($return_exception as $error_key=>$error_val)
                                                        {
                                                            if(isset($error_val['file_type']) && $error_val['file_type'] == '1')
                                                            {
                                                                $return_fanmail_existing_data = $instance->select_existing_fanmail_data($error_val['temp_data_id']);
                                                                if($error_val['field'] == 'social_security_number')
                                                                {
                                                                    $existing_field_value = ($error_val['error_code_id']==13 ? 'blank' : $return_fanmail_existing_data['social_security_number']);
                                                                }
                                                                if($error_val['field'] == 'mutual_fund_customer_account_number')
                                                                {
                                                                    $existing_field_value = $return_fanmail_existing_data['mutual_fund_customer_account_number'];
                                                                }
                                                                if($error_val['field'] == 'registration_line1')
                                                                {
                                                                    $existing_field_value = $return_fanmail_existing_data['registration_line1'];
                                                                }
                                                                if($error_val['field'] == 'u5')
                                                                {
                                                                    $rep_number = $return_fanmail_existing_data['representative_number'];
                                                                    $u5_date = $instance->broker_termination_date($rep_number);
                                                                    $existing_field_value = date('m/d/Y',strtotime($u5_date));
                                                                }
                                                            }
                                                            if(isset($error_val['file_type']) && in_array($error_val['file_type'], ['2', '9']))
                                                            {   
                                                                switch ($error_val['file_type']){
                                                                    case '9':
                                                                        $return_commission_existing_data = $instance->select_existing_gen_data($error_val['temp_data_id']);
                                                                        break;
                                                                    default:
                                                                        $return_commission_existing_data = $instance->select_existing_idc_data($error_val['temp_data_id']);
                                                                        break;
                                                                }
                                                                // Display the names found during the processing of the data. Sometimes they don't match what was sent in the data fields (alpha_code & rep_name), or the
                                                                // the descrtiptions are vague, i.e. "Trust Account 123"
                                                                $instance_client = new client_maintenance();
                                                                $instance_product = new product_maintenance();
                                                                $instance_broker = new broker_master();
                                                                $instance_import = new import();
                                                                $instance_suitability = new client_suitebility_master();

                                                                if (!empty($return_commission_existing_data['broker_id'])) {
                                                                    $brokerRow = $instance_broker->select_broker_by_id($return_commission_existing_data['broker_id']);

                                                                    if ($brokerRow) {
                                                                        $error_val['rep_name'] = trim($brokerRow['last_name']).(($brokerRow['last_name']!='' AND $brokerRow['last_name']!='') ? ', ' : '').trim($brokerRow['first_name']);
                                                                    }
                                                                }

                                                                if (!empty($return_commission_existing_data['client_id'])) {
                                                                    $clientDetail = $instance_client->get_client_name($return_commission_existing_data['client_id']);

                                                                    if ($clientDetail) {
                                                                        $error_val['client'] = trim($clientDetail[0]['last_name']).(($clientDetail[0]['first_name']!='' AND $clientDetail[0]['last_name']!='') ? ', ' : '').trim($clientDetail[0]['first_name']);
                                                                    }
                                                                }

                                                                // Exception Types
                                                                if($error_val['field'] == 'customer_account_number') {
                                                                    $existing_field_value = $return_commission_existing_data['customer_account_number'];
                                                                }

                                                                if($error_val['field'] == 'cusip_number') {
                                                                    $existing_field_value = $return_commission_existing_data['cusip_number'];
                                                                }

                                                                if($error_val['field'] == 'u5') {
                                                                    $rep_number = $return_commission_existing_data['representative_number'];
                                                                    $u5_date = $instance->broker_termination_date($rep_number, $return_commission_existing_data['broker_id']);
                                                                    $existing_field_value = date('m/d/Y',strtotime($u5_date));
                                                                }

                                                                if($error_val['field'] == 'active_check') {
                                                                    // 1.State / 2.ProdCat / 3.TermDate
                                                                    $clientDetail = $instance_client->get_client_name($return_commission_existing_data['client_id']);
                                                                    if (empty($return_commission_existing_data['product_id'])){
                                                                        $productDetail = $instance_product->product_list_by_query("`is_delete`=0 AND `cusip` = '".$instance_client->re_db_input($return_commission_existing_data['cusip_number'])."'");
                                                                    } else {
                                                                        $productDetail = $instance_product->edit_product($return_commission_existing_data['product_id']);
                                                                    }
                                                                    $licenceDetail = $instance_import->checkStateLicence($return_commission_existing_data['broker_id'], $clientDetail[0]['state'], $productDetail['category'], $return_commission_existing_data['trade_date'], 1);
                                                                    $category = substr($licenceDetail['licence_table'], strrpos($licenceDetail['licence_table'], '_') +1 );
                                                                    $existing_field_value = trim($category).' / '.trim($licenceDetail['state_name']);
                                                                }

                                                                if($error_val['field'] == 'objectives') {
                                                                    $productObjectiveId = 0;
                                                                    $existing_field_value = '';
                                                                    $res = 0;
                                                                        
                                                                    if (!empty($return_commission_existing_data['product_id'])){
                                                                        $productDetail = $instance_product->edit_product($return_commission_existing_data['product_id']);
                                                                    } else {
                                                                        $productDetail = $instance_product->product_list_by_query("`is_delete`=0 AND `cusip` = '".$instance_client->re_db_input($return_commission_existing_data['cusip_number'])."'");
                                                                    }
                                                                    
                                                                    if ($productDetail) {
                                                                        $productObjectiveId = (int)$productDetail['objective'];

                                                                        $res = $instance_suitability->edit_objective($productObjectiveId);

                                                                        if ($res) {
                                                                            $existing_field_value = $res['option'];
                                                                        }
                                                                    }
                                                                }

                                                                if($error_val['field'] == 'alpha_code') {
                                                                    $existing_field_value = trim($error_val['field_value']);
                                                                }
                                                            }
                                                            if(isset($error_val['file_type']) && $error_val['file_type'] == '3')
                                                            {
                                                                $return_sfr_existing_data = $instance->select_existing_sfr_data($error_val['temp_data_id']);

                                                                if($error_val['field'] == 'cusip_number') {
                                                                    $existing_field_value = $return_sfr_existing_data['cusip_number'];
                                                                } else if($error_val['field'] == 'ticker_symbol') {
                                                                    $existing_field_value = $return_sfr_existing_data['ticker_symbol'];
                                                                } else if($error_val['field'] == 'fund_name') {
                                                                    $existing_field_value = $return_sfr_existing_data['fund_name'];
                                                                } else if($error_val['field'] == 'major_security_type') {
                                                                    $existing_field_value = $return_sfr_existing_data['major_security_type'];
                                                                }
                                                            }

                                                        ?>
                                                        <tr>
                                                            <td><?php echo date('m/d/Y',strtotime($error_val['date']));?></td>
                                                            <?php if(isset($error_val['file_type']) && in_array($error_val['file_type'], ['1', '2', '9'])) { ?>
                                                                <td><?php echo $error_val['rep'];?></td>
                                                                <td><?php echo $error_val['rep_name'];?></td>
                                                                <td><?php echo $error_val['account_no'];?></td>
                                                                <td><?php echo $error_val['client'];?></td>
                                                            <?php } ?>

                                                            <?php if(isset($error_val['file_type']) && $error_val['file_type'] == '1'){
                                                                $get_client_data = $instance->get_client_data($file_id,$error_val['temp_data_id']); ?>
                                                                <td><?php echo $get_client_data[0]['client_address'];?></td>
                                                            <?php } else if(isset($error_val['file_type']) && in_array($error_val['file_type'], ['2','9'])) { ?>
                                                                <td><?php echo $error_val['cusip'];?></td>
                                                                <td style="text-align: right;"><?php if($error_val['principal'] > 0){ echo '$'.number_format($error_val['principal'],2);}else{ echo '$0';}?></td>
                                                                <td style="text-align: right;"><?php if($error_val['commission'] > 0){ echo '$'.number_format($error_val['commission'],2);}else{ echo '$0';}?></td>
                                                            <?php } else if(isset($error_val['file_type']) && $error_val['file_type'] == '3') { ?>
                                                                <?php
                                                                    $return_sfr_existing_data = $instance->select_existing_sfr_data($error_val['temp_data_id']);
                                                                    $existingDetailValues = ['fund_name'=> $return_sfr_existing_data['fund_name'], 'cusip_number'=>$return_sfr_existing_data['cusip_number'], 'ticker_symbol'=>$return_sfr_existing_data['ticker_symbol'], 'product_category_id'=>$return_sfr_existing_data['product_category_id']];
                                                                ?>
                                                                <td><?php echo $return_sfr_existing_data['fund_name'] ?></td>
                                                                <td><?php echo $return_sfr_existing_data['cusip_number'] ?></td>
                                                                <td><?php echo $return_sfr_existing_data['ticker_symbol'] ?></td>
                                                                <td><?php echo $return_sfr_existing_data['major_security_type'] ?></td>
                                                            <?php } ?>
                                                            <td><?php echo $error_val['error'];?></td>
                                                            <td style="width: 20%;">
                                                            <form method="post">
                                                            <!-- 02/01/22 Remove "ADD" dropdown, only has one option
                                                            <select name="review_action_" id="review_action_" class="form-control" style=" width: 75% !important;display: inline;">
                                                                <option value="0">ADD</option>
                                                            </select> -->
                                                            <input type="hidden" name="id" id="id" value="" />
                                                            <a href="#solve_exception_model" data-toggle="modal"><button type="submit" onclick="add_exception_value('<?php echo $error_val['file_id'];?>','<?php echo $error_val['file_type'];?>','<?php echo $error_val['temp_data_id'];?>','<?php echo $error_val['field'];?>','<?php echo $error_val['rep'];?>','<?php echo $existing_field_value;?>',<?php echo $error_val['error_code_id'];?>,<?php echo $error_val['id'];?>,'<?php echo $error_val['account_no'];?>');" class="btn btn-sm btn-warning" name="go" value="go" style="display: inline;">Resolve</button></a>
                                                            </form>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                   </tbody>
                                                </table>
                                            </div>
                                        </div>
                                     </div>
                                        <div class="panel-overlay">
                                            <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                        </div>
                                     </div>
                                </div>

                                <div class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="processed_files" && $_GET['id']>0){ echo "active"; } ?>" id="processed_files">
                                    <div class="panel-overlay-wrap">
                                        <div class="panel-body" style="border: 1px solid #DFDFDF; margin-top: 17px;">
                                            <div class="row">
                                            <?php if (isset($_GET['file_type'])) {
                                                $get_file_type = $instance->re_db_input($_GET['file_type']);
                                            } else {
                                                $get_file_type = $instance->get_file_type($_GET['id']);
                                            } ?>

                                                <div class="table-responsive" style="margin: 0px 5px 0px 5px;">
                                                    <table id="data-table4" class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <th>Date</th>

                                                            <?php if(isset($get_file_type) && $get_file_type == '3') { ?>
                                                                <th>Fund Name</th>
                                                                <th>CUSIP</th>
                                                                <th>Ticker Symbol</th>
                                                                <th>Security Type</th>
                                                            <?php } else { ?>
                                                                <th>Rep#</th>
                                                                <th>Rep Name</th>
                                                                <th>Account#</th>
                                                                <th>Client Name</th>
                                                            <?php } ?>

                                                            <?php if(isset($get_file_type) && $get_file_type == '1') { ?>
                                                                <th>Client Address</th>
                                                            <?php } else if(isset($get_file_type) && in_array($get_file_type, ['2','9'])){ ?>
                                                                <th>CUSIP</th>
                                                                <th>Principal</th>
                                                                <th>Commission</th>
                                                            <?php } ?>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $file_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
                                                            $get_sfr_detail_data = [];
                                                            if (isset($_GET['file_type'])) {
                                                                $get_file_type = $instance->re_db_input($_GET['file_type']);
                                                            } else {
                                                                $get_file_type = $instance->get_file_type($_GET['id']);
                                                            }
                                                            $return_solved_exception = $instance->select_solved_exception_data($file_id, $get_file_type);

                                                            foreach($return_solved_exception as $process_key=>$process_val)
                                                            {
                                                                $total_commission_amount = $total_commission_amount+$process_val['commission'];
                                                                ?>

                                                                <tr>
                                                                    <td><?php echo date('m/d/Y',strtotime($process_val['date']));?></td>

                                                                    <?php if(isset($get_file_type) && $get_file_type == '3') {
                                                                        $get_sfr_detail_data = $instance->select_existing_sfr_data($process_val['temp_data_id']);
                                                                        $security_type = $instance_batches->get_product_type($get_sfr_detail_data['product_category_id']);
                                                                        ?>

                                                                        <td><?php echo $get_sfr_detail_data['fund_name'];?></td>
                                                                        <td><?php echo $get_sfr_detail_data['cusip_number'];?></td>
                                                                        <td><?php echo $get_sfr_detail_data['ticker_symbol'];?></td>
                                                                        <td><?php if ($security_type!=''){echo $security_type;} else {echo $get_sfr_detail_data['major_security_type'];}?></td>
                                                                    <?php } else { ?>
                                                                        <td><?php echo $process_val['rep'];?></td>
                                                                        <td><?php echo $process_val['rep_name'];?></td>
                                                                        <td><?php echo $process_val['account_no'];?></td>
                                                                        <td><?php echo $process_val['client'];?></td>
                                                                    <?php } ?>


                                                                    <?php if(isset($get_file_type) && $get_file_type == '1') {
                                                                        $get_client_data = $instance->get_client_data($file_id,$process_val['temp_data_id']); ?>
                                                                        <td><?php echo $get_client_data[0]['client_address'];?></td>
                                                                    <?php } else if(isset($get_file_type) && in_array($get_file_type,['2','9'])) { ?>
                                                                        <td><?php echo $process_val['cusip'];?></td>
                                                                        <td style="text-align: right;"><?php if($process_val['principal'] > 0){ echo '$'.number_format($process_val['principal'],2);}else{ echo '$0';}?></td>
                                                                        <td style="text-align: right;"><?php if($process_val['commission'] > 0){ echo '$'.number_format($process_val['commission'],2);}else{ echo '$0';}?></td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                         </div>
                                         <div class="panel-overlay">
                                             <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content col-md-12">
                    <div class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="view_processed_files"){ echo "active"; } ?>" id="tab_view"><?php if(isset($_GET['tab']) && $_GET['tab']=="view_processed_files" && $_GET['id']>0){?>
                        <ul class="nav nav-tabs ">
                          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="view_processed_files"){ echo "active"; } ?>" ><a href="#view_processed_files" data-toggle="tab">View Processed Data</a></li>
                        </ul> <?php } ?> <br />
                          <!-- Tab 1 is started -->
                            <div class="tab-content">
                                <div class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="view_processed_files" && $_GET['id']>0){ echo "active"; } ?>" id="view_processed_files">
                                    <div class="panel-overlay-wrap">
                                        <div class="panel-body" style="border: 1px solid #DFDFDF; margin-top: 17px;">
                                            <div class="row">
                                            <?php
                                            $get_file_type = $instance->get_file_type($_GET['id']);
                                            ?>
                                                <div class="table-responsive" style="margin: 0px 5px 0px 5px;">
                                                    <table id="data-table5" class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <th>Date</th>
                                                            <th>Rep#</th>
                                                            <th>Rep Name</th>
                                                            <th>Account#</th>
                                                            <th>Client Name</th>
                                                            <?php if(isset($get_file_type) && $get_file_type == '1'){
                                                            ?>
                                                            <th>Client Address</th>
                                                            <?php }
                                                            else if(isset($get_file_type) && in_array($get_file_type, ['2','9'])){?>
                                                            <th>CUSIP</th>
                                                            <th>Principal</th>
                                                            <th>Commission</th>
                                                            <?php } ?>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $file_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
                                                            $return_processed_data = $instance->select_processed_data($file_id);
                                                            foreach($return_processed_data as $process_key=>$process_val)
                                                            {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo date('m/d/Y',strtotime($process_val['date']));?></td>
                                                                <td><?php echo $process_val['rep'];?></td>
                                                                <td><?php echo $process_val['rep_name'];?></td>
                                                                <td><?php echo $process_val['account_no'];?></td>
                                                                <td><?php echo $process_val['client'];?></td>
                                                                <?php
                                                                if(isset($get_file_type) && $get_file_type == '1'){
                                                                $get_client_data = $instance->get_client_data($file_id,$process_val['temp_data_id']);
                                                                ?>
                                                                <td><?php echo $get_client_data[0]['client_address'];?></td>
                                                                <?php }
                                                                else if(isset($get_file_type) && in_array($get_file_type,['2','9']))
                                                                { ?>
                                                                <td><?php echo $process_val['cusip'];?></td>
                                                                <td style="text-align: right;"><?php if($process_val['principal'] > 0){ echo '$'.number_format($process_val['principal'],2);}else{ echo '$0';}?></td>
                                                                <td style="text-align: right;"><?php if($process_val['commission'] > 0){ echo '$'.number_format($process_val['commission'],2);}else{ echo '$0';}?></td>
                                                                <?php } ?>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                         </div>
                                         <div class="panel-overlay">
                                             <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content col-md-12">
                    <div class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="preview_files"){ echo "active"; } ?>" id="tab_view"><?php if(isset($_GET['tab']) && $_GET['tab']=="preview_files" && $_GET['id']>0){?>
                        <ul class="nav nav-tabs ">
                          <li class="<?php if(isset($_GET['tab']) && $_GET['tab']=="preview_files"){ echo "active"; } ?>" ><a href="#preview_files" data-toggle="tab">Preview Data</a></li>
                         </ul> <?php } ?> <br />
                          <!-- Tab 1 is started -->
                            <div class="tab-content">
                                <div class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=="preview_files" && $_GET['id']>0){ echo "active"; } ?>" id="preview_files">
                                    <div class="panel-overlay-wrap">
                                        <div class="panel-body" style="border: 1px solid #DFDFDF; margin-top: 17px;">
                                            <div class="row">
                                            <?php
                                            $get_file_type =  '';
                                            $get_file_type_source = $instance->get_current_file_type($_GET['id']);
                                            if($get_file_type_source == 'DSTFANMail'){
                                                $get_file_type = 1;
                                            } else if($get_file_type_source == 'DSTIDC'){
                                                $get_file_type = 2;
                                            } else if($get_file_type_source == 'GENERIC'){
                                                $get_file_type = 9;
                                            }
                                            ?>
                                                <div class="table-responsive" style="margin: 0px 5px 0px 5px;">
                                                    <table id="data-table6" class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <th>Date</th>
                                                            <th>Rep#</th>
                                                            <th>Rep Name</th>
                                                            <th>Account#</th>
                                                            <th>Client Name</th>
                                                            <?php if(isset($get_file_type) && $get_file_type == '1'){ ?>
                                                                <th>Client Address</th>
                                                            <?php } else if(isset($get_file_type) && in_array($get_file_type,['2','9'])){ ?>
                                                                <th>CUSIP</th>
                                                                <th>Principal</th>
                                                                <th>Commission</th>
                                                            <?php }?>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $file_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
                                                            $return_file_data_array = $instance->get_file_array($file_id);
                                                            foreach($return_file_data_array as $preview_key=>$preview_val)
                                                            {?>
                                                             <tr>
                                                                <td><?php echo date('m/d/Y');?></td>
                                                                <td><?php echo $preview_val['representative_number'];?></td>
                                                                <td><?php echo $preview_val['representative_name'];?></td>
                                                                <?php
                                                                if(isset($get_file_type) && $get_file_type == '1'){
                                                                ?>
                                                                <td><?php echo $preview_val['mutual_fund_customer_account_number'];?></td>
                                                                <?php }
                                                                else if(isset($get_file_type) && in_array($get_file_type,['2','9'])){
                                                                ?>
                                                                <td><?php echo $preview_val['customer_account_number'];?></td>
                                                                <?php } ?>
                                                                <?php
                                                                if(isset($get_file_type) && $get_file_type == '1'){
                                                                ?>
                                                                <td><?php echo $preview_val['registration_line1'];?></td>
                                                                <?php }
                                                                else if(isset($get_file_type) && in_array($get_file_type,['2','9'])){
                                                                ?>
                                                                <td><?php echo $preview_val['alpha_code'];?></td>
                                                                <?php } ?>
                                                                <?php
                                                                if(isset($get_file_type) && $get_file_type == '1'){
                                                                ?>
                                                                <td>
                                                                <?php
                                                                if($preview_val['line_code'] == 1)
                                                                {
                                                                    echo $preview_val['registration_line1'].' '.$preview_val['registration_line2'].' '.$preview_val['registration_line3'].' '.$preview_val['registration_line4'];
                                                                }
                                                                else if($preview_val['line_code'] == 2)
                                                                {
                                                                    echo $preview_val['registration_line2'].' '.$preview_val['registration_line3'].' '.$preview_val['registration_line4'];
                                                                }
                                                                else if($preview_val['line_code'] == 3)
                                                                {
                                                                    echo $preview_val['registration_line3'].' '.$preview_val['registration_line4'];
                                                                }
                                                                else if($preview_val['line_code'] == 4)
                                                                {
                                                                    echo $preview_val['registration_line4'];
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                                ?>
                                                                </td>
                                                                <?php }
                                                                else if(isset($get_file_type) && in_array($get_file_type,['2','9']))
                                                                { ?>
                                                                <td><?php echo $preview_val['cusip_number'];?></td>
                                                                <td style="text-align: right;"><?php if($preview_val['gross_transaction_amount'] > 0){ echo '$'.number_format($preview_val['gross_transaction_amount']/100,2);}else{ echo '$0';}?></td>
                                                                <td style="text-align: right;"><?php if($preview_val['dealer_commission_amount'] > 0){ echo '$'.number_format($preview_val['dealer_commission_amount']/100,2);}else{ echo '$0';}?></td>
                                                                <?php } ?>
                                                             </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                         </div>
                                         <div class="panel-overlay">
                                             <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content col-md-12">
                    <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="open_ftp"){ echo "active"; } ?>" id="ftp">
                    <?php
                    if($action=='add_ftp'||($action=='edit_ftp' && $ftp_id>0)){ ?>
                        <form method="POST">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-control" style="float: right;">
                    				<div class="btn-group dropdown">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right"> <!--style="">-->
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?tab=open_ftp&action=view_ftp"><i class="fa fa-eye"></i> View List</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_ftp'?'Add':'Edit'; ?> FTP</h3>
                    		</div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Host Name <span class="text-red">*</span></label><br />
                                        <input type="text" class="form-control" name="host_name" value="<?php echo $host_name;?>"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User Name <span class="text-red">*</span></label><br />
                                        <input type="text" class="form-control" name="user_name" value="<?php echo $user_name;?>"  />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password <span class="text-red">*</span></label><br />
                                        <input type="password" class="form-control" name="password" value=""  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password <span class="text-red">*</span></label><br />
                                        <input type="password" class="form-control" name="confirm_password" value=""  />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Folder Location </label><br />
                                        <input type="text" class="form-control" name="folder_location" value="<?php echo $folder_location;?>"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-red">*</span></label><br />
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" <?php if($status != '' && $status == 1){echo "selected='selected'";} ?>>Active</option>
                                            <option value="0" <?php if($status != '' && $status == 0){echo "selected='selected'";} ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                           </div>
                           <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>File Type <span class="text-red">*</span></label><br />
                                        <select name="ftp_file_type" id="ftp_file_type" class="form-control">
                                            <option value="">Select FileType</option>
                                            <option value="1" <?php if($ftp_file_type != '' && $ftp_file_type == 1){echo "selected='selected'";} ?>>DST FANMAIL</option>
                                            <option value="2" <?php if($ftp_file_type != '' && $ftp_file_type == 2){echo "selected='selected'";} ?>>DST IDC</option>
                                        </select>
                                    </div>
                                </div>
                           </div>
                           </div>
                           <div class="panel-footer">
                                <div class="selectwrap">
                                    <input type="hidden" name="id" id="id" value="<?php echo $ftp_id; ?>" />
                                    <input type="submit" name="submit" onclick="waitingDialog.show();" value="Save"/>
                                    <a href="<?php echo CURRENT_PAGE.'?tab=open_ftp&action=view_ftp';?>"><input type="button" name="cancel" value="Cancel" /></a>
                                </div><br />
                           </div>

                        </div>
                        </form>
                        
                        <?php } else { ?>
                        
                        <div class="panel">
                        <form method="post" enctype="multipart/form-data">
                    		<!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                                        <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?tab=open_ftp&action=add_ftp"><i class="fa fa-plus"></i> Add new FTP Site</a></li>
                                            <li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-minus"></i> Back to List of Current Files Page</a></li>
                    					</ul>
                    				</div>
                    			</div>
                            </div><br />-->
                    		<div class="panel-body">
                                <div class="table-responsive">
                    			<table id="data-table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    	            <thead>
                    	                <tr>
                                            <th>Host Name</th>
                                            <th>Username</th>
                                            <th>Status</th>
                                            <th class="text-center">ACTION</th>
                                        </tr>
                    	            </thead>
                    	            <tbody>
             
                                    <?php 
                                    if(isset($_GET['tab']) && $_GET['tab'] =='open_ftp'){
                                        $count = 0;
                                        foreach($return_ftplist as $key=>$val){
                                    ?>
                                            <tr>
                                                <td><?php echo $val['host_name'];?></td>
                                                <td><?php echo $val['user_name'];?></td>
                                                <td class="text-center">
                                                    <?php if($val['status']==1){ ?>
                                                        <a href="<?php echo CURRENT_PAGE; ?>?action=ftp_status&ftp_id=<?php echo $val['id']; ?>&status=0" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Active</a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo CURRENT_PAGE; ?>?action=ftp_status&ftp_id=<?php echo $val['id']; ?>&status=1" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Inactive</a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo CURRENT_PAGE; ?>?tab=open_ftp&action=edit_ftp&ftp_id=<?php echo $val['id']; ?>" class="btn btn-md btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                    <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_ftp&ftp_id=<?php echo $val['id']; ?>');" class="btn btn-md btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                                    <a href="<?php echo CURRENT_PAGE; ?>?tab=get_ftp&ftp_id=<?php echo $val['id']; ?>" class="btn btn-md btn-warning"><i class="fa fa-download"></i> Fetch</a>
                                                    <!--<button type="submit" class="btn btn-md btn-warning" name="submit_files" value="Fetch"><i class="fa fa-download"></i> Fetch</button>-->
                                                </td>
                                            </tr>
                                        <?php } 
                                        // Add Generic CSV Interface for user-file upload -->
                                        if(!empty($instance_importGeneric->dataInterface)){ ?>
                                            <tr>
                                                <td><?php echo $instance_importGeneric->dataInterface['name'];?></td>
                                                <td>
                                                    <select name="generic_sponsor" id="generic_sponsor" class="form-control" style="display: block;">
                                                        <option value="">Select Sponsor</option>
                                                        <?php foreach($get_sponsor as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="generic_product_category" id="generic_product_category" class="form-control" onchange="get_product(this.value);open_product_link(this.value);">
                                                        <option value="0">Select Category</option>
                                                        <?php foreach($get_product_category as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php echo (isset($existingDetailValues['product_category_id']) AND $val['id']==$existingDetailValues['product_category_id'])?"selected":"" ?>><?php echo $val['type'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <!-- <td class="text-center"> -->
                                                <td>
                                                    <input type="file" name="upload_generic_csv_file" class="form-control" />
                                                    <!-- <a href="<?php echo CURRENT_PAGE; ?>?action=uploadGeneric" class="btn btn-md btn-warning"><i class="fa fa-download"></i> Upload</a> -->
                                                    <button type="submit" class="btn btn-md btn-warning" name="upload_generic_csv_file" value="upload_generic_csv_file"><i class="fa fa-download"></i> Upload</button>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                                </div>
                    		</div>
                        </form>
                    	</div>
                        <!--<form method="post" enctype="multipart/form-data">
                            <div class="row">
                				<div class="col-sm-12 form-group">
                					<label>Upload files </label>
                				    <input type="file" class="form-control" name="file" id="file" />
                                    <input type="submit" name="submit_files" onclick="waitingDialog.show();" value="Save"/>
                                </div>
                            </div>
                        </form>-->
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-content col-md-12">
                    <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="get_ftp" && $ftp_id>0){ echo "active"; } ?> " id="get_ftp_file">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-control" style="float: right;">
                    				<div class="btn-group dropdown">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right"> <!--style="">-->
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?tab=open_ftp&action=view_ftp"><i class="fa fa-eye"></i> View List</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title"><i class="fa fa-file"></i> Download Files (Only used with Internet Explorer)</h3>
                    		</div>

                            <div class="panel-body" onunload="TerminateDownload()" id="fetch_file_div" style="display: none;">

                            <object id="HTTPDL" style="height: 0px !important; width: 0px !important;" classid="CLSID:2DEA82A9-7FEF-4F68-8091-B800ECF54C9F" codeBase="./dsthttpdl.dll"></object>
                        	<!--<object style="display:none" id="SOME_ID" classid="clsid:SOME_CLASS_ID" codebase="./somePath.dll"></object>-->
                            <object id="MSXML3" style="DISPLAY: none" codeBase="http:msxml3.cab#version=8,00,7820,0" type="application/x-oleobject" data="data:application/x-oleobject;base64,EQ/Z9nOc0xGzLgDAT5kLtA==" classid="clsid:f5078f32-c551-11d3-89b9-0000f81fe221"></object>

                            <div id="Main">
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User ID <span class="text-red">*</span></label><br />
                                        <input type="text" class="form-control" name="UserID" id="UserID" value="<?php echo $return_ftp_host['user_name'];?>" disabled="true" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Destination Directory (local): </label><br />
                                        <input type="text" value="<?php echo $return_ftp_host['folder_location'];?>" id="DestDir" disabled="true" class="form-control"/></p>
                                        <input type="hidden" class="form-control" name="Password" id="Password" disabled="true" value="<?php echo $instance->decryptor($return_ftp_host['password']);?>"  />
                                        <input type="hidden" class="form-control" name="ftpType" id="ftpType" disabled="true" value="<?php echo $return_ftp_host['ftp_file_type'];?>"  />
                                    </div>
                                </div>
                            </div>
                            <!--HTTPs Download Guide Product Guide-->
                            <div class="panel-footer">
                                <div class="selectwrap">
                                    <input type="button" value="Download Files" onclick="GetFileList()"/>
                                    <input type="button" value="Cancel Download" onclick="CancelDownload()"/>
                                    <!--<a href="<?php echo CURRENT_PAGE.'?tab=open_ftp&action=view_ftp';?>"><input type="button" name="cancel" value="Cancel" /></a>-->
                                    <a href="#upload_zip_import" data-toggle="modal"><input type="button" name="import_files" value="Import Files" /></a>
                                </div><br />
                           </div>
                           </div>
                           <div class="row">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="HTTPDL_result" rows="10" cols="50" wrap="soft" class="form-control"></textarea>
                                    </div>
                            </div>
                           </div>
                           <div class="row" style="display: none;">
                            <div class="col-md-12">
                                    <div id="FileList">
                                    </div>
                            </div>
                           </div>
                           </div>
                           <!--<div class="panel-footer">
                                <div class="selectwrap">
                                    <input type="button" value="Get FileList" onclick="GeFileList()"/>
                                    <a href="<?php echo CURRENT_PAGE.'?tab=open_ftp&action=view_ftp';?>"><input type="button" name="cancel" value="Cancel" /></a>
                                </div><br />
                           </div>-->

                        </div>
                    </div>
                    <!-- Modal for add files -->
                    	<div id="upload_zip_import" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    		<div class="modal-dialog">
                    		<div class="modal-content">
                    		<div class="modal-header" style="margin-bottom: 0px !important;">
                    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    			<h4 class="modal-title">Fetch Files</h4>
                    		</div>
                    		<div class="modal-body">
                             <div class="col-md-12">
                                <div id="msg_files">
                                </div>
                            </div>
                            <form id="form_import_files" name="form_import_files" style="padding: 10px;" method="post" onsubmit="return formsubmitfiles();" enctype="multipart/form-data">
    						<div class="inputpopup">
                                <!--<input type="file" class="form-control" name="file_attach" id="file_attach"/>-->
    							<input type="file" id="files" name="files[]" multiple="multiple" accept="zip/*" class="form-control"/>
    						</div>
    						<div class="inputpopup">
                                <input type="hidden" name="fetch_files" value="Fetch Files"  />
    							<button type="submit" class="btn btn-sm btn-warning" id="fetch_files" name="fetch_files" value="Fetch Files"><i class="fa fa-save"></i> Save</button>
    						</div>
                            </form>
                            </div><!-- End of Modal body -->
                    		</div><!-- End of Modal content -->
                    		</div><!-- End of Modal dialog -->
                    </div><!-- End of Modal -->
                </div>
            </div>
            </div>
        </div>

		</div>
    </div>
  </div>
<!-- Lightbox start -->
	<!-- Modal for Resolve Exceptions popup -->
	<div id="solve_exception_model" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Resolve Exceptions</h4>
		</div>
		<div class="modal-body">
        <div class="col-md-12">
            <div id="msg_exception">
            </div>
        </div>
        <br />
        <div class="col-md-12" > <!--deprecated 03/04/22 -- style="alignment-adjustment: center;"-->
            <form method="post" id="resolve_exception_form" name="resolve_exception_form" onsubmit="return exception_submit();">
                <div class="row"> <!--text-right"-->
                <div class="col-md-5">
                    <div class="inputpopup">
                        <label id="field_label" class="pull-right">Add Exception value</label>
                        <label id="active_label" for="active" class="pull-right" style="display: none;">Active</label>
                    </div>
                </div>
                <div class="col-md-4 pull-left float-left">
                    <div class="inputpopup pull-left float-left">
                        <input type="text" name="exception_value" class="default_value" id="exception_value" value="" style="display: block;"/>
                        <input type="text" name="exception_value_dis" class="default_value" id="exception_value_dis" value="" style="display: none;"/>
                        <input type="checkbox" class="checkbox" name="active_state" id="active_state" value="1" style="display: none;"/>
                        <select name="status" id="status" class="form-control" style="display: none;">
                            <option value="">Select Option</option>
                            <option value="1">Active</option>
                            <option value="0" disabled="true">Terminated</option>
                        </select>
                        <select name="sponsor" id="sponsor" class="form-control" style="display: none;">
                            <option value="">Select Sponsor</option>
                            <?php foreach($get_sponsor as $key=>$val){?>
                                <option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                            <?php } ?>
                        </select>
                        <select name="objectives" id="objectives" class="form-control" style="display: none;">
                            <option value="">Select Objective</option>
                            <?php foreach($get_objective as $key=>$val){?>
                                <option value="<?php echo $val['id'];?>" <?php echo $productObjectiveId==$val['id']?'selected':'' ;?> ><?php echo $val['option'];?></option>
                            <?php } ?>
                        </select>
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="exception_value_date" id="exception_value_date" class="form-control" value="" style="display: none;"/>
                                <input type="text" name="exception_value_date_display" id="exception_value_date_display" class="form-control" value="" style="display: none;"/>
                            </div>
                        </div>
                        <input type="text" name="cusip_number" id="cusip_number" value="" style="display: none;"/>
                        <input type="text" name="existing_cusip_number" id="existing_cusip_number" value="" style="display: none;"/>
                        <input type="text" name="existing_ticker_symbol" id="existing_ticker_symbol" value="" style="display: none;"/>
                        <input type="text" name="alpha_code" id="alpha_code" value="" style="display: none;"/>
                        <input type="text" name="social_security_number" id="social_security_number" value="" style="display: none;"/>
                        <input type="checkbox" class="checkbox" name="active" id="active" value="1" style="display: none;vertical-align:-2px;"/></label>
                    </div>
                </div>
                </div>
                <div class="row" style="display: none;" id="broker_terminated">
                <div class="col-md-5">
                    <div class="inputpopup">
                        <label id="broker_termination_label">Broker Termination Date</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="inputpopup">
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="broker_termination_date" id="broker_termination_date" class="form-control" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="row" style="display: none;" id="broker_termination_options_trades" data-exception-field="representative_number">
                    <div class="col-md-5">
                        <div class="inputpopup">
                            <label id="broker_termination_label" class="pull-right">Select Option</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="radio" class="radio" name="resolve_broker_terminated" id="hold_commission" style="display: inline;" value="1" onclick="reassign_broker_(this.value);" checked/>
                            <label> Hold commission</label><br />
                        <input type="radio" class="radio" name="resolve_broker_terminated" id="broker_active_trade" style="display: inline;" value="2" onclick="reassign_broker_(this.value);"/>
                            <label id="lbl_broker_active_trades"> Remove U5 Date</label><br />
                        <input type="radio" class="radio" name="resolve_broker_terminated" id="reassign_broker" style="display: inline;" value="3" onclick="reassign_broker_(this.value);"/>
                            <label id="lbl_reassign_broker_trades"> Reassign Trade to Another Broker</label><br />
                        <input type="radio" class="radio" name="resolve_broker_terminated" id="delete_record" style="display: inline;" value="4" onclick="reassign_broker_(this.value);"/>
                            <label> Skip/Remove Trade Exception</label><br />
                    </div>
                </div>
                <div class="row" style="display: none;" id="broker_termination_options_clients">
                    <div class="col-md-5">
                        <div class="inputpopup">
                            <label id="broker_termination_label" class="pull-right">Select Option</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="radio" class="radio" name="resolve_broker_terminated" id="broker_active" style="display: inline;" value="2" onclick="reassign_broker_(this.value);"/><label> Reactivate Broker</label><br />
                        <input type="radio" class="radio" name="resolve_broker_terminated" id="reassign_broker" style="display: inline;" value="3" onclick="reassign_broker_(this.value);"/><label> Reassign Client to Another Broker </label><br />
                        <input type="radio" class="radio" name="resolve_broker_terminated" id="delete_record" style="display: inline;" value="4" onclick="reassign_broker_(this.value);"/><label> Skip/Remove Exception</label><br />
                    </div>
                </div>
                <div class="row" id="assign_rep_to_broker" style="display: none;">
                    <div class="col-md-5">
                        <div class="inputpopup">
                            <label class="pull-right" id="label_assign_rep_to_broker">Select Broker: </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="inputpopup">
                            <select name="rep_for_broker" id="rep_for_broker" class="form-control">
                                <option value="">Select Broker</option>
                                <?php foreach($get_broker as $key=>$val){?>
                                <option value="<?php echo $val['id'];?>"><?php echo strtoupper($val['last_name']).((!empty($val['first_name']) AND !empty($val['last_name'])) ? ', ' : '').strtoupper($val['first_name']);?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="assign_cusip_to_product" style="display: none;">
                    <div class="col-md-12">
                        <div class="row" id="assign_cusip_to_product_row_cusip">
                            <div class="col-md-5">
                                <div class="inputpopup">
                                    <label class="pull-right" id="label_cusip_number">CUSIP # to Assign: </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="inputpopup">
                                    <input type="text" name="assign_cusip_to_product_text" id="assign_cusip_to_product_text" disabled="true"/>
                                    <input type="hidden" name="assign_cusip_number" id="assign_cusip_number"/>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="assign_cusip_to_product_row_product_category">
                            <div class="col-md-5">
                                <div class="inputpopup">
                                    <label class="pull-right">Product Category: </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="inputpopup">
                                    <select name="assign_cusip_product_category" id="assign_cusip_product_category" class="form-control" onchange="get_product(this.value);open_product_link(this.value);">
                                        <option value="0">Select Category</option>
                                        <?php foreach($get_product_category as $key=>$val){?>
                                        <option value="<?php echo $val['id'];?>" <?php echo (isset($existingDetailValues['product_category_id']) AND $val['id']==$existingDetailValues['product_category_id'])?"selected":"" ?>><?php echo $val['type'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="assign_cusip_to_product_row_product">
                            <div class="col-md-5">
                                <div class="inputpopup">
                                    <label class="pull-right">Product: </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="inputpopup">
                                    <select class="form-control" name="assign_cusip_product"  id="assign_cusip_product">
                                        <option value="0">Select Product</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="assign_client_to_account" style="display: none;">
                    <div class="col-md-5">
                        <div class="inputpopup">
                            <label class="pull-right" id="label_assign_to_existing_client">Assign to Existing Client</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="inputpopup">
                            <select name="acc_for_client" id="acc_for_client" class="form-control">
                                <option value="">Select Client</option>
                                <?php foreach($get_client as $key=>$val){ ?>
                                    <option value="<?php echo $val['id'];?>"><?php echo strtoupper($val['last_name']).((!empty($val['last_name']) AND !empty($val['first_name'])) ? ', ' : '').strtoupper($val['first_name']);?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="assign_objective_to_client" style="display: none;">
                    <div class="col-md-5">
                        <div class="inputpopup">
                            <label class="pull-right" id="label_assign_objective_to_client">Add Client Objective: </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="inputpopup">
                            <select name="objective_for_client" id="objective_for_client" class="form-control" style="display: none;">
                                <option value="">Select Objective</option>
                                <?php foreach($get_objective as $key=>$val){?>
                                    <option value="<?php echo $val['id'];?>" <?php echo $productObjectiveId==$val['id']?'selected':'' ;?> ><?php echo $val['option'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row skip_exception" id="row_skip_exception" style="display: none;">
                    <div class="col-md-5">
                        <div class="inputpopup">
                            <label class="pull-right" id="label_skip_exception" for="checkbox_skip_exception" style="margin-top: 6px;">Remove/Skip Exception </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="inputpopup">
                            <input type="checkbox" name="skip_exception" class="checkbox skip_exception" id="checkbox_skip_exception" value="1"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-5"></div>
                <div class="col-md-2">
                    <div class="inputpopup">
                        <input type="hidden" name="exception_data_id" id="exception_data_id" value=""/>
                        <input type="hidden" name="exception_field" id="exception_field" value=""/>
                        <input type="hidden" name="exception_file_id" id="exception_file_id" value=""/>
                        <input type="hidden" name="exception_file_type" id="exception_file_type" value=""/>
                        <input type="hidden" name="error_code_id" id="error_code_id" value=""/>
                        <input type="hidden" name="exception_record_id" id="exception_record_id" value=""/>
                        <input type="hidden" name="resolveAction" id="resolveAction" value=""/>
                        <input type="hidden" name="resolve_exception" id="resolve_exception" value="Resolve Exception" />
        	            <button type="submit" class="btn btn-sm btn-warning" name="resolve_exception" value="Resolve Exception"><i class="fa fa-save"></i> Save</button>
                        <!--Deprecated 03/04/22 -- style="alignment-adjustment: center !important;" -->
                    </div>
                </div>
                <div class="col-md-5" id="link_div">

                </div>
                </div>
            </form>
		</div>
        <br />
        </div><!-- End of Modal body -->
		</div><!-- End of Modal content -->
		</div><!-- End of Modal dialog -->
    </div>
<!-- End of Modal -->
<style>
#table-scroll {
  height:500px;
  overflow:auto;
  margin-top:20px;
}
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
        $('.form-go-action').on('onselect change', function(){
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
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [ 6,7 ] },
                { "bSearchable": false, "aTargets": [ 6,7 ] }
            ],
            "order": [<?php echo !empty($dataTableOrder) ? $dataTableOrder : '[0, "asc"], [1, "asc"]';?>]
        });
        $("div.toolbar").html('<a class="btn btn-sm btn-warning" href="<?php echo CURRENT_PAGE; ?>?action=open_ftp"> Fetch</a>'+
                    '<a class="btn btn-sm btn-default" href="<?php echo CURRENT_PAGE; ?>?action=process_all" style="display:inline;">Import All</a>');

        $('#data-table1').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar1">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 6,7 ] },
                        { "bSearchable": false, "aTargets": [ 6,7 ] }]
        });

        $('#data-table2').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar2">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 3 ] },
                        { "bSearchable": false, "aTargets": [ 3 ] }]
        });
        $("div.toolbar2").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?tab=open_ftp&action=add_ftp"><i class="fa fa-plus"></i> Add new FTP Site</a></li>'+
                            '<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-minus"></i> Back to List of Current Files Page</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');

       $('#data-table3').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        //"bSort" : false,
        "bAutoWidth": false,
        "dom": '<"toolbar3">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ -1 ] },
                        { "bSearchable": false, "aTargets": [ -1 ] }]
        });

        $("div.toolbar3").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-minus"></i> Back to List of Current Files Page</a></li>'+
                            '<li><a href="<?php echo SITE_URL.'report_exception_data.php?id='.$file_id; ?>" target="_blank"><i class="fa fa-plus"></i> Output to print</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');

       $('#data-table4').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        //"bSort" : false,
        "bAutoWidth": false,
        "dom": '<"toolbar4">frtip'
        });
        $("div.toolbar4").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-minus"></i> Back to List of Current Files Page</a></li>'+
                            '<li><a href="<?php echo SITE_URL.'report_processed_data.php?id='.$file_id; ?>" target="_blank"><i class="fa fa-plus"></i> Output to print</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');

       $('#data-table5').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        //"bSort" : false,
        "bAutoWidth": false,
        "dom": '<"toolbar5">frtip'
        });
        $("div.toolbar5").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?tab=archived_files"><i class="fa fa-minus"></i> Back to List of Archived Files Page</a></li>'+
                            '<li><a href="<?php echo SITE_URL.'report_view_import_data.php?id='.$file_id; ?>" target="_blank"><i class="fa fa-plus"></i> Output to print</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');

       $('#data-table6').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        //"bSort" : false,
        "bAutoWidth": false,
        "dom": '<"toolbar6">frtip'
        });
        $("div.toolbar6").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE.'?action=process_file&file_id='.$file_id; ?>"><i class="fa fa-refresh"></i> Process File</a></li>'+
                            '<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-minus"></i> Cancel</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
});
function get_product(category_id){
        category_id = document.getElementById("assign_cusip_product_category").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200)
            {
                document.getElementById("assign_cusip_product").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_get_product.php?product_category_id="+category_id, true);
        xmlhttp.send();
}
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
$('#demo-dp-range .input-daterange').datepicker({
    format: "mm/dd/yyyy",
    todayBtn: "linked",
    autoclose: true,
    todayHighlight: true
});
function reassign_broker_(value)
{
    $exceptionField = document.getElementById('broker_termination_options_trades').dataset.exceptionField;
    $("#assign_rep_to_broker").css('display','none');
    $("#assign_client_to_account").css('display','none');

    if(value==3)
    {
        if ($exceptionField == 'objectives'){
            $("#assign_client_to_account").css('display','block');
        } else {
            $("#assign_rep_to_broker").css('display','block');
        }
    }
}
function add_exception_value(exception_file_id,exception_file_type,temp_data_id,exception_field,rep_number,existing_field_value,error_code_id,exception_record_id,client_account_no)
{
    //--- For testing
    console.log(
        'exception_file_id:'+ typeof exception_file_id + ': ' + exception_file_id + 
        ',  exception_file_type:' + typeof exception_file_type + ': ' + exception_file_type +
        ',  temp_data_id:' + typeof temp_data_id + ': '  + temp_data_id +
        ',  exception_field:' + typeof exception_field + ': '  + exception_field +
        ',  rep_number:'+ typeof rep_number + ': '  + rep_number +
        ',  existing_field_value:'+ typeof existing_field_value + ': '  + existing_field_value +
        ',  error_code_id:'+ typeof error_code_id + ': '  + error_code_id +
        ',  exception_record_id:'+ typeof exception_record_id+ ': '  + exception_record_id +
        ',  client_account_no:'+ typeof client_account_no + ': '  + client_account_no
    );
    
    // Some arguments are passed as strings, which messes up the "[array].includes(#value#)" function which is type sensitive 
    // BUT, "if" statements are NOT type sensitive -> "if (exception_file_id == '1')" & "if (exception_file_id == 1) isn't type sensitive, unlike "==="
    exception_file_id = Number(exception_file_id);
    exception_file_type = Number(exception_file_type);
    temp_data_id = Number(temp_data_id);
    error_code_id = Number(error_code_id);
    result = 0;
    // Trade/Commission records display more detailed elements and Resolve options than Clients & Securities
    tradeOrCommRecord = [2, 9].includes(exception_file_type);

    document.getElementById("field_label").innerHTML = 'Add Exception Value';
    document.getElementById("exception_data_id").value = temp_data_id;
    document.getElementById("exception_field").value = exception_field;
    document.getElementById("exception_file_id").value = exception_file_id;
    document.getElementById("exception_file_type").value = exception_file_type;
    document.getElementById("error_code_id").value = error_code_id;
    document.getElementById("exception_record_id").value = exception_record_id;
    document.getElementById("exception_value").value = '';
    document.getElementById("link_div").innerHTML = '';

    $("#exception_value").css('display','block');
    $("#exception_value_date").css('display','none');
    $("#exception_value_date_display").css('display','none');
    $("#exception_value_dis").css('display','none');
    $("#active_state").css('display','none');
    $("#active").css('display','none');
    $("#row_skip_exception").css('display','none');
    $("#status").css('display','none');
    $("#social_security_number").css('display','none');
    $("#sponsor").css('display','none');
    $("#objectives").css('display','none');
    $("#cusip_number").css('display','none');
    $("#alpha_code").css('display','none');
    $("#existing_ticker_symbol").css('display','none');
    $("#existing_cusip_number").css('display','none');
    $("#assign_client_to_account").css('display','none');
    $("#assign_rep_to_broker").css('display','none');
    $("#assign_objective_to_client").css('display','none');
    $("#assign_cusip_to_product").css('display','none');
    $("#broker_termination_options_clients").css('display','none');
    $("#broker_termination_options_trades").css('display','none');

    if (exception_file_type == 3 && error_code_id == 13){
        // SFR File - Missing/Invalid Data
        // 03/05/22 Not needed with file_type and error_code_id criteria --- && ['major_security_type', 'cusip_number', 'fund_name'].includes(exception_field)
        const existingDetailValues = <?php echo isset($existingDetailValues) ? json_encode($existingDetailValues) : json_encode(['fund_name'=>'', 'cusip_number'=>'', 'product_category_id'=>'0']); ?>;
        const parentRow = '#assign_cusip_to_product';

        $(parentRow).css("display","block");
        $(parentRow + " #label_cusip_number").html("Cusip #");
        $(parentRow + "_row_product").css("display","none");

        $("#field_label").html("Fund Name");
        $("#field_label").css("display","block");
        $("#exception_value").css("display","block");
        $("#exception_value").val(existingDetailValues['fund_name']);
        $(parentRow + " #cusip_number").val(existingDetailValues['cusip_number']);
        $(parentRow + " #cusip_number").prop("disabled", false);

        result += 1;
    } else if (error_code_id == 17){
        // Product Type Not Found
        document.getElementById("field_label").innerHTML = 'Security Type';
        $("#assign_cusip_to_product").css("display","block");
        $(".row #assign_cusip_to_product_row_cusip").css("display","none");
        $("#assign_cusip_to_product_row_product").css("display","none");

        document.getElementById("exception_value").value = existing_field_value;
        document.getElementById("exception_value_dis").value = existing_field_value;
        $("#exception_value").prop( "disabled", true );
        $("#exception_value_dis").prop( "disabled", true );
        $("#exception_value").css('display','none');
        $("#exception_value_dis").css('display','block');

        result += 1;
    } else if(exception_field == 'u5'){
        document.getElementById("field_label").innerHTML = 'Broker Termination Date:';
        $("#exception_value_date_display").css('display','block');
        
        if(exception_file_type == 1){
            $("#broker_termination_options_clients").css('display','block');
            document.getElementById("label_assign_rep_to_broker").innerHTML = "Assign Broker to Client";
            document.getElementById("broker_active").checked = true;
        } else if(tradeOrCommRecord){
            $("#broker_termination_options_trades").css('display','block');
            document.getElementById("broker_termination_options_trades").dataset.exceptionField = "u5";
            document.getElementById("lbl_broker_active_trades").innerHTML = 'Remove U5 Date/Activate Broker';
            document.getElementById("hold_commission").checked = true;
        }
        document.getElementById("exception_value_date").value = existing_field_value;
        document.getElementById("exception_value_date_display").value = existing_field_value;
        $("#exception_value_date_display").prop('disabled','true');
        $("#exception_value").css('display','none');

        result += 1;
    } else if(exception_field == 'representative_number'){
        document.getElementById("field_label").innerHTML = (error_code_id == 13 ? 'Missing Field' : 'Alias # Not Found');
        document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'manage_broker.php?action=add_new&rep_no=';?>'+rep_number+'<?php echo '&file_id='; ?>'+exception_file_id+'&file_type='+exception_file_type+'<?php echo '&exception_data_id='; ?>'+temp_data_id+'<?php echo '&exception_record_id='; ?>'+exception_record_id+'" style="display: block; float: right;" id="add_broker_for_rep">Add New Broker</a>';
        document.getElementById("exception_value").value = (error_code_id == 13 ? '<Broker Alias/Fund #>' : rep_number);
        $("#assign_rep_to_broker").css('display','block');
        $("#resolveAction").val('2');
        
        if (error_code_id == 1){
            document.getElementById("label_assign_rep_to_broker").innerHTML = 'Assign Alias to Broker';
        } else if (exception_file_type == 1 && error_code_id == 13) {
            document.getElementById("label_assign_rep_to_broker").innerHTML = 'Assign Client to Broker';
        } else if (tradeOrCommRecord && error_code_id == 13) {
            document.getElementById("label_assign_rep_to_broker").innerHTML = 'Assign Trade to Broker';
        } else {
            document.getElementById("label_assign_rep_to_broker").innerHTML = 'Assign to Broker';
        }

        result += 1;
    } else if(exception_field == 'status'){
        document.getElementById("field_label").innerHTML = 'Product Terminated:';
        $("#status").css('display','block');
        $("#exception_value").css('display','none');

        result += 1;
    } else if(exception_field == 'mutual_fund_customer_account_number'){
        document.getElementById("field_label").innerHTML = 'Account# to Add';
        document.getElementById("exception_value").value = '';

        result += 1;
    } else if(exception_field == 'customer_account_number'){
        document.getElementById("field_label").innerHTML = (error_code_id == 13 ? 'Missing Field' : 'Account # to Add');
        document.getElementById("exception_value").value = (error_code_id == 13 ? '<Customer Account #>' : existing_field_value);
        document.getElementById("exception_value_dis").value = (error_code_id == 13 ? '<Customer Account #>' : existing_field_value);
        document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'client_maintenance.php?action=add_new&account_no=';?>'+existing_field_value+'&file_id='+exception_file_id+'&file_type='+exception_file_type+'&exception_data_id='+temp_data_id+'&exception_record_id='+exception_record_id+'" style="display: block; float: right;" id="add_client_for_account">Add New Client</a>';
        $("#exception_value_dis").prop( "disabled", true );
        $("#assign_client_to_account").css('display','block');
        populate_assign_to_client(error_code_id, existing_field_value, exception_record_id);
        $("#exception_value").css('display','none');
        $("#exception_value_dis").css('display','block');
        if (error_code_id == 13){
            document.getElementById("label_assign_to_existing_client").innerHTML = 'Assign Client to Trade';
        }

        result += 1;
    } else if(exception_field == 'active_check'){
        document.getElementById("field_label").innerHTML = 'Licence Category / State';
        // $("#active_state").css('display','block');
        $("#exception_value").css('display','block');
        $("#broker_termination_options_trades").css('display','block');
        document.getElementById("exception_value").value = existing_field_value;
        document.getElementById("exception_value_dis").value = existing_field_value;
        document.getElementById("broker_termination_options_trades").dataset.exceptionField = "active_check";
        document.getElementById("hold_commission").checked = true;
        document.getElementById("lbl_broker_active_trades").innerHTML = 'Enter/Activate Broker Licence';
        document.getElementById("lbl_reassign_broker_trades").innerHTML = 'Reassign Trade to Another Broker';
        $("#exception_value").prop( "disabled", true );
        $("#exception_value_dis").prop( "disabled", true );
        $("#exception_value").css('display','none');
        $("#exception_value_dis").css('display','block');

        result += 1;
    } else if(exception_field == 'registration_line1'){
        document.getElementById("field_label").innerHTML = 'Enter Client Name:';
        document.getElementById("exception_value").value = existing_field_value;

        result += 1;
    } else if(exception_field == 'social_security_number'){
        document.getElementById("exception_value_dis").value = (error_code_id == 13 ? '<Customer Account #>' : '***-**-' + existing_field_value.slice(-4));

        if (exception_file_type == 1 && [13, 19].includes(error_code_id)){
            document.getElementById("field_label").innerHTML = (error_code_id == 13 ? 'Missing Field' : 'Social Security #');
            // Test 3/18/22 - Send $clientAccountNo instead of 'existing_field_value
            document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'client_maintenance.php?action=add_new&account_no=';?>'+client_account_no+'&file_id='+exception_file_id+'&exception_data_id='+'&file_type='+exception_file_type+'&exception_data_id='+temp_data_id+'&exception_record_id='+exception_record_id+'&file_type='+exception_file_type+'" style="display: block; float: right;" id="add_client_for_account">Add New Client</a>';
            $("#exception_value_dis").css('display','block');
            $("#exception_value_dis").val((error_code_id == 13) ? '<Social Security Number>' : '***-**-' + existing_field_value.slice(-4));
            $("#exception_value_dis").prop( "disabled", true );
            $("#exception_value").css('display','none');
            // Client dropdown and/or "Skip/Delete Exception Record" - 3/13/22 
            populate_assign_to_client(error_code_id, existing_field_value, exception_record_id);
            $("#assign_client_to_account").css('display','block');
            $("#row_skip_exception").css('display','block');
        } else {
            document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'client_maintenance.php?action=add_new&account_no=';?>'+client_account_no+'<?php echo '&file_id='; ?>'+exception_file_id+'&file_type'+exception_file_id+'&exception_data_id='+temp_data_id+'&exception_record_id='+exception_record_id+'&file_type='+exception_file_type+'" style="display: block; float: right;" id="add_client_for_account">Add New Client</a>';
            $("#field_label").html('Change Social Security Number: ');
            $("#social_security_number").val(existing_field_value);
            $("#social_security_number").css('display','block');
            $("#exception_value").css('display','none');
        }

        result += 1;
    } else if(exception_field == 'active'){
        document.getElementById("field_label").innerHTML = 'Active Client:';
        $("#active").css('display','block');
        $("#exception_value").css('display','none');

        result += 1;
    } else if(exception_field == 'objectives'){
        // Use #broker_termination_options_trades row
        $("#objectives").css('display','none');

        document.getElementById("field_label").innerHTML = 'Product Objective';
        $("#field_label").css('display','block');
        $("#exception_value").css('display','none');
        $("#exception_value").prop( "disabled", true );
        $("#exception_value_dis").css('display','block');
        $("#exception_value_dis").prop( "disabled", true );
        document.getElementById("exception_value").value = existing_field_value;
        document.getElementById("exception_value_dis").value = existing_field_value;

        /* Show Hold/Assign/Reassign/Delete radio buttons */
        $("#broker_termination_options_trades").css('display','block');
        document.getElementById("broker_termination_options_trades").dataset.exceptionField = "objectives";
        document.getElementById("lbl_broker_active_trades").innerHTML = 'Add Product Objective to Client';
        document.getElementById("lbl_reassign_broker_trades").innerHTML = 'Reassign Trade to Another Client';
        document.getElementById("hold_commission").checked = true;

        result += 1;
    } else if (exception_field == 'sponsor'){
        document.getElementById("field_label").innerHTML = 'Assign Existing Sponsor';
        $("#sponsor").css('display','block');
        $("#exception_value").css('display','none');
        document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'manage_sponsor.php?action=add_sponsor';?>&file_id='+exception_file_id+'&exception_data_id='+temp_data_id+'" style="display: block; float: right;" id="add_sponsor">Add New Sponsor</a>';

        result += 1;
    } else if(exception_field == 'cusip_number' && error_code_id == 13){
        document.getElementById("field_label").innerHTML = 'Enter Missing CUSIP #';
        $("#cusip_number").css('display','block');
        $("#exception_value").css('display','none');

        result += 1;
    } else if(exception_field == 'cusip_number' && error_code_id == 11){
        document.getElementById("resolveAction").value = "2";
        
        if(exception_file_type == 3) {
            document.getElementById("field_label").innerHTML = 'Change Cusip Number:';
            $("#existing_cusip_number").css('display','block');
            document.getElementById("existing_cusip_number").value = existing_field_value;
            $("#exception_value").css('display','none');
        } else {
            document.getElementById("field_label").innerHTML = 'Assign CUSIP to Product';
            $("#assign_cusip_to_product").css('display','block');
            $("#exception_value").css('display','none');
            $("#field_label").css('display','block');
            document.getElementById("assign_cusip_number").value = existing_field_value;
            document.getElementById("assign_cusip_to_product_text").value = existing_field_value;
            document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'product_cate.php?action=add_new&file_id=';?>'+exception_file_id+'<?php echo '&cusip_number='; ?>'+existing_field_value+'<?php echo '&exception_data_id='; ?>'+temp_data_id+'<?php echo '&exception_record_id='; ?>'+exception_record_id+'" style="display: block; float: right;" id="add_product_for_cusip">Add New Product</a>';
        }

        result += 1;
    } else if(exception_field == 'ticker_symbol'){
        document.getElementById("field_label").innerHTML = 'Change Ticker Symbol:';
        $("#existing_ticker_symbol").css('display','block');
        document.getElementById("existing_ticker_symbol").value = existing_field_value;
        $("#exception_value").css('display','none');

        result += 1;
    } else if(exception_field == 'alpha_code'){
        document.getElementById("field_label").innerHTML = 'Enter Client Name';
        document.getElementById("alpha_code").value = existing_field_value;
        document.getElementById("alpha_code").style.display = 'block';
        document.getElementById("exception_value").style.display = 'none';
        
        // Commission record - add Assign Client dropdown
        if (tradeOrCommRecord){
            document.getElementById("alpha_code").readOnly = 'true';
            document.getElementById("field_label").innerHTML = 'Account # to Add';
            document.getElementById("active_label").innerHTML = 'File Client Name';
            document.getElementById("active_label").style.display = 'inline-block';
            document.getElementById("active_label").style.paddingTop = '11px';
            document.getElementById("exception_value_dis").value = client_account_no;
            document.getElementById("exception_value_dis").readOnly = 'true';
            document.getElementById("exception_value_dis").style.display = 'block';
            document.getElementById("assign_client_to_account").style.display = 'block';
            document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'client_maintenance.php?action=add_new&account_no=';?>'+client_account_no+'&file_id='+exception_file_id+'&file_type='+exception_file_type+'&exception_data_id='+temp_data_id+'&exception_record_id='+exception_record_id+'" style="display: block; float: right;" id="add_client_for_account">Add New Client</a>';
            populate_assign_to_client(error_code_id, existing_field_value, exception_record_id);
        } else {
            $a = 0;
        }

        result += 1;
    }

    return result
}
function populate_assign_to_client(error_code_id, existing_field_value, exception_record_id){
    var clientDropdown = $("#acc_for_client");
    clientDropdown.empty();

    <?php foreach ($get_client AS $key=>$val){ ?>
        // 19=SSN Already Exists - filter for only matching SSN's
        if((error_code_id != 19 && error_code_id != 24) 
            || (error_code_id == 19 && existing_field_value.replace('-','') == '<?php echo str_replace('-','',$val['client_ssn']); ?>')
            || (error_code_id == 24 && (existing_field_value.includes("<?php echo empty($val['last_name'])?'@@@@@@@@@':$val['last_name'];?>") || existing_field_value.includes("<?php echo empty($val['first_name'])?'@@@@@@@@@':$val['first_name'];?>")))
        ) {
            clientDropdown.append(
                $('<option></option>')
                    .val("<?php echo $val['id'];?>")
                    .html("<?php echo strtoupper($val['last_name']).((!empty($val['last_name']) AND !empty($val['first_name'])) ? ', ' : '').strtoupper($val['first_name']);?>")
            );  
        }
    <?php } ?>

    if (clientDropdown.find("option").length > 1) {
        clientDropdown.prepend(
            $('<option></option>')
                .val("0")
                .html("Select Client")
                .attr("selected", "selected")
        );  
    }
}

function exception_submit()
{
   $('#msg_exception').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

   var url = "import.php"; // the script where you handle the form input.
   //alert($("#resolve_exception_form").serialize());
   $.ajax({
      type: "POST",
      url: url,
      data: $("#resolve_exception_form").serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){
               window.location.href = "import.php?tab=review_files&id="+<?php echo $id;?>+"&file_type="+<?php echo $file_type; ?>;//get_client_notes();
          }
          else{
               $('#msg_exception').html('<div class="alert alert-danger">'+data+'</div>');
          }

      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
           $('#msg_exception').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
      }

   });

   //e.preventDefault(); // avoid to execute the actual submit of the form.
   return false;

}
function open_product_link(category_id)
{
    cusip_number = document.getElementById("assign_cusip_number").value;
    exception_file_id = document.getElementById("exception_file_id").value;
    temp_data_id = document.getElementById("exception_data_id").value;

    if(category_id>0)
    {
        document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'product_cate.php?action=add_product&category=';?>'+category_id+'&cusip_number='+cusip_number+'&file_id='+exception_file_id+'&exception_data_id='+temp_data_id+'" style="display: block; float: right;" id="add_product_for_cusip">Add New Product</a>';
    }
    else
    {
       document.getElementById("link_div").innerHTML = '<a href="<?php echo SITE_URL.'product_cate.php?action=add_product&category=';?>'+category_id+'&cusip_number='+cusip_number+'" style="display: none; float: right;" id="add_product_for_cusip">Add New Product</a>';
    }
}
function check_u5_date(broker)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = this.responseText;
            if(data != '0'){
               $("#broker_terminated").css('display','block');
               document.getElementById("broker_termination_date").value = data;
            }
            else
            {
               $("#broker_terminated").css('display','none');
            }
        }
    };
    xmlhttp.open("GET", "import.php?broker_termination="+broker, true);
    xmlhttp.send();
}
</script>
<script type="text/javascript">
    var isIE = /*@cc_on!@*/false || !!document.documentMode;
    /*if(isIE == true)
    {*/
        $('#fetch_file_div').css('display','block');
    //}
</script>
<script type="text/javascript">
var waitingDialog = waitingDialog || (function ($) {
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
		show: function (message, options) {
			// Assigning defaults
			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Saving...';
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
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
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
function formsubmitfiles()
{
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
        success: function(data){

           if(data=='1'){
                window.location.href = "import.php";

                /*$('#msgnotes').html('<div class="alert alert-success">Thank you.</div>');
                $('#add_notes')[0].reset();
                setTimeout(function(){
    				$('#myModalShare').modal('hide');
    			}, 2000);*/

           }
           else{
                $('#msg_files').html('<div class="alert alert-danger">'+data+'</div>');
           }

       },
       error: function(XMLHttpRequest, textStatus, errorThrown) {
            $('#msg_files').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
       }

    });

    //e.preventDefault(); // avoid to execute the actual submit of the form.
    return false;

}
function changeClass() {
    var pullRights = document.getElementsByClassName('pull-right');
    var width = (window.innerHeight > 0) ? window.innerHeight : screen.Height;
    // console.log(width);
    if(width <= 992) {
        while(pullRights.length > 0) {
            pullRights[1].className ='';
        }
    } else {
        while(pullRights.length > 0) {
            pullRights[0].className = "pull-right";
        }
    }
}
</script>