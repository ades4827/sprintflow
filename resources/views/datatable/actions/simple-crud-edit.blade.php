<ul class="datatable_actions">
    @if (!method_exists($model, 'trashed') || !$model->trashed())
        @can($model->getPermissionPrefix().'.update')
            <li>
                <a href="{{ route('admin.'.$model->getClassSlug(true).'.edit', [$model->getClassSlug() => $model->id]) }}" class="btn btn-primary btn-icon-sm tooltip"
                   data-toggle="tooltip" data-placement="bottom" title="{{ __('sprintflow::view.edit') }}">
                    <i class="{{ $fa_solid }} fa-pen-to-square"></i></a>
            </li>
        @endcan
        @can($model->getPermissionPrefix().'.delete')
            <li>
                <a href="{{ route('admin.'.$model->getClassSlug(true).'.delete', [$model->getClassSlug() => $model->id]) }}" class="btn btn-danger btn-icon-sm with-confirm tooltip"
                   data-placement="bottom" title="{{ __('sprintflow::view.delete') }}">
                    <i class="{{ $fa_solid }} fa-trash"></i></a>
            </li>
        @endcan
    @else
        @can($model->getPermissionPrefix().'.restore')
            <li>
                <a href="{{ route('admin.'.$model->getClassSlug(true).'.restore', ['deleted_'.$model->getClassSlug() => $model->id]) }}" class="btn btn-warning btn-icon-sm tooltip"
                   data-placement="bottom" title="{{ __('sprintflow::view.restore') }}">
                    <i class="{{ $fa_solid }} fa-trash-can-arrow-up"></i></a>
            </li>
        @endcan
    @endif
</ul>