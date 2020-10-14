<?php

session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['user'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

include "header.php"; ?>
<html>

<head>
    <title>Payment Gateways & Payment Methods Settings | <?= $settings['app_name'] ?> - Dashboard</title>
</head>
</body>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">

        <h2>Payment Gateways & Methods Settings</h2>
        <?php
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
        $data = $fn->get_settings('payment_methods', true);
        ?>
        <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
        </ol>
        <hr />
    </section>
    <?php if ($permissions['settings']['read'] == 1) { ?>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Payment Methods Settings</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <div class="col-md-4">
                                <form method="post" id="payment_method_settings_form">
                                    <input type="hidden" id="payment_method_settings" name="payment_method_settings" required="" value="1" aria-required="true">
                                    <h5>Paypal Payments </h5>
                                    <hr>
                                    <div class="form-group">
                                        <label for="paypal_payment_method">Paypal Payments <small>[ Enable / Disable ] </small></label><br>
                                        <input type="checkbox" id="paypal_payment_method_btn" class="js-switch" <?= (!empty($data['paypal_payment_method']) && $data['paypal_payment_method'] == 1) ? 'checked' : ''; ?>>
                                        <input type="hidden" id="paypal_payment_method" name="paypal_payment_method" value="<?= (isset($data['paypal_payment_method']) && !empty($data['paypal_payment_method'])) ? $data['paypal_payment_method'] : 0; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Payment Mode <small>[ sandbox / live ]</small></label>
                                        <select name="paypal_mode" class="form-control">
                                            <option value="">Select Mode </option>
                                            <option value="sandbox" <?= (isset($data['paypal_mode']) && $data['paypal_mode'] == 'sandbox') ? "selected" : "" ?>>Sandbox ( Testing )</option>
                                            <option value="production" <?= (isset($data['paypal_mode']) && $data['paypal_mode'] == 'production') ? "selected" : "" ?>>Production ( Live )</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Currency Code <small>[ PayPal supported ]</small> <a href="https://developer.paypal.com/docs/api/reference/currency-codes/" target="_BLANK"><i class="fa fa-link"></i></a></label>
                                        <select name="paypal_currency_code" class="form-control">
                                            <option value="">Select Currency Code </option>
                                            <option value="INR" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'INR') ? "selected" : "" ?>>Indian rupee </option>
                                            <option value="AUD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'AUD') ? "selected" : "" ?>>Australian dollar </option>
                                            <option value="BRL" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'BRL') ? "selected" : "" ?>>Brazilian real </option>
                                            <option value="CAD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'CAD') ? "selected" : "" ?>>Canadian dollar </option>
                                            <option value="CNY" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'CNY') ? "selected" : "" ?>>Chinese Renmenbi </option>
                                            <option value="CZK" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'CZK') ? "selected" : "" ?>>Czech koruna </option>
                                            <option value="DKK" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'DKK') ? "selected" : "" ?>>Danish krone </option>
                                            <option value="EUR" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'EUR') ? "selected" : "" ?>>Euro </option>
                                            <option value="HKD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'HKD') ? "selected" : "" ?>>Hong Kong dollar </option>
                                            <option value="HUF" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'HUF') ? "selected" : "" ?>>Hungarian forint </option>
                                            <option value="ILS" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'ILS') ? "selected" : "" ?>>Israeli new shekel </option>
                                            <option value="JPY" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'JPY') ? "selected" : "" ?>>Japanese yen </option>
                                            <option value="MYR" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'MYR') ? "selected" : "" ?>>Malaysian ringgit </option>
                                            <option value="MXN" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'MXN') ? "selected" : "" ?>>Mexican peso </option>
                                            <option value="TWD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'TWD') ? "selected" : "" ?>>New Taiwan dollar </option>
                                            <option value="NZD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'NZD') ? "selected" : "" ?>>New Zealand dollar </option>
                                            <option value="NOK" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'NOK') ? "selected" : "" ?>>Norwegian krone </option>
                                            <option value="PHP" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'PHP') ? "selected" : "" ?>>Philippine peso </option>
                                            <option value="PLN" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'PLN') ? "selected" : "" ?>>Polish złoty </option>
                                            <option value="GBP" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'GBP') ? "selected" : "" ?>>Pound sterling </option>
                                            <option value="RUB" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'RUB') ? "selected" : "" ?>>Russian ruble </option>
                                            <option value="SGD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'SGD') ? "selected" : "" ?>>Singapore dollar </option>
                                            <option value="SEK" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'SEK') ? "selected" : "" ?>>Swedish krona </option>
                                            <option value="CHF" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'CHF') ? "selected" : "" ?>>Swiss franc </option>
                                            <option value="THB" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'THB') ? "selected" : "" ?>>Thai baht </option>
                                            <option value="USD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'USD') ? "selected" : "" ?>>United States dollar </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="paypal_business_email">Paypal Business Email</label>
                                        <input type="text" class="form-control" name="paypal_business_email" value="<?= (isset($data['paypal_business_email'])) ? $data['paypal_business_email'] : '' ?>" placeholder="Paypal Business Email" />
                                    </div>
                                    <div class="form-group">
                                        <label for="paypal_notification_url">Notification URL <small>(Set this as IPN notification URL in you PayPal account)</small></label>
                                        <input type="text" class="form-control" name="paypal_notification_url" value="<?=$protocol."://".$_SERVER['SERVER_NAME']."/paypal/ipn.php"?>" placeholder="Paypal IPN notification URL" disabled/>
                                    </div>
                                    <hr>
                                    <h5>PayUMoney Payments </h5>
                                    <hr>
                                    <div class="form-group">
                                        <label for="payumoney_payment_method">PayUMoney Payments <small>[ Enable / Disable ] </small></label><br>
                                        <input type="checkbox" id="payumoney_payment_method_btn" class="js-switch" <?php if (!empty($data['payumoney_payment_method']) && $data['payumoney_payment_method'] == '1') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>
                                        <input type="hidden" id="payumoney_payment_method" name="payumoney_payment_method" value="<?= (isset($data['payumoney_payment_method']) && !empty($data['payumoney_payment_method'])) ? $data['payumoney_payment_method'] : 0; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Payment Mode <small>[ sandbox / live ]</small></label>
                                        <select name="payumoney_mode" class="form-control">
                                            <option value="">Select Mode </option>
                                            <option value="sandbox" <?= (isset($data['payumoney_mode']) && $data['payumoney_mode'] == 'sandbox') ? "selected" : "" ?>>Sandbox ( Testing )</option>
                                            <option value="production" <?= (isset($data['payumoney_mode']) && $data['payumoney_mode'] == 'production') ? "selected" : "" ?>>Production ( Live )</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payumoney_merchant_key">Merchant key</label>
                                        <input type="text" class="form-control" name="payumoney_merchant_key" value="<?= (isset($data['payumoney_merchant_key'])) ? $data['payumoney_merchant_key'] : '' ?>" placeholder="PayUMoney Merchant Key" />
                                    </div>
                                    <div class="form-group">
                                        <label for="payumoney_merchant_id">Merchant ID</label>
                                        <input type="text" class="form-control" name="payumoney_merchant_id" value="<?= (isset($data['payumoney_merchant_id'])) ? $data['payumoney_merchant_id'] : '' ?>" placeholder="PayUMoney Merchant ID" />
                                    </div>
                                    <div class="form-group">
                                        <label for="payumoney_salt">Salt</label>
                                        <input type="text" class="form-control" name="payumoney_salt" value="<?= (isset($data['payumoney_salt'])) ? $data['payumoney_salt'] : '' ?>" placeholder="PayUMoney Merchant ID" />
                                    </div>
                                    <hr>
                                    <h5>Razorpay Payments </h5>
                                    <hr>
                                    <div class="form-group">
                                        <label for="razorpay_payment_method">Razorpay Payments <small>[ Enable / Disable ] </small></label><br>
                                        <input type="checkbox" id="razorpay_payment_method_btn" class="js-switch" <?php if (!empty($data['razorpay_payment_method']) && $data['razorpay_payment_method'] == '1') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>
                                        <input type="hidden" id="razorpay_payment_method" name="razorpay_payment_method" value="<?= (isset($data['razorpay_payment_method']) && !empty($data['razorpay_payment_method'])) ? $data['razorpay_payment_method'] : 0; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="razorpay_key">Razorpay key ID</label>
                                        <input type="text" class="form-control" name="razorpay_key" value="<?= (isset($data['razorpay_key'])) ? $data['razorpay_key'] : '' ?>" placeholder="Razor Key ID" />
                                    </div>
                                    <div class="form-group">
                                        <label for="razorpay_secret_key">Secret Key</label>
                                        <input type="text" class="form-control" name="razorpay_secret_key" value="<?= (isset($data['razorpay_secret_key'])) ? $data['razorpay_secret_key'] : '' ?>" placeholder="Razorpay Secret Key " />
                                    </div>
                                    <hr>
                                    <h5>COD Payments </h5>
                                    <hr>
                                    <div class="form-group">
                                        <label for="cod_payment_method">COD Payments <small>[ Enable / Disable ] </small></label><br>
                                        <input type="checkbox" id="cod_payment_method_btn" class="js-switch" <?php if (isset($data['cod_payment_method']) && !empty($data['cod_payment_method']) && $data['cod_payment_method'] == '1') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>
                                        <input type="hidden" id="cod_payment_method" name="cod_payment_method" value="<?= (isset($data['cod_payment_method']) && !empty($data['cod_payment_method'])) ? $data['cod_payment_method'] : 0; ?>">
                                    </div>
                                    <div class="form-group">                                                                            
                                    <input type="submit" id="btn_update" class="btn-primary btn" value="Save" name="btn_update" />
                                    </div>                                                                         
                                    <div class="form-group">
                                        <div id="result"></div>
                                    </div>     
                                                                                                               
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    <?php } else { ?>
        <div class="alert alert-danger">You have no permission to view settings</div>
    <?php } ?>
    <div class="separator"> </div>
