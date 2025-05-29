@extends('layouts.header')

@section('title', 'Dashboard Page')

@section('content')
<div class="row">
   <h1>Selamat Datang Admin</h1>
    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Yakin Untuk Meninggalkan Aplikasi?')">
                      @csrf
                      <button class="dropdown-item text-danger" type="submit">Logout</button>
    </form>
</div>
@endsection