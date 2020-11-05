<?php

namespace WHMCS;

use GuzzleHttp\Exception\GuzzleException;
use WHMCS\Exceptions\WHMCSConnectionException;
use WHMCS\Exceptions\WHMCSResultException;

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
     * @param int|null $start
     * @param int|null $limit
     * @param string|null $sorting
     * @param string|null $search
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getClients($start = null, $limit = null, $sorting = null, $search = null)
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
     * @param null $email
     * @param bool $stats
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
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
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
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
     * Returns the specified client's domains
     *
     * @param null $clientId
     * @param null $domainId
     * @param int $start
     * @param int $limit
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getClientDomains($clientId = null, $domainId = null, $start = 0, $limit = 25)
    {
        $data = [
            'action'        =>  'GetClientsDomains',
            'limitstart'    =>  $start,
            'limitnum'      =>  $limit
        ];

        if($clientId) $data['clientid'] = $clientId;
        if($domainId) $data['domainid'] = $domainId;

        return $this->submitRequest($data);
    }

    /**
     * Return a list of a client's products
     *
     * @param null $clientId
     * @param null $serviceId
     * @param int $start
     * @param int $limit
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getClientProducts($clientId = null, $serviceId = null, $start = 0, $limit = 25)
    {
        $data = [
            'action' => 'GetClientsProducts',
            'limitstart' => $start,
            'limitnum' => $limit
        ];

        if($clientId) $data['clientid'] = $clientId;
        if($serviceId) $data['serviceid'] = $serviceId;

        return $this->submitRequest($data);
    }

    /**
     * Returns a list of product types
     *
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getAllProducts()
    {
        $data = [
            'action' => 'GetProducts',
        ];

        return $this->submitRequest($data);
    }

    /**
     * Creates a new client
     *
     * @param array $data
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
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
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getTickets($client_id = null, $start = 0, $limit = 50, $subject = null, $status = null, $department_id = null, $ignoredept = 'false')
    {
        $data = [
            'action' => 'GetTickets',
            'limitstart' => $start,
            'limitnum' => $limit,
            'ignore_dept_assignments' => $ignoredept,
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
     * @param int $product_id
     * @param boolean $is_domain
     *
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function openTicket($client_id, $department_id, $subject, $priority, $message, $markdown = true, $product_id = null, $is_domain = null)
    {
        $data = [
            'action' => 'OpenTicket',
            'clientid' => $client_id,
            'deptid' => $department_id,
            'subject' => $subject,
            'priority' => $priority,
            'message' => $message,
            'markdown' => $markdown,
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
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function addTicketReply($client_id, $ticket_id, $message, $markdown = true)
    {
        $data = [
            'action' => 'AddTicketReply',
            'clientid' => $client_id,
            'ticketid' => $ticket_id,
            'message' => $message,
            'useMarkdown' => $markdown,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Retrieves a specific ticket by num.
     *
     * @param $num
     * @param string $sort
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getTicketByNum($num, $sort)
    {
        $data = [
            'action' => 'GetTicket',
            'ticketnum' => $num,
            'repliessort' => $sort,
        ];

        return $this->submitRequest($data);
    }


    /**
     * Retrieves a specific ticket.
     *
     * @param $id
     * @param string $sort
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getTicketById($id, $sort)
    {
        $data = [
            'action' => 'GetTicket',
            'ticketid' => $id,
            'repliessort' => $sort,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Retrieves the available support departments.
     *
     * @param bool $ignore_dept_assignments
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getSupportDepartments($ignore_dept_assignments = true)
    {
        $data = [
            'action' => 'GetSupportDepartments',
            'ignore_dept_assignments' => $ignore_dept_assignments,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Update a ticket.
     *
     * @param $ticket_id
     * @param $dataToUpdate
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function updateTicket($ticket_id, $dataToUpdate)
    {
        $data = [
            'action' => 'UpdateTicket',
            'ticketid' => $ticket_id,
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
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
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
     * @param int $invoiceId
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getInvoice(int $invoiceId)
    {
        $data = [
            'action' => 'GetInvoice',
            'invoiceid' => $invoiceId,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Adds a transaction to the WHMCS backlog.
     *
     * @param string $paymentMethod
     * @param int $custom_id
     * @param int $invoice_id
     * @param string $transaction_id
     * @param string $description
     * @param float $amount
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function addTransaction(string $paymentMethod, int $custom_id, int $invoice_id, string $transaction_id, string $description, float $amount)
    {
        $data = [
            'action' => 'AddTransaction',
            'paymentmethod' => $paymentMethod,
            'userid'  => $custom_id,
            'invoiceid' => $invoice_id,
            'transid' => $transaction_id,
            'description' => $description,
            'amountin' => $amount,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Adds an order to the WHMCS backlog.
     *
     * @param string $paymentMethod
     * @param int $custom_id
     * @param array $product_ids
     * @param array $product_cycles
     * @param array $domain_names
     * @param array $domain_durations
     * @param array $domain_types
     * @param array $domain_epps
     * @param string $promoCode
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function addOrder(string $paymentMethod, int $custom_id, array $product_ids, array $product_cycles, array $domain_names, array $domain_durations, array $domain_types, array $domain_epps, string $promoCode = null)
    {
        $data = [
            'action' => 'AddOrder',
            'clientid' => $custom_id,
            'paymentmethod' => $paymentMethod,
            'pid' => $product_ids,
            'billingcycle' => $product_cycles,
        ];

        if ($promoCode) {
            $data['promocode'] = $promoCode;
        }

        if (count($domain_names) > 0) {
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
     * @param int $oldId
     * @param int $newId
     * @param string $newCycle
     * @param string $paymentMethod
     * @param string $promoCode
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function upgradeProduct(int $oldId, int $newId, string $newCycle, string $paymentMethod, string $promoCode = null)
    {
        $newCycle = ucfirst(strtolower($newCycle));

        // For some weird reason, semiannually is the exception to WHMCS's rule to specify the billing cycle with a capital letter first.
        if ($newCycle == "Semiannually") {
            $newCycle = "semiannually";
        }

        $data = [
            'action' => 'UpgradeProduct',
            'serviceid' => $oldId,
            'paymentmethod' => $paymentMethod,
            'type' => 'product',
            'newproductid' => $newId,
        ];

        if ($newCycle != '') {
            $data['newproductbillingcycle'] = $newCycle;
        }

        if ($promoCode) {
            $data['promocode'] = $promoCode;
        }

        return $this->submitRequest($data);
    }

    /**
     * Calculates the parameters of a product upgrade.
     *
     * @param int $oldId
     * @param int $newId
     * @param string $newCycle
     * @param string $paymentMethod
     * @param string|null $promoCode
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function calculateProductUpgrade(int $oldId, int $newId, string $newCycle, string $paymentMethod, string $promoCode = null)
    {
        $newCycle = ucfirst(strtolower($newCycle));

        // For some weird reason, semiannually is the exception to WHMCS's rule to specify the billing cycle with a capital letter first.
        if ($newCycle == "Semiannually") {
            $newCycle = "semiannually";
        }

        $data = [
            'action' => 'UpgradeProduct',
            'calconly' => true,
            'serviceid' => $oldId,
            'paymentmethod' => $paymentMethod,
            'type' => 'product',
            'newproductid' => $newId,
            'newproductbillingcycle' => $newCycle,
        ];

        if ($promoCode) {
            $data['promocode'] = $promoCode;
        }

        return $this->submitRequest($data);
    }

    /**
     * Cancels a subscription (or at least sends the request to cancel)
     *
     * @param int $serviceId
     * @param string $cancelType
     * @param string $reason
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function cancelSubscription(int $serviceId, string $cancelType, string $reason)
    {
        $data = [
            'action' => 'AddCancelRequest',
            'serviceid' => $serviceId,
            'type' => $cancelType,
            'reason' => $reason
        ];

        return $this->submitRequest($data);
    }

    /**
     * Fetches a clients domains.
     *
     * @param int $customer_id
     * @param int $limitStart
     * @param int $limitNum
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getClientsDomains(int $customer_id, int $limitStart, int $limitNum)
    {
        $data = [
            'action' => 'GetClientsDomains',
            'limitstart' => $limitStart,
            'limitnum' => $limitNum,
            'clientid' => $customer_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Renews a domain of a user.
     *
     * @param int $customer_id
     * @param string $domainname
     * @param int $years
     * @param string $paymentmethod
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
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
     *
     * @param int $currencyId
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getTLDPricing(int $currencyId)
    {
        $data = [
            'action'  => 'GetTLDPricing',
            'currencyid' => $currencyId,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Returns Whois information of a given domain.
     *
     * @param string $domain
     * @param bool $requireSuccess
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getDomainWhois(string $domain, bool $requireSuccess = false)
    {
        $data = [
            'action' => 'DomainWhois',
            'domain' => $domain,
        ];

        return $this->submitRequest($data, $requireSuccess);
    }

    /**
     * Returns an array of currency information.
     *
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getCurrencies()
    {
        $data = [
            'action' => 'GetCurrencies',
        ];

        return $this->submitRequest($data);
    }

    /**
     * Returns an array of payment method information.
     *
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getPaymentMethods()
    {
        $data = [
            'action' => 'GetPaymentMethods',
        ];

        return $this->submitRequest($data);
    }

    /**
     * Gets the nameservers of a domain.
     *
     * @param int $id
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getDomainNameservers(int $id)
    {
        $data = [
            'action' => 'DomainGetNameservers',
            'domainid' => $id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Updates the nameservers of a domain.
     * Ns1 and ns2 are required, ns3 through 5 are updated if supplied.
     *
     * @param int $id
     * @param string $ns1
     * @param string $ns2
     * @param string|null $ns3
     * @param string|null $ns4
     * @param string|null $ns5
     * @return array
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     * @throws GuzzleException
     */
    public function updateDomainNameServers(int $id, string $ns1, string $ns2, string $ns3 = null, string $ns4 = null, string $ns5 = null)
    {
        $data = [
            'action' => 'DomainUpdateNameservers',
            'domainid' => $id,
            'ns1' => $ns1,
            'ns2' => $ns2,
        ];

        if ($ns3) $data['ns3'] = $ns3;
        if ($ns4) $data['ns4'] = $ns4;
        if ($ns5) $data['ns5'] = $ns5;

        return $this->submitRequest($data);
    }

    /**
     * Requests an EPP code for a domain.
     * If you only get a result success, the EPP is probably sent to the client
     * directly.
     *
     * @param int $id
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function requestDomainEpp(int $id)
    {
        $data = [
            'action' => 'DomainRequestEpp',
            'domainid' => $id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Gets Whois info (More in detail, using the int)
     *
     * @param int $id
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getDomainWhoisInfo(int $id)
    {
        $data = [
            'action' => 'DomainGetWhoisInfo',
            'domainid' => $id
        ];

        return $this->submitRequest($data);
    }

    /**
     * Updates the domain whois info based on an xml string of a given domain.
     *
     * @param int $id
     * @param string $xml
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function updateDomainWhoisInfo(int $id, string $xml)
    {
        $data = [
            'action' => 'DomainUpdateWhoisInfo',
            'domainid' => $id,
            'xml' => $xml,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Get a single or all promotion codes
     *
     * @param string $code
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getPromotions(string $code = null)
    {
        $data = [
            'action' => 'GetPromotions',
        ];

        if ($code) {
            $data['code'] = $code;
        }

        return $this->submitRequest($data);
    }

    /**
     * Validate the login details for a client.
     *
     * @param string $email
     * @param string $password
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function validateLogin(string $email, string $password)
    {
        $data = [
            'action' => 'ValidateLogin',
            'email' => $email,
            'password2' => $password
        ];

        return $this->submitRequest($data, false);
    }

    /**
     * Get cancellations.
     *
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getCancelledPackages()
    {
        $data = [
            'action' => 'GetCancelledPackages',
            'limitstart' => 0,
            'limitnum' => 999999999999,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Get orders.
     *
     * @param int $customId
     * @param string|null $status
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getOrders(int $customId, string $status = null)
    {
        $data = [
            'action' => 'GetOrders',
            'limitstart' => 0,
            'limitnum' => 999999999999,
            'userid' => $customId,
        ];

        if ($status) {
            $data['status'] = $status;
        }

        return $this->submitRequest($data);
    }

    /**
     * Cancel an order.
     *
     * @param int $orderId
     * @param bool $cancelSub
     * @param bool $noEmail
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function cancelOrder(int $orderId, bool $cancelSub = false, bool $noEmail = true)
    {
        $data = [
            'action' => 'CancelOrder',
            'orderid' => $orderId,
            'cancelsub' => $cancelSub,
            'noemail' => $noEmail,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Get transactions.
     *
     * @param $search
     * @param $value
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getTransactions($search, $value)
    {
        if (! in_array($search, ['invoiceid', 'clientid', 'transid'])) {
            throw new WHMCSResultException('Cannot find transactions by ' + $search + ". Please use invoiceid, clientid or transid.");
        }

        $data = [
            'action' => 'GetTransactions',
            $search => $value
        ];

        return $this->submitRequest($data);
    }

    /**
     *
     * Get an order by Id.
     *
     * @param int $orderId
     * @return array
     * @throws GuzzleException
     * @throws WHMCSConnectionException
     * @throws WHMCSResultException
     */
    public function getOrderById(int $orderId)
    {
        $data = [
            'action' => 'GetOrders',
            'id' => $orderId
        ];

        return $this->submitRequest($data);
    }
}
