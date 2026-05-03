{{--
    Component: breadcrumb
    Variables:
      $items – array of arrays with keys: label, url (null for current page)
--}}
@if(isset($items) && count($items) > 0)
<div class="breadcrumb" style="display:flex;align-items:center;gap:8px;font-size:12px;color:#757575;margin-bottom:16px;flex-wrap:wrap;">
    @foreach($items as $item)
        @if($loop->first)
            <a href="{{ $item['url'] }}" style="color:#757575;text-decoration:none;">{{ $item['label'] }}</a>
        @elseif($item['url'])
            <span>›</span>
            <a href="{{ $item['url'] }}" style="color:#757575;text-decoration:none;">{{ $item['label'] }}</a>
        @else
            <span>›</span>
            <span style="color:#1A1A1A;font-weight:600;">{{ $item['label'] }}</span>
        @endif
    @endforeach
</div>
@endif
