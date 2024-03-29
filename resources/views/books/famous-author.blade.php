@extends('layouts.client')

@section('title', 'famous author')
@section('content')
    {{-- filter section --}}
    <div class="mb-10 text-center">
        <h1 class="capitalize font-bold text-3xl">top 10 most famous author</h1>
    </div>

    {{-- datas section --}}
    <div>
        <div class="container mx-auto">
            <table class="w-full">
                <thead>
                    <tr class="capitalize">
                        <td class="border border-black p-5">no</td>
                        <td class="border border-black p-5">author name</td>
                        <td class="border border-black p-5">voters</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse ($authors as $author)
                        <tr>
                            <td class="border border-black p-5">{{ $i }}</td>
                            <td class="border border-black p-5">{{ $author->name }}</td>
                            <td class="border border-black p-5">{{ $author->voters }}</td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @empty
                        <tr>
                            <td class="border border-black p-5 text-center" colspan="6">author not found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
