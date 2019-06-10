<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        陪产记录详情
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
                    {{ $malfunction->project ? $malfunction->project->title : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                AGV编号或其他:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $malfunction->car_no }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
               是否解决 :
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $malfunction->is_handle ? '是' : '否' }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                现场故障或问题描述:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $malfunction->question }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                解决过程或方法:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $malfunction->solution }}
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                记录人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $malfunction->user ? $malfunction->user->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                关闭时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $malfunction->closed_at }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                备注:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $malfunction->remark }}
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        关闭
    </button>
</div>
