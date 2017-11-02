<?php

namespace thefae\highlightjsbbcode\acp;

class main_info
{
    public function module()
    {
        return array(
            'filename'  => '\thefae\highlightjsbbcode\acp\main_module',
            'title'     => 'ACP_HLJS_BBCODEBOX_TITLE',
            'modes'    => array(
                'settings'  => array(
                    'title' => 'ACP_HLJS_BBCODEBOX_SETTINGS_TITLE',
                    'auth'  => 'ext_thefae/highlightjsbbcode && acl_a_board',
                    'cat'   => array('ACP_HLJS_BBCODEBOX_TITLE')
                ),
            ),
        );
    }
}
