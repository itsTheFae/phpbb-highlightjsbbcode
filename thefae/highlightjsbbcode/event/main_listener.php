<?php

namespace thefae\highlightjsbbcode\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
    protected $config;
    protected $helper;
    protected $template;
    protected $user;
    
    public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
    {
        $this->config = $config;
        $this->helper = $helper;
        $this->template = $template;
        $this->user = $user;
    }
    
    /**
     * Assign functions defined in this class to event listeners in the core
     *
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            'core.user_setup'   => 'load_language_on_setup',
            'core.page_header'  => 'add_page_header_data'
        );
    }

    /**
     * Load the language file
     *
     * @param \phpbb\event\data $event The event object
     */
    public function load_language_on_setup($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'thefae/highlightjsbbcode',
            'lang_set' => 'highlightjsbbcode',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }
    
    public function add_page_header_data($event)
    {
        $style_name = (!empty($this->config['thefae_hljs_bbcodebox_style'])) ? $this->config['thefae_hljs_bbcodebox_style'] : "default";
        
        $vars = array(
            'HLJSBBC_STYLE_NAME'     => $style_name,
        );
        
        $this->template->assign_vars( $vars );
    }
}

