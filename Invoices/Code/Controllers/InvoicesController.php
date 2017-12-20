<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of InvoicesController
 *
 * @author sbc
 */

namespace Payments\Invoices\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;

class InvoicesController extends BaseController {

    public function payAction() {


        $user = $this->model->getUser();
        $invoices = $this->model->getInvoices();
        $prepayments = $this->model->getPrepayments();

        $data_arr['invoices'] = $invoices;
        $data_arr['prepayments'] = $prepayments;
        $data_arr['user'] = $user;

        $this->html = $this->render('Payments:Invoices:Code:views:pay.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
