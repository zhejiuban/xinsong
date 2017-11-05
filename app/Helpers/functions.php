<?php
/**
 * 自定义帮助函数
 * User: Guess
 * Date: 2017/10/25
 * Time: 上午11:57
 */


/**
 * 获取路由uri参数
 * @param $name
 * @return string
 */
function route_uri($name)
{
    return app('router')->getRoutes()->getByName($name)->uri();
}

/**
 *  对象装换成数组
 * @param unknown $cgi
 * @param number $type
 * @return Ambigous <multitype:, multitype:multitype: unknown >
 */
if (!function_exists('object2array')) {
    function object2array(&$cgi, $type = 0)
    {
        if (is_object($cgi)) {
            $cgi = get_object_vars($cgi);
        }
        if (!is_array($cgi)) {
            $cgi = array();
        }
        foreach ($cgi as $kk => $vv) {
            if (is_object($vv)) {
                $cgi[$kk] = get_object_vars($vv);

                object2array($cgi[$kk], $type);
                //utf8_gbk($vv);
            } else if (is_array($vv)) {
                object2array($cgi[$kk], $type);
            } else {
                $v = $vv;
                $k = $kk;
                $cgi["$k"] = $v;
            }
        }
        return $cgi;
    }
}
/**
 * 循环创建目录
 */
if (!function_exists('createDir')) {
    function createDir($path)
    {
        if (!file_exists($path)) {
            createDir(dirname($path));
            mkdir($path, 0777);
        }
    }
}
/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str 要分割的字符串
 * @param  string $glue 分割符
 * @return array
 */
if (!function_exists('str2arr')) {
    function str2arr($str, $glue = ',')
    {
        return explode($glue, $str);
    }
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array $arr 要连接的数组
 * @param  string $glue 分割符
 * @return string
 */
if (!function_exists('arr2str')) {
    function arr2str($arr, $glue = ',')
    {
        if (is_array($arr)) {
            return implode($glue, $arr);
        } else {
            return '';
        }
    }
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
if (!function_exists('list_to_tree')) {
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}
/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree 原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array $list 过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
if (!function_exists('tree_to_list')) {
    function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array())
    {
        if (is_array($tree)) {
            $refer = array();
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if (isset($reffer[$child])) {
                    unset($reffer[$child]);
                    tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = list_sort_by($list, $order, $sortby = 'asc');
        }
        return $list;
    }
}
/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
if (!function_exists('list_sort_by')) {
    function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }
}
if (!function_exists('formatTreeData')) {
    function formatTreeData($data, $id = "id", $parent_id = "parent_id", $root = 0, $space = '&nbsp;&nbsp;|--&nbsp;', $level = 0)
    {
        $arr = array();
        if ($data) {
            foreach ($data as $v) {
                if ($v[$parent_id] == $root) {
//                    $v['level'] = $level + 1;
                    $v['space'] = $root != 0 ? str_repeat($space, $level) : '' . str_repeat($space, $level);
                    $arr[] = $v;
                    $arr = array_merge($arr, formatTreeData($data, $id, $parent_id, $v[$id], $space, $level + 1));
                }
            }
        }
        return $arr;
    }
}
if (!function_exists('menu_select')) {
    /**
     * 菜单选择
     * @param int $selected
     * @param int $type
     * @return string
     */
    function menu_select($selected = 0, $type = 0)
    {
        $list = \App\Menu::getTreeData();
        if ($type == 1) {
            $str = '<option value="">请选择菜单</option>';
        } else {
            $str = '<option value="0">顶级菜单</option>';
        }

        if ($list) {
            foreach ($list as $key => $val) {
                $str .= '<option value="' . $val['id'] . '" '
                    . ($selected == $val['id'] ? 'selected="selected"' : '') . '>'
                    . $val['space'] . $val['title'] . '</option>';
            }
        }
        return $str;
    }
}
if (!function_exists('gurad_name_select')) {
    function gurad_name_select($selected = 'web')
    {
        $data = [
            'web' => '默认分组'
        ];
        $str = '';
        if ($data) {
            foreach ($data as $key => $val) {
                $str .= '<option value="' . $val . '" '
                    . ($selected == $val ? 'selected="selected"' : '') . '>'
                    . $val . '</option>';
            }
        }
        return $str;
    }
}
if (!function_exists('_404')) {
    function _404($message = '您访问的信息不存在', $data = null, $url = '', $time = 0)
    {
        $request = request();
        if ($request->ajax()) {
            if ($request->method() == 'GET') {
                return view('layouts._error', compact('message'));
            } else {
                return response()->json([
                    'message' => $message, 'data' => $data,
                    'status' => 'error', 'url' => $url
                ]);
            }
        } else {
            return view('layouts._404', compact('message'));
        }
    }
}

if (!function_exists('department_level')) {
    /**
     * 获取组织机构级别
     * @param $level
     * @return mixed|null
     */
    function department_level($level)
    {
        $data = [
            1 => '总部', 2 => '分部', 3 => '部门'
        ];
        return isset($data[$level]) ? $data[$level] : null;
    }
}
/**
 * 获取总部信息
 * @param bool $field
 * @return null
 */
function headquarters($field = true)
{
    $info = \App\Department::where('level', 1)->first();
    if ($info) {
        if ($field === true) {
            return $info;
        }
        return isset($info->$field) ? $info->$field : null;
    } else {
        return null;
    }
}

if (!function_exists('department_select')) {
    /**
     * 部门选择
     * @param int $selected
     * @return string
     */
    function department_select($selected = 0)
    {
        $list = \App\Department::getTreeData();
        $str = '<option value="">请选择部门</option>';
        if ($list) {
            foreach ($list as $key => $val) {
                $str .= '<option value="' . $val['id'] . '" '
                    . ($selected == $val['id'] ? 'selected="selected"' : '') . '>'
                    . $val['space'] . $val['name'] . '</option>';
            }
        }
        return $str;
    }
}


