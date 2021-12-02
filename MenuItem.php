<?php
namespace dokuwiki\plugin\sharebylink;
use dokuwiki\Menu\Item\AbstractItem;
/**
 * Class MenuItem
 *
 * Implements the ShareByLink button for DokuWiki's menu system
 *
 * @package dokuwiki\plugin\sharebylink
 */

class MenuItem extends AbstractItem {

    /** @var string icon file */
    protected $svg = __DIR__ . '/images/sharebutton.svg';


    public function getLinkAttributes($classprefix = 'menuitem ') {
        $attr = parent::getLinkAttributes($classprefix);
        if (empty($attr['class'])) {
            $attr['class'] = '';
        }
        $attr['class'] .= ' plugin_sharebylink_sharepage ';
        return $attr;
    }

    
    /**
     * Get label from plugin language file
     *
     * @return string
     */
    public function getLabel() {
        $hlp = plugin_load('action', 'sharebylink_checkPerm');
        return $hlp->getLang('sharepage');
    }
}