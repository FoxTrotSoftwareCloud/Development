<?php
// require_once("./../include/config.php");    
// require_once('./../include/db.class.php');

class DSTFetch
{
    //------------------------------------------------------------------------------------------------------------
    //-- This is my initial foray into using cURL for the DST File Fetch implmentation - 01/08/23 li --
    //------------------------------------------------------------------------------------------------------------
    public $fileList = [];
    public $fetchStatus = "";
    //-- DST Creds
    private $password = "";
    private $userId = "";
    private $localFolder = "";
    private $client = "";
    private $params = "";
    private $headerDlua = "";
    private $headerRequester = "";
    private $url = "";
    private $txid = "";
    private $dataDir = "";
    private $dimId = 0;
    private $testMode = 0;

    function __construct(int $dimId = 2, $testMode = 0)
    {
        $this->testMode = $testMode;

        $this->dimId = $dimId;
        $this->client = '415171403';
        $this->params = "?tx=RetrieveFile&cz=" . $this->client;
        $this->url = "https://filetransfer.financialtrans.com/tf/FANMail" . $this->params;
        $this->headerRequester = "FANMail_Retrieve";
        $this->get_credentials($dimId);
        // Create an error handler for bad creds and data directory - 01/10/23 li
        if (empty($this->userId) or empty($this->password) or empty($this->localFolder)) {
            $this->fetchStatus = "DST FETCH - Initiated: [EXCEPTION] User Name, Password and/or Local Folder not specified or invalid";
        } else {
            $this->fetchStatus = "DST FETCH: Initiated...";
        }
        $this->headerDlua = base64_encode($this->userId . ":" . $this->password);
    }

    private function get_credentials(int $dimId = 2)
    {
        global $dbins;
        $return = 0;
        $tableValues = [];

        $q = "SELECT name,dim_id,user_name,password,local_folder FROM " . DATA_INTERFACES . " WHERE dim_id=$dimId";
        $res = $dbins->re_db_query($q);
        $return = $dbins->re_db_num_rows($res);

        if ($return) {
            $tableValues = $dbins->re_db_fetch_array($res);
            $this->setUserId($tableValues['user_name']);
            $this->setPassword($dbins->decryptor($tableValues['password']));
            $this->setDataDir($tableValues['local_folder']);
            $this->setLocalFolder($tableValues['local_folder']);
        }

        return $return;
    }

    // Main function: Pull all the files from the DST server
    function fetch()
    {
        $return = $temp = [];
        $return = $this->get_file_list();

        if (count($return) > 0) {
            //--- Sort the files by file identifier, so the oldest files are downloaded first
            $temp = $return;
            foreach ($return as $index => $file) {
                $fileNum = substr($file['name'], 8, 5);
                $temp[$index]['filenum'] = $fileNum;
            }
            usort($temp, function ($item1, $item2) {
                return $item1['filenum'] <=> $item2['filenum'];
            });

            // TEST MODE - 01/10/23 Only fetch the first 5 of the list, otherwise load ALL the sorted files in $return array
            $end = $this->testMode ? 2 : count($return);

            $return = [];
            for ($i = 0; $i < $end; $i++) {
                $return[]['name'] = $temp[$i]['name'];
            }

            $return = $this->download_file($return);
            $return = $this->extract_file($return);

            if (!$this->testMode) {
                $return = $this->delete_file($return);
            }
        }

        return $return;
    }