</div><!-- /.content-wrapper -->
</body>

</html>
<?php include "footer.php"; ?>
<script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('contact_us');
</script>
<script type="text/javascript">
    /* paypal change button value */
    var changeCheckbox = document.querySelector('#paypal_payment_method_btn');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#paypal_payment_method').val(1);
        } else {
            $('#paypal_payment_method').val(0);
        }
    };

    /* payumoney change button value */

    var changeCheckbox = document.querySelector('#payumoney_payment_method_btn');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#payumoney_payment_method').val(1);
        } else {
            $('#payumoney_payment_method').val(0);
        }
    };

    /* razorpay change button value */

    var changeCheckbox = document.querySelector('#razorpay_payment_method_btn');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#razorpay_payment_method').val(1);
        } else {
            $('#razorpay_payment_method').val(0);
        }
    };

    /* COD button value */

    var changeCheckbox = document.querySelector('#cod_payment_method_btn');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#cod_payment_method').val(1);
        } else {
            $('#cod_payment_method').val(0);
        }
    };
</script>
<script>
    $('#payment_method_settings_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'public/db-operation.php',
            data: formData,
            beforeSend: function() {
                $('#btn_update').val('Please wait..').attr('disabled', true);
            },
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                $('#result').html(result);
                $('#result').show().delay(5000).fadeOut();
                $('#btn_update').val('Save').attr('disabled', false);
            }

        });
    });
</script>