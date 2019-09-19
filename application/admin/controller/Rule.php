<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-6
 * Time: 9:22
 */

namespace app\admin\controller;


use app\admin\model\AuthRule;

/**
 * Class Rule
 * @package app\admin\controller
 * 权限规则类
 */
class Rule extends Base
{
    /**
     * @return mixed
     * 规则首页
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 空操作
     */
    public function _empty()
    {
        $this->redirect('rule/index');
    }

    /**
     * 规则添加
     * @param int $pid
     * @param string $title
     * @param string $name
     * @param string $show
     * @param int $status
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add($pid = 0, $title = '', $name = '', $show = '', $status = 0)
    {
        $ruleTree = new AuthRule();
        if (request()->isAjax()) {
            $data = ['pid' => $pid, 'title' => $title, 'name' => $name, 'show' => $show, 'status' => $status];
            //验证
            $validate = validate('auth_rule');
            if (!$validate->scene('add')->check($data)) {
                return ['code' => 0, 'msg' => $validate->getError()];
            }
            $add = $ruleTree->insert($data);
            if ($add) {
                return ['code' => 1, 'msg' => '添加规则成功'];
            } else {
                return ['code' => 0, 'msg' => '添加规则失败!'];
            }
        }
        $ruleRes = $ruleTree->select();

        $ruleTree = $ruleTree->ruleTree($ruleRes);
        $this->assign(['ruleTree' => $ruleTree]);
        return $this->fetch();
    }

    /**
     * 规则编辑
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        $ruleTree = new AuthRule();
        if (request()->isPost()) {
            $data = input('post.');
            $validate = validate('auth_rule');
            if (!$validate->scene('edit')->check($data)) {
                $this->error($validate->getError());
            }
            $save = $ruleTree->where('id', 'eq', $data['id'])->update($data);
            if ($save) {
                $this->success('修改规则成功！');
            } else {
                $this->error('修改规则失败！');
            }
        }

        $id = input('id');
        $rules = $ruleTree->find($id);
        $ruleRes = $ruleTree->select();
        $ruleTree = $ruleTree->ruleTree($ruleRes);
        $this->assign([
            'ruleTree' => $ruleTree,
            'rules' => $rules,
        ]);
        return $this->fetch();
    }

    /**
     * 编辑保存
     */
    public function editSave()
    {
        $dataS = input('post.');
        if ($dataS['title'] == '' && $dataS['name'] == '') {
            $this->error('名称或者节点不能为空');
        }
        $result = new AuthRule();
        $res = $result->ruleEdit($dataS);
        if ($res) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    /**
     *  删除规则方法
     * @param int $id
     * @return \think\response\Json
     */
    public function del($id = 0)
    {
        $AuthRule = new AuthRule();
        $pid = $AuthRule->where('pid',$id)->count();
        if($pid > 0){
            return json(['code' => 1, 'msg' => '存在下级,请先删除']);
        }
        $del = $AuthRule->where('id', 'eq', $id)->delete();
        if ($del) {
            return json(['code' => 0, 'msg' => '删除成功']);
        } else {
            return json(['code' => 1, 'msg' => '删除失败']);
        }
    }


    /**
     * 获取规则数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRuleJsonData()
    {
        $ruleRes = db('authRule')->select();
        $authRule = new AuthRule();
        $ruleTree = $authRule->ruletree($ruleRes);
        foreach ($ruleTree as &$rule) {
            if ($rule['status'] == 0) {
                $rule['status'] = '<span style="color: #008000">开启</span>';
            } else {
                $rule['status'] = '<span style="color: #FF0000">关闭</span>';
            }
        }
        return ['code' => 0, 'msg' => 'ok', 'data' => $ruleTree];
    }
}