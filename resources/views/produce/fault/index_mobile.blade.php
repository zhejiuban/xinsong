@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <form method="get" action="{{route('product_faults.index',['mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed m-form-group-padding-bottom-10">
            <div class="m-portlet__body ">
                <div class=" m-form__group row align-items-center">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input" name="search" placeholder="小车编号..."
                                   id="m_form_search" value="{{request('search')}}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input m-date"
                                   placeholder="发生日期" name="date" id="date"
                                   readonly value="{{request('date')}}"/>
                        </div>
                    </div>
                </div>
                <div class=" m-form__group row align-items-center">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select name="project_id" class="form-control" id="project_id">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select class="form-control" id="status" name="fault_cause_id">
                                {!! fault_cause_select(request('fault_cause_id')) !!}
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="hidden" name="mid" value="{{request('mid')}}">
                            <button type="submit" class="btn btn-brand  m-btn m-btn--pill m-btn--icon">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>搜索</span>
                                </span>
                            </button>
                            <a href="{{ route('product_faults.create',['mid'=>'6c8112484593862335c4644c0c8932df']) }}"
                               class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span>
                                        新增
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @foreach($list as $malfunction)
    <div class="m-portlet m-portlet--mobile " id="malfunction-{{$malfunction->id}}">
        <div class="m-portlet__body">
            <div class="m-widget">
                <div class="m-widget-body">
                    <div class="m-section m-section-none">
                        <h3 class="m-section__heading m-line-height-25">
                            {{$malfunction->faultCause ? $malfunction->faultCause->name : null}}
                        </h3>
                        <span class="m-section__sub m-section__sub-margin-bottom-none">
                            小车编号：{{$malfunction->car_no}} <br>
                            所属项目：{{$malfunction->project ? $malfunction->project->title : null}} <br>
                            发生日期：{{$malfunction->occurrenced_at}} <br>
                            处理人：{{$malfunction->user ? $malfunction->user->name : null }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet-action row">
            <div class="btn-group m-btn-group col" role="group" aria-label="">
                @if(is_administrator()
                || $malfunction->user_id == get_current_login_user_info()
                 || check_project_owner($malfunction->project,'company'))
                    <a href="{{route('product_faults.edit',['malfunction'=>$malfunction->id,'mid'=>request('mid')])}}"
                       class="m-btn-left-bottom-border-none m-btn
                    m-btn--square btn btn-secondary col m-btn--icon
                    m-btn--icon-center">
                        <span>
                            <i class="la la-edit"></i>
                            <span>编辑</span>
                        </span>
                    </a>
                    <a class="m-btn-right-bottom-border-none m-btn
                    m-btn--square m-btn--icon btn btn-secondary
                    col m-btn--icon-center action-delete " data-malfunction-id="{{$malfunction->id}}"
                       href="{{route('product_faults.destroy',['malfunction'=>$malfunction->id,'mid'=>request('mid')])}}">
                        <span>
                            <i class="la la-trash"></i>
                            <span>删除</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    {{ $list->appends([
        'mid' => request('mid'),
        'project_id' => request('project_id'),
        'date' => request('date'),
        'search' => request('search'),
        'fault_cause_id' => request('fault_cause_id'),
    ])->links('vendor.pagination.bootstrap-4') }}
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('js')
    <script type="text/javascript">
        function formatProjectRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.title + "</div>";
            markup += "<div class='select2-result-repository__description'>项目编号：" + repo.no + "</div>";
            markup += "</div></div>";
            return markup;
        }
        function formatProjectRepoSelection(repo) {
            return repo.title || repo.text;
        }
        $(document).ready(function () {
            $('#status').selectpicker();
            var $projectSelector = $("#project_id").select2({
                language:'zh-CN',
                placeholder: "输入项目编号、名称等关键字搜索，选择项目",
                allowClear: true,
                width:'100%',
                ajax: {
                    url: "{{route('projects.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            all:'company',
                            per_page: {{config('common.page.per_page')}}
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * data.per_page) < data.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
                templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
            });
            $('a.action-show').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#_modal').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#_modal .modal-content',
                    url,
                    {}, true);
            });
            $('a.action-delete').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                var id = $(this).data('malfunction-id');
                mAppExtend.deleteData({
                    'url': url,
                    'callback': function () {
                        $("#malfunction-"+id).fadeOut('slow');
                    }
                });
            });
        })
    </script>
@endsection
