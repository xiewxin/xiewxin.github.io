<?php

namespace App\Http\Controllers\Home\Exercise;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

/**
 * 練習
 * 
 * @author xiewxin
 */
class CollectController extends Controller
{
    /**
     * 練習1
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect1(Request $request)
    {
        $numbers = $request->get('numbers', 'X,XL,XXL');
        $colors  = $request->get('colors', '红色,蓝色,紫色');
        
        // A. 參數整理
        $numbers = collect(explode(',', $numbers))
            ->unique()->filter(function ($value) {
                return ! empty($value);
            });
        $colors  = collect(explode(',', $colors))
            ->unique()->filter(function ($value) {
                return ! empty($value);
            })->all();
        
        // B. 笛卡爾積    
        return response()->json([
            'status' => 1, 
            'msg'    => '請求成功', 
            'data'   => $numbers->crossJoin($colors)->all()
        ]);
    }
    
    /**
     * 練習2
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect2() 
    {
        // A. 初始配置
        $arr = [
            ["id" => 1, "name" => "张三", "age" => 39, "gender" => "M", "weight" => 130],
            ["id" => 2, "name" => "李四", "age" => 25, "gender" => "F", "weight" => 120],
            ["id" => 3, "name" => "王五", "age" => 18, "gender" => "M", "weight" => 150],
            ["id" => 4, "name" => "陈六", "age" => 18, "gender" => "M", "weight" => 135],
            ["id" => 5, "name" => "周七", "age" => 18, "gender" => "M", "weight" => 135],
        ];
        
        // B. 數據整理
        $res = collect($arr)->where('gender', 'M')
            ->shuffle()
            ->sortByDesc('age')
            ->sortBy('weight')
            ->keyBy('id')
            ->all();
        
        return response()->json([
            'status' => 1,
            'msg'    => '請求成功',
            'data'   => $res, 
        ]);
    }
    
    /**
     * 練習3
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect3(Request $request) 
    {
        $numbers = $request->get('numbers', 'a,b,c,d,e,f,g,h');
        $offset  = $request->get('offset', 3);

        $collect = collect(explode(',', $numbers));
        $arr     = $collect->slice($offset);
        
        return response()->json([
            'status' => 1,
            'msg'    => '請求成功',
            'data'   => $arr->merge($collect->diff($arr->all())),
        ]);
    }
    
    /**
     * 練習4
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect4(Request $request)
    {
        $words = $request->get('words', 'picture,turepic,icturep,word,ordw,lint');
        
        $res = collect(explode(',', $words))->mapToGroups(function ($item) {
            $tmp_arr = str_split($item);
            asort($tmp_arr);
            
            return [implode('', $tmp_arr) => $item];
        })
        ->filter(function ($arr) {
            return count($arr) > 1;
        })
        ->map(function ($arr) {
            return $arr->toArray();
        })->values()->all();       

        return response()->json([
            'status' => 1,
            'msg'    => '請求成功',
            'data'   => $res,
        ]);
    }
    
    /**
     * 練習5
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect5(Request $request) 
    {
        $arrs = $request->get('arr', [[7, 1, 6], [5, 10], [2, 5]]);
        
        $sum = collect($arrs)->map(function ($item) {
            $temp = collect($item)->reverse()->all();
            
            return (int) implode('', $temp);
        })->sum();
        
        return response()->json([
            'status' => 1,
            'msg'    => '請求成功',
            'data'   => collect(str_split($sum))->reverse()->values()->all(),
        ]);
    }
}