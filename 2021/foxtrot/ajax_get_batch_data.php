<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
// Retrieve the variable from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

$instance = new batches();

$data = $instance->get_batch_detail(htmlspecialchars($id));

// Send the data as the response
header('Content-Type: application/json');
echo json_encode($data);
?>
