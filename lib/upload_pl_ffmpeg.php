<?php
/**
 * upload.php (Customised for PHPVibe.com)
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */


// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 5 minutes execution time
@set_time_limit(5 * 60);

require_once("../load.php");

/**
 * Save to db
 * @param string $file
 */
function vinsert($file){
    /** @var ezSQL_mysql $db */
    global $db, $token;
    //Just one insert
    if(isset($_SESSION['upl-' . $token])){return;}

    $db->query(sprintf(
        "INSERT INTO %svideos_tmp (`uid`, `name`, `path`, `ext`) VALUES ('%s', '%s', '%s', '%s')",
        DB_PREFIX,
        user_id(),
        $token,
        $file,
        substr($file, strrpos($file, '.') + 1)
    ));
    // Prepare conversion
    $db->query(sprintf(
        "INSERT INTO %svideos (`date`,`pub`,`token`, `user_id`, `tmp_source`, `thumb`) VALUES (now(), '0','%s', '%s', '%s','storage/uploads/processing.png')",
        DB_PREFIX,
        $token,
        user_id(),
        $file
    ));
    //Add action
    $id = $db->get_var(sprintf(
        "SELECT id from %svideos where token = '%s' order by id DESC limit 0,1",
        DB_PREFIX,
        $token
    ));
    if($id){
        add_activity('4', $id);
    }
    //Prevent multiple
    //inserts when chucking
    $_SESSION['upl-' . $token] = 1;
}

try {
    if (!is_user()) {
        throw new Exception(_lang("Login first!"), 100);
    }

    // Settings
    $targetDir = ABSPATH.'/storage/'.get_option('tmp-folder', 'rawmedia')."/";
    $token = '';
    if (isset($_REQUEST['token']) && not_empty($_REQUEST['token'])) {
        $token = toDb($_REQUEST['token']);
    } else if (isset($_REQUEST['pvo']) && not_empty($_REQUEST['pvo'])) {
        $token = toDb($_REQUEST['pvo']);
    } else {
        $msg = _lang(sprintf(
            "Oups! Something went wrong. Token was empty. [%s/%s] Please refresh the page and try again",
            @$_REQUEST['token'],
            @$_REQUEST['pvo']
        ));
        throw new Exception($msg,107);
    }

    $cleanupTargetDir = true; // Remove old files
    $maxFileAge = 5 * 3600; // Temp file age in seconds

    // Create target dir
    if (!file_exists($targetDir)) {
        @mkdir($targetDir);
    }

    // Get a file name
    if (isset($_REQUEST["name"])) {
        $fileName = $_REQUEST["name"];
    } else if (isset($_REQUEST["fnm"])) {
        $fileName = $_REQUEST["fnm"];
    } else if (!empty($_FILES)) {
        $fileName = $_FILES["file"]["name"];
    } else {
        $fileName = $token;
    }
    if (is_insecure_file(strtolower($fileName))) {
        $msg = _lang("Insecure file detected! This file has an high chance of being an hacking attempt!");
        throw new Exception($msg,107);
    }
    $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
    $ext = substr($fileName, strrpos($fileName, '.') + 1);
    $targetPath = $targetDir . DIRECTORY_SEPARATOR . $token . '.' . $ext;;
    // Chunking might be enabled
    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


    // Remove old temp files
    if ($cleanupTargetDir) {
        if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
            $msg = "Failed to open temp directory.";
            throw new Exception($msg,100);
        }

        while (($file = readdir($dir)) !== false) {
            $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

            // If temp file is current file proceed to the next
            if ($tmpfilePath == "{$filePath}.part") {
                continue;
            }

            // Remove temp file if it is older than the max age and is not the current file
            if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                @unlink($tmpfilePath);
            }
        }
        closedir($dir);
    }


    // Open temp file
    if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
        $msg = "Failed to open output stream.";
        throw new Exception($msg,102);
    }

    if (!empty($_FILES)) {
        if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
            $msg = "Failed to move uploaded file.";
            throw new Exception($msg,103);
        }

        // Read binary input stream and append it to temp file
        if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
            $msg = "Failed to open input stream.";
            throw new Exception($msg,101);
        }
    } else {
        if (!$in = @fopen("php://input", "rb")) {
            $msg = "Failed to open input stream.";
            throw new Exception($msg,101);
        }
    }

    while ($buff = fread($in, 4096)) {
        fwrite($out, $buff);
    }

    @fclose($out);
    @fclose($in);

    // Check if file has been uploaded
    if (!$chunks || $chunk == $chunks - 1) {
        // Strip the temp .part suffix off
        rename("{$filePath}.part", $filePath);
        rename($filePath, $targetPath);
    }
    //Insert in db
    vinsert($token.'.'.$ext);
    // Return Success JSON-RPC response
    exit(json_encode([
        'jsonrpc'=>'2.0',
        'result'=>null,
        'id'=>'id'
    ]));

}catch (Exception $exception){
    // TODO Прикрутить логирование
    exit(json_encode([
        'jsonrpc'=>'2.0',
        'error'=>[
            'code'=>$exception->getCode(),
            'message'=>$exception->getMessage()
        ],
        'id'=>'id'
    ]));
}
