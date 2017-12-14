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
  @if(!$task->status && (check_project_owner($task->project,'edit') || $task->leaderUser->id == get_current_login_user_info()))
  <button type="button" class="btn btn-primary"  href="{{ route('tasks.finish',['task'=>$task->id]) }}" id="finish-task">
    完成此任务
  </button>
  @endif
  @if($task->is_need_plan)
  <button type="button" class="btn btn-primary"  href="{{ route('tasks.edit',['task'=>$task->id]) }}" id="task-plan">
    相关计划
  </button>
  @endif
  @if(check_project_owner($task->project,'edit'))
  @if(!$task->status)
  <button type="button" class="btn btn-primary" href="{{ route('tasks.edit',['task'=>$task->id]) }}" id="edit-task">
    编辑
  </button>
  @endif
  <button type="button" class="btn btn-danger" href="{{ route('tasks.destroy',['task'=>$task->id]) }}" id="del-task" >
    删除
  </button>
  @endif
  <button type="button" class="btn btn-secondary" data-dismiss="modal"  >
    关闭
  </button>
</div>
<script type="text/javascript">
  jQuery(document).ready(function () {
    $('#edit-task,#finish-task').click(function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        $('#_editModal').modal('show');
        mAppExtend.ajaxGetHtml(
            '#_editModal .modal-content',
            url,
            {},true);
    });
    $("#task-plan").click(function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        $('#_planModal').modal('show');
        mAppExtend.ajaxGetHtml(
            '#_planModal .modal-content',
            url,
            {},true);
    });
    $('#del-task').click(function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        mAppExtend.deleteData({
            'url':url
        });
    });
  });
</script>
