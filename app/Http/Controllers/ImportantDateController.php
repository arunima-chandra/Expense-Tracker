<?php

namespace App\Http\Controllers;

use App\Models\ImportantDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportantDateController extends Controller
{
    public function index(Request $request)
    {
        $dates = $request->user()->importantDates()
            ->orderBy('is_recurring', 'desc')
            ->orderBy('due_day', 'asc')
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json($dates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:bill,rent,emi,salary,other',
            'amount' => 'nullable|numeric|min:0.01',
            'due_day' => 'nullable|integer|min:1|max:31',
            'due_date' => 'nullable|date',
            'reminder_method' => 'required|in:notification,alarm,both',
            'is_recurring' => 'required|boolean',
        ]);

        // Validate that recurring items have due_day, and one-time items have due_date
        if ($validated['is_recurring'] && empty($validated['due_day'])) {
            return response()->json([
                'message' => 'The due day is required for recurring monthly reminders (1–31).'
            ], 422);
        }

        if (!$validated['is_recurring'] && empty($validated['due_date'])) {
            return response()->json([
                'message' => 'The due date is required for one-time reminders.'
            ], 422);
        }

        $importantDate = $request->user()->importantDates()->create($validated);

        Log::info('Important date created', [
            'important_date_id' => $importantDate->id,
            'user_id' => $request->user()->id,
            'title' => $importantDate->title,
            'type' => $importantDate->type,
            'reminder_method' => $importantDate->reminder_method,
        ]);

        return response()->json($importantDate, 201);
    }

    public function show(Request $request, ImportantDate $importantDate)
    {
        if ($importantDate->user_id !== $request->user()->id) {
            Log::warning('Unauthorized important date access attempt', [
                'user_id' => $request->user()->id,
                'important_date_id' => $importantDate->id,
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($importantDate);
    }

    public function update(Request $request, ImportantDate $importantDate)
    {
        if ($importantDate->user_id !== $request->user()->id) {
            Log::warning('Unauthorized important date update attempt', [
                'user_id' => $request->user()->id,
                'important_date_id' => $importantDate->id,
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:bill,rent,emi,salary,other',
            'amount' => 'nullable|numeric|min:0.01',
            'due_day' => 'nullable|integer|min:1|max:31',
            'due_date' => 'nullable|date',
            'reminder_method' => 'sometimes|in:notification,alarm,both',
            'is_recurring' => 'sometimes|boolean',
        ]);

        $importantDate->update($validated);

        Log::info('Important date updated', [
            'important_date_id' => $importantDate->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($importantDate);
    }

    public function destroy(Request $request, ImportantDate $importantDate)
    {
        if ($importantDate->user_id !== $request->user()->id) {
            Log::warning('Unauthorized important date delete attempt', [
                'user_id' => $request->user()->id,
                'important_date_id' => $importantDate->id,
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $importantDate->delete();

        Log::info('Important date deleted', [
            'important_date_id' => $importantDate->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(null, 204);
    }

    /**
     * Endpoint for frontend to poll alarms due today.
     */
    public function alarmsToday(Request $request)
    {
        $dueAlarms = $request->user()->importantDates()
            ->forAlarm()
            ->dueToday()
            ->get();

        Log::info('Fetched alarms due today', [
            'user_id' => $request->user()->id,
            'count' => $dueAlarms->count(),
        ]);

        return response()->json($dueAlarms);
    }
}
