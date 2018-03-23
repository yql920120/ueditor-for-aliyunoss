<?php
/**
 * aliyun oss
 *
 * @cosmos提供技术支持 授权请购买cosmos授权
 * @license    http://www.shikexu.com
 * @link       交流群号：997823131

 */
final class oss {
    private static $oss_sdk_service;
    private static $bucket;
    private static function _init() {
        require_once('oss/sdk.class.php');
        self::$oss_sdk_service = new ALIOSS(null,null,"oss-cn-shenzhen.aliyuncs.com");
        //设置是否打开curl调试模式
        self::$oss_sdk_service->set_debug_mode(false);
        self::$bucket = "haoshaonian";

    }

    //格式化返回结果
    private static function _format($response) {
        echo '|-----------------------Start---------------------------------------------------------------------------------------------------'."\n";
        echo '|-Status:' . $response->status . "\n";
        echo '|-Body:' ."\n";
        echo $response->body . "\n";
        echo "|-Header:\n";
        print_r ( $response->header );
        echo '-----------------------End-----------------------------------------------------------------------------------------------------'."\n\n";
    }

    /**
     * 
     * @param unknown $src_file
     * @param unknown $new_file
     */
    public static function upload($src_file,$new_file) {
        self::_init();
        try{
            $response = self::$oss_sdk_service->upload_file_by_file(self::$bucket,$new_file,$src_file);
//            echo "oss.42";
//            die();
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
             self::_format($response);exit;
        } catch (Exception $ex){
//            return false;
            die($ex->getMessage());
        }
    }

    public static function del($img_list = array()) {
        self::_init();
        try{
            $options = array(
                    'quiet' => false,
                    //ALIOSS::OSS_CONTENT_TYPE => 'text/xml',
            );
            $response = self::$oss_sdk_service->delete_objects(self::$bucket,$img_list,$options);
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex){
            return false;
            //die($ex->getMessage());
        }
    }
    /* 列出bucket列表 start */
    public static function list_bucket(){
        self::_init();
        try{
            $response = self::$oss_sdk_service->list_bucket();
            /* if ($response->status == '200') {
                 return true;
             } else {
                 return false;
             }*/
            self::_format($response);exit;
        }catch (Exception $ex){
            return false;
        }
    }
    /* 列出bucket列表 end */
}
