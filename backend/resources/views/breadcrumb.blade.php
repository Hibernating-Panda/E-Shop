{{--
    Component: breadcrumb
    $items = array of ['label' => string, 'url' => string|null]
--}}
<nav class="breadcrumb">
    @foreach($items as $i => $item)
        @if($i > 0)<span>›</span>@endif
        @if($item['url'] && $i < count($items) - 1)
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        @else
            <span style="color:#1A1A1A;font-weight:600;">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>