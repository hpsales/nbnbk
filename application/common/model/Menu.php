<?php
namespace app\common\model;

class Menu extends Base
{
	// 设置当前模型对应的完整数据表名称
    //protected $table = 'fl_menu';
    // 默认主键为自动识别，如果需要指定，可以设置属性
    protected $pk = 'id';
    
    public function getDb()
    {
        return db('menu');
    }
    
    //状态，0正常，1隐藏
    const MENU_STATUS_NORMAL  = 0;
    const MENU_STATUS_DISABLE = 1;
    //状态描述
    public static $menu_status_desc = [
        self::MENU_STATUS_NORMAL  => '正常',
        self::MENU_STATUS_DISABLE => '隐藏'
    ];
    
    /**
     * 列表
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $offset 偏移量
     * @param int $limit 取多少条
     * @return array
     */
    public function getList($where = array(), $order = '', $field = '*', $offset = 0, $limit = 15)
    {
        $res['count'] = self::where($where)->count();
        $res['list'] = array();
        
        if($res['count'] > 0)
        {
            $res['list'] = self::where($where);
            
            if(is_array($field))
            {
                $res['list'] = $res['list']->field($field[0],true);
            }
            else
            {
                $res['list'] = $res['list']->field($field);
            }
            
            if(is_array($order) && isset($order[0]) && $order[0]=='orderRaw')
            {
                $res['list'] = $res['list']->orderRaw($order[1]);
            }
            else
            {
                $res['list'] = $res['list']->order($order);
            }
            
            $res['list'] = $res['list']->limit($offset.','.$limit)->select();
        }
        
        return $res;
    }
    
    /**
     * 分页，用于前端html输出
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $limit 每页几条
     * @param int|bool $simple 是否简洁模式或者总记录数
     * @param int $page 当前第几页
     * @return array
     */
    public function getPaginate($where = array(), $order = '', $field = '*', $limit = 15, $simple = false)
    {
        $res = self::where($where);
        
        if(is_array($field))
        {
            $res = $res->field($field[0],true);
        }
        else
        {
            $res = $res->field($field);
        }
        
        if(is_array($order) && isset($order[0]) && $order[0]=='orderRaw')
        {
            $res = $res->orderRaw($order[1]);
        }
        else
        {
            $res = $res->order($order);
        }
        
        return $res->paginate($limit, $simple, array('query' => request()->param()));
    }
    
    /**
     * 查询全部
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $limit 取多少条
     * @return array
     */
    public function getAll($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = self::where($where);
            
        if(is_array($field))
        {
            $res = $res->field($field[0],true);
        }
        else
        {
            $res = $res->field($field);
        }
        
        if(is_array($order) && isset($order[0]) && $order[0]=='orderRaw')
        {
            $res = $res->orderRaw($order[1]);
        }
        else
        {
            $res = $res->order($order);
        }
        
        $res = $res->limit($limit)->select();
        
        return $res;
    }
    
    /**
     * 获取一条
     * @param array $where 条件
     * @param string $field 字段
     * @return array
     */
    public function getOne($where, $field = '*', $order = '')
    {
        $res = self::where($where);
        
        if(is_array($field))
        {
            $res = $res->field($field[0],true);
        }
        else
        {
            $res = $res->field($field);
        }
        
        if(is_array($order) && isset($order[0]) && $order[0]=='orderRaw')
        {
            $res = $res->orderRaw($order[1]);
        }
        else
        {
            $res = $res->order($order);
        }
        
        $res = $res->find();
        
        return $res;
    }
    
    /**
     * 添加
     * @param array $data 数据
     * @return int
     */
    public function add($data,$type=0)
    {
        // 过滤数组中的非数据表字段数据
        // return $this->allowField(true)->isUpdate(false)->save($data);
        
        if($type==1)
        {
            // 添加单条数据
            //return $this->allowField(true)->data($data, true)->save();
            return self::strict(false)->insert($data);
        }
        elseif($type==2)
        {
            /**
             * 添加多条数据
             * $data = [
             *     ['foo' => 'bar', 'bar' => 'foo'],
             *     ['foo' => 'bar1', 'bar' => 'foo1'],
             *     ['foo' => 'bar2', 'bar' => 'foo2']
             * ];
             */
            
            //return $this->allowField(true)->saveAll($data);
            return self::strict(false)->insertAll($data);
        }
        
        // 新增单条数据并返回主键值
        return self::strict(false)->insertGetId($data);
    }
    
