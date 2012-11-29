<?php

namespace Oryzone\Bundle\MediaStorageBundle\Event;

final class MediaEvents
{
    const BEFORE_PROCESS    = 'oryzone_media_storage.events.before_process';
    const AFTER_PROCESS     = 'oryzone_media_storage.events.after_process';
    const BEFORE_PREPARE    = 'oryzone_media_storage.events.before_prepare';
    const AFTER_PREPARE     = 'oryzone_media_storage.events.after_prepare';
    const BEFORE_SAVE       = 'oryzone_media_storage.events.before_save';
    const AFTER_SAVE        = 'oryzone_media_storage.events.after_save';
    const BEFORE_UPDATE     = 'oryzone_media_storage.events.before_update';
    const AFTER_UPDATE      = 'oryzone_media_storage.events.after_update';
    const BEFORE_REMOVE     = 'oryzone_media_storage.events.before_remove';
    const AFTER_REMOVE      = 'oryzone_media_storage.events.after_remove';
}
