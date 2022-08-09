<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Task;

use App\Services\ChatService;
use Hhxsv5\LaravelS\Swoole\Task\Task;

class ChatTask extends Task
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(): string
    {
        $pushMsg = ['code' => 0, 'msg' => '', 'data' => []];
        $data = json_decode($this->data, true);

        switch ($data['task']) {
            case 'open':
                $pushMsg = ChatService::open($data);
                app('swoole')->push($data['fd'], json_encode($pushMsg));

                return 'Finished';

            case 'groupChat':
                $pushMsg = ChatService::groupChat($data);

                break;
            case 'nologin':
                $pushMsg = ChatService::noLogin($data);
                app('swoole')->push($data['fd'], json_encode($pushMsg));

                return "Finished";
            case 'login':
                $pushMsg = ChatService::doLogin($data);

                break;
            case 'logout':
                $pushMsg = ChatService::doLogout($data);

                break;
            case 'change':
                $pushMsg = ChatService::change($data);

                break;
            case 'new':
                $pushMsg = ChatService::sendNewMsg($data);

                break;
            case 'secretnew':
                $pushMsg = ChatService::sendSecretMsg($data);
                //对方在线才可以私聊信息
                if ($pushMsg) {
                    $this->sendMsgBySecret(app('swoole'), $pushMsg, $data['send_fd']);
                }

                return "Finished";
        }
        if ($pushMsg) {
            $this->sendMsg(app('swoole'), $pushMsg, $data['fd']);
        }

        return "Finished";
    }

    public function finish()
    {
    }

    //群发，广播,给所有人
    private function sendMsg($swoole, $pushMsg, $myfd)
    {
        echo "当前服务器共有 " . count(app('swoole')->ws_usersTable) . " 个连接\n";
        foreach (app('swoole')->ws_usersTable as $row) {
            if ($row['fd'] === $myfd) {
                $pushMsg['data']['mine'] = 1;
            } else {
                $pushMsg['data']['mine'] = 0;
            }

            $swoole->push($row['fd'], json_encode($pushMsg, JSON_UNESCAPED_UNICODE));
        }
    }

    //只广播给同个房间的人
    private function sendMsgOnlyRoom($swoole, $pushMsg, $myfd)
    {
        $userArr = $swoole->ws_roomsTable->get($pushMsg['data']['roomid']);
        if ($userArr) {
            $userArr = json_decode($userArr['users'], true);
            echo "当前房间共有 " . count($userArr) . " 个连接\n";
            foreach ($userArr as $row) {
                if ($row === $myfd) {
                    $pushMsg['data']['mine'] = 1;
                } else {
                    $pushMsg['data']['mine'] = 0;
                }
                $swoole->push($row, json_encode($pushMsg));
            }
        }
    }

    //私聊发信息
    private function sendMsgBySecret($swoole, $pushMsg, $myfd)
    {
        //判断用户是否在线
        $user = $swoole->ws_usersTable->get('user' . $pushMsg['data']['receive_fd']);
        if ($user) {
            //发送给发送方
            if ($myfd == $pushMsg['data']['send_fd']) {
                $pushMsg['data']['mine'] = 1;
                $swoole->push($pushMsg['data']['send_fd'], json_encode($pushMsg));
            }

            //发送给接受方
            $pushMsg['data']['mine'] = 0;
            $swoole->push($pushMsg['data']['receive_fd'], json_encode($pushMsg));
        }
    }
}
