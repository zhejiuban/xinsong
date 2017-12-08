<div class="modal-header">
  <h5 class="modal-title" id="_ModalLabel">
    上传文档
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form class="m-form" action="{{ route('project.files.create',['project'=>$project->id]) }}" method="post" id="file-form">
      <div class="form-group">
        <div id="file-upload-instance" class="clearfix multi-image-upload">
            <div id="file-upload-instance-picker" class="pull-left m-b-sm p-xxs b-r-sm tooltips uploader-picker" data-container="body" data-html="true" data-trigger="focus" data-toggle="m-tooltip"
                data-placement="top" data-original-title="单个文件大小{{format_bytes(config('filesystems.disks.file.validate.size')*1024)}}以内,允许上传类型：{{arr2str(config('filesystems.disks.file.validate.ext'))}}">
                <p class="m-b-sm"><i class="fa fa-plus-circle m--font-primary fa-2x fa-fw"></i></p>
                选择文件
            </div>
        </div>
      </div>
      {{ csrf_field() }}
    </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal"  >
    关闭
  </button>
  <button type="button" class="btn btn-primary" id="submit-button">
    提交
  </button>
</div>
<script type="text/javascript">
  jQuery(document).ready(function () {
    //多文件上传实例
    mAppExtend.fileUpload({
        uploader:'fileUploadInstance',
        picker:'file-upload-instance',
        swf: '{{ asset("assets/js/plugins/webuploader/Uploader.swf") }}',
        server: '{{ route("file.upload") }}',
        formData: {
            '_token':'{{ csrf_token() }}'
        },
        fileNumLimit:10,
        showTooltip:false,
        isAutoInsertInput:true,//上传成功是否自动创建input存储区域
        storageInputName:'file_project',//上传成功后input存储区域的name
        uploadComplete:function(file, uploader){},
        uploadError:function(file, uploader){},
        uploadSuccess:function(file,response, uploader){
        },
        fileCannel:function(fileId, uploader){},
        fileDelete:function(fileId, uploader){}
    });
    var form = $( "#file-form" );
    var submitButton = $("#submit-button");
    form.validate({
        // define validation rules
        rules: {
        },
        messages:{
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
                        mAppExtend.notification(response.message,'success','toastr',function() {
                            mAppExtend.backUrl(response.url);
                        });
                    }else{
                        mAppExtend.notification(response.message,'error');
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
                  mAppExtend.notification(_err_mes,'error');
              }
          });
        }
    });
    $("#submit-button").click(function(event) {
      form.submit();
    });
  });
</script>
