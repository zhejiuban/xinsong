<div class="modal-header">
    <h5 class="modal-title" id="_ModalLabel">
        编辑问题
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">
			&times;
		</span>
    </button>
</div>
<div class="modal-body">
        <!--begin::Form-->
        <form action="{{route('questions.update',['question'=>$question->id])}}" id="question-form" method="post"
              class="m-form  m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row m-form__group ">
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label>
                                问题名称:
                            </label>
                            <input type="text" name="title" class="form-control m-input" value="{{$question->title}}"
                                   data-error-container="#titles-error" placeholder="请输入问题名称">
                            <div id="titles-error" class=""></div>
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                问题接收人:
                            </label>
                            <div class="">
                                <select class="form-control m-input" id="user_select" name="receive_user_id"
                                        data-error-container="#receive_user_ids-error">
                                    <option value="{{$question->receive_user_id}}"
                                            selected>{{$question->receiveUser->name}}</option>
                                </select>
                            </div>
                            <div id="receive_user_ids-error" class=""></div>
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="">
                                所属分类:
                            </label>
                            <select class="form-control m-input select2" name="question_category_id"
                                    id="question_category_id"
                                    data-error-container="#question_category_ids-error">
                                {!! question_category_select($question->question_category_id) !!}
                            </select>
                            <div id="question_category_ids-error" class=""></div>
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="">
                                所属项目:
                            </label>
                            <div class="">
                                <select class="form-control m-input select2" id="project_select" name="project_id">
                                    @if ($question->project)
                                        <option value="{{$question->project->id}}"
                                                selected>{{$question->project->title}}</option>
                                    @endif
                                </select>
                            </div>
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="">
                                问题描述:
                            </label>
                            <textarea name="content" class="form-control m-input" rows="8"
                                      data-error-container="#contents-error"
                                      placeholder="请输入问题描述">{{$question->content}}</textarea>
                            <div id="contents-error" class=""></div>
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="">
                                相关附件:
                            </label>
                            <div id="file-upload-instance" class="clearfix multi-image-upload">
                                @if ($question->file and $question->file->isNotEmpty())
                                    @foreach ($question->file as $key => $value)
                                        @if(is_image($value->suffix))
                                        <div id="uploaded-file-{{$value->id}}" title="{{$value->old_name}}"
                                             data-container="body" data-toggle="m-tooltip" data-placement="top"
                                             data-original-title="{{$value->old_name}}"
                                             class="file-item tooltips pull-left">
                                            <div class="file-preview">
                                                <span class="preview">
                                                  <a href="{{asset($value->path)}}" data-lightbox="roadtrip">
                                                  <img class="hide" src="{{asset($value->path)}}">
                                                  </a>
                                                </span>
                                            </div>
                                            <div class="file-item-bg ">
                                                <div class="text-right file-delete">
                                                    <a href="javascript:;" title="删除"
                                                       data-file-id="uploaded-file-{{$value->id}}"
                                                       class="m--font-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="hidden" name="files[]" value="{{$value->id}}">
                                        </div>
                                        @else
                                            <div id="uploaded-file-{{$value->id}}" title="{{$value->old_name}}" data-container="body"
                                                 data-toggle="m-tooltip" data-placement="top"
                                                 data-original-title="FILE_{{$value->old_name}}"
                                                 class="file-item tooltips pull-left">
                                                <div class="file-item-bg bg-grey-cararra full-height">
                                                    <div class="text-right file-delete">
                                                        <a href="javascript:;" title="删除"
                                                           data-file-id="uploaded-file-{{$value->id}}"
                                                           class="m--font-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                    <div class="file-progress text-center "></div>
                                                    <div class="file-state text-center">10.04K</div>
                                                    <div class="file-info text-center"
                                                         title="{{$value->old_name}}">{{$value->old_name}}</div>
                                                </div>
                                                <input type="hidden" name="files[]" value="{{$value->id}}">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div id="file-upload-instance-picker"
                                     class="pull-left m-b-sm p-xxs b-r-sm tooltips uploader-picker"
                                     data-container="body" data-html=true data-toggle="m-tooltip"
                                     data-placement="top"
                                     data-original-title="单个文件大小{{format_bytes(config('filesystems.disks.file.validate.size')*1024)}}以内,允许上传类型：{{arr2str(config('filesystems.disks.file.validate.ext'))}}">
                                    <p class="m-b-sm"><i class="fa fa-plus-circle m--font-primary fa-2x fa-fw"></i></p>
                                    选择文件
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ csrf_field() }}
            {{ method_field('PUT') }}
        </form>
        <!--end::Form-->
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        关闭
    </button>
    <button type="button" class="btn btn-primary" id="question-submit-button">
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

        function formatProjectRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.title + "</div>";
            markup += "<div class='select2-result-repository__description'>项目编号：" + repo.no + "</div>";
            markup += "</div></div>";
            return markup;
        }

        function formatProjectRepoSelection(repo) {
            return repo.title || repo.text;
        }

        jQuery(document).ready(function () {
            mAppExtend.select2Instance("#question_category_id");
            //异步加载用户选择框
            var $userSelector = $("#user_select").select2({
                language: 'zh-CN',
                placeholder: "输入姓名、用户名等关键字搜索，选择用户",
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

            var $projectSelector = $("#project_select").select2({
                language: 'zh-CN',
                placeholder: "输入项目编号、名称等关键字搜索，选择项目",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{route('projects.selector.data')}}",
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
                templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
                templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
            });

            //多文件上传实例
            mAppExtend.fileUpload({
                uploader: 'fileUploadInstance',
                picker: 'file-upload-instance',
                swf: '{{ asset("assets/js/plugins/webuploader/Uploader.swf") }}',
                server: '{{ route("file.upload") }}',
                formData: {
                    '_token': '{{ csrf_token() }}'
                },
                fileNumLimit: 10,
                isAutoInsertInput: true,//上传成功是否自动创建input存储区域
                storageInputName: 'files',//上传成功后input存储区域的name
                uploadComplete: function (file, uploader) {
                },
                uploadError: function (file, uploader) {
                },
                uploadSuccess: function (file, response, uploader) {
                },
                fileCannel: function (fileId, uploader) {
                },
                fileDelete: function (fileId, uploader) {
                }
            });

            var form = $('#question-form');
            var submitButton = $("#question-submit-button");
            form.validate({
                // define validation rules
                rules: {
                    title: {
                        required: true
                    }, receive_user_id: {
                        required: true
                    }, question_category_id: {
                        required: true
                    }, content: {
                        required: true
                    }
                    , project_id: {
                        required: true
                    }
                },
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
            submitButton.click(function(event) {
              form.submit();
            });
        });
    </script>
