<ul class="datatable_actions">
	@if (!method_exists($model, 'trashed') || !$model->trashed())
		@can($model->getPermissionPrefix().'.update')
			<li>
				<a onclick='Livewire.dispatch("openModal", {
                        component: "modal-edit", arguments: {
                            load_entity: true,
                            model_class: {{ json_encode(get_class($model)) }},
                            model: "{{ $model->getClassSlug() }}",
                            model_id: {{ $model->id }},
                            modal_title: "{{ isset($modal_title) ? $modal_title : '' }}",
                            component_folder: "{{ isset($component_folder) ? $component_folder : null }}",
                        }
                    })' class="btn btn-primary btn-icon-sm tooltip"
				   data-placement="bottom" title="{{ __('sprintflow::view.edit') }}">
					<i class="{{ isset($fa_base) ? $fa_base : 'fa-light' }} fa-pen-to-square"></i></a>
			</li>
		@endcan
		@can($model->getPermissionPrefix().'.delete')
			<li>
				<a href="{{ route('admin.'.$model->getClassSlug(true).'.delete', [$model->getClassSlug() => $model->id]) }}" class="btn btn-danger btn-icon-sm remove with-confirm tooltip"
				   data-placement="bottom" title="{{ __('sprintflow::view.delete') }}">
					<i class="{{ isset($fa_base) ? $fa_base : 'fa-light' }} fa-trash"></i></a>
			</li>
		@endcan
	@else
		@can($model->getPermissionPrefix().'.restore')
			<li>
				<a href="{{ route('admin.'.$model->getClassSlug(true).'.restore', ['deleted_'.$model->getClassSlug() => $model->id]) }}" class="btn btn-warning btn-icon-sm tooltip"
				   data-placement="bottom" title="{{ __('sprintflow::view.restore') }}">
					<i class="{{ isset($fa_base) ? $fa_base : 'fa-light' }} fa-trash-can-arrow-up"></i></a>
			</li>
		@endcan
	@endif
</ul>