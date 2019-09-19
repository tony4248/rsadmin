<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-10
 * Time: 17:30
 */

namespace app\admin\controller;

use app\admin\model\Users as MUsers;
use app\admin\model\Agents as MAgents;
use app\admin\model\Orders as MOrders;
use app\admin\model\Products as MProducts;
use GatewayClient\Gateway;
use think\Config;
use think\Db;
use think\Loader;

/**
 * Class Shop
 * @package app\admin\controller
 * 用户处理类
 */
class Shop extends Base
{

    /**
     * 产品列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $products = new MProducts();
        $finalRes = $products->getProducts();
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

     /**
     * 增加商品
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function add_product($name = '', $description = '', $image = '', $type = '', $qty = '', $price = '', $currencyType = '')
    {
        if (request()->isAjax()) {
            $data = ['id' => generateRandomInts(6), 
                    'name' => $name, 
                    'description' => $description, 
                    'image' => $image, 
                    'type' => $type, 
                    'qty' => $qty, 
                    'price' => $price, 
                    'currencyType' => $currencyType];
            $validate = validate('Product');
            if (!$validate->scene('add')->check($data)) {
                return ['code' => 0, 'msg' => $validate->getError()];
            }
            $product = new MProducts();
            //更新代理层级数据
            $result = $product->saveProduct($data);
            if ($result) {
                return ['code' => 1, 'msg' => '商品添加成功'];
            } else {
                return ['code' => 0, 'msg' => '商品添加失败'];
            }
        }
        return $this->fetch();
    }

    /**
     * 编辑商品
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function edit_product($id = '', $name = '', $description = '', $image = '', $type = '', $qty = '', $price = '', $currencyType = '')
    {
        if (request()->isAjax()) {
            $data = ['name' => $name, 
                    'description' => $description, 
                    'image' => $image, 
                    'type' => $type, 
                    'qty' => $qty, 
                    'price' => $price, 
                    'currencyType' => $currencyType];
            $validate = validate('Product');
            if (!$validate->scene('add')->check($data)) {
                return ['code' => 0, 'msg' => $validate->getError()];
            }
            $product = new MProducts();
            //更新代理层级数据
            $result = $product->updateProduct($id, $data);
            if ($result) {
                return ['code' => 1, 'msg' => '商品修改成功'];
            } else {
                return ['code' => 0, 'msg' => '商品修改失败'];
            }
        }
        $product = new MProducts();
        $finalRes = $product->getProduct($id);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

    /**
     * 删除商品
     */
    public function del_product($id = '')
    {
        $product = new MProducts();
        $result = $product ->deleteProduct($id);
        if ($result == 1) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }

     /**
     * 订单记录
     * @param string $userId
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function order_records($userId = '')
    {
        $orders = new MOrders();
        $finalRes = $orders->getUserOrders($userId,true);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

     /**
     * 订单管理
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function orders()
    {
        $orders = new MOrders();
        $finalRes = $orders->getAllUsersOrders(false);
        $this->assign('page', null);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

    
    public function validateSaveOrder($uid = '', $type = '', $prodType = '', $amount = 0){
        if (!is_numeric($amount) || $amount <=0 ) {
            return ['code' => 0, 'msg' => '金额不能为负数,零'];
        }
        if($type == 'WITHDRAW' && $prodType != 'COIN'){
            return ['code' => 0, 'msg' => '只有积分/金币可提现'];
        }
        //检查用户ID
        $user = new MUsers();
        $userData = $user->getById($uid);
        if (!$userData) {
            return ['code' => 0, 'msg' =>'用户Id无效,请重新输入'];
        }
        //检查金额
        if($type == 'WITHDRAW'){
            $score = (array_key_exists('score', $userData)) ? $userData['score']:'';
            if($score == 0){
                return ['code' => 0, 'msg' =>'该用户尚无可提余额'];
            }elseif($score < $amount){
                return ['code' => 0, 'msg' =>'输入的金额大于可用余额'];
            }
        }
        return null;
    }

    /**
     * 增加提佣记录
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function add_order($uid = '', $type = '', $prodType = '', $prodName = '',  $amount = '', $paymentType = '', $paymentId = '', $state = '')
    {
        if (request()->isAjax()) {
            $data = ['id' => getUniqueOrderId(), 
                    'type' => $type, 
                    'prodType' => $prodType, 
                    'prodName' => $prodName,
                    'qty' => 1, 
                    'amount' => $amount, 
                    'paymentType' => $paymentType, 
                    'paymentId' => $paymentId, 
                    'state' => $state, 
                    'createTime' => currentMillis()];
            $validateRes = $this->validateSaveOrder($uid, $type,  $prodType, $amount);
            if($validateRes){return $validateRes;}
            $order = new MOrders();
            $result = $order->saveOrder($uid,$data);
            if ($result) {
                return ['code' => 1, 'msg' => '订单添加成功'];
            } else {
                return ['code' => 0, 'msg' => '订单添加失败'];
            }
        }
        return $this->fetch();
    }

    /**
     * 编辑订单记录
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function edit_order($uid = '', $oid = '', $type = '', $prodType = '', $prodName = '', $qty = 0,  $amount = 0, $paymentType = '', $paymentId = '', $state = '')
    {
        if (request()->isAjax()) {
            $data = ['id' => $oid, 
                'type' => $type, 
                'prodType' => $prodType,
                'prodName' => $prodName,
                'qty' => $qty, 
                'amount' => $amount, 
                'paymentType' => $paymentType, 
                'paymentId' => $paymentId, 
                'state' => $state];
            $validateRes = $this->validateSaveOrder($uid, $type, $prodType, $amount);
            if($validateRes){return $validateRes;}
            $order = new MOrders();
            $result = $order->updateOrder($uid,$data);
            if ($result) {
                return ['code' => 1, 'msg' => '订单修改成功'];
            } else {
                return ['code' => 0, 'msg' => '订单修改失败,请确保余额充足'];
            }
        }
        $order = new MOrders();
        //更新代理层级数据
        $finalRes = $order->getOrder($uid, $oid);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }
    /**
     * 删除订单记录
     */
    public function del_order($uid = '', $oid = '')
    {
        $order = new MOrders();
        $result = $order ->deleteOrder($uid, $oid);
        if ($result == 1) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }
}