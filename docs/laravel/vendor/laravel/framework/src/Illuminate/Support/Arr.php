<?php

namespace Illuminate\Support;

use ArrayAccess;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;

class Arr
{
    use Macroable;

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param  array  $array
     * @param  string  $key
     * @param  mixed  $value
     * @return array
     */
    public static function add($array, $key, $value)
    {
        // 如果给定的键在数组中不存在或数组被设置为 null ，
        if (is_null(static::get($array, $key))) {
            // 那么 Arr::add 函数将会把给定的键值对添加到数组中
            static::set($array, $key, $value);
        }

        // 否則會返回當前值
        return $array;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param  iterable  $array
     * @return array
     */
    public static function collapse($array)
    {
        $results = [];

        // 循環檢查參數
        foreach ($array as $values) {
            // 如數組中的元素可被創建為集合則獲取集合中所有元素
            if ($values instanceof Collection) {
                $values = $values->all();
            } 
            // 不為數組的元素直接跳過
            elseif (! is_array($values)) {
                continue;
            }

            // 整合到臨時數組中
            $results[] = $values;
        }

        // 合併臨時數組中的數組並返回合併後的數組
        return array_merge([], ...$results);
    }

    /**
     * Cross join the given arrays, returning all possible permutations.
     *
     * @param  iterable  ...$arrays
     * @return array
     */
    public static function crossJoin(...$arrays)
    {
        $results = [[]];

        // 遍歷數組中的每一個數組
        foreach ($arrays as $index => $array) {
            $append = [];
            // 重新組合
            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;

                    $append[] = $product;
                }
            }

            $results = $append;
        }

