<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-4
 * Time: 15:04
 */

namespace app\admin\model;


use think\Model;

class Products extends Model
{
    public $tableName  ='products';

    public function getProducts(){
        $products = $this->select()->toArray();
        $finalRes = array();
        foreach($products as $key => $product){
            $data = $this->formateProductOutput($product);
            $finalRes[$key] = $data;
        }
        return $finalRes;
    }

    public function formateProductOutput($product){
        $data = array();
        $data['id'] = (array_key_exists('id', $product)) ? $product['id']:'';
        $data['name'] = (array_key_exists('name', $product)) ? $product['name']:'';
        $data['description'] = (array_key_exists('description', $product)) ? $product['description']:'';
        $data['image'] = (array_key_exists('image', $product)) ? $product['image']:'';
        $data['type'] = (array_key_exists('type', $product)) ? $product['type']:'';
        $data['qty'] = (array_key_exists('qty', $product)) ? $product['qty']:0;
        $data['price'] = (array_key_exists('price', $product)) ? $product['price']:0.00;
        $data['currencyType'] = (array_key_exists('currencyType', $product)) ? $product['currencyType']:'';
        return $data;
    }

    public function saveProduct($data){
        $res = $this->insert($data);
        return $res;
    }
    public function getProduct($id){
        $product = $this->where('id', $id)->find()->toArray();
        $res = $this->formateProductOutput($product);
        return $res;
    }
    public function updateProduct($id, $data){
        $res = $this->where('id', $id)->update($data);
        return $res;
    }
    public function deleteProduct($id){
        $res = $this->where('id', $id)->delete();
        return $res;
    }
}