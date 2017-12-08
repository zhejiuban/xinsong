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
        $('body').on('click', 'a[data-toggle="relaodHtml"],button[data-toggle="relaodHtml"],a[data-toggle="loadHtml"],button[data-toggle="loadHtml"]', function (e) {
            e.preventDefault();
            var el = $(this).data('target');
            var url = $(this).attr('href') ? $(this).attr('href') : $(this).data('href');
            var query = $(this).data('query');
            var loading = $(this).data('loading') ? $(this).data('loading') : false;
            if (url) {
                mAppExtend.ajaxGetHtml(el, url, query, loading);
            }
        });
        $('[data-toggle="autoLoadHtml"]').each(function(i,v) {
            var el = $(v).data('target');
            var url = $(v).attr('href') ? $(v).attr('href') : $(v).data('href');
            var query = $(v).data('query');
            var loading = $(v).data('loading') ? $(this).data('loading') : false;
            if (url) {
                mAppExtend.ajaxGetHtml(el, url, query, loading);
            }
        })
    };
    /**
     * 下框初始化
     * @return {[type]} [description]
     */
    var handleSelect2 = function (el) {
        if ($().select2) {
            // $.fn.select2.defaults.set("theme", "bootstrap");
            var ele = el ? el : '.select2';
            $(ele).select2({
                language:'zh-CN',
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
    };
    var handleDatePicker = function(el,options){
        var ele = el ? el : '.m-date';
        var options = $.extend(true, {
            language: "zh-CN",
            todayBtn: "linked",
            clearBtn: true,
            // orientation: "bottom left",
            zIndexOffset: 200,
            todayHighlight: true,
            format: "yyyy-mm-dd",
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }, options);
        $(ele).datepicker(options);
    }
    var handleDateTimePicker = function (el, options) {
        var ele = el ? el : '.m-datetime';
        var options = $.extend(true, {
            language: "zh-CN",
            todayBtn: "linked",
            clearBtn: true,
            // orientation: "bottom left",
            zIndexOffset: 200,
            todayHighlight: true,
            format: "yyyy-mm-dd hh:ii:ss",
            // pickerPosition: 'bottom-left',
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }, options);
        $(ele).datetimepicker(options);
    }
    var handleTooltips = function (el) {
        // init bootstrap tooltips
        var el = el ? el : '[data-toggle="m-tooltip"]';
        $(el).each(function () {
            var el = $(this);
            var skin = el.data('skin') ? 'm-tooltip--skin-' + el.data('skin') : '';

            el.tooltip({
                template: '<div class="m-tooltip ' + skin + ' tooltip" role="tooltip">\
                    <div class="arrow"></div>\
                    <div class="tooltip-inner"></div>\
                </div>'
            });
        });
    }
    return {
        init: function () {
            initToastr();
            handleReloadHtml();
            handleSelect2();
            handleValidatorExtendMethod();
            handleDatePicker();
            handleDateTimePicker();
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
            var loadingEl = isLoading != true ? isLoading : 'body';
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
        },
        deleteData:function(options){
          var options = $.extend(true, {
            title:'你确定要执行此操作吗?',
            text:"此操作的数据无法撤销，请谨慎操作!",
            url:'',
            data:{'_method': 'DELETE'},
            callback:null,
            successTimer:1000,
            errorTimer:2000
          }, options);
          swal({
            title: options.title,
            text: options.text,
            type: "warning",
            cancelButtonText: '取消',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "确定",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
            $.ajax({
              url: options.url,
              type: 'POST',
              dataType: 'json',
              data: options.data,
              success:function(response, status, xhr) {
                if(response.status == 'success'){
                  swal({
                    timer: options.successTimer,
                    title:'操作成功',
                    text:"您的操作数据已被处理",
                    type:'success'
                  });
                  setTimeout(function () {
                    if (options.callback instanceof Function) {
                        options.callback(response, status, xhr);
                    }else{
                        mAppExtend.backUrl(response.url);
                    }
                  }, options.successTimer);
                }else{
                  swal({
                    timer: options.errorTimer,
                    title:'操作失败',
                    text:response.message,
                    type:'error'
                  });
                }
              },
              error:function(xhr, textStatus, errorThrown) {
                _$error = xhr.responseJSON.errors;
                var _err_mes = '未知错误，请重试';
                if(_$error != undefined){
                    _err_mes = '';
                    $.each(_$error, function (i, v) {
                        _err_mes += v[0] + '<br>';
                    });
                }
                swal({
                  timer: options.errorTimer,
                  title:'删除失败',
                  text:_err_mes,
                  type:'error'
                });
              }
            });
          });
        },
        confirmControllData:function(options){
          mAppExtend.deleteData(options);
        },
        fileUpload:function(options){
            // {
            //     'uploader':options.uploader, //必须
            //     'picker':options.picker, //必须
            //     'swf':options.swf,//必须
            //     'server':options.server,//必须
            //     'formData':options.formData,
            //     'fileNumLimit':options.fileNumLimit,
            //     'showTooltip':true,
            //     'isAutoInsertInput':options.isAutoInsertInput,#是否自动追加上传成功后的input存储框
            //     'storageInputName':options.storageInputName,#上传成功后的input存储框名称
            //     'uploadSuccess':function
            //     'uploadError':function
            //     'uploadComplete':function
            //     'fileDelete':function
            //     'fileCannel':function
            // }
            options = $.extend(true, {
                'showTooltip':true
            }, options);
            var pickers = options.picker,
                uploaders = options.uploader,
                autoCreateInput = !options.isAutoInsertInput ? options.isAutoInsertInput : true,
                storageInputName = options.storageInputName ? options.storageInputName : 'files',
                limit = options.fileNumLimit ? options.fileNumLimit : 50;
            uploaders = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: options.auto ? options.auto : true,
                // swf文件路径
                swf: options.swf,
                // 文件接收服务端。
                server: options.server,
                formData: options.formData ? options.formData : {},
                fileNumLimit:limit,
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: {
                    id: '#'+pickers+'-picker',
                    multiple:limit > 1 ? true : false
                }
            });

            // 当有文件添加进来的时候
            uploaders.on( 'fileQueued', function( file ) {
                var $li = $('<div id="' + file.id + '" title="'+file.name+'"  data-container="body" data-toggle="m-tooltip" data-placement="top" data-original-title="'+file.name+'"  class="tooltips file-item pull-left">' +
                                '<div class="file-item-bg bg-grey-cararra full-height">' +
                                    '<div class="text-right file-cannel">' +
                                        '<a href="javascript:;" title="删除" data-file-id="' + file.id + '" class="m--font-danger">' +
                                            '<i class="fa fa-ban"></i>' +
                                        '</a>' +
                                    '</div>' +
                                    '<div class="file-progress text-center ">' +
                                        '<i class="fa fa-circle-o-notch fa-2x fa-fw m--font-warning"></i>' +
                                    '</div>' +
                                    '<div class="file-state text-center">' +
                                        '等待中...' +
                                    '</div>' +
                                    '<div class="file-info text-center" title="'+file.name+'">' +
                                        file.name +
                                    '</div>' +
                                '</div>' +
                            '</div> '
                        );
                  if(uploaders.option('fileNumLimit') == 1){
                      $('#'+pickers+' .file-item').remove();
                  }
                  $('#'+pickers+'-picker').before( $li );
            });
            // 文件上传过程中创建进度条实时显示。
            uploaders.on( 'uploadProgress', function( file, percentage ) {
                var $li = $( '#'+file.id ),
                    $percent = $li.find('.file-progress'),
                    $state = $li.find('.file-state');
                if(!$percent.find('i.fa-spinner').length){
                    $percent.html('<i class="fa fa-spinner fa-spin m--font-primary fa-2x fa-fw"></i>');
                }
                $state.text( parseInt(percentage * 100) + '%' );
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploaders.on( 'uploadSuccess', function( file,response ) {
                var $li = $( '#'+file.id ),
                    $percent = $li.find('.file-progress'),
                    $info = $li.find('.file-info'),
                    $state = $li.find('.file-state'),
                    $cannel = $li.find('.file-cannel'),
                    $cannelBtn = $cannel.find('a');
                if(response.status == 1){
                    $percent.html('<i class="fa fa-check-circle-o m--font-primary fa-2x fa-fw"></i>');
                    $state.text(WebUploader.formatSize( file.size ));
                    $cannelBtn.html('<i class="fa fa-trash"></i>');
                    $cannel.removeClass('file-cannel').addClass('file-delete');
                    $cannelBtn.attr('data-response-info',JSON.stringify(response));
                    if(autoCreateInput){
                        if(uploaders.option('fileNumLimit') == 1){
                            $li.append('<input type="hidden" name="'+storageInputName+'" value="'+response.data.id+'">');
                        }else{
                            $li.append('<input type="hidden" name="'+storageInputName+'[]" value="'+response.data.id+'">');
                        }
                    }
                    if (options.uploadSuccess instanceof Function) {
                        options.uploadSuccess(file, response, uploaders);
                    }
                }else{
                    $percent.html('<i class="fa fa-exclamation-circle m--font-danger fa-2x fa-fw"></i>');
                    $state.addClass('m--font-danger').text(response.message);
                    $info.hide();
                }
            });

            // 文件上传失败，显示上传出错。
            uploaders.on( 'uploadError', function( file ) {
                var $li = $( '#'+file.id ),
                    $percent = $li.find('.file-progress'),
                    $state = $li.find('.file-state');
                if(!$percent.find('i.fa-exclamation-circle').length){
                    $percent.html('<i class="fa fa-exclamation-circle m--font-danger fa-2x fa-fw"></i>');
                }
                $state.addClass('m--font-danger').text('上传失败，稍后重试');
                if (options.uploadError instanceof Function) {
                    options.uploadError(file, uploaders);
                }
            });
            uploaders.on( 'uploadComplete', function( file ) {
                if (options.uploadComplete instanceof Function) {
                    options.uploadComplete(file, uploaders);
                }
                uploaders.removeFile( file,true);
                if (options.showTooltip){
                    mAppExtend.handleInitTooltips('#' + pickers + ' .tooltips');
                }
            });
            //未上传取消文件
            $('#'+pickers).on('click', ".file-cannel a", function(){
                var $fileid = $(this).data('file-id');
                if(uploaders.getFile($fileid) != undefined){
                    uploaders.removeFile( $fileid,true);
                }
                $('#'+pickers).find('#'+$fileid).tooltip('hide');
                $('#'+pickers).find('#'+$fileid).remove();
                if (options.fileCannel instanceof Function) {
                    options.fileCannel($fileid, uploaders);
                }
            });
            $('#'+pickers).on('click', ".file-delete a", function(){
                var $fileid = $(this).data('file-id');
                if(uploaders.getFile($fileid) != undefined){
                    uploaders.removeFile( $fileid,true);
                }
                $('#'+pickers).find('#'+$fileid).tooltip('hide');
                $('#'+pickers).find('#'+$fileid).remove();
                if (options.fileDelete instanceof Function) {
                    options.fileDelete($fileid, uploaders);
                }
            });
        },
        imageUpload:function(options){
            // {
            //     'uploader':options.uploader, //必须
            //     'picker':options.picker, //必须
            //     'swf':options.swf,//必须
            //     'server':options.server,//必须
            //     'formData':options.formData,
            //     'fileNumLimit':options.fileNumLimit,
            //     'showTooltip': true
            //     'isAutoInsertInput':options.isAutoInsertInput,#是否自动追加上传成功后的input存储框
            //     'storageInputName':options.storageInputName,#上传成功后的input存储框名称
            //     'uploadSuccess':function
            //     'uploadError':function
            //     'uploadComplete':function
            //     'fileDelete':function
            //     'fileCannel':function
            // }
            options = $.extend(true, {
                'showTooltip': true
            }, options);
            var pickers = options.picker,
                uploaders = options.uploader,
                autoCreateInput = !options.isAutoInsertInput ? options.isAutoInsertInput : true,
                storageInputName = options.storageInputName ? options.storageInputName : 'images',
                limit = options.fileNumLimit ? options.fileNumLimit : 50;
            uploaders = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: options.auto == false ? options.auto : true,
                // swf文件路径
                swf: options.swf,
                // 文件接收服务端。
                server: options.server,
                formData: options.formData ? options.formData : {},
                fileNumLimit:limit,
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: {
                    id: '#'+pickers+'-picker',
                    multiple:limit > 1 ? true : false
                },
                // 只允许选择图片文件。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/jpg,image/jpeg,image/png,image/bmp,image/gif'
                }
            });

            // 当有文件添加进来的时候
            uploaders.on( 'fileQueued', function( file ) {
                var $li = $('<div id="' + file.id + '" title="'+file.name+'"  data-container="body" data-toggle="m-tooltip" data-placement="top" data-original-title="'+file.name+'"  class="file-item tooltips pull-left">' +
                                '<div class="file-preview">'+
                                    '<span class="preview"><img class="hide" src=""></span>'+
                                '</div>'+
                                '<div class="file-item-bg full-height">' +
                                    '<div class="text-right file-cannel">' +
                                        '<a href="javascript:;" title="删除" data-file-id="' + file.id + '" class="m--font-danger">' +
                                            '<i class="fa fa-ban"></i>' +
                                        '</a>' +
                                    '</div>' +
                                    '<div class="file-progress text-center ">' +
                                        '<i class="fa fa-circle-o-notch fa-2x fa-fw m--font-warning"></i>' +
                                    '</div>' +
                                    '<div class="file-state text-center">' +
                                        '等待中...' +
                                    '</div>' +
                                    '<div class="file-info text-center " title="'+file.name+'">' +
                                        file.name +
                                    '</div>' +
                                '</div>' +
                            '</div> '
                        );
                if(uploaders.option('fileNumLimit') == 1){
                    $('#'+pickers+' .file-item').remove();
                }
                $('#'+pickers+'-picker').before( $li );
                // 如果为非图片文件，可以不用调用此方法。
                // thumbnailWidth x thumbnailHeight 为 100 x 100
                uploaders.makeThumb( file, function( error, src ) {
                    var $item = $( '#'+file.id ),
                        $img = $li.find('img');
                    if ( error ) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }
                    $img.attr( 'src', src );
                }, 1, 1 );
            });
            // 文件上传过程中创建进度条实时显示。
            uploaders.on( 'uploadProgress', function( file, percentage ) {
                var $li = $( '#'+file.id ),
                    $percent = $li.find('.file-progress'),
                    $state = $li.find('.file-state');
                if(!$percent.find('i.fa-spinner').length){
                    $percent.html('<i class="fa fa-spinner fa-spin m--font-primary fa-2x fa-fw"></i>');
                }
                $state.text( parseInt(percentage * 100) + '%' );
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploaders.on( 'uploadSuccess', function( file,response ) {
                var $li = $( '#'+file.id ),
                    $percent = $li.find('.file-progress'),
                    $state = $li.find('.file-state'),
                    $info = $li.find('.file-info'),
                    $cannel = $li.find('.file-cannel'),
                    $cannelBtn = $cannel.find('a'),
                    $img = $li.find('img');
                    if(response.status == 1){
                        $info.hide();
                        $percent.html('<i class="fa fa-check-circle-o m--font-primary fa-2x fa-fw"></i>').hide();
                        $state.text(WebUploader.formatSize( file.size )).hide();
                        $cannelBtn.html('<i class="fa fa-trash"></i>');
                        $cannel.removeClass('file-cannel').addClass('file-delete');
                        $cannelBtn.attr('data-response-info',JSON.stringify(response));
                        $img.removeClass('hide');
                        if(autoCreateInput){
                            if(uploaders.option('fileNumLimit') == 1){
                                $li.append('<input type="hidden" name="'+storageInputName+'" value="'+response.data.id+'">');
                            }else{
                                $li.append('<input type="hidden" name="'+storageInputName+'[]" value="'+response.data.id+'">');
                            }
                        }
                        if (options.uploadSuccess instanceof Function) {
                            options.uploadSuccess(file, response, uploaders);
                        }
                    }else{
                        $percent.html('<i class="fa fa-exclamation-circle m--font-danger fa-2x fa-fw"></i>');
                        $state.addClass('m--font-danger').text(response.message);
                    }
            });

            // 文件上传失败，显示上传出错。
            uploaders.on( 'uploadError', function( file, reason ) {
                var $li = $( '#'+file.id ),
                    $percent = $li.find('.file-progress'),
                    $state = $li.find('.file-state');
                if(!$percent.find('i.fa-exclamation-circle').length){
                    $percent.html('<i class="fa fa-exclamation-circle m--font-danger fa-2x fa-fw"></i>');
                }
                $state.addClass('m--font-danger').text('上传失败，稍后重试');
                if (options.uploadError instanceof Function) {
                    options.uploadError(file, uploaders);
                }
            });
            uploaders.on( 'uploadComplete', function( file ) {
                if (options.uploadComplete instanceof Function) {
                    options.uploadComplete(file, uploaders);
                }
                uploaders.removeFile( file,true);
                if (options.showTooltip) {
                    mAppExtend.handleInitTooltips('#' + pickers + ' .tooltips');
                }
            });
            //未上传取消文件
            $('#'+pickers).on('click', ".file-cannel a", function(){
                var $fileid = $(this).data('file-id');
                if(uploaders.getFile($fileid) != undefined){
                    uploaders.removeFile( $fileid,true);
                }
                $('#'+pickers).find('#'+$fileid).tooltip('hide');
                $('#'+pickers).find('#'+$fileid).remove();
                if (options.fileCannel instanceof Function) {
                    options.fileCannel($fileid, uploaders);
                }
            });
            $('#'+pickers).on('click', ".file-delete a", function(){
                var $fileid = $(this).data('file-id');
                if(uploaders.getFile($fileid) != undefined){
                    uploaders.removeFile( $fileid,true);
                }
                $('#'+pickers).find('#'+$fileid).tooltip('hide');
                $('#'+pickers).find('#'+$fileid).remove();
                if (options.fileDelete instanceof Function) {
                    options.fileDelete($fileid, uploaders);
                }
            });
        },
        datePickerInstance:function (el,options) { 
            handleDatePicker(el, options);
        },
        dateTimePickerInstance: function (el, options) {
            handleDateTimePicker(el, options);
        },
        select2Instance: function (el) {
            handleSelect2(el);
        },
        handleInitTooltips:function (el) {
            handleTooltips(el);
        }

    }
}();
$(document).ready(function () {
    mAppExtend.init();
});
