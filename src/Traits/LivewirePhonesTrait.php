<?php

namespace Ades4827\Sprintflow\Traits;

use Exception;
use Locale;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Livewire\Attributes\Computed;

/*
use LivewirePhonesTrait;

public array $phones = [];
$this->phones['mobile_phone'] = $this->explodePhoneNumber($this->state['mobile_phone']);

protected function rules()
{
    $rules['phones.mobile_phone.number'] = (new Phone)->type('mobile')->country($this->phones['mobile_phone']['country_code']);
    return $rules;
}

protected function messages()
{
    return [
        'phones.mobile_phone.number.phone' => 'Numero di telefono non valido',
    ];
}

$this->state['mobile_phone'] = $this->implodePhoneNumber(phone: $this->phones['mobile_phone']);

<flux:field>
    <flux:label>Cellulare</flux:label>
    <flux:input.group>
        <flux:select wire:model="phones.mobile_phone.country_code" class="!w-30" variant="combobox">
            @foreach($this->phone_prefixes as $prefix)
                <flux:select.option :value="$prefix['region']" selected-label="+{{ $prefix['code'] }}">
                    <div class="flex items-center gap-2">
                        <small class="text-gray-400">{{ $prefix['name'] }}</small> +{{ $prefix['code'] }}
                    </div>
                </flux:select.option>
            @endforeach
        </flux:select>
        <flux:input wire:model="phones.mobile_phone.number" />
    </flux:input.group>
</flux:field>
*/
trait LivewirePhonesTrait
{
    private static function checkDependency() {
        if (!class_exists('Propaganistas\LaravelPhone\PhoneNumber')) {
            throw new \RuntimeException(
                'The package "propaganistas/laravel-phone" is not installed. ' .
                'Install it with: composer require propaganistas/laravel-phone'
            );
        }
    }

    #[Computed]
    public function phone_prefixes(): array
    {
        self::checkDependency();

        $util = PhoneNumberUtil::getInstance();
        return collect($util->getSupportedRegions())
            ->map(function (string $region) use ($util) {
                return [
                    'region' => $region,
                    'name' => Locale::getDisplayRegion('-' . $region, 'it'),
                    'code' => $util->getCountryCodeForRegion($region),
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();
    }

    public function explodePhoneNumber(?string $phone): array
    {
        self::checkDependency();

        if(empty($phone)) {
            return ['country_code' => 'IT', 'number' => null];
        }

        $util = PhoneNumberUtil::getInstance();

        try {
            $parsed = $util->parse($phone);

            //$prefix   = $parsed->getCountryCode();     // int, es. 39
            $country_code   = $util->getRegionCodeForNumber($parsed); // string, es. IT
            $number = $util->getNationalSignificantNumber($parsed);  // string, es. "3331234567"
        } catch (NumberParseException $e) {
            $country_code = 'IT';
            $number = null;
        }

        return ['country_code' => $country_code, 'number' => $number];
    }

    public function implodePhoneNumber(?array $phone = null, ?string $country_code = null, ?string $number = null): ?string
    {
        self::checkDependency();

        if(is_array($phone)) {
            $number = $phone['number'] ?? null;
            $country_code = $phone['country_code'] ?? null;
        }

        if(empty($country_code) || empty($number)) {
            return null;
        }

        try {
            return phone($number, $country_code)->formatE164();
        } catch (NumberParseException $e) {
            return null;
        }
    }
}
