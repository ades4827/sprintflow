<?php

namespace Setting;

use Spatie\LaravelSettings\Settings;

class ExampleSettings extends Settings
{
    /**
     * @param  string  $label  Field Title
     * @param  string  $description  Additional long description
     */
    public int $int_type_text;

    /**
     * @param  string  $label  Field Title
     * @param  string  $formType  float
     */
    public int $float_number;

    /**
     * @param  string  $label  Field Title
     * @param  string  $formType  bool
     */
    public bool $checkout_enabled;

    /**
     * @param  string  $label  Field Title
     * @param  string  $formType  string
     */
    public string $string;

    /**
     * @param  string  $label  Field Title
     * @param  string  $formType  textarea
     */
    public string $text;

    /**
     * @param  string  $label  Field Title
     * @param  string  $formType  wireUiNativeSelect
     * @param  string  $wireUiNativeSelectOptions  [{"id":"url", "name": "Url"}, {"id":"storage", "name": "From storage/app/public/privacy.pdf"}]
     */
    public string $selected_value;

    /**
     * @param  string  $label  Field Title
     * @param  string  $formType  wireUiSelect
     * @param  string  $wireUiSelectRoute  api.example.index
     */
    public string $privacy_method;

    /**
     * @param  string  $label  Field Title
     * @param  string  $formType  url
     * @param  string  $visibility  [{"field": "privacy_method", "value": "url"}]
     */
    public string $privacy_url;

    public static function group(): string
    {
        return 'general';
    }
}
