<?php

namespace App\Http\Controllers\Home\Auxiliary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ArrController extends Controller
{
    public function index()
    {
        $res = [];
        
        // 1. 将给定键值加入到某数组中
        /*
         NOTE:如果给定的键在数组中不存在或数组被设置为 null ，
         NOTE:那么 Arr::add 函数将会把给定的键值对添加到数组中
         */
        // 1.1 ['name' => 'Desk', 'price' => 100]
        $res['arr_add'][] = Arr::add(['name' => 'Desk'], 'price', 100);
        
        // 1.2 ['name' => 'Desk', 'price' => 100]
        $res['arr_add'][] = Arr::add(['name' => 'Desk', 'price' => null], 'price', 100);
        
        // 2. Arr::collapse 函数将多个数组合并为一个数组
        // [1, 2, 3, 4, 5, 6, 7, 8, 9]
        $res['arr_collapse'] = Arr::collapse([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
        
        // 3. Arr::crossJoin 函数交叉连接给定的数组，返回具有所有可能排列的笛卡尔乘积
        /*
         3.1
         [
            [1, 'a'],
            [1, 'b'],
            [2, 'a'],
            [2, 'b'],
         ]
         */
        $res['arr_crossJoin'][] = Arr::crossJoin([1, 2], ['a', 'b']);
        
        /*
         3.2
         [
             [1, 'a', 'I'],
             [1, 'a', 'II'],
             [1, 'b', 'I'],
             [1, 'b', 'II'],
             [2, 'a', 'I'],
             [2, 'a', 'II'],
             [2, 'b', 'I'],
             [2, 'b', 'II'],
         ]
         */
        $res['arr_crossJoin'][] = Arr::crossJoin([1, 2], ['a', 'b'], ['I', 'II']);
        
        // 4. Arr::divide 函数返回一个二维数组，一个值包含原始数组的键，
        // 另一个值包含原始数组的值
        // $keys: ['name']
        // $values: ['Desk']
        $res['arr_divide'] = Arr::divide(['name' => 'Desk']);
        
        // 5. Arr::dot 函数将多维数组中所有的键平铺到一维数组中，
        // 新数组使用「.」符号表示层级包含关系
        // ['products.desk.price' => 100]
        $array = ['products' => ['desk' => ['price' => 100]]];
        $res['arr_dot'] = Arr::dot($array);
        
        // 6. Arr::except 函数从数组中删除指定的键值对
        // ['name' => 'Desk']
        $array = ['name' => 'Desk', 'price' => 100];
        $res['arr_except'] = Arr::except($array, ['price']);
        
        // 7. Arr::first 函数返回数组中通过真值测试的第一个元素
        // 200
        $array = [100, 200, 300];
        $res['arr_first'][] = Arr::first($array, function ($value, $key) {
            return $value >= 150;
        });
        
        // 7.1 将默认值作为第三个参数传递给该方法， 如果数组中没有值通过真值测试，则返回默认值
        $res['arr_first'][] = Arr::first($array, function ($value, $key) {
            return $value >= 400;
        }, 500);
    
        // 8. Arr::flatten 函数将多维数组中数组的值取出平铺为一维数组
        // ['Joe', 'PHP', 'Ruby']
        $array = ['name' => 'Joe', 'languages' => ['PHP', 'Ruby']];
        $res['arr_flatten'] = Arr::flatten($array);
        
        // 9. Arr::forget 函数使用「.」符号从深度嵌套的数组中删除给定的键值对
        $array = ['products' => ['desk' => ['price' => 100]]];
        $res['arr_forget'] = Arr::forget($array, 'products.desk');
        
        // 10. Arr::get 函数使用「.」符号从深度嵌套的数组中根据指定键检索值
        // 100
        $array = ['products' => ['desk' => ['price' => 100]]];
        $res['arr_get'][] = Arr::get($array, 'products.desk.price');
        
        // 10.1 Arr::get 函数也接受一个默认值，如果没有找到特定的键，将返回默认值
        $res['arr_get'][] = Arr::get($array, 'products.desk.discount', 0);
        
        // 11. Arr::has 函数使用「.」符号查找数组中是否存在指定的一个或多个键
        $array = ['product' => ['name' => 'Desk', 'price' => 100]];
        // true
        $res['arr_has'][] = Arr::has($array, 'product.name');
        // false
        $res['arr_has'][] = Arr::has($array, ['product.price', 'product.discount']);
        
        // 12. 如果给定数组是关联数组，则 Arr::isAssoc 函数返回 true 。
        // 如果数组没有以零开头的连续数字键，则将其视为 “关联”。
        // true
        $res['arr_isAssoc'][] = Arr::isAssoc(['product' => ['name' => 'Desk', 'price' => 100]]);
        // false
        $res['arr_isAssoc'][] = Arr::isAssoc([1, 2, 3]);
        
        // 13. Arr::last 函数返回数组中满足指定条件的最后一个元素
        $array = [100, 200, 300, 110];
        // 300
        $res['arr_last'][] =  Arr::last($array, function ($value, $key) {
            return $value >= 150;
        });
        
        // 13.1 将默认值作为第三个参数传递给该方法，如果没有值通过真值测试，则返回该默认值
        // 500
        $res['arr_last'][] =  Arr::last($array, function ($value, $key) {
            return $value >= 350;
        }, 500);
        
        // 14. Arr::only 函数只返回给定数组中指定的键值对
        $array = ['name' => 'Desk', 'price' => 100, 'orders' => 10];
        // ['name' => 'Desk', 'price' => 100]
        $res['arr_only'] = Arr::only($array, ['name', 'price']);
        
        // 15. Arr::pluck 函数从数组中检索给定键的所有值
        $array = [
            ['developer' => ['id' => 1, 'name' => 'Taylor']],
            ['developer' => ['id' => 2, 'name' => 'Abigail']],
        ];
        // ['Taylor', 'Abigail']
        $res['arr_pluck'][] = Arr::pluck($array, 'developer.name');
        
        // 15.1 你也可以指定获取的结果的键
        // [1 => 'Taylor', 2 => 'Abigail']
        $res['arr_pluck'][] = Arr::pluck($array, 'developer.name', 'developer.id');
        
        // 16. Arr::prepend 函数将一个值插入到数组的开始位置
        $array = ['one', 'two', 'three', 'four'];
        // ['zero', 'one', 'two', 'three', 'four']
        $res['arr_prepend'][] = Arr::prepend($array, 'zero');
        
        // 16.1 如果需要，你可以指定你插入值的键
        $array = ['price' => 100];
        // ['name' => 'Desk', 'price' => 100]
        $res['arr_prepend'][] = Arr::prepend($array, 'Desk', 'name');
        
        // 17. Arr::pull 函数从数组中返回指定键的值并删除此键／值对
        $array = ['name' => 'Desk', 'price' => 100];
        // $name: Desk
        // $array: ['price' => 100]
        $res['arr_pull'][] = Arr::pull($array, 'name');
        
        // 17.1 默认值可以作为第三个参数传递给该方法，如果键不存在，则返回该值
        $res['arr_pull'][] = Arr::pull($array, 'id', 0);
        
        // 18. Arr::random 函数从数组中随机返回一个值
        $array = [1, 2, 3, 4, 5];
        $res['arr_random'][] = Arr::random($array);
        
        // 18.1 你也可以将返回值的数量作为可选的第二个参数传递给该方法，
        // 请注意，提供这个参数会返回一个数组，即使是你只需要一项
        $res['arr_random'][] = Arr::random($array, 2);
        
        // 19. Arr::query 函数将数组转换为查询字符串
        $array = ['name' => 'Taylor', 'order' => ['column' => 'created_at', 'direction' => 'desc']];
        // name=Taylor&order[column]=created_at&order[direction]=desc
        $res['arr_query'] = Arr::query($array);
        
        // 20. Arr::set 函数使用「.」符号在多维数组中设置指定键的值
        $array = ['products' => ['desk' => ['price' => 100]]];
        // ['products' => ['desk' => ['price' => 200]]]
        $res['arr_set'] = Arr::set($array, 'products.desk.price', 200);
        
        // 21. Arr::shuffle 函数将数组中值进行随机排序
        // [3, 2, 5, 1, 4] - (generated randomly)
        $res['arr_shuffle'] = Arr::shuffle([1, 2, 3, 4, 5]);
        
        // 22. Arr::sort 函数根据数组的值对数组进行排序
        $array = ['Desk', 'Table', 'Chair'];
        // ['Chair', 'Desk', 'Table']
        $res['arr_sort'][] = Arr::sort($array);
        
        // 22.1 你也可以根据给定闭包返回的结果对数组进行排序
        $array = [
            ['name' => 'Desk'],
            ['name' => 'Table'],
            ['name' => 'Chair'],
        ];
        /*
         [
             ['name' => 'Chair'],
             ['name' => 'Desk'],
             ['name' => 'Table'],
         ]
         */
        $res['arr_sort'][] = Arr::sort($array, function ($value) {
            return $value['name'];
        });
            
        // 23. Arr::sortRecursive 函数使用 sort 函数对数值子数组进行递归排序，
        // 使用 ksort 函数对关联子数组进行递归排序
        $array = [
            ['Roman', 'Taylor', 'Li'],
            ['PHP', 'Ruby', 'JavaScript'],
            ['one' => 1, 'two' => 2, 'three' => 3],
        ];
        /*
         [
             ['JavaScript', 'PHP', 'Ruby'],
             ['one' => 1, 'three' => 3, 'two' => 2],
             ['Li', 'Roman', 'Taylor'],
         ]
         */
        $res['arr_sortRecursive'] = Arr::sortRecursive($array);
        
        // 24. Arr::where 函数使用给定闭包返回的结果过滤数组
        $array = [100, '200', 300, '400', 500];
        // [1 => '200', 3 => '400']
        $res['arr_where'] = Arr::where($array, function ($value, $key) {
            return is_string($value);
        });
        
        // 25. Arr::wrap 方法可以将给定值转换为一个数组。如果给定值已经是一个数组，
        // 将不会进行转换
        $string = 'Laravel';
        $res['arr_wrap'][] = Arr::wrap($string);
        
        // 25.1 如果给定值是 null ，将返回一个空数组
        $nothing = null;
        $res['arr_wrap'][] = Arr::wrap($nothing);
        
        // 26. data_fill 函数以 . 形式给嵌套数组或对象中设置缺省值
        $data = ['products' => ['desk' => ['price' => 100]]];
        // ['products' => ['desk' => ['price' => 100]]]
        $res['data_fill'][] = data_fill($data, 'products.desk.price', 200);
        // ['products' => ['desk' => ['price' => 100, 'discount' => 10]]]
        $res['data_fill'][] = data_fill($data, 'products.desk.discount', 10);
        // 这个函数也可以接收一个 * 作为通配符，并对相应位置进行填充
        $data = [
            'products' => [
                ['name' => 'Desk 1', 'price' => 100],
                ['name' => 'Desk 2'],
            ],
        ];
        /*
         [
             'products' => [
                 ['name' => 'Desk 1', 'price' => 100],
                 ['name' => 'Desk 2', 'price' => 200],
             ],
         ]
         */
        $res['data_fill'][] = data_fill($data, 'products.*.price', 200);
        
        // 27. data_get 函数可使用 . 形式获得嵌套函数或者对象中的值
        $data = ['products' => ['desk' => ['price' => 100]]];
        // 100
        $res['data_get'][] = data_get($data, 'products.desk.price');
        
        // 27.1 当找不到指定键名时，data_get 函数也支持返回一个默认值
        // 0
        $res['data_get'][] = data_get($data, 'products.desk.discount', 0);
        
        // 27.2 这个函数也可以接受一个 * 作为通配符，以匹配数组或对象中任意键名
        $data = [
            'product-one' => ['name' => 'Desk 1', 'price' => 100],
            'product-two' => ['name' => 'Desk 2', 'price' => 150],
        ];
        // ['Desk 1', 'Desk 2'];
        $res['data_get'][] = data_get($data, '*.name');
        
        // 28. data_set 函数可以用 . 形式给嵌套函数或对象赋值
        $data = ['products' => ['desk' => ['price' => 100]]];
        // ['products' => ['desk' => ['price' => 200]]]
        $res['data_set'][] = data_set($data, 'products.desk.price', 200);
        
        // 28.1 这个函数也支持使用 * 作为通配符给相应键名赋值
        $data = [
            'products' => [
                ['name' => 'Desk 1', 'price' => 100],
                ['name' => 'Desk 2', 'price' => 150],
            ],
        ];
        /*
         [
             'products' => [
                 ['name' => 'Desk 1', 'price' => 200],
                 ['name' => 'Desk 2', 'price' => 200],
             ],
         ]
         */
        $res['data_set'][] = data_set($data, 'products.*.price', 200);
        
        // 28.2 通常情况下，已存在的值将会被覆盖。如果只是希望设置一个目前不存在的值，
        // 你可以增加一个 false 作为函数的第四个参数
        $data = ['products' => ['desk' => ['price' => 100]]];
        // ['products' => ['desk' => ['price' => 100, 'name' => 'xiaoming']]]
        $res['data_set'][] = data_set($data, 'products.desk.name', 'xiaoming', false);
        
        // 29. head 函数将返回数组中的第一个值
        $array = [100, 200, 300];
        $res['head'] = head($array);
        
        // 30. last 返回给定数组的最后一个元素
        $array = [100, 200, 300];
        $res['last'] = last($array);
        
        dump($res);
    }
}