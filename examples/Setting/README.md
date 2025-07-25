# Settings component

Livewire component to generate configuration form for [Laravel-settings](https://github.com/spatie/laravel-settings)

## Usage

Configure native package and create a Settings class like in this [example](ExampleSettings.php)

Then insert a livewire component in blade code

```
<livewire:sf.settings />
```

You can override or integrate the components locally using the following code in the ServiceProvider

```
\Livewire\Livewire::component('sf.settings', \App\Livewire\Settings::class);
```