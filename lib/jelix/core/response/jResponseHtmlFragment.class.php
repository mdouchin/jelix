<?php
/**
* @package     jelix
* @subpackage  core_response
* @author      Tahina Ramaroson
* @contributor Sylvain de Vathaire, Dominique Papin, Olivier Demah, Laurent Jouanneau
* @copyright   2008 Tahina Ramaroson, Sylvain de Vathaire
* @copyright   2008 Dominique Papin
* @copyright   2009 Olivier Demah, 2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
 * Send Html part
 * @package  jelix
 * @subpackage core_response
 */
class jResponseHtmlFragment extends jResponse {

    /**
    * jresponse id
    * @var string
    */
    protected $_type = 'htmlfragment';

    /**
    * template selector
    * set the template name in this property
    * @var string
    */
    public $tplname = '';

    /**
    * the jtpl object created automatically
    * @var jTpl
    */
    public $tpl = null;

    /**#@+
     * content surrounding template content
     * @var array
     */
    protected $_contentTop = array();
    protected $_contentBottom = array();
    /**#@-*/

    /**
    * constructor;
    * setup the template engine
    */
    function __construct (){
        $this->tpl = new jTpl();
        parent::__construct();
    }

    /**
    * send the Html part
    * @return boolean    true if it's ok
    */
    final public function output(){

        global $gJConfig;

        if($this->hasErrors()) return false;

        $this->doAfterActions();

        if($this->hasErrors()) return false;

        $content = implode("\n",$this->_contentTop);
        if($this->tplname!=''){
            $content.=$this->tpl->fetch($this->tplname,'html');
            if($this->hasErrors()) return false;
        }
        $content .= implode("\n",$this->_contentBottom);

        $this->_httpHeaders['Content-Type']='text/plain;charset='.$gJConfig->charset;
        $this->_httpHeaders['Content-length']=strlen($content);
        $this->sendHttpHeaders();
        echo $content;
        return true;
    }

    /**
     * add content to the response
     * you can add additionnal content, before or after the content generated by the main template
     * @param string $content additionnal html content
     * @param boolean $beforeTpl true if you want to add it before the template content, else false for after
     */
    function addContent($content, $beforeTpl = false){
      if($beforeTpl){
        $this->_contentTop[]=$content;
      }else{
         $this->_contentBottom[]=$content;
      }
    }


    /**
     * The method you can overload in your inherited htmlfragment response
     * after all actions
     * @since 1.1
     */
    protected function doAfterActions(){
        $this->_commonProcess(); // for compatibility with jelix 1.0
    }

    /**
     * same use as doAfterActions, but deprecated method. It is just here for compatibility with Jelix 1.0.
     * Use doAfterActions instead
     * @deprecated
     */
    protected function _commonProcess(){
    }

    /**
     * output errors
     */
    final public function outputErrors(){

        global $gJConfig;
        $this->clearHttpHeaders();
        $this->_httpStatusCode ='500';
        $this->_httpStatusMsg ='Internal Server Error';
        $this->_httpHeaders['Content-Type']='text/plain;charset='.$gJConfig->charset;

        if($this->hasErrors()){
            $content = $this->getFormatedErrorMsg();
        }else{
            $content = '<p style="color:#FF0000">Unknow Error</p>';
        }

        $this->_httpHeaders['Content-length'] = strlen($content);
        $this->sendHttpHeaders();
        echo $content;
    }

    /**
     * create html error messages
     * @return string html content
     */
    protected function getFormatedErrorMsg(){
        global $gJConfig;
        
        $errors='';
        foreach ($GLOBALS['gJCoord']->errorMessages  as $e) {
           $errors .= '<p style="margin:0;"><b>['.$e[0].' '.$e[1].']</b> <span style="color:#FF0000">';
           $errors .= htmlspecialchars($e[2], ENT_NOQUOTES, $gJConfig->charset)."</span> \t".$e[3]." \t".$e[4]."</p>\n";
           if ($e[5])
              $errors.= '<pre>'.htmlspecialchars($e[5], ENT_NOQUOTES, $gJConfig->charset).'</pre>';
        }
        return $errors;
    }
    
    public function getFormatType(){ return 'html';}
}
