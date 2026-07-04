<?php
/**
 * BRK Hub - Social Platform Helper
 */

function cleanUrl($url)
{
    $url = trim($url);

    if (empty($url)) {
        return false;
    }

    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    return $url;
}

function detectPlatform($url)
{
    $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
    $host = preg_replace('/^www\./', '', $host);

    $platforms = [

        'youtube.com'     => 'youtube',
        'youtu.be'        => 'youtube',
        'm.youtube.com'   => 'youtube',

        'facebook.com'    => 'facebook',
        'm.facebook.com'  => 'facebook',
        'fb.watch'        => 'facebook',

        'instagram.com'   => 'instagram',

        'tiktok.com'      => 'tiktok',
        'vm.tiktok.com'   => 'tiktok',
        'vt.tiktok.com'   => 'tiktok',

        'twitter.com'     => 'twitter',
        'x.com'           => 'twitter',

        'threads.net'     => 'threads',

        'snapchat.com'    => 'snapchat',

        'pinterest.com'   => 'pinterest',

        'vimeo.com'       => 'vimeo',

        'dailymotion.com' => 'dailymotion'
    ];

    return $platforms[$host] ?? 'unknown';
}

function platformName($platform)
{
    $names = [
        'youtube'     => 'YouTube',
        'facebook'    => 'Facebook',
        'instagram'   => 'Instagram',
        'tiktok'      => 'TikTok',
        'twitter'     => 'X (Twitter)',
        'threads'     => 'Threads',
        'snapchat'    => 'Snapchat',
        'pinterest'   => 'Pinterest',
        'vimeo'       => 'Vimeo',
        'dailymotion' => 'Dailymotion',
        'unknown'     => 'Unknown'
    ];

    return $names[$platform] ?? 'Unknown';
}

function isSupportedPlatform($platform)
{
    return $platform !== 'unknown';
}

function getPlatformIcon($platform)
{
    $icons = [
        'youtube'     => 'fab fa-youtube',
        'facebook'    => 'fab fa-facebook',
        'instagram'   => 'fab fa-instagram',
        'tiktok'      => 'fab fa-tiktok',
        'twitter'     => 'fab fa-x-twitter',
        'threads'     => 'fas fa-at',
        'snapchat'    => 'fab fa-snapchat',
        'pinterest'   => 'fab fa-pinterest',
        'vimeo'       => 'fab fa-vimeo',
        'dailymotion' => 'fas fa-play-circle',
        'unknown'     => 'fas fa-link'
    ];

    return $icons[$platform] ?? 'fas fa-link';
}

function getPlatformColor($platform)
{
    $colors = [
        'youtube'     => '#ff0000',
        'facebook'    => '#1877f2',
        'instagram'   => '#e1306c',
        'tiktok'      => '#000000',
        'twitter'     => '#000000',
        'threads'     => '#000000',
        'snapchat'    => '#fffc00',
        'pinterest'   => '#bd081c',
        'vimeo'       => '#1ab7ea',
        'dailymotion' => '#0066dc',
        'unknown'     => '#6c757d'
    ];

    return $colors[$platform] ?? '#6c757d';
}

function getUrlInfo($url)
{
    $url = cleanUrl($url);

    if (!$url) {
        return false;
    }

    $platform = detectPlatform($url);

    return [
        'url'       => $url,
        'platform'  => $platform,
        'name'      => platformName($platform),
        'icon'      => getPlatformIcon($platform),
        'color'     => getPlatformColor($platform),
        'supported' => isSupportedPlatform($platform)
    ];
}