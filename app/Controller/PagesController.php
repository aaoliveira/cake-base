<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Node');

        
        public function admin_index( $node = 'home' )
        {
            $this->set('title_for_layout', Inflector::humanize($node));
            
            if ( !empty($this->request->data) ) {
                $this->Node->create();
                if ( $this->Node->save($this->request->data) ) {
                    $this->Session->setFlash('<p>Conteúdo atualizado com sucesso!</p>', 'default', 
                            array('class' => 'notification msgsuccess'));
                    $this->redirect(array('controller' => 'pages', 'action' => 'index', $node, 'admin' => true));
                } else {
                    $this->Session->setFlash('<p>Não foi possível gravar o conteudo, por favor tente novamente.</p>', 
                            'default', array('class' => 'notification msgerror'));
                }
            }
            
            $page = $this->Node->find('first', array('conditions' => array('type' => 'page', 'location' => $node)));
            if( $page ) {
                $this->request->data = $page;
            } else {
                $this->request->data = array('Node' => 
                    array('type' => 'page', 'location' => $node, 'content' => '',
                        'keywords' => '', 'description' => ''));
            }
            
        }
        
/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}
