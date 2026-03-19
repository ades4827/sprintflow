<?php

namespace Ades4827\Sprintflow\Livewire;

use Ades4827\Sprintflow\Traits\LivewireUtilsTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Livewire\Component;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use ReflectionProperty;
use Spatie\LaravelSettings\SettingsRepositories\SettingsRepository;

class Settings extends Component
{
    use LivewireUtilsTrait;

    public $is_modal = false;

    public $settings = [];

    /**
     * @throws \ReflectionException
     * @throws BindingResolutionException
     */
    public function mount()
    {
        $this->checkPermission('settings.settings');

        $settings_repository = app()->make(SettingsRepository::class);
        $settings_groups = config('settings.settings');

        foreach ($settings_groups as $settings_group) {
            $setting = new $settings_group;
            $setting_properties = $settings_repository->getPropertiesInGroup($setting->group());
            if (count($setting_properties) > 0) {

                $this->settings[$setting->group()]['cols'] = 1;
                if(method_exists($settings_group, 'cols')) {
                    $this->settings[$setting->group()]['cols'] = (int) $settings_group::cols();
                }

                // reformat setting for extract type
                foreach ($setting_properties as $property_name => $property_value) {
                    $rp = new ReflectionProperty($settings_group, $property_name);
                    $this->settings[$setting->group()]['properties'][$property_name] = [
                        'name' => $this->getDocsField($rp, 'label'),
                        'description' => $this->getDocsField($rp, 'description'),
                        'type' => $rp->getType()->getName(),
                        'value' => $property_value,
                    ];
                    if ($this->getDocsField($rp, 'formType')) {
                        $this->settings[$setting->group()]['properties'][$property_name]['type'] = $this->getDocsField($rp, 'formType');
                    }
                    if ($this->settings[$setting->group()]['properties'][$property_name]['type'] == 'wireUiSelect') {
                        $this->settings[$setting->group()]['properties'][$property_name]['wireUiSelectRoute'] = $this->getDocsField($rp, 'wireUiSelectRoute');
                    }
                    if ($this->settings[$setting->group()]['properties'][$property_name]['type'] == 'wireUiNativeSelect') {
                        $this->settings[$setting->group()]['properties'][$property_name]['wireUiNativeSelectOptions'] = json_decode($this->getDocsField($rp, 'wireUiNativeSelectOptions'), true);
                    }
                    if ($this->getDocsField($rp, 'visibility')) {
                        $this->settings[$setting->group()]['properties'][$property_name]['visibility'] = json_decode($this->getDocsField($rp, 'visibility'), true);
                    }
                }
            }
        }

        // dd($this->settings);
    }

    public function fieldIsVisible($group, $property_name) {
        if(!isset($this->settings[$group]['properties'][$property_name]['visibility'])) {
            return true;
        }
        foreach ($this->settings[$group]['properties'][$property_name]['visibility'] as $visibility) {
            if($this->settings[$group]['properties'][$visibility['field']]['value'] != $visibility['value']) {
                return false;
            }
        }
        return true;
    }

    private function parseDocs(ReflectionProperty $rp, $parameterName)
    {
        // get setting php doc
        $doc_comment = $rp->getDocComment();

        if ($doc_comment === false) {
            return false;
        }

        // https://github.com/phpstan/phpdoc-parser

        // basic setup
        $parserConfig = new ParserConfig([]);
        $lexer = new Lexer($parserConfig);
        $constExprParser = new ConstExprParser($parserConfig);
        $typeParser = new TypeParser($parserConfig, $constExprParser);
        $phpDocParser = new PhpDocParser($parserConfig, $typeParser, $constExprParser);

        // parsing and reading a PHPDoc string
        $tokens = new TokenIterator($lexer->tokenize($doc_comment));
        $phpDocNode = $phpDocParser->parse($tokens); // PhpDocNode
        $paramTags = $phpDocNode->getParamTagValues(); // ParamTagValueNode[]

        foreach ($paramTags as $paramTag) {
            if ($paramTag->parameterName === '$'.$parameterName) {
                return $paramTag->description;
            }
        }

        return null;
    }

    private function getDocsField(ReflectionProperty $rp, string $parameterName)
    {
        $value = $this->parseDocs($rp, $parameterName);
        if ($value === false) {
            return null;
        }

        return $value;
    }

    public function updated($name, $value)
    {
        $exploded_name = explode('.', $name);

        // Manual cast value
        if ($this->settings[$exploded_name[1]]['properties'][$exploded_name[3]]['type'] === 'int') {
            $value = (int) $value;
        }
        if ($this->settings[$exploded_name[1]]['properties'][$exploded_name[3]]['type'] === 'float') {
            $value = (float) $value;
        }
        if ($this->settings[$exploded_name[1]]['properties'][$exploded_name[3]]['type'] === 'url') {
            $this->validate([$name => 'url'], null, [$name => $this->settings[$exploded_name[1]]['properties'][$exploded_name[3]]['name']]);
        }
        if ($this->settings[$exploded_name[1]]['properties'][$exploded_name[3]]['type'] === 'wireUiSelect') {
            $value = (int) $value;
        }

        $settings_repository = app()->make(SettingsRepository::class);
        $settings_repository->updatePropertiesPayload($exploded_name[1], [$exploded_name[3] => $value]);

        $this->dispatch('livewire-alert', type: 'success', title: '', message: 'Salvato');
    }

    public function render()
    {
        return view('sprintflow::livewire.settings');
    }
}
