<?php
namespace app\common\validate;
use think\Validate;
use app\common\lib\Helper;

class Admin extends Validate
{
    // 验证规则
    protected $rule = [
        ['id', 'require|number','ID必填|ID必须是数字'],
        ['role_id', 'require|number','角色ID必填|角色ID必须是数字'],
        ['name', 'require|max:30|alphaDash','名称必填|名称不能超过30个字符|名称只能包含字母和数字、下划线及破折号'],
        ['pwd', 'require|max:32','密码必填|密码不能超过32个字符'],
        ['mobile', 'max:20|checkMobile|checkMobileUnique','手机号码不能超过20个字符'],
        ['email', 'email|checkEmailUnique','邮箱格式不正确'],
        ['avatar', 'max:150','头像不能超过150个字符'],
        ['status', 'in:0,1,2','用户状态 0：正常； 1：禁用 ；2：未验证'],
        ['login_time', 'number', '登录时间格式不正确'],
        ['add_time', 'number', '添加时间格式不正确'],
        ['update_time', 'number', '更新时间格式不正确'],
        ['delete_time', 'number', '删除时间格式不正确'],
    ];
    
    protected $scene = [
        'add'  => ['role_id', 'name', 'pwd', 'mobile', 'email', 'avatar', 'status', 'login_time', 'add_time', 'update_time', 'delete_time'],
        'edit' => ['role_id', 'name', 'pwd', 'mobile', 'email', 'avatar', 'status', 'login_time', 'add_time', 'update_time', 'delete_time'],
        'del'  => ['id'],
    ];
    
    /**
     * 手机号码验证
     * 参数依次为验证数据，验证规则，全部数据(数组)，字段名
     */
    protected function checkMobile($value,$rule,$data,$field)
    {
        if(Helper::isValidMobile($value))
        {
            return true;
        }
        
        return '手机号码格式不正确';
    }
    
    /**
     * 手机号码唯一验证
     * 参数依次为验证数据，验证规则，全部数据(数组)，字段名
     */
    protected function checkMobileUnique($value,$rule,$data,$field)
    {
        $where['mobile'] = $value;
        if(isset($data['id'])){$where['id'] = ['<>',$data['id']];}
        $res = model('Admin')->getOne($where);
        if($res)
        {
            return '手机号码已经存在';
        }
        
        return true;
    }
    
    /**
     * 邮箱唯一验证
     * 参数依次为验证数据，验证规则，全部数据(数组)，字段名
     */
    protected function checkEmailUnique($value,$rule,$data,$field)
    {
        $where['email'] = $value;
        if(isset($data['id'])){$where['id'] = ['<>',$data['id']];}
        $res = model('Admin')->getOne($where);
        if($res)
        {
            return '邮箱已经存在';
        }
        
        return true;
    }
}