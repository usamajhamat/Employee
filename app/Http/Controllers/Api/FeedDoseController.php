<?php

namespace App\Http\Controllers\Api;
use App\Models\FeedDose;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class FeedDoseController extends Controller
{


    public function index(Request $request)
    {

        // $feedDoses = FeedDose::with('feed')->orderBy('created_at')
        //     ->paginate(1000);
        // $feedDoses = FeedDose::select('feed_id', DB::raw('SUM(weight) as total_weight'))
        //     ->groupBy('feed_id')
        //     ->orderBy('feed_id') ->paginate(1000);
        // return $feedDoses;

        $feedDoses = FeedDose::select('feed_doses.feed_id', 'feeds.item', DB::raw('SUM(feed_doses.weight) as total_weight'))
            ->join('feeds', 'feeds.id', '=', 'feed_doses.feed_id')
            ->groupBy('feed_doses.feed_id', 'feeds.item')
            ->orderBy('feed_doses.feed_id')
            ->paginate(1000);

        return $feedDoses;
    }
    public function store(Request $request)
    {

        // Validate the incoming request data
        $request->validate([
            'selectedFeed' => 'required|numeric|max:255',
            'feedWeigt' => 'required|numeric',
            'date' => 'required|date',
        ]);

        // Create a new FeedDose instance and save it to the database
        $feedDose = FeedDose::create([
            'feed_id' => $request->selectedFeed,
            'weight' => $request->feedWeigt,
            'date' => $request->date,
        ]);


        return [
            'message' => 'Feed Dose created successfully.'
        ];
    }

    public function totalFeedConsumed()
    {
        // $feedConsumed =  FeedDose::select('feed_id', 'SUM(weight) as total_weight')
        // ->groupBy('feed_id')
        // ->get();

        $feedConsumed = FeedDose::sum('weight')->groupBy('feed_id');




        return $feedConsumed;
    }
}
