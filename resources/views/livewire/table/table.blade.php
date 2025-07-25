<div class="form-box !p-0" id="paginated-table">
    <table class="w-full text-left text-gray-500">
        <thead class="text-gray-700 uppercase bg-gray-50">
            <tr>
            @foreach($this->columns() as $column)
                <th @if($column->orderable) wire:click="sort('{{ $column->sortKey() }}')" class="cursor-pointer" @endif>
                    <div class="py-3 px-6 flex items-center">
                        @if($column->livewire_component)
                            @if($column->livewire_component === 'checkbox' && $this->data()->count()>0 )
                                <x-checkbox label="" wire:model.live="checkboxes.all" wire:key="all" />
                            @endif
                        @else
                            {{ $column->label }}
                        @endif
                        @if($column->orderable)
                            @if($sortBy === $column->sortKey())
                                @if ($sortDirection === 'asc')
                                    <i class="fa-solid fa-sort-down ml-2"></i>
                                @else
                                    <i class="fa-solid fa-sort-up ml-2"></i>
                                @endif
                            @else
                                <i class="fa-solid fa-sort ml-2"></i>
                            @endif
                        @endif
                    </div>
                </th>
            @endforeach
            </tr>
        </thead>
        <tbody>
        @forelse($this->data() as $row)
            <tr class="border-b hover:bg-gray-50">
                @foreach($this->columns() as $column)
                    <td wire:key="{{ $row->id }}">
                        <div class="py-3 px-6 flex items-center">
                            @if($column->livewire_component)
                                @if($column->livewire_component === 'checkbox')
                                    <x-checkbox wire:model.live="checkboxes.{{ $row->id }}" />
                                @else
                                    <livewire:is :component="$column->livewire_component" />
                                @endif
                            @else
                                <x-dynamic-component
                                    :component="$column->component"
                                    :value="$column->value($row)"
                                />
                            @endif
                        </div>
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td class="py-3 px-6 flex items-center" colspan="{{ count($this->columns()) }}">{{ __('admin.no_result') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="py-4 px-5">
        {{ $this->data()->links(data: ['scrollTo' => '#paginated-table']) }}
    </div>
</div>
