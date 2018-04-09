<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\attention\frontend\assets;

use yii\web\AssetBundle;

/**
 * Class AttentionAsset
 * @package yuncms\attention\frontend\assets
 */
class AttentionAsset extends AssetBundle
{
    public $sourcePath = '@yuncms/attention/frontend/views/assets';

    public $js = [
        'js/attention.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}