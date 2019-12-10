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
     * Return all clients.
     *
     * @param int $start
     * @param int $limit
     * @param string $sorting
     * @param string $search
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getClients(int $start = null, int $limit = null, string $sorting = null, string $search = null)
    {
        $data = [
            'action' => 'GetClients',
        ];

        if($start) $data['limitstart'] = $start;
        if($limit) $data['limitnum'] = $limit;
        if($sorting) $data['sorting'] = $sorting;
        if($search) $data['search'] = $search;

        return $this->submitRequest($data);
    }

    /**
     * Returns the specified client's data
     *
     * @param null $clientId
     * @param bool $stats
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getClientsDetails($clientId = null, $email = null, $stats = null)
    {
        $data = [
            'action' => 'GetClientsDetails',
        ];

        if($clientId) $data['clientid'] = $clientId;
        if($email) $data['email'] = $email;
        if($stats) $data['stats'] = $stats;

        return $this->submitRequest($data);
    }

    /**
     * Updates client information.
     * For value_updates, pass an array of key => value combinations.
     *
     * @param $client_id
     * @param $value_updates
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
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
	 * @param int     $client_id
	 * @param int     $department_id
	 * @param string  $subject
	 * @param string  $priority
	 * @param string  $message
	 * @param boolean $markdown
	 * @param int    $product_id
	 * @param boolean    $is_domain
	 *
	 * @return array
	 * @throws Error\WHMCSConnectionException
	 * @throws Error\WHMCSResultException
	 */
    public function openTicket($client_id, $department_id, $subject, $priority, $message, $markdown = true, $product_id = null, $is_domain = null)
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

        if($product_id){
        	if($is_domain == null){
        	        $data['serviceid'] = $product_id;
	        } else {
        		if($is_domain == true)
			        $data['domainid'] = $product_id;
	        }
        }

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
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
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
     * Retrieves a specific ticket by num.
     *
     * @param $num
     * @param string $sort
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getTicketByNum($num, $sort)
    {
        $data = [
            'action'        =>  'GetTicket',
            'ticketnum'     =>  $num,
            'repliessort'   =>  $sort,
        ];

        return $this->submitRequest($data);
    }


    /**
     * Retrieves a specific ticket.
     *
     * @param $id
     * @param string $sort
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getTicketById($id, $sort)
    {
        $data = [
            'action'        =>  'GetTicket',
            'ticketid'      =>  $id,
            'repliessort'   =>  $sort,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Retrieves the available support departments.
     *
     * @param bool $ignore_dept_assignments
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
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
     * @param $ticket_id
     * @param $dataToUpdate
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
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
     * @throws Error\WHMCSResultException
     */
    public function getInvoices($client_id = null, $limitstart = null, $limitnum = null, $status = null)
    {
        $data = [
            'action' => 'GetInvoices',
        ];

        if ($client_id) {
            $data['userid'] = $client_id;
        }

        if ($limitstart) {
            $data['limitstart'] = $limitstart;
        }

        if ($limitnum) {
            $data['limitnum'] = $limitnum;
        }

        if ($status) {
            $data['status'] = $status;
        }


        return $this->submitRequest($data);
    }

    /**
     * Returns a specific invoice
     *
     * @param $invoiceid
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
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
     * @throws Error\WHMCSResultException
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
     * @param array $domain_names
     * @param array $domain_durations
     * @param array $domain_types
     * @param array $domain_epps
     * @param string $promocode
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function addOrder(string $paymentmethod, int $custom_id, array $product_ids, array $product_cycles, array $domain_names, array $domain_durations, array $domain_types, array $domain_epps, string $promocode = null)
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

        if (count($domain_names) > 0)
        {
            // For each domain name, WHMCS wants an extra entry in the type array.
            $data['domaintype'] = $domain_types;
            $data['regperiod'] = $domain_durations;
            $data['domain'] = $domain_names;
            $data['eppcode'] = $domain_epps;
        }
        return $this->submitRequest($data);
    }

    /**
     * Upgrades a service to a new product.
     *
     * @param int $oldid
     * @param int $newid
     * @param string $newcycle
     * @param string $paymentmethod
     * @param string $promocode
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
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
        ];

        if ($newcycle != '')
        {
            $data['newproductbillingcycle'] = $newcycle;
        }
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

    /**
     * Renews a domain of a user.
     * @param int $customer_id
     * @param string $domainname
     * @param int $years
     * @param string $paymentmethod
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function domainRenew(int $customer_id, string $domainname, int $years, string $paymentmethod)
    {
        $data = [
            'action'            => 'AddOrder',
            'clientid'          => $customer_id,
            'paymentmethod'     => $paymentmethod,
            'domainrenewals'    => array($domainname => $years),
        ];
        return $this->submitRequest($data);
    }

    /**
     * Gets the pricing for a certain TLD. Requires a Currency ID instead of a code because the API is weird like that.
     * @param int $user_id
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getTLDPricing(int $currency_id)
    {
        $data = [
            'action'    => 'GetTLDPricing',
            'currencyid'  => $currency_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Returns Whois information of a given domain.
     * @param string $domain
     * @param bool $require_success = false
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getDomainWhois(string $domain, bool $require_success = false)
    {
        $data = [
            'action'    => 'DomainWhois',
            'domain'    =>  $domain,
        ];

        return $this->submitRequest($data, $require_success);
    }

    /**
     * Returns an array of currency information.
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getCurrencies()
    {
        $data = [
            'action'    => 'GetCurrencies',
        ];

        return $this->submitRequest($data);
    }

    /**
     * Returns an array of payment method information.
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getPaymentMethods()
    {
        $data = [
            'action'    => 'GetPaymentMethods',
        ];

        return $this->submitRequest($data);
    }

    /**
     * Gets the nameservers of a domain.
     * @param int $id
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getDomainNameservers(int $id)
    {
        $data = [
            'action'    => 'DomainGetNameservers',
            'domainid'  => $id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Updates the nameservers of a domain.
     * Ns1 and ns2 are required, ns3 through 5 are updated if supplied.
     * @param int $id
     * @param string $ns1
     * @param string $ns2
     * @param string|null $ns3
     * @param string|null $ns4
     * @param string|null $ns5
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function updateDomainNameServers(int $id, string $ns1, string $ns2, string $ns3 = null, string $ns4 = null, string $ns5 = null)
    {
        $data = [
            'action'    => 'DomainUpdateNameservers',
            'domainid'  => $id,
            'ns1'       => $ns1,
            'ns2'       => $ns2,
        ];
        if ($ns3)
        {
            $data['ns3'] = $ns3;
        }
        if ($ns4)
        {
            $data['ns4'] = $ns4;
        }
        if ($ns4)
        {
            $data['ns4'] = $ns5;
        }

        return $this->submitRequest($data);
    }

    /**
     * Requests an EPP code for a domain.
     * If you only get a result success, the EPP is probably sent to the client
     * directly.
     * @param int $id
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function requestDomainEpp(int $id)
    {
        $data = [
            'action'    => 'DomainRequestEpp',
            'domainid'  => $id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Gets Whois info (More in detail, using the int)
     * @param int $id
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getDomainWhoisInfo(int $id)
    {
        $data = [
            'action'    => 'DomainGetWhoisInfo',
            'domainid'  => $id
        ];

        return $this->submitRequest($data);
    }

    /**
     * Updates the domain whois info based on an xml string of a given domain.
     * @param int $id
     * @param string $xml
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function updateDomainWhoisInfo(int $id, string $xml)
    {
        $data = [
            'action'    => 'DomainUpdateWhoisInfo',
            'domainid'  => $id,
            'xml'       => $xml,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Get a single or all promotion codes
     *
     * @param string $code
     * @return array
     * @throws Error\WHMCSConnectionException
     * @throws Error\WHMCSResultException
     */
    public function getPromotions(string $code = null)
    {
        $data = [
            'action'    => 'GetPromotions',
        ];

        if ($code) {
            $data['code'] = $code;
        }

        return $this->submitRequest($data);
    }

    public function validateLogin(string $email, string $password)
    {
        $data = [
            'action'    => 'ValidateLogin',
            'email'     => $email,
            'password2' => $password
        ];

        return $this->submitRequest($data, false);
    }

    public function getCancelledPackages()
    {
        $data = [
            'action'        => 'GetCancelledPackages',
            'limitstart'    => 0,
            'limitnum'      => 999999999999,
        ];

        return $this->submitRequest($data);
    }

    public function getOrders(int $custom_id, string $status = null)
    {
        $data = [
            'action'        => 'GetOrders',
            'limitstart'    => 0,
            'limitnum'      => 999999999999,
            'userid'        => $custom_id,
        ];
        if ($status)
        {
            $data['status'] = $status;
        }
        return $this->submitRequest($data);
    }
    public function cancelOrder(int $orderid, bool $cancelsub = false, bool $noemail = true)
    {
        $data = [
            'action'    => 'CancelOrder',
            'orderid'   => $orderid,
            'cancelsub' => $cancelsub,
            'noemail'   => $noemail,
        ];

        return $this->submitRequest($data);
    }

    public function getTransactions($search, $value)
    {
        if (! in_array($search, ['invoiceid', 'clientid', 'transid']))
        {
            throw new WHMCSResultException('Cannot find transactions by ' + $search + ". Please use invoiceid, clientid or transid.");
        }
        $data = [
            'action' => 'GetTransactions',
            $search => $value
        ];

        return $this->submitRequest($data);
    }


    public function getOrderById(int $orderid)
    {
        $data = [
            'action' => 'GetOrders',
            'id'    => $orderid
        ];

        return $this->submitRequest($data);
    }
}