    function get_file_list()
    {
        $this->fetchStatus .= "\r\nRetrieving File List...";
        $this->fileList = [];
        // Have to store info from the response header
        $headerSize = 0;

        //-- Start cURL Options
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('X-File-Requester: ' . $this->headerRequester, 'X-Dlua: ' . $this->headerDlua));
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HEADER, true);

        $output = curl_exec($handle);
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        curl_close($handle);

        // Transaction Index (X-Tidx) for the download files routine - needs that header value
        if (strpos($output, "X-Tidx: ") !== false) {
            // Did this steps to not confuse myself and future generations
            $txid = substr($output, strpos($output, "X-Tidx: ") + strlen("X-Tidx: "));
            $this->setTxid(trim(substr($txid, 0, strpos($txid, "\n"))));
        }

        if (strpos($output, 'xml') !== false) {
            $this->fileList = $this->xml_to_file_list(substr($output, $headerSize));
            $this->fetchStatus .= "\r\nGET File List - Successful, File Count: " . count($this->fileList);
        } else if (strpos($output, 'html') !== false) {
            $document = new DOMDocument();
            $document->loadHTML(substr($output, strpos($output, "<html>")));

            if (!empty($document->getElementsByTagName('h1'))) {
                $this->fetchStatus .= "\r\nGET File List - FAIL: " . $document->getElementsByTagName('h1')[0]->nodeValue;
            } else if (!empty($document->getElementsByTagName('title'))) {
                $this->fetchStatus .= "\r\nGet File List - FAIL: " . $document->getElementsByTagName('title')[0]->nodeValue;
            }
        } else {
            // $fetchStatus = str_get_html($output);
            $this->fetchStatus .= "\r\nGet File List - FAIL: Reason not specified";
        }
        return $this->fileList;
    }

    function xml_to_file_list(string $xmlString)
    {
        $i = 0;
        $fileList = simplexml_load_string($xmlString);
        $return = [];

        foreach ($fileList->FileList->File as $file) {
            $i++;
            foreach ($file->attributes() as $name => $value) {
                $return[] = ['name' => (string)$value, 'status' => ''];
            }
        }

        return $return;
    }

    function download_file(array $downloadList = [])
    {
        $this->fetchStatus .= "\r\nDownloading files...";
        $downloadCount = count($downloadList);
        $return = [];
        $success = $file = $output = $i = 0;
        $fileName = strtoupper(isset($downloadList[$i]['name']) ? $downloadList[$i]['name'] : $downloadList[$i]);

        if ($downloadCount > 0 && substr($fileName, -3) == 'ZIP') {
            //-- Start cURL Operations
            $handle = curl_init();
            curl_setopt($handle, CURLOPT_HTTPHEADER, array('X-File-Requester: ' . $this->headerRequester, 'X-Dlua: ' . $this->headerDlua));
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_FAILONERROR, true);

            foreach ($downloadList as $index => $iFile) {
                $fileName = strtoupper(isset($iFile['name']) ? $iFile['name'] : $iFile);
                $this->fetchStatus .= "\r\nDownloading " . "File " . ($index + 1) . " of $downloadCount: $fileName";
                // Make sure the file doesn't already exist
                $output = 0;
                $dupFile = [];
                $dupFile = array_filter(
                    scandir($this->dataDir),
                    function ($dirFile) use ($fileName) {
                        return (strcasecmp($dirFile, $fileName) === 0 or strcasecmp($fileName, str_replace('.TXT', '.ZIP', $dirFile)) === 0);
                    }
                );

                if ($dupFile) {
                    $return[] = ["name" => $fileName, "status" => "already exists"];
                } else {
                    $file = fopen(trim($this->dataDir) . $fileName, 'w+');
                    $event = "&tidx=" . $this->txid . "&event=RetrieveFile&file=" . $fileName;
                    curl_setopt($handle, CURLOPT_FILE, $file);
                    curl_setopt($handle, CURLOPT_URL, $this->url . $event);
                    $output = curl_exec($handle);
                    fclose($file);

                    if ($output) {
                        $success++;
                        $return[] = ["name" => $fileName, "status" => "downloaded"];
                    } else {
                        $return[] = ["name" => $fileName, "status" => "download failed"];
                    }
                }
            }
            curl_close($handle);
            $this->fetchStatus .= "\r\nDownloading files - Complete: " . $success . " of " . ($index + 1) . " files successfully downloaded";
        } else {
            $this->fetchStatus .= "\r\nDownloading files: No files specified. Procedure cancelled.";
        }

        return $return;
    }

    function extract_file(array $extractList = [])
    {
        $this->fetchStatus .= "\r\nExtracting files...";
        $extractCount = count($extractList);
        //-- TEST DELETE ME - reinstate db() and any references when moved into the Production/Test environment
        // $instance_db = new db();
        $return = [];
        $success = $i = 0;
        $fileName = strtoupper(isset($extractList[$i]['name']) ? $extractList[$i]['name'] : $extractList[$i]);
        $status = "";

        if ($extractCount > 0 && strtolower(substr($fileName, -3)) == 'zip') {
            $unzip = new ZipArchive;
            $dir = $this->dataDir;

            for ($i = 0; $i < count($extractList); $i++) {
                $fileName = strtoupper(isset($extractList[$i]['name']) ? $extractList[$i]['name'] : $extractList[$i]);
                $this->fetchStatus .= "\r\nExtracting File " . ($i + 1) . " of $extractCount: $fileName";
                $output = $unlinked = 0;
                //-- Actual Extraction
                if ($unzip->open($dir . $fileName)) {
                    $output = $unzip->extractTo($dir);
                    $unzip->close();
                } else {
                    $return[] = ["name" => $fileName, "status" => "extract failed - open() failed"];
                }
                //-- Update the file array
                if ($output) {
                    $success++;
                    $status = "extracted";

                    if (isset($extractList[$i]['status'])) {
                        $extractList[$i]['status'] = trim($extractList[$i]['status']) . "/extracted";
                        $status = $extractList[$i]['status'];
                    }
                    $return[] = ["name" => $fileName, "status" => $status];
                } else if (count($return) == 0 or $return[count($return) - 1] != $fileName) {
                    $status = "extraction failed";
                    if (isset($extractList[$i]['status'])) {
                        $extractList[$i]['status'] = trim($extractList[$i]['status']) . "/deletion failed";
                        $status = $extractList[$i]['status'];
                    }
                    $return[] = ["name" => $fileName, "status" => $status];
                }
                //-- Delete the ZIP file
                if ($output) {
                    $unlinked = unlink($dir . $fileName);
                    $status = "";

                    if ($unlinked) {
                        $return[count($return) - 1]['status'] .= "/local ZIP file deleted";
                        if (isset($extractList[$i]['status'])) {
                            $extractList[$i]['status'] = trim($extractList[$i]['status']) . "/local ZIP file deleted";
                        }
                    } else {
                        $return[count($return) - 1]['status'] .= "/local ZIP file deletion failed";
                        if (isset($extractList[$i]['status'])) {
                            $extractList[$i]['status'] = trim($extractList[$i]['status']) . "/local ZIP file deletion failed";
                        }
                    }
                }
            }
            $this->fetchStatus .= "\r\nExtracting files - Complete: " . $success . " of " . ($i) . " files successfully extracted";
        } else {
            $this->fetchStatus .= "\r\nExtracting files: No files specified. Procedure cancelled.";
        }

        return $return;
    }

    function delete_file(array $deleteList = [])
    {
        $this->fetchStatus .= "\r\nDeleting remote files...";
        $deleteCount = count($deleteList);
        $return = [];
        $success = $i = 0;
        $fileName = strtoupper(isset($deleteList[$i]['name']) ? $deleteList[$i]['name'] : $deleteList[$i]);
        $status = "";

        if ($deleteCount > 0 && substr($fileName, -3) == 'ZIP') {
            //-- Start cURL Operations
            $handle = curl_init();
            curl_setopt($handle, CURLOPT_HEADER, true);
            curl_setopt($handle, CURLOPT_HTTPHEADER, array('X-File-Requester: ' . $this->headerRequester, 'X-Dlua: ' . $this->headerDlua));
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_FAILONERROR, true);

            for ($i = 0; $i < count($deleteList); $i++) {
                $fileName = strtoupper(isset($deleteList[$i]['name']) ? $deleteList[$i]['name'] : $deleteList[$i]);
                $fileStatus = strtoupper(isset($deleteList[$i]['status']) ? $deleteList[$i]['status'] : "*not specified*");

                $this->fetchStatus .= "\r\nDeleting File " . ($i + 1) . " of $deleteCount: $fileName";
                // Make sure the file was downloaded before deleting from the DST(remote) server
                if (substr($fileStatus, 0, strlen("DOWNLOADED") - 1) == "DOWNLOADED" or $fileStatus = "*NOT SPECIFIED*") {
                    // Make sure the file doesn't already exist
                    $event = "&tidx=" . $this->txid . "&event=DeleteFile&file=" . $fileName;
                    curl_setopt($handle, CURLOPT_URL, $this->url . $event);
                    $output = curl_exec($handle);

                    // Check in the response header for good\bad delete
                    $errorNum = $errorMsg = $temp = "";
                    if (strpos($output, "X-Error: ") !== false) {
                        // Did this extra step to not confuse myself and future generations
                        $temp = substr($output, strpos($output, "X-Error: ") + strlen("X-Error: "));
                        $errorNum = trim(substr($temp, 0, strpos($temp, "\n")));
                    }
                    if (strpos($output, "X-Error-Message: ") !== false) {
                        // Did this extra step to not confuse myself and future generations
                        $temp = substr($output, strpos($output, "X-Error-Message: ") + strlen("X-Error-Message: "));
                        $errorMsg = trim(substr($temp, 0, strpos($temp, "\n")));
                    }

                    if ($errorMsg === "") {
                        $success++;
                        $status = 'deleted';

                        if (isset($deleteList[$i]['status'])) {
                            $deleteList[$i]['status'] = trim($deleteList[$i]['status']) . "/remotes deleted";
                            $status = $deleteList[$i]['status'];
                        }
                        $return[] = ["name" => $fileName, "status" => $status];
                    } else {
                        $status = "deletion failed-" . $errorNum . "-" . $errorMsg;

                        if (isset($deleteList[$i]['status'])) {
                            $deleteList[$i]['status'] = trim($deleteList[$i]['status']) . "/deletion failed-" . $errorNum . "-" . $errorMsg;
                            $status = $deleteList[$i]['status'];
                        }
                        $return[] = ["name" => $fileName, "status" => $status];
                    }
                }
            }
            curl_close($handle);
            $this->fetchStatus .= "\r\nDeleting Remote Files - Complete: " . $success . " of " . ($i) . " files successfully downloaded";
        } else {
            $this->fetchStatus .= "\r\n[EXCEPTION]Delete Remote Files: No files specified. Procedure cancelled.";
        }

        return $return;
    }

    function setUserId(string $setUserId)
    {
        $this->userId = trim($setUserId);
    }
    function setPassword(string $setPassword)
    {
        $this->password = trim($setPassword);
    }
    function setLocalFolder(string $setLocalFolder)
    {
        $this->localFolder = DIR_FS . rtrim($setLocalFolder, '/') . '/';
    }
    function setClient(string $setClient)
    {
        $this->client = trim($setClient);
    }
    function setParams(string $setParams)
    {
        $this->params = trim($setParams);
    }
    function setUrl(string $setUrl)
    {
        $this->url = trim($setUrl);
    }
    function setTxid(string $setTxid)
    {
        $this->txid = trim($setTxid);
    }
    function setHeaderDlua(string $setHeaderDlua)
    {
        $this->headerDlua = trim($setHeaderDlua);
    }
    function setHeaderRequester(string $setHeaderRequester)
    {
        $this->headerRequester = trim($setHeaderRequester);
    }
    function setDataDir(string $setDataDir)
    {
        $this->dataDir = DIR_FS . rtrim($setDataDir, '/') . '/';
    }
}
