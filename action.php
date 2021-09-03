<?php
/**
 * DokuWiki Plugin Code Prettifier
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  Satoshi Sahara <sahara.satoshi@gmail.com>
 */
// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class action_plugin_codeprettify extends DokuWiki_Action_Plugin
{
    // register hook
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'insert_button', array());
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'insert_button_inline', array());
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'load_code_prettify');
    }

    /**
     * Insert a toolbar button
     */
    public function insert_button(Doku_Event $event) {
        $event->data[] = array(
            'type' => 'format',
            'title' => 'Insert code',
            'icon' => '../../plugins/codeprettify/images/code.png',
            'open' => '<code: none linenums:1>\n',
            'close' => '\n</code>',
        );
    }
    
    /**
     * Insert a toolbar button
     */
    public function insert_button_inline(Doku_Event $event) {
        $event->data[] = array(
            'type' => 'format',
            'title' => 'Insert code inline',
            'icon' => '../../plugins/codeprettify/images/inline-button.png',
            'open' => "''%%",
            'close' => "%%''",
        );
    }


    /**
     * register google code prettifier script and css
     */
    public function load_code_prettify(Doku_Event $event, $param)
    {
        // Base URL for prettify.js and optional language handler scripts
        // ex: https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/
        if ($this->getConf('url_prettify_handlers')) {
            $urlPrettifyHandlers = $this->getConf('url_prettify_handlers');
        } else {
            $urlPrettifyHandlers =
                DOKU_BASE.'lib/plugins/codeprettify/code-prettify/src/';
        }

        // Base URL for color theme for code-prettify (css)
        // ex: https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/skins/
        if ($this->getConf('url_prettify_skins')) {
            $urlPrettifySkins = $this->getConf('url_prettify_skins');
        } else {
            $urlPrettifySkins =
                DOKU_BASE.'lib/plugins/codeprettify/code-prettify/styles/';
        }

        // load prettify.js and optional language handler scripts
        $handlers = 'prettify';
        if ($this->getConf('lang_handlers')) {
            $handlers .= ',' . trim($this->getConf('lang_handlers'), ',');
            $handlers = str_replace(' ', '', $handlers);
            $handlers = str_replace(',',',lang-', $handlers);
        }
        $scripts = explode(',', $handlers);

        foreach ($scripts as $script) {
            $event->data['script'][] = [
                'type'    => 'text/javascript',
                'charset' => 'utf-8',
                'src'     => $urlPrettifyHandlers. $script.'.js',
                '_data'   => '',
            ];
        }

        // load convenient language handler which enables prettyprinting
        // as plain text, ie. not any kind of language code.
        // use <Code:none>..</Code> to show code as plain text.
        $event->data['script'][] = [
            'type'    => 'text/javascript',
            'charset' => 'utf-8',
            'src'     => DOKU_BASE.'lib/plugins/codeprettify/code-prettify/src/lang-none.js',
            '_data'   => '',
        ];

        // load color theme for code-prettify (css file)
        if ($this->getConf('skin')) {
            $skin = $urlPrettifySkins . $this->getConf('skin');
        } else {
            $skin = $urlPrettifyHandlers .'prettify.css';
        }
        $event->data['link'][] = array (
                'rel'     => 'stylesheet',
                'type'    => 'text/css',
                'href'    => $skin,
        );
    }

}
