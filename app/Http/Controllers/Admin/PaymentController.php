<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request): Response
    {
        $query = Payment::with(['student.user', 'course'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(15);

        // Get summary stats
        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::pending()->count(),
            'completed' => Payment::completed()->count(),
            'failed' => Payment::failed()->count(),
            'total_amount' => Payment::completed()->sum('amount'),
        ];

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'stats' => $stats,
            'filters' => [
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'search' => $request->search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(): Response
    {
        $courses = Course::published()->get();
        $students = Student::with('user')->get();

        return Inertia::render('Admin/Payments/Create', [
            'courses' => $courses,
            'students' => $students,
        ]);
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:bkash,nagad,rocket,bank_transfer,cash',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = 'completed'; // Manual entry, mark as completed
        $validated['paid_at'] = now();
        $validated['currency'] = 'BDT';

        $payment = Payment::create($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
    }

    /**
     * Confirm a pending payment.
     */
    public function confirmPayment(Payment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment confirmed successfully.');
    }

    /**
     * Reject a pending payment.
     */
    public function rejectPayment(Payment $payment)
    {
        $payment->update([
            'status' => 'failed',
            'failed_at' => now(),
        ]);

        return back()->with('success', 'Payment rejected.');
    }
}
