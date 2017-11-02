<?php

namespace thefae\highlightjsbbcode\migrations;

use \phpbb\db\migration\container_aware_migration;

class v020_install extends container_aware_migration
{
    /**
     * If our config variable already exists in the db
     * skip this migration.
     */
    public function effectively_installed()
    {
        return isset($this->config['thefae_hljs_bbcodebox_style']) && isset($this->config['thefae_hljs_bbcodebox_style_hash']);
    }

    /**
     * This migration depends on phpBB's v314 migration
     * already being installed.
     */
    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v31x\v314');
    }

    public function update_data()
    {
        return array(

            // Add the config variable we want to be able to set
            array('config.add', array('thefae_hljs_bbcodebox_style', "default")),
            array('config.add', array('thefae_hljs_bbcodebox_style_hash', "")),

            // Add a parent module (ACP_HLJS_BBCODEBOX_TITLE) to the Extensions tab (ACP_CAT_DOT_MODS)
            array('module.add', array(
                'acp',
                'ACP_CAT_DOT_MODS',
                'ACP_HLJS_BBCODEBOX_TITLE'
            )),

            // Add our main_module to the parent module (ACP_HLJS_BBCODEBOX_TITLE)
            array('module.add', array(
                'acp',
                'ACP_HLJS_BBCODEBOX_TITLE',
                array(
                    'module_basename'       => '\thefae\highlightjsbbcode\acp\main_module',
                    'modes'                 => array('settings'),
                ),
            )),
            
            array('custom', array(array($this, 'c_install_bbcodes'))),
        );
    }
    
    public function revert_data()
    {
        return array(
            array('config.remove', array('thefae_hljs_bbcodebox_style')),
            array('config.remove', array('thefae_hljs_bbcodebox_style_hash')),
            
            array('module.remove', array(
                'acp',
                'ACP_CAT_DOT_MODS',
                'ACP_HLJS_BBCODEBOX_TITLE'
            )),

            array('module.remove', array(
                'acp',
                'ACP_HLJS_BBCODEBOX_TITLE',
                array(
                    'module_basename'       => '\thefae\highlightjsbbcode\acp\main_module',
                    'modes'                 => array('settings'),
                ),
            )),
        );
    }
    
    public function c_install_bbcodes() 
    {
        $bb_util = new \thefae\highlightjsbbcode\core\bbcode_util($this->db);
		$bb_util->install_bbcodes(array(
            'codebox' => array(
                'bbcode_helpline'	=> 'Codebox with language auto-detection.',
                'bbcode_match'		=> '[codebox]{TEXT}[/codebox]',
                'bbcode_tpl'		=> '<div class="codehlb">'."\n".
                                       '  <div class="heading">{L_HLJS_BBCODEBOX_HEADING}<a class="codehlb_toggle">{L_HLJS_BBCODEBOX_BUTTON_SHOW}</a>'.
                                       '{L_HLJS_BBCODEBOX_HEAD_SEPARATOR}<a class="codehlb_select" href="#" onclick="selectCode(this); return false;">{L_HLJS_BBCODEBOX_BUTTON_SELECT}</a></div>'."\n". 
                                       '  <pre style="display:none;"><code>{TEXT}</code></pre>'."\n".
                                       '</div>',
            ),
            'codebox=' => array(
                'bbcode_helpline'	=> 'Codebox with specific lanaguage.',
                'bbcode_match'		=> '[codebox={SIMPLETEXT1}]{TEXT}[/codebox]',
                'bbcode_tpl'		=> '<div class="codehlb">'."\n".
                                       '  <div class="heading">{L_HLJS_BBCODEBOX_HEADING}<a class="codehlb_toggle">{L_HLJS_BBCODEBOX_BUTTON_SHOW}</a>'.
                                       '{L_HLJS_BBCODEBOX_HEAD_SEPARATOR}<a class="codehlb_select" href="#" onclick="selectCode(this); return false;">{L_HLJS_BBCODEBOX_BUTTON_SELECT}</a></div>'."\n". 
                                       '  <pre style="display:none;"><code class="{SIMPLETEXT1}">{TEXT}</code></pre>'."\n".
                                       '</div>',
            ),
        ));
    }
}

