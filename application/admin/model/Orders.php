<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-4
 * Time: 15:04
 */

namespace app\admin\model;


use think\Model;
use \app\admin\model\Users as MUsers;

class Orders extends Model
{
    public $tableName  ='orders';

    public function formateOrderOutput($order, $userId){
        $data = array();
        $mUser = new MUsers();
        $data['id'] = (array_key_exists('id', $order)) ? $order['id']:'';
        if($userId){
            $uData = $mUser->getById($userId);
            if(!$uData){return $data;}
            $data['uid'] = $userId;
            $data['name'] = (array_key_exists('name', $uData)) ? $uData['name']:'';
            $data['cardNum'] = (array_key_exists('cardNum', $uData)) ? $uData['cardNum']:'';
            $data['score'] = (array_key_exists('score', $uData)) ? $uData['score']:0;
        }
        $data['type'] = (array_key_exists('type', $order)) ? $order['type']:'';
        $data['prodType'] = (array_key_exists('prodType', $order)) ? $order['prodType']:'';
        $data['prodName'] = (array_key_exists('prodName', $order)) ? $order['prodName']:'';
        $data['qty'] = (array_key_exists('qty', $order)) ? $order['qty']:0;
        $data['amount'] = (array_key_exists('amount', $order)) ? $order['amount']:0.00;
        $data['state'] = (array_key_exists('state', $order)) ? $order['state']:0.00;
        $data['paymentType'] = (array_key_exists('paymentType', $order)) ? $order['paymentType']:'';
        $data['paymentId'] = (array_key_exists('paymentId', $order)) ? $order['paymentId']:'';
        $data['createTime'] = (array_key_exists('createTime', $order)) ? date('Y-m-d H:i:s', $order['createTime']/1000):'';
        return $data;
    }

    /**取得用户的订单 */
    public function getUserOrders($userId, $isProcessed){
        $filter = [ 'userId' => $userId ];
        $finalRes = array();
        $userOrders = $this->where($filter)->find();
        if(!$userOrders){return $finalRes;}
        $userOrders = $userOrders->toArray();
        $finalRes = $this->getOrdersFromUserData(null, $userOrders, $isProcessed);
        return $finalRes;
    }
    /**从用户的数据中提取订单 */
    public function getOrdersFromUserData($userId, $userOrders, $isProcessed){
        $finalRes = array();
        $orders = (array_key_exists('orders', $userOrders)) ? $userOrders['orders']: array();
        foreach($orders as $key => $order){
            if( $isProcessed){
                if($order['state'] == 'PROCESSED'){
                    $data = $this->formateorderOutput($order,$userId);
                    $finalRes[$key] = $data;
                }
            }else{
                if($order['state'] == 'PENDING' || $order['state'] == 'APPROVED'){
                    $data = $this->formateorderOutput($order,$userId);
                    $finalRes[$key] = $data;
                }
       
            }
        }
        return $finalRes;
    }

    /** 取得全部用户的订单 */
    public function getAllUsersOrders($isProcessed){
        $finalRes = array();
        $usersOrders = $this->select();
        if(!$usersOrders){return $finalRes;}
        $usersOrders = $usersOrders->toArray();
        foreach($usersOrders as $key => $userOrders){
            $tempOrders = $this->getOrdersFromUserData($userOrders['userId'],$userOrders,$isProcessed);
            $finalRes = array_merge($finalRes,$tempOrders);
        }
        return $finalRes;
    }

    public function getRechargeSum($orders){
        $rechargeSum = 0;
        foreach($orders as $order){
            if(($order['type'] == 'VIPRECHARGE' || $order['type'] == 'RECHARGE') && $order['state'] == 'PROCESSED'){
                $rechargeSum += $order['amount'];
            }
        }
        return  $rechargeSum;
    }

    public function getWithdrawSum($orders){
        $withdrawSum = 0;
        foreach($orders as $order){
            if($order['type'] == 'WITHDRAW'  && $order['state'] == 'PROCESSED'){
                $withdrawSum += abs($order['amount']);
            }
        }
        return  $withdrawSum;
    }

    public function getCoinRechargeSum($orders){
        $rechargeSum = 0;
        foreach($orders as $order){
            if($order['prodType'] != "COIN"){continue;}
            if(($order['type'] == 'VIPRECHARGE' || $order['type'] == 'RECHARGE') && $order['state'] == 'PROCESSED'){
                $rechargeSum += $order['amount'];
            }
        }
        return  $rechargeSum;
    }

    public function getBalance($orders) {
		return $this->getCoinRechargeSum($orders) - $this->getWithdrawSum($orders);
    }
    
    public function createUserOrdersEntity($uid){
        return [
            'userId' => $uid,
            'balance' => 0,
            'rechargeSum' => 0,
            'withdrawSum' => 0,
        ];
    }

    public function createOrderEntity($type, $prodType, $amount){
        return [
            'id' => getUniqueOrderId(),
            'type' => $type,
            'prodType' => $prodType,
            'prodName' => '手工操作',
            'qty' => 1,
            'amount' => $amount,
            'state' => 'PROCESSED',
            'paymentType' => 'OFFLINE',
            'paymentId' => '',
            'createTime' => currentMillis(),
        ];
    }

