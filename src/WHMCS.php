<?php

namespace WHMCS;

class WHMCS extends WhmcsCore {

    /**
     * Instantiate a new instance
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return a list of all clients
     *
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getClients($start = 0, $limit = 25, $search = null)
    {
        $data = [
            'action'        => 'GetClients',
            'limitstart'    => $start,
            'limitnum'      => $limit,
        ];

        if($search)
            $data['search'] = $search;

        return $this->submitRequest($data);
    }

    /**
     * Returns the specified client's data
     *
     * @param string|int $client_id
     * @param bool $stats
     * @return array
     */
    public function getClientDetails($client_id, $stats = false)
    {
        $data = [
            'action'    =>  'GetClientsDetails',
            'clientid'  =>  $client_id,
            'stats'     =>  $stats
        ];

        $response =  $this->submitRequest($data, false);
        if ($response["result"] == "error")
        {
            return null;
        }
        return $response;
    }

    /**
     * Updates client information.
     * For value_updates, pass an array of key => value combinations.
     * @param $client_id
     * @param $value_updates
     * @return array
     */
    public function UpdateClient($client_id, $value_updates)
    {
        $data = [
            'action' => 'UpdateClient',
            'clientid' => $client_id,
        ];
        $data = array_merge($data, $value_updates);
        return $this->submitRequest($data);
    }

    /**
     * Returns the specified client's domainss
     *
     * @param string|int $clientId
     * @return array
     */
    public function getClientDomains($client_id, $start = 0, $limit = 25)
    {
        $data = [
            'action'        =>  'GetClientsDomains',
            'clientid'      =>  $client_id,
            'limitstart'    =>  $start,
            'limitnum'      =>  $limit
        ];

        return $this->submitRequest($data);
    }

    /**
     * Return a list of a client's products
     *
     * @param int $client_id
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getClientProducts($client_id, $start = 0, $limit = 25)
    {
        $data = [
            'action'        =>  'GetClientsProducts',
            'clientid'      =>  $client_id,
            'limitstart'    =>  $start,
            'limitnum'      =>  $limit
        ];
        return $this->submitRequest($data);
    }

    /**
     * Returns a list of product types
     *
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function getAllProducts()
    {
        $data = [
            'action'        =>  'GetProducts',
        ];

        return $this->submitRequest($data);
    }
    /**
     * Creates a new client
     *
     * @param array $data
     * @return array
     */
    public function createClient($data)
    {
        $data['action'] = 'addclient';

        $response = $this->submitRequest($data);
        return $response["clientid"];
    }


    /**
     * Returns a list of tickets.
     *
     * @param int $client_id
     * @param int $start
     * @param int $limit
     * @param string $subject
     * @param string $status
     * @param int $department_id
     * @param string $ignoredept
     * @return array
     */
    public function getTickets($client_id = null, $start = 0, $limit = 50, $subject = null, $status = null, $department_id = null, $ignoredept = 'false')
    {
        $data = [
            'action'                   =>  'GetTickets',
            'limitstart'               =>  $start,
            'limitnum'                 =>  $limit,
            'ignore_dept_assignments'  =>  $ignoredept,
        ];

        if ($subject) {
            $data['subject'] = $subject;
        }

        if ($client_id) {
            $data['clientid'] = $client_id;
        }

        if ($status) {
            $data['status'] = $status;
        }

        if ($department_id) {
            $data['deptid'] = $department_id;
        }

        return $this->submitRequest($data);
    }

