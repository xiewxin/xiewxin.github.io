<?php

namespace App\Http\Controllers\Home\Auxiliary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PathController extends Controller
{
    public function index()
    {
        $res = [];
        
        // 1. app_path 函数返回 app 目录的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\app
        $res['app_path'][] = app_path();
        // 1.1 可以用 app_path 函数去生成应用程序目录下一个文件的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\app\Http/Controllers/Controller.php
        $res['app_path'][] = app_path('Http/Controllers/Controller.php');
        
        // 2. base_path 函数返回项目根目录的完整路径。 
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel
        $res['base_path'][] = base_path();
        // 2.1 你也可以用 base_path 函数生成项目根目录下一个文件的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\vendor/bin
        $res['base_path'][] = base_path('vendor/bin');
        
        // 3. config_path 函数返回 config 目录的完整路径。
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\config
        $res['config_path'][] = config_path();
        // 3.1 你也可以用 config_path 函数去生成应用程序配置目录下一个指定文件的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\config\app.php
        $res['config_path'][] = config_path('app.php');
        
        // 4. database_path 函数返回 database 目录的完整路径。
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\database
        $res['database_path'][] = database_path();
        // 4.1 你也可以用 database_path 函数去生成 database 目录下一个指定文件的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\database\factories/UserFactory.php
        $res['database_path'][] = database_path('factories/UserFactory.php');
            
        // 5 mix 返回 版本化 MIX 文件 的路径
//         $res['mix'] = mix('css/app.css');

        // 6. public_path 函数返回 public 目录的完整路径。
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\public"
        $res['public_path'][] = public_path();
        // 6.1 你也可以用 public_path 函数去生成 public 目录下一个指定文件的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\public\css/app.css
        $res['public_path'][] = public_path('css/app.css');
        
        // 7. resource_path 函数返回 resources 目录的完整路径。
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\resources
        $res['resource_path'][] = resource_path();
        // 7.1 你也可以用 resource_path 函数去生成资源文件目录下的一个指定文件的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\resources\sass/app.scss
        $res['resource_path'][] = resource_path('sass/app.scss');
        
        // 8. torage_path 函数返回指向 “storage” 目录的绝对路径。
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\storage
        $res['storage_path'][] = storage_path();
        // 8.1 还可以使用 storage_path 函数生成 storage 目录下给定文件的完整路径
        // D:\ruanjian\phpStudy\PHPTutorial\WWW\xiewxin.github.io\docs\laravel\storage\app/file.txt
        $res['storage_path'][] = storage_path('app/file.txt');
        
        dump($res);
    }
}