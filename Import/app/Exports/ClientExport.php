<?php

namespace App\Exports;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    protected $id;

    function __construct($id) {
        $this->id = $id;
    }
    public function collection()
    {   
        $client=Client::find($this->id);
        $projects=$client->projects;
        $invoices=$client->invoices;
        return Collection::make([$client,$projects,$invoices]);
    }
}