    /**
     * Creates a new ticket.
     *
     * @param int $client_id
     * @param int $department_id
     * @param string $subject
     * @param string $priority
     * @param string $message
     * @param boolean $markdown
     * @return array
     */
    public function openTicket($client_id, $department_id, $subject, $priority, $message, $markdown = true)
    {
        $data = [
            'action'        =>  'OpenTicket',
            'clientid'      =>  $client_id,
            'deptid'        =>  $department_id,
            'subject'       =>  $subject,
            'priority'      =>  $priority,
            'message'       =>  $message,
            'markdown'      =>  $markdown,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Adds a new reply to a ticket.
     *
     * @param int $client_id
     * @param int $ticket_id
     * @param string $message
     * @param boolean $markdown
     * @return array
     */
    public function addTicketReply($client_id, $ticket_id, $message, $markdown = true)
    {
        $data = [
            'action'        =>  'AddTicketReply',
            'clientid'      =>  $client_id,
            'ticketid'      =>  $ticket_id,
            'message'       =>  $message,
            'useMarkdown'   =>  $markdown,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Retrieves a specific ticket.
     *
     * @param int $ticket_num
     * @param string $sort
     * @return array
     */
    public function getTicket($ticket_num, $sort)
    {
        $data = [
            'action'        =>  'GetTicket',
            'ticketnum'     =>  $ticket_num,
            'repliessort'   =>  $sort,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Retrieves the available support departments.
     *
     * @param bool $ignore_dept_assignments
     * @return array
     */
    public function getSupportDepartments($ignore_dept_assignments = true)
    {
        $data = [
            'action'                    =>  'GetSupportDepartments',
            'ignore_dept_assignments'   =>  $ignore_dept_assignments,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Update a ticket.
     *
     * @param string $status
     * @return array
     */
    public function updateTicket($ticket_id, $dataToUpdate)
    {
        $data = [
            'action'     =>  'UpdateTicket',
            'ticketid'   =>  $ticket_id,
        ];
        $data = array_merge($data, $dataToUpdate);
        return $this->submitRequest($data);
    }

    /**
     * Returns a list of invoices of a client
     *
     * @param $client_id
     * @param $limitstart
     * @param $limitnum
     * @param $status
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function getInvoices($client_id, $limitstart, $limitnum, $status)
    {
        $data = [
            'action'        =>  'GetInvoices',
            'userid'        =>  $client_id,
            'limitstart'    =>  $limitstart,
            'limitnum'      =>  $limitnum,
            'status'        =>  $status,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Returns a specific invoice
     *
     * @param $invoiceid
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function getInvoice($invoiceid)
    {
        $data = [
            'action'        =>  'GetInvoice',
            'invoiceid'     =>  $invoiceid,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Adds a transaction to the WHMCS backlog.
     * @param string $paymentmethod
     * @param int $custom_id
     * @param int $invoice_id
     * @param string $transaction_id
     * @param string $description
     * @param float $amount
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function addTransaction(string $paymentmethod, int $custom_id, int $invoice_id, string $transaction_id, string $description, float $amount)
    {
        $data = [
            'action'        => 'AddTransaction',
            'paymentmethod' => $paymentmethod,
            'userid'        => $custom_id,
            'invoiceid'     => $invoice_id,
            'transid'       => $transaction_id,
            'description'   => $description,
            'amountin'      => $amount,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Adds an order to the WHMCS backlog.
     *
     * @param string $paymentmethod
     * @param int $custom_id
     * @param array $product_ids
     * @param array $product_cycles
     * @param string|null $promocode
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function addProductOrder(string $paymentmethod, int $custom_id, array $product_ids, array $product_cycles, string $promocode = null)
    {
        $data = [
            'action'        => 'AddOrder',
            'clientid'      => $custom_id,
            'paymentmethod' => $paymentmethod,
            'pid'           => $product_ids,
            'billingcycle'  => $product_cycles,
        ];
        if ($promocode)
        {
            $data['promocode'] = $promocode;
        }
        return $this->submitRequest($data);
    }

    /**
     * Upgrades a service to a new product.
     * @param int $oldid
     * @param int $newid
     * @param string $newcycle
     * @param string $paymentmethod
     * @param string|null $promocode
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function upgradeProduct(int $oldid, int $newid, string $newcycle, string $paymentmethod, string $promocode = null)
    {
        $newcycle = ucfirst(strtolower($newcycle));

        // For some weird reason, semiannually is the exception to WHMC's rule to specify the billing cycle with a capital letter first.
        if ($newcycle == "Semiannually")
        {
            $newcycle = "semiannually";
        }
        $data = [
            'action'                    => 'UpgradeProduct',
            'serviceid'                 => $oldid,
            'paymentmethod'             => $paymentmethod,
            'type'                      => 'product',
            'newproductid'              => $newid,
            'newproductbillingcycle'    => $newcycle,
        ];
        if ($promocode)
        {
            $data['promocode']          = $promocode;
        }

        return $this->submitRequest($data);
    }

    /**
     *
     * Calculates the parameters of a product upgrade.
     * @param int $oldid
     * @param int $newid
     * @param string $newcycle
     * @param string $paymentmethod
     * @param string|null $promocode
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function calculateProductUpgrade(int $oldid, int $newid, string $newcycle, string $paymentmethod, string $promocode = null)
    {
        $newcycle = ucfirst(strtolower($newcycle));

        // For some weird reason, semiannually is the exception to WHMC's rule to specify the billing cycle with a capital letter first.
        if ($newcycle == "Semiannually")
        {
            $newcycle = "semiannually";
        }
        $data = [
            'action'                    => 'UpgradeProduct',
            'calconly'                  => true,
            'serviceid'                 => $oldid,
            'paymentmethod'             => $paymentmethod,
            'type'                      => 'product',
            'newproductid'              => $newid,
            'newproductbillingcycle'    => $newcycle,
        ];
        if ($promocode)
        {
            $data['promocode']          = $promocode;
        }
        return $this->submitRequest($data);
    }

    /**
     * Cancels a subscription (or at least sends the request to cancel)
     * @param int $serviceid
     * @param string $canceltype
     * @param string $reason
     * @return array
     * @throws Error\WHMCSConnectionException
     */
    public function cancelSubscription(int $serviceid, string $canceltype, string $reason)
    {
        $data = [
            'action'        => 'AddCancelRequest',
            'serviceid'     => $serviceid,
            'type'          => $canceltype,
            'reason'        => $reason
            ];

        return $this->submitRequest($data);
    }

    /**
     * Fetches a clients domains.
     * @param int $customer_id
     * @param int $limitstart
     * @param int $limitnum
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getClientsDomains(int $customer_id, int $limitstart, int $limitnum)
    {
        $data = [
            'action'        => 'GetClientsDomains',
            'limitstart'    => $limitstart,
            'limitnum'      => $limitnum,
            'clientid'      => $customer_id,
        ];

        return $this->submitRequest($data);
    }
}
