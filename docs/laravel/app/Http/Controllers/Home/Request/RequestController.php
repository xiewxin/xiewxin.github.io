<?php

namespace App\Http\Controllers\Home\Request;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $res = [];
        
        // 1. path 方法返回请求的路径信息。因此，如果接收到的请求
        // 目标是 http://xiewxin.lv.com/home/request，
        // 则 path 方法会返回 home/request
        $res['path'] = $request->path();
        
        // 2. is 方法验证请求的路径是否与给定的模式匹配。
        // true
        $res['is'][] = $request->is('home/request');
        
        // 2.1 使用此方法时，可以将 * 字符作为通配符
        // false
        $res['is'][] = $request->is('request/*');

        // 3. 要获取完整的请求 URL，你可以使用 url 或 fullUrl 方法
        // 3.1 url 方法返回不带查询条件的 URL
        // http://xiewxin.lv.com/home/request
        $res['url'] = $request->url();
        
        // 3.2 fullUrl 方法的返回包含查询条件字符串
        // http://xiewxin.lv.com/home/request?id=1
        $res['fullUrl'] = $request->fullUrl();
        
        // 4. 當前請求類型
        // get
        $res['method'] = $request->method();
        
        // 4.1 isMethod 方法去验证 HTTP 动词与所给定的字符串是否匹配
        // false
        if ($res['isMethod'] = $request->isMethod('post')) {
            // xxx
        }
        
        // 5. all 方法来获取 array 类型的全部输入数据
        $res['all'] = $request->all();
        
        // 6. input 方法都可以用来获取用户的输入数据
        // input 方法可以从整个请求体中获取数据（包括查询字符串）
        $res['input'][] = $request->input('name');
        
        // 6.1 input 方法第二个参数传入一个默认值。
        // 这个值将会在当前请求不包含所需要的字段时返回
        $res['input'][] = $request->input('type', 'pc');
        
        // 6.2 当处理包含数组的表单时，可以使用 「.」 运算符来访问数组的数据
        $res['input'][] = $request->input('products.0.name');
        
        $res['input'][] = $request->input('products.*.name');
        
        // 6.3 你也可以使用无参数的 input 方法来获取全部输入的关联数组
        // 和$request->all()一樣
        $res['input'][] = $request->input();
        
        // 7. query 方法仅仅从查询字符串中获取输入值
        $res['query'][] = $request->query('type', 'ios');
        
        // 7.1 无参数的 query 方法来获取全部查询条件的关联数组
        $res['query'][] = $request->query();
        
        // 8. 如果需要获取输入数据的子集，你可以使用 only 或 except 方法。
        // 它们接受单个 array 或者动态参数列表
        $res['only'][] = $request->only(['username', 'password']);
        
        $res['only'][] = $request->only('username', 'password');
        
        $res['except'][] = $request->except(['credit_card']);
        
        $res['except'][] = $request->except('credit_card');
        
        // 9. has 来判断当前请求中是否含有指定的值。
        // 如果请求中存在该值则 has 方法将会返回 true
        $res['has'][] = $request->has('id');
        
        // 9.1 当给定一个数组时，has 将会判断指定的值是否全部存在
        $res['has'][] = $request->has(['id', 'type']);
        
        // 9.2 hasAny 方法将会在指定的值有一个存在的情况下返回 true
        $res['has'][] = $request->hasAny(['id', 'type']);
        
        // 10. 如果你想要判断一个值在请求中是否存在，并且不为空，可以使用 filled 方法
        $res['filled'] = $request->filled('id');
        
        // 如果你想要判断一个值在请求中是否缺失，可以使用 missing 方法
        $res['missing'] = $request->missing('name');
        
        dump($res);
    }
}