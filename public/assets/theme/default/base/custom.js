var mAppExtend = function () {
    /**
     * laravel route js版
     * @param  {[type]} routeUrl [description]
     * @param  {[type]} param    [description]
     * @return {[type]}          [description]
     */
    var route = function route(routeUrl, param) {
        var append = [];

        for (var x in param) {
            var search = '{' + x + '}';

            if (routeUrl.indexOf(search) >= 0) {
                routeUrl = routeUrl.replace('{' + x + '}', param[x]);
            } else {
                append.push(x + '=' + param[x]);
            }
        }

        var url = '/' + _.trimStart(routeUrl, '/');

        if (append.length == 0) {
            return url;
        }

        if (url.indexOf('?') >= 0) {
            url += '&';
        } else {
            url += '?';
        }

        url += append.join('&');

        return url;
    };
    /**
     * ajax自动载入页面
     * @return {[type]} [description]
     */
    var handleReloadHtml = function () {
        $('body').on('click', 'a[data-toggle="relaodHtml"],button[data-toggle="relaodHtml"]', function (e) {
            e.preventDefault();
            var el = $(this).data('target');
            var url = $(this).attr('href') ? $(this).attr('href') : $(this).data('href');
            var query = $(this).data('query');
            var loading = $(this).data('loading') ? true : false;
            if (url) {
                mAppExtend.ajaxGetHtml(el, url, query, loading);
            }
        });
    };
    /**
     * 下框初始化
     * @return {[type]} [description]
     */
    var handleSelect2 = function () {
        if ($().select2) {
            // $.fn.select2.defaults.set("theme", "bootstrap");
            $('.select2').select2({
                width: '100%'
            });
        }
    };
    /**
     * 手机号验证
     * @return {[type]} [description]
     */
    var handleValidatorExtendMethod = function () {
        if ($.validator) {
            // 手机号码验证
            $.validator.addMethod("isMobile", function (value, element) {
                var length = value.length;
                var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
                return this.optional(element) || (length == 11 && mobile.test(value));
            }, "请正确填写您的手机号码");
        }
    };
    var initToastr = function () {
      if(toastr){
        toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": true,
          "progressBar": false,
          "positionClass": "toast-top-center",
          "preventDuplicates": true,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "2000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };
      }
    }
    return {
        init: function () {
            initToastr();
            handleReloadHtml();
            handleSelect2();
            handleValidatorExtendMethod();
        },
        notification: function(message,type,notifyType,callback,options){
          if(notifyType == 'notify'){
            var option = $.extend(true,{
              'message':'',
              'options':{
                  type: 'success',
                  placement: {
                      from: "top",
                      align: "center"
                  },
                  delay:1000,
                  onClose:null
              }
            },options);
            if(type != undefined){
              option.options.type = type;
            }
            if(message != undefined){
              option.message = message;
            }
            if(callback != undefined){
              option.options.onClose = callback;
            }
            $.notify(option.message,option.options);
          }else{
            var option = $.extend(true,{
              'message':'',
              'title':null,
              'options':{
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "positionClass": "toast-top-center",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
              }
            },options);
            if(message != undefined){
              option.message = message;
            }
            if(type == undefined){
              type = 'info';
            }
            toastr.options = option.options;
            if(option.title){
              toastr[type](option.message,option.title);
            }else{
              toastr[type](option.message);
            }
            if(callback instanceof Function){
              window.setTimeout(function () {
                callback();
              }, option.options.hideDuration);
            }
          }

        },
        laravelRoute: function (routeUrl, param) {
            return route(routeUrl, param);
        },
        //返回某个url页面
        backUrl: function (url, time) {
            if (url == 'reload') {
                if(time){
                  window.setTimeout(function () {
                    location.reload();
                  }, time);
                }else{
                  location.reload();
                }
                return;
            }
            url = url != undefined ? url : 'javascript:history.back()';
            if (time) {
                window.setTimeout(function () {
                    window.location.href = url;
                }, time);
            } else {
                window.location.href = url;
            }
        },
        /*ajax加载页面*/
        ajaxGetHtml: function (el, url, query, isLoading, callback, errorCallback) {
            var loading = isLoading ? true : false;
            var loadingEl = isLoading != true ? isLoading : 'body'
            jQuery.ajax({
                url: url,
                type: 'GET',
                dataType: 'HTML',
                cache: false,
                data: query,
                beforeSend: function () {
                    if (loading) {
                        mApp.block(loadingEl, {
                            overlayColor: '#000000',
                            type: 'loader',
                            state: 'primary',
                            message: '正在加载...'
                        });
                    }
                },
                complete: function (xhr, textStatus) {
                    //called when complete
                    if (loading) {
                        mApp.unblock(loadingEl);
                    }
                },
                success: function (data, textStatus, xhr) {
                    //called when successful
                    $(el).html(data);
                    if (callback instanceof Function) {
                        callback(data, textStatus, xhr);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    //called when there is an error
                    if (errorCallback instanceof Function) {
                        errorCallback(xhr, textStatus, errorThrown);
                    } else {
                        if (xhr.status == 401) { //未认证
                          mAppExtend.notification('登录超时'
                            ,'error','toastr',function() {
                                mAppExtend.backUrl('reload');
                              });
                        } else {
                          mAppExtend.notification('请求错误，请重试'
                            ,'error');
                        }
                    }
                }
            });
        },
        ajaxPostSubmit: function (options) {
            var option = $.extend(true, {
                'type': 'POST',
                'dataType': 'json',
                'el': '',
                'url': '',
                'redirect': true,
                'query': {},
                'showLoading': true,
                'callback': null,//成功毁掉函数
                'errorCallback': null//失败毁掉函数
            }, options);
            jQuery.ajax({
                url: option.url,
                type: option.type,
                dataType: option.dataType,
                data: option.query,
                beforeSend: function () {
                    if (option.showLoading) {
                        mApp.block(option.el ? option.el : '', {
                            overlayColor: '#000000',
                            type: 'loader',
                            state: 'primary',
                            message: '正在加载...'
                        });
                    }
                },
                complete: function (xhr, textStatus) {
                    //called when complete
                    if (option.showLoading) {
                        mApp.unblock(option.el ? option.el : '');
                    }
                },
                success: function (data, textStatus, xhr) {
                    //called when successful
                    if (option.callback instanceof Function) {
                        option.callback(data, textStatus, xhr);
                    } else {
                        if (data.status == 'success') {
                          mAppExtend.notification(data.message
                            ,'success','toastr',function() {
                                if (option.redirect) {
                                    mAppExtend.backUrl(data.url);
                                }
                            });
                        } else {
                          mAppExtend.notification(data.message
                            ,'error');
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    //called when there is an error
                    if (option.errorCallback instanceof Function) {
                        option.errorCallback(xhr, textStatus, errorThrown);
                    } else {
                        var _err_mes = '未知错误，请重试';
                        if (xhr.responseJSON != undefined) {
                            _$error = xhr.responseJSON.errors;
                            if (_$error != undefined) {
                                _err_mes = '';
                                $.each(_$error, function (i, v) {
                                    _err_mes += v[0] + '<br>';
                                });
                            }
                        }
                        mAppExtend.notification(_err_mes
                          ,'error');
                    }
                }
            });
        }
    }
}();
$(document).ready(function () {
    mAppExtend.init();
});
