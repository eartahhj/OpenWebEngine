<?php

// NOTE: In case you need to put custom advertisements in your website

class Ad
{
    public $id = 0;
    public $width = 0;
    public $height = 0;
    public $altText = '';
    public $title = '';
    public $imgSrc = '';
    public $imgExtension = '';
    public $provider = '';
    public $url = '';
    public $campaign = '';
    public $imgFolder = '';
    public $pictures = [];
    public $imgFullPath = '';

    public function __construct(int $id, int $width, int $height, string $altText, string $imgSrc, string $campaign = '', string $imgExtension = 'png', string $referralUrl = '')
    {
        $this->id = $id;
        $this->width = $width;
        $this->height = $height;
        $this->altText = $altText;
        $this->imgSrc = $imgSrc;
        $this->imgExtension = $imgExtension;
        $this->campaign = $campaign;
        $this->imgFullPath = '/' . $this->imgFolder . $this->imgSrc . '.' . $this->imgExtension;

        if (is_file($_SERVER['APP_PUBLIC'] . $this->imgFolder . 'webp/' . $this->imgSrc . '.webp')) {
            $this->pictures['webp'] = '/' . $this->imgFolder . 'webp/' . $this->imgSrc . '.webp';
            $this->pictures[$this->imgExtension] = '/' . $this->imgFolder . $this->imgSrc . '.' . $this->imgExtension;
        }
    }
}

class AdProvider extends Ad
{
    public $provider = 'Provider Name';
    public $url = 'https://www.providerurl.com/';
    public $imgFolder = 'img/ads/providername/';
}

$siteAds = [];
$siteAds['it'][1] = new AdProvider(1, 900, 50, 'Alt text', 'imgsrc', 'campaign');
$siteAds['en'][1] = new AdProvider(1, 900, 50, 'Alt text', 'imgsrc', 'campaign', 'extension');
