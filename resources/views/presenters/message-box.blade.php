@if($showDate)
<div class="box-date">
    <span title="{{$message->created_at->format('d/m/Y')}}">
        {{$message->created_at->format('d \d\e M')}}
    </span>
</div>
@endif

<div class="box-message {{$class}}">
    <div class="name">{{$name}}</div>
    <pre>{{ ($message->message) }}</pre>  
    <div class="date-info">
        <span title="{{$message->created_at->format('d/M/Y H:i')}}">
            {{$message->created_at->format('h:i A')}}
        </span>
    </div>
</div>