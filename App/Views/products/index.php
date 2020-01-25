<div class="col-md-12">
    <h2>Products</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Transactions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
            <tr>
                <td><?php echo $product->id; ?></td>
                <td><?php echo $product->name; ?></td>
                <td>$ <?php echo $product->price; ?></td>
                <td>
                    <button class="btn btn-sm btn-info">
                        Add to cart
                        <i class="fa fa-shopping-cart"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
