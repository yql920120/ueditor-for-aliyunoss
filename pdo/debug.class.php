<?php
/**
 * Created by PhpStorm.
 * User: 神秘大腿
 * Date: 2017/3/1
 * Time: 10:54
 * QQ:  997823131
 */
defined('InCosmos') or exit('Access Invalid!');

class Debug {
		static $includefile=array();
		static $info=array();
		static $sqls=array();
		static $paths=array();
		static $startTime;                //保存脚本开始执行时的时间（以微秒的形式保存）
		static $stopTime;                //保存脚本结束执行时的时间（以微秒的形式保存）
		
		static $msg = array(
       			 E_WARNING=>'运行时警告',
       			 E_NOTICE=>'运行时提醒',
        		 E_STRICT=>'编码标准化警告',
        		 E_USER_ERROR=>'自定义错误',
        		 E_USER_WARNING=>'自定义警告',
        		 E_USER_NOTICE=>'自定义提醒',
        		 'Unkown '=>'未知错误'
		 );

		/**
		 * 在脚本开始处调用获取脚本开始时间的微秒值
		 */
		static function start(){                       
			self::$startTime = microtime(true);   //将获取的时间赋给成员属性$startTime
		}
		/**
		 *在脚本结束处调用获取脚本结束时间的微秒值
		 */
		static function stop(){
			self::$stopTime= microtime(true);   //将获取的时间赋给成员属性$stopTime
		}

		/**
		 *返回同一脚本中两次获取时间的差值
		 */
		static function spent(){
			return round((self::$stopTime - self::$startTime) , 4);  //计算后以4舍5入保留4位返回
		}

    		/*错误 handler*/
   		static function Catcher($errno, $errstr, $errfile, $errline){
	   		if(!isset(self::$msg[$errno])) 
				$errno='Unkown';
			//忽略注意级别的提示
			if(!($errno==E_NOTICE || $errno==E_USER_NOTICE)) {	
	   			$mess='<font color="red">';
	   			$mess.='<b>'.self::$msg[$errno]."</b>[在文件 {$errfile} 中,第 $errline 行]:";
	   			$mess.=$errstr;
				$mess.='</font>'; 
				self::addMsg($mess);
			}
		}
		/**
		 * 添加调试消息
		 * @param	string	$msg	调试消息字符串
		 * @param	int	$type	消息的类型
		 */
		static function addmsg($msg,$type=0) {
			if(defined("DEBUG") && DEBUG==1){
				switch($type){
					case 0:
						self::$info[]=$msg;
						break;
					case 1:
						self::$includefile[]=$msg;
						break;
					case 2:
						self::$sqls[]=$msg;
						break;
					case 3:
						self::$paths[]=$msg;
						break;
				}
			}
		}
		/**
		 * 输出调试消息
		 */
		static function message(){
			$mess = '<br />';
			$mess .= '<font size="1"><table style="border:3px solid #eeeeec" align="center" bgcolor="#eeeeec" dir="ltr" cellspacing="0" cellpadding="1">';
			$mess .= '<tr  bgcolor="#e9b96e"><th>&nbsp;</th><th align="left" colspan="4">运行信息</th><th align="right"><span onclick="this.parentNode.parentNode.parentNode.parentNode.style.display=\'none\'" style="cursor:pointer;width:50px;text-align:center;background:#500;border:1px solid #555;color:white">关闭X</span></th></tr>';


			if(count(self::$includefile) > 0){
				$mess.= '<tr><th>#</th><th align="left"  colspan="5">［自动包含］</th></tr><tr style="font-weight:bold"><td>&nbsp;</td><td colspan="5">';
			
				foreach(self::$includefile as $file){
					$mess .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;'.$file.'</span>';
				}	
				$mess .= '</td></tr>';	
			}

		

			if(count(self::$info) > 0 ){
				$mess.= '<tr><th>#</th><th align="left"  colspan="5">［系统信息］</th></tr><tr><td>&nbsp;</td><td colspan="5"><ul>';
				foreach(self::$info as $info){
					$mess.= '<li>&nbsp;&nbsp;&nbsp;&nbsp;'.$info.'</li>';
				}
				$mess .= '</ul></td></tr>';	
			}

			if(count(self::$paths) > 0 ){
				$mess.= '<tr><th>#</th><th align="left"  colspan="5">［架构资源］</th></tr><tr><td>&nbsp;</td><td colspan="5"><ul>';
				foreach(self::$paths as $path){
					$mess.= '<li>&nbsp;&nbsp;&nbsp;&nbsp;'.$path.'</li>';
				}
				$mess .= '</ul></td></tr>';	
			}

			if(count(self::$sqls) > 0) {
				$mess.= '<tr><th>#</th><th align="left"  colspan="5">［SQL语句］</th></tr><tr><td>&nbsp;</td><td colspan="5"><ul>';
				foreach(self::$sqls as $sql){
					$mess.= '<li>&nbsp;&nbsp;&nbsp;&nbsp;'.$sql.'</li>';
				}
				$mess .= '</ul></td></tr>';
			}
		
			$mess.= '<tr style="font-weight:bold"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">脚本运行总时：</td><td><span style="color:red;font-weight:100">'.self::spent().'</span>秒</td></tr></table></font>';	

			return $mess;
		}
	}




