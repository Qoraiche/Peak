<x-mail::message>
# Magic Link Login

Click the button below to log in to your account:

<x-mail::button :url="$magicLink">
Log In
</x-mail::button>

If you did not request this link, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
