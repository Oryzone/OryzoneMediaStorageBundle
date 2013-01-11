<?php

namespace Oryzone\Bundle\MediaStorageBundle\Event;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

final class MediaEvents
{
    const BEFORE_PROCESS        = 'oryzone_media_storage.events.before_process';
    const AFTER_PROCESS         = 'oryzone_media_storage.events.after_process';
    const BEFORE_STORE          = 'oryzone_media_storage.events.before_store';
    const AFTER_STORE           = 'oryzone_media_storage.events.after_store';
    const BEFORE_UPDATE         = 'oryzone_media_storage.events.before_update';
    const AFTER_UPDATE          = 'oryzone_media_storage.events.after_update';
    const BEFORE_REMOVE         = 'oryzone_media_storage.events.before_remove';
    const AFTER_REMOVE          = 'oryzone_media_storage.events.after_remove';
    const BEFORE_MODEL_PERSIST  = 'oryzone_media_storage.events.before_model_persist';
    const AFTER_MODEL_PERSIST   = 'oryzone_media_storage.events.after_model_persist';
    const BEFORE_MODEL_REMOVE   = 'oryzone_media_storage.events.before_model_persist';
    const AFTER_MODEL_REMOVE    = 'oryzone_media_storage.events.after_model_persist';
}
