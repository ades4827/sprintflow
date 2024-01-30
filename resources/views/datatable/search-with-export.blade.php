<div class="input-group flex-1">
    <input type="text" name="filter" class="form-control" placeholder="Cerca" autocomplete="off">
    <button class="btn btn-primary btn-search input-group-text">Cerca</button>
    @can($export_data['permission'])
        <button type="submit" class="btn btn-primary btn-export input-group-text">Export</button>
    @endcan
</div>
