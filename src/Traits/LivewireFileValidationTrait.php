<?php

namespace Ades4827\Sprintflow\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait LivewireFileValidationTrait
{
    /**
     * Simple validation for image
     *
     * To use right before ->addMedia
     *
     * @param  TemporaryUploadedFile  $upload
     * @return void
     *
     * @throws Exception
     */
    public function validateImage(TemporaryUploadedFile $upload)
    {
        $this->validateByMimeType($upload, ['mime_groups' => ['image']]);
    }

    /**
     * To use right before ->addMedia
     *
     * valid options example:
     * - valid_extensions: ['.doc', '.pdf', '.xls']
     * - valid_types: ['Imagine', 'PDF', 'File Excel']
     * - mime_types: ['image/png', 'image/jpg', 'image/jpeg']
     * - error_message: 'Stai caricando un file di tipo errato'
     * - error_prefered: 'generic' | 'extension' | 'type'
     * - mime_groups: ['image', 'image_extended', 'pdf', 'zip', 'xls', 'ppt', 'doc']
     *
     * @param  TemporaryUploadedFile  $upload
     * @param  array  $options
     * @return void
     *
     * @throws Exception
     */
    public function validateByMimeType(TemporaryUploadedFile $upload, array $options)
    {
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types

        if (! isset($options['valid_extensions']) || ! is_array($options['valid_extensions'])) {
            $options['valid_extensions'] = [];
        }
        if (! isset($options['valid_types']) || ! is_array($options['valid_types'])) {
            $options['valid_types'] = [];
        }
        if (! isset($options['mime_types']) || ! is_array($options['mime_types'])) {
            $options['mime_types'] = [];
        }
        if (! isset($options['error_prefered']) || ! is_string($options['error_prefered'])) {
            $options['error_prefered'] = 'type';
        }

        if (isset($options['mime_groups']) && is_array($options['mime_groups'])) {
            foreach ($options['mime_groups'] as $mime_group) {
                if ($mime_group === 'image') {
                    $options['mime_types'] = array_merge($options['mime_types'], ['image/png', 'image/jpg', 'image/jpeg']);
                    $options['valid_extensions'] = array_merge($options['valid_extensions'], ['.png', '.jpg', '.jpeg']);
                    $options['valid_types'] = array_merge($options['valid_types'], ['Immagine PNG', 'Immagine JPEG']);
                } elseif ($mime_group === 'image_extended') {
                    $options['mime_types'] = array_merge($options['mime_types'], ['image/png', 'image/jpg', 'image/jpeg', 'image/gif', 'image/webp', 'image/bmp']);
                    $options['valid_extensions'] = array_merge($options['valid_extensions'], ['.png', '.jpg', '.jpeg', '.gif', '.webp', '.bmp']);
                    $options['valid_types'] = array_merge($options['valid_types'], ['Immagine PNG', 'Immagine JPEG', 'Animazione GIF', 'Immagine WebP', 'Immagine BMP']);
                } elseif ($mime_group === 'pdf') {
                    $options['mime_types'] = array_merge($options['mime_types'], ['application/pdf']);
                    $options['valid_extensions'] = array_merge($options['valid_extensions'], ['.pdf']);
                    $options['valid_types'] = array_merge($options['valid_types'], ['Documento PDF']);
                } elseif ($mime_group === 'zip') {
                    $options['mime_types'] = array_merge($options['mime_types'], ['application/zip']);
                    $options['valid_extensions'] = array_merge($options['valid_extensions'], ['.zip']);
                    $options['valid_types'] = array_merge($options['valid_types'], ['Archivio ZIP']);
                } elseif ($mime_group === 'xls') {
                    $options['mime_types'] = array_merge($options['mime_types'],
                        ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.oasis.opendocument.spreadsheet']);
                    $options['valid_extensions'] = array_merge($options['valid_extensions'], ['.xls', '.xlsx']);
                    $options['valid_types'] = array_merge($options['valid_types'], ['File Excel']);
                } elseif ($mime_group === 'ppt') {
                    $options['mime_types'] = array_merge($options['mime_types'],
                        ['application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.presentation']);
                    $options['valid_extensions'] = array_merge($options['valid_extensions'], ['.ppt', '.pptx']);
                    $options['valid_types'] = array_merge($options['valid_types'], ['File PowerPoint']);
                } elseif ($mime_group === 'doc') {
                    $options['mime_types'] = array_merge($options['mime_types'],
                        ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text']);
                    $options['valid_extensions'] = array_merge($options['valid_extensions'], ['.doc', '.docx']);
                    $options['valid_types'] = array_merge($options['valid_types'], ['File Word']);
                } else {
                    throw new Exception('Mime groups: "'.$mime_group.'" not valid');
                }
            }
        }

        if (is_array($options['mime_types'])) {
            foreach ($options['mime_types'] as $mime_type) {
                if (! Str::contains($mime_type, '/')) {
                    throw new Exception('Mime types: "'.$mime_type.'" not valid');
                }
            }
        }

        if (count($options['mime_types']) === 0) {
            throw new Exception('Mime types not defined');
        }

        if (! isset($options['error_message']) || ! is_string($options['error_message'])) {
            $options['error_message'] = 'File di tipo errato';
            if ($options['error_prefered'] === 'extension' && count($options['valid_extensions']) > 0) {
                $options['error_message'] .= '. Usa documenti con uno dei seguenti formati: '.implode(', ', $options['valid_extensions']);
            } elseif ($options['error_prefered'] === 'type' && count($options['valid_types']) > 0) {
                $options['error_message'] .= '. Usa documenti con uno dei seguenti tipi: '.implode(', ', $options['valid_types']);
            }
        }

        if (! in_array($upload->getMimeType(), $options['mime_types'])) {
            throw ValidationException::withMessages(['files' => $options['error_message']]);
        }
    }
}
