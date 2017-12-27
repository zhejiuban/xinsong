<div class="m-actions">
    @if(check_project_owner($project,'company'))
        <a href="{{ route('project.users.create',['project_id'=>$project->id,'board'=>1,'mid'=>request('mid')]) }}"
            class="m-nav__link btn btn-sm btn-primary  user-add m-btn--pill">
            <span>
                <i class="m-nav__link-icon flaticon-add"></i>
                <span class="m-nav__link-text">
                    添加参与人
                </span>
            </span>
        </a>
    @endif
</div>
<!--begin::Widget 14-->
<div class="m-widget4 row m--margin-bottom-20 m--padding-15">
@foreach($users as $user)
    <!--begin::Widget 14 Item-->
    <div class="m-widget4__item col-md-4 m-widget4__item-border-bottom" id="user-{{$user->id}}">
        <div class="m-widget4__img m-widget4__img--pic">
            <img src="{{ avatar($user->avatar) }}" alt="">
        </div>
        <div class="m-widget4__info">
            <span class="m-widget4__title">
                {{$user->name}}
            </span>
            <br>
            <span class="m-widget4__sub">
                {{$user->department ? $user->department->name : null}}
            </span>
        </div>
        @if(check_project_owner($project, 'company'))
        <div class="m-widget4__ext">
            <a href="{{route('project.users.destroy',['project'=>$project->id,'user'=>$user->id,'mid'=>request('mid')])}}"
               class="user-del m-btn m-btn--pill  btn btn-sm btn-danger">
                移除
            </a>
        </div>
        @endif
    </div>
    <!--end::Widget 14 Item-->
@endforeach
</div>
<!--end::Widget 14-->
{{ $users->appends([
    'mid' => request('mid'),
])->links('vendor.pagination.project-board-ajax') }}
<script type="text/javascript">
    var modal = function (url, type) {
        $('#_modal').modal(type ? type : 'show');
        mAppExtend.ajaxGetHtml(
            '#_modal .modal-content',
            url,
            {}, true);
    };
    $(document).ready(function () {
        $(".user-add").click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            modal(url);
        });
        $('.user-del').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url': url,
                callback: function (response, status, xhr) {
                    if (response.data.id) {
                        $("#user-" + response.data.id).fadeOut('slow');
                    }
                }
            });
        });
    });
</script>
