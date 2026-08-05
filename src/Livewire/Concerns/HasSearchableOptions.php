<?php

namespace Ades4827\Sprintflow\Livewire\Concerns;

use Closure;

/*

#[Computed]
public function cities()
{
    return $this->searchableOptions(
        modelClass: City::class,
        searchFields: ['name'],
        idColumn: 'id',
        wireModel: 'state.city',
        searchKey: 'search.city',
        orderBy: 'name',
        filters: fn (Builder $query) => $query
            ->when($this->state['country'] ?? null, fn ($q, $country) =>
                $q->where('country_iso2', $country)
            ),
    );
}

<flux:select label="Città" wire:model.live="state.city" variant="combobox" :filter="false">
    <x-slot name="input">
        <flux:select.input wire:model.live="search.city" clearable placeholder="Cerca..." :invalid="$errors->has('state.city')" />
    </x-slot>
    @foreach ($this->cities() as $city)
        <flux:select.option value="{{ $city->id }}" wire:key="city_{{ $city->id }}">
            {{ $city->name }}
        </flux:select.option>
    @endforeach
</flux:select>

*/

trait HasSearchableOptions
{
    protected function searchableOptions(
        string $modelClass, // es: Country::class
        array $searchFields, // es: ['name', 'description']
        string $idColumn, // es: id
        string $wireModel, // es: 'state.country'
        string $searchKey, // es: 'search.country'
        ?string $orderBy = null, // es: name
        int $limit = 100,
        ?Closure $filters = null
    ) {
        $search = trim(data_get($this, $searchKey));

        $query = $modelClass::query();

        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        if (filled($search)) {
            $query->where(function ($q) use ($searchFields, $search) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        if ($filters) {
            $filters($query);
        }

        $results = $query->limit($limit)->get();

        // data_get supporta dot notation su proprietà pubbliche dell'oggetto Livewire
        $selected = data_get($this, $wireModel);

        if (blank($search) && filled($selected)) {
            $selectedQuery = $modelClass::query()->where($idColumn, $selected);

            if ($filters) {
                $filters($selectedQuery);
            }

            $results = $selectedQuery
                ->whereNotIn($idColumn, $results->pluck($idColumn))
                ->get()
                ->merge($results);
        }

        return $results;
    }
}
