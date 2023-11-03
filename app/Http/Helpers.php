<?php

/**
 * 集合按照指定欄位去正序/倒序排列，這function僅限於該表有存在的欄位，需要排序關聯表的無法使用此function
 * @param collection $query 要排序的集合(集合要先用query()裝起來不要get())
 * @param string  $request_rules 要排序的欄位陣列(例: ['name' => '1'])
 */
function columnSort($query, $request_rules)
{
    if (!is_array($request_rules)) return $query;
    // 如果要排序，只會傳一個key value過來，此function回傳也只一筆
    $exist_rule = array_filter($request_rules, fn ($rule) => $rule);
    // 抓key name
    $column = key($exist_rule);
    // 如果有key，意即有$request->sortXXXXXXXXXXXXX，則以此key與其value正向/反向排序
    if (!$column) return $query;
    $query->orderBy($column, $exist_rule[$column] === '1' ? 'asc' : 'desc');
}

/**
 * 集合按照指定欄位JSON中的KEY去正序排列
 * @param collection $query 要排序的集合(集合要先用query()裝起來不要get())
 * @param string $column 要排序的欄位
 * @param string $key 欄位JSON要排序的KEY
 * @param string $sort 排序的方式(正序: asc、倒敘: desc)
 */
function jsonSort($query, $column, $key, $sort = 'asc')
{
    $type = ['asc', 'desc'];
    if (!is_object($query) || !is_string($column) || !is_string($key) || !$query?->count() || !in_array($sort, $type)) return $query;
    $rule = '/[:+?=*!\'\/\-\s(?=)]/';
    $clean_column = preg_replace($rule, '', $column);
    $clean_key = preg_replace($rule, '', $key);
    // orderByRaw() 方法可被用於設定一個原生字串作為 order by 子句的值 ，例:orderByRaw('(updated_at - created_at) desc') ， 正序: asc、倒序: desc
    // JSON_EXTRACT 可抓取在資料中json的值 例: JSON_EXTRACT('存放json的欄位','$.json的key') ， JSON_EXTRACT() 是JSON提取函數，例:$.keyname 就是一個 JSON path
    // CONVERT(value, type) 將value轉換為type數據型別，例:CONVERT(150, CHAR);
    // 或是CONVERT(value USING charset) charset為要轉換為的字符編碼 (latin1、UTF8、big5...)
    $query->orderByRaw("CONVERT(JSON_VALUE({$clean_column}, '$.{$clean_key}') using big5) {$sort}");
}

/**
 * 回傳給前端固定格式的物件
 * @param mixed $rt_data 要回傳給前端的資料
 * @param integer $rt_code 要回傳給前端的結果代碼(0:失敗；1:成功)
 * @param string $rt_message 要回傳給前端的結果訊息
 * @return object 回傳固定格式的物件
 */
function rtFormat($data = [], $code = 1, $message = '執行成功')
{
    $status = [0, 1];
    if (!is_int($code) || !in_array($code, $status) || !is_string($message)) {
        throw new Exception('rt_format函式傳入參數錯誤');
    }

    $format = (object) [
        'rt_code' => $code,
        'rt_message' => $message,
        'rt_data' => $data,
    ];

    return $format;
}
