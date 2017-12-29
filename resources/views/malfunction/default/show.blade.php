<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        故障详情
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
                小车编号:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $malfunction->car_no }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                设备类型:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $malfunction->device ? $malfunction->device->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                故障来自:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $malfunction->phase ? $malfunction->phase->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                故障现象:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $malfunction->content}}
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                故障处理人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $malfunction->user ? $malfunction->user->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                处理时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $malfunction->handled_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                故障原因:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $malfunction->reason}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                故障处理:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $malfunction->result}}
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
