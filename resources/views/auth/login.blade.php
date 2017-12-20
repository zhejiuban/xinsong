<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name', '新松项目管理系统') }}</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1, maximum-scale=1, user-scalable=no" >

        <!--begin::Base Styles -->
        <link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/theme/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <!--end::Base Styles -->
        <link rel="shortcut icon" href="{{ asset('assets/theme/default/media/img/logo/favicon.ico') }}" />
    </head>
    <!-- end::Head -->
    <!-- end::Body -->
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-1" id="m_login" style="background-image: url({{ asset('assets/app/media/img/bg/bg-1.jpg') }});">
                <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
                    <div class="m-login__container">
                        <div class="m-login__logo">
                            <a href="#">
                                <img src="{{ asset('assets/app/media/img/logos/logo-1.png') }}">
                            </a>
                        </div>
                        <div class="m-login__signin">
                            {{-- <!--<div class="m-login__head">
                                <h3 class="m-login__title">
                                    {{ config('app.name', '新松项目管理系统') }}
                                </h3>
                            </div>--> --}}
                            <form class="m-login__form m-form"  method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <div class="form-group m-form__group {{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <input class="form-control m-input"   type="text" placeholder="账号/手机号/邮箱" name="email" autocomplete="off" required autofocus value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <div id="email-error" class="form-control-feedback"><strong>{{ $errors->first('email') }}</strong></div>
                                    @endif
                                </div>
                                <div class="form-group m-form__group {{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <input class="form-control m-input m-login__form-input--last" type="password" placeholder="密码" name="password">

                                    @if ($errors->has('password'))
                                        <div id="password-error" class="form-control-feedback"><strong>{{ $errors->first('password') }}</strong></div>
                                    @endif

                                </div>
                                <div class="row m-login__form-sub">
                                    <div class="col m--align-left m-login__form-left">
                                        <label class="m-checkbox  m-checkbox--light">
                                            <input type="checkbox" name="remember"  {{ old('remember') ? 'checked' : '' }}>
                                            记住我
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col m--align-right m-login__form-right">
                                        <a href="javascript:;" id="m_login_forget_password" class="m-link">
                                            忘记密码 ?
                                        </a>
                                    </div>
                                </div>
                                <div class="m-login__form-action">
                                    <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary">
                                        登 录
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="m-login__signup">
                            <div class="m-login__head">
                                <h3 class="m-login__title">
                                    Sign Up
                                </h3>
                                <div class="m-login__desc">
                                    Enter your details to create your account:
                                </div>
                            </div>
                            <form class="m-login__form m-form" action="">
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="text" placeholder="Fullname" name="fullname">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="password" placeholder="Password" name="password">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Confirm Password" name="rpassword">
                                </div>
                                <div class="row form-group m-form__group m-login__form-sub">
                                    <div class="col m--align-left">
                                        <label class="m-checkbox m-checkbox--light">
                                            <input type="checkbox" name="agree">
                                            I Agree the
                                            <a href="#" class="m-link m-link--focus">
                                                terms and conditions
                                            </a>
                                            .
                                            <span></span>
                                        </label>
                                        <span class="m-form__help"></span>
                                    </div>
                                </div>
                                <div class="m-login__form-action">
                                    <button id="m_login_signup_submit" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                                        Sign Up
                                    </button>
                                    &nbsp;&nbsp;
                                    <button id="m_login_signup_cancel" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div> -->
                        <div class="m-login__forget-password">
                            <div class="m-login__head">
                                <h3 class="m-login__title">
                                    忘记密码 ?
                                </h3>
                                <div class="m-login__desc">
                                    输入你的邮箱找回密码:
                                </div>
                            </div>
                            <form class="m-login__form m-form" action="">
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="text" placeholder="您的邮箱" name="email" id="m_email" autocomplete="off">
                                </div>
                                <div class="m-login__form-action">
                                    <button id="m_login_forget_password_submit" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                                        确定
                                    </button>
                                    &nbsp;&nbsp;
                                    <button id="m_login_forget_password_cancel" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn">
                                        取消
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="m-login__account">
                            <span class="m-login__account-msg">
                                Don't have an account yet ?
                            </span>
                            &nbsp;&nbsp;
                            <a href="javascript:;" id="m_login_signup" class="m-link m-link--light m-login__account-link">
                                Sign Up
                            </a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Page -->
        <!--begin::Base Scripts -->
        <script src="{{ asset('assets/vendors/base/vendors.bundle.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/theme/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
        <!--end::Base Scripts -->
        <!--begin::Page Snippets -->
        <script src="{{ asset('assets/snippets/pages/user/login.js') }}" type="text/javascript"></script>
        <!--end::Page Snippets -->
    </body>
    <!-- end::Body -->
</html>
