<div class="input-group flex-1">
    <input type="text" name="filter" class="form-control" placeholder="{{ __('sprintflow::view.search') }}" autocomplete="off">
    <button class="btn btn-primary btn-search input-group-text">{{ __('sprintflow::view.search') }}</button>
    @can($export_data['permission'])
        <button type="submit" class="btn btn-primary btn-export input-group-text">{{ __('sprintflow::view.export') }}</button>
    @endcan
</div>
