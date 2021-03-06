<div class="modal-header">
  <h5 class="modal-title" id="_ModalLabel">
    任务详情
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
    <form class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--group-padding-left-right-none">
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
            任务描述:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $task->content}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
            所属项目:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $task->project ? $task->project->title : null }}
                </div>
            </div>
            {{--<label class="col-lg-2 col-form-label">--}}
                {{--所属阶段:--}}
            {{--</label>--}}
            {{--<div class="col-lg-9">--}}
                {{--<div class="form-static-text">--}}
                    {{--{{ $task->phase ? $task->phase->name : null }}--}}
                {{--</div>--}}
            {{--</div>--}}
            <label class="col-lg-2 col-form-label">
            创建人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $task->user ? $task->user->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
            创建时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $task->created_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
            开始日期:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $task->start_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
            完成日期:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $task->finished_at}}
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                接收人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $task->leaderUser ? $task->leaderUser->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
            接收时间:
            </label>
            <div class="col-lg-3">
            <div class="form-static-text">
                {{ $task->received_at}}
            </div>
            </div>
            <label class="col-lg-2 col-form-label">
            去现场日期:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $task->builded_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
            离开现场日期:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $task->leaved_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
            任务完成情况:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $task->result}}
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    
    @if($task->is_need_plan)
      <a class="btn btn-primary" href="{{ route('plans.index',['project'=>$task->project_id,'mid'=>'bd128edbfd250c9c5eff5396329011cd']) }}" >
        相关计划
      </a>  
    @endif

  <button type="button" class="btn btn-secondary" data-dismiss="modal"  >
    关闭
  </button>
</div>
