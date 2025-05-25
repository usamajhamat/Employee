<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Feed;
use App\Models\FeedDose;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $feeds = Feed::orderBy('date')
            ->paginate(Constants::$PAGE_LIMIT);
       return $feeds;
    }


    public function feedConsumption()
    {
        
        $animalCount = Animal::count();


        $feedDoses = FeedDose::select(
            'feed_doses.feed_id', 
            'feeds.item', 
            DB::raw("SUM(feed_doses.weight) * $animalCount as total_weight")
        )
        ->join('feeds', 'feeds.id', '=', 'feed_doses.feed_id')
        ->groupBy('feed_doses.feed_id', 'feeds.item')
        ->orderBy('feed_doses.feed_id')
        ->paginate(1000);
    
        return $feedDoses;
         
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return null;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'item' => 'required',
            'quntity' => 'required',
            'price' => 'required',
        ]);

        Feed::create([
            'date' => $request->date,
            'item' => $request->item,
            'quntity' => $request->quntity,
            'price' => $request->price,

        ]);

        return [
            'message' => 'Feed created successfully.'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $id;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $feed = Feed::where('id',$id)->orderBy('date');
        $request->validate([
            'date' => 'required',
            'item' => 'required',
            'quntity' => 'required',
            'price' => 'required',
        ]);

        $feed->update([
            'date' => $request->date,
            'item' => $request->item,
            'quntity' => $request->quntity,
            'price' => $request->price,

        ]);

        return [
            'message' => 'Feed updated successfully.'
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $feeds = Feed::whereIn('id', $request->ids)->get();

        if (!$feeds) {
            return [
                'message' => 'Feed not found.'
            ];
        }

        foreach ($feeds as $feed) {
            $feed->delete();
        }
    }
}
