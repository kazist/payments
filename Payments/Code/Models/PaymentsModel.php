<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payments\Payments\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\System\Callback;
use Kazist\Service\System\System;
use Payments\Transactions\Code\Models\TransactionsModel;
use Kazist\Service\Database\Query;
use Payments\Payments\Code\Events\PaymentEvent;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class PaymentsModel extends BaseModel {

    public $ingore_search_query = false;
    public $currency = 'USD';
    public $currency_rate = '1';
    public $total_amount = 0;
    public $json = 0;
    public $pay_app_id = 0;
    public $pay_com_id = 0;
    public $pay_subset_id = 0;
    public $item_id = 0;
    public $description = 0;
    public $quantity = 0;
    public $is_new = 0;
    public $amount = 0;
    public $user_id = 0;
    public $payment_source = '';
    public $payment = '';
    public $items = '';
    public $deposit_gateway = '';

    public function appendSearchQuery($query) {

        $factory = new KazistFactory();
        $user = $factory->getUser();
        $user_id = $user->id;

        $document = $this->container->get('document');
        $search = $document->search;

        $query = parent:: appendSearchQuery($query);

        if (!$this->ingore_search_query) {
            if (WEB_IS_ADMIN) {

                $user_id = $this->request->query->get('user_id');

                if ($user_id) {
                    $query->andWhere('pp.user_id=' . $user_id);
                } elseif ($search['from']) {
                    $query->andWhere('pp.user_id=:user_id');
                    $query->setParameter('user_id', $this->getUserIdByUsername($search['from']));
                }
            } else {
                if ($user_id) {
                    $query->andWhere('pp.user_id=' . $user_id);
                } else {
                    $query->andWhere('1=-1');
                }
            }

            $query->andWhere('pp.amount>0');

            $query->andWhere('pp.completed=1');
        }

        return $query;
    }

    public function getUserIdByUsername($username) {

        $query = $this->getQueryBuilder('#__users_users', 'uu');
        $query->andWhere('uu.username = :username');
        $query->setParameter('username', $username);
        $user = $query->loadObject();

        return $user->id;
    }

    public function getUser($user_id) {

        $factory = new KazistFactory();

        if ($user_id) {
            $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));
        } else {
            $user = $factory->getUser();
        }

        $user->money_in = $this->getUserMoney($user->id, 'credit');
        $user->money_out = $this->getUserMoney($user->id, 'debit');
        $user->total = $user->money_in - $user->money_out;

        $wallets = $factory->getRecords('#__payments_wallets', 'pw', array('pw.published=1'));

        foreach ($wallets as $key => $wallet) {

            $query = $factory->getQueryBuilder('#__payments_transactions', 'pt');
            $query->select('COUNT(*) as amount');
            $query->andWhere('pt.payment_source=:payment_source');
            $query->setParameter('payment_source', $wallet->payment_source);
            $query->andWhere('pt.user_id=:user_id');
            $query->setParameter('user_id', $user->id);
            $wallets_amount = $query->loadObject();

            $wallets[$key]->amount = $wallets_amount->amount;
        }

        $user->wallets = $wallets;

        return $user;
    }

    public function getUserMoney($user_id, $type = 'credit') {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('SUM(pt.' . $type . ') as total');
        $query->from('#__payments_transactions', 'pt');
        $query->where('user_id=:user_id');
        $query->setParameter('user_id', $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getConverterAmount($amount, $gateway, $is_up = true) {

        $query = new Query();
        $query->select('*');
        $query->from('#__setup_currencies', 'sc');
        $query->where('sc.id=:id');
        $query->setParameter('id', $gateway->currency_id);
        $record = $query->loadObject();

        if ($is_up) {
            $new_amount = ($record->buying) ? $amount * $record->buying : $amount * $record->rate;
        } else {
            $new_amount = ($record->buying) ? $amount / $record->buying : $amount / $record->rate;
        }

        return $new_amount;
    }

    public function getConverter() {


        $factory = new KazistFactory();

        $user = $factory->getUser();

        $default_currency = $factory->getSetting('setup_currency_default_code');
        $country_id = $user->country_id;

        $query = new Query();
        $query->select('*');
        $query->from('#__setup_currencies', 'sc');
        $query->where('sc.country_id=:country_id');
        $query->setParameter('country_id', $country_id);
        $record = $query->loadObject();


        if (!$record->published) {
            $query = new Query();
            $query->select('*');
            $query->from('#__setup_currencies', 'sc');
            $query->where('sc.code=:code');
            $query->setParameter('code', $default_currency);
            $record = $query->loadObject();
        }

        $record->buying = ($record->buying) ? $record->buying : 1;
        $record->selling = ($record->selling) ? $record->selling : 1;

        $this->currency = $record->code;
        $this->currency_rate = ($record->buying) ? $record->buying : $record->rate;

        return $record;
    }

    public function getPaymentGateway($gateway_id, $payment_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('pg.*');
        $query->from('#__payments_gateways', 'pg');
        $query->where('pg.published=1');
        $query->andWhere('pg.id=:id');
        $query->setParameter('id', $gateway_id);

        $record = $query->loadObject();

        if (is_object($record)) {
            $record = $this->processPaymentGatewayAdvance($payment_id, $record);
        }

        return $record;
    }

    public function getPaymentGateways($payment_id) {

        $gateways = array();

        $factory = new KazistFactory();
        $session = $factory->getSession();

        $deposit_gateway_name = $factory->getSetting('payments_gateway_deposit_gateway');

        $query = $factory->getQueryBuilder('#__payments_gateways', 'pg');
        $query->where('pg.can_payment=1');
        $query->andWhere('pg.published=1');
        $query->orderBy('pg.ordering', 'ASC');

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $key => $record) {

                $is_allowed = $this->getIsAllowedInCountry($record->id);
                $is_notallowed = $this->getIsNotAllowedInCountry($record->id);
                $count_allowed = $this->getCountIsAllowedIn($record->id);

                if ($count_allowed && !$is_allowed) {
                    unset($records[$key]);
                }

                if ($is_notallowed) {
                    unset($records[$key]);
                }

                if (isset($records[$key])) {
                    $records[$key] = $this->processPaymentGatewayAdvance($payment_id, $record);
                    $gateways[$record->short_name] = $records[$key];
                }

                if ($deposit_gateway_name == $record->id) {
                    $this->deposit_gateway = $record;
                }

                if (!$record->image) {
                    $image_path = 'applications/' . $record->form_path . '/image.png';

                    if (file_exists(JPATH_ROOT . $image_path)) {
                        $record->image = 1;
                        $record->image_file = $image_path;
                    }
                }
            }

            $records = array_values($records);
        }

        $records = array_values($records);

        $session->set('gateways', $gateways);

        return $records;
    }

    public function processPaymentGatewayAdvance($payment_id, $record) {

        $this->getConverter();
        $record->rates = $this->getPaymentGatewayInvoice($payment_id, $record->id);
        $record->parameters = $this->getPaymentGatewayParameters($record->id);
        $record->total_amount = $this->total_amount;
        $record->total_amount_in_cent = $this->total_amount * 100;
        $record->currency = $this->currency;
        $record->json = $this->json;

        return $record;
    }

    public function getPaymentGatewayInvoice($payment_id, $gateway_id) {

        $payment = $this->getPayment($payment_id);

        $factory = new KazistFactory();
        $factory->loggingMessage('Amt=' . $payment->amount . ',  Payment=' . $payment_id . '  Gateway ' . $gateway_id);

        foreach ($payment->coupons as $coupon) {
            $payment->amount = $payment->amount - $coupon->amount;
        }

        $gateway_rates = $this->getPaymentGatewayInvoiceRates($payment->amount, $gateway_id, 'payment');

        return $gateway_rates;
    }

    public function getPaymentGatewayInvoiceRates($amount, $gateway_id, $type = 'payment') {

        $main_amount = 0;
        $total_amount = 0;

        $main_array = array();
        $total_array = array();
        $tmp_array = array();

        $main_amount_obj = new \stdClass();
        $total_amount_obj = new \stdClass();
        $gateway_obj = new \stdClass();

        $gateways = $this->getGatewayRates($amount, $gateway_id, $type);

        $main_amount = $total_amount = $amount;

        if (!empty($gateways)) {
            foreach ($gateways as $key => $gateway) {

                $gateway->highlighted = false;

                switch ($gateway->rule) {
                    case '+%':
                        $gateway->amount = $amount * $gateway->value / 100;
                        $gateway->label = '+' . $gateway->value . '%';
                        break;
                    case '-%':
                        $gateway->amount = -1 * $amount * $gateway->value / 100;
                        $gateway->label = '-' . $gateway->value . '%';
                        break;
                    case '+':
                        $gateway->amount = $gateway->value;
                        $gateway->label = '+' . $gateway->value;
                        break;
                    case '-':
                        $gateway->amount = -1 * $gateway->value;
                        $gateway->label = '-' . $gateway->value;
                        break;
                }

                if (!$gateway->is_visible) {
                    $main_amount += $gateway->amount;
                } else {
                    $tmp_array[] = $gateway;
                }

                $total_amount += $gateway->amount;
            }

            $gateway_obj->transactions = $gateways;
        }

        $gateway_obj->intial_amount = $main_amount;
        $gateway_obj->amount = $total_amount;

        $this->json = json_encode($gateway_obj);

        if ($main_amount <> $total_amount) {
            $main_amount_obj->title = 'Sub Total';
            $main_amount_obj->is_visible = true;
            $main_amount_obj->amount = $main_amount;
            $main_amount_obj->highlighted = true;
            $main_array[] = $main_amount_obj;
        }

        $total_amount_obj->title = 'Total';
        $total_amount_obj->is_visible = true;
        $total_amount_obj->amount = $total_amount;
        $total_amount_obj->highlighted = true;
        $total_array[] = $total_amount_obj;


        $this->total_amount = $total_amount;


        return array_merge($main_array, $tmp_array, $total_array);
    }

    public function getGatewayRates($amount, $gateway_id, $type) {

        $rates = array();

        switch ($type) {
            case 'withdraw':
                $rates = $this->getWithdrawGatewayRates($amount, $gateway_id);
                break;
            case 'transfer':
                $rates = $this->getTransferGatewayRates($amount, $gateway_id);
                break;
            case 'payment':
            default:
                $rates = $this->getPaymentGatewayRates($amount, $gateway_id);
                break;
        }

        return $rates;
    }

    public function getWithdrawGatewayRates($amount, $gateway_id) {


        $factory = new KazistFactory();

        $user = $factory->getUser();

        $country_id = $user->country_id;

        $query = new Query();
        $query->select('pr.*');
        $query->from('#__payments_rates', 'pr');
        $query->leftJoin('pr', '#__payments_gateways_withdrawrates', 'pgw', 'pr.id = pgw.rate_id');
        $query->where('pgw.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        if ($country_id) {
            $query->andWhere('(pr.country_id=:country_id OR pr.country_id=0 OR pr.country_id IS NULL)');
            $query->setParameter('country_id', $country_id);
        } else {
            $query->andWhere('(pr.country_id=\'\' OR pr.country_id IS NULL OR pr.country_id = 0)');
        }

        $query->andWhere('(:amount BETWEEN pr.start_amount AND pr.end_amount OR pr.end_amount=0 OR pr.end_amount IS NULL )');
        $query->andWhere('pr.published=1');
        $query->setParameter('amount', $amount);

        $records = $query->loadObjectList();


        return $records;
    }

    public function getTransferGatewayRates($amount, $gateway_id) {


        $factory = new KazistFactory();

        $user = $factory->getUser();

        $country_id = $user->country_id;

        $query = new Query();
        $query->select('pr.*');
        $query->from('#__payments_rates', 'pr');
        $query->leftJoin('pr', '#__payments_gateways_transferrates', 'pgt', 'pr.id = pgt.rate_id');
        $query->where('pgt.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);
        if ($country_id) {
            $query->andWhere('(pr.country_id=:country_id OR pr.country_id=0 OR pr.country_id IS NULL)');
            $query->setParameter('country_id', $country_id);
        } else {
            $query->andWhere('(pr.country_id=\'\' OR pr.country_id IS NULL)');
        }
        $query->andWhere('(:amount BETWEEN pr.start_amount AND pr.end_amount OR pr.end_amount=0 OR pr.end_amount IS NULL )');
        $query->setParameter('amount', $amount);
        $query->andWhere('pr.published=1');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getPaymentGatewayRates($amount, $gateway_id) {


        $factory = new KazistFactory();

        $user = $factory->getUser();

        $country_id = $user->country_id;

        $query = new Query();
        $query->select('pr.*');
        $query->from('#__payments_rates', 'pr');
        $query->leftJoin('pr', '#__payments_gateways_paymentrates', 'pgp', 'pr.id = pgp.rate_id');
        $query->where('pgp.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);
        if ($country_id) {
            $query->andWhere('(pr.country_id=:country_id OR pr.country_id=0 OR pr.country_id IS NULL)');
            $query->setParameter('country_id', $country_id);
        } else {
            $query->andWhere('(pr.country_id=\'\' OR pr.country_id IS NULL)');
        }
        $query->andWhere('((:amount BETWEEN pr.start_amount AND pr.end_amount) OR pr.end_amount=0 OR pr.end_amount IS NULL )');
        $query->setParameter('amount', (int) $amount);
        $query->andWhere('pr.published=1');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getPaymentGatewayParameters($gateway_id) {



        $query = new Query();
        $query->select('pgp.*');
        $query->from('#__payments_gateways_parameters', 'pgp');
        $query->where('pgp.gateway_id=:gateway_id');
        $query->andWhere('pgp.is_private=0');
        $query->setParameter('gateway_id', $gateway_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getGatewayByShortName($short_name) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('pg.*');
        $query->from('#__payments_gateways', 'pg');
        $query->where('pg.short_name=:short_name');
        $query->setParameter('short_name', $short_name);

        $record = $query->loadObject();

        return $record;
    }

    public function getGatewayParameter($gateway_id, $parameter) {


        $query = new Query();
        $query->select('pgp.value');
        $query->from('#__payments_gateways_parameters', 'pgp');
        $query->where('pgp.gateway_id=:gateway_id');
        $query->andWhere('pgp.name=:parameter');
        $query->setParameter('gateway_id', $gateway_id);
        $query->setParameter('parameter', $parameter);

        $record = $query->loadObject();

        return $record->value;
    }

    public function getCountIsAllowedIn($gateway_id) {

        $factory = new KazistFactory();


        $user = $factory->getUser();


        if ($user->country_id) {

            $query = new Query();
            $query->select('COUNT(*) as total');
            $query->from('#__payments_gateways_allowedin', 'pba');
            $query->where('pba.gateway_id=:gateway_id');
            $query->setParameter('gateway_id', $gateway_id);

            $result = $query->loadResult();

            return $result;
        } else {
            return false;
        }
    }

    public function getIsAllowedInCountry($gateway_id) {

        $factory = new KazistFactory();


        $user = $factory->getUser();


        if ($user->country_id) {

            $query = new Query();
            $query->select('pba.*');
            $query->from('#__payments_gateways_allowedin', 'pba');
            $query->where('pba.gateway_id=:gateway_id');
            $query->andWhere('pba.country_id=:country_id');
            $query->setParameter('gateway_id', $gateway_id);
            $query->setParameter('country_id', $user->country_id);

            $record = $query->loadObject();

            return (is_object($record)) ? true : false;
        } else {
            return true;
        }
    }

    public function getIsNotAllowedInCountry($gateway_id) {

        $factory = new KazistFactory();


        $user = $factory->getUser();

        if ($user->country_id) {

            $query = new Query();
            $query->select('pbd.*');
            $query->from('#__payments_gateways_disallowedin', 'pbd');
            $query->where('pbd.gateway_id=:gateway_id');
            $query->andWhere('pbd.country_id=:country_id');
            $query->setParameter('gateway_id', $gateway_id);
            $query->setParameter('country_id', $user->country_id);

            $record = $query->loadObject();

            return (is_object($record)) ? true : false;
        }
    }

    public function getRedirectUrl($app_id, $com_id, $subset_id, $item_id) {

        $factory = new KazistFactory();
        $system = new System();

        return $this->generateUrl('' . $app_name . '.' . $com_name . '.' . $subset_name . '.edit', array('id', $item_id));
    }

    public function getPayment($payment_id = '') {

        if ($payment_id) {

            $record = $this->getPaymentById($payment_id);
        } else {
            $record = $this->getPaymentByParams();
        }

        $payment_id = $this->saveNewPayment($record->id, $record);

        if (!is_object($record)) {
            $record = $this->getPaymentById($payment_id);
        }

        return $record;
    }

    public function addPaymentCoupons($payment_id) {

        $where_arr = array();

        $factory = new KazistFactory();

        $payment = $factory->getRecord('#__payments_payments', 'pp', array('pp.id=' . (int) $payment_id));

        if ($payment->payment_source == 'payments.deposits') {
            return false;
        }

        $amount_query_str = '(pc.start_amount IS NULL AND pc.end_amount IS NULL) OR '
                . '(pc.start_amount <= :amount AND pc.end_amount IS NULL) OR '
                . '(pc.start_amount IS NULL AND pc.end_amount >= :amount) OR '
                . '(pc.start_amount <= :amount AND pc.end_amount >= :amount)';

        $date_query_str = '(pc.start_date IS NULL AND pc.end_date IS NULL) OR '
                . '(pc.start_date <= :current_date AND pc.end_date IS NULL) OR '
                . '(pc.start_date IS NULL AND pc.end_date >= :current_date) OR '
                . '(pc.start_date <= :current_date AND pc.end_date >= :current_date)';

        $query = $factory->getQueryBuilder('#__payments_coupons', 'pc');
        $query->andWhere($date_query_str);
        $query->setParameter('current_date', date('Y-m-d H:i:s'));
        $query->andWhere($amount_query_str);
        $query->setParameter('amount', $payment->amount);

        $where_arr[] = 'pc.applied = \'all_payment\'';
        if ($payment->is_new) {
            $where_arr[] = 'pc.applied = \'all_new\'';
        }

        $query->andWhere(implode(' OR ', $where_arr));
        $coupons = $query->loadObjectList();

        if (!empty($coupons)) {
            foreach ($coupons as $key => $coupon) {

                $start_amount = (int) $coupon > start_amount;
                $end_amount = (int) $coupon > end_amount;
                $paid_amount = $coupon > amount;

                if ($paid_amount < $start_amount || $paid_amount > $end_amount) {
                    continue;
                }
                $amount = (!$coupon->is_percent) ? $coupon->value : $payment->amount * $coupon->value;


                $data = new \stdClass();
                $data->payment_id = $payment->id;
                $data->coupon_id = $coupon->id;
                $exist = clone $data;
                $data->item_id = $payment->item_id;
                $data->description = $coupon->description;
                $data->amount = $amount;

                $factory->saveRecord('#__payments_payments_coupons', $data, array('payment_id=:payment_id', 'coupon_id=:coupon_id'), $exist);
            }
        }
    }

    public function addPaymentItems($payment_id) {

        $factory = new KazistFactory();

        $total_amount = 0;
        $description = '';

        if (!empty($this->items)) {

            $factory->deleteRecords('#__payments_payments_items', array('id=:id'), array('id' => $payment_id));

            foreach ($this->items as $key => $item) {

                if ($item['item_id']) {
                    $total_amount += $item['unit_price'];
                    $description .= $item['description'] . ' | ';

                    $item['payment_id'] = $payment_id;

                    $factory->saveRecord('#__payments_payments_items', $item);
                }
            }

            $payment_data = new \stdClass();
            $payment_data->id = $payment_id;
            $payment_data->amount = $total_amount;
            $payment_data->description = $description;

            $factory->saveRecord('#__payments_payments', $payment_data);
        }
    }

    public function getPaymentById($payment_id = '') {

        $system = new System();
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('pp.*');
        $query->from('#__payments_payments', 'pp');

        if ($payment_id) {
            $query->where('pp.id=:id');
            $query->setParameter('id', $payment_id);
        } else {
            $query->where('1=-1');
        }


        $record = $query->loadObject();

        $record->items = $factory->getRecords('#__payments_payments_items', 'ppi', array('ppi.payment_id=:payment_id'), array('payment_id' => $record->id));
        $record->coupons = $factory->getRecords('#__payments_payments_coupons', 'ppi', array('ppi.payment_id=:payment_id'), array('payment_id' => $record->id));

        return $record;
    }

    public function getPaymentByParams() {

        $factory = new KazistFactory();

        $subset_id = ($this->pay_subset_id) ? $this->pay_subset_id : $this->request->query->get('pay_subset_id');
        $item_id = ($this->item_id) ? $this->item_id : $this->request->query->get('item_id');
        $amount = ($this->amount) ? $this->amount : $this->request->query->get('amount');

        $query = new Query();
        $query->select('pp.*');
        $query->from('#__payments_payments', 'pp');
        $query->where('1=1');

        if ($subset_id && $item_id) {

            $query->andWhere('pp.subset_id=:subset_id');
            $query->andWhere('pp.item_id=:item_id');
            //$query->andWhere('pp.amount=:amount');
            $query->setParameter('subset_id', $subset_id);
            $query->setParameter('item_id', $item_id);
            // $query->setParameter('amount', (int) $amount);
            $query->andWhere('(pp.completed=0 OR pp.completed IS NULL)');
        } else {
            $query->andWhere('1=-1');
        }

        $record = $query->loadObject();

        return $record;
    }

    public function saveNewPayment($payment_id = '', $payment = '') {

        $uniq_id = uniqid();
        $data_obj = new \stdClass();

        $factory = new KazistFactory();

        $user = $factory->getUser();

        $data_obj->id = $payment_id;

        if ($payment_id) {
            $data_obj->receipt_no = $uniq_id;
        }

        if ($this->pay_subset_id || $this->request->query->get('pay_subset_id')) {
            $data_obj->subset_id = ($this->pay_subset_id) ? $this->pay_subset_id : $this->request->query->get('pay_subset_id', '1');
        }

        if ($this->type || $this->request->query->get('type')) {
            $data_obj->type = ($this->type) ? $this->type : $this->request->query->get('type');
        }

        if ($this->is_new || $this->request->query->get('is_new')) {
            $data_obj->is_new = ($this->is_new) ? $this->is_new : $this->request->query->get('is_new');
        }

        if ($this->gateway_id || $this->request->query->get('gateway_id')) {
            $data_obj->gateway_id = ($this->gateway_id) ? $this->gateway_id : $this->request->query->get('gateway_id');
        }

        if ($this->item_id || $this->request->query->get('item_id')) {
            $data_obj->item_id = ($this->item_id) ? $this->item_id : $this->request->query->get('item_id');
        }

        if ($this->description || $this->request->query->get('description')) {
            $data_obj->description = ($this->description) ? $this->description : $this->request->query->get('description');
        }

        if ($this->quantity || $this->request->query->get('quantity')) {
            $data_obj->quantity = ($this->quantity) ? $this->quantity : $this->request->query->get('quantity');
        }

        if ($this->amount || $this->request->query->get('amount')) {
            $data_obj->amount = ($this->amount) ? $this->amount : $this->request->query->get('amount');
        }

        if ($this->user_id || $this->request->query->get('user_id') || !$payment->user_id) {
            $data_obj->user_id = ($this->user_id) ? $this->user_id : $user->id;
        }

        if ($this->payment_source || $this->request->query->get('payment_source') || !$payment->payment_source) {
            $data_obj->payment_source = ($this->payment_source) ? $this->payment_source : $this->request->query->get('payment_source');
        }

        if (is_object($payment)) {
            $data_obj = json_decode(json_encode(array_merge((array) $payment, (array) $data_obj)));
        }

        if ($data_obj->amount) {
            $id = $factory->saveRecord('#__payments_payments', $data_obj);

            $this->addPaymentItems($id);
            $this->addPaymentCoupons($id);
        } else {
            return false;
        }

        return $id;
    }

    public function savePaymentJson($payment_id, $payment_params, $payment_type) {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();

        $payment_params_obj = json_decode($payment_params);

        $data_obj->id = $payment_id;
        $data_obj->deductions = $payment_params;
        $data_obj->type = $payment_type;
        $data_obj->amount_required = $payment_params_obj->amount;
        $data_obj->completed = 1;

        $factory->saveRecord('#__payments_payments', $data_obj);
    }

    public function notificationTransaction($payment_id, $code = '') {

        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);

        $this->updateTaxationTransactions($payment);
        $this->updateCouponsTransactions($payment);
        $this->savePaymentCodeStatus($payment_id, $code, true);
        $factory->enqueueMessage('Thank you. Your payment was Successful.', 'info');

        $this->container->get('dispatcher')->dispatch('payment.notification', new PaymentEvent($payment));
    }

    public function successfulTransaction($payment_id, $code = '') {
 
        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);

        $this->updateTaxationTransactions($payment);
        $this->updateCouponsTransactions($payment);
        $this->savePaymentCodeStatus($payment_id, $code, true);
        $factory->enqueueMessage('Thank you. Your payment was Successful.', 'info');
       
        $this->container->get('dispatcher')->dispatch('payment.successful', new PaymentEvent($payment));
 
    }

    public function failTransaction($payment_id) {

        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);
        $this->savePaymentCodeStatus($payment_id);
        $factory->enqueueMessage('Sorry. Your payment is Payment has Failed.', 'error');

        $this->container->get('dispatcher')->dispatch('payment.fail', new PaymentEvent($payment));
    }

    public function invalidTransaction($payment_id) {

        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);
        $this->savePaymentCodeStatus($payment_id);
        $factory->enqueueMessage('Sorry. Your payment is Invalid.', 'error');

        $this->container->get('dispatcher')->dispatch('payment.invalid', new PaymentEvent($payment));
    }

    public function pendingTransaction($payment_id) {

        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);
        $this->savePaymentCodeStatus($payment_id);
        $factory->enqueueMessage('Thank you. Your payment is being processed.', 'error');

        $this->container->get('dispatcher')->dispatch('payment.pending', new PaymentEvent($payment));
    }

    public function completeTransaction($payment_id) {

        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);
        $this->savePaymentCodeStatus($payment_id);
        $factory->enqueueMessage('Thank you. Your payment is Completed.', 'info');

        $this->container->get('dispatcher')->dispatch('payment.complete', new PaymentEvent($payment));
    }

    public function cancelTransaction($payment_id) {

        $callback = new Callback();
        $factory = new KazistFactory();

        $payment = $this->getPayment($payment_id);
        $this->savePaymentCodeStatus($payment_id);
        $factory->enqueueMessage('Sorry. Your payment was Cancelled.', 'error');

        $callback->dispatch('onPaymentCancel', 'payments.payment.cancel', $payment);
    }

    public function savePaymentCodeStatus($payment_id, $code = '', $successful = false) {

        $payment = new \stdClass();
        $factory = new KazistFactory();

        if ($code == '') {
            return;
        }

        $payment->id = $payment_id;
        $payment->completed = 1;
        $payment->successful = $successful;
        $payment->code = $code;

        $factory->saveRecord('#__payments_payments', $payment);
    }

    public function savePaidAmount($payment, $required_amount, $paid_amount) {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        if ($required_amount == $paid_amount) {
            
        } elseif ($required_amount > $paid_amount) {

            $factory->enqueueMessage('Amount Paid of (' . number_format($paid_amount, 2) . ') is less than Amount Required (of ' . number_format($required_amount, 2) . ').', 'info');
            $factory->enqueueMessage('Amount(' . $paid_amount . ') Was credited to your account. Top up Your account and  use pay with wallet for payment.', 'info');
        } elseif ($paid_amount > $required_amount) {

            $over_payment = $paid_amount - $required_amount;

            $factory->enqueueMessage('Amount Paid of (' . number_format($paid_amount, 2) . ') is more than Amount Required (of ' . number_format($required_amount, 2) . ').', 'info');
            $factory->enqueueMessage('Amount(' . $over_payment . ') Was credited to your account. Top up Your account and  use pay with wallet for payment.', 'info');
        }


        $payment_obj = new \stdClass();
        $payment_obj->id = $payment->id;
        $payment_obj->receipt_no = $payment->receipt_no;
        $payment_obj->code = $payment->code;
        $payment_obj->type = $payment->type;
        $payment_obj->amount_required = $required_amount;
        $payment_obj->amount_paid = $paid_amount;
        if ($payment->gateway_id) {
            $payment_obj->gateway_id = $payment->gateway_id;
        }

        $factory->saveRecord('#__payments_payments', $payment_obj);

        $data_obj = new \stdClass();
        $data_obj->user_id = $user->id;
        $data_obj->behalf_user_id = $payment->user_id;
        $data_obj->item_id = $payment->item_id;
        $data_obj->payment_id = $payment->id;
        $data_obj->description = $payment->description . ' [Txn:' . $payment->id . ']';
        $data_obj->credit = $paid_amount;
        $data_obj->type = 'payment';
        $data_obj->payment_source = $payment->payment_source;

        $factory->saveRecord('#__payments_transactions', $data_obj);

        return true;
    }

    public function updateCouponsTransactions($payment) {

        $factory = new KazistFactory();

        if (!empty($payment->coupons)) {
            foreach ($payment->coupons as $coupon) {

                $data_obj = new \stdClass();
                $data_obj->user_id = $payment->user_id;
                $data_obj->behalf_user_id = $payment->user_id;
                $data_obj->item_id = $payment->item_id;
                $data_obj->payment_id = $payment->id;
                $data_obj->description = '[Coupon] ' . trim($coupon->description);
                $data_obj->debit = -$coupon->amount;
                $data_obj->type = 'coupon-discount';
                $data_obj->payment_source = $payment->payment_source;

                $factory->saveRecord('#__payments_transactions', $data_obj);
            }
        }
    }

    public function updateTaxationTransactions($payment) {

        $factory = new KazistFactory();

        $deductions = json_decode($payment->deductions, true);


        if (!empty($deductions['transactions'])) {
            foreach ($deductions['transactions'] as $rate) {

                $data_obj = new \stdClass();
                $data_obj->user_id = $payment->user_id;
                $data_obj->behalf_user_id = $payment->user_id;
                $data_obj->rate_id = $rate['id'];
                $data_obj->item_id = $payment->item_id;
                $data_obj->payment_id = $payment->id;
                $data_obj->description = $rate['title'] . ' [Txn:' . $payment->id . ']';
                $data_obj->debit = $rate['amount'];
                $data_obj->type = 'other-payment-charges';
                $data_obj->payment_source = $payment->payment_source;

                $factory->saveRecord('#__payments_transactions', $data_obj);
            }
        }
    }

    public function getGatewayByName($name) {

        $factory = new KazistFactory();

        $record = $factory->getRecord('#__payments_gateways', 'pg', array('short_name=:short_name'), array('short_name' => $name));

        return $record;
    }

    public function getUrlByPaymentId($payment_id) {

        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);
        $record = $factory->getRecord('#__system_subsets', 'ss', array('ss.id=:id'), array('id' => $payment->subset_id));

        if (is_object($record)) {
            $url = $this->generateUrl(str_replace('/', '.', $record->path) . '.detail', array('id' => $payment->item_id));
        } else {
            $url = $this->generateUrl('payments.payments');
        }

        return $url;
    }

    public function saveParams($payment_id, $posted_data) {

        $factory = new KazistFactory();

        if (is_object($posted_data) || is_array($posted_data)) {
            $posted_data = json_encode($posted_data);
        }

        $data_obj = new \stdClass();
        $data_obj->payment_id = $payment_id;
        $data_obj->params = $posted_data;

        $factory->saveRecord('#__payments_payments', $data_obj);
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

//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  reverseWithdraw
    public function reversePayment() {

        $factory = new KazistFactory();

        $transactionModel = new TransactionsModel();

        $id = $this->request->get('id');

        $transactionModel->reverseTransaction($id, 'payment');

        $data_obj = new \stdClass();
        $data_obj->id = $id;
        $data_obj->is_canceled = 1;
        $factory->saveRecord('#__payments_payments', $data_obj);

        $transactionModel->reverseTransaction($id, 'subscription');
    }

}
