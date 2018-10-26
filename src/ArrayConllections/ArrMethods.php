<?php

namespace ArrayConllections;

class ArrMethods
{
    /**
     * @params $arr array
     * @return bool
     * 判断一个数组是否是一维数组
     */
    public static function isOneDimensionalArray($arr)
    {
        if(count($arr) == count($arr,1)){
            return true;
        }
        return false;
    }

    /**
     * @param $arr
     * @param $key
     * @return array
     * 根据key 给二维数组分组
     */
    public static function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach($arr as $v){
            $grouped[$v[$key]][] = $v;
        }

        if(func_num_args() > 2) {
            $args = func_get_args();
            foreach($grouped as $k => $v){
                $params = array_merge([$v], array_slice($args, 2, func_num_args()));
                $grouped[$k] = @call_user_func_array('array_group_by', $params);
            }
        }
        return $grouped;
    }

    /**
     * @param $array
     * @param $key
     * @return array
     * 获取二维数组中同一个key的value，返回得到的value组成的一维数组
     */
    public static function getQuery($array, $key){
        return array_column($array, $key);

        /* 或者使用array_map来解决
        $data=[];
        array_map(function($item)use(&$data, $key){
            $data[]=$item[$key];
        },$array);
        return $data;
        */

    }

    /**
     * @param $array
     * @return mixed
     * 二维数组中的一个【键的值】作为一维的 【键】
     * 示例：
     * 原数组:
     *          $arr = [
     *              0 => [
     *                  'id' => 200,
     *                  'name' => 'yes'
     *              ],
     *              1 => [
     *                  'id' => 300,
     *                  'name' => 'no
     *              ]
     *         ];
     * 经函数转换后:
     *          $arr = [
     *              200 => [
     *                  'id' => 200,
     *                  'name' => 'yes'
     *              ],
     *              300 => [
     *                  'id' => 300,
     *                  'name' => 'no'
     *              ]
     *         ];
     */
    public static function changeValueToKey($array, $key){
        return array_column($array, null, $key);

        /* 也可以用array_reduce来实现，但是不足的地方是无法将key传入array_reduce中
        $data = array_reduce($array, function(&$data, $v){
            $data[$v['id']] = $v;
            return $data;
        });
        return $data;
        */
    }

    /**
     * @param $arr
     * @return array
     * 将laravel model中get()->toArray()获取得到的数据转换成真正的数组，而不是对象数组
     */
    public static function getRealArrayFromModelGetToArray($arr){
        return array_map('get_object_vars', $arr);
    }

    /**
     * @param $preArr array 原数组，以此数组为基准，合并后返回
     * @param $nextArr array  要被合并的数组
     * @param $key1 string    以此key为基准
     * @param $key2 string    要合并的key
     * @return mixed array
     */
    public static function mergeArrFromKey($preArr, $nextArr, $key1, $key2){

        foreach($nextArr as $k => $v){

            if($preArr[$k][$key1] == $v[$key1]){
                $preArr[$k][$key2] = $v[$key2];
            } else {
                $preArr[$k][$key2] = '';
            }
        }
        return $preArr;
    }

    /**
     * @param $arr
     * @param $key1
     * @param $key2
     * @return array
     * 一个二维数组中相同的数据合并
     * 示例：
     * 原数组：
     * $arr = [
     *  [
     *      'id' => 1,
     *      'tags' => '位置好'
     *  ],
     *  [
     *      'id' => 1,
     *      'tags' => '近地铁'
     *  ],
     *  [
     *      'id' => 2,
     *      'tags' => '学校旁'
     *  ]
     * ]
     *
     * 转换后的数组
     * $arr = [
     *          'id' => 1,
     *          'tags' => [
     *              '位置好',
     *              '近地铁'
     *      ],
     *      [
     *          'id' => 2
     *          'tags' => ['学校旁']
     *      ]
     *  ]
     */
    public static function oneArrMergeSameData($arr, $key1, $key2){

        foreach($arr as $item){
            if( !isset($res[$item[$key1]])){
                $res[$item[$key1]] = $item;
            } else {
                $res[$item[$key1]][$key2] .= ','.$item[$key2];
            }
        }
        $res = array_values($res);
        foreach($res as $k => $v){
            if($v[$key2] != "" && strpos($v[$key2], ',') !== false){
                $a = explode(',', $res[$k][$key2]);
                $res[$k][$key2] = $a;
            } else {
                // 处理只有一个值的情况
                $res[$k]['new'][] = $v[$key2];
                $res[$k][$key2] = $res[$k]['new'];
                unset($res[$k]['new']);
            }
        }
        return $res;
    }
}
