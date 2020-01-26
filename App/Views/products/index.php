<div class="col-md-12">
    <h2>Products</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
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
                    <input class="form-control" id="product_number_<?php echo $product->id; ?>" type="number" value="1">
                </td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="addToProductCart(<?php echo $product->id; ?>)">
                        Add to cart
                        <i class="fa fa-shopping-cart"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function addToProductCart(id) {
    $(document).ready(function () {
        $.ajax({
            url: '/shopping-cart/add',
            type: 'POST',
            data: {
                product_id: id,
                product_count: $('#product_number_' + id).val()
            },
            dataType: 'JSON',
            success: function (data) {
                if (data.status === 'success') {
                    $('#product_number_' + id).val(1);
                }

                alert(data.message);
            }
        });
    });
}
</script>
