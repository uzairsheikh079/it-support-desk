<?php

namespace App\Http\Controllers;

use App\Actions\CreateTicketAction;
use App\Actions\UpdateTicketAction;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Requests\ListTicketsRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListTicketsRequest $request): View
    {
        $tickets = Ticket::query()
            ->filter($request->validated())
            ->latest()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Ticket::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('tickets.index', [
            'tickets' => $tickets,
            'statusCounts' => $statusCounts,
            'priorities' => TicketPriority::cases(),
            'statuses' => TicketStatus::cases(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tickets.create', [
            'priorities' => TicketPriority::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, CreateTicketAction $createTicket): RedirectResponse
    {
        $ticket = $createTicket->handle($request->validated());

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): View
    {
        return view('tickets.show', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit', [
            'ticket' => $ticket,
            'priorities' => TicketPriority::cases(),
            'statuses' => TicketStatus::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket, UpdateTicketAction $updateTicket): RedirectResponse
    {
        $updateTicket->handle($ticket, $request->validated());

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }
}
