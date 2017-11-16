<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of DepositsController
 *
 * @author sbc
 */

namespace Payments\Deposits\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Kazist\Controller\BaseController;
use Payments\Payments\Code\Models\PaymentsModel;

class DepositsController extends BaseController {

    public function addAction($id = '') {

        $payment = new PaymentsModel();

        $user = $payment->getUser();

        $this->data_arr['user'] = $user;

        return parent::addAction($id);
    }

    public function saveAction() {

        $email = new Email();

        $payment = new PaymentsModel();
        $factory = new KazistFactory();
        $user = $factory->getUser();

        $form = $this->request->get('form');

        $wallet_name = $factory->getSetting('payments_gateway_wallet_name');
        $default_gateway = $factory->getSetting('payments_gateway_default_gateway');
        $tmp_minimum_amount = $factory->getSetting('payments_deposits_minimum_amount');
        $minimum_amount = ($tmp_minimum_amount) ? $tmp_minimum_amount : 10;
        $tmp_maximum_amount = $factory->getSetting('payments_deposits_maximum_amount');
        $maximum_amount = ($tmp_maximum_amount) ? $tmp_maximum_amount : 500;

        $form['description'] = 'Deposit $' . $form['amount'] . ' to your ' . $wallet_name . ' wallet';

        if ($form['amount'] > $maximum_amount) {
            $factory->enqueueMessage('The deposited money is more than Maximum Amount set($' . $maximum_amount . ')', 'error');
            return $this->redirect($this->generateUrl('payments.deposits.add'));
        } elseif ($form['amount'] < $minimum_amount) {
            $factory->enqueueMessage('The deposited money is less than Minimum Amount set($' . $minimum_amount . ')', 'error');
            return $this->redirect($this->generateUrl('payments.deposits.add'));
        }

        $form['user_id'] = $user->id;

        $deposit_id = $factory->saveRecord('#__payments_deposits', $form);

        //Processing Payment Page
        $payment->item_id = $deposit_id;
        $payment->gateway_id = $default_gateway;
        $payment->user_id = $user->id;
        $payment->payment_source = 'payments.deposits';
        $payment->description = $form['description'];
        $payment->amount = $form['amount'];

        $payment_obj = $payment->getPayment();

        $return_url = $this->generateUrl('payments.payments.pay', array('payment_id' => $payment_obj->id));

        return $this->redirect($return_url);
    }

}
