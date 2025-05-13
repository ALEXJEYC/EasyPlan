@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">{{ $project->name }}</h1>
        <p class="text-gray-600 mb-4">{{ $project->description }}</p>
        <img src="{{ $project->getFirstMediaUrl('images') }}" alt="Imagen del Proyecto" class="w-full h-auto mb-4">
        <a href="{{ route('organizations.show', $project->organization) }}" class="text-blue-600 underline">Volver a la Organización</a>
    </div>
@endsection