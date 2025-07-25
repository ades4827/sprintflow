# Livewire components

Livewire generic components

## Usage

Then insert a livewire component in blade code

```
<livewire:sf.admin-form />
<livewire:sf.user-edit-password />
<livewire:sf.user-form />
```

You can override or integrate the components locally using the following code in the ServiceProvider

```
\Livewire\Livewire::component('sf.admin-form', \App\Livewire\AdminForm::class);
```