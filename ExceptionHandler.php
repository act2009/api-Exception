<?php
/**

 */

namespace app\lib\exception;


use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
        private $code;//http 状态码
        private $msg; //错误信息
        private $errorCode;//自定义的错误code

          public function render(\Exception $e)
          {
            //如果异常属于BaseException,则属于用户异常，否则是服务器异常
            if($e instanceof BaseException){
                //如果是自定义的异常
                $this->code=$e->code;
                $this->msg=$e->msg;
                $this->errorCode=$e->errorCode;
            }else{
                if(config('app_debug')==true){
                    //如果开启了debug模式，走正常的调试模式，否则走我们自定义的json数据格式
                    return parent::render($e);

                }else{
                $this->code='500';
                $this->msg='服务器内部错误';
                $this->errorCode='999';
                $this->recordErrorLog($e);
                }
            }
              //
              $url=Request::instance()->url();
              $result=[
                  'msg'=>$this->msg,
                  'error_code'=>$this->errorCode,
                  'request_url'=>$url,
              ];

              return json($result,$this->code);
          }
          /*自定义记录错误日志*/
          private function recordErrorLog(\Exception $e){
              //初始化
              Log::init([
                  // 日志记录方式，内置 file socket 支持扩展
                  'type'  => 'File',
                  // 日志保存目录
                  'path'  => LOG_PATH,
                  // 日志记录级别
                  'level' => ['error'],
              ]);
              Log::record($e->getMessage(),'error');
          }
}