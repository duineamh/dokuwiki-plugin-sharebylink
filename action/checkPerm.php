<?php
/**
 * DokuWiki Plugin sharebylink (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Peter Rohmann <dokuwiki@razupaltuff.com>
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
        $controller->register_hook('AUTH_ACL_CHECK', 'AFTER', $this, 'handle_auth_acl_check');
   
    }

    /**
     * [Custom event handler which performs action]
     *
     * Called for event:
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

