<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
     //function yang pertama kali dibuka
    public function index()
    {
        return view('home');//menampilkan tampilan home
    }
    public function features()
    {
        return view('features');//menampilkan tampilan home
    }
 

   
}