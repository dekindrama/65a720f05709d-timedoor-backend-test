@extends('layouts.client')

@section('title', 'book list')
@section('content')
    {{-- filter section --}}
    <div class="mb-10">
        <form action="{{ route('books.index') }}" method="get">
            <table>
                <tr>
                    <td class="p-2">List shown</td>
                    <td class="p-2">:</td>
                    <td class="p-2">
                        <select name="list_shown" class="border border-black" required>
                            @for ($i = 10; $i <= 100; $i = $i + 10)
                                <option
                                    value="{{ $i }}"
                                    @if (request()->list_shown == $i)
                                        selected
                                    @endif
                                >{{ $i }}</option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="p-2">search</td>
                    <td class="p-2">:</td>
                    <td class="p-2">
                        <input type="text" name="search" class="border border-black" value="{{ request()->search }}">
                    </td>
                </tr>
                <tr>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-900 text-white py-2 px-5 uppercase">submit</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    {{-- datas section --}}
    <div>
        <div class="container mx-auto">
            <table class="w-full">
                <thead>
                    <tr class="capitalize">
                        <td class="border border-black p-5">no</td>
                        <td class="border border-black p-5">book name</td>
                        <td class="border border-black p-5">category name</td>
                        <td class="border border-black p-5">author name</td>
                        <td class="border border-black p-5">average rating</td>
                        <td class="border border-black p-5">voters</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse ($books as $book)
                        <tr>
                            <td class="border border-black p-5">{{ $i }}</td>
                            <td class="border border-black p-5">{{ $book->name }}</td>
                            <td class="border border-black p-5">{{ $book->category->name }}</td>
                            <td class="border border-black p-5">{{ $book->author->name }}</td>
                            <td class="border border-black p-5">{{ DecimalNumberFormatHelper::run($book->average_rating) }}</td>
                            <td class="border border-black p-5">{{ $book->voters }}</td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @empty
                        <tr>
                            <td class="border border-black p-5 text-center" colspan="6">book not found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
