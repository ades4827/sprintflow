<div class="input-group flex-1">
    <input type="text" name="filter" class="form-control" placeholder="{{ __('sprintflow::datatable.search') }}" autocomplete="off">
    <button class="btn btn-primary btn-search input-group-text">{{ __('sprintflow::datatable.search') }}</button>
    @can($export_data['permission'])
        <button type="submit" class="btn btn-primary btn-export input-group-text">{{ __('sprintflow::datatable.export') }}</button>
    @endcan
</div>
