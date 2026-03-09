<?php

namespace Ades4827\Sprintflow\Livewire\Tables;

use Livewire\Component;
use Livewire\WithPagination;

abstract class Table extends Component
{
    // https://fly.io/laravel-bytes/reusable-dynamic-tables-with-laravel-livewire/

    use WithPagination;

    public int $perPage = 10;

    public int $page = 1;

    public string $sortBy = '';

    public string $sortDirection = 'asc';

    public array $filters = [
        'search' => null,
    ];

    abstract public function query(): \Illuminate\Database\Eloquent\Builder;

    abstract public function columns(): array;

    #[\Livewire\Attributes\Computed]
    public function data()
    {
        return $this
            ->query()
            ->when($this->sortBy !== '', function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->perPage);
    }

    public function sort($key): void
    {
        $this->resetPage();

        if ($this->sortBy === $key) {
            $direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sortDirection = $direction;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

    public function updatingFilters() {
        $this->resetPage();
    }

    public function render()
    {
        return view('sprintflow::livewire.table.table');
    }
}
