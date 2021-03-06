<div class="modal-header">
    <h5 class="modal-title" id="_ModalLabel">
        编辑文档分类
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body">
    <form class="m-form" action="{{ route('project.folders.update',['project'=>$project->id,'folder'=>$folder->id]) }}" method="post"
          id="folder-form">
        <div class="form-group">
            <label for="parent_id">
                上级目录：<span class="required">*</span>
            </label>
            <select name="parent_id" id="parent_id" class="form-control select2">
                {!! project_folder_select($project,$folder->parent_id,1) !!}
            </select>
        </div>
        <div class="form-group">
            <label for="">
                分类名称：<span class="required">*</span>
            </label>
            <input type="text" name="name" value="{{$folder->name}}" class="form-control">
        </div>
        {{ csrf_field() }}
        {{method_field('put')}}
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
    jQuery(document).ready(function () {
        mAppExtend.select2Instance('#parent_id');
        var form = $("#folder-form");
        var submitButton = $("#submit-button");
        form.validate({
            // define validation rules
            rules: {
                parent_id:{
                    required:true
                },name:{
                    required:true
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
                                $("#_modal").modal('hide');$('#_modal,#_editModal').modal('hide');
                                mAppExtend.ajaxGetHtml(
                                    "#project-body","{!! get_redirect_url('board_ajax_url') !!}"
                                    , {}, "#project-body");
                            }else{
                                mAppExtend.notification(response.message,'success','toastr',function() {
                                    mAppExtend.backUrl(response.url);
                                });
                            }s
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
