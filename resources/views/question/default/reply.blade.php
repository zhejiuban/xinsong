<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        问题回复
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body padding-top-bottom-none padding-left-right-15">
    <form method="post" action="{{route('questions.reply',['question'=>$question->id])}}" id="reply-form"
          class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--group-padding-left-right-none">
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                标题:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$question->title}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                所属项目:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->project ? $question->project->title : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                所属版块:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->category ? $question->category->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                上报人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $question->user ? $question->user->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                上报时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $question->created_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                问题内容:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->content}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                相关附件:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text clearfix multi-image-upload">
                    @if ($question->file and $question->file->isNotEmpty())
                        @foreach ($question->file as $key => $value)
                            @if(is_image($value->suffix))
                                <div id="FILE_{{$value->id}}" title="{{$value->old_name}}" data-container="body"
                                     data-toggle="m-tooltip" data-placement="top"
                                     data-original-title="FILE_{{$value->old_name}}"
                                     class="file-item tooltips pull-left">
                                    <div class="file-preview">
                                        <span class="preview">
                                          <a href="{{asset($value->path)}}" data-lightbox="roadtrip">
                                            <img class="" src="{{asset($value->path)}}">
                                          </a>
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div id="FILE_{{$value->id}}" title="{{$value->old_name}}" data-container="body"
                                     data-toggle="m-tooltip" data-placement="top"
                                     data-original-title="FILE_{{$value->old_name}}"
                                     class="file-item tooltips pull-left">
                                    <div class="file-item-bg bg-grey-cararra full-height">
                                        <div class="text-right file-delete">
                                            <a href="{{ route('file.download',['file'=>$value->uniqid]) }}"
                                               target="_blank" title="下载"
                                               data-file-id="FILE_{{$value->id}}" class="m--font-primary">
                                                <i class="fa fa-download"></i></a>
                                        </div>
                                        <div class="file-progress text-center "></div>
                                        <div class="file-state text-center">10.04K</div>
                                        <div class="file-info text-center"
                                             title="{{$value->old_name}}">{{$value->old_name}}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                接收人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $question->receiveUser ? $question->receiveUser->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                回复时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $question->replied_at}}
                </div>
            </div>
            <!-- <label class="col-lg-2 col-form-label">
                接收时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $question->received_at}}
                </div>
            </div> -->
            <label class="col-lg-2 col-form-label">
                回复内容:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    <textarea name="reply_content" class="form-control m-input" rows="8"
                              data-error-container="#reply_content-error"
                              placeholder="请输入问题回复内容">{{$question->reply_content}}</textarea>
                    <div id="reply_content-error" class=""></div>
                    <span class="m-form__help"></span>
                </div>
                {{ csrf_field() }}
            </div>
            
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" id="reply-button" class="btn btn-primary">
        提交
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        关闭
    </button>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var replyForm = $('#reply-form');
        var submitButton = $("#reply-button");
        replyForm.validate({
            // define validation rules
            rules: {
                reply_content: {
                    required: true
                }
            },
            //display error alert on form submit
            invalidHandler: function (event, validator) {
            },
            submitHandler: function (forms) {
                // alert(1);
                replyForm.ajaxSubmit({
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
                            if (typeof datatable != 'undefined') {
                                mAppExtend.notification(response.message
                                    , 'success', 'toastr', function () {
                                        $('#question_modal').modal('hide');
                                        datatable.load();
                                    });
                            } else {
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
                                }
                            }
                        } else {
                            mAppExtend.notification(response.message
                                , 'error');
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
                        mAppExtend.notification(_err_mes
                            , 'error');
                    }
                });
            }
        });
        $("#reply-button").click(function (event) {
            replyForm.submit();
        });
    });
</script>
