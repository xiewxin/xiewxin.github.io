<?php

namespace App\Http\Controllers\Home\Auxiliary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StringController extends Controller
{
    public function index()
    {
        $res = [];
        
        // 1. __函数可使用 本地化文件 翻译指定的字符串或者键值
        // 翻譯文件在 resources/lang 中定義,比如創建en.json
        $res['__'][] = __('Please enter your 4-digit verification number:');
        // 1.1 如果指定的转换字符串或键不存在，__ 函数将返回原来的值。
        $res['__'][] = __('messages.welcome');
        
        // 2. class_basename 函数返回删除命名空间后的类名
        // Baz
        $res['class_basename'] = class_basename('Foo\Bar\Baz');
        
        dump($res);
    }
}