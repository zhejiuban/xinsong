<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $user = User::with(['department','roles'])->where(
                'name', 'like',
                "%{$request->input('datatable.query.search')}%"
            )->orderBy(
                $sort_field
                , $sort)->paginate(
                $prepage
                , ['*']
                , 'datatable.pagination.page'
            );
            $meta = [
                'field' => $sort_field,
                'sort' => $sort,
                'page' => $user->currentPage(),
                'pages' => $user->hasMorePages(),
                'perpage' => $prepage,
                'total' => $user->total()
            ];
            $data = $user->toArray();
            $data['meta'] = $meta;
            return response()->json($data);
        }
        return view('user.default.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.default.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User;
        $user->username = $request->username;
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->tel = $request->tel;
        $user->sex = $request->sex;
        $user->department_id = $request->department_id ? $request->department_id : 0;
        $user->status = $request->status ? 1 : 0;
        if ($user->save()){
            //授权角色
            if($request->role_id){
                $user->syncRoles($request->role_id);
            }
            return response()->json([
                'message'=>'保存成功','status'=>'success',
                'url'=>route('users.index'),'data'=>$user->toArray()
            ]);
        }else{
            return _404('保存失败');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with(['department','roles'])->find($id);
        if($user){
            return view('user.default.edit',compact('user'));
        }else{
            return _404('你访问的信息不存在');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        if($user){
            $user->username = $request->username;
            $user->name = $request->name;
            if($request->password){
                $user->password = $request->password;
            }
            $user->email = $request->email;
            $user->tel = $request->tel;
            $user->sex = $request->sex;
            $user->department_id = $request->department_id ? $request->department_id : 0;
            $user->status = $request->status ? 1 : 0;
            if ($user->save()){
                //授权角色
                if(!is_administrator_user($user->id)){
                    if($request->role_id){
                        $user->syncRoles($request->role_id);
                    }else{
                        $user->roles()->detach();
                    }
                }
                return response()->json([
                    'message'=>'保存成功','status'=>'success',
                    'url'=>route('users.index'),'data'=>$user->toArray()
                ]);
            }else{
                return _404('保存失败');
            }
        }else{
            return _404('您访问信息不存在');
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
        $user = User::find($id);
        if($user){
            if(is_administrator_user($user->id)){
                return  _404('系统管理员不允许被删除');
            }
            //判断是否有相关项目

            if($user->delete()){
                return response()->json([
                    'message'=>'您操作的信息已被删除','data'=>$user->toArray(),
                    'url'=>route('users.index'),'status'=>'success'
                ]);
            }
            return _404('您操作的数据删除失败，未知错误');
        }else{
            return _404('您删除的信息不存在');
        }
    }

    public function power(Request $request){
        Validator::make($request->all(),[
            'id'=>'bail|required'
        ],[
            'id.required'=>'请选择要操作的数据'
        ])->validate();
        //获取用户实例
        $user = User::whereIn('id',$request->id)->get();
        if($user->isNotEmpty()){
            foreach ($user as $k=>$v){
                if($v->id != config('auth.administrator')){
                    if($request->roles){
                        $v->syncRoles($request->roles);
                    }else{
                        $v->roles()->detach();
                    }
                }
            }
        }
        return response()->json([
            'message'=>'授权成功','status'=>'success',
            'data'=>null,'url'=>route('users.index')
        ]);
    }

    public function editPwd(Request $request){
        Validator::make($request->all(),[
            'id'=>'bail|required',
            'password'=>'bail|required|min:6'
        ],[
            'id.required'=>'请选择要操作的数据',
            'password.required'=>'请输入密码',
            'password.min'=>'密码长度不能小于6位',
        ])->validate();

        $user = User::whereIn('id',$request->id)->get();
        if($user->isNotEmpty()){
            $update = [];
            foreach ($user as $k=>$v){
                if(is_administrator()){
                    $update[] = $v->id;
                }elseif($v->id != config('auth.administrator')){
                    $update[] = $v->id;
                }
            }
            if(count($update)){
                User::whereIn('id',$update)->update([
                    'password'=>bcrypt($request->password)
                ]);
            }
        }
        return response()->json([
            'message'=>'重置成功，新密码为 '.$request->password,'status'=>'success',
            'data'=>null,'url'=>route('users.index')
        ]);
    }
}
