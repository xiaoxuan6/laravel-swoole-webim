<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services;

use swoole_http_request;
use swoole_websocket_frame;
use swoole_websocket_server;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;

/**
 * @see https://wiki.swoole.com/wiki/page/400.html
 */
class WebSocketService implements WebSocketHandlerInterface
{
    protected $websocket;

    public function __construct()
    {
        $this->websocket = new WebSocket();
    }

    public function onOpen(swoole_websocket_server $server, swoole_http_request $request)
    {
        $this->websocket->open($request);
        echo $request->fd . "open\n";
    }

    public function onMessage(swoole_websocket_server $server, swoole_websocket_frame $frame)
    {
        $data = json_decode($frame->data, true);
        switch ($data['type']) {
            case 1://登录
                $this->websocket->login($frame);
                echo $frame->fd . "登陆\n";

                break;
            case 2: //新消息
                $this->websocket->new($frame);
                echo $frame->fd . "发了消息\n";

                break;

            case 3: // 改变房间
                $this->websocket->change($frame);
                echo $frame->fd . "改变房间\n";

                break;
            case 4: //私聊信息消息
                $this->websocket->secretnew($frame);
                echo $frame->fd . "向" . $data['receive_fd'] . "发起了私聊\n";

                break;
            default:
                $server->push($frame->fd, json_encode(['code' => 0, 'msg' => 'type error']));
        }
    }

    //登出,记得laravels.php中的dispatch_mode要设置为2
    public function onClose(swoole_websocket_server $server, $fd, $reactorId)
    {
        $this->websocket->logout($fd);
        echo "client {$fd} closed\n";
    }
}
