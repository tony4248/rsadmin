<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-11-6
 * Time: 17:56
 */

namespace app\admin\controller;

use app\admin\model\Anncmnts as MAnncmnts;
use think\Config;
use think\Db;

class Anncmnt extends Base
{

    /**
     * 公告列表
     * @return mixed
     */
    public function index()
    {
        $anncmnt = new MAnncmnts();
        $result = Db::name($anncmnt->tableName)->where('content','neq', '')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $finalRes = $anncmnt->formatAnncmntsOutput($result);
        $page = $result->render();
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  公告添加处理
     * @return mixed
     */
    public function add($content = '')
    {
        if (request()->isPost()) {
            if (!$content ) {
                $this->error('请输入内容');
            }
            $anncmnt = new MAnncmnts();
            $result = $anncmnt->saveAnncmnt($content, true);
            if ($result) {
                $this->success('公告添加成功');                
            } else {
                $this->error('公告添加失败,请重试!');
            }
        }
        return $this->fetch();
    }

    /**
     * 修改公告
     */
    public function edit($id = '', $content = '', $active = false)
    {
        if (request()->isPost()) {
            if (!$content ) {
                $this->error('请输入标题和内容');
            }
            $anncmnt = new MAnncmnts();
            $result = $anncmnt->updateAnncmnt($id, $content, $active);
            if ($result) {
                $this->success('公告修改成功');                
            } else {
                $this->error('公告修改失败!');
            }
        }
        $anncmnt = new MAnncmnts();
        $result = $anncmnt->getAnncmnt($id);
        if ($result === false) {
            $this->error('对不起,公告不存在,请刷新页面');
        }
        $this->assign('data', $result);
        return $this->fetch();
    }


    /**
     * 公告删除处理
     * @param $id
     * @return \think\response\Json
     */
    public function del($id)
    {
        $anncmnt = new MAnncmnts();
        $result = $anncmnt->deleteAnncmnt($id);
        if ($result === false) {
            return json(['code' => 0, 'msg' => '数据删除失败,请检查']);
        } else {
            return json(['code' => 1, 'msg' => '数据删除成功']);
        }

    }

}