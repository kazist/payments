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
use Payments\Code\Classes\Subscriber;

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

        $data = new \stdClass();
        $data->id = $payment->item_id;
        $data->payment_id = $payment->id;
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

        $subscriber_obj = new Subscriber();

        $subscriber = $subscriber_obj->getSubscriber($payment->user_id);

        // Phpfox::getService('mlmfinance.commission')->giveCommission($advert);

        $tmp_array['subscriber'] = $subscriber;
        $tmp_array['payment'] = $payment;
        $tmp_array['url']['dashboard'] = $this->generateUrl('payments.deposits', null, 0);

        return $tmp_array;
    }

}
