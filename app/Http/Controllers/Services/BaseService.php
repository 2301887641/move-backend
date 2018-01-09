<?php
/**
 * Created by PhpStorm.
 * User: sui
 * Date: 18-1-4
 * Time: 下午12:31
 */

namespace App\Http\Controllers\Services;


class BaseService
{
    /**
     * 根据id查询单条
     * @param $class
     * @param $id
     * @param array $field
     * @return mixed
     */
    protected function BaseFindById($class, $id, $field = ['*'])
    {
        return $class::find($id, $field);
    }

    /**
     * 修改数据
     * @param $class
     * @param $id
     * @param $data
     * @return bool
     */
    protected function BaseUpdate($class, $id, $data)
    {
        $instance = $class::find($id);
        if ($instance->fill($data)->save()) {
            return true;
        }
        return false;
    }

    /**
     * 删除
     * @param $instance 实例对象
     * @param $id
     * @return bool
     */
    public function BaseDestroy($instance, $id)
    {
        if ($instance->where(["id" => $id])->delete()) {
            return true;
        }
        return false;
    }

    /**
     * 添加
     * @param $instance 实例对象
     * @param $data
     * @return bool
     */
    public function BaseStore($instance,$data)
    {
        if ($instance->fill($data)->save()) {
            return true;
        }
        return false;
    }

    /**
     * 分页查询
     * @param $query  查询对象
     * @param $currentPage  当前页
     * @param $pageSize    每页多少条数据
     * @param $columns   查询字段
     * @return mixed
     */
    public function BaseIndex($query,$pageInfo,$columns=['*'])
    {
        $count=$query->count();
        $data=$query->orderBy("id","desc")->select($columns)->offset($pageInfo['page'])->limit($pageInfo['pageSize'])->get();
        return collect(["data"=>$data,"total"=>$count]);
    }

    /**
     * 获取所有
     * @param $class
     * @param array $columns
     * @return mixed
     */
    public function BaseGetAll($class,$columns=['*'])
    {
        return $class::all($columns);
    }
}