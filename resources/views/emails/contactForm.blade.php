@component('mail::message')
# New Contact Form Submission

You have received a new message from the contact form:

- **Name:** {{ $data['name'] }}
- **Company:** {{ $data['company'] }}
- **Phone No:** {{ $data['phone_type'] }} - {{ $data['phone'] }}
- **Address:** {{ $data['address'] }}
- **E-mail:** {{ $data['email'] }}
- **Message:** {{ $data['comment'] }}

@component('mail::button', ['url' => ''])
Reply to User
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
