<?php
/**
 * Transfer (Export) Files Server to Server using PHP FTP
 * @link https://shellcreeper.com/?p=1249
 */
 
/* Remote File Name and Path */
$remote_file = '/public/CloudFox/imported_files/test2.N3047.ZIP';
 
/* FTP Account */
$ftp_host = 'ftp.foxtrotsoftware.com'; /* host */
$ftp_user_name = 'krunal.foxtrotsoftware.com'; /* username */
$ftp_user_pass = 'We3b2!12'; /* password */
 
 
/* File and path to send to remote FTP server */
$local_file = 'E:\foxtrot_idc_file\AMERI17.N3051.ZIP';
 
/* Connect using basic FTP */
$connect_it = ftp_connect( $ftp_host );
 
/* Login to FTP */
$login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
 
/* Send $local_file to FTP */
if ( ftp_put( $connect_it, $remote_file, $local_file, FTP_BINARY ) ) {
    echo "WOOT! Successfully transfer $local_file\n";
}
else {
    echo "Doh! There was a problem\n";
}
 
/* Close the connection */
ftp_close( $connect_it );

?>