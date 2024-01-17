@extends('layouts.client')

@section('title', 'insert rating')
@section('content')
    {{-- filter section --}}
    <div class="mb-10 text-center">
        <h1 class="capitalize font-bold text-3xl">insert rating</h1>
    </div>

    {{-- filter section --}}
    <div>
        <form action="{{ route('books.store-rating') }}" method="post">
            @csrf
            <table>
                <tr>
                    <td class="p-2">book author</td>
                    <td class="p-2">:</td>
                    <td class="p-2">
                        <select id="author-id" name="author_id" class="border border-black" required>
                            <option value=""></option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="p-2">book name</td>
                    <td class="p-2">:</td>
                    <td class="p-2">
                        <select id="book-id" name="book_id" class="border border-black" required>
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
                        <button id="submit" type="submit" class="bg-blue-500 hover:bg-blue-900 text-white py-2 px-5 uppercase">submit</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script>
        function updateBookIdOptions (authorId) {
            // disabled submit button
            $('#submit').css('display', 'none');

            // do check
            $.ajax({
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                },
                url: `{{ route('books.get-books-by-author') }}?author_id=${authorId}`,
                success: function ({ data }) {
                    // append data to select option book-id
                    const books = data.books;
                    let contentAppend = books.map(function (book) {
                        return `<option value="${book.id}">${book.name}</option>`;
                    });
                    console.log(contentAppend);
                    $('#book-id').html(contentAppend);

                    // enabled submit button
                    $('#submit').css('display', 'block');
                },
                error: function (error) {
                    console.log(error);

                    // reset book id
                    $('#book-id').html('');

                    // enabled submit button
                    $('#submit').css('display', 'block');
                }
            });
        }

        $('#author-id').on('change', function () {
            // get current value of author-id
            const authorId = $('#author-id').val();
            if (authorId == '') {
                console.log('author not selected');
                $('#book-id').html('');
                return;
            }

            //*
            updateBookIdOptions(authorId);
        });
    </script>
@endsection
