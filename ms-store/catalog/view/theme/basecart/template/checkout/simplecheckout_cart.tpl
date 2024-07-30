    <div class="row">
        <div class="col-md-12">
            <div class="simplecheckout-block" id="simplecheckout_cart" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
                 <?php if ($display_header) { ?>
                 <div class="checkout-heading"><?php echo $text_cart ?></div>
                <?php } ?>
                <?php if ($attention) { ?>
                <div class="simplecheckout-warning-block"><?php echo $attention; ?></div>
                <?php } ?>
                <?php if ($error_warning) { ?>
                <div class="table-responsive"><?php echo $error_warning; ?></div>
                <?php } ?>
                <table class="table table-bordered table-striped table-hover">
                    <!-- <colgroup>
                         <col class="name">
                         <col class="model">
                         <col class="quantity">
                         <col class="price">
                         <col class="total">
                         <col class="remove">
                     </colgroup>-->
                    <thead>
                        <tr>
                            <th class="text-left"><?php echo $column_model; ?></th>
                            <td class="text-left"><strong><?php echo $column_article; ?></strong></td>
                            <th class="text-left"><?php echo $column_name; ?></th>
                            <th class="text-right"><?php echo $column_quantity; ?></th>
                            <th class="text-right"><?php echo $column_price; ?></th>
                            <th class="text-right"><?php echo $column_total; ?></th>
                            <th class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                        <?php if (!empty($product['recurring'])) { ?>
                        <tr>
                            <td class="simplecheckout-recurring-product" style="border:none;"><img src="<?php echo $additional_path ?>catalog/view/theme/default/image/reorder.png" alt="" title="" style="float:left;" />
                                <span style="float:left;line-height:18px; margin-left:10px;">
                                    <strong><?php echo $text_recurring_item ?></strong>
                                    <?php echo $product['profile_description'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="model"><?php echo $product['model']; ?></td>
                            <td class="text-left"><?php if (isset($product['article'])){ echo $product['article'];} ?></td> <!-- Код детали -->
                            <td class="name">
                                <?php if ($product['thumb']) { ?>
                                <div class="image">
                                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                                </div>
                                <?php } ?>
                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                <?php if (!$product['stock'] && ($config_stock_warning || !$config_stock_checkout)) { ?>
                                <span class="product-warning">***</span>
                                <?php } ?>
                                <!--<div class="options">
                                     <?php foreach ($product['option'] as $option) { ?>
                                     &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                                     <?php } ?>
                                     <?php if (!empty($product['recurring'])) { ?>
                                     - <small><?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
                                     <?php } ?>
                                 </div>-->
                                <div class="options">
                                    <small><?php echo $product['comment']; ?></small>

                                </div>
                                <?php if ($product['reward']) { ?>
                                <small><?php echo $product['reward']; ?></small>
                                <?php } ?>
                            </td>

                            <td class="text-center quantity_order">
                                <div class="input-group btn-block" style="max-width: 200px;">
                                    <!--<input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control cb_edit" />-->
                                  <div class="input-group spinner" data-trigger="spinner">
                                        <input type="text" data-onchange="changeProductQuantity" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" class="form-control text-center f-quantity" data-rule="quantity">
                                        <div class="input-group-addon">
                                            <a  name="spin-up[<?php echo $product['cart_id']; ?>]" class="spin-up" ><i data-onclick="increaseProductQuantity" class="fa fa-caret-up"></i></a>
                                            <a  name="spin-down[<?php echo $product['cart_id']; ?>]" class="spin-down" ><i data-onclick="decreaseProductQuantity" class="fa fa-caret-down"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="price"><?php echo $product['price']; ?></td>
                            <td class="total"><?php echo $product['total']; ?></td>
                            <td class="text-center remove">
                                <span class="group-btn">
                                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" data-onclick="removeProduct" data-product-key="<?php echo !empty($product['cart_id']) ? $product['cart_id'] : $product['key']; ?>"><i data-onclick="removeProduct" class="fa fa-times-circle"></i></button>               
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php foreach ($vouchers as $voucher_info) { ?>
                        <tr>
                            <td class="image"></td>
                            <td class="name"><?php echo $voucher_info['description']; ?></td>
                            <td class="model"></td>
                            <td class="quantity">1</td>
                            <td class="price"><?php echo $voucher_info['amount']; ?></td>
                            <td class="total"><?php echo $voucher_info['amount']; ?></td>
                            <td class="remove">
                                <img data-onclick="removeGift" data-gift-key="<?php echo $voucher_info['key']; ?>" src="<?php echo $additional_path ?>catalog/view/image/close.png"  />
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <?php foreach ($totals as $total) { ?>
                        <tr>
                            <td colspan="6" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                            <td class="text-right"><?php echo $total['text']; ?></td>
                        </tr>
                        <?php } ?>
                    </tfoot>
                </table>
                <!-- вывод суммы 
                    <?php foreach ($totals as $total) { ?>
                    <div class="simplecheckout-cart-total" id="total_<?php echo $total['code']; ?>">
                        <span><b><?php echo $total['title']; ?>:</b></span>
                        <span class="simplecheckout-cart-total-value"><?php echo $total['text']; ?></span>
                        <span class="simplecheckout-cart-total-remove">
                            <?php if ($total['code'] == 'coupon') { ?>
                            <img data-onclick="removeCoupon" src="<?php echo $additional_path ?>catalog/view/image/close.png" />
                            <?php } ?>
                            <?php if ($total['code'] == 'voucher') { ?>
                            <img data-onclick="removeVoucher" src="<?php echo $additional_path ?>catalog/view/image/close.png" />
                            <?php } ?>
                            <?php if ($total['code'] == 'reward') { ?>
                            <img data-onclick="removeReward" src="<?php echo $additional_path ?>catalog/view/image/close.png" />
                            <?php } ?>
                        </span>
                    </div>
                    <?php } ?>-->
                <?php if (isset($modules['coupon'])) { ?>
                <div class="simplecheckout-cart-total">
                    <span class="inputs"><?php echo $entry_coupon; ?>&nbsp;<input type="text" data-onchange="reloadAll" name="coupon" value="<?php echo $coupon; ?>" /></span>
                </div>
                <?php } ?>
                <?php if (isset($modules['reward']) && $points > 0) { ?>
                <div class="simplecheckout-cart-total">
                    <span class="inputs"><?php echo $entry_reward; ?>&nbsp;<input type="text" name="reward" data-onchange="reloadAll" value="<?php echo $reward; ?>" /></span>
                </div>
                <?php } ?>
                <?php if (isset($modules['voucher'])) { ?>
                <div class="simplecheckout-cart-total">
                    <span class="inputs"><?php echo $entry_voucher; ?>&nbsp;<input type="text" name="voucher" data-onchange="reloadAll" value="<?php echo $voucher; ?>" /></span>
                </div>
                <?php } ?>
                <?php if (isset($modules['coupon']) || (isset($modules['reward']) && $points > 0) || isset($modules['voucher'])) { ?>
                <div class="simplecheckout-cart-total simplecheckout-cart-buttons">
                    <span class="inputs buttons"><a id="simplecheckout_button_cart" data-onclick="reloadAll" class="button btn-primary button_oc btn"><span><?php echo $button_update; ?></span></a></span>
                </div>
                <?php } ?>
                <input type="hidden" name="remove" value="" id="simplecheckout_remove">
                <div style="display:none;" id="simplecheckout_cart_total"><?php echo $cart_total ?></div>
                <?php if ($display_weight) { ?>
                <div style="display:none;" id="simplecheckout_cart_weight"><?php echo $weight ?></div>
                <?php } ?>
                <?php if (!$display_model) { ?>
                <style>
                    .simplecheckout-cart col.model,
                    .simplecheckout-cart th.model,
                    .simplecheckout-cart td.model {
                        display: none;
                    }
                </style>
                <?php } ?>
            </div>
        </div>
    </div>
