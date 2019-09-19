<?php
namespace poker;

class Poker
{

	public $poker;

	/**
	 * 计算牛几
	 * 比较大小
	 * 
	 * 初始化一副牌并打乱顺序
	 */
	public function __construct()
	{
		$this->poker = range(0,51);
		shuffle($this->poker);
	}

	// 发到第几张
	private $index = 0;

	public $numToWord = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

	// 发N张牌
	public function produce($num)
	{
		$poker = array_slice($this->poker, $this->index, $num);
		$this->index += $num;
		return $poker;
	}

	/**
	 * 转化牌面
	 */
	public function indexToPoker($index)
	{
		// 花 0方片 1梅花 2红桃 3黑桃
		$num = floor($index / 4);
		$word = $this->numToWord[$num];
		return [$num + 1, $index % 4, $word];
	}

	/**
	 * 组合算法
	 */
	protected function strand($val, $num)
    {
      $rs = [];
      for ($i = 0; $i < pow(2, count($val)); $i ++){
          $a = 0;
          $b = [];
          for ($j = 0; $j < count($val); $j ++){
              if ($i >> $j & 1){
                  $a ++;
                  $b[] = $val[$j];
              }
          }
          if ($a == $num){
              $rs[] = $b;
          }
      }
      return $rs;
    }

}