    public function updateUserData($uid, $data){
        $result = 1;
        if($data['state'] == 'PROCESSED'){
            $mUser = new MUsers();
            $uData = $mUser->getById($uid);
            $uData['id'] = $uid;
            if($data['type'] == 'VIPRECHARGE' || $data['type'] == 'RECHARGE'){
                if($data['prodType'] == 'CARD'){
                    $uData['cardNum'] = (array_key_exists('cardNum', $uData)) ? $uData['cardNum'] + $data['amount'] : $data['amount'];
                }elseif ($data['prodType'] == 'COIN'){
                    $uData['score'] = (array_key_exists('score', $uData)) ? $uData['score'] + $data['amount'] : $data['amount'];
                }
            }elseif($data['type'] == 'WITHDRAW'){
                if($data['prodType'] == 'CARD'){
                    $uData['cardNum'] = (array_key_exists('cardNum', $uData)) ? $uData['cardNum'] - $data['amount'] : $data['amount'];
                }elseif ($data['prodType'] == 'COIN'){
                    $uData['score'] = (array_key_exists('score', $uData)) ? $uData['score'] - $data['amount'] : $data['amount'];
                }
            }
            $result = $mUser->updateById($uData);
        }
        return $result;
    }
    /**
     * 加入提佣记录
     */
    public function saveOrder($uid, $data){
        $isNew = true;
        $filter = [ 'userId' => $uid ];
        $userOrders = $this->where($filter)->find();
        if(!$userOrders){$userOrders = $this->createUserOrdersEntity($uid);}
        else{
            $userOrders = $userOrders->toArray();
            $isNew = false;
        }
        $orders = (array_key_exists('orders', $userOrders)) ? $userOrders['orders']: array();
        array_push($orders, $data);
        //更新库
        $userOrders['orders'] = $orders;
        if($isNew){
            $result = $this->insert($userOrders);
        }else{
            $result = $this->where($filter)->update($userOrders);
        }
        //如果出错返回
        if(!$result){return $result;}
        //改变用户对应的钻石和积分的金额
        $result = $this->updateUserData($uid, $data);
        return $result;
    }

    /**
     * 更新提佣记录
     */
    public function updateOrder($uid, $data){
        $filter = [ 'userId' => $uid ];
        $userOrders = $this->where($filter)->find()->toArray();
        $orders = (array_key_exists('orders', $userOrders)) ? $userOrders['orders']: array();
        foreach($orders as $key => $order){
            if($order['id'] == $data['id']){
                $data['createTime'] = $order['createTime'];
                $orders[$key] = $data;
                break;
            }
        }
        $userOrders['orders'] = $orders;
        $result = $this->where($filter)->update($userOrders);
        //如果出错返回
        if(!$result){return $result;}
        //改变用户对应的钻石和积分的金额
        $result = $this->updateUserData($uid, $data);
        return $result;
    }

    /**
     * 取得指定的记录
     */
    public function getOrder($uid, $oid){
        $filter = [ 'userId' => $uid ];
        $userOrders = $this->where($filter)->find()->toArray();
        $finalRes = array();
        $orders = (array_key_exists('orders', $userOrders)) ? $userOrders['orders']: array();
        if(sizeof($orders) != 0){
            foreach($orders as $item){
                if($item['id'] == $oid){
                    $finalRes['oid'] = $item['id'];
                    $finalRes['uid'] = $uid;
                    $finalRes['type'] = (array_key_exists('type', $item)) ? $item['type']:'';
                    $finalRes['prodType'] = (array_key_exists('prodType', $item)) ? $item['prodType']:'';
                    $finalRes['prodName'] = (array_key_exists('prodName', $item)) ? $item['prodName']:'';
                    $finalRes['qty'] = (array_key_exists('qty', $item)) ? $item['qty']:0;
                    $finalRes['amount'] = (array_key_exists('amount', $item)) ? $item['amount']:0;
                    $finalRes['state'] = (array_key_exists('state', $item)) ? $item['state']:'';
                    $finalRes['paymentType'] = (array_key_exists('paymentType', $item)) ? $item['paymentType']:'';
                    $finalRes['paymentId'] = (array_key_exists('paymentId', $item)) ? $item['paymentId']:'';
                    $finalRes['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
                    break;
                }
                
            }
        }
        return $finalRes;
    }

    /**
     * 删除订单记录
     */
    public function deleteOrder($uid, $oid){
        $filter = [ 'userId' => $uid ];
        $userOrders = $this->where($filter)->find()->toArray();
        $orders = (array_key_exists('orders', $userOrders)) ? $userOrders['orders']: array();
        foreach($orders as $key => $item){
            if($item['id'] == $oid && $item['state'] != 'PROCESSED'){
                unset($orders[$key]);
                break;
            }
        }
        $userOrders['orders'] = $orders;
        $result = $this->where($filter)->update($userOrders);
        return $result;
    }

}