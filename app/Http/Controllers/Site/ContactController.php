<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request): RedirectResponse
    {
        $message = ContactMessage::query()->create([
            ...$request->safe()->only(['name', 'email', 'phone', 'subject', 'message']),
            'locale' => app()->getLocale(),
            'ip' => $request->ip(),
        ]);

        $recipient = content('contact.notify_email');

        if (filled($recipient)) {
            try {
                Mail::to($recipient)->send(new ContactMessageReceived($message));
            } catch (Throwable $exception) {
                // The message is stored either way; a mail outage must not lose it or break the form.
                Log::warning('Contact form notification failed: '.$exception->getMessage());
            }
        }

        return redirect(page_url('contact').'#contact-form')->with('contact_sent', true);
    }
}
