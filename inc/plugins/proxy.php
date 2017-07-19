<?php
defined('IN_MYBB') or die('私はちょうど何が重要か見つけようとしている。');

function id_info()
{
    return array(
        'name' => 'Camo Proxy',
        'description' => 'Small plugin to proxy insecure page assets through a Camo server using Phpamo.',
        'author' => 'Leiko',
        'authorsite' => 'https://keybase.io/kawaii',
        'website' => 'https://cute.im',
        'version' => '2.0',
        'compatibility' => '18*'
    );
}

$plugins->add_hook("pre_output_page", "kawaiibb_proxy_parse");

function proxy_parse(&$page)
{
    global $mybb;

    if($mybb->input['previewpost'])
        return;

    preg_match_all('/(src)="([^"]*)"/i', $page, $matches);

    if(isset($matches[2]) && !empty($matches[2]))
    {
        $phpamo = new \WillWashburn\Phpamo\Phpamo(
            'secretcode',
            'proxy.example.com'
        );

        foreach($matches[2] as $match)
        {
            if(stripos($match, 'http://') !== false)
            {
                $page = str_replace($match, $phpamo->camo($match), $page);
            }
        }
    }
}
