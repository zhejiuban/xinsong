@extends('layouts.app')

@section('content')
  <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-info"></i>
                </span>
                <h3 class="m-portlet__head-text m--font-primary">
                    编辑问题
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{get_redirect_url()}}" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-reply"></i>
                            <span>
                                返回
                            </span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!--begin::Form-->
    <form action="{{route('questions.update',['question'=>$question->id])}}" id="project-form" method="post" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
      <div class="m-portlet__body">
				<div class="row m-form__group ">
  					<div class="col-md-12">
              <div class="form-group ">
                <label>
    							问题名称:
    						</label>
    						<input type="text" name="title" class="form-control m-input" value="{{$question->title}}" data-error-container="#titles-error" placeholder="请输入问题名称">
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
                <select class="form-control m-input" id="user_select" name="receive_user_id" data-error-container="#receive_user_ids-error">
                  <option value="{{$question->receive_user_id}}">{{$question->receiveUser->name}}</option>
                </select>
              </div>
              <div id="receive_user_ids-error" class=""></div>
              <span class="m-form__help"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
  						<label class="">
  							所属版块:
  						</label>
              <select class="form-control m-input select2" name="question_category_id" data-error-container="#question_category_ids-error">
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
                  {{$question->project?'<option value="'.$question->project->id.'">'.$question->project->title.'</option>':''}}
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
              <textarea name="content" class="form-control m-input" rows="8" data-error-container="#contents-error" placeholder="请输入问题描述">{{$question->content}}</textarea>
              <div id="contents-error" class=""></div>
  						<span class="m-form__help"></span>
              </div>
  					</div>
            <div class="col-md-12">
              <div class="form-group">
              <label class="">
                相关图片:
              </label>
              <div id="file-upload-instance" class="clearfix multi-image-upload">
                @if ($question->file and $question->file->isNotEmpty())
                    @foreach ($question->file as $key => $value)
                    <div id="uploaded-file-{{$value->id}}" title="{{$value->old_name}}"  data-container="body" data-toggle="m-tooltip" data-placement="top" data-original-title="{{$value->old_name}}"  class="file-item tooltips pull-left">
                        <div class="file-preview">
                            <span class="preview">
                              <a href="{{asset($value->path)}}" data-lightbox="roadtrip">
                              <img class="hide" src="{{asset($value->path)}}">
                              </a>
                            </span>
                        </div>
                        <div class="file-item-bg ">
                            <div class="text-right file-delete">
                                <a href="javascript:;" title="删除" data-file-id="uploaded-file-{{$value->id}}" class="m--font-danger">
                                    <i class="fa fa-ban"></i>
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="files[]" value="{{$value->id}}">
                    </div>
                  @endforeach
                @endif
                  <div id="file-upload-instance-picker" class="pull-left m-b-sm p-xxs b-r-sm tooltips uploader-picker" data-container="body" data-html=true data-toggle="m-tooltip"
                   data-placement="top" data-original-title="单个文件大小{{format_bytes(config('filesystems.disks.image.validate.size')*1024)}}以内,允许上传类型：{{arr2str(config('filesystems.disks.image.validate.ext'))}}">
                      <p class="m-b-sm"><i class="fa fa-plus-circle m--font-primary fa-2x fa-fw"></i></p>
                      选择图片
                  </div>
              </div>
              </div>
            </div>
  				</div>
      </div>
      <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
        <div class="m-form__actions m-form__actions--solid">
          <div class="row">
            <div class="col-lg-6">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <button type="submit" id="submit-button" class="btn btn-primary">
                提交
              </button>
              <button type="reset" class="btn btn-secondary">
                重置
              </button>
            </div>
            <div class="col-lg-6 m--align-right">
              <a href="{{get_redirect_url()}}"  class="btn btn-secondary "><i class="fa fa-reply"></i> 返回</a>
            </div>
          </div>
        </div>
      </div>
    </form>
    <!--end::Form-->
  </div>
