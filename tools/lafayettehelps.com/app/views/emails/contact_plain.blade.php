====================================================
| lafayettehelps.com
====================================================

{{$user->getPublicName()}} has contacted you.

Their Message:

{{{$content}}}

----------------------------------------------------
This email was sent to you on behalf of {{$user->email}} by lafayettehelps.com. If you think this user is abusing this privilege, please report them here: {{route('report', array('id'=>$user->id, 'by'=>me()->id))}}.