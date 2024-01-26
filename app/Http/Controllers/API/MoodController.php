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
        $userId = $request->input('userId');
        $perPage = 4;

        $moods = Mood::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

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
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMoods(Request $request)
    {
        try {
            $userId = $request->input('userId');

            $moods = Mood::where('user_id', $userId)->orderBy('created_at', 'desc')->get();


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
     * Get the user moods.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserMoods(Request $request)
    {
        $moods = Mood::where('user_id', $request->id)->get();
        $newmoods = $moods->sortByDesc('created_at');

        return response()->json([
            'msg' => 'User moods',
            'moods' => $newmoods,
            'success' => true
        ]);
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

                // Get the month in full text 
                $month = $lastMoodDate->format('F');

                // Get the year
                $year = $lastMoodDate->format('Y');

                $result[] = [
                    'lastMoodMonth' => $month,
                    'lastMoodYear' => $year,
                ];
            } else {
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
                    'lastMoodWeek' => $startOfWeek->toDateString(),
                    'endWeek' => $endOfWeek->toDateString(),
                    'lastMoodYear' => $year,
                ];
            } else {
                $result[] = [
                    'lastMoodWeek' => null,
                    'lastMoodYear' => null,
                ];
            }
        }

        usort($result, function ($a, $b) {
            return strtotime($b['lastMoodWeek']) - strtotime($a['lastMoodWeek']);
        });

        return response()->json(['weeks' => $result]);
    }

    /**
     * Get moods for a specific month.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMoodsByMonth(Request $request)
    {
        try {
            $userId = $request->userId;
            $carbonMonth = Carbon::parse($request->month);
            $year = $request->year;

            $startOfMonth = Carbon::createFromDate($year, $carbonMonth->month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $carbonMonth->month, 1)->endOfMonth();

            // Retrieve moods within the specified month and year
            $moods = Mood::where('user_id', $userId)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->get();

            return response()->json([
                'msg' => 'Moods for the specified month and year',
                'moods' => $moods,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }


    /**
     * Get moods for a specific week.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMoodsByWeek(Request $request)
    {
        try {
            $userId = $request->userId;
            $year = $request->year;

            $startOfWeek = $request->startWeek;
            $endOfWeek = $request->endWeek;

            // Retrieve moods within the specified month and year
            $moods = Mood::where('user_id', $userId)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->whereYear('created_at', $year)
                ->get();

            return response()->json([
                'msg' => 'Moods for the specified week and year',
                'moods' => $moods,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
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

    public function update(Request $request, string $id)
    {
        $mood = Mood::find($id);

        $mood->mood = $request->selectedItem;
        $mood->mood_body = $request->moreInfo;
        $mood->save();

        return response()->json([
            'msg' => "Mood posted",
            'mood' => $mood,
            'success' => true
        ]);
    }
}