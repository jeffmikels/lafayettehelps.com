<h2>lafayettehelps.com</h2>
<p><b>{{$user->name}}</b> has contacted you.</p>

<h3>Their Message:</h3>
{{$content}}

<hr />
<p style="font-size:.8em;">This email was sent to you on behalf of "{{$user->name}}" ( {{$user->email}} ) by <a href="{{route('home')}}">lafayettehelps.com</a>. If you think this user is abusing this privilege, please report them by forwarding this email to: <a href="mailto:webmaster@lafayettehelps.com">webmaster@lafayettehelps.com</a></p>