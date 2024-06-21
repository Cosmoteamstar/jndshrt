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
    <x-slot name="header">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-7">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <section>
                            <h1 class="text-4xl text-blue-800">Short your link</h1>
                            @if (session('success_url'))
                                {!! session('success_url') !!}
                                <button data-copy-to-clipboard-target="npm-install-copy-text" class="absolute end-2.5 top-1/2 -translate-y-1/2 text-gray-900 dark:text-gray-400 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 rounded-lg py-2 px-2.5 inline-flex items-center justify-center bg-white border-gray-200 border">
                                    <span id="default-message" class="inline-flex items-center">
                                        <svg class="w-3 h-3 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                            <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                                        </svg>
                                        <span class="text-xs font-semibold">Copy</span>
                                    </span>
                                    <span id="success-message" class="hidden inline-flex items-center">
                                        <svg class="w-3 h-3 text-blue-700 dark:text-blue-500 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                        </svg>
                                        <span class="text-xs font-semibold text-blue-700 dark:text-blue-500">Copied</span>   
                                    </span>
                                </button>
                            @endif
                            <div class="mt-2">

                            </div>
                            <form action="{{ route('short.url') }}" method="POST">
                                @csrf
                                <input class="border border-gray-300 rounded-lg" type="url" name="original_url"
                                    id="original_url" placeholder="Enter url here">
                                <button
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                    type="submit">Confirm</button>
                                @error('original_url')
                                    <span class="text-red-400 ml-2">{{ $message }}</span>
                                @enderror
                                <div
                                    class="w-full p-4 text-left bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                                    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Here are all your
                                        links that have been shortened.</h5>
                                    <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">We've
                                        collected all the URLs you've ever used.</p>
                                        <div class="table-responsive">
                                            <table id="example" class="display nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Original</th>
                                                        <th>Shortened</th>
                                                        <th>Views</th>
                                                        <th>Created Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($data)
                                                        @foreach ($data as $userlink)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>
                                                                @if(strlen($userlink->original_url) > 50)
                                                                    <span class="truncate-text">{{ substr($userlink->original_url, 0, 50) }}...</span>
                                                                @else
                                                                    {{ $userlink->original_url }}
                                                                @endif
                                                            </td>
                                                            <td><a class="text-blue-400 hover:text-blue-900" href="{{ url($userlink->short_url) }}" target="_blank">
                                                                {{ url($userlink->short_url) }}
                                                            </a></td>
                                                            <td>{{$userlink->visits}}</td>
                                                            <td>{{$userlink->created_at}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Load DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- Load DataTables Responsive plugin -->
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: false,
                paging: true,
                pageLength: 10,
                scrollX: true,
            });
        });
    </script>

</x-app-layout>
