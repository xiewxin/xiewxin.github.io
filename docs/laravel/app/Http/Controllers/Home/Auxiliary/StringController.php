<?php

namespace App\Http\Controllers\Home\Auxiliary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        
        // 3. e 函数在指定字符串上运行 htmlentities 方法（double_encode 参数为 false）
        // &lt;html&gt;foo&lt;/html&gt;
        $res['e'] = e('<html>foo</html>');    
        
        // 4. preg_replace_array 函数使用正则规则用给定数组替换字符串中的内容：
        $string = 'The event will take place between :start and :end';
        
        $res['preg_replace_array'] = preg_replace_array('/:[a-z_]+/', ['8:30', '9:00'], $string);
        
        // 5. Str::after 方法返回字符串中给定值之后的所有内容。
        // 如果字符串中不存在该值，则将返回整个字符串
        // my name
        $res['str_after'] = Str::after('This is my name', 'This is');
        
        // 6. Str::afterLast 方法返回字符串中给定值最后一次出现后的所有内容。
        // 如果字符串中不存在该值，则将返回整个字符串
        // ontroller
        $res['str_afterLast'] = Str::afterLast('App\Http\Controllers\Controller', 'C');
        
        // 7. Str::before 方法返回字符串中给定值之前的所有内容
        // This is 
        $res['str_before'] = Str::before('This is my name', 'my name');
        
        // 8. Str::beforeLast 方法返回字符串中给定值最后出现之前的所有内容
        // This 
        $res['str_beforeLast'] = Str::beforeLast('This is my name', 'is');
        
        // 9. Str::camel 方法将给定字符串转换为 camelCase（驼峰式）
        // fooBar
        $res['str_camel'] = Str::camel('foo_bar');
        
        // 10. Str::contains 方法判断给定的字符串是否包含给定的值（区分大小写）
        // true
        $res['str_contains'][] = Str::contains('This is my name', 'my');
        // 10.1 你也可以传递一个数组形式的值来判断给定的字符串中是否包含数组中的任意一个值
        // true
        $res['str_contains'][] = Str::contains('This is my name', ['my', 'foo']);
        
        // 11. Str::containsAll 方法判断给定的字符串中是否包含给定的数组中所有的值
        // true
        $res['str_containsAll'] = Str::containsAll('This is my name', ['my', 'name']);
        
        // 12. Str::endsWith 方法判断给定的字符串是否以给定的值结尾
        // true
        $res['str_endsWith'][] = Str::endsWith('This is my name', 'name');
        // 12.1 你也可以传递一个数组形式的值来判断给定的字符串是否以数组中的任意一个值结尾
        // false
        $res['str_endsWith'][] = Str::endsWith('This is my name', ['this', 'foo']);
        
        // 13. Str::finish 方法将给定的字符串以给定的值结尾返回（如果该字符串尚未以该值结尾）
        // this/string/
        $res['str_finish'] = Str::finish('this/string', '/');
        
        // 14. Str::is 方法判断给定的字符串是否匹配给定的模式，星号 * 可以用来表示通配符
        // true
        $res['str_is'] = Str::is('foo*', 'foobar');
        
        // 15. Str::ucfirst 方法将给定的字符串首字母大写并返回
        // Foo bar
        $res['str_ucfirst'] = Str::ucfirst('foo bar');
        
        // 16. Str::isUuid 方法用来确定给定的字符串是否为有效的 UUID
        // true
        $res['str_isUuid'] = Str::isUuid('a0a2a2d2-0b87-4a18-83f2-2529882be2de');
        
        // 17. Str::kebab 方法将给定的「驼峰式」字符串转化为 kebab-case「短横式」字符串
        // foo-bar
        $res['str_kebab'] = Str::kebab('fooBar');
        
        // 18. Str::limit 方法按给定的长度截断给定的字符串
        // The quick brown fox...
        $res['str_limit'][] = Str::limit('The quick brown fox jumps over the lazy dog', 20);
        // 18.1 你也可以传递第三个参数来改变将被追加到最后的字符串
        // The quick brown fox (...)
        $res['str_limit'][] = Str::limit('The quick brown fox jumps over the lazy dog', 20, ' (...)');
        
        // 19. Str::orderedUuid 方法高效生成一个可存储在索引数据库列中的「第一时间」 UUID
        $res['str_orderedUuid'] = Str::orderedUuid();
        
        // 20. Str::plural 函数将字符串转换为复数形式。该函数目前仅支持英文
        // cars
        $res['str_plural'][] = Str::plural('car');
        // 20.1 你可以提供一个整数作为函数的第二个参数来检索字符串的单数或复数形式
        // children
        $res['str_plural'][] = Str::plural('child', 2);
        
        // 21. Str::random 函数生成一个指定长度的随机字符串。这个函数用 PHP 的 random_bytes 函数
        $res['str_random'] = Str::random(40);
        
        // 22. Str::replaceArray 函数使用数组顺序替换字符串中的给定值
        $string = 'The event will take place between ? and ?';
        // The event will take place between 8:30 and 9:00
        $res['str_replaceArray'] = Str::replaceArray('?', ['8:30', '9:00'], $string);
        
        // 23. Str::replaceFirst 函数替换字符串中给定值的第一个匹配项
        // a quick brown fox jumps over the lazy dog
        $res['str_replaceFirst'] = Str::replaceFirst('the', 'a', 'the quick brown fox jumps over the lazy dog');
        
        // 24. Str::replaceLast 函数替换字符串中最后一次出现的给定值
        // the quick brown fox jumps over a lazy dog
        $res['str_replaceLast'] = Str::replaceLast('the', 'a', 'the quick brown fox jumps over the lazy dog');

        // 25. Str::singular 函数将字符串转换为单数形式。该函数目前仅支持英文
        // car
        $res['str_singular'][] = Str::singular('cars');
        // child
        $res['str_singular'][] = Str::singular('children');
        
        // 26. Str::slug 函数将给定的字符串生成一个 URL 友好的 「slug」
        // 可用於定義函數名
        // laravel-5-framework
        $res['str_slug'] = Str::slug('Laravel 5 Framework', '-');
        
        // 27. Str::snake 函数将给定的字符串转换为 snake_case「蛇式」
        // foo_bar
        $res['str_snake'] = Str::snake('fooBar');
        
        // 28. Str::start 函数将给定值添加到给定字符串的开始位置（如果字符串尚未以给定值开始）
        // /this/string
        $res['str_start'][] = Str::start('this/string', '/');
        // /this/string
        $res['str_start'][] = Str::start('/this/string', '/');
       
        // 29. Str::startsWith 函数判断给定的字符串的开头是否是指定值
        // true
        $res['str_startsWith'] = Str::startsWith('This is my name', 'This');
        
        // 30. Str::studly 函数将给定的字符串转换为 「变种驼峰命名」
        // FooBar
        $res['str_studly'] = Str::studly('foo_bar');
        
        // 31. Str::title 函数将给定的字符串转换为「首字母大写」
        // A Nice Title Uses The Correct Case
        $res['str_title'] = Str::title('a nice title uses the correct case');
        
        // 32. Str::uuid 方法生成一个 UUID（版本 4）
        // 75c0a1f7-fb55-4a3c-af41-28f226e74f7e
        $res['str_uuid'] = (string) Str::uuid();
        
        // 33. Str::words 函数限制字符串中的单词数
        // Perfectly balanced, as >>>
        $res['str_words'] = Str::words('Perfectly balanced, as all things should be.', 3, ' >>>');
        
        // 34. trans 函数使用你的 本地文件 转换给定的翻译密钥
        // 翻譯配置文件/xiewxin.github.io/docs/laravel/resources/lang/en.json
        // test
        $res['trans'] = trans('messages.welcome');
        
        // 35. trans_choice 函数根据词形变化来翻译给定的翻译键
        $res['trans_choice'] = trans_choice('messages.notifications', 3);
        
        dump($res);
    }
}