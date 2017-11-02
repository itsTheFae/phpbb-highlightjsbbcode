<?php

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
    'HLJS_BBCODEBOX_HEADING'                        => 'Code: ',
    'HLJS_BBCODEBOX_HEAD_SEPARATOR'                 => ' | ',
    'HLJS_BBCODEBOX_BUTTON_SHOW'                    => '[show]',
    'HLJS_BBCODEBOX_BUTTON_HIDE'                    => '[hide]',
    'HLJS_BBCODEBOX_BUTTON_SELECT'                  => '[select all]',
    
    'ACP_HLJS_BBCODEBOX_TITLE'                      => 'HighlightJS BBCode',
    'ACP_HLJS_BBCODEBOX_SETTINGS_TITLE'             => 'Settings',
    'ACP_HLJS_BBCODEBOX_SETTINGS_SAVED'             => 'Settings have been saved successfully!',
    'ACP_HLJS_BBCODEBOX_SETTINGS_GROUP_GENERAL'     => 'General Settings',
    
    'ACP_HLJS_BBCODEBOX_OPT_STYLE'                  => 'Highlighter Style: ',
    'ACP_HLJS_BBCODEBOX_OPT_STYLE_DESC'             => 'Select a style for highlightjs.',
));
