<?php

namespace App\Http\Controllers;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AutherTicketsController extends Controller
{
    // since the created route is '/authers/{auther}/tickets' we could use '$auther_id' to grap the 
    // {auther} from the url.
    public function index($auther_id, TicketFilter $filters){
        return TicketResource::collection(Ticket::query()->where('user_id', $auther_id)->filter($filters)->paginate());
    }
}
