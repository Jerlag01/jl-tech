<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    protected function validPayload(): array
    {
        return [
            'name' => 'Jan Peeters',
            'email' => 'jan@example.com',
            'phone' => '+32 470 00 00 00',
            'subject' => 'Offerte',
            'message' => 'Graag een offerte voor straatverlichting.',
            'privacy' => '1',
        ];
    }

    public function test_a_message_is_stored_and_emailed(): void
    {
        Mail::fake();
        Content::put('contact.notify_email', 'office@example.com');

        $this->post('/nl/contact', $this->validPayload())
            ->assertRedirect(url('/nl/contact').'#contact-form')
            ->assertSessionHas('contact_sent');

        $this->assertDatabaseHas('contact_messages', ['email' => 'jan@example.com', 'locale' => 'nl']);
        Mail::assertSent(ContactMessageReceived::class, fn (ContactMessageReceived $mail): bool => $mail->hasTo('office@example.com'));
    }

    public function test_the_notification_email_links_to_the_message_in_the_admin(): void
    {
        $message = ContactMessage::factory()->create(['subject' => 'Offerte straatverlichting']);

        $text = (new ContactMessageReceived($message))->render();

        $this->assertStringContainsString('Offerte straatverlichting', $text);
        $this->assertStringContainsString(url("/admin/contact-messages/{$message->getKey()}"), $text);
    }

    public function test_required_fields_and_privacy_consent_are_validated(): void
    {
        $this->post('/en/contact', ['name' => ''])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message', 'privacy']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_submissions_with_the_honeypot_filled_are_rejected(): void
    {
        $this->post('/en/contact', [...$this->validPayload(), 'website' => 'http://spam.example'])
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_validation_messages_are_in_dutch_on_the_dutch_site(): void
    {
        $this->post('/nl/contact', [...$this->validPayload(), 'email' => ''])
            ->assertSessionHasErrors(['email' => 'Het veld E-mail is verplicht.']);
    }
}
