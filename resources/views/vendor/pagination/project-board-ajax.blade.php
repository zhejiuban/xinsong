@if ($paginator->hasPages())
    <ul class="pagination pagination-project-board-ajax">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
@endif
<script type="text/javascript">
    $(document).ready(function () {
        $('.pagination-project-board-ajax').on('click', 'a.page-link', function (e) {
            e.preventDefault();
            var el = "#project-body";
            var url = $(this).attr('href') ? $(this).attr('href') : $(this).data('href');
            var query = $(this).data('query');
            var loading = "#project-body";
            if (url) {
                mAppExtend.ajaxGetHtml(el, url, query, loading);
            }
        });
    })
</script>
