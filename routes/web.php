<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

Route::get('/', function () {
    return view('index');
});

Route::post('upload_imgs', function () {
    if (! request()->hasFile('upload')) {
        return ['err' => '文件不存在', 'msg' => ['url' => '', 'localname' => '']];
    }

    $file = request()->file('upload');
    if (! $file->isValid()) {
        return ['err' => '上传失败', 'msg' => ['url' => '', 'localname' => '']];
    }

    $ext = $file->getClientOriginalExtension();
    if (! in_array($ext, ['gif', 'jpg', 'jpge', 'png'])) {
        return ['err' => '上传格式错误', 'msg' => ['url' => '', 'localname' => '']];
    }

    $filename = uniqid(date('Ymd')) . '.' . $ext;
    $file->move(storage_path('/uploads'), $filename);

    return ['err' => 'success', 'msg' => [
        'url' => sprintf('%s:%s%s', config('app.url'), '10000', '/uploads/' . $filename),
        'localname' => $file->getFilename()
    ]];
});
