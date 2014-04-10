<h2>lafayettehelps.com</h2>
<p><b>{{$user->getName()}}</b> has contacted you.</p>

<h3>Their Message:</h3>
{{$content}}

<hr />
<p style="font-size:.8em;">This email was sent to you on behalf of {{$user->email}} by <a href="{{route('home')}}">lafayettehelps.com</a>. If you think this user is abusing this privilege, please report them here: <a href="{{route('report', array('id'=>$user->id, 'by'=>me()->id))}}">REPORT ABUSE</a></p>