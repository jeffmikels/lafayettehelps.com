<?php $object = $notification['object']; ?>
====================================================
| lafayettehelps.com
====================================================

Your {{$noun}} {{$verb}}.
----------------------------------------------------

@if ($notification['type'] == 'plea')
Request Title:
{{$object->summary}}

View more details about your Request here:

{{$object->permalink()}}

@elseif ($notification['type'] == 'pledge')

You have made a pledge to help with the following need:
{{$object->plea->summary}}

View more details about your Request here:

{{$object->plea->permalink()}}

@endif

----------------------------------------------------
This email was sent to you by lafayettehelps.com. To update your email notification settings, visit this page: {{route('dashboard')}}.