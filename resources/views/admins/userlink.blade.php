<x-app-layout>
    <style>
        .truncate-text {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/2.0.3/css/select.dataTables.css" rel="stylesheet" />
    <x-slot name="header">
        <div class="py-5">
            <section>
                <form action="{{ route('short.url') }}" method="POST">
                    @csrf
                    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Here are all your
                        links that have been shortened.</h5>
                    <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">We've
                        collected all the URLs you've ever used.</p>
                    {{-- @php
                        dd($links);
                    @endphp --}}
                    <div
                        class="w-full p-2 text-left bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="table-responsive">
                            <table id="example" class="display nowrap text-sm" style="width:100%;margin: 0 auto;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Username</th>
                                        <th>Original</th>
                                        <th>Shortened</th>
                                        <th>Views</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($links)
                                        @foreach ($links as $userlink)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $userlink->name }}</td>
                                                <td>
                                                    @foreach ($userlink->links as $link_data)
                                                        <input type="checkbox" class="link-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            name="linksToDelete[]" value="{{ $link_data->id }}" >
                                                        @if (strlen($link_data->original_url) > 50)
                                                            <span
                                                                class="truncate-text" for="">{{ substr($link_data->original_url, 0, 50) }}...</span>
                                                            <br>
                                                        @else
                                                            {{ $link_data->original_url }}
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($userlink->links as $link_data)
                                                        <a class="text-blue-400 hover:text-blue-900"
                                                            href="{{ url($link_data->short_url) }}" target="_blank">
                                                            {{ url($link_data->short_url) }}
                                                            <br>
                                                    @endforeach
                                                    </a>
                                                </td>
                                                <td>
                                                    @foreach ($userlink->links as $link_data)
                                                        {{ $link_data->visits }}<br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($userlink->links as $link_data)
                                                        {{ $link_data->created_at }}<br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="delete-button-appear" style="display: none;">
                                <button type="button"
                                    class="delete-link-btn text-xs text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2 text-center me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </x-slot>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.link-checkbox').change(function() {
                var anyChecked = $('.link-checkbox:checked').length > 0;
                if (anyChecked) {
                    $('.delete-button-appear').show();
                } else {
                    $('.delete-button-appear').hide();
                }
            });

            $('#example').DataTable({
                responsive: false,
                paging: true,
                pageLength: 10,
                scrollX: true,
                /* scrollY: , */
            });
        });

        //Delete select data
        $('.delete-link-btn').click(function() {
            var linkIds = [];
            $('.link-checkbox:checked').each(function() {
                linkIds.push($(this).val());
            });
            console.log(linkIds);
            if (linkIds.length > 0) {
                console.log(linkIds);
                $.ajax({
                    url: "{{ route('delete.links') }}",
                    type: "DELETE",
                    data: {
                        linkIds: linkIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Links deleted successfully.');
                        location.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('Unable to delete links.');
                    }
                });
            }

        });
    </script>

</x-app-layout>
