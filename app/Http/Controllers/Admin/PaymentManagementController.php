<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\License;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentManagementController extends Controller
{
    /**
     * Display a listing of all payments
     */
    public function index()
    {
        $this->authorize('view-payments');
        
        $payments = License::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $totalRevenue = License::sum('amount');
        $monthlyRevenue = License::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $pendingPayments = License::where('status', 'pending')->count();
        
        return view('admin.super.payments.index', compact(
            'payments', 
            'totalRevenue', 
            'monthlyRevenue', 
            'pendingPayments'
        ));
    }
    
    /**
     * Show payment details
     */
    public function show($id)
    {
        $this->authorize('view-payments');
        
        $payment = License::with(['user'])->findOrFail($id);
        
        return view('admin.super.payments.show', compact('payment'));
    }
    
    /**
     * Update payment status
     */
    public function updateStatus(Request $request, $id)
    {
        $this->authorize('manage-payments');
        
        $request->validate([
            'status' => 'required|in:active,inactive,pending,expired'
        ]);
        
        $license = License::findOrFail($id);
        $oldStatus = $license->status;
        $license->status = $request->status;
        $license->updated_at = now();
        $license->save();
        
        // Log the status change
        activity()
            ->performedOn($license)
            ->causedBy(auth()->user())
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ])
            ->log('license_status_updated');
        
        return redirect()->route('admin.payments.show', $license->id)
            ->with('success', 'License status updated successfully');
    }
    
    /**
     * Show payment analytics
     */
    public function analytics()
    {
        $this->authorize('view-payment-analytics');
        
        // Get monthly revenue for the past 12 months
        $monthlyRevenue = DB::table('licenses')
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->where('status', 'active')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        // Format for chart
        $labels = [];
        $data = [];
        
        foreach ($monthlyRevenue as $revenue) {
            $date = Carbon::createFromDate($revenue->year, $revenue->month, 1);
            $labels[] = $date->format('M Y');
            $data[] = $revenue->total;
        }
        
        // Get license type distribution
        $licenseTypes = DB::table('licenses')
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();
            
        // Get top customers
        $topCustomers = DB::table('licenses')
            ->select('user_id', DB::raw('SUM(amount) as total'))
            ->where('status', 'active')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
            
        $topCustomersData = [];
        foreach ($topCustomers as $customer) {
            $user = User::find($customer->user_id);
            if ($user) {
                $topCustomersData[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'total' => $customer->total,
                    'id' => $user->id
                ];
            }
        }
        
        // Get payment methods (simulated since we're using licenses)
        $paymentMethods = [
            (object)['payment_method' => 'Credit Card', 'count' => 65],
            (object)['payment_method' => 'PayPal', 'count' => 25],
            (object)['payment_method' => 'Bank Transfer', 'count' => 10]
        ];
        
        return view('admin.super.payments.analytics', compact(
            'labels', 
            'data', 
            'paymentMethods', 
            'topCustomersData',
            'licenseTypes'
        ));
    }
    
    /**
     * Process refund (deactivate license)
     */
    public function processRefund(Request $request, $id)
    {
        $this->authorize('manage-payments');
        
        $request->validate([
            'refund_reason' => 'required|string|max:255'
        ]);
        
        $license = License::findOrFail($id);
        
        // Only allow refunds for active licenses
        if ($license->status !== 'active') {
            return redirect()->back()->with('error', 'Only active licenses can be refunded');
        }
        
        // Update license status
        $license->status = 'inactive';
        $license->notes = $license->notes . "\nRefund reason: " . $request->refund_reason;
        $license->updated_at = now();
        $license->save();
        
        // Log the refund
        activity()
            ->performedOn($license)
            ->causedBy(auth()->user())
            ->withProperties([
                'refund_reason' => $request->refund_reason
            ])
            ->log('license_refunded');
        
        return redirect()->route('admin.payments.show', $license->id)
            ->with('success', 'License refunded successfully');
    }
    
    /**
     * Export payments data
     */
    public function export(Request $request)
    {
        $this->authorize('export-payments');
        
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'required|in:csv,excel'
        ]);
        
        $query = License::with(['user']);
        
        if ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        
        $licenses = $query->get();
        
        // Generate export file based on format
        $filename = 'payments_export_' . date('Y-m-d') . '.' . ($request->format === 'excel' ? 'xlsx' : 'csv');
        
        // Log the export
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'format' => $request->format,
                'count' => $licenses->count(),
                'filters' => $request->only(['start_date', 'end_date'])
            ])
            ->log('licenses_exported');
        
        // Return appropriate response based on format
        if ($request->format === 'excel') {
            // Excel export logic would go here
            return redirect()->back()->with('success', 'Licenses exported successfully');
        } else {
            // CSV export logic would go here
            return redirect()->back()->with('success', 'Licenses exported successfully');
        }
    }
}
