<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\model;

use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = false;
    protected $dateFormat         = false;
}
