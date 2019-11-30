<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/20
 * Time: 17:51
 */
namespace app\Common\Model;
use think\Db;
use think\Model;

class Com extends Model
{
    /**
     * 缓存名称
     * @var type
     */
    public $mcName = '';

    /**
     * 缓存过期时间
     * @var type
     */
    protected $mcTimeOut = 600;   //默认缓存过期时间，不过期

    /**
     * 缓存开关
     * @var type
     */
    protected $mcOpen = false;   //缓存开关

    /**
     * 缓存主键
     * @var type
     */
    protected $mcPkId = 'id';

    /**
     * 列表查询缓存时间
     * @var type
     */
    protected $selectTime = 0;

    /**
     * 设置列表缓存时间
     * @param type $time
     */
    public function setSelectTime($time = 0) {
        $this->selectTime = $time;
    }

    /**
     * 删除缓存
     * @param type $id
     * @param type $uid
     */
    public function delCache($id = 0, $mcRelationIdInfoAll = 0) {
        if ($this->mcOpen) {
            if ($id > 0) {
                $mcKey = $this->mcName . '_' . $id;
                session($mcKey, null);
            } else {
                set_time_limit(0);
                $list = $this->select();
                foreach ($list as $value) {
                    if ($value[$this->mcPkId]) {
                        $mcKey = $this->mcName . '_' . $value[$this->mcPkId];
                        session($mcKey, null);
                    }
                }
            }
        }
    }


    /**
     * 查看主键信息
     * @param type $id 自定义的主键$mcPkId
     * @param type $field 字段
     * @return type
     */
    public function info($id, $field = '') {
        $id = intval($id);
        if ($id <= 0) {
            return false;
        }
        //判断是否开启缓存
        if ($this->mcOpen === true) {
            $mcKey = $this->mcName . '_' . $id;
            $rs = session($mcKey);
            if ($rs === false) {
                $rs = $this->where(array($this->mcPkId => $id))->find();
                if ($rs) {
                    $time = $this->mcTimeOut > 0 ? $this->mcTimeOut : 0;
                    session($mcKey, $rs, $time);
                }
            }
        } else {
            $rs = $this->where(array($this->mcPkId => $id))->find();

        }
//        if ($rs) {
//            if ( isset($rs['title']))  $rs['title'] = filtration($rs['title']);
//            if ( isset($rs['content']))  $rs['content'] = filtration($rs['content']);
//            if ( isset($rs['digest']))  $rs['digest'] = filtration($rs['digest']);
//        }

        return $this->returnRs($rs, $field);
    }

    /**
     * 插入
     * @param type $insert
     * @return boolean
     */
    public function insert($insert) {
        if(empty($insert['created_time'])){
            $insert['created_time'] = time();
        }
        $id = $this->insertGetId($insert);
        if ($id === false) {
            return false;
        }

        $this->insert_after($id, $insert);
        return $id;
    }

    /**
     * insert后续操作
     * @param type $id
     */
    public function insert_after(&$id, &$data = array()) {

    }

    /**
     * 更新
     * @param type $uid 自定义的主键$mcPkId
     * @param type $update
     * @return boolean
     */
    public function updates($id, $update) {
        if (empty($id) || empty($update)) {
            return false;
        }

        if ($this->mcOpen === true) {
            $data = $this->info($id);
        }
        $update['updated_at'] = $update['updated_at'] ? $update['updated_at'] : time();

        if ($this->where($this->mcPkId . '=' . $id)->save($update) === false) {
            return false;
        }
        if ($this->mcOpen === true) {
            //更新缓存
            if (!empty($update)) {
                $data = array_merge($data, $update);
            }
            //更新缓存
            $mcKey = $this->mcName . '_' . $id;
            $time = $this->mcTimeOut > 0 ? $this->mcTimeOut : 0;
            S($mcKey, $data, $time);
        }
        $this->update_after($id, $update);
        return true;
    }


    /**
     * update后续操作
     * @param type $id
     * @param type $data 需要修改的数据
     * @param type $newData 更新后的所有数据
     */
    public function update_after(&$id, &$data = array()) {

    }

    /**
     * 删除
     * @param type $id 自定义的主键$mcPkId
     * @param type $update
     * @return boolean
     */
    public function del($id) {
        if (empty($id)) {
            return false;
        }
        if ($this->where($this->mcPkId . '=' . $id)->delete() === false) {
            return false;
        }
        if ($this->mcOpen === true) {
            //更新缓存
            $mcKey = $this->mcName . '_' . $id;
            session($mcKey, null);
        }
        return true;
    }


    /**
     * 获取列表
     * @param type $options
     * @return type
     */
    public function getList($options) {
        $data = array();
        //判断是否要合计
        if ($options['page']) {
            $countOp = $options;
            unset($countOp['page'], $countOp['order']);
            if ($options['group']){
                if($options['alias']){
                    $subQuery = $this->alias($options['alias'])->where($countOp['where'])->group($countOp['group'])->buildSql();
                }else{
                    $subQuery = $this->where($countOp['where'])->group($countOp['group'])->buildSql();
                }
                $data['count'] = $this->selectTime > 0 ? $this->cache(true, $this->selectTime)->count() : $this->table($subQuery.' a')->count();
            }else{
                $this->options = $countOp;
                $data['count'] = $this->selectTime > 0 ? $this->cache(true, $this->selectTime)->count() : $this->count();
            }
        }
        $this->options = $options;
        $list = $this->selectTime > 0 ? $this->cache(true, $this->selectTime)->select() : $this->select();
        $data['list'] = $list;
        return $data;
    }

    /**
     * 删除
     * @param type $id
     * @param type $uid
     * @return boolean
     */
    public function delAll($idArray) {
        if (empty($idArray)) {
            return false;
        }
        $where[$this->mcPkId] = array('in', $idArray);
        if ($this->where($where)->delete() === false) {
            return false;
        }
        if ($this->mcOpen) {
            foreach ($idArray as $key => $value) {
                //更新缓存
                $mcKey = $this->mcName . $value;
                session($mcKey, null);
            }
        }
        return true;
    }

    /**
     * 获取相邻两篇信息的方法
     * @param type $id
     * @param type $where where条件仅支持相等的
     * @author liufengcheng@258.com
     */
    public function getPreAndNext($id, $where = [], $field = '*', $order = 'DESC') {
        if (empty($id)) {
            return false;
        }
        $where_str = '';
        if (count($where) > 0) {
            foreach ($where as $key => $value) {
                $where_str .= ' and `' . $key . '`="' . $value . '"';
            }
        }

        if ($order == 'DESC') {
            $pre_fuhao = '>';
            $next_fuhao = '<';
            $order_str = 'ASC';
        } else {
            $pre_fuhao = '<';
            $next_fuhao = '>';
            $order_str = 'DESC';
        }
        $tableName = $this->getTableName();
        $sql = '(SELECT ' . $field . ' FROM `' . $tableName . '` WHERE `id`  '.$pre_fuhao. $id  . $where_str .' ORDER BY id '.$order_str.' LIMIT 1)'
            . 'UNION'
            . '(SELECT ' . $field . ' FROM `' . $tableName . '` WHERE `id` '.$next_fuhao. $id  . $where_str .' ORDER BY id '.$order. ' LIMIT 1)';
        $rs = Db::name()->query($sql);
        if($rs[0]['id']<$id && $order=='DESC' || $rs[0]['id']>$id && $order=='ASC'){
            return ['pre' => null, 'next' => $rs[0]];
        }else{
            return ['pre' => $rs[0], 'next' => $rs[1],];
        }
    }
}