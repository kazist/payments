<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

namespace Payments\Deposits\Code\Models;

/**
 * Description of DepositsModel
 *
 * @author sbc
 */
use Kazist\Model\BaseModel;
use Kazist\KazistFactory;

class DepositsModel extends BaseModel {

    public function appendSearchQuery($query) {

        $factory = new KazistFactory();
        $user = $factory->getUser();
        $document = $this->container->get('document');
        $search = $document->search;

        $query = parent:: appendSearchQuery($query);

        if (WEB_IS_ADMIN) {
            $user_id = $this->request->get('user_id');
        } else {
            $user_id = $user->id;
        }

        $query->leftJoin('pd', '#__payments_gateways', 'pg', 'pg.id=pp.gateway_id');
        $query->addSelect('pp.amount_required, pp.amount_paid, pp.receipt_no, pp.code');
        $query->addSelect('pg.short_name as gateway_id_short_name');

        if ($search['gateway_id'] <> '') {
            $query->where('pp.gateway_id = :gateway_id');
            $query->setParameter('gateway_id', (int) $search['gateway_id']);
        }

        if ($user_id) {
            $query->andwhere('pd.user_id=:user_id');
            $query->setParameter('user_id', (int) $user_id);
            $query->andwhere('pd.completed=1');
            $query->andwhere('pd.successful=1');
        }

        return $query;
    }

    public function processPayment() {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        $document = $this->container->get('document');
        $default_gateway = $factory->getSetting('subscription_subscription_default_gateway');

        $form = $this->request->get('form');

        if ($form['amount']) {

            $deposit = $this->getDeposit($user->id, $form);

            $redirect = $this->generateUrl('finance.payments.newpayment', array(
                'path' => 'payments.deposits',
                'gateway_id' => $default_gateway,
                'pay_subset_id' => $document->subset_id,
                'item_id' => $deposit->id,
                'is_new' => 0,
                'type' => 'deposit',
                'amount' => $form['amount'],
                'description' => 'Partial Payments.'
            ));
        } else {
            $factory->enqueueMessage('Amount is not set.', 'error');
            $redirect = $this->generateUrl('payments.deposits');
        }

        return $redirect;
    }

    public function getDeposit($user_id, $form) {

        $factory = new KazistFactory();

        $data = new \stdClass();

        $data->payment_id = 0;
        $data->user_id = $user_id;
        $data->amount = $form['amount'];

        $record = $factory->getRecord('payments_deposits', 'pd', array('pd.user_id=:user_id', 'pd.completed=0 OR pd.completed IS NULL'), array('user_id' => $user_id));

        if (empty($record)) {
            $factory->saveRecordByEntity('payments_deposits', $data);
            $record = $factory->getRecord('payments_deposits', 'pd', array('pd.user_id=:user_id', 'pd.completed=0 OR pd.completed IS NULL'), array('user_id' => $user_id));
        }

        return $record;
    }

    public function getGatewaysInput() {

        $tmp_array = array();

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__payments_gateways', 'pg');
        $query->andWhere('pg.published=1');
        $gateways = $query->loadObjectList();

        if (!empty($gateways)) {
            foreach ($gateways as $key => $gateway) {
                $tmp_array[] = array('value' => $gateway->id, 'text' => $gateway->short_name);
            }
        }

        return $tmp_array;
    }

}
