<?php

namespace thefae\highlightjsbbcode\core;

use phpbb\template\template;

class utils
{
    public $extPath;
	public $php_ext;
    public $phpbb_root_path;
    protected $template;
    
    public function __construct() {
        global $template, $phpbb_root_path;
        
        $this->php_ext = 'php';  // not sure where to actually get this value. shouldn't matter??
        $this->phpbb_root_path = $phpbb_root_path;
        
        $this->template = $template;
        
        $this->extPath = $phpbb_root_path . "ext/thefae/highlightjsbbcode/" ;
    }
    
    public function getStyleNameList( $style="all" ) 
    {
        $stylePath = $this->extPath . "styles/" . $style . "/theme/highlightjs/";
        $list = array_diff(scandir($stylePath), array('..', '.'));
        $nameList = array();
        foreach( $list as $k => $name ) {
            $file_ext = substr($name, -4);
            if( strtolower($file_ext) != '.css' ) {
                continue;
            }
            
            $nameList[] = substr($name, 0, -4);
        }
        
        return $nameList;
    }
    
    public function getStyleHash()
    {
        $styleStr = join("", $this->getStyleNameList());
        return hash('sha384', $styleStr);
    }
    
    public function regenerateStylesTemplate( $style="all" ) 
    {
        $styleFile = $this->extPath . "styles/" . $style . "/template/event/overall_header_stylesheets_after.html";
        $templateTplS = "<!-- IF HLJSBBC_STYLE_NAME == '{sname}' -->\n".
                        "  <!-- INCLUDECSS @thefae_highlightjsbbcode/highlightjs/{sname}.css -->\n".
                        "<!-- ENDIF -->\n";
        $templateTplE = "\n<!-- INCLUDECSS @thefae_highlightjsbbcode/codebox.css -->\n";
        
        $list = $this->getStyleNameList();
        $tpl = "";
        foreach( $list as $k => $sname ) {
            $tpl .= str_replace('{sname}', $sname, $templateTplS);
        }
        $tpl .= $templateTplE;
        
        file_put_contents( $styleFile, $tpl );
        
        $this->template->clear_cache();
    }
}