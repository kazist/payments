<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payments\Transactions\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Service\Items\Pagination;
use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Subscriptions\Subscriptions\Code\Classes\Subscriber;
use Subscriptions\Subscriptions\Code\Classes\SyncRegistered;
use Subscriptions\Subscriptions\Code\Classes\Rank;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class TransactionsModel extends BaseModel {

    //put your code here
    //public function getQueryBuilder() {
    public function appendSearchQuery($query) {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        $view_name = $this->request->get('view');
        $document = $this->container->get('document');
        $search = $document->search;

        if (WEB_IS_ADMIN) {
            $user_id = $this->request->get('user_id');
        } else {
            $user_id = $user->id;
        }

        $query = parent:: appendSearchQuery($query);

        $query->leftJoin('pt', '#__users_users', 'pt_uu', 'pt_uu.id = pt.behalf_user_id');
        $query->leftJoin('pt', '#__users_users', 'pt_own', 'pt_own.id = pt.user_id');

        if ($user_id) {
            $query->andWhere('pt.user_id=:user_id');
            $query->setParameter('user_id', $user_id);
        }

        if ($search['from']) {
            $query->andWhere('pt.behalf_user_id=:behalf_user_id');
            $query->setParameter('behalf_user_id', $this->getUserIdByUsername($search['from']));
        }

        if ($search['to']) {
            $query->andWhere('pt.user_id=:user_id');
            $query->setParameter('user_id', $this->getUserIdByUsername($search['to']));
        }

        if ($view_name == 'commission') {
            $query->andWhere('pt.payment_source=:payment_source');
            $query->setParameter('payment_source', 'affiliates.affiliates');
        }

        $query->addSelect('pt_uu.username AS trans_username, pt_uu.name AS trans_name, pt_uu.email AS trans_email');
        $query->addSelect('pt_own.username AS own_username, pt_own.name AS own_name, pt_own.email AS own_email');

        return $query;
    }

    public function save($form_data = '') {

        $form_data = $this->request->get('form');

        $form_data['behalf_user_id'] = $form_data['user_id'];
        $form_data['type'] = 'admin';

        if ($form_data['amount'] > 0) {
            $form_data['credit'] = $form_data['amount'];
        } else {
            $form_data['debit'] = abs($form_data['amount']);
        }
   
        return parent::save($form_data);
    }

    public function reverseTransaction($id, $payment_source) {

        $factory = new KazistFactory();

        $check_arr = array('item_id' => $id, 'payment_source' => $payment_source);

        $records = $factory->getRecords('#__payments_transactions', 'pt', array('item_id=:item_id', 'payment_source=:payment_source'), $check_arr);

        if (!empty($records)) {
            foreach ($records as $record) {

                $credit = $record->credit;
                $debit = $record->debit;

                $record->payment_source = $payment_source;
                $record->credit = $debit;
                $record->debit = $credit;
                $record->description = 'Reversed for :- ' . $record->description . '[Txn:' . $record->id . ']';

                unset($record->id);
                unset($record->is_capped);
                unset($record->token);
                unset($record->is_processed);
                unset($record->created_by);
                unset($record->modified_by);
                unset($record->date_created);
                unset($record->date_modified);

                $factory->saveRecord('#__payments_transactions', $record);
            }
        }
    }

    public function getUserIdByUsername($username) {

        $query = $this->getQueryBuilder('#__users_users', 'uu');
        $query->andWhere('uu.username = :username');
        $query->setParameter('username', $username);
        $user = $query->loadObject();

        return $user->id;
    }

    public function getUserById($user_id) {

        $query = $this->getQueryBuilder('#__users_users', 'uu');
        $query->andWhere('uu.id = :id');
        $query->setParameter('id', $user_id);
        $user = $query->loadObject();

        return $user;
    }

    public function getLevelsInput() {

        $tmp_array = array();

        $tmp_array[] = array('value' => '1', 'text' => '1');
        $tmp_array[] = array('value' => '2', 'text' => '2');
        $tmp_array[] = array('value' => '3', 'text' => '3');
        $tmp_array[] = array('value' => '4', 'text' => '4');
        $tmp_array[] = array('value' => '5', 'text' => '5');

        return $tmp_array;
    }

    public function getTypesInput() {

        $tmp_array = array();
        $view_name = $this->request->get('view');

        $factory = new KazistFactory();
        $user = $factory->getUser();

        $query = $this->getQueryBuilder('#__payments_transactions', 'pt');
        $query->select('DISTINCT pt.type');
        $query->andWhere('pt.user_id =' . (int) $user->id);
        if ($view_name == 'commission') {
            $query->andWhere('pt.payment_source=:payment_source');
            $query->setParameter('payment_source', 'affiliates.affiliates');
        }

        $types = $query->loadObjectList();

        if (!empty($types)) {
            foreach ($types as $key => $type) {
                $tmp_array[] = array('value' => $type->type, 'text' => $type->type);
            }
        }


        return $tmp_array;
    }

}
