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
}
