<?php

namespace App\Http\Controllers\Home\Collect;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class CollectController extends Controller
{
    public function index()
    {
        $res = [];
       
        // 1. 將數組中的每個元素轉為大寫並去除空值,以數組返回
        $res['collect'][] = collect(['taylor', 'abigail', null])->map(function ($name) {
                return strtoupper($name);
            })
            ->reject(function ($name) {
                return empty($name);
            })->toArray();
            
        // 2. collect 方法返回一个包含当前集合所含元素的新 Collection 实例
        // [1, 2, 3]
        $res['collect'][] = collect([1, 2, 3]);
        
        // 2.1 collect 方法从根本上对将 懒集合 转换为标准 Collection 实例有用
        $lazyCollection = LazyCollection::make(function () {
            yield 1;
            yield 2;
            yield 3;
        });
        $collection = $lazyCollection->collect();
        // 'Illuminate\Support\Collection'
        $res['collect'][] = get_class($collection);
        // [1, 2, 3]
        $res['collect'][] = $collection->all();

        // 3. all 方法返回代表集合的底层数组
        // [1, 2, 3]
        $res['all'] = collect([1, 2, 3])->all();
        
        // 4. avg 方法返回指定键的 平均值
        // 20
        $res['all'] = collect([
                ['foo' => 10], 
                ['foo' => 10], 
                ['foo' => 20], 
                ['foo' => 40]
            ])->avg('foo');
        
        // 5. chunk 方法把集合分割成多个指定大小的较小集合
        // [[1, 2, 3, 4], [5, 6, 7]]
        $collection = collect([1, 2, 3, 4, 5, 6, 7]);
        
        $chunks = $collection->chunk(4);
        
        $res['chunks'] = $chunks->toArray();
        
        // 6. collapse 方法把一个多数组集合坍缩为单个扁平的集合
        // [1, 2, 3, 4, 5, 6, 7, 8, 9]
        $collection = collect([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
        
        $collapsed = $collection->collapse();
        
        $res['collapse'] = $collapsed->all();
        
        // 7. combine 方法将一个集合的值作为键，与另一个数组或集合的值进行结合
        // ['name' => 'George', 'age' => 29]
        $collection = collect(['name', 'age']);
        
        $combined = $collection->combine(['George', 29]);
        
        $res['combine'] = $combined->all();
 
        // 8. concat 方法在集合的末端附加指定的 数组 或集合值
        $collection = collect(['John Doe']);
        
        $concatenated = $collection->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);
        
        // ['John Doe', 'Jane Doe', 'Johnny Doe']
        $res['concat'] = $concatenated->all();
        
        // 9. contains 方法检查集合有否包含指定的元素
        $collection = collect(['name' => 'Desk', 'price' => 100]);
        // true
        $res['contains'][] = $collection->contains('Desk');
        // false
        $res['contains'][] = $collection->contains('New York');
        
        // 10. 用 containsStrict 方法使用 “严格” 比较过滤。
        // 这个方法和 contains 方法类似；但是它却是使用了「严格」比较来比较所有的值。
        $collection = collect(['name' => 'Desk', 'price' => 100]);
        // true
        $res['containsStrict'][] = $collection->containsStrict('Desk');
        // false
        $res['containsStrict'][] = $collection->containsStrict('New York');
        
        // 11. count 方法返回这个集合内集合项的总数量
        $collection = collect([1, 2, 3, 4]);
        // 4
        $res['count'] = $collection->count();
        
        // 12. countBy 方法计算集合中每个值的出现次数。
        // 默认情况下，该方法计算每个元素的出现次数
        $collection = collect([1, 2, 2, 2, 3]);
        $counted = $collection->countBy();
        // [1 => 1, 2 => 3, 3 => 1]
        $res['countBy'] = $counted->all();
        
        // 13. crossJoin 方法交叉连接指定数组或集合的值，返回所有可能排列的笛卡尔积
        $collection = collect([1, 2]);
        
        $matrix = $collection->crossJoin(['a', 'b']);
        /*
         [
         [1, 'a'],
         [1, 'b'],
         [2, 'a'],
         [2, 'b'],
         ]
         */
        $res['crossJoin'] = $matrix->all();
        
        // 15. diff 方法将集合与其它集合或者 PHP 数组进行值的比较。
        // 然后返回原集合中存在而指定集合中不存在的值
        $collection = collect([1, 2, 3, 4, 5]);
        
        $diff = $collection->diff([2, 4, 6, 8]);
        // 集合 差 [1, 3, 5]
        $res['diff'] = $diff->all();
        
        // 16. diffAssoc 方法与另外一个集合或基于 PHP 数组的键 / 值对（keys and values）进行比较。
        // 这个方法将会返回原集合不存在于指定集合的键 / 值对
        $collection = collect([
            'color' => 'orange',
            'type' => 'fruit',
            'remain' => 6
        ]);
        
        $diff = $collection->diffAssoc([
            'color' => 'yellow',
            'type' => 'fruit',
            'remain' => 3,
            'used' => 6,
        ]);
        // ['color' => 'orange', 'remain' => 6]
        $res['diffAssoc'] = $diff->all();
        
        // 17 diffKeys 方法和另外一个集合或 PHP 数组的键（keys）进行比较，
        // 然后返回原集合中存在而指定集合中不存在键所对应的键 / 值对
        $collection = collect([
            'one' => 10,
            'two' => 20,
            'three' => 30,
            'four' => 40,
            'five' => 50,
        ]);
        
        $diff = $collection->diffKeys([
            'two' => 2,
            'four' => 4,
            'six' => 6,
            'eight' => 8,
        ]);
        // ['one' => 10, 'three' => 30, 'five' => 50]
        $res['diffKeys'] = $diff->all();
        
        // 18. dump 方法用于打印集合项
        $collection = collect(['John Doe', 'Jane Doe']);
        /*
         Collection {
         #items: array:2 [
         0 => "John Doe"
         1 => "Jane Doe"
         ]
         }
         */
        $res['dump'] = $collection->dump();

        dump($res);
        
        // 14. dd 方法用于打印集合元素并中断脚本执行
        $collection = collect(['John Doe', 'Jane Doe']);
        /*
         Collection {
         #items: array:2 [
         0 => "John Doe"
         1 => "Jane Doe"
         ]
         }
         */
        $collection->dd();
    }
}