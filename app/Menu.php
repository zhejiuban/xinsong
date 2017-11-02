<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    /**
     * 获取菜单格式数据用树形结构
     * @return array
     */
    public static function getTreeData()
    {
        $data = self::where('status', 1)->get()->toArray();
        return formatTreeData($data);
    }

    /**
     * 自动转换菜单唯一识别号
     * @param $value
     */
    public function setUniqidAttribute($value)
    {
        $this->attributes['uniqid'] = md5($value);
    }

    /**
     * 添加菜单时自动同步至权限
     * @param $menu
     */
    public function syncPermissions($menu)
    {
        return Permission::create([
            'name' => $menu->url,
            'display_name' => $menu->title,
            'guard_name' => $menu->guard_name ? $menu->guard_name : 'web'
        ]);
    }

    public function syncUpdatePermissions($menu, $origin)
    {
        return Permission::where('name', $origin)->update([
            'name' => $menu->url,
            'display_name' => $menu->title,
            'guard_name' => $menu->guard_name ? $menu->guard_name : 'web'
        ]);
    }
}
