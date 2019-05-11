<?php
/**

 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
   public $code=400;//http 状态码
   public $msg='参数错误'; //错误信息
   public $errorCode='';//自定义的错误code

   //编写构造函数
    public function __construct($params=[]){
        if(!is_array($params)){
            return ;
        }

        if(array_key_exists('code',$params)){
             $this->code=$params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg=$params['msg'];
        }
        if(array_key_exists('code',$params)){
            $this->errorCode=$params['errorCode'];
        }

    }
}