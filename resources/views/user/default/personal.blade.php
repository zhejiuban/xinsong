<form action="{{ route('user.profile') }}" class="m-form m-form--fit " method="post" id="user-profile-form">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group ">
                <label>
                    用户名<span class="required">*</span>:
                </label>
                <input type="text" class="form-control m-input" name="username"
                       disabled value="{{$user->username}}" placeholder="用户名">
                <span class="m-form__help"></span>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group ">
                <label>
                    登录密码:
                </label>
                <input type="password" class="form-control m-input" name="password" value="" placeholder="登录密码">
                <span class="m-form__help">留空不修改</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group ">
                <label>
                    姓名<span class="required">*</span>:
                </label>
                <input type="text" name="name" value="{{$user->name}}" class="form-control m-input" placeholder="姓名">
                <span class="m-form__help"></span>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group ">
                <label class="">
                    性别<span class="required">*</span>:
                </label>
                <div class="m-radio-inline">
                    <label class="m-radio">
                        <input type="radio" name="sex" value="男" {{ $user->sex == '男' ? 'checked' : '' }}>
                        男
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input type="radio" name="sex" value="女" {{ $user->sex == '男' ? '' : 'checked' }}>
                        女
                        <span></span>
                    </label>
                </div>
                <span class="m-form__help"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label>
                    邮箱<span class="required">*</span>:
                </label>
                <input type="text" class="form-control m-input" name="email" value="{{$user->email}}" placeholder="邮箱">
                <span class="m-form__help"></span>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="">
                    手机号<span class="required">*</span>:
                </label>
                <input type="text" class="form-control m-input" name="tel" value="{{$user->tel}}" placeholder="手机号">
                <span class="m-form__help"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label>
                    所属部门<span class="required">*</span>:
                </label>
                <div>
                    {{$user->company()->name}} / {{$user->department->name}}
                </div>
            </div>
        </div>
    </div>
    {{ csrf_field() }}
    {{method_field('PUT')}}
</form>
<script type="text/javascript">
    jQuery(document).ready(function () {
        mAppExtend.select2Instance("#department_id");
        $("#user-profile-form-submit").click(function () {
            $("#user-profile-form").ajaxSubmit(
                {
                    beforeSend: function () {
                        mApp.block($('#m_quick_sidebar'));
                    },
                    complete: function () {
                        mApp.unblock($('#m_quick_sidebar'));
                    },
                    success: function (response, status, xhr, $form) {
                        if (response.status == 'success') {
                            mAppExtend.notification(response.message, 'success', 'toastr', function () {
                                // mAppExtend.backUrl(response.url);
                                $('#m_quick_sidebar_close').click();
                            });
                        } else {
                            mAppExtend.notification(response.message, 'error');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        _$error = xhr.responseJSON.errors;
                        var _err_mes = '未知错误，请重试';
                        if (_$error != undefined) {
                            _err_mes = '';
                            $.each(_$error, function (i, v) {
                                _err_mes += v[0] + '<br>';
                            });
                        }
                        mAppExtend.notification(_err_mes, 'error');
                    }
                }
            );
        });
    });
</script>
