<?php
require_once 'Base.php';

/**
 *  会员转换类
 */
class StatisticsOrder extends Base
{
    private $table_old = 'statistics';
    private $table_new = 'statistics_order';

    /**
     * 数据转换传输
     *
     * @author 李新宇
     * @date   2018-03-05T20:48:45+0800
     * @return [type]                   [description]
     */
    public function transfer()
    {
        echo "<pre>";
        try {
            //获取一条旧的数据
            $allOld = $this->getAllOld();
            //格式转换
            $newData = $this->getAllNewData($allOld);
            //写入新的数据
            $this->insterNew($newData);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function getAllOld()
    {
        return $this->old_db->select($this->table_old, '*');
    }

    private function getAllNewData($data)
    {
        $oneNewData = [];
        foreach ($data as $key => $v) {
            $oneNewData[] = [
                'statistics_order_id' => $v['id'],
                'day' => date('Ymd', $v['time']),
                'sum_order_num' => $v['order_num'],
                'store_id' => $v['store_id'],
                'create_time' => $v['create_time'],
                'update_time' => $v['update_time'],
                'delete_time' => 0
            ];
        }
        return $oneNewData;
    }

    private function insterNew($data)
    {
        $this->new_db->delete($this->table_new, "*");
        $this->new_db->truncate($this->table_new);
        if (count($data) == 0) {
            return;
        }
        $result = $this->new_db->insert($this->table_new, $data);
        echo $result->errorCode();
    }
}
