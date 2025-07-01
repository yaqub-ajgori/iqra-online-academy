<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request): Response
    {
        $payments = Payment::with(['student.user', 'course'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            // Note: This is a simple sum and does not convert currencies.
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
        ];

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(): Response
    {
        $students = Student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->full_name,
                'email' => $student->user->email ?? null,
            ];
        });

        $courses = Course::where('status', 'published')->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'price' => $course->price,
                'is_free' => $course->is_free,
            ];
        });

        return Inertia::render('Admin/Payments/Create', [
            'students' => $students,
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'payment_method' => ['required', Rule::in(['bkash', 'nagad', 'rocket', 'bank_transfer'])],
            'transaction_id' => ['nullable', 'string', 'unique:payments,transaction_id'],
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])],
            'sender_number' => ['nullable', 'string', 'max:20'],
            'receiver_number' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string'],
            'account_number' => ['nullable', 'string'],
            'branch_name' => ['nullable', 'string'],
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): Response
    {
        $payment->load(['student.user', 'course']);

        return Inertia::render('Admin/Payments/Show', [
            'payment' => $payment,
        ]);
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment): Response
    {
        $payment->load(['student.user', 'course']);

        $students = Student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->full_name,
                'email' => $student->user->email ?? null,
            ];
        });

        $courses = Course::where('status', 'published')->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'price' => $course->price,
                'is_free' => $course->is_free,
            ];
        });

        return Inertia::render('Admin/Payments/Edit', [
            'payment' => $payment,
            'students' => $students,
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'payment_method' => ['required', Rule::in(['bkash', 'nagad', 'rocket', 'bank_transfer'])],
            'transaction_id' => ['nullable', 'string', Rule::unique('payments', 'transaction_id')->ignore($payment->id)],
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])],
            'sender_number' => ['nullable', 'string', 'max:20'],
            'receiver_number' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string'],
            'account_number' => ['nullable', 'string'],
            'branch_name' => ['nullable', 'string'],
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        // Check if payment is used in any enrollment
        if ($payment->enrollments()->exists()) {
            return back()->withErrors(['payment' => 'Cannot delete payment that is linked to enrollments.']);
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Update payment status.
     */
    public function updateStatus(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])],
        ]);

        $payment->update($validated);

        return back()->with('success', 'Payment status updated successfully.');
    }

    /**
     * Approve payment.
     */
    public function approve(Payment $payment): RedirectResponse
    {
        $payment->update(['status' => 'completed']);

        return back()->with('success', 'Payment approved successfully.');
    }

    /**
     * Reject payment.
     */
    public function reject(Payment $payment): RedirectResponse
    {
        $payment->update(['status' => 'failed']);

        return back()->with('success', 'Payment rejected successfully.');
    }
}
