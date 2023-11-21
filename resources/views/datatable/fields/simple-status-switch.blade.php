@if (!$model->trashed())
    <div class="form-check form-switch switch-status">
        <input class="form-check-input" type="checkbox" id="switch-s{{$model->id}}" data-route="{{ route('admin.'.$model->getClassSlug(true).'.changeStatus', [$model->getClassSlug() => $model->id]) }}" data-datatable="{{ $model->getClassSlug(true) }}-table" @if($model->$field_name) checked @endif>
        <label class="form-check-label" for="switch-s{{$model->id}}"></label>
    </div>
    {{-- js logic in bootstrap.js --}}
@endif
