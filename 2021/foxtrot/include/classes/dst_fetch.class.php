<?php
// require_once("./../include/config.php");    
// require_once('./../include/db.class.php');

class DSTFetch {
    //------------------------------------------------------------------------------------------------------------
    //-- This is my initial foray into using cURL for the DST File Fetch implmentation - 01/08/23 li --
    //------------------------------------------------------------------------------------------------------------
    public $fileList = [];
    public $fetchStatus = "";
    //-- Initialize cURL PROPERTIES
    private $userId = "";
    private $password = "";
    private $localFolder = "";
    //-- DST Creds
    private $client = "";
    private $params = "";
    private $headerDlua = "";
    private $headerRequester = "";
    private $url = "";
    private $txid = "";
    private $dataDir = "";
    
    function __construct() {
        // $this->setUserId(''); // XML/LA User
        $this->setUserId('fmtsthtp'); // Test User   
            
        // $this->setPassword(''); // XML/LA Password
        $this->setPassword('testing#'); // Test User Password    
        // $this->setPassword('testing#"');  // Bad Password
        
        //-- Path format:
        $this->setLocalFolder('c:/1TestDSTRetrieve/Files/');    
        
        $this->setClient('415171403');    
        $this->setParams("?tx=RetrieveFile&cz=".$this->client);    
        $this->setUrl("https://filetransfer.financialtrans.com/tf/FANMail".$this->params);    
        $this->setHeaderDlua(base64_encode($this->userId.":".$this->password));
        $this->setHeaderRequester("FANMail_Retrieve");
        $this->setDataDir("c:/1TestDSTRetrieve/files/");
    }

