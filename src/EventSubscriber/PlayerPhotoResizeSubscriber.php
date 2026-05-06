<?php

namespace App\EventSubscriber;

use App\Entity\Players;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * Réduit les photos joueur après upload (bord maximal 800px, ratio conservé).
 */
final class PlayerPhotoResizeSubscriber implements EventSubscriberInterface
{
    private const MAPPING = 'player_photos';

    private const MAX_EDGE = 800;

    public function __construct(
        private readonly StorageInterface $storage,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::POST_UPLOAD => 'resizePlayerPhoto',
        ];
    }

    public function resizePlayerPhoto(Event $event): void
    {
        if (!$event->getObject() instanceof Players) {
            return;
        }

        if ($event->getMapping()->getMappingName() !== self::MAPPING) {
            return;
        }

        $path = $this->storage->resolvePath($event->getObject(), 'photoFile');

        if ($path === null || !is_file($path) || !is_readable($path)) {
            return;
        }

        $imagine = new Imagine();
        $image = $imagine->open($path);
        $size = $image->getSize();

        if ($size->getWidth() <= self::MAX_EDGE && $size->getHeight() <= self::MAX_EDGE) {
            return;
        }

        $image = $image->thumbnail(new Box(self::MAX_EDGE, self::MAX_EDGE));

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        match ($ext) {
            'jpg', 'jpeg' => $image->save($path, ['jpeg_quality' => 88]),
            'png' => $image->save($path, ['png_compression_level' => 7]),
            'webp' => $image->save($path, ['webp_quality' => 88]),
            default => $image->save($path),
        };
    }
}
