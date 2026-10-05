<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\CreateTicketAction;
use App\Actions\UpdateTicketAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListTicketsRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListTicketsRequest $request): AnonymousResourceCollection
    {
        $tickets = Ticket::query()
            ->filter($request->validated())
            ->latest()
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return TicketResource::collection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, CreateTicketAction $createTicket): JsonResponse
    {
        $ticket = $createTicket->handle($request->validated());

        return (new TicketResource($ticket))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): TicketResource
    {
        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket, UpdateTicketAction $updateTicket): TicketResource
    {
        return new TicketResource($updateTicket->handle($ticket, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket): Response
    {
        $ticket->delete();

        return response()->noContent();
    }
}
