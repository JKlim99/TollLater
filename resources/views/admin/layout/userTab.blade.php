<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link @if($tab_active=='profile') active @endif" href="/admin/user/{{$user->id}}">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($tab_active=='card') active @endif" href="/admin/ucard/{{$user->id}}">Card</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($tab_active=='billing') active @endif" href="/admin/ubill/{{$user->id}}">Billing</a>
    </li>
</ul>
<br/>