    function get_file_list() {
        $this->fetchStatus = "Getting files...";
        $this->fileList = [];
        // Have to store info from the response header
        $headerSize = 0;
        
        //-- Start cURL Options
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('X-File-Requester: '.$this->headerRequester, 'X-Dlua: '.$this->headerDlua));
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HEADER, true);

        $output = curl_exec($handle);
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        curl_close($handle);
        
        // Transaction Index (X-Tidx) for the download files routine - needs that header value
        if (strpos($output, "X-Tidx: ")!==false){
            // Did this steps to not confuse myself and future generations
            $txid = substr($output, strpos($output, "X-Tidx: ")+strlen("X-Tidx: "));
            $this->setTxid(trim(substr($txid, 0, strpos($txid,"\n"))));
        }
        
        if (strpos($output,'xml')!==false){
            $this->fileList = $this->xml_to_file_list(substr($output, $headerSize));
            $this->fetchStatus = "Get File List - PASS, Count: ".count($this->fileList);
        } else if (strpos($output,'html')!==false){
            $document = new DOMDocument();
            $document->loadHTML($output);
            
            if (!empty($document->getElementsByTagName('h1'))) {
                $this->fetchStatus = "Get File List - FAIL: ".$document->getElementsByTagName('h1')[0]->nodeValue;
            } else if (!empty($document->getElementsByTagName('title'))) {
                $this->fetchStatus = "Get File List - FAIL: ".$document->getElementsByTagName('title')[0]->nodeValue;
            }
        } else {
            // $fetchStatus = str_get_html($output);
            $this->fetchStatus = "Get File List - FAIL: Reason not specified";
        }
        return $this->fileList;
    }

    function xml_to_file_list (string $xmlString) {
        $i = 0;
        $fileList = simplexml_load_string($xmlString);
        $return = [];
        
        foreach ($fileList->FileList->File as $file){
            $i++;
            foreach ($file->attributes() as $name=>$value){
                $return[] = (string)$value; 
            }
        }
        
        return $return;
    }
    
    function download_file(array $downloadList=[]) {
        $this->fetchStatus = "Downloading files...";
        $downloadCount = count($downloadList);
        $return = [];
        $success = $file = $output = 0;
        
        if ($downloadCount>0 && strtolower(substr($downloadList[0],-3))=='zip') {
            //-- Start cURL Operations
            $handle = curl_init();
            // curl_setopt($handle, CURLOPT_HEADER, true);
            curl_setopt($handle, CURLOPT_HTTPHEADER, array('X-File-Requester: '.$this->headerRequester, 'X-Dlua: '.$this->headerDlua));
            // curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($handle, CURLOPT_FAILONERROR, true);
            // curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
            
            foreach ($downloadList as $index=>$fileName){
                $this->fetchStatus = "Downloading files..."."File ".($index+1)." of $downloadCount: $fileName";
                // Make sure the file doesn't already exist
                $output = 0;
                $dupFile = [];
                $dupFile = array_filter(scandir($this->dataDir), 
                    function($dirFile) use($fileName){
                        return (strcasecmp($dirFile, $fileName)===0 OR strcasecmp($fileName, str_replace('.TXT', '.ZIP', $dirFile))===0);
                    }
                );
                
                if ($dupFile){
                    $return[] = ["name"=>$fileName, "status"=>"already exists"];
                } else {
                    $file = fopen(trim($this->dataDir).$fileName, 'w+');
                    $event = "&tidx=".$this->txid."&event=RetrieveFile&file=".$fileName;
                    curl_setopt($handle, CURLOPT_FILE, $file);
                    curl_setopt($handle, CURLOPT_URL, $this->url.$event);
                    $output = curl_exec($handle);
                    fclose($file);
                    
                    if ($output){
                        $success++;
                        $return[] = ["name"=>$fileName, "status"=>"downloaded"];
                        
                        // Unzip the file in the same directory, and delete the ZIP file
                        $output = $this->extract_file([$return[count($return)-1]]);
                        if (count($output)){
                            $return[count($return)-1]['status'] .= '/'.$output[0]['status'];
                        }
                        // Delete the file from the DST Server
                        $output = $this->delete_file([$return[count($return)-1]]);
                        if (count($output)){
                            $return[count($return)-1]['status'] .= '/'.$output[0]['status'];
                        }
                        
                    } else {
                        $return[] = ["name"=>$fileName, "status"=>"download failed"];
                    }
                }
            }
            curl_close($handle);
            $this->fetchStatus = "Downloading files: Complete: ".$success." of ".($index+1)." files successfully downloaded";
        } else {
            $this->fetchStatus = "Downloading files: No files specified. Procedure cancelled.";
        }
        
        return $return;
    }

    function delete_file(array $deleteList=[]) {
        $this->fetchStatus = "Deleting files...";
        $deleteCount = count($deleteList);
        $return = [];
        $success = $i = 0;
        $fileName = strtoupper(isset($deleteList[$i]['name']) ? $deleteList[$i]['name'] : $deleteList[$i]);
        
        if ($deleteCount>0 && substr($fileName,-3)=='ZIP') {
            //-- Start cURL Operations
            $handle = curl_init();
            curl_setopt($handle, CURLOPT_HEADER, true);
            curl_setopt($handle, CURLOPT_HTTPHEADER, array('X-File-Requester: '.$this->headerRequester, 'X-Dlua: '.$this->headerDlua));
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_FAILONERROR, true);
            
            for ($i=0; $i < count($deleteList); $i++){
                $fileName = strtoupper(isset($deleteList[$i]['name']) ? $deleteList[$i]['name'] : $deleteList[$i]);
                $this->fetchStatus = "Deleteing files..."."File ".($i+1)." of $deleteCount: $fileName";
                // Make sure the file doesn't already exist
                $event = "&tidx=".$this->txid."&event=DeleteFile&file=".$fileName;
                curl_setopt($handle, CURLOPT_URL, $this->url.$event);
                $output = curl_exec($handle);
                
                // Check in the response header for good\bad delete
                $errorNum = $errorMsg = $temp = "";
                if (strpos($output, "X-Error: ")!==false){
                    // Did this extra step to not confuse myself and future generations
                    $temp = substr($output, strpos($output, "X-Error: ")+strlen("X-Error: "));
                    $errorNum = trim(substr($temp, 0, strpos($temp,"\n")));
                }
                if (strpos($output, "X-Error-Message: ")!==false){
                    // Did this extra step to not confuse myself and future generations
                    $temp = substr($output, strpos($output, "X-Error-Message: ")+strlen("X-Error-Message: "));
                    $errorMsg = trim(substr($temp, 0, strpos($temp,"\n")));
                }
                
                if ($errorMsg === ""){
                    $success++;
                    $return[] = ["name"=>$fileName, "status"=>"deleted"];
                    if (isset($deleteList[$i]['status'])){
                        $deleteList[$i]['status'] = trim($deleteList[$i]['status'])."/deleted";
                    }
                } else {
                    $return[] = ["name"=>$fileName, "status"=>"deletion failed-".$errorNum."-".$errorMsg];
                    if (isset($deleteList[$i]['status'])){
                        $deleteList[$i]['status'] = trim($deleteList[$i]['status'])."/deletion failed-".$errorNum."-".$errorMsg;
                    }
                }
            }
            curl_close($handle);
            $this->fetchStatus = "Downloading files: Complete: ".$success." of ".($i+1)." files successfully downloaded";
        } else {
            $this->fetchStatus = "Downloading files: No files specified. Procedure cancelled.";
        }
        
        return $return;
    }

    function extract_file(array $extractList=[]) {
        $this->fetchStatus = "Extracting files...";
        $extractCount = count($extractList);
        //-- TEST DELETE ME - reinstate db() and any references when moved into the Production/Test environment
        // $instance_db = new db();
        $return = [];
        $success = $i = 0;
        $fileName = strtoupper(isset($extractList[$i]['name']) ? $extractList[$i]['name'] : $extractList[$i]);
        
        if ($extractCount>0 && strtolower(substr($fileName,-3))=='zip') {
            $unzip = new ZipArchive;
            $dir = $this->dataDir;

            for ($i=0; $i < count($extractList); $i++){
                $fileName = strtoupper(isset($extractList[$i]['name']) ? $extractList[$i]['name'] : $extractList[$i]);
                $this->fetchStatus = "extracting files..."."File ".($i+1)." of $extractCount: $fileName";
                $output = $unlinked = 0;
                //-- Actual Extraction
                if ($unzip->open($dir.$fileName)) {
                    $output = $unzip->extractTo($dir);
                    $unzip->close();
                } else {
                    $return[] = ["name"=>$fileName, "status"=>"extract failed - open() failed"];
                }
                //-- Update the file array
                if ($output){
                    $success++;
                    $return[] = ["name"=>$fileName, "status"=>"extracted"];
                    if (isset($extractList[$i]['status'])){
                        $extractList[$i]['status'] = trim($extractList[$i]['status'])."/extracted";
                    }
                } else if (count($return)==0 or $return[count($return)-1]!=$fileName){
                    $return[] = ["name"=>$fileName, "status"=>"extraction failed"];
                    if (isset($extractList[$i]['status'])){
                        $extractList[$i]['status'] = trim($extractList[$i]['status'])."/deletion failed";
                    }
                }
                //-- Delete the ZIP file
                if ($output){
                    $unlinked = unlink($dir.$fileName);
                    
                    if ($unlinked){
                        $return[count($return)-1]['status'] .= "/local ZIP file deleted";
                        if (isset($extractList[$i]['status'])){
                            $extractList[$i]['status'] = trim($extractList[$i]['status'])."/local ZIP file deleted";
                        }
                    } else {
                        $return[count($return)-1]['status'] .= "/local ZIP file deletion failed";
                        if (isset($extractList[$i]['status'])){
                            $extractList[$i]['status'] = trim($extractList[$i]['status'])."/local ZIP file deletion failed";
                        }
                    }
                }
            }
            $this->fetchStatus = "Extracting files: Complete: ".$success." of ".($i+1)." files successfully extracted";
        } else {
            $this->fetchStatus = "Extracting files: No files specified. Procedure cancelled.";
        }
        
        return $return;
    }

    function setUserId (string $setUserId) { $this->userId = trim($setUserId); }
    function setPassword (string $setPassword) { $this->password = trim($setPassword); }
    function setLocalFolder (string $setLocalFolder) { $this->localFolder = trim($setLocalFolder); }
    function setClient (string $setClient) { $this->client = trim($setClient); }
    function setParams (string $setParams) { $this->params = trim($setParams); }
    function setUrl (string $setUrl) { $this->url = trim($setUrl); }
    function setTxid (string $setTxid) { $this->txid = trim($setTxid); }
    function setHeaderDlua (string $setHeaderDlua) { $this->headerDlua = trim($setHeaderDlua); }
    function setHeaderRequester (string $setHeaderRequester) { $this->headerRequester= trim($setHeaderRequester); }
    function setDataDir (string $setDataDir) { 
        $instance_db = new db();
        $this->dataDir= rtrim($instance_db->re_db_input(trim($setDataDir)), "/")."/"; 
    }
}
?>