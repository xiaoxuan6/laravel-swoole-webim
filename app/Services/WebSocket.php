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

use App\Task\ChatTask;
use Hhxsv5\LaravelS\Swoole\Task\Task;

class WebSocket
{
    public function open($request)
    {
        $data = [
            'task' => 'open',
            'fd' => $request->fd
        ];

        Task::deliver(new ChatTask(json_encode($data)));
    }

    public function groupChat($frame)
    {
        $data = json_decode($frame->data, true);
        $params = [
            'task' => 'groupChat',
            'fd' => $frame->fd,
            'room_id' => $data['message'] . date('m'),
            'room_name' => $data['message']
        ];

        Task::deliver(new ChatTask(json_encode($params)));
    }

    public function login($frame)
    {
        $data = json_decode($frame->data, true);
        $params = [
            'task' => 'login',
            'params' => [
                'name' => $data['name'],
                'email' => $data['email']
            ],
            'fd' => $frame->fd,
            'roomid' => $data['roomid']
        ];
        if (! $params['params']['name'] || ! $params['params']['email']) {
            $params['task'] = "nologin";
        }

        Task::deliver(new ChatTask(json_encode($params)));
    }

    public function new($frame)
    {
        $data = json_decode($frame->data, true);
        $params = [
            'task' => 'new',
            'params' => [
                'name' => $data['name'],
                'avatar' => $data['avatar']
            ],
            'c' => $data['c'],
            'message' => $data['message'],
            'fd' => $frame->fd,
            'roomid' => $data['roomid']
        ];
        Task::deliver(new ChatTask(json_encode($params)));
    }

    public function change($frame)
    {
        $data = json_decode($frame->data, true);
        $params = [
            'task' => 'change',
            'params' => [
                'name' => $data['name'],
                'avatar' => $data['avatar'],
                'email' => $data['email'],
            ],
            'fd' => $frame->fd,
            'oldroomid' => $data['oldroomid'],
            'roomid' => $data['roomid']
        ];
        Task::deliver(new ChatTask(json_encode($params)));
    }

    public function secretnew($frame)
    {
        $data = json_decode($frame->data, true);
        $params = [
            'task' => 'secretnew',
            'params' => [
                'name' => $data['send_name'],
                'avatar' => $data['send_avatar']
            ],
            'c' => $data['c'],
            'message' => $data['message'],
            'send_fd' => $frame->fd,
            'receive_fd' => $data['receive_fd']
        ];
        Task::deliver(new ChatTask(json_encode($params)));
    }

    public function logout($fd)
    {
        $user = app('swoole')->ws_usersTable->get('user' . $fd);
        if ($user) {
            $data = [
                'task' => 'logout',
                'params' => [
                    'name' => $user['name'],
                    'roomid' => $user['roomid']
                ],
                'fd' => $fd
            ];
            Task::deliver(new ChatTask(json_encode($data)));
        }
    }
}
