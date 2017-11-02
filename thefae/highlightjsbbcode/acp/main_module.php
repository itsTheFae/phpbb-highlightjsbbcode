<?php

namespace thefae\highlightjsbbcode\acp;

class main_module
{
    public $u_action;
    public $tpl_name;
    public $page_title;

    public function main($id, $mode)
    {
        global $config, $request, $template, $user, $db;
        
		$user->add_lang('acp/common');
        
        $this->tpl_name = 'hljs_bbcodebox_body';
        $this->page_title = $user->lang('ACP_HLJS_BBCODEBOX_TITLE');

        add_form_key('thefae_hljs_bbcodebox_settings');

        if ($request->is_set_post('submit'))
        {
            if (!check_form_key('thefae_hljs_bbcodebox_settings'))
            {
                 trigger_error('FORM_INVALID');
            }

            $config->set('thefae_hljs_bbcodebox_style', $request->variable('thefae_hljs_bbcodebox_style', "default"));
            
            trigger_error($user->lang('ACP_HLJS_BBCODEBOX_SETTINGS_SAVED') . adm_back_link($this->u_action));
        }
        
        $t_utils = new \thefae\highlightjsbbcode\core\utils();
        
        // make sure the styles list is up-to-date.
        $styleHash = $t_utils->getStyleHash();
        if( $config['thefae_hljs_bbcodebox_style_hash'] != $styleHash ) {
            $t_utils->regenerateStylesTemplate();
            $config->set('thefae_hljs_bbcodebox_style_hash', $styleHash);
        }
        
        //$bb_utils = new \thefae\highlightjsbbcode\core\bbcode_util($db);
        
        $options = $t_utils->getStyleNameList();
        foreach( $options as $i => $sname ) {
            $sel = "";
            if( $sname == $config['thefae_hljs_bbcodebox_style'] ) {
                $sel = 'selected="selected"';
            }
            
            $template->assign_block_vars('styleopts', array(
                'VAL' => $sname,
                'SELECTED' => $sel
            ));
        }

        $template->assign_vars(array(
            'THEFAE_HLJS_BBCODEBOX_STYLE_HASH'  => $config['thefae_hljs_bbcodebox_style_hash'],
            'U_ACTION'                          => $this->u_action,
        ));
    }
}

