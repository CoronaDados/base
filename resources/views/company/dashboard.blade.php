@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    @include('company.layouts.headers.cards')
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
