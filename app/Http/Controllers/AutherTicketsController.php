<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AutherTicketsController extends ApiController
{
    // since the created route is '/authers/{auther}/tickets' we could use '$auther_id' to grap the 
    // {auther} from the url.
    public function index($auther_id, TicketFilter $filters){
        return TicketResource::collection(Ticket::query()->where('user_id', $auther_id)->filter($filters)->paginate());
    }

    public function store($auther_id, StoreTicketRequest $request){
        try{
            $user = User::findOrFail($auther_id);
        } catch(ModelNotFoundException $exception){
            return $this->ok('user not found', [
                'error' => 'the provided user id does not match'
            ]);
        }

        return Ticket::create($request->mappedAttributes());
    }

    public function update(UpdateTicketRequest $request, $auther_id, $ticket_id)
    {
        // PUT
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            
            if($ticket->user_id == $auther_id){
    
                $ticket->update($request->mappedAttributes());
    
                return new TicketResource($ticket);
            }
            return $this->error('ticket not found', 404);

        }catch(ModelNotFoundException $exception){
            return $this->error('ticket not found', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, $auther_id, $ticket_id)
    {
        // PUT
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            
            if($ticket->user_id == $auther_id){
    
                $ticket->update($request->mappedAttributes());
    
                return new TicketResource($ticket);
            }
            return $this->error('ticket not found', 404);

        }catch(ModelNotFoundException $exception){
            return $this->error('ticket not found', 404);
        }
    }
    
    public function destroy($auther_id, $ticket_id){
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            if($ticket->user_id == $auther_id){
                $ticket->delete();
                return $this->ok('deleted');
            }
            return $this->error('ticket not found', 404);
            
        }catch(ModelNotFoundException $exception){
            return $this->error('ticket not found', 404);
        }
    }
}
