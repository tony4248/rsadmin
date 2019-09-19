<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-17
 * Time: 11:55
 */

namespace app\dest\controller;


class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}