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

use App\ChatTask\ChatTask;
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
        if (! $data['params']['name'] || ! $data['params']['email']) {
            $params['task'] = "nologin";
        }

        Task::deliver(new ChatTask(json_encode($params)));
    }
}
