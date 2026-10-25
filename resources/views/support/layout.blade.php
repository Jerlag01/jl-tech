{{-- Support portal pages: the website layout plus the portal's own navigation bar. --}}
@extends('layouts.site')

@section('content')
    <div class="portal">
        <x-support.nav />
        @yield('portal')
    </div>
@endsection
