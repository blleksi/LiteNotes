<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ !$note->trashed() ? __('Notes') : __('Trash') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert-success>
                {{ session('success') }}
            </x-alert-success>
            <div class="flex">
                @if(request()->routeIs('notes.show'))
                    <p class="opacity-70 ml-8">
                        <strong>Created: </strong> {{ $note->created_at->diffForHumans() }}
                    </p>
                    <p class="opacity-70 ml-8">
                        <strong>Updated at: </strong> {{ $note->updated_at->diffForHumans() }}
                    </p>
                    <a href="{{ route('notes.edit', $note) }}" class="btn-link ml-auto">Edit Note</a>
                    <form action="{{ route('notes.destroy', $note) }}" method="POST">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger ml-4" onclick="return confirm('Are you sure moving to trash this note?')" >Move to Trash</button>
                    </form>
                @else
                    <p class="opacity-70 ml-8">
                        <strong>Deleted at: </strong> {{ $note->deleted_at->diffForHumans() }}
                    </p>
                    <form action="{{ route('trashed.update', $note) }}" method="POST" class="ml-auto">
                    @method('put')
                    @csrf
                    <button type="submit" class="btn-link ">Restore Note</button>
                    </form>
                    
                    <form action="{{ route('trashed.destroy', $note) }}" method="POST">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger ml-4" onclick="return confirm('Are you sure deleting about this note?')" >Delete Permanently</button>
                    </form>
                @endif
            </div>
            <div class="my-6 p-6 mt-3 bg-white border-b border-gray-200 shadow-sm sm:rounded-lg">
                <h2 class="font-bold text-4xl">
                    {{ $note->title }}
                </h2>
                <p class="mt-2 whitespace-pre-wrap">{{ $note->text }}</p>
                
            </div>
        </div>
    </div>
</x-app-layout>