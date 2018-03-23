<?php
/**
 * 获取已上传的文件列表
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
include "Uploader.class.php";

/* 判断类型 */
switch ($_GET['action']) {
    /* 列出文件 */
    case 'listfile':
        $allowFiles = $CONFIG['fileManagerAllowFiles'];
        $listSize = $CONFIG['fileManagerListSize'];
        $path = $CONFIG['fileManagerListPath'];
        break;
    /* 列出图片 */
    case 'listimage':
    default:
        $allowFiles = $CONFIG['imageManagerAllowFiles'];
        $listSize = $CONFIG['imageManagerListSize'];
        $path = $CONFIG['imageManagerListPath'];
}

$allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);
/* 获取参数 */
$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
$end = $start + $size;

/* 获取文件列表 */
$path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
$files = getfiles($path, $allowFiles);
if (!count($files)) {
    return json_encode(array(
        "state" => "no match file",
        "list" => array(),
        "start" => $start,
        "total" => count($files)
    ));
}

/* 获取指定范围的列表 */
$len = count($files);
for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
    $list[] = $files[$i];
}
//倒序
//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
//    $list[] = $files[$i];
//}


// 以上是 原来的 数据 请注释

/* 定义 PDO start */
define('InCosmos',true);
define('DS','/');   // 斜杠

//定义我的PDO
define("HOST","localhost");
define("USER","root");
define("PASS","root");
define("DBNAME","shaonian");
define("TABPREFIX","33hao_");
define("DEBUG","0");
///* 定义 PDO end */
$pdo_path = $_SERVER['DOCUMENT_ROOT'].DS."core".DS."framework".DS."libraries".DS."dpdo.php";
include_once($pdo_path);
$db_ueditor = new Dpdo();
$db_ueditor->setTable("bbs_ueditor_pic");
$data_ueditor = $db_ueditor->limit($_GET['start'],20)->select();

$data_total = $db_ueditor->total();
//print_r($data_ueditor);

/* 新的数据返回  start */
$result = json_encode(array(
    "state" => "SUCCESS",
    "list" => $data_ueditor,
    "start" => $start,
    "total" => $data_total
));
/* 新的数据返回  end */

/* 原来的 返回数据 */

/*$result = json_encode(array(
    "state" => "SUCCESS",
    "list" => $list,
    "start" => $start,
    "total" => count($files)
));*/

return $result;


/**
 * 遍历获取目录下的指定类型的文件
 * @param $path
 * @param array $files
 * @return array
 */
function getfiles($path, $allowFiles, &$files = array())
{
    if (!is_dir($path)) return null;
    if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $path2 = $path . $file;
            if (is_dir($path2)) {
                getfiles($path2, $allowFiles, $files);
            } else {
                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                    $files[] = array(
                        'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                        'mtime'=> filemtime($path2)
                    );
                }
            }
        }
    }
    return $files;
}