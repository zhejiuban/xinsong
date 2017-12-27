<div class="modal-header">
    <h5 class="modal-title" id="_ModalLabel">
        增加参与人
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body">
    <form class="m-form" action="{{ route('project.users.create',['project'=>$project->id]) }}" method="post"
          id="user-form">
        <div class="form-group">
            <label class="">
            </label>
            <select class="form-control m-input" id="user_select" multiple
                    name="project_user[]">
            </select>
            <span class="m-form__help"></span>
        </div>
        {{ csrf_field() }}
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        关闭
    </button>
    <button type="button" class="btn btn-primary" id="submit-button">
        提交
    </button>
</div>
<script type="text/javascript">
    //异步加载用户选择框
    function formatRepo(repo) {
        if (repo.loading) return repo.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.name + "</div>";
        if (repo.department || repo.company) {
            markup += "<div class='select2-result-repository__description'>所在部门：" + (repo.company ? repo.company.name : '') + (repo.department && repo.department.level == 3 ? '/' + repo.department.name : '') + "</div>";
        }
        markup += "</div></div>";
        return markup;
    }

    function formatRepoSelection(repo) {
        return repo.name || repo.text;
    }

    jQuery(document).ready(function () {
        var $userSelector = $("#user_select").select2({
            language: 'zh-CN',
            placeholder: "输入姓名、用户名等关键字搜索",
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{route('users.selector.data')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        per_page: {{config('common.page.per_page')}}
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 0,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
        var form = $("#user-form");
        var submitButton = $("#submit-button");
        form.validate({
            // define validation rules
            rules: {
                'project_user[]': {
                    required: true
                }
            },
            messages: {},
            //display error alert on form submit
            invalidHandler: function (event, validator) {
            },
            submitHandler: function (forms) {
                // alert(1);
                form.ajaxSubmit({
                    beforeSend: function () {
                        submitButton.attr('disabled', 'disabled')
                            .addClass('m-loader m-loader--light m-loader--right');
                    },
                    complete: function () {
                        submitButton.removeAttr('disabled')
                            .removeClass('m-loader m-loader--light m-loader--right');
                    },
                    success: function (response, status, xhr, $form) {
                        if (response.status == 'success') {
                            var $board = "{{request('board')}}";
                            if($board == '1'){
                                $('#_modal,#_editModal').modal('hide');
                                mAppExtend.ajaxGetHtml(
                                    "#project-body","{!! get_redirect_url('board_ajax_url') !!}"
                                    , {}, "#project-body");
                            }else{
                                mAppExtend.notification(response.message,'success','toastr',function() {
                                    mAppExtend.backUrl(response.url);
                                });
                            }
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
                });
            }
        });
        $("#submit-button").click(function (event) {
            form.submit();
        });
    });
</script>
