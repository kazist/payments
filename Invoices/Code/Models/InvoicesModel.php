<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payments\Invoices\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\System\System;
use Kazist\Service\Database\Query;
use Hosting\Domains\Code\Classes\Domain;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class InvoicesModel extends BaseModel {

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
    public $amount = 0;
    public $user_id = 0;
    public $invoice_source = '';
    public $invoice = '';
    public $items = '';
    public $deposit_gateway = '';

    public function getInvoice($invoice_id = '') {

        if ($invoice_id) {
            $record = $this->getInvoiceById($invoice_id);
        } else {
            $record = $this->getInvoiceByParams();
        }


        $invoice_id = $this->saveNewInvoice($record->id, $record);

        if (!is_object($record)) {
            $record = $this->getInvoiceById($invoice_id);
        }

        return $record;
    }

    public function addInvoiceItems($invoice_id) {

        $factory = new KazistFactory();

        $total_amount = 0;
        $description = '';

        if (!empty($this->items)) {

            $factory->deleteRecords('#__payments_invoices_items', array('id=:id'), array('id' => $invoice_id));
           
            foreach ($this->items as $key => $item) {
                if ($item['amount']) {
                    $total_amount += $item['unit_price'];
                    $description .= $item['description'] . ' | ';

                    $item['invoice_id'] = $invoice_id;

                    $factory->saveRecord('#__payments_invoices_items', $item);
                }
            }

            if ($total_amount) {
                
                $invoice_data = new \stdClass();
                $invoice_data->id = $invoice_id;
                $invoice_data->amount = $total_amount;
                $invoice_data->description = $description;

                $factory->saveRecord('#__payments_invoices', $invoice_data);
            }
        }
    }

    public function getInvoiceById($invoice_id = '') {

        $system = new System();
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('pp.*');
        $query->from('#__payments_invoices', 'pp');

        if ($invoice_id) {
            $query->where('pp.id=:id');
            $query->setParameter('id', $invoice_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        $record->items = $factory->getRecords('#__payments_invoices_items', 'pii', array('pii.invoice_id=:invoice_id'), array('invoice_id' => $record->id));

        return $record;
    }

    public function getInvoiceByParams() {

        $factory = new KazistFactory();

        $subset_id = ($this->pay_subset_id) ? $this->pay_subset_id : $this->request->query->get('pay_subset_id');
        $item_id = ($this->item_id) ? $this->item_id : $this->request->query->get('item_id');
        $amount = ($this->amount) ? $this->amount : $this->request->query->get('amount');

        $query = new Query();
        $query->select('pp.*');
        $query->from('#__payments_invoices', 'pp');
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

    public function saveNewInvoice($invoice_id = '', $invoice = '') {

        $uniq_id = uniqid();
        $data_obj = new \stdClass();

        $factory = new KazistFactory();

        $user = $factory->getUser();

        $data_obj->id = $invoice_id;

        if (!$invoice_id) {
            $data_obj->invoice_no = $uniq_id;
            $data_obj->expiry_date = date('Y-m-d 00-00-00', strtotime('1 month'));
        }

        if ($this->pay_subset_id || $this->request->query->get('pay_subset_id')) {
            $data_obj->subset_id = ($this->pay_subset_id) ? $this->pay_subset_id : $this->request->query->get('pay_subset_id', '1');
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

        if ($this->user_id || $this->request->query->get('user_id') || !$invoice->user_id) {
            $data_obj->user_id = ($this->user_id) ? $this->user_id : $user->id;
        }

        if ($this->invoice_source || $this->request->query->get('invoice_source') || !$invoice->invoice_source) {
            $data_obj->invoice_source = ($this->invoice_source) ? $this->invoice_source : $this->request->query->get('invoice_source');
        }

        if (is_object($invoice)) {
            $data_obj = json_decode(json_encode(array_merge((array) $invoice, (array) $data_obj)));
        }

        $id = $factory->saveRecord('#__payments_invoices', $data_obj);

        $this->addInvoiceItems($id);

        return $id;
    }

}
