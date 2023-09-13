<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 调用Pixabay图片 前端调用  &lt;?php PixabayPlugin_Plugin::header(); ?&gt;
 * 
 * @package PixabayPlugin_Plugin
 * @author 剑二十七
 * @version 1.0.0
 * @link https://www.jian27.com
 */
class PixabayPlugin_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
    }

    public static function deactivate()
    {
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 添加Pixabay API Key设置项
        $apiKey = new Typecho_Widget_Helper_Form_Element_Text('apiKey', NULL, '', _t('Pixabay API Key'), _t('Pixabay API的申请地址为https://pixabay.com/api/docs/。

'));
        $form->addInput($apiKey);

        // 添加图片数量设置项
        $quantity = new Typecho_Widget_Helper_Form_Element_Text('quantity', NULL, '10', _t('调用图片数量'), _t('最大好像是200'));
        $form->addInput($quantity);

        // 添加图片分辨率设置项
        $resolution = new Typecho_Widget_Helper_Form_Element_Text('resolution', NULL, '640x480', _t('图片分辨率'), _t('比如640x480 不要有空格'));
        $form->addInput($resolution);

        // 添加图片关键词设置项
        $keywords = new Typecho_Widget_Helper_Form_Element_Text('keywords', NULL, '', _t('图片关键词'), _t('Example: "yellow+flower"'));
        $form->addInput($keywords);
		
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

public static function header()
{
    $options = Helper::options()->plugin('PixabayPlugin');
    $apiKey = $options->apiKey;
    $quantity = $options->quantity;
    $resolution = $options->resolution;
    $keywords = $options->keywords;

    // 使用以上配置调用Pixabay API来获取图片
    $url = "https://pixabay.com/api/?key=$apiKey&q=$keywords&per_page=$quantity&page=" . rand(1, 100);
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // 渲染输出图片
    if (isset($data['hits'])) {
        foreach ($data['hits'] as $image) {
            echo '<img src="' . $image['webformatURL'] . '" alt="' . $image['tags'] . '">';
        }
    } else {
        echo 'Failed to fetch images from Pixabay.';
    }
}


    public static function render()
    {
        $options = Typecho_Widget::widget('Widget_Options')->plugin('PixabayPlugin');
        $form = new Typecho_Widget_Helper_Form($options->pluginUrl);
        self::config($form);
        echo '<h2>Pixabay Plugin Settings</h2>';
        $form->render();
    }
}
