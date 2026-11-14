<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SeviaContactMessageController
{
    public function store(Request $request): RedirectResponse
    {
        ContactMessage::create($request->validateWithBag('contactMessage', [
            'name' => ['required', 'string', 'max:120'],
            'contact' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]));

        return redirect()
            ->to(route('contacts').'#write-us')
            ->with('contactMessageSent', true);
    }
}
