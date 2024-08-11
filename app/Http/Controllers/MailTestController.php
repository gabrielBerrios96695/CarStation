<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class MailTestController extends Controller
{
    public function sendTestEmail()
    {
        Mail::raw('Este es un correo de prueba', function ($message) {
            $message->to('tuemail@example.com')
                    ->subject('Correo de Prueba');
        });

        return 'Correo enviado';
    }
}
