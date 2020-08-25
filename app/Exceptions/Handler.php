<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Library\Common\Common;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
{

    // 此处用Jobs做 体验更佳
    $webhook = "https://oapi.dingtalk.com/robot/send?access_token=411963d1598df9577494089a59d9de40e7ec92fbc5e8bec1b4cca139b15c8fc0";
    $msg= sprintf("%s:%s:%s", $e->getFile(), $e->getLine(), $e->getMessage());
    $message = "系统异常报告： \r\r".$msg."\r".date("Y-m-d H:i");
    $data = array ('msgtype' => 'text','text' => array ('content' => $message));
    $data_string = json_encode($data);

    $res = Common::curl($webhook, $data_string);

    parent::report($e);
}

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }
}
