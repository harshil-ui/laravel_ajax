<x-app-layout>

    <?php $srno = 1; ?>
    <form id="deleteForm">
        <div class="my-4 cointainer">
            <a href="{{ route('products.create') }}" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                Create Product
            </a>
            <button type='submit' id="deleteSelected" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                Delete Selected
            </button>
        </div>

        @csrf
        @method('DELETE')
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <div class="flex items-center">
                                id
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <div class="flex items-center">
                                name
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <div class="flex items-center">
                                Price
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <div class="flex items-center">
                                upc
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <div class="flex items-center">
                                status
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <div class="flex items-center">
                                image
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $product->id }}">
                        </td>
                        <td class="px-6 py-4">
                            {{$srno++}}
                        </td>
                        <td class="px-6 py-4">
                            {{$product->name}}
                        </td>
                        <td class="px-6 py-4">
                            {{$product->price}}
                        </td>
                        <td class="px-6 py-4">
                            {{$product->upc}}
                        </td>
                        <td class="px-6 py-4">
                            {{$product->status}}
                        </td>
                        <td class="px-6 py-4">
                            @if ($product->image)
                            <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" width="70">
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <ul>
                                <li>
                                    <a href="{{ route('products.edit', $product->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </li>
                                <li>
                                    <a href="#" class="deleteRecord" data-id="{{ $product->id }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">delete</a>
                                </li>
                            </ul>

                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>


    <script type="text/javascript">
        $(document).ready(function() {
            // Listen for the form submission event
            $('#deleteForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submit behavior

                // Check if at least one checkbox is checked
                if ($('input[name="ids[]"]:checked').length === 0) {
                    alert('Please select at least one record to delete.');
                    return;
                }

                // Get the form data
                var formData = $(this).serialize();

                let ids = $('input[name="ids[]"]:checked').map(function() {
                    return $(this).val();
                }).get();

                // Make the AJAX request to delete the selected records
                $.ajax({
                    url: '{{ route("deletemultipleproducts") }}',
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "ids": ids
                    },
                    success: function(response) {
                        // console.log(response);
                        // Reload the page to show the updated record list
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Listen for the check all checkbox click event
            $('#checkAll').on('click', function() {
                // Toggle all the checkboxes to match the check all checkbox
                $('input[name="ids[]"]').prop('checked', $(this).prop('checked'));
            });

            // Listen for the individual checkbox click events
            $('input[name="ids[]"]').on('click', function() {
                // Uncheck the check all checkbox if any of the individual checkboxes are unchecked
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }
            });

            // Listen for the delete link click events
            $('.deleteRecord').on('click', function(event) {
                event.preventDefault(); // Prevent the default link click behavior

                // Get the record ID from the data attribute
                var recordId = $(this).data('id');

                // Make the AJAX request to delete the record
                $.ajax({
                    url: '{{ route("deleteproduct", ":id") }}'.replace(':id', recordId),
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": recordId
                    },
                    success: function(response) {
                        // Reload the page to show the updated record list
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>

</x-app-layout>