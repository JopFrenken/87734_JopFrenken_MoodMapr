<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Mood;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MoodController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 4;

        $moods = Mood::orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'msg' => 'Paginated moods',
            'moods' => $moods->items(),  // Note: Use ->items() to get the actual data
            'success' => true,
            'current_page' => $moods->currentPage(),
            'last_page' => $moods->lastPage(),
            'total' => $moods->total(),
        ]);
    }

    /**
     * Display a listing of the resource with pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMoods(Request $request)
    {
        try {
            $moods = Mood::all();

            return response()->json([
                'msg' => 'All moods',
                'moods' => $moods,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
            ], 500); // Set a proper HTTP status code for internal server error
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $lastMoodDate = session('lastMoodDate_' . $request->userId);

        if ($lastMoodDate === now()->toDateString()) {
            // User has already posted mood today
            return response()->json(['message' => 'You can only post your mood once a day.'], 403);
        }

        $mood = new Mood;
        $mood->mood = $request->selectedItem;
        $mood->mood_body = $request->moreInfo;
        $mood->user_id = $request->userId;
        $mood->save();

        session()->put('lastMoodDate_' . $request->userId, now()->toDateString());
        session()->save();

        return response()->json([
            'msg' => "Mood posted",
            'mood' => $mood,
            'success' => true
        ]);
    }

    /**
     * Gets date session
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDate(Request $request)
    {
        $lastMoodDate = session()->get('lastMoodDate_' . $request->id);

        return response()->json(['lastMoodDate' => $lastMoodDate]);
    }

    /**
     * Gets months
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonth(Request $request)
    {
        $moods = $request->json();

        $result = [];

        // Loop over each mood in the array
        foreach ($moods as $mood) {
            // Check if the date is not empty before processing
            if (!empty($mood['created_at'])) {
                // Convert the date string to a Carbon instance
                $lastMoodDate = Carbon::parse($mood['created_at']);

                // Get the month in full text format (e.g., 'January')
                $month = $lastMoodDate->format('F');

                // Get the year
                $year = $lastMoodDate->format('Y');

                $result[] = [
                    'lastMoodMonth' => $month,
                    'lastMoodYear' => $year,
                ];
            } else {
                // If the date is empty, you may want to handle it differently or provide a default value
                $result[] = [
                    'lastMoodMonth' => null,
                    'lastMoodYear' => null,
                ];
            }
        }

        return response()->json(['months' => $result]);
    }

    /**
     * Gets weeks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeek(Request $request)
    {
        $moods = $request->json();

        $result = [];

        foreach ($moods as $mood) {
            if (!empty($mood['created_at'])) {
                $lastMoodDate = Carbon::parse($mood['created_at']);

                // Get the start and end dates of the week
                $startOfWeek = $lastMoodDate->copy()->startOfWeek();
                $endOfWeek = $lastMoodDate->copy()->endOfWeek();

                $weekRange = $startOfWeek->format('j F') . ' - ' . $endOfWeek->format('j F');

                $year = $lastMoodDate->format('Y');

                $result[] = [
                    'lastMoodWeek' => $weekRange,
                    'lastMoodYear' => $year,
                ];
            } else {
                $result[] = [
                    'lastMoodWeek' => null,
                    'lastMoodYear' => null,
                ];
            }
        }

        return response()->json(['weeks' => $result]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $mood = Mood::find($id);

        if ($mood) {
            return response()->json([
                'msg' => 'Mood found',
                'mood' => $mood,
                'success' => true
            ]);
        } else {
            return response()->json([
                'msg' => 'Mood not found',
                'success' => false
            ]);
        }
    }
}