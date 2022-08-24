<?php

namespace vigetbuildertests\fixtures;

use craft\test\fixtures\elements\AssetFixture;
use vigetbuildertests\fixtures\VolumesFixture;
use vigetbuildertests\fixtures\VolumesFolderFixture;

class AssetsFixture extends AssetFixture
{
    public $dataFile = __DIR__ . '/data/assets.php';

    public $depends = [VolumesFixture::class, VolumesFolderFixture::class];
}
