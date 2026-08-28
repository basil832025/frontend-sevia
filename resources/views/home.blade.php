@extends('front.sevia::layouts.app')

@section('title', 'Sevia')

@section('content')
    @include('front.sevia::home.sections.hero')
    @include('front.sevia::home.sections.summer-bestsellers')
    @include('front.sevia::home.sections.collections')
    @include('front.sevia::home.sections.discounts')
    @include('front.sevia::home.sections.discovery-set')
    @include('front.sevia::home.sections.showroom')
    @include('front.sevia::home.sections.instagram')
@endsection
