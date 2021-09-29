<?php 
error_reporting(0);
require_once("../include/config.php");
require_once(DIR_FS."islogin.php");
$userId = $_SESSION['user_id'];
$requestData = $_POST;
$userWidgetSettings = new user_widget_settings($userId);


if ($requestData['widget'] == 'daily-importing')
  $userWidgetSettings->dailyImporting = $requestData['status'];
if ($requestData['widget'] == 'commissions')
  $userWidgetSettings->commissions = $requestData['status'];
if ($requestData['widget'] == 'payroll')
  $userWidgetSettings->payroll = $requestData['status'];
if ($requestData['widget'] == 'compliance')
  $userWidgetSettings->compliance = $requestData['status'];
if ($requestData['widget'] == 'ytd-production')
  $userWidgetSettings->ytdProduction = $requestData['status'];

$userWidgetSettings->Save();
$response = array('code' => 200);
print json_encode($response);
?>