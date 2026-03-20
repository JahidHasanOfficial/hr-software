<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    protected $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    /**
     * Display a listing of the leaves.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Admins and HR can see all, but can also filter to see only their own
        if (($user->hasRole('Admin') || $user->hasPermissionTo('leave.approve')) && $request->type != 'personal') {
            $leaves = Leave::with(['user', 'leaveType'])->latest()->paginate(15);
        } else {
            // Employees (or Admin in personal mode) see only their own
            $leaves = Leave::where('user_id', $user->id)->with('leaveType')->latest()->paginate(15);
        }

        $balances = $this->leaveService->getUserBalance($user->id);

        return view('backend.pages.leaves.index', compact('leaves', 'balances'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        $leaveTypes = LeaveType::where('status', 1)->get();
        return view('backend.pages.leaves.create', compact('leaveTypes'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required_if:day_type,full|date|after_or_equal:start_date',
            'day_type' => 'required|in:full,first_half,second_half',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('leaves', 'public');
            }

            $this->leaveService->applyLeave($data);

            return redirect()->route('leaves.index')->with('success', 'Leave application submitted successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve a leave request.
     */
    public function approve($id)
    {
        try {
            $this->leaveService->approveLeave($id, Auth::id());
            return back()->with('success', 'Leave request approved.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:300']);
        
        try {
            $this->leaveService->rejectLeave($id, $request->rejection_reason);
            return back()->with('success', 'Leave request rejected.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show/Edit/Update/Destroy can be added if needed, but for now focusing on workflow.
     */
    public function balances()
    {
        $balances = \App\Models\LeaveBalance::with(['user', 'leaveType'])->latest()->paginate(20);
        return view('backend.pages.leaves.balances', compact('balances'));
    }
}
