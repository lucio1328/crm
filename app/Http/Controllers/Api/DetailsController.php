<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Invoice\InvoiceCalculator;
use \App\Enums\InvoiceStatus;
use App\Repositories\Money\MoneyConverter;

class DetailsController extends Controller
{
    public function clients(): JsonResponse
    {
        return response()->json(Client::all());
    }

    public function projets(): JsonResponse
    {
        $projects = Project::with(['assignee', 'status', 'client'])
            ->get()
            ->map(function ($project) {
                return [
                    'external_id' => $project->external_id,
                    'title' => $project->title,
                    'created_at' => $project->created_at ? $project->created_at->format('Y-m-d') : '',
                    'deadline' => $project->deadline ? $project->deadline->format('Y-m-d') : '',
                    'client' => $project->client ? $project->client->company_name : 'N/A',
                    'assignee' => $project->assignee ? $project->assignee->name : 'N/A',
                    'status' => [
                        'title' => $project->status->title,
                        'color' => $project->status->color
                    ]
                ];
            });

        return response()->json($projects);
    }

    public function taches(): JsonResponse
    {
        $tasks = Task::with(['user', 'status', 'project'])
            ->get()
            ->map(function ($task) {
                return [
                    'external_id' => $task->external_id,
                    'title' => $task->title,
                    'created_at' => $task->created_at ? $task->created_at->format('Y-m-d') : '',
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d') : '',
                    'project' => $task->project ? $task->project->title : 'N/A',
                    'assignee' => $task->assignee ? $task->assignee->name : 'N/A',
                    'status' => [
                        'title' => $task->status->title,
                        'color' => $task->status->color
                    ]
                ];
            });

        return response()->json($tasks);
    }

    public function offres(): JsonResponse
    {
        $offers = Offer::with(['client:id,company_name', 'invoiceLines'])
        ->get()
        ->map(function ($offer) {
            return [
                'id' => $offer->id,
                'external_id' => $offer->external_id,
                'client' => $offer->client ? $offer->client->company_name : 'N/A',
                'status' => $offer->status,
                'created_at' => $offer->created_at ? $offer->created_at : '',
                'price' => $offer->invoiceLines->sum(function ($line) {
                    return ($line->price * $line->quantity) / 100;
                })
            ];
        });
        return response()->json($offers);
    }

    public function factures(): JsonResponse
    {
        $invoices = Invoice::with(['invoiceLines', 'source', 'offer', 'client'])
            ->get()
            ->map(function ($invoice) {
                $invoiceCalculator = new InvoiceCalculator($invoice);
                $amountDue = $invoiceCalculator->getAmountDue();
                $total = $invoiceCalculator->getTotalPrice();

                return [
                    'client' => [
                        'company_name' => optional($invoice->client)->company_name ?? 'N/A',
                        'external_id' => optional($invoice->client)->external_id,
                    ],
                    'contact_info' => [
                        'name' => optional($invoice->client->primary_contact)->name ?? 'N/A',
                        'email' => optional($invoice->client->primary_contact)->email ?? 'N/A',
                    ],
                    'created_at' => optional($invoice->created_at)->format('d/m/Y'),
                    'sent_at' => optional($invoice->sent_at)->format('d/m/Y') ?? __('Not sent'),
                    'due_at' => optional($invoice->due_at)->format('d/m/Y'),
                    'amount_due' => app(MoneyConverter::class, ['money' => $amountDue])->format(),
                    'total' => app(MoneyConverter::class, ['money' => $total])->format(),
                    'status' => InvoiceStatus::fromStatus($invoice->status)->getDisplayValue(),
                    'invoice_number' => $invoice->invoice_number,
                    'source' => $invoice->source ? [
                        'reference' => class_basename(get_class($invoice->source)),
                        'url' => $invoice->source->getShowRoute(),
                    ] : null,
                    'offer' => $invoice->offer ? [
                        'external_id' => $invoice->offer->external_id
                    ] : null
                ];
            });

        return response()->json($invoices);
    }

    public function paiements(): JsonResponse
    {
        return response()->json(Payment::all());
    }

}
