<?php
/**
 * Created by 大师兄
 * 派系: 神秘剑派
 * 技能: zxc秒杀
 * Date: 2018/3/22
 * Time: 19:55
 * QQ:  997823131 
 */
// 阿里云上传文件
include_once("oss.php");
include_once("uploadfile.php");

$result = oss::list_bucket();
print_r($result);