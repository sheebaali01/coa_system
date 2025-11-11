<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Sku;
use App\Models\Batch;
use App\Models\Vial;
use Spatie\PdfToImage\Pdf;
class DashboardController extends Controller
{

    public function index()
    {
        // Basic Statistics
        $stats = $this->getDashboardStats();
        
        // Weekly Data for Charts
        $weeklyData = $this->getWeeklyScanData();
        
        // Top Products by Scan Volume
        // $topProducts = $this->getTopProducts();
        
        // Recent Batches
        // $recentBatches = $this->getRecentBatches();
        
        // Recent Scan Activity
        // $recentScans = $this->getRecentScans();

        return view('admin.dashboard', compact(
            'stats',
            'weeklyData', 
            // 'topProducts',
            // 'recentBatches',
            // 'recentScans'
        ));
    }

    private function getDashboardStats()
    {
        // Total SKUs
        $totalSkus = Sku::count();
        $lastMonthSkus = Sku::where('created_at', '>=', now()->subDays(30))->count();
        $previousMonthSkus = Sku::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();
        $skuGrowth = $previousMonthSkus > 0 ? round((($lastMonthSkus - $previousMonthSkus) / $previousMonthSkus) * 100) : 0;

        // Active Batches
        $activeBatches = Batch::where('status', 'active')->count();
        $lastMonthBatches = Batch::where('created_at', '>=', now()->subDays(30))->count();
        $previousMonthBatches = Batch::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();
        $batchGrowth = $previousMonthBatches > 0 ? round((($lastMonthBatches - $previousMonthBatches) / $previousMonthBatches) * 100) : 0;

        // Total QR Scans
        $totalScans = Vial::where('is_scanned', true)->count();
        $currentMonthScans = Vial::where('is_scanned', true)
            ->where('first_scan_at', '>=', now()->startOfMonth())
            ->count();
        $lastMonthScans = Vial::where('is_scanned', true)
            ->whereBetween('first_scan_at', [now()->subMonths(1)->startOfMonth(), now()->subMonths(1)->endOfMonth()])
            ->count();
        $scanGrowth = $lastMonthScans > 0 ? round((($currentMonthScans - $lastMonthScans) / $lastMonthScans) * 100) : 0;

        // COA Documents Issued (batches with COA documents)
        $coaIssued = Batch::whereNotNull('coa_document')->count();
        $currentWeekCOA = Batch::whereNotNull('coa_document')
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
        $lastWeekCOA = Batch::whereNotNull('coa_document')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
        $coaGrowth = $lastWeekCOA > 0 ? round((($currentWeekCOA - $lastWeekCOA) / $lastWeekCOA) * 100) : 0;

        return [
            'total_skus' => $totalSkus,
            'sku_growth' => $skuGrowth,
            'active_batches' => $activeBatches,
            'batch_growth' => $batchGrowth,
            'total_scans' => $totalScans,
            'scan_growth' => $scanGrowth,
            'coa_issued' => $coaIssued,
            'coa_growth' => $coaGrowth,
        ];
    }

    private function getWeeklyScanData()
    {
        $weeklyData = [];
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $scans = Vial::where('is_scanned', true)
                ->whereDate('first_scan_at', $date->format('Y-m-d'))
                ->count();
            
            $weeklyData[] = [
                'day' => $days[$date->dayOfWeek - 1] ?? $days[6], // Adjust for Monday start
                'scans' => $scans,
                'date' => $date->format('Y-m-d')
            ];
        }

        return $weeklyData;
    }
    
}