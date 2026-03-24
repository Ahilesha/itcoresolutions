<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    /**
     * Owner/Admin view: attendance listing with optional filters.
     */
    public function index(Request $request)
    {
        $employees = Employee::orderBy('name')->get();

        $query = Attendance::with('employee')
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        $attendances = $query->paginate(20)->withQueryString();

        return view('attendance.index', compact('attendances', 'employees'));
    }

    /**
     * Employee self punch screen.
     */
    public function punch()
    {
        $employee = auth()->user()?->employee;
        abort_if(!$employee, 403, 'No employee profile linked to your user.');

        $today = now()->toDateString();
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        return view('attendance.punch', compact('employee', 'todayAttendance'));
    }

    public function punchIn()
    {
        $employee = auth()->user()?->employee;
        abort_if(!$employee, 403, 'No employee profile linked to your user.');

        $today = now()->toDateString();

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $employee->id, 'date' => $today],
            ['punch_in' => now()]
        );

        if (!$attendance->punch_in) {
            $attendance->update(['punch_in' => now()]);
        }

        return redirect()->route('attendance.punch')->with('success', 'Punch in recorded.');
    }

    public function punchOut()
    {
        $employee = auth()->user()?->employee;
        abort_if(!$employee, 403, 'No employee profile linked to your user.');

        $today = now()->toDateString();
        $attendance = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();

        if (!$attendance || !$attendance->punch_in) {
            return redirect()->route('attendance.punch')->with('error', 'Please punch in first.');
        }

        if ($attendance->punch_out) {
            return redirect()->route('attendance.punch')->with('error', 'Punch out already recorded.');
        }

        $attendance->update(['punch_out' => now()]);

        return redirect()->route('attendance.punch')->with('success', 'Punch out recorded.');
    }
}
