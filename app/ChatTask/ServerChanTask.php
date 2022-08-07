<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\ChatTask;

use Seven;
use Exception;
use Hhxsv5\LaravelS\Swoole\Task\Task;

class ServerChanTask extends Task
{
    protected $channel = [];
    private $data;

    public function __construct($data)
    {
        $this->channel = [
            'a' => config('serverchan.a'), //sw群组
            'b' => config('serverchan.b'), //php群组
            'c' => config('serverchan.c') //go群组
        ];

        $this->data = $data;
    }

    public function handle()
    {
        $domain = env('APP_URL');
        $message = $this->data['params']['name'] . "说了：\n" . $this->data['message'] . "\n";
        $message .= "来自于" . "[sevenshi的webim](" . $domain . ")" . "\n";

        switch ($this->data['roomid']) {
            case 'a':
                try {
                    $response = Seven::setTitle('来自sw群组')->setMessage($message)->setChannel($this->channel['a'])->pushbear();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                break;
            case 'b':
                try {
                    $response = Seven::setTitle('来自php群组')->setMessage($message)->setChannel($this->channel['b'])->pushbear();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                break;
            case 'c':
                try {
                    $response = Seven::setTitle('来自go群组')->setMessage($message)->setChannel($this->channel['c'])->pushbear();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                break;

            default:
                break;
        }
    }
}
