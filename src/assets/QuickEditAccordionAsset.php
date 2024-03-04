<?php
namespace hiddenpathz\QuickEditAccordion\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class QuickEditAccordionAsset extends AssetBundle
{
    public $sourcePath = '@hiddenpathz/QuickEditAccordion/assets';

    public $css = [
        'css/quick-edit-accordion.css',
    ];

    public $js = [
        'js/quick-edit-accordion.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}