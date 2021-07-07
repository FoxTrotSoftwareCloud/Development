<?php
// FTP access parameters
$host = 'ftp.foxtrotsoftware.com';
$usr = 'krunal.foxtrotsoftware.com';
$pwd = 'We3b2!12';
 
// file to move
$local_file = 'E:\foxtrot_idc_file\AMERI17.N3051.ZIP';
$ftp_path = '/public/CloudFox/imported_files/example.ZIP';
 
// connect to FTP server (port 21)
$conn_id = ftp_connect($host, 21) or die ("Cannot connect to host");
 
// send access parameters
ftp_login($conn_id, $usr, $pwd) or die("Cannot login");
 
// turn on passive mode transfers (some servers need this)
// ftp_pasv ($conn_id, true);
 
// perform file upload
$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_ASCII);
 
// check upload status:
print (!$upload) ? 'Cannot upload' : 'Upload complete';
print "\n";
 
/*
** Chmod the file (just as example)
*/
 
// If you are using PHP4 then you need to use this code:
// (because the "ftp_chmod" command is just available in PHP5+)
if (!function_exists('ftp_chmod')) {
   function ftp_chmod($ftp_stream, $mode, $filename){
        return ftp_site($ftp_stream, sprintf('CHMOD %o %s', $mode, $filename));
   }
}
 
// try to chmod the new file to 666 (writeable)
if (ftp_chmod($conn_id, 0666, $ftp_path) !== false) {
    print $ftp_path . " chmoded successfully to 666\n";
} else {
    print "could not chmod $file\n";
}
 
// close the FTP stream
ftp_close($conn_id);

?>