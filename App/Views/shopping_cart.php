<div class="container mb-4">
    <?php if (count($_SESSION['shopping_cart'])): ?>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col" class="text-center">Quantity</th>
                            <th scope="col" class="text-right">Price, $</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?php echo $product->name; ?></td>
                            <td>
                                <input
                                    id="product_count_<?php echo $product->id; ?>"
                                    type="number"
                                    min="0"
                                    step="1"
                                    onchange="changeCount(this, <?php echo $product->id; ?>, <?php echo $product->price; ?>)"
                                    class="form-control product_count" type="text"
                                    value="<?php echo $_SESSION['shopping_cart'][$product->id]?>"
                                >
                            </td>
                            <td id="product_result_price_<?php echo $product->id; ?>" value="<?php echo ($product->price * $_SESSION['shopping_cart'][$product->id]); ?>" class="text-right product_result_price">
                                <?php echo number_format($product->price * $_SESSION['shopping_cart'][$product->id], 2); ?>
                            </td>
                            <td class="text-right">
                                <button onclick="removeProduct(this, <?php echo $product->id; ?>)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                        <tr>
                            <td></td>
                            <td></td>
                            <td>Shipping, $</td>
                            <td>
                                <form id="from_buy_products" method="post" action="/shopping-cart/pay">
                                    <select name="transport_type" class="form-control" id="shipping_types" onchange="changeTransportType(this)" required>
                                        <option price="0.00" value="">Not chosen</option>
                                        <?php foreach ($transportTypes as $type) : ?>
                                            <option price="<?php echo $type->price; ?>" value="<?php echo $type->id ?>"><?php echo $type->name . ', ' . $type->price . ' $' ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Sub-Total, $</td>
                            <td id="sub_total_price" value="<?php echo $subTotalPrice; ?>" class="text-right"><?php echo number_format($subTotalPrice, 2); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>Total, $</strong></td>
                            <td id="total_price" value="<?php echo $subTotalPrice; ?>" class="text-right"><strong><?php echo number_format($subTotalPrice, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col mb-2">
            <div class="row">
                <div class="col-sm-12  col-md-6">
                    <a href="/" class="btn btn-block btn-light">Continue Shopping</a>
                </div>
                <div class="col-sm-12 col-md-6 text-right">
                    <button id="form_button_pay" class="btn btn-lg btn-block btn-success text-uppercase" form="from_buy_products" type="submit">Pay</button>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
        <h1 class="text-center">Shopping cart is empty! </h1>
    <div class="row">
        <div class="col-md-12">
            <a href="/" class="btn btn-block btn-light">Continue Shopping</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    let my_cash = <?php echo $_SESSION['cash'] ?>;
    function changeCount(e, id, price)
    {
        if ($(e).val() < 0) {
            $(e).val(0);
        }
        $(document).ready(function ()
        {
            $.ajax({
                url: '/shopping-cart/change',
                type: 'POST',
                data: {
                    product_id: id,
                    product_count: $(e).val()
                },
                dataType: 'JSON',
                success: function (data)
                {
                    alert(data.message);

                    if (data.status === 'success')
                    {
                        let newPrice = ($(e).val() * price).toFixed(2);
                        $('#product_result_price_' + id).text(newPrice);
                        $('#product_result_price_' + id).attr('value', newPrice);

                        recountTotal();
                    }
                }
            });
        });
    }
    function changeTransportType(e)
    {
        let shipping_type = Number($(e).children("option:selected").attr('price'));
        let sub_total_price = Number($('#sub_total_price').attr('value'));
        let total_price = sub_total_price + shipping_type;
        $('#total_price').attr('value', Number(sub_total_price + shipping_type).toFixed(2));
        $('#total_price').text(Number(sub_total_price + shipping_type).toFixed(2));

        disableButtonPay(total_price, sub_total_price);
    }
    
    function recountTotal()
    {
        let sub_total_price = 0;
        $('.product_result_price').each(function () {
            sub_total_price += Number($(this).attr('value'));
        })

        $('#sub_total_price').attr('value', Number(sub_total_price).toFixed(2));
        $('#sub_total_price').text(Number(sub_total_price).toFixed(2));

        let shipping_type = Number($('#shipping_types').children("option:selected").attr('price'));
        let total_price = Number(sub_total_price + shipping_type).toFixed(2);
        $('#total_price').attr('value', Number(sub_total_price + shipping_type).toFixed(2));
        $('#total_price').text(Number(sub_total_price + shipping_type).toFixed(2));

        disableButtonPay(total_price, sub_total_price);
    }

    function disableButtonPay(total_price, sub_total_price) {
        if (sub_total_price === 0)
        {
            $('#form_button_pay').prop("disabled", true);
            $('#form_button_pay').text('Cart is empty!');
        }
        else if (my_cash < total_price)
        {
            $('#form_button_pay').prop("disabled", true);
            $('#form_button_pay').text('Not enough money');
        }
        else
        {
            $('#form_button_pay').prop("disabled", false);
            $('#form_button_pay').text('pay');
        }
    }
    
    function removeProduct(e, id)
    {
        $(document).ready(function ()
        {
            $.ajax({
                url: '/shopping-cart/remove',
                type: 'POST',
                data: {
                    product_id: id,
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status === 'success')
                    {
                        $(e).closest('tr').remove();

                        recountTotal();
                    }
                    else{}
                    alert(data.message);
                }
            });
        });
    }
</script>