@endsection
@section('js')
  <script type="text/javascript">
  //异步加载用户选择框
  function formatRepo(repo) {
    if (repo.loading) return repo.text;
    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.name + "</div>";
    if (repo.department) {
        markup += "<div class='select2-result-repository__description'>所在部门：" + repo.department.name + "</div>";
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
      //异步加载用户选择框
      var $userSelector = $("#user_select").select2({
          language:'zh-CN',
          placeholder: "输入姓名、用户名等关键字搜索，选择用户",
          allowClear: true,
          width:'100%',
          ajax: {
              url: "{{route('users.selector.data')}}",
              dataType: 'json',
              delay: 250,
              data: function(params) {
                  return {
                      q: params.term, // search term
                      page: params.page,
                      per_page: {{config('common.page.per_page')}}
                  };
              },
              processResults: function(data, params) {
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
          escapeMarkup: function(markup) {
              return markup;
          }, // let our custom formatter work
          minimumInputLength: 0,
          templateResult: formatRepo, // omitted for brevity, see the source of this page
          templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
      });

      var $projectSelector = $("#project_select").select2({
          language:'zh-CN',
          placeholder: "输入项目编号、名称等关键字搜索，选择项目",
          allowClear: true,
          width:'100%',
          ajax: {
              url: "{{route('projects.selector.data')}}",
              dataType: 'json',
              delay: 250,
              data: function(params) {
                  return {
                      q: params.term, // search term
                      page: params.page,
                      per_page: {{config('common.page.per_page')}}
                  };
              },
              processResults: function(data, params) {
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
          escapeMarkup: function(markup) {
              return markup;
          }, // let our custom formatter work
          minimumInputLength: 0,
          templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
          templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
      });

      //多文件上传实例
      mAppExtend.imageUpload({
          uploader:'fileUploadInstance',
          picker:'file-upload-instance',
          swf: '{{ asset("assets/js/plugins/webuploader/Uploader.swf") }}',
          server: '{{ route("image.upload") }}',
          formData: {
              '_token':'{{ csrf_token() }}'
          },
          fileNumLimit:10,
          isAutoInsertInput:true,//上传成功是否自动创建input存储区域
          storageInputName:'files',//上传成功后input存储区域的name
          uploadComplete:function(file, uploader){},
          uploadError:function(file, uploader){},
          uploadSuccess:function(file,response, uploader){},
          fileCannel:function(fileId, uploader){},
          fileDelete:function(fileId, uploader){}
      });

      var form = $('#project-form');
      var submitButton = $("#submit-button");
      form.validate({
          // define validation rules
          rules: {
              title: {
                  required: true
              },receive_user_id:{
                  required: true
              },question_category_id:{
                  required: true
              },content:{
                  required: true
              }
              // ,project_id:{
              //     required: true
              // }
          },
          //display error alert on form submit
          invalidHandler: function(event, validator) {
          },
          submitHandler: function (forms) {
            // alert(1);
            form.ajaxSubmit({
                beforeSend: function(){
                  submitButton.attr('disabled', 'disabled')
                  .addClass('m-loader m-loader--light m-loader--right');
                },
                complete:function(){
                  submitButton.removeAttr('disabled')
                  .removeClass('m-loader m-loader--light m-loader--right');
                },
                success: function(response, status, xhr, $form) {
                  if(response.status == 'success'){
                    mAppExtend.notification(response.message
                      ,'success','toastr',function() {
                            mAppExtend.backUrl(response.url);
                        });
                  }else{
                    mAppExtend.notification(response.message
                      ,'error');
                  }
                },
                error:function (xhr, textStatus, errorThrown) {
                    _$error = xhr.responseJSON.errors;
                    var _err_mes = '未知错误，请重试';
                    if(_$error != undefined){
                        _err_mes = '';
                        $.each(_$error, function (i, v) {
                            _err_mes += v[0] + '<br>';
                        });
                    }
                    mAppExtend.notification(_err_mes
                      ,'error');
                }
            });
          }
      });
      // $("#submit-button").click(function(event) {
      //   form.submit();
      // });
  });
  </script>
@endsection
