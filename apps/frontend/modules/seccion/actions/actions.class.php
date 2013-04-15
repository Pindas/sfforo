<?php

/**
 * seccion actions.
 *
 * @package    sfforo
 * @subpackage seccion
 * @author     Daniel López
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class seccionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->seccion = Doctrine
      ::getTable('Seccion')
      ->findOneBy('slug', $request->getParameter('slug'));
    $this->mensaje_temas = Doctrine_Core::getTable('MensajeTema')
      ->createQuery('a')
      ->where('a.id_seccion = ?', $this->seccion->getId())
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->seccion = Doctrine
      ::getTable('Seccion')
      ->find($request->getParameter('id'));
    $this->form = new MensajeTemaForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new MensajeTemaForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($mensaje_tema = Doctrine_Core::getTable('MensajeTema')->find(array($request->getParameter('id'))), sprintf('Object mensaje_tema does not exist (%s).', $request->getParameter('id')));
    $this->form = new MensajeTemaForm($mensaje_tema);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($mensaje_tema = Doctrine_Core::getTable('MensajeTema')->find(array($request->getParameter('id'))), sprintf('Object mensaje_tema does not exist (%s).', $request->getParameter('id')));
    $this->form = new MensajeTemaForm($mensaje_tema);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($mensaje_tema = Doctrine_Core::getTable('MensajeTema')->find(array($request->getParameter('id'))), sprintf('Object mensaje_tema does not exist (%s).', $request->getParameter('id')));
    $mensaje_tema->delete();

    $this->redirect('seccion/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $mensaje_tema = $form->save();

      $this->redirect('seccion/edit?id='.$mensaje_tema->getId());
    }
  }
}
