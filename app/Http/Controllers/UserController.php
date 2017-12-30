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
        if (!check_permission('user/users')) {
            return _404('无权操作');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $search = $request->input('datatable.query.search');
            $department = $request->input('datatable.query.department_id');
            if (is_administrator() || check_user_role(null,'总部管理员')) {
                $user = User::with(['department', 'roles'])->when($search,function ($query) use ($search){
                    return $query->where(function ($query) use ($search){
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('username', 'like', "%$search%")
                            ->orWhere('tel', 'like', "%$search%");
                    });
                })->when($department,function ($query) use ($department){
                    $department_arr = (array) get_company_deparent($department);
                    return $query->whereIn('department_id',array_unique($department_arr));
                })->orderBy(
                    $sort_field
                    , $sort)->paginate(
                    $prepage
                    , ['*']
                    , 'datatable.pagination.page'
                );
            } else {
                $user = User::with(['department', 'roles'])->when($search,function ($query) use ($search){
                    return $query->where(function ($query) use ($search){
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('username', 'like', "%$search%")
                            ->orWhere('tel', 'like', "%$search%");
                    });
                })->when($department,function ($query) use ($department){
                    $department_arr = (array) get_company_deparent($department);
                    return $query->whereIn('department_id',array_unique($department_arr));
                })->where(function ($query) {
                    //获取用户所属分部所有部门
                    $query->whereIn('department_id', get_company_deparent(get_user_company_id()));
                })->orderBy(
                    $sort_field
                    , $sort)->paginate(
                    $prepage
                    , ['*']
                    , 'datatable.pagination.page'
                );
            }
            if($user){
                foreach ($user as $key=>$val){
                    $user[$key]->company = $val->company();
                }
            }
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
        if (!check_permission('user/users/create')) {
            return _404('无权操作');
        }
        return view('user.default.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if (!check_permission('user/users/create')) {
            return _404('无权操作');
        }
        $user = new User;
        $user->username = $request->username;
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->tel = $request->tel;
        $user->sex = $request->sex;
        $user->department_id = $request->department_id ? $request->department_id : 0;
        $user->status = $request->status ? 1 : 0;
        if ($user->save()) {
            //授权角色
            if ($request->role_id) {
                $user->syncRoles($request->role_id);
            }
            activity('系统日志')->performedOn($user)
                ->withProperties($user)
                ->log('新增用户');
            return response()->json([
                'message' => '保存成功', 'status' => 'success',
                'url' => route('users.index'), 'data' => $user->toArray()
            ]);
        } else {
            return _404('保存失败');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('user/users/edit')) {
            return _404('无权操作');
        }
        if (is_administrator()) {
            $user = User::with(['department', 'roles'])->find($id);
        } else {
            $user = User::with(['department', 'roles'])->whereIn('department_id'
                , get_company_deparent(get_user_company_id()))->find($id);
        }
        if ($user) {
            return view('user.default.edit', compact('user'));
        } else {
            return _404('无权操作');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        if (!check_permission('user/users/edit')) {
            return _404('无权操作');
        }
        if (is_administrator()) {
            $user = User::find($id);
        } else {
            $user = User::whereIn('department_id'
                , get_company_deparent(get_user_company_id()))->find($id);
        }
        if ($user) {
            $user->username = $request->username;
            $user->name = $request->name;
            if ($request->password) {
                $user->password = $request->password;
            }
            $user->email = $request->email;
            $user->tel = $request->tel;
            $user->sex = $request->sex;
            $is_own = $user->id !== get_current_login_user_info();
            if(!is_administrator_user($user->id)){
                $user->department_id = $request->department_id ? $request->department_id : 0;
                if($is_own){
                    $user->status = $request->status ? 1 : 0;
                }
            }
            if ($user->save()) {
                //授权角色
                if (!is_administrator_user($user->id) && $is_own) {
                    if ($request->role_id) {
                        $user->syncRoles($request->role_id);
                    } else {
                        $user->roles()->detach();
                    }
                }
                activity('系统日志')->performedOn($user)
                    ->withProperties($user)
                    ->log('更新用户');
                return response()->json([
                    'message' => '保存成功', 'status' => 'success',
                    'url' => route('users.index'), 'data' => $user->toArray()
                ]);
            } else {
                return _404('保存失败');
            }
        } else {
            return _404('无权操作');
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
        if (!check_permission('user/users/destroy')) {
            return _404('无权操作');
        }
        if (is_administrator()) {
            $user = User::find($id);
        } else {
            $user = User::whereIn('department_id'
                , get_company_deparent(get_user_company_id()))->find($id);
        }
        if ($user) {
            if ($user->id == get_current_login_user_info()) {
                return _404('您不能删除自己');
            }
            if (is_administrator_user($user->id)) {
                return _404('系统管理员不允许被删除');
            }
            //判断是否有相关项目
            if($user->projects->isNotEmpty() || $user->leaderTasks->isNotEmpty()
                || $user->dynamics->isNotEmpty() || $user->questions->isNotEmpty()
                || $user->receiveQuestions->isNotEmpty()){
                return _404('该用户有关联数据不允许被删除');
            }

            if ($user->delete()) {
                activity('系统日志')->performedOn($user)
                    ->withProperties($user)
                    ->log('删除用户');
                return response()->json([
                    'message' => '您操作的信息已被删除', 'data' => $user->toArray(),
                    'url' => route('users.index'), 'status' => 'success'
                ]);
            }
            return _404('您操作的数据删除失败，未知错误');
        } else {
            return _404('无权操作');
        }
    }

    public function power(Request $request)
    {
        if (!check_permission('user/users/power')) {
            return _404('无权操作');
        }
        $this->validate($request, [
            'id' => 'bail|required'
        ], [
            'id.required' => '请选择要操作的数据'
        ]);
        //获取用户实例
        if(is_administrator()){
            $user = User::whereIn('id', $request->id)->get();
        }else{
            $user = User::whereIn('id', $request->id)->whereIn('department_id'
                , get_company_deparent(get_user_company_id()))->get();
        }
        if ($user->isNotEmpty()) {
            foreach ($user as $k => $v) {
                if (!is_administrator_user($v->id)
                    && $v->id !== get_current_login_user_info()) {
                    if ($request->roles) {
                        $v->syncRoles($request->roles);
                    } else {
                        $v->roles()->detach();
                    }
                }
            }
        }else{
            return _404('无权操作');
        }
        activity('系统日志')
            ->withProperties($user)
            ->log('授权成功');
        return response()->json([
            'message' => '授权成功', 'status' => 'success',
            'data' => null, 'url' => route('users.index')
        ]);
    }

    public function editPwd(Request $request)
    {
        if (!check_permission('user/users/edit')) {
            return _404('无权操作');
        }
        $this->validate($request, [
            'id' => 'bail|required',
            'password' => 'bail|required|min:6'
        ], [
            'id.required' => '请选择要操作的数据',
            'password.required' => '请输入密码',
            'password.min' => '密码长度不能小于6位',
        ]);
        if(is_administrator()){
            $user = User::whereIn('id', $request->id)->get();
        }else{
            $user = User::whereIn('id', $request->id)->whereIn('department_id'
                , get_company_deparent(get_user_company_id()))->get();
        }
        if ($user->isNotEmpty()) {
            $update = [];
            foreach ($user as $k => $v) {
                if (is_administrator()) {
                    $update[] = $v->id;
                } elseif (!is_administrator_user($v->id)) {
                    $update[] = $v->id;
                }
            }
            if (count($update)) {
                User::whereIn('id', $update)->update([
                    'password' => bcrypt($request->password)
                ]);
            }
        }else{
           return _404('无权操作');
        }
        activity('系统日志')
            ->withProperties($user)
            ->log('重置密码');
        return response()->json([
            'message' => '重置成功，新密码为 ' . $request->password, 'status' => 'success',
            'data' => null, 'url' => route('users.index')
        ]);
    }

    /**
     * 修改个人资料
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(Request $request){
        $user = get_current_login_user_info(true);
        if($request->isMethod('PUT')){
            //验证
            $this->validate($request,[
                'password'=>'bail|nullable|min:6',
                'name'=>'bail|required',
                'email'=>'bail|required|email|unique:users,email,'.$user->id,
                'tel'=>'bail|required|unique:users,tel,'.$user->id
            ],[
                'password.required'=>'请输入登录密码',
                'password.min'=>'密码不能小于6个字符',
                'name.required'=>'请输入姓名',
                'email.required'=>'请输入邮箱',
                'email.email'=>'请输入正确格式的邮箱',
                'email.unique'=>'邮箱已存在',
                'tel.required'=>'请输入手机号',
                'tel.unique'=>'手机号已存在',
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->tel = $request->tel;
            $user->sex = $request->sex;
            $user->avatar = $request->avatar;
            if ($request->password) {
                $user->password = $request->password;
            }
            if($user->save()){
                activity('系统日志')->performedOn($user)
                    ->withProperties($user)
                    ->log($user->username.'更新个人资料');
                return _success('更新成功');
            }else{
                return _error('更新失败');
            }
        }else{
            return view('user.default.personal',compact('user'));
        }
    }

}
