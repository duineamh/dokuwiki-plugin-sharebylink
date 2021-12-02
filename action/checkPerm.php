<?php
/**
 * DokuWiki Plugin sharebylink (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Peter Rohmann <dokuwiki at razupaltuff dot com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class action_plugin_sharebylink_checkPerm extends DokuWiki_Action_Plugin
{

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     *
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {

        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'handle_init');
        $controller->register_hook('AUTH_ACL_CHECK', 'AFTER', $this, 'handle_auth_acl_check');
        $controller->register_hook('MENU_ITEMS_ASSEMBLY', 'AFTER', $this, 'addsvgbutton', array());
   
    }




    public function handle_init() {

        global $JSINFO;
        global $INFO;
        $JSINFO['sharebylink_sharePageDialogOkay'] = $this->sharePageDialogOkay($INFO['id']);
        $JSINFO['sharebylink_shareNewPageOkay'] = $this->shareNewPageOkay($INFO['id']);

    }


    public function sharePageDialogOkay($id) {
       
        global $ACT;
        global $USERINFO;

        if(!($this->getConf('sharekey_accessEnabled') == 1)) return false;

        if(!($ACT == 'show' || empty($ACT))) return false; // Make sure action is "show page" and nothing else

        if(!page_exists($id)) return false;

        if(auth_quickaclcheck($id) < AUTH_EDIT) return false; // only users with edit rights are allowed to activate/deactivate sharing

        if(checklock($id) !== false || @file_exists(wikiLockFN($id))) return false; // make sure the page is not locked

        if(!isset($_SERVER['REMOTE_USER'])) return false;
        if(!auth_isMember($this->getConf('sharekey_allowChangeFor'), $_SERVER['REMOTE_USER'], (array) $USERINFO['grps'])) return false;

        return true;
    }


    public function shareNewPageOkay($id) {
       
        if(!$this->sharePageDialogOkay($id)) return false;

        if(!($this->getConf('sharekey_generationEnabled') == 1)) return false;

        return true;
    }



    /**
     * [Custom event handler which performs action]
     *
     * Called for event: AUTH_ACL_CHECK
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     *
     * @return void
     */
    public function handle_auth_acl_check(Doku_Event $event, $param)
    {
        
        if ($this->getConf('sharekey_accessEnabled') == 1)
        {
        
            global $INPUT;
            
            $sharekey = $INPUT->str('sharekey', 'none');
            
            $mastersharekey = "xx"; //DEBUG
            
            $this->printDebug("BLADEBUG_START\n"); //DEBUG
            
            $this->printDebug("sharekey: "); //DEBUG
            $this->printDebug($sharekey); //DEBUG
            $this->printDebug("\n"); //DEBUG
            
            $this->printDebug("ACLresult before: "); //DEBUG
            $this->printDebug($event->result); //DEBUG
            $this->printDebug("\n"); //DEBUG
            
            //DEBUG
            // compare shareKey to master key            
            if (strcmp($sharekey, $mastersharekey) == 0)
            {
                
                // make sure not to lower the permission
                if ($event->result < AUTH_READ)
                {
                    $event->result = AUTH_READ;
                }
                
            }
            
            $this->printDebug("ACLresult after: "); //DEBUG
            $this->printDebug($event->result); //DEBUG
            $this->printDebug("\n"); //DEBUG
            
            
            $this->printDebug("BLADEBUG_END\n"); //DEBUG
        
        }

        
    }
    

    public function addsvgbutton(Doku_Event $event) {

        if ($this->getConf('sharekey_accessEnabled') == 1) {

            global $INFO, $JSINFO;

            if(
                $event->data['view'] !== 'page' ||
                !$JSINFO['sharebylink_sharePageDialogOkay']
            ) {
                return;
            }
            if(!$INFO['exists']) {
                return;
            }

            array_splice($event->data['items'], -1, 0, array(new \dokuwiki\plugin\sharebylink\MenuItem()));

        } else {

            return;

        }
    }



    //DEBUG
    private function printDebug($outputString)
    {
        $activateDebug = false;
        
        if ($activateDebug)
        {
            print($outputString);
        }
        
    }

}

