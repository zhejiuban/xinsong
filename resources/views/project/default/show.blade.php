<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        项目基本信息
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
                名称:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$project->title}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                描述:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$project->remark}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                编号:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{$project->no}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                所属办事处:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{$project->deparment ? $project->deparment->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                新松负责人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{$project->leaderUser ? $project->leaderUser->name : null}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                办事处负责人:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{$project->companyUser ? $project->companyUser->name : null}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                客户对接人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{$project->customers}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                客户对接人电话:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{$project->customers_tel}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                客户对接人地址:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$project->customers_address}}
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
