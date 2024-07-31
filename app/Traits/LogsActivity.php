<?php
namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public function logActivity($activity, $details = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'details' => $details
        ]);
    }

    public function logError($message, $exception)
    {
        $this->logActivity($message, $exception->getMessage());
    }
}
