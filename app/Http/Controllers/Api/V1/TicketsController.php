<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketsController extends ApiController
{
    /**
     * Display a listing of the resource.
     */

    // The "TicketFilter" object would be passed to "scoped filter" which would 
    // also get the Builder by dependecy injection and the filters would be applied
    // one by one through the foreach loop.
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try{
            $user = User::findOrFail($request->input('data.relationships.auther.data.id'));
        } catch(ModelNotFoundException $exception){
            return $this->ok('User not found', [
                'error' => 'the provided user id does not match'
            ]);
        }

        return new TicketResource($request->mappedAttributes());

        //return $this->success('created', $model, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            if($this->include('auther'))
            {
                return new TicketResource($ticket->load('user'));
            }
    
            return new TicketResource($ticket);
        } catch(ModelNotFoundException $exception){
            return $this->error('ticket not found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        // PATCH
        try{
            $ticket = Ticket::findOrFail($ticket_id);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        }catch(ModelNotFoundException $exception){
            return $this->error('ticket not found', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        // PUT
        try{
            $ticket = Ticket::findOrFail($ticket_id);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        }catch(ModelNotFoundException $exception){
            return $this->error('ticket not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();
            return $this->ok('deleted');
        } catch(ModelNotFoundException $exception){
            return $this->error('ticket not found', 404);
        }
    }
}
