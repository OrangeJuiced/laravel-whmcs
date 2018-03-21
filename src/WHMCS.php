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
     * Creates a new client
     *
     * @param array $data
     * @return array
     */
    public function createClient($data)
    {
        $data['action'] = 'addclient';

        return $this->submitRequest($data);
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
     * @param string $ticket_num
     * @param string $message
     * @param boolean $markdown
     * @return array
     */
    public function addTicketReply($client_id, $ticket_num, $message, $markdown = true)
    {
        $data = [
            'action'        =>  'AddTicketReply',
            'clientid'      =>  $client_id,
            'ticketid'      =>  $ticket_num,
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
    public function updateTicket($ticket_id, $status)
    {
        $data = [
            'action'     =>  'UpdateTicket',
            'ticketid'   =>  $ticket_id,
            'status'     =>  $status,
        ];

        return $this->submitRequest($data);
    }
}
