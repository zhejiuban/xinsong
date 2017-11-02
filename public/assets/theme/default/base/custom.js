var mAppExtend = function() {
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
  var handleReloadHtml = function(){
      $('body').on('click', 'a[data-toggle="relaodHtml"],button[data-toggle="relaodHtml"]', function(e) {
          e.preventDefault();
          var el = $(this).data('target');
          var url = $(this).attr('href') ? $(this).attr('href') : $(this).data('href');
          var query = $(this).data('query');
          var loading = $(this).data('loading') ? true : false;
          if(url){
              mAppExtend.ajaxGetHtml(el,url,query,loading);
          }
      });
  };

  var handleSelect2 = function() {
        if ($().select2) {
            // $.fn.select2.defaults.set("theme", "bootstrap");
            $('.select2').select2({
                width: '100%'
            });
        }
    };

  return {
    init: function(){
      handleReloadHtml();
      handleSelect2();
    },
    laravelRoute: function(routeUrl, param) {
      return route(routeUrl, param);
    },
    //返回某个url页面
    backUrl: function (url,time) {
        if(url == 'reload'){
            location.reload();
            return;
        }
        if (time) {
            window.setTimeout(function () {
               window.location.href = url;
            }, time);
        }else{
            window.location.href = url;
        }
    },
    /*ajax加载页面*/
    ajaxGetHtml: function(el, url, query, isLoading, callback, errorCallback){
        var loading = isLoading ? true : false;
        jQuery.ajax({
            url: url,
            type: 'GET',
            dataType: 'HTML',
            cache:false,
            data: query,
            beforeSend: function () {
                if(loading){
                    mApp.block(el?el:'',{
                      overlayColor: '#000000',
                      type: 'loader',
                      state: 'primary',
                      message: 'Processing...'
                    });
                }
            },
            complete: function (xhr, textStatus) {
                //called when complete
                if(loading){ mApp.unblock(el?el:''); }
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
                }else{
                    //toastr.error('请求错误，请重试', '警告');
                    $.notify({'message':'请求错误，请重试'},{
                        type: 'danger',
                        placement: {
                            from: "top",
                            align: "center"
                        },delay:1000
                    });
                }
            }
        });
    },
    ajaxSubmit: function(){

    }
  }
}();
$(document).ready(function() {
    mAppExtend.init();
});
