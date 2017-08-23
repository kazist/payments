<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payments\Deposits\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\Service\Email\Email;
use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

/**
 * Description of AdvertModel
 *
 * @author sbc
 */
class PaymentModel extends BaseModel {

    //put your code here

    public function paymentSuccessful($payment) {

        $email = new Email();
        $factory = new KazistFactory();

        $deductions = json_decode($payment->deductions);
        $charges = $deductions->amount - $deductions->intial_amount;

        $data = new \stdClass();
        $data->id = $payment->item_id;
        $data->payment_id = $payment->id;
        $data->payment_id = $payment->id;
        $data->amount = $payment->amount_paid - $charges;
        $data->successful = 1;
        $data->completed = 1;
        
        $factory->saveRecordByEntity('payments_deposits', $data);
     
        $email_data = $this->getDataObject($payment);

        $email->sendDefinedLayoutEmail('payments.deposits.paymentsuccessful', $email_data['subscriber']->email, $email_data);
    }

    public function paymentFail($payment) {

        $email = new Email();
        $factory = new KazistFactory();

        $data = new \stdClass();
        $data->payment_id = $payment->id;
        $data->id = $payment->item_id;
        $data->completed = 1;

        $factory->saveRecordByEntity('payments_deposits', $data);

        $email_data = $this->getDataObject($payment);

        $email->sendDefinedLayoutEmail('payments.deposits.paymentfail', $email_data['subscriber']->email, $email_data);
    }

    public function paymentComplete($payment) {

        $email = new Email();
        $factory = new KazistFactory();

        $data = new \stdClass();
        $data->id = $payment->item_id;
        $data->payment_id = $payment->id;
        $data->completed = 1;
        $factory->saveRecordByEntity('payments_deposits', $data);



        $email_data = $this->getDataObject($payment);

        $email->sendDefinedLayoutEmail('payments.deposits.paymentcomplete', $email_data['subscriber']->email, $email_data);
    }

    public function paymentCancel($payment) {

        $email = new Email();
        $factory = new KazistFactory();

        $data = new \stdClass();
        $data->id = $payment->item_id;
        $data->payment_id = $payment->id;
        $data->completed = 1;
        $factory->saveRecordByEntity('payments_deposits', $data);



        $email_data = $this->getDataObject($payment);

        $email->sendDefinedLayoutEmail('payments.deposits.paymentcancel', $email_data['subscriber']->email, $email_data);
    }

    public function getDataObject($payment) {

        $tmp_array['user'] = $this->getUser($payment->user_id);
        $tmp_array['deposit'] = $this->getDeposit($payment->item_id);
        $tmp_array['url']['dashboard'] = $this->generateUrl('payments.deposits', null, 0);

        return $tmp_array;
    }

    public function getUser($user_id) {

        $factory = new KazistFactory();

        $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));

        return $user;
    }

    public function getDeposit($item_id) {

        $query = new Query();
        $query->select('pd.*');
        $query->from('#__payments_deposits', 'pd');
        $query->where('pd.id=:id');
        $query->setParameter('id', (int) $item_id);
        $record = $query->loadObject();

        return $record;
    }

}
