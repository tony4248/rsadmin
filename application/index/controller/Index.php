<?php
namespace app\index\controller;

use GatewayClient\Gateway;

class Index
{
    public function index()
    {   
        Gateway::$registerAddress = '127.0.0.1:1238';
        Gateway::sendToUid(2, '阿诗丹顿所多所');
        echo '前台首页';
    }
}
