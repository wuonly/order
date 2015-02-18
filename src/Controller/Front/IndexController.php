<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Order\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Order\Form\RemoveForm;
use Zend\Json\Json;

class IndexController extends ActionController
{
    public function removeAction()
    {
        // Get invoice id
        $id = $this->params('id');
        // Get post
        if ($this->request->isPost()) {
            $data = $this->request->getPost()->toArray();
            if (isset($data['id']) && !empty($data['id'])) {
                Pi::api('processing', 'order')->removeProcessing();
                $message = __('Your old payment process remove, please try new payment ation');
            } else {
                $message = __('Payment is clean');
            }
            $this->jump(array('', 'action' => 'invoice', 'id' => $id), $message);
        } else {
            $processing = Pi::api('processing', 'order')->getProcessing();
            if (isset($processing['id']) && !empty($processing['id'])) {
                $values['id'] = $processing['id'];
            } else {
                $message = __('Payment is clean');
                $this->jump(array('', 'action' => 'invoice', 'id' => $id), $message);
            }
            // Set form
            $form = new RemoveForm('Remove');
            $form->setData($values);
            // Set view
            $this->view()->setTemplate('remove');
            $this->view()->assign('form', $form);
        }    
    }

    public function finishAction()
    {
        $processing = Pi::api('processing', 'order')->getProcessing();
        if (!empty($processing['invoice'])) {
            $invoice = $processing['invoice'];
            // remove
            Pi::api('processing', 'order')->removeProcessing();
            //
            $invoice = Pi::api('invoice', 'order')->getInvoice($invoice);
            // jump to module
            $message = __('Your payment were successfully.');
            $this->jump($invoice['back_url'], $message);
        } else {
            // Set return
            $return = array(
                'website' => Pi::url(),
                'module' => $this->params('module'),
                'message' => 'finish',
            );
            // Set view
            $this->view()->setTemplate(false)->setLayout('layout-content');
            return Json::encode($return);
        }
    }

    public function errorAction()
    {
        // Set return
        $return = array(
            'website' => Pi::url(),
            'module' => $this->params('module'),
            'message' => 'error',
            'id' => $this->params('id'),
        );
        // Set view
        $this->view()->setTemplate(false)->setLayout('layout-content');
        return Json::encode($return);  
    }

    public function checkUser()
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Check config
        if ($config['order_anonymous'] == 0) {
            // Check user is login or not
            Pi::service('authentication')->requireLogin();
        }
        // Check
        if (!Pi::service('authentication')->hasIdentity()) {
            if (!isset($_SESSION['payment']['process']) || $_SESSION['payment']['process'] != 1) {
                $this->jump(array('', 'action' => 'error'));
            }
            // Set session
            $_SESSION['payment']['process_update'] = time();
        }
        //
        return true;
    }
}