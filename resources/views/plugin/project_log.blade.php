
    <div class="m-list-timeline__items">
        @foreach($list as $log)
        <div class="m-list-timeline__item">
            <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
            <span class="m-list-timeline__text">
                {{$log->description}}
                <span class="m-badge m-badge--info m-badge--wide">
                    {{$log->causer->name}}
                </span>
            </span>
            <span class="m-list-timeline__time">
                {{$log->created_at->diffForHumans()}}
            </span>
        </div>
        @endforeach
    </div>