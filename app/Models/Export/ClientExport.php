<?php
namespace App\Models\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Client;

class ClientExport implements FromCollection
{
    private $external_id;
    public function __construct($external_id)
    {
        $this->external_id = $external_id;
    }

    public function collection()
    {
        $client = $this->getClient();
        $projects = $this->getProjetClient();
        $invoices[] = $this->getInvoiceClient();

        echo($client);
        echo($projects);
        echo(count($invoices));
        // for ($i=0; $i < count($invoices); $i++) {
        //     $invoices[$i]->invoice_lines;
        // }
    }

    public function getClient()
    {
        $client = Client::where('external_id', $this->external_id)->firstOrFail();

        return $client;
    }

    public function getProjetClient()
    {
        $client = $this->getClient();
        $projects = $client->projects()->with(['status'])->select(
            ['id', 'external_id', 'title', 'created_at', 'deadline', 'user_assigned_id', 'client_id', 'status_id']
        )->get();
    }

    public function getInvoiceClient()
    {
        $client = $this->getClient();
        $invoices = $client->invoices()->select(
            ['id', 'external_id', 'sent_at', 'status', 'invoice_number']
        );
    }

}