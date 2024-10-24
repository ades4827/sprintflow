@if($model->trashed())
    <s>{{$model->number}}</s>
@else
    {{$model->number}}
@endif