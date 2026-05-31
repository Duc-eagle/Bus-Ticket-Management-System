<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::paginate(15);
        return view('admins.payment_methods.index', ['paymentMethods' => $paymentMethods]);
    }

    public function create()
    {
        return view('admins.payment_methods.create');
    }

    public function store(Request $request)
    {

        PaymentMethod::create($request->all());
        return Redirect::route('paymentMethods.index');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admins.payment_methods.edit', ['paymentMethod' => $paymentMethod]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {


        $paymentMethod->update($request->all());
        return Redirect::route('paymentMethods.index');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        
        DB::statement('ALTER TABLE payment_methods AUTO_INCREMENT = 1;');
        
        return Redirect::route('paymentMethods.index');
    }
}
