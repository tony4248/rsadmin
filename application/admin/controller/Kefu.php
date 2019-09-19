<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-11-6
 * Time: 17:56
 */

namespace app\admin\controller;

use app\admin\model\Kefus as MKefus;
use think\Config;
use think\Db;

class Kefu extends Base
{

    /**
     * 客服列表
     * @return mixed
     */
    public function index()
    {
        $kefu = new MKefus();
        $result = Db::name($kefu->tableName)->where('content','neq', '')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $finalRes = $kefu->formatKefusOutput($result);
        $page = $result->render();
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  客服添加处理
     * @return mixed
     */
    public function add($content = '')
    {
        if (request()->isPost()) {
            if (!$content ) {
                $this->error('请输入内容');
            }
            $kefu = new MKefus();
            $result = $kefu->saveKefu($content, true);
            if ($result) {
                $this->success('客服添加成功');                
            } else {
                $this->error('客服添加失败,请重试!');
            }
        }
        return $this->fetch("kefu/add");
    }

    /**
     * 修改客服
     */
    public function edit($id = '', $content = '', $active = false)
    {
        if (request()->isPost()) {
            if (!$content ) {
                $this->error('请输入标题和内容');
            }
            $kefu = new MKefus();
            $result = $kefu->updateKefu($id, $content, $active);
            if ($result) {
                $this->success('客服修改成功');                
            } else {
                $this->error('客服修改失败!');
            }
        }
        $kefu = new MKefus();
        $result = $kefu->getKefu($id);
        if ($result === false) {
            $this->error('对不起,客服不存在,请刷新页面');
        }
        $this->assign('data', $result);
        return $this->fetch("kefu/edit");
    }


    /**
     * 客服删除处理
     * @param $id
     * @return \think\response\Json
     */
    public function del($id)
    {
        $kefu = new MKefus();
        $result = $kefu->deleteKefu($id);
        if ($result === false) {
            return json(['code' => 0, 'msg' => '数据删除失败,请检查']);
        } else {
            return json(['code' => 1, 'msg' => '数据删除成功']);
        }

    }

}