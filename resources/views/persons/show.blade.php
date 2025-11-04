@extends('layouts.app')

@section('title', 'Ver Persona')

@section('content')
    <x-header />

    <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detalles de la Persona</h1>
            <a href="{{ route('persons.edit', $person) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="text-sm text-gray-900">{{ $person->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $person->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="text-sm text-gray-900">{{ $person->phone ?? 'No especificado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $person->type === 'client' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $person->type === 'client' ? 'Cliente' : 'Agente' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                        <dd class="text-sm text-gray-900">{{ $person->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                        <dd class="text-sm text-gray-900">{{ $person->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Viajes Asociados</h3>
                @if($person->trips->count() > 0)
                    <ul class="space-y-2">
                        @foreach($person->trips as $trip)
                            <li class="text-sm">
                                <a href="{{ route('trips.show', $trip) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $trip->title }}
                                </a>
                                <span class="text-gray-500">({{ $trip->start_date ? $trip->start_date->format('d/m/Y') : 'Sin fecha' }})</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">No hay viajes asociados.</p>
                @endif
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('persons.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Volver al Listado
            </a>
            <form method="POST" action="{{ route('persons.destroy', $person) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta persona?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Eliminar Persona
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
