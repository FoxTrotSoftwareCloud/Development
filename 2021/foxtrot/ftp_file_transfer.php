<?php

/**
 * Transfer (Import) Files Server to Server using PHP FTP
 * @link https://shellcreeper.com/?p=1249
 */
 
/* Source File Name and Path */
$remote_file = '/public/CloudFox/imported_files/AMERI02.N3047.ZIP';
 
/* FTP Account */
$ftp_host = 'ftp.foxtrotsoftware.com'; /* host */
$ftp_user_name = 'krunal.foxtrotsoftware.com'; /* username */
$ftp_user_pass = 'We3b2!12'; /* password */
 
 
/* New file name and path for this file */
$local_file = 'E:\idc_file_foxtrot\check.N3047.ZIP';
 
/* Connect using basic FTP */
$connect_it = ftp_connect( $ftp_host );
 
/* Login to FTP */
$login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
 
/* Download $remote_file and save to $local_file */
if ( ftp_get( $connect_it, $local_file, $remote_file, FTP_BINARY ) ) {
    echo "WOOT! Successfully written to $local_file\n";
}
else {
    echo "Doh! There was a problem\n";
}
 
/* Close the connection */
ftp_close( $connect_it );


?>