<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    function search_tickets(Request $request)
    {
        $search = $request->search;
        $priority = $request->priority;
        $status = $request->status;
        $dates = [];


        if ($request->date) {
            $dates[0] = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($request->date[0])) . '00:00:00'));
            $dates[1] = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($request->date[1])) . '00:00:00'));
        }


        $tickets = Ticket::with('user')
            ->where(function ($q) use ($dates) {
                $q->when($dates, function ($q) use ($dates) {
                    $q->whereBetween('created_at', $dates);
                });
            })
            ->where(function ($q) use ($priority) {
                $q->when($priority, function ($q) use ($priority) {
                    $q->where('priority', $priority);
                });
            })
            ->where(function ($q) use ($status) {
                $q->when($status, function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })
            ->where(function ($q) use ($search) {
                $q->when($search, function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%');
                    $q->orWhere('description', 'like', '%' . $search . '%');
                    $q->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                        $q->orWhere('email', 'like', '%' . $search . '%');
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $request->page);



        return $tickets;
    }
}
