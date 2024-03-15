# LivewireFileValidationTrait

Use this trait to simple validation file upload (when Laravel rules is difficult to use)

## Classic usage

Insert "Use" directive in livewire component and use a method like this:

```
use Ades4827\Sprintflow\Traits\LivewireFileValidationTrait;

class Component extends DefaultComponent
{
    use LivewireFileValidationTrait;
    
    public function submit()
    {
        ...
        $this->validate();
        $document = new Document();
    
        foreach ($this->medias['uploads'] as $collection_name => $positions) {
            if ($collection_name === \App\Models\Document::DOCUMENTS) {
                foreach ($positions as $uploads) {
                    foreach ($uploads as $upload) {
                        $this->validateByMimeType($upload, ['mime_groups' => ['pdf', 'doc', 'xls']]);
                        $document->addMedia($upload->path())->toMediaCollection($collection_name, 'documents');
                    }
                }
            }
        }
    
        $document->name = $this->state['name'];
        $document->save();
        ...
    }
}
```

## Simple usage for image

For default configuration and easy to read code you can use this code (ONLY for image)
```
$this->validateImage($upload);
```

## Complex usage

There are different options for use validation in different way.

### Options list

#### Mime Group 
for select mime by preset.
```
mime_groups: ['image', 'image_extended', 'pdf', 'zip', 'xls', 'ppt', 'doc']
```

#### Mime Type
for custom mime not mapped. Ex from: https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
```
mime_types: ['image/png', 'image/jpg', 'image/jpeg']
```

#### Error message type
to choose different error message. Version extension: list accepted file by extension. Version type: list accepted file by type like "File Excel"
```
error_prefered: 'generic' | 'extension' | 'type'
```

#### Valid Extension and Type
used in error message. By default, not necessary when use Mime Group. When you use Mime Type you can set a custom list in error message.
```
valid_extensions: ['.doc', '.pdf', '.xls']
valid_types: ['Imagine', 'PDF', 'File Excel']
```

#### Custom Error message
to override default error message
```
error_message: 'Stai caricando un file di tipo errato'
```
