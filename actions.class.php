<?php

/**
 * register actions.
 *
 * @package    petboard
 * @subpackage register
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class registerActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    // is already login?
    if (sfContext::getInstance()->getUser()->isAuthenticated())
      return $this->redirect('/index.php/home/index');
    
    // get registration type, must only(user, shop, pro)
    $this->user_type = $this->getRequestParameter('user_type');
    
    if ($this->user_type == 'user' OR $this->user_type == 'shop' OR $this->user_type == 'pro')
    {
      $this->form = new FormRegister();
      
      if ($request->isMethod('post'))
      {
        $this->form->bind($request->getParameter('register'));
        
        if ($this->form->isValid())
        {
          $this->getUser()->setAttribute('register_type', 		$this->form->getValue('register-type'));
          $this->getUser()->setAttribute('register_name', 		$this->form->getValue('register-name'));
          $this->getUser()->setAttribute('register_email', 	  $this->form->getValue('register-email'));
          $this->getUser()->setAttribute('register_password', $this->form->getValue('register-password'));
          
          $this->redirect('/index.php/register/confirm');
        }
      }
    }
  }
  
  public function executeConfirm(sfWebRequest $request)
  {
    // is already login?
    if (sfContext::getInstance()->getUser()->isAuthenticated())
      return $this->redirect('/index.php/home/index');
    
    $this->form = new FormRegisterConfirm();
    $this->is_submit = false;
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('registerconfirm'));
      
      if ($this->form->isValid())
      {
        $this->is_submit = true;
        $this->message = $this->getContext()->getI18N()->__('Thank you!');
        
        $role_id = 2; // user by default..
        $user_type = $this->form->getValue('register-type');
        
        if ($user_type == 'pro')
          $role_id = 3;
        else if ($user_type == 'shop')
          $role_id = 4;
        
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Mix'));
        $password = md5hash($this->form->getValue('register-password'));
        
        $oUser = new User();
        $oUser->setEmail($this->form->getValue('register-email'));
        $oUser->setPassword($password);
        $oUser->setFirst_Name($this->form->getValue('register-name'));
        $oUser->setRole_Id($role_id);
        $oUser->setLogin_Type(0); // 0 = native, 1 = fb, 2 = twitter..
        $oUser->setActive(1);
        $oUser->save();
      }
    }
    else
    {
      $this->register_type 		  = $this->getUser()->getAttribute('register_type');
      $this->register_name 		  = $this->getUser()->getAttribute('register_name');
      $this->register_email 	  = $this->getUser()->getAttribute('register_email');
      $this->register_password 	= $this->getUser()->getAttribute('register_password');
    }
  }
  
  public function executeForgotpassword(sfWebRequest $request)
  {
    $this->form = new FormForgotPassword();
		$this->is_sent = 0;
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('forgotpassword'));
      
      if ($this->form->isValid())
			{
        // proccess here..
      }
    }
  }
  
  public function executeRich(sfWebRequest $request){
    $this->renderText('My new founciton');
  }
	
	//Christian bootcamp was here!
}
