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
    function formatTreeData($data, $id = "id", $parent_id = "parent_id"
        , $root = 0, $space = '&nbsp;&nbsp;|--&nbsp;', $level = 0)
    {
        $arr = array();
        if ($data) {
            foreach ($data as $v) {
                if ($v[$parent_id] == $root) {
//                    $v['level'] = $level + 1;
                    $v['space'] = $root != 0 ? str_repeat($space, $level) : ''
                        . str_repeat($space, $level);
                    $arr[] = $v;
                    $arr = array_merge($arr, formatTreeData($data, $id,
                        $parent_id, $v[$id], $space, $level + 1));
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
            if ($request->isMethod('GET')) {
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
    function department_select($selected = 0, $type = 1)
    {
        $list = \App\Department::getTreeData($type);
        $str = '<option value="">' . ($type == 1 ? '请选择部门' : '请选择办事处') . '</option>';
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

if (!function_exists('role_select')) {
    function role_select($selected = '', $type = 0, $vType = 'id')
    {
        if (!is_administrator()) {
            $list = \Spatie\Permission\Models\Role::where('is_call', 1)->get()->toArray();
        } else {
            $list = \Spatie\Permission\Models\Role::get()->toArray();
        }
        $str = '';
        if (!$type) { //单选模式
            $str .= '<option value="">请选择角色</option>';
        }
        if ($list) {
            if ($type) {
                $selected_arr = [];
                if (is_array($selected) && count($selected)) {
                    foreach ($selected as $k => $val) {
                        $selected_arr[] = $val[$vType];
                    }
                }
            }
            foreach ($list as $key => $val) {
                if (!$type) {
                    $str .= '<option value="' . $val[$vType] . '" '
                        . ($selected == $val[$vType] ? 'selected="selected"' : '') . '>'
                        . $val['name'] . '</option>';
                } else {
                    $str .= '<option value="' . $val[$vType] . '" '
                        . (in_array($val[$vType], $selected_arr) ? 'selected="selected"' : '') . '>'
                        . $val['name'] . '</option>';
                }
            }
        }
        return $str;
    }
}
/**
 * 判断当前登录用户是否超级管理员
 * @param string $guard
 * @return bool
 */
function is_administrator($guard = 'web')
{
    return config('auth.administrator') == get_current_login_user_info('id', $guard);
}

/**
 * 判断某个用户是否是超级管理员
 * @param $id
 * @return bool
 */
function is_administrator_user($id)
{
    return config('auth.administrator') == $id;
}

/**
 * 获取当前登录用户信息
 */
if (!function_exists('get_current_login_user_info')) {
    function get_current_login_user_info($field = 'id', $guard = 'web')
    {
        if ($field === true) {
            return auth($guard)->user();
        }
        return auth($guard)->user()->$field;
    }
}
/**
 * 检测权限
 * @param $rule
 * @param null $user
 * @return bool
 */
function check_permission($rule = null, $user = null)
{
    if (!$rule) {
        return false;
    }
    if (!$user) {
        $user = get_current_login_user_info(true);
    } elseif (is_numeric($user)) {
        $user = \App\User::find($user);
    }
    if (is_administrator_user($user->id) || $user->hasPermissionTo($rule)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取用户可用菜单
 * @return array
 */
function get_user_menu()
{
    return \App\Menu::getUserMenu();
}

/**
 * url格式化
 * @param $url
 * @param array $params
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function menu_url_format($url, $params = [])
{
    if (!$url) {
        return 'javascript:;';
    }
    $p = http_build_query($params);
    if (stripos($url, '?') !== false) {
        return $p ? url($url . '&' . $p) : url($url);
    } else {
        return $p ? url($url . '?' . $p) : url($url);
    }
}

/**
 * 激活状态菜单前缀
 * @param $uri
 * @param int $level
 * @return string
 */
function active_menu_pattern_str($uri, $level = 0)
{
    $uri_arr = str2arr($uri, '/');
    $new = [];
    foreach ($uri_arr as $key => $val) {
        if ($key <= $level) {
            $new[] = $val;
        }
    }
    return arr2str($new, '/') . ($level ? '' : '*');
}

function check_active_url($url)
{
    $return = false;
    $res = parse_url($url);
    $path = isset($res['path']) ? $res['path'] : null;
    $query = isset($res['query']) ? $res['query'] : null;
    if ($path && if_uri_pattern($path)) {
        $return = true;
    }
    if ($query) {
        $query_arr = convertUrlQuery($res['query']);
        foreach ($query_arr as $key => $value) {
            if (!if_query($key, $value)) {
                $return = false;
                break;
            } else {
                $return = true;
            }
        }
    }
    return $return;
}

/**
 * 将参数转换成字符串
 * @param $query
 * @return array
 */
function convertUrlQuery($query)
{
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}

/**
 * 将参数变为字符串
 * @param $array_query
 * @return string
 */
function getUrlQuery($array_query)
{
    $tmp = array();
    foreach ($array_query as $k => $param) {
        $tmp[] = $k . '=' . $param;
    }
    $params = implode('&', $tmp);
    return $params;
}

/**
 * 获取某个菜单的所有父级菜单
 * @param $data
 * @param int $is_contain_self 是否包含自身
 * @return array
 */
function get_all_parent_menu($data, $is_contain_self = 1)
{
    //获取某个菜单的所有父级菜单
    static $info = [];
    if (is_numeric($data) || is_string($data)) {
        $data = \App\Menu::info($data);
    }
    $info[] = $data;
    if ($data['parent_id']) {
        return get_all_parent_menu($data['parent_id'], $is_contain_self);
    }
    asort($info);
    $new_arr = [];
    foreach ($info as $key => $val) {
        $new_arr[] = $val;
        unset($info[$key]);
    }
    if (!$is_contain_self) {
        array_pop($new_arr);
    }
    return $new_arr;
}

/**
 * 面包屑
 * @param $menu
 * @return array
 */
function breadcrumb($menu)
{
    return get_all_parent_menu($menu, 1);
}

/**
 * 设置跳转页面URL
 * 使用函数再次封装，方便以后选择不同的存储方式（目前使用cookie存储）
 */
function set_redirect_url($url = '', $name = '_redirect_url_')
{
    if (!$url) {
        $url = url()->full();
    }
    Cookie::queue($name, $url, 30);
}

/**
 * 获取跳转页面URL
 * @return string 跳转页URL
 */
function get_redirect_url($name = '_redirect_url_')
{
    $url = Cookie::get($name);
    return $url ? $url : url()->previous();
}

/**
 * 获取用户所属分部
 * @param null $user
 * @return mixed
 */
function get_user_company_id($user = null)
{
    if (is_numeric($user)) {
        $user = \App\User::with('department')->find($user);
    } elseif (!$user) {
        $user = get_current_login_user_info(true);
    }
    if ($user->department->company_id) {
        return $user->department->company_id;
    } else {
        return $user->department->id;
    }
}

/**
 * 检测是否分部管理员
 * @param $user
 * @return bool
 */
function check_company_admin($user = null, $role = "分部管理员")
{
    return check_user_role($user, $role);
}

/**
 * 检测用户角色
 * @param $user
 * @param null $role
 * @return bool
 */
function check_user_role($user = null, $role = null)
{
    if (!$user) {
        $user = get_current_login_user_info(true);
    } elseif (is_numeric($user)) {
        $user = \App\User::find($user);
    }
    if (is_administrator_user($user->id) || ($user->hasRole($role) && $role)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 检测用户所属分部
 * @param $company
 * @param $user
 * @return bool
 */
function check_user_company($company, $user)
{
    if (!is_object($user)) {
        $user = \App\User::with('department')->find($user);
    }
    $user_company_id = $user->department->company_id;
    if (!$user_company_id) {
        $user_company_id = $user->department->id;
    }
    if ($company == $user_company_id) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取某个分部所有部门信息
 * @param $company
 * @param string $field
 * @param string $type
 * @return mixed
 */
function get_company_deparent($company, $field = 'id', $type = 'array')
{
    if (is_array($company)) {
        $dep = \App\Department::whereIn('company_id', $company)->get();
    } else {
        $dep = \App\Department::where('company_id', $company)
            ->orWhere('id', $company)->get();
    }
    if ($field === true) {
        return $type == 'array' ? $dep->toArray() : $dep;
    } else {
        return $type == 'array' ? $dep->pluck($field)->toArray() : $dep->pluck($field);
    }
}

/**
 * 获取部门人数
 * @param $id
 * @param $level
 * @return null
 */
function get_department_user_count($id, $level)
{
    switch ($level) {
        case 1:
        case 2:
            //获取分部所有部门信息
            $dep = get_company_deparent($id);
            if ($dep && count($dep)) {
                return \App\User::where('department_id', $id)
                    ->orWhereIn('department_id', $dep)->count();
            } else {
                return \App\User::where('department_id', $id)->count();
            }
        case 3:
            //部门
            return \App\User::where('department_id', $id)->count();
        default:
            return null;
    }
}

/**
 * 设备类型选择项
 * @param string $selected
 * @return string
 */
function device_select($selected = '')
{
    $list = \App\Device::lists()->toArray();
    $str = '<option value="">请选择设备类型</option>';
    if ($list) {
        foreach ($list as $key => $val) {
            $str .= '<option value="' . $val['id'] . '" '
                . ($selected == $val['id'] ? 'selected="selected"' : '') . '>'
                . $val['name'] . '</option>';
        }
    }
    return $str;
}

/**
 * 格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 问题版块下拉选项
 * @param string $selected
 * @return string
 */
function question_category_select($selected = '')
{
    $list = \App\QuestionCategory::lists()->toArray();
    $str = '<option value="">请选版块</option>';
    if ($list) {
        foreach ($list as $key => $val) {
            $str .= '<option value="' . $val['id'] . '" '
                . ($selected == $val['id'] ? 'selected="selected"' : '') . '>'
                . $val['name'] . '</option>';
        }
    }
    return $str;
}

/**
 * 错误提示
 * @param string $message
 * @param null $data
 * @param string $url
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
 */
function _error($message = '您访问的信息不存在', $data = null, $url = '')
{
    return _404($message, $data, $url);
}

/**
 * 成功提示
 * @param string $message
 * @param null $data
 * @param string $url
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
 */
function _success($message = '操作成功', $data = null, $url = '')
{
    $request = request();
    if ($request->ajax()) {
        if ($request->isMethod('GET')) {
            return view('layouts._success', compact('message'));
        } else {
            return response()->json([
                'message' => $message, 'data' => is_object($data) ? $data->toArray() : $data,
                'status' => 'success', 'url' => $url
            ]);
        }
    } else {
        return view('layouts._success', compact('message'));
    }
}

/**
 * 项目参与人option
 * @param $project
 * @param string $selected
 * @return string
 */
function project_user_select($project, $selected = '', $type = 1)
{
    if (is_numeric($project)) {
        $project = \App\Project::find($project);
    }
    if (1 == $type) {
        $str = '<option value="">请选择接收人</option>';
    } else {
        $str = '';
    }
    if ($project) {
        if ($project->users->isNotEmpty()) {
            foreach ($project->users as $key => $user) {
                if ($selected == $user->id) {
                    $str .= '<option value="' . $user->id . '" selected="selected">' .
                        $user->name . ($user->id == get_current_login_user_info() ? '(我自己)' : '') . '</option>';
                } else {
                    $str .= '<option value="' . $user->id . '">' .
                        $user->name . ($user->id == get_current_login_user_info() ? '(我自己)' : '') . '</option>';
                }
            }
        }
    }
    return $str;
}

/**
 * 建设阶段option
 * @param $project
 * @param string $selected
 * @return string
 */
function project_phase_select($project, $selected = '', $type = 0)
{
    if (is_numeric($project)) {
        $project = \App\Project::find($project)->phases;
    }
    $str = '';
    if ($type) {
        $str .= '<option value="">请选择阶段</option>';
    }
    if ($project->isNotEmpty()) {
        foreach ($project as $key => $phase) {
            if ($selected == $phase->id) {
                $str .= '<option value="' . $phase->id . '" selected="selected">' .
                    $phase->name . '(' . project_phases_status($phase->status) . ')</option>';
            } else {
                $str .= '<option value="' . $phase->id . '">' .
                    $phase->name . '(' . project_phases_status($phase->status) . ')</option>';
            }
        }
    }

    return $str;
}

/**
 * 项目建设阶段状态
 * @param string $selected
 * @param int $type
 * @return string
 */
function project_phases_status_select($selected = '', $type = 0)
{
    $data = config('common.project_phases_status');
    $str = '';
    if ($type) {
        $str = '<option value="">请选择状态</option>';
    }
    foreach ($data as $key => $val) {
        if ($selected == $key) {
            $str .= '<option value="' . $key . '" selected="selected">' . $val['title'] . '</option>';
        } else {
            $str .= '<option value="' . $key . '">' . $val['title'] . '</option>';
        }
    }
    return $str;
}

/**
 * 获取项目建设阶段状态名称
 * @param $status
 * @return null
 */
function project_phases_status($status, $field = 'title')
{
    $data = config('common.project_phases_status');
    return isset($data[$status][$field]) ? $data[$status][$field] : null;
}

/**
 * 任务状态信息
 * @param $status
 * @param string $field
 * @return null
 */
function tasks_status($status, $field = 'title')
{
    $data = config('common.task_status');
    return isset($data[$status][$field]) ? $data[$status][$field] : null;
}

/**
 * 获取某天开始时间与结束时间
 * @param null $date
 * @param string $type
 * @return false|string
 */
function date_start_end($date = null, $type = "start")
{
    if (!$date) {
        $date = time();
    }
    if (is_string($date)) {
        $date = strtotime($date);
    }
    if ($type == 'start') {
        return date('Y-m-d 00:00:00', $date);
    } else {
        return date('Y-m-d 23:59:59', $date);
    }
}

/**
 * 格式化输出项目参与人
 * @param $users
 * @param string $filed
 * @param string $num
 * @return string
 */
function format_project_users($users, $filed = "name", $num = 'all')
{
    $user_arr = [];
    if ($users) {
        foreach ($users as $key => $user) {
            $user_arr[] = $user->$filed;
            if ($num != 'all' && $num == ($key + 1)) {
                break;
            }
        }
    }
    return arr2str($user_arr);
}

/**
 * 头像
 * @param $path
 * @param string $type
 * @return string
 */
function avatar($path, $type = '男')
{
    if (!$path) {
        if ($type == '男') {
            $path = 'assets/app/media/img/users/default.jpg';
        }
    }
    return asset($path);
}

/**
 * 获取某个项目的信息
 * @param $id
 * @param string $field
 * @return null
 */
function get_project_info($id, $field = 'title')
{
    $project = \App\Project::find($id);
    if ($project) {
        return isset($project->$field) ? $project->$field : null;
    } else {
        return null;
    }
}

/**
 * 获取问题状态名称
 * @param $status
 * @return null
 */
function question_status($status, $field = 'title')
{
    $data = config('common.question_status');
    return isset($data[$status][$field]) ? $data[$status][$field] : null;
}

function check_project_owner($project, $power)
{
    if (!is_object($project)) {
        $project = \App\Project::find(intval($project));
    }
    if (!$project) {
        return false;
    }
    if ($power == 'look') {
        if (check_user_role(null, '总部管理员')) { //总部管理员
            return true;
        }
        if (check_company_admin() && get_user_company_id() == $project->department_id) {//分部管理员
            return true;
        }
        if ($project->companyUser && $project->companyUser->id == get_current_login_user_info()) {//项目办事处负责人
            return true;
        }
        if ($project->users()->where('user_id', get_current_login_user_info())->first()) { //项目参与人
            return true;
        }
    } elseif ($power == 'edit') {
        if (check_user_role(null, '总部管理员')) { //总部管理员
            return true;
        }
        if (check_company_admin() && get_user_company_id() == $project->department_id) {//分部管理员
            return true;
        }
        if ($project->companyUser && $project->companyUser->id == get_current_login_user_info()) {//项目办事处负责人
            return true;
        }
    } elseif ($power == 'del') {
        if (check_user_role(null, '总部管理员')) { //总部管理员
            return true;
        }
        if (check_company_admin() && get_user_company_id() == $project->department_id) {//分部管理员
            return true;
        }
    }
    return false;
}

/**
 * 检测某个项目的分部负责人或现场负责人
 * @param $project
 * @param int $type 1分部，2现场
 * @param null $user
 * @return bool
 */
function check_project_leader($project, $type = 1, $user = null)
{
    if (!$user) {
        $user = get_current_login_user_info(true);
    }
    if (is_numeric($project)) {
        $project = \App\Project::find($project);
    }
    if ($type == 1) {
        if ($user->id == $project->subcompany_leader) {
            return true;
        } else {
            return false;
        }
    } elseif ($type == 2) {
        if ($user->id == $project->agent) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

/**
 * 项目状态
 * @param $status
 * @param string $field
 * @return null
 */
function project_status($status, $field = 'title')
{
    $data = config('common.project_status');
    return isset($data[$status][$field]) ? $data[$status][$field] : null;
}

function get_company_user($company = null, $field = true)
{
    if (!$company) {
        $company = get_user_company_id();
    }
    //获取分部所有部门信息
    $dep = get_company_deparent($company);
    if ($dep && count($dep)) {
        $list = \App\User::where('department_id', $company)
            ->orWhereIn('department_id', $dep)->get();
    } else {
        $list = \App\User::where('department_id', $company)->get();
    }
    if ($list) {
        if ($field !== true) {
            return collect($list)->pluck($field)->all();
        } else {
            return $list;
        }
    }
    return null;
}

function project_folder_select($project, $selected = 0, $type = 1)
{
    if (is_numeric($project)) {
        $project = \App\Project::find($project);
    }
    $str = '<option value="0">顶级分类</option>';
    if ($type == 2) {
        $str = '<option value="0">未分类</option>';
    }
    if ($project->folders) {
        foreach (formatTreeData($project->folders->toArray()) as $key => $val) {
            if ($selected == $val['id']) {
                $str .= '<option value="' . $val['id'] . '" selected="selected">' . $val['space'] . $val['name'] . '</option>';
            } else {
                $str .= '<option value="' . $val['id'] . '" >' . $val['space'] . $val['name'] . '</option>';
            }
        }
    }
    return $str;
}

/**
 * 获取某个项目文档分类信息
 * @param $folder
 * @param bool $field
 * @return null
 */
function project_folders_info($folder, $field = true)
{
    if (is_numeric($folder)) {
        $folder = \App\ProjectFolder::find($folder);
    }
    if ($field === true) {
        return $folder;
    }
    return isset($folder->$field) ? $folder->$field : null;
}

/**
 * 获取当前项目所处阶段
 * @param $project
 * @return mixed
 */
function get_project_current_phase($project)
{
    if (is_object($project)) {
        $current_phase = $project->phases()->where('status', '<', 2)
            ->orderBy('id', 'asc')->first();
    }else{
        $current_phase = \App\ProjectPhase::where('project_id', $project)->where('status', '<', 2)
            ->orderBy('id', 'asc')->first();
    }
    return $current_phase;
}

function current_date()
{
    return \Carbon\Carbon::now()->toDateString();
}

function is_image($ext)
{
    $ext_arr = config('filesystems.disks.image.validate.ext')
        ? config('filesystems.disks.image.validate.ext')
        : ['jpg','jpeg','png','gif','bmp'];
    if (in_array($ext,$ext_arr)) {
        return true;
    } else {
        return false;
    }
}