@extends('layouts.client')

@section('title', 'insert rating')
@section('content')
    {{-- filter section --}}
    <div class="mb-10 text-center">
        <h1 class="capitalize font-bold text-3xl">insert rating</h1>
    </div>

    {{-- filter section --}}
    <div>
        <form action="#" method="post">
            <table>
                <tr>
                    <td class="p-2">book author</td>
                    <td class="p-2">:</td>
                    <td class="p-2">
                        <select name="author_id" class="border border-black" required>
                            <option value="author_id">author name</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="p-2">book name</td>
                    <td class="p-2">:</td>
                    <td class="p-2">
                        <select name="book_id" class="border border-black" required>
                            <option value="book_id">book name</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="p-2">rating</td>
                    <td class="p-2">:</td>
                    <td class="p-2">
                        <select name="scale" class="border border-black" required>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
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
@endsection
