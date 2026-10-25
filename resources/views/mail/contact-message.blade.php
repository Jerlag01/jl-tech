New message via the website contact form

Name:     {{ $contactMessage->name }}
Email:    {{ $contactMessage->email }}
Phone:    {{ $contactMessage->phone ?: '-' }}
Subject:  {{ $contactMessage->subject }}
Language: {{ $contactMessage->locale }}

{{ $contactMessage->message }}

--
View in admin: {{ \App\Filament\Resources\ContactMessages\ContactMessageResource::getUrl('view', ['record' => $contactMessage], panel: 'admin') }}
