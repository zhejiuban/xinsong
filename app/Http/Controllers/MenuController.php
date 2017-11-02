<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Menu;
use Illuminate\Http\Request;

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
        return view('system.menu.index',compact('list'));
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
     * @param  \Illuminate\Http\Request  $request
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
        if($menu->save()){
            //权限同步
            $menu->syncPermissions($menu);
            return response()->json([
                'message'=>'添加成功','url'=>route('menus.index'),
                'data'=>$menu->toArray(),'status'=>'success'
            ]);
        }else{
            return response()->json([
                'message'=>'保存失败','url'=>null,
                'data'=>null,'status'=>'error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::find($id);
        if($menu){
            return view('system.menu.edit',compact('menu'));
        }else{
            return _404('你访问的页面不存在');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $request, $id)
    {
        $menu = Menu::find($id);
        $origin = $menu->url;
        if($menu){
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
            if($menu->save()){
                //权限同步
                $menu->syncUpdatePermissions($menu,$origin);
                return response()->json([
                    'message'=>'编辑成功','url'=>route('menus.index'),
                    'data'=>$menu->toArray(),'status'=>'success'
                ]);
            }else{
                return response()->json([
                    'message'=>'保存失败','url'=>null,
                    'data'=>null,'status'=>'error'
                ]);
            }
        }else{
            return response()->json([
                'message'=>'你访问信息不存在','status'=>'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