    /**
     * 修改
     * @param array $data 数据
     * @param array $where 条件
     * @return bool
     */
    public function edit($data, $where = array())
    {
        //return $this->allowField(true)->save($data, $where);
        return self::strict(false)->where($where)->update($data);
    }
    
    /**
     * 删除
     * @param array $where 条件
     * @return bool
     */
    public function del($where)
    {
        return self::where($where)->delete();
    }
    
    /**
     * 统计数量
     * @param array $where 条件
     * @param string $field 字段
     * @return int
     */
    public function getCount($where, $field = '*')
    {
        return self::where($where)->count($field);
    }
    
    /**
     * 获取最大值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMax($where, $field)
    {
        return self::where($where)->max($field);
    }
    
    /**
     * 获取最小值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMin($where, $field)
    {
        return self::where($where)->min($field);
    }
    
    /**
     * 获取平均值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getAvg($where, $field)
    {
        return self::where($where)->avg($field);
    }
    
    /**
     * 统计总和
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getSum($where, $field)
    {
        return self::where($where)->sum($field);
    }
    
    /**
     * 查询某一字段的值
     * @param array $where 条件
     * @param string $field 字段
     * @return null
     */
    public function getValue($where, $field)
    {
        return self::where($where)->value($field);
    }
    
    /**
     * 查询某一列的值
     * @param array $where 条件
     * @param string $field 字段
     * @return array
     */
    public function getColumn($where, $field)
    {
        return self::where($where)->column($field);
    }
    
    /**
     * 获取器——状态
     * @param int $value
     * @return string
     */
    public function getStatusTextAttr($value, $data)
    {
        return self::$menu_status_desc[$data['status']];
    }
    
    /**
     * 获取器——菜单类型  1：权限认证+菜单；0：只作为菜单
     * @param int $value
     * @return string
     */
    public function getTypeTextAttr($value, $data)
    {
        $arr = array(0 => '只作为菜单', 1 => '权限认证+菜单');
        return $arr[$data['type']];
    }
    
	/**
     * 将列表生成树形结构
     * @param int $parent_id 父级ID
     * @param int $deep 层级
     * @return array
     */
	public function get_category($parent_id=0,$deep=0)
	{
		$arr=array();
		
		$cats = model('Menu')->getAll(['parent_id'=>$parent_id], 'listorder asc');
		if($cats)
		{
			foreach($cats as $row)//循环数组
			{
				$row['deep'] = $deep;
                //如果子级不为空
				if($child = $this->get_category($row["id"],$deep+1))
				{
					$row['child'] = $child;
				}
				$arr[] = $row;
			}
		}
        
        return $arr;
	}
    
    /**
     * 树形结构转成列表
     * @param array $list 数据
     * @param int $parent_id 父级ID
     * @return array
     */
	public function category_tree($list,$parent_id=0)
	{
		global $temp;
		if(!empty($list))
		{
			foreach($list as $v)
			{
				$temp[] = array("id"=>$v['id'],"deep"=>$v['deep'],"name"=>$v['name'],"parent_id"=>$v['parent_id']);
				//echo $v['id'];
				if(isset($v['child']))
				{
					$this->category_tree($v['child'],$v['parent_id']);
				}
			}
		}
		
		return $temp;
	}
    
	//获取后台管理员所具有权限的菜单列表
	public function getPermissionsMenu($role_id, $parent_id=0, $pad=0)
	{
		$res = array();
		
		$where['fl_access.role_id'] = $role_id;
		$where['fl_menu.parent_id'] = $parent_id;
		$where["fl_menu.status"] = 0;
		
		$menu =db('menu')
			->join('fl_access', 'fl_access.menu_id = fl_menu.id')
            ->field('fl_menu.*, fl_access.role_id')
			->where($where)
			->order('fl_menu.listorder asc')
            ->select();
		
		if($menu)
		{
			foreach($menu as $row)
			{
				$row['deep'] = $pad;
				
				if($PermissionsMenu = $this->getPermissionsMenu($role_id, $row['id'], $pad+1))
				{
					$row['child'] = $PermissionsMenu;
				}
				
				$res[] = $row;
			}
		}
		
		return $res;
	}
}