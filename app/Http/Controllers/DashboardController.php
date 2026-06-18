<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use App\Models\UploadLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Converts a single model record into an associative array
        // $user = User::find(1) -> firstname;
        // $userId = User::find(1) -> id;
        $userId = auth()->id();              // This works fine!
        $user = auth()->user()->firstname;   // Correct way to get the profile column

        // dd($user);
        // dd($userId);

        // $uploadLog = UploadLog::all();
        // dd($uploadLog);

        $today = Carbon::today()->toDateString();

        $uploadLog = UploadLog::where('user_id', $userId)
            ->whereDate('upload_date', today())
            ->first();

        // dd($uploadLog);

        $max = 5;
        if ($uploadLog) {
            $limit = $uploadLog -> total_upload;
            if($limit < 5){
                $status = "Still can upload";
            }
            else {    
              $status = "Limit reached for today"; 
            }
        } else {
            $limit = "0";
            $status = "Still can upload";
        }


        $document = Document::where('user_id', $userId) -> get();
        // $totaldoc = $document -> count();
        $totaldoc = Document::where('user_id', $userId)
                    -> where ('status', 'active')
                    ->count();

        // dd($totaldoc);

        



        
        return view('dashboard', compact('user', 'limit','status', 'totaldoc', 'max'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
