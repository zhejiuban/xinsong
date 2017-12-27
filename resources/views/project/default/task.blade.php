<div class="m-actions">
    @if(check_project_leader($project,1))
        <a href="{{ route('tasks.create',['project_id'=>$project->id,'mid'=>request('mid'),'board'=>1]) }}"
           id="task-add" class="m-nav__link btn btn-sm btn-primary  m--margin-bottom-20 m-btn--pill">
            <i class="m-nav__link-icon flaticon-add"></i>
            <span class="m-nav__link-text">
                发布任务
            </span>
        </a>
    @endif
    <a href="{{ route('project.tasks',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
       class="m-nav__link btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-list"></i>
        <span class="m-nav__link-text">
            所有任务
        </span>
    </a>

    <a href="{{ route('project.tasks',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}"
       class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-user"></i>
        <span class="m-nav__link-text">
            只看我的任务
        </span>
    </a>

</div>
<div class="m-widget2">
    @foreach($tasks as $task)
        <div id="task-{{ $task->id }}"
             class="m-widget2__item m-widget2__item--{{tasks_status($task->status,'color')}}">
            <div class="m-widget2__checkbox">
                {{--<label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">--}}
                {{--<input type="checkbox">--}}
                {{--<span></span>--}}
                {{--</label>--}}
            </div>
            <div class="m-widget2__desc">
                <span class="m-widget2__text">
                    @if($task->status)
                        <s><a href="{{ route('tasks.show',['task'=>$task->id,'mid' => request('mid')]) }}"
                              class="task-look">{{$task->content}}</a></s>
                    @else
                        <a href="{{ route('tasks.show',['task'=>$task->id,'mid' => request('mid')]) }}"
                           class="task-look">{{$task->content}}</a>
                    @endif
                    <span class="m--font-size-12">
                        (开始时间：{{$task->start_at}}
                        @if($task->status) 完成时间：{{$task->finished_at}} @endif )
                    </span>
                </span>
                <br>
                <span class="m-widget2__user-name">
                接收人：{{$task->leaderUser?$task->leaderUser->name:null}} 接收时间：{{$task->received_at}}
                </span>
                <span class="pull-right">
                @if(check_project_owner($project,'company'))
                    @if(!$task->status)
                        <a class="fast-finish btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill"
                           href="{{ route('tasks.finish',['task'=>$task->id,'mid'=>request('mid'),'board'=>1]) }}">
                        <i class="la la-power-off"></i>
                        </a>
                        <a href="{{ route('tasks.edit',['task'=>$task->id,'mid'=>request('mid'),'board'=>1])}}"
                           class="fast-edit btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i></a>
                    @endif
                        <a href="{{ route('tasks.destroy',['task'=>$task->id,'mid'=>request('mid')])}}"
                           class="fast-del btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                            <i class="la la-trash"></i></a>
                @elseif(!$task->status && $task->leaderUser->id == get_current_login_user_info())
                    <a class="fast-finish btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill"
                       href="{{ route('tasks.finish',['task'=>$task->id,'mid'=>request('mid'),'board'=>1]) }}">
                        <i class="la la-power-off"></i>
                    </a>
                @endif
                </span>
            </div>
        </div>
    @endforeach
</div>
{{ $tasks->appends([
    'mid' => request('mid'),
    'only'=>request('only')
    ])->links('vendor.pagination.project-board-ajax') }}

<script type="text/javascript">
    var lookTask = function (url, type) {
        $('#_modal').modal(type ? type : 'show');
        mAppExtend.ajaxGetHtml(
            '#_modal .modal-content',
            url,
            {}, true);
    };
    $(document).ready(function () {
        $('.task-look,#task-add,.fast-edit,.fast-finish').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            lookTask(url);
        });
        $('.fast-del').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url': url,
                callback: function (response, status, xhr) {
                    if (response.data.id) {
                        $("#task-" + response.data.id).fadeOut('slow');
                    }
                }
            });
        });
    });
</script>