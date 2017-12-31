@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible fade show alert-success" role="alert">
        <div class="m-alert__icon">
            <i class="la la-check"></i>
        </div>
        <div class="m-alert__text">
            <strong>
                温馨提示：
            </strong>
            {{session('success')}}
        </div>
        <div class="m-alert__close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
<div class="m-portlet m-portlet--full-height">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-alert-2"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    消息通知
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{route('notifications.mark_read')}}" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-check"></i>
                            <span>
                                全部标记为已读
                            </span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="m-widget3">
            @foreach($list as $notification)
            <div class="m-widget3__item">
                <div class="m-widget3__header">
                    <div class="m-widget3__user-img">
                        <img class="m-widget3__img" src="{{avatar(get_user_info($notification->data['from_user_id'],'avatar'))}}" alt="">
                    </div>
                    <div class="m-widget3__info">
                        <span class="m-widget3__username">
                            {{$notification->data['from_user_name']}}
                        </span>
                        <br>
                        <span class="m-widget3__time">
                            {{$notification->created_at->diffForHumans()}}
                        </span>
                    </div>
                    @if($notification->read_at)
                        <span class="m-widget3__status m--font-info">
                            <i class="la la-check"></i>
                        </span>
                    @else
                        <span class="m-widget3__status m--font-danger">
                            <i class="la la-eye-slash"></i>
                        </span>
                    @endif
                </div>
                <div class="m-widget3__body">
                    <p class="m-widget3__text">
                        <a href="{{route('notifications.read',['id'=>$notification->id])}}" class="m--font-default">{{$notification->data['content']}}</a>
                    </p>
                </div>
            </div>
           @endforeach
        </div>

        {{ $list->links('vendor.pagination.bootstrap-4') }}

    </div>
</div>
@endsection