<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        日志详情
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body padding-top-bottom-none padding-left-right-15">
    <form class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--group-padding-left-right-none">
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                所属项目:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$dynamic->project ? $dynamic->project->title : null}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                所属任务:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$dynamic->task ? $dynamic->task->content : null}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                日志内容:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$dynamic->content}} 
                    @if($dynamic->fill)
                    <span class="m-badge  m-badge--warning m-badge--wide">补</span>
                    @endif
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                上报人:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{$dynamic->user?$dynamic->user->name : null}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                上报时间:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{$dynamic->created_at}}
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"  >
        关闭
    </button>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
    });
</script>
