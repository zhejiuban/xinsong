<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //获取菜单信息
        $menu = Menu::get()->toArray();
        $list = formatTreeData($menu);
        return view('system.menu.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        $menu = new Menu();
        $menu->parent_id = $request->parent_id;
        $menu->title = $request->title;
        $menu->url = $request->url;
        $menu->status = $request->status;
        $menu->target = $request->target;
        $menu->hide = $request->hide;
        $menu->icon_class = $request->icon_class ? $request->icon_class : '';
        $menu->sort = $request->sort ? intval($request->sort) : 0;
        $menu->tip = $request->tip ? $request->tip : '';
        $menu->uniqid = $request->url;
        $menu->guard_name = 'web';
        if ($menu->save()) {
            //权限同步
            $menu->syncPermissions($menu);
            return response()->json([
                'message' => '添加成功', 'url' => route('menus.index'),
                'data' => $menu->toArray(), 'status' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => '保存失败', 'url' => null,
                'data' => null, 'status' => 'error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::find($id);
        if ($menu) {
            return view('system.menu.edit', compact('menu'));
        } else {
            return _404('你访问的页面不存在');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $request, $id)
    {
        $menu = Menu::find($id);
        $origin = $menu->url;
        if ($menu) {
            $menu->parent_id = $request->parent_id;
            $menu->title = $request->title;
            $menu->url = $request->url;
            $menu->status = $request->status;
            $menu->target = $request->target;
            $menu->hide = $request->hide;
            $menu->icon_class = $request->icon_class ? $request->icon_class : '';
            $menu->sort = $request->sort ? intval($request->sort) : 0;
            $menu->tip = $request->tip ? $request->tip : '';
            $menu->uniqid = $request->url;
            $menu->guard_name = 'web';
            if ($menu->save()) {
                //权限同步
                $menu->syncUpdatePermissions($menu, $origin);
                return response()->json([
                    'message' => '编辑成功', 'url' => route('menus.index'),
                    'data' => $menu->toArray(), 'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'message' => '保存失败', 'url' => null,
                    'data' => null, 'status' => 'error'
                ]);
            }
        } else {
            return response()->json([
                'message' => '你访问信息不存在', 'status' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = [
            'status' => 'success',
            'message' => '删除成功',
            'data' => '',
            'url' => route('menus.index'),
        ];
        $dp = Menu::find($id);
        if ($dp) {
            $flag = 1;
            //判断是否有子菜单
            if (Menu::where(['parent_id' => $id])->first()) {
                $result['status'] = 0;
                $result['message'] = '不能删除，请先删除子菜单';
                $flag = 0;
            }
            //删除
            if ($flag && !$dp->delete()) {
                $result['status'] = 'error';
                $result['message'] = '删除失败';
            } else {
                //同步清除相关权限
                Menu::syncDeletePermissions($dp);
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = '您操作的信息不存在';
        }
        return response()->json($result);
    }

    public function sync()
    {
        $nodes = Menu::where('guard_name', 'web')->get()->toArray();
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
        if(count($data)){
            foreach ($data as $k => $row) {
                Permission::create($row);
            }
        }
        return response()->json(['message'=>'同步完成','status'=>'success']);
    }
}
