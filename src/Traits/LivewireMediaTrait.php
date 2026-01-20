<?php

namespace Ades4827\Sprintflow\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait LivewireMediaTrait
{
    /**
     * Remove Media or set error
     *
     * @param int $id
     * @param int $model_id
     * @param string|null $collection_name
     * @return int|null
     */
    /*
     * Use Example
     *
    public function removeMedia($id, $collection_name = null)
    {
        $this->checkPermission('MODEL.update');
        $id = $this->removeMediaItem($id, $this->model_id, $collection_name);
        if ($id && $collection_name) {
            unset($this->medias[$collection_name][$id]);
            return;
        }
        if ($id) {
            unset($this->medias[$id]);
        }
    }
    */
    public function removeMediaItem(int $id, int $model_id, string $collection_name = null)
    {
        try {
            Log::info('Remove media id: '. $id.' model id: '.$model_id.' model collection name: '.$collection_name.' from user id:'.auth()->user()->id);

            $media = Media::where('id', $id)->where('model_id', $model_id);
            if($collection_name) {
                $media->where('collection_name', $collection_name);
            }
            $media->delete();

            return $id;
        } catch (Exception $e) {
            report($e);
            $this->addError('media_files', $e->getMessage());
        }

        return null;
    }

    /**
     * Get medias from Entity by collection
     *
     * @param $entity
     * @param $options = [
     *  'collections' => ['header', 'gallery'], // collection to fetch, default from $this->media_collections
     *  'with_no_conversion' => false, // get path and url from original media (no conversion)
     *  'conversion' => ['thumb'], // conversion list for get path and url
     * ]
     * @return void
     */
    public function getMedia($entity, array $options = [])
    {
        // load default collection to get
        if (isset($this->media_collections)) {
            $collections = $this->media_collections;
        }
        if (isset($options['collections'])) {
            $collections = $options['collections'];
        }
        $conversions = ['thumb'];
        if (isset($options['conversion'])) {
            $conversions = $options['conversion'];
        }

        foreach ($collections as $collection) {
            $this->medias['collections'][$entity->id][$collection] = [];
            $medias = $entity->getMedia($collection);
            foreach ($medias as $media) {
                $this->medias['collections'][$entity->id][$collection][$media->id] = [
                    'id' => $media->id,
                    'name' => $media->getCustomProperty('name'),
                    'created_at' => $media->created_at->format('d-m-Y'),
                ];
                /*if (isset($options['with_no_conversion']) && $options['with_no_conversion'] === true)*/
                $this->medias['collections'][$entity->id][$collection][$media->id]['original']['path'] = $media->getPath();
                $this->medias['collections'][$entity->id][$collection][$media->id]['original']['url'] = $media->getUrl();

                foreach ($conversions as $conversion) {
                    if ($media->hasGeneratedConversion($conversion)) {
                        $this->medias['collections'][$entity->id][$collection][$media->id]['conversions'][$conversion]['path'] = $media->getPath($conversion);
                        $this->medias['collections'][$entity->id][$collection][$media->id]['conversions'][$conversion]['url'] = $media->getUrl($conversion);
                    }
                }
            }
        }
    }

    /**
     * DEPRECATED
     * Get only first media from Entity by collection
     *
     * @param $entity
     * @param string $collection
     */
    public function getFirstMediaOfCollection($entity, string $collection)
    {
        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $mediaItem **/
        $mediaItem = $entity->getFirstMedia($collection);
        if ($mediaItem) {
            $this->medias[$collection][$mediaItem->id] = [
                'id' => $mediaItem->id,
                'src' => route('media.serve', ['media' => $mediaItem->id, 'conversion' => 'thumb']),
                'url' => route('file.download', ['media' => $mediaItem->id]),
                'original_name' => $mediaItem->custom_properties["original_name"],
                'mime_type' => $mediaItem->mime_type,
                'type' => $mediaItem->type,
            ];
        }
    }

    /**
     * DEPRECATED
     * Get all media from Entity by collection
     *
     * @param $entity
     * @param string $collection
     */
    public function getAllMediaOfCollection($entity, $collection)
    {
        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $mediaItem **/
        $mediaItems = $entity->getMedia($collection);
        foreach ($mediaItems as $mediaItem) {
            $this->medias[$collection][$mediaItem->id] = [
                'id' => $mediaItem->id,
                'src' => route('media.serve', ['media' => $mediaItem->id, 'conversion' => 'thumb']),
                'url' => route('file.download', ['media' => $mediaItem->id]),
                'original_name' => $mediaItem->custom_properties["original_name"],
                'mime_type' => $mediaItem->mime_type,
                'type' => $mediaItem->type,
            ];
        }
    }

    /**
     * Returns a file size limit in bytes based on the PHP upload_max_filesize and post_max_size
     *
     * @return int
     */
    protected function file_upload_max_size()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = $this->parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        return $max_size;
    }

    /**
     * Parse file size
     *
     * @param $size
     * @return float
     */
    private function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }

        return round($size);
    }
}
