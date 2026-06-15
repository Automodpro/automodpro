<?php
namespace App\Controllers;

class DashboardController extends BaseController
{
public function index()
{
if (!session('logueado')) {
return redirect()->to('/');
}
return view('dashboard');
}
}