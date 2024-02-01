<ul class="datatable_actions">
    @if (!$model->trashed())
        @can($model->getPermissionPrefix().'.update')
            <li>
                <a onclick='Livewire.emit("openModal", "modal-edit", {{ json_encode(["model" => $model->getClassSlug(), "model_id" => $model->id, "modal_title" => (isset($modal_title) ? $modal_title : 'Modifica')]) }})' class="btn btn-primary btn-icon-sm tooltip"
                   data-placement="bottom" title="Modifica">
                    <i class="fa-light fa-pen-to-square"></i></a>
            </li>
        @endcan
        @can($model->getPermissionPrefix().'.delete')
            <li>
                <a href="{{ route('admin.'.$model->getClassSlug(true).'.delete', [$model->getClassSlug() => $model->id]) }}" class="btn btn-danger btn-icon-sm with-confirm tooltip"
                   data-placement="bottom" title="Rimuovi">
                    <i class="fa-light fa-trash"></i></a>
            </li>
        @endcan
    @else
        @can($model->getPermissionPrefix().'.restore')
            <li>
                <a href="{{ route('admin.'.$model->getClassSlug(true).'.restore', [$model->getClassSlug() => $model->id]) }}" class="btn btn-warning btn-icon-sm tooltip"
                   data-placement="bottom" title="Ripristina">
                    <i class="fa-regular fa-trash-undo"></i></a>
            </li>
        @endcan
    @endif
</ul>