        return $results;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param  array  $array
     * @return array
     */
    public static function divide($array)
    {
        // 將數組中的值和鍵分別生成數組組成新的數組返回
        return [array_keys($array), array_values($array)];
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  iterable  $array
     * @param  string  $prepend
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public static function except($array, $keys)
    {
        static::forget($array, $keys);

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param  iterable  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public static function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            // 未定義回調且數組為空則返回默認
            if (empty($array)) {
                return value($default);
            }

            // 否則返回第一個元素
            foreach ($array as $item) {
                return $item;
            }
        }

        // 定義回調，知性回調後返回符合要求元素
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return value($default);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public static function last($array, callable $callback = null, $default = null)
    {
        // 如未定義回調則返回數組中的最後一個元素
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }

        // 如定義回調，則數組轉為相反順序後返回符合要求的第一個元素，即符合要求的最後一個元素
        return static::first(array_reverse($array, true), $callback, $default);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  iterable  $array
     * @param  int  $depth
     * @return array
     */
    public static function flatten($array, $depth = INF)
    {
        $result = [];

        foreach ($array as $item) {
            $item = $item instanceof Collection ? $item->all() : $item;

            if (! is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : static::flatten($item, $depth - 1);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     * 使用“点”符号从给定数组中删除一个或多个数组项。
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return void
     */
    public static function forget(&$array, $keys)
    {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            // 如為指定刪除的元素則刪除
            if (static::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            // clean up before each pass
            $array = &$original;
            // 逐級搜索指定刪除的鍵值
            while (count($parts) > 1) {
                // 去除第一個，並從鍵值數組中去除，避免重複查找
                $part = array_shift($parts);
                
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            // 刪除找到的鍵值
            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        // 确定给定值是否可访问数组,不是返回默認值
        if (! static::accessible($array)) {
            return value($default);
        }

        // 未指定返回元素，則返回數組
        if (is_null($key)) {
            return $array;
        }

        // 如鍵值直接存在於數組中澤直接返回，不再逐級尋找
        if (static::exists($array, $key)) {
            return $array[$key];
        }

        // 逐級下找到指定元素並返回
        if (strpos($key, '.') === false) {
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public static function has($array, $keys)
    {
        $keys = (array) $keys;

        if (! $array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            // 确定给定键在提供的数组中是否存在,存在則找下一個
            if (static::exists($array, $key)) {
                continue;
            }

            // 深層級查找
            foreach (explode('.', $key) as $segment) {
                // 搜尋層級為可訪問數組且存在於數組中時算找到
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Determine if any of the keys exist in an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public static function hasAny($array, $keys)
    {
        if (is_null($keys)) {
            return false;
        }

        $keys = (array) $keys;

        if (! $array) {
            return false;
        }

        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            if (static::has($array, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param  array  $array
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        // 如果数组没有以零开头的连续数字键，则它是“关联的”
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public static function only($array, $keys)
    {
        // 从给定数组中获取项的子集
        return array_intersect_key($array, array_flip((array) $keys));
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param  iterable  $array
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    public static function pluck($array, $value, $key = null)
    {
        $results = [];

        // 展开传递给“ pluck”的“值”和“键”参数。
        [$value, $key] = static::explodePluckParameters($value, $key);

        foreach ($array as $item) {
            // 獲取指定元素的值的集合
            $itemValue = data_get($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            // 如未定義鍵名，則直接返回值集合
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                // 獲取指定鍵值的元素集合
                $itemKey = data_get($item, $key);

                // 指定鍵和值重新組合為新的集合返回
                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string) $itemKey;
                }

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    protected static function explodePluckParameters($value, $key)
    {
        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    }

    /**
     * Push an item onto the beginning of an array.
     *
     * @param  array  $array
     * @param  mixed  $value
     * @param  mixed  $key
     * @return array
     */
    public static function prepend($array, $value, $key = null)
    {
        // 如為指定插入值的鍵名，則直接插入給定數組的頭部
        if (is_null($key)) {
            array_unshift($array, $value);
        } 
        // 否則在頭部插入指定鍵名的值
        else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param  array  $array
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function pull(&$array, $key, $default = null)
    {
        // A. 獲取指定鍵值
        $value = static::get($array, $key, $default);

        // B. 刪除指定鍵值
        static::forget($array, $key);

        // C. 返回
        return $value;
    }

    /**
     * Get one or a specified number of random values from an array.
     *
     * @param  array  $array
     * @param  int|null  $number
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function random($array, $number = null)
    {
        // 沒有設置返回數量默認返回一個元素
        $requested = is_null($number) ? 1 : $number;

        $count = count($array);

        // 驗證，隨機取出元素大於數組元素喝則拋出異常
        if ($requested > $count) {
            throw new InvalidArgumentException(
                "You requested {$requested} items, but there are only {$count} items available."
            );
        }

        // 未設置返回數量則從數組中隨機返回一個元素
        if (is_null($number)) {
            return $array[array_rand($array)];
        }

        // 當設置返回數為0時返回空數組
        if ((int) $number === 0) {
            return [];
        }

        // 返回設定數量的隨機數組鍵名，並返回含隨機鍵值的集合
        $keys = array_rand($array, $number);

        $results = [];

        foreach ((array) $keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array  $array
     * @param  string|null  $key
     * @param  mixed  $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        // 當未設置設置鍵名時，直接返回設置值
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            // 當層級只有一層時，跳出循環
            if (count($keys) === 1) {
                break;
            }

            // 刪除當前層級，避免重複查詢
            unset($keys[$i]);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            // 如查詢層級不存在則創建
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }
            
            // 設置層級
            $array = &$array[$key];
        }

        // 設置值並返回
        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Shuffle the given array and return the result.
     *
     * @param  array  $array
     * @param  int|null  $seed
     * @return array
     */
    public static function shuffle($array, $seed = null)
    {
        // 將數組隨機排序
        if (is_null($seed)) {
            shuffle($array);
        } 
        // 設置 seed 則使用隨機數生成器
        else {
            // mt_srand() 函数播种 Mersenne Twister 随机数生成器
            mt_srand($seed);
            shuffle($array);
            mt_srand();
        }

        return $array;
    }

    /**
     * Sort the array using the given callback or "dot" notation.
     *
     * @param  array  $array
     * @param  callable|string|null  $callback
     * @return array
     */
    public static function sort($array, $callback = null)
    {
        // 升序排序
        // 可根據數組中的某個鍵值排序
        return Collection::make($array)->sortBy($callback)->all();
    }

    /**
     * Recursively sort an array by keys and values.
     *
     * @param  array  $array
     * @return array
     */
    public static function sortRecursive($array)
    {
        // 對数值子数组进行递归排序
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::sortRecursive($value);
            }
        }

        // 關聯數組使用倒序
        if (static::isAssoc($array)) {
            ksort($array);
        } 
        // 非關聯數組使用降序
        else {
            sort($array);
        }

        return $array;
    }

    /**
     * Convert the array into a query string.
     *
     * @param  array  $array
     * @return string
     */
    public static function query($array)
    {
        // 将数组转换为查询字符串
        return http_build_query($array, null, '&', PHP_QUERY_RFC3986);
    }

    /**
     * Filter the array using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     */
    public static function where($array, callable $callback)
    {
        // 根據條件過濾數組
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    public static function wrap($value)
    {
        // 將字符串轉為數組
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }
}
