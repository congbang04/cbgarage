<?php

namespace App\Http\Controllers;

use App\Services\BranchService;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $branchService, $serviceService;

    public function __construct(BranchService $branchService, ServiceService $serviceService)
    {
        $this->branchService = $branchService;
        $this->serviceService = $serviceService;
    }

    public function index()
    {
        $branches = $this->branchService->getActiveBranches();
        $services = $this->serviceService->getAll();

        return view('customer.booking', compact('branches', 'services'));
    }
}
