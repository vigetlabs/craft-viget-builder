<?php

namespace vigetgeneratortests\fixtures;

use craft\test\fixtures\elements\AssetFixture;
use vigetgeneratortests\fixtures\VolumesFixture;
use vigetgeneratortests\fixtures\VolumesFolderFixture;

class AssetsFixture extends AssetFixture
{
    public $dataFile = __DIR__ . '/data/assets.php';

    public $depends = [VolumesFixture::class, VolumesFolderFixture::class];
}
