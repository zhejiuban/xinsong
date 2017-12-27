    <div class="m-actions">
        <a href="{{ route('project.files.create',['project'=>$project->id,'folder_id'=>request('folder_id'),'mid'=>request('mid'),'board'=>1]) }}"
            class="m-nav__link file-add btn btn-sm btn-primary  m--margin-bottom-20 m-btn--pill">
            <i class="m-nav__link-icon flaticon-add"></i>
            <span class="m-nav__link-text">
                上传文档
            </span>
        </a>

        <a href="{{ route('project.files',['project'=>$project->id,'folder_id'=>request('folder_id'),'mid'=>request('mid')]) }}"
           class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
           data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
            <i class="m-nav__link-icon flaticon-list"></i>
            <span class="m-nav__link-text">
                所有文档
            </span>
        </a>
        @if(check_project_leader($project))
        <a href="{{ route('project.folders.create',['project'=>$project->id,'folder_id'=>request('folder_id'),'mid'=>request('mid'),'board'=>1]) }}"
            class="m-nav__link folder-add  btn btn-sm btn-primary  m--margin-bottom-20 m-btn--pill">
            <i class="m-nav__link-icon flaticon-add"></i>
            <span class="m-nav__link-text">
                新建分类
            </span>
        </a>
        @endif
        <a href="{{ route('project.files',['project'=>$project->id,'only'=>1,'folder_id'=>request('folder_id'),'mid'=>request('mid')]) }}"
           class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
           data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
            <i class="m-nav__link-icon flaticon-list"></i>
            <span class="m-nav__link-text">
                只看我的
            </span>
        </a>
        @if(request('folder_id'))
        <a href="{{ route('project.files',['project'=>$project->id,'folder_id'=>project_folders_info(request('folder_id'),'parent_id'),'mid'=>request('mid')]) }}"
           class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
           data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
            <i class="m-nav__link-icon flaticon-up-arrow"></i>
            <span class="m-nav__link-text">
                返回上层目录
            </span>
        </a>
        @endif
    </div>
    <div class="m-widget4">
        @foreach($folders as $folder)
            <div class="m-widget4__item" id="folder-{{$folder->id}}">
                <div class="m-widget4__info m--padding-left-0">
                    <span class="m-widget4__text">
                        <i class="la la-folder-o"></i>
                        <a href="{{route('project.files',['project'=>$project->id,'folder_id'=>$folder->id,'mid'=>request('mid')])}}"
                           title="{{$folder->name}}" data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
                            {{$folder->name}}
                        </a>
                    </span>
                </div>
                @if(check_project_owner($project, 'company'))
                <div class="m-widget4__ext">
                    <a href="{{ route('project.folders.update',['project'=>$project->id,'folder'=>$folder->id,'mid'=>request('mid'),'board'=>1]) }}"
                       title="编辑" class="m-widget4__icon folder-edit">
                        <i class="la la-edit"></i>
                    </a>
                </div>
                <div class="m-widget4__ext">
                    <a href="{{ route('project.folders.destroy',['project'=>$project->id,'folder'=>$folder->id,'mid'=>request('mid')]) }}"
                       title="删除" class="m-widget4__icon folder-del">
                        <i class="la la-trash"></i>
                    </a>
                </div>
                @endif
                <div class="m-widget4__ext">
                    <a href="{{route('project.files',['project'=>$project->id,'folder_id'=>$folder->id,'mid'=>request('mid')])}}"
                       title="查看" class="m-widget4__icon" data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
                        <i class="la la-eye"></i>
                    </a>
                </div>
            </div>
        @endforeach
        @foreach($files as $file)
            <div class="m-widget4__item" id="file-{{$file->id}}">
                <div class="m-widget4__info m--padding-left-0">
                    <span class="m-widget4__text">
                        <i class="la la-file-text"></i>
                        <a href="{{ route('file.download',['file'=>$file->uniqid]) }}" title="下载" target="_blank" >{{$file->old_name}}</a>
                         (上传人：{{$file->user->name}} 时间：{{$file->created_at->diffForHumans()}})
                    </span>
                </div>
                <div class="m-widget4__ext">
                    <a href="{{ route('file.download',['file'=>$file->uniqid]) }}" title="下载" target="_blank" class="m-widget4__icon">
                        <i class="la la-download"></i>
                    </a>
                </div>
                @if(check_project_owner($project, 'company'))
                <div class="m-widget4__ext">
                    <a href="{{ route('project.files.destroy',['project'=>$project->id,'file'=>$file->id,'mid'=>request('mid')]) }}" title="删除" class="m-widget4__icon file-del">
                        <i class="la la-trash"></i>
                    </a>
                </div>
                @endif
                @if(check_project_owner($project, 'company') || $file->user_id == get_current_login_user_info())
                <div class="m-widget4__ext">
                    <a href="{{ route('project.files.move',['project'=>$project->id,'file'=>$file->id,'mid'=>request('mid'),'board'=>1]) }}"
                       title="移动" class="m-widget4__icon file-move">
                        <i class="la la-chevron-circle-right"></i>
                    </a>
                </div>
                @endif
            </div>
        @endforeach
    </div>
    <script type="text/javascript">
        var modalFile = function(url,type){
            $('#_modal').modal(type?type:'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {},true);
        }
        $(document).ready(function(){
            $('.file-add,.folder-add,.folder-edit,.file-move').click(function(event){
                event.preventDefault();
                var url = $(this).attr('href');
                modalFile(url);
            });
            $('.file-del').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url':url,
                    callback:function(response, status, xhr){
                        if(response.data.id){
                            $("#file-"+response.data.id).fadeOut('slow');
                        }
                    }
                });
            });
            $('.folder-del').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url':url,
                    callback:function(response, status, xhr){
                        if(response.data.id){
                            $("#folder-"+response.data.id).fadeOut('slow');
                        }
                    }
                });
            });
        });
    </script>
