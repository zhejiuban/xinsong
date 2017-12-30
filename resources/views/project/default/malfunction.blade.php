<div class="m-actions">
    <a href="{{ route('malfunctions.create',['project_id'=>$project->id,'mid'=>request('mid'),'board'=>1]) }}"
       id="malfunction-add"
       class="m-nav__link  btn btn-sm btn-primary  m--margin-bottom-20 m-btn--pill">
        <i class="m-nav__link-icon flaticon-add"></i>
        <span class="m-nav__link-text">
            故障录入
        </span>
    </a>

    <a href="{{ route('project.malfunctions',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
       class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-list"></i>
        <span class="m-nav__link-text">
            所有的故障
        </span>
    </a>

    <a href="{{ route('project.malfunctions',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}"
       class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-user"></i>
        <span class="m-nav__link-text">
            只看我的记录
        </span>
    </a>
</div>

<div class="m-widget3 m--margin-top-15">
    @foreach($malfunctions as $malfunction)
        <div class="m-widget3__item" id="malfunction-{{$malfunction->id}}">
            <div class="m-widget3__header">
                <div class="m-widget3__user-img">
                    <img class="m-widget3__img" src="{{avatar($malfunction->user?$malfunction->user->avatar:null)}}"
                         alt="">
                </div>
                <div class="m-widget3__info">
                <span class="m-widget3__username">
                    {{$malfunction->user?$malfunction->user->name:null}}
                </span>
                    <br>
                    <span class="m-widget3__time">
                    {{$malfunction->created_at->diffForHumans()}}
                </span>
                </div>
                @php
                    $is_admin = 0;
                    if(is_administrator()
                    || check_project_owner($malfunction->project,'company')
                    || $malfunction->user_id == get_current_login_user_info()){
                        $is_admin=1;
                    }
                @endphp
                <span class="m-widget3__status" style="float: none;">
                    @if($is_admin)
                        <a href="{{ route('malfunctions.edit',['malfunction'=>$malfunction->id,'mid'=>request('mid'),'board'=>1]) }}"
                           class="malfunction-edit btn btn-outline-brand m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill"><i
                                    class="la la-edit"></i></a>
                    @endif
                </span>
                <span class="m-widget3__status" style="float: none;padding-left:10px;">
                    @if($is_admin)
                        <a href="{{ route('malfunctions.destroy',['malfunction'=>$malfunction->id,'mid'=>request('mid')]) }}"
                           class="malfunction-del btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                        <i class="la la-trash"></i></a>
                    @endif
                </span>
            </div>
            <div class="m-widget3__body">
                <p class="m-widget3__text">
                    <a href="{{ route('malfunctions.show',['malfunction'=>$malfunction->id,'mid'=>request('mid')]) }}"
                       class="malfunction-look m--font-default">{{str_limit($malfunction->content,300)}}</a>
                </p>
            </div>
        </div>
    @endforeach
</div>
{{ $malfunctions->appends([
    'mid' => request('mid'),
    'only'=>request('only'),
    'date'=>request('date'),
    'all'=>request('all'),
    ])->links('vendor.pagination.project-board-ajax') }}

<script type="text/javascript">
    var ActionModal = function (url, type) {
        $('#_modal').modal(type ? type : 'show');
        mAppExtend.ajaxGetHtml(
            '#_modal .modal-content',
            url,
            {}, true);
    }
    $(document).ready(function () {
        $('#malfunction-add,.malfunction-edit,.malfunction-look').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            ActionModal(url);
        });
        $('.malfunction-del').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url': url,
                callback: function (response, status, xhr) {
                    if (response.data.id) {
                        $("#malfunction-" + response.data.id).fadeOut('slow');
                    }
                }
            });
        });
    });
</script>
