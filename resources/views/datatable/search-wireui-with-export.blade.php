<div class="input-group flex-1">
    <x-input name="filter" placeholder="{{ __('sprintflow::view.search') }}" autocomplete="off" />
    <button class="btn btn-primary btn-search input-group-text">{{ __('sprintflow::view.search') }}</button>
    @if(isset($export_data) && $export_data['permission'])
        @can($export_data['permission'])
            <button type="submit" class="btn btn-primary btn-export input-group-text">{{ __('sprintflow::view.export') }}</button>
        @endcan
    @else
        <button type="submit" class="btn btn-primary btn-export input-group-text">{{ __('sprintflow::view.export') }}</button>
    @endif
</div>
