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
        $data = self::where('status', 1)->orderBy('sort', 'desc')->orderBy('id')->get()->toArray();
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
    public static function syncPermissions($menu)
    {
        return Permission::create([
            'name' => $menu->url,
            'display_name' => $menu->title,
            'guard_name' => $menu->guard_name ? $menu->guard_name : 'web'
        ]);
    }

    /**
     * 同步更新权限
     * @param $menu
     * @param $origin
     * @return mixed
     */
    public static function syncUpdatePermissions($menu, $origin)
    {
        return Permission::where('name', $origin)->update([
            'name' => $menu->url,
            'display_name' => $menu->title,
            'guard_name' => $menu->guard_name ? $menu->guard_name : 'web'
        ]);
    }

    /**
     * 同步删除权限
     * @param $menu
     * @return mixed
     */
    public static function syncDeletePermissions($menu)
    {
        return Permission::where('name', $menu->url)->delete();
    }

    public static function nodes($tree = true, $changeUrlField = false)
    {
        static $tree_nodes = array();
        if ($tree && !empty($tree_nodes[(int)$tree])) {
            return $tree_nodes[$tree];
        }
        if ((int)$tree) {
            $list = self::orderBy('sort', 'desc')->orderBy('id')->get()->toArray();
            foreach ($list as $key => $value) {
                if ($changeUrlField) {
                    $list[$key]['urls'] = $list[$key]['url'];
                    unset($list[$key]['url']);
                }
            }
            $nodes = list_to_tree($list, $pk = 'id', $pid = 'parent_id', $child = 'operator', $root = 0);
        } else {
            $nodes = self::orderBy('sort', 'desc')->orderBy('id')->get()->toArray();
            foreach ($nodes as $key => $value) {
                if ($changeUrlField) {
                    $nodes[$key]['urls'] = $nodes[$key]['url'];
                    unset($nodes[$key]['url']);
                }
            }
        }
        $tree_nodes[(int)$tree] = $nodes;
        return $nodes;
    }

    /**
     * 一键同步权限规则
     * @return bool
     */
    public static function sync()
    {
        $nodes = self::where('guard_name', 'web')->get()->toArray();
        $permission = Permission::where('guard_name', 'web')->get()->toArray();

        $data = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value) {
            $temp['name'] = $value['url'];
            $temp['display_name'] = $value['title'];
            $temp['guard_name'] = 'web';
            $data[$temp['name']] = $temp;//去除重复项
        }
        $update = array();//保存需要更新的节点
        $ids = array();//保存需要删除的节点的id
        foreach ($permission as $index => $rule) {
            $key = $rule['name'];
            if (isset($data[$key])) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($permission[$index]);
            } else {
                $ids = $rule['id'];
            }
        }
        if (count($update)) {
            foreach ($update as $k => $row) {
                $update_id = $row['id'];
                unset($row['id']);
                Permission::where('id', $update_id)->update($row);
            }
        }
        if (count($ids)) {
            Permission::destroy($ids);
        }
        if (count($data)) {
            foreach ($data as $k => $row) {
                Permission::create($row);
            }
        }
        return true;
    }

    public static function getUserMenu()
    {
        //获取当前登录用户
        if (is_administrator()) {
            $menu = self::where('status', 1)->where('hide', 0)->orderBy('sort', 'desc')->orderBy('id')->get();
        } else {
            $permission = get_current_login_user_info(true)->getAllPermissions();
            $all = $permission->pluck('name')->toArray();
            $menu = self::whereIn('url', $all)->where('status', 1)->where('hide', 0)->orderBy('sort', 'desc')->orderBy('id')->get();
        }
        return list_to_tree($menu->toArray(), 'id', 'parent_id');
    }

    public static function info($id)
    {
        if (is_numeric($id)) {
            return self::find($id)->toArray();
        }
        return self::where('uniqid', $id)->first()->toArray();
    }

    public function breadcrumb($menu, $is_contain_self = 1)
    {
        $data = get_all_parent_menu($menu, $is_contain_self);
        return $data;
    }
}
