<h1>Product List</h1>


<table class="table">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>brandname</th>
            <th>Color</th>
            <th>tag</th>
            <th>user</th>
            <th>size</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td><img style="height:80px;width:80px;"
                        src="{{ Config('constants.catimage_IMAGE_ROOT_PATH') }}/{{ $product->image }}"></td>
                <td> {{ $product->description }}</td>
                <td> {{ $product->brandname }}</td>

                <td> {{ $product->color_type }}</td>
                <td> {{ $product->tag }}</td>
                <td> {{ $product->user }}</td>
                <td> {{ $product->size }}</td>
            </tr>
        @endforeach
        </tr>

    </tbody>
</table>
