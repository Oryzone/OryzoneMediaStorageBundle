<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\Form\FormBuilderInterface;
use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

use Oryzone\Bundle\MediaStorageBundle\Provider\Provider,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

class YoutubeProvider extends VideoServiceProvider
{
    protected $name = 'youtube';

    const CANONICAL_URL = 'http://www.youtube.com/watch?v=%s';

    /**
     * {@inheritDoc}
     */
    const VALIDATION_REGEX_URL = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

    /**
     * {@inheritDoc}
     */
    const VALIDATION_REGEX_ID = '%^[^"&?/ ]{11}$%i';

    /**
     * {@inheritDoc}
     */
    protected function getDefaultOptions()
    {
        return array(
            'metadata'  => array(
                'title' => 'title',
                'content' => 'description',
                'tags' => 'tags'
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(Media $media, Context $context)
    {
        $id = $this->getIdFromContent($media->getContent());

        if($id !== NULL)
        {
            $this->service->load($id);

            $previewImageUrl = $this->service->getMetaValue('thumbnail');
            $previewImageFile = sprintf('%syoutube_preview_%s.jpg', $this->tempDir, $id);
            $this->addTempFile($previewImageFile);
            if(!file_exists($previewImageFile))
                $this->downloadFile($previewImageUrl, $previewImageFile, $media);
            $media->setContent($previewImageFile);

            $media->setMetaValue('id', $id);

            foreach($this->options['metadata'] as $metaName => $mediaMetaName)
            {
                $value = $this->service->getMetaValue($metaName);
                if($value !== NULL)
                    $media->setMetaValue($mediaMetaName, $value);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, VariantInterface $variant, $url = NULL, $options = array())
    {
        $defaultOptions = array(
            'mode' => 'video',
            'attributes' => array()
        );

        $options = array_merge($defaultOptions, $options);

        if($options['mode'] != 'video' && $options['mode'] != 'image')
            throw new InvalidArgumentException(sprintf('Invalid mode "%s" to render a Youtube Video. Allowed values: "image", "video"', $options['mode']) );

        switch($options['mode'])
        {
            case 'video':
                $options['attributes'] = array_merge(
                    array(
                        'width' => $variant->getMetaValue('width', 420),
                        'height'=> $variant->getMetaValue('height', 315),
                        'frameborder' => 0,
                        'allowfullscreen' => ''
                    ), $options['attributes']);
                break;

            case 'image':
                $options['attributes'] = array_merge(
                    array(
                        'title' => $media->getName(),
                        'width' => $variant->getMetaValue('width', 420),
                        'height'=> $variant->getMetaValue('height', 315),
                    ), $options['attributes']
                );
                break;
        }

        $htmlAttributes = '';
        if(isset($options['attributes']))
            foreach($options['attributes'] as $key => $value)
                if($value !== NULL)
                    $htmlAttributes .= $key . ($value !== '' ?('="' . $value. '"'):'') . ' ';

        if($options['mode'] == 'video')
            $code = sprintf('<iframe src="http://www.youtube.com/embed/%s" %s></iframe>', $media->getMetaValue('id'), $htmlAttributes);
        else
            $code = sprintf('<img src="%s" %s/>', $url, $htmlAttributes);

        return $code;
    }
}
