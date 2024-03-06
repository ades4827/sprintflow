# API generator for WireUi select

To create [WireUi selects](https://wireui.dev/components/select) it is necessary to format the data correctly,
for this reason the [official example](https://github.com/wireui/docs/blob/main/src/Examples/UserController.php) has been extended to improve its potential.

## Eloquent generic usage

Using 'simple' method you can add eloquent query or filter to optimize your API.
- Add custom route [like this](api.php)
- Extend ApiController [like this](ApiController.php)
- Usage [like this](usage.php)

## Collection version

You can filter Laravel Collection with 'getFromCollection'

## Array version

You can filter custom array with 'getFromArray' method like this:
```
public function example_from_array(Request $request)
{
    $all_entries = [
        0 => 'Version 1',
        1 => 'Version 2',
    ];

    return $this->getFromArray($request, $all_entries);
}
```
