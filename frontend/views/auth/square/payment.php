<?php

use shop\helpers\PriceHelper;
use yii\helpers\Html;

$this->title = $response['request'] . ' Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="<?= \Yii::$app->params['squarePaymentGateWay']['sandBox-paymentform'] ?>"></script>
<div class="container">
    <div id="payment_load" class="loader_payment"></div>
</div>

<div id="payment_div" class="container" style="margin-top:30px;">
    <ul class="checkout-progress-bar">
        <li class="active">
            <span>
                <?php if ($response['request'] == 'membership') { ?>
                    Plan Selected
                <?php } else if ($response['request'] == 'checkout') { ?>
                    Shipping
                <?php } else if ($response['request'] == 'addons') { ?>
                    Subscription Selected
                <?php } ?>
            </span>
        </li>
        <li class="active">
            <span>Review &amp; Payments</span>
        </li>
    </ul>
    <div class="row">
        <div id="show_errors" class="col-md-12">
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3><i class="fa fa-shopping-cart"></i> PURCHASES DETAILS </h3></div>
                <div class="panel-body">
                    <?php if ($response['request'] == 'membership') { ?>
                        <h3><a data-toggle="collapse" href="#collapse1">MEMBERSHIP : <?= $response['membership']['title'] ?> <i class="fa fa-arrow-down"></i><p class="text-right"><strong><?= $response['membership']['price'] == null ? 'FREE' : 'TOTAL PRICE : $' . $response['membership']['price'] ?></strong></p></a></h3>
                        <div id="collapse1" class="panel-collapse collapse">
                            <ul class="list-group">
                                <?php foreach ($response['msItems'] as $item) { ?>
                                    <li class="list-group-item"><?= ($item->unit == 0 || $item->unit == null) ? '' : $item->unit, ' ' . $item->itemType->title ?></li>
                                <?php } ?>
                                <li class="text-right list-group-item active"><strong><?= $response['membership']['price'] == null ? 'FREE' : 'TOTAL PRICE : $' . $response['membership']['price'] ?></strong></li>
                            </ul>
                        </div>
                    <?php } else if ($response['request'] == 'checkout') { ?>
                        <h3><a data-toggle="collapse" href="#collapse1">PRODUCTS IN CART <i class="fa fa-arrow-down"></i><p class="text-right"><strong>TOTAL PRICE : <?= PriceHelper::format($response['cart']->getCost()->getOrigin()) ?></strong></p></a></h3>
                        <div id="collapse1" class="panel-collapse collapse">
                            <ul class="list-group">
                                <?php foreach ($response['cart']->getItems() as $item) { ?>
                                    <?php $product = $item->getProduct(); ?>
                                    <li class="list-group-item"> <?= 'Product Name : ' . Html::encode($product->name), '<br> Qty : ', $item->getQuantity(), '<br> Price Per Item : ' . PriceHelper::format($product->price_new), '<br> Total Price : ', PriceHelper::format($product->price_new) . ' X ' . $item->getQuantity() . ' = ', PriceHelper::format($item->getCost()) ?> </li>
                                <?php } ?>
                                <li class="text-right list-group-item active"><strong><?= 'TOTAL PRICE : ' . PriceHelper::format($response['cart']->getCost()->getOrigin()) ?></strong></li>
                            </ul>
                        </div>
                    <?php } else if ($response['request'] == 'addons') { ?>
                        <?php if (isset($response['membership']) && $response['membership'] != null) { ?>
                            <h3><a data-toggle="collapse" href="#collapse1">MEMBERSHIP : <?= $response['membership']['title'] ?> <i class="fa fa-arrow-down"></i><p class="text-right"><strong><?= $response['membership']['price'] == null ? 'FREE' : 'TOTAL PRICE : $' . $response['membership']['price'] ?></strong></p></a></h3>
                            <div id="collapse1" class="panel-collapse collapse">
                                <ul class="list-group">
                                    <?php foreach ($response['membershipItems'] as $item) { ?>
                                        <li class="list-group-item"><?= ($item->unit == 0 || $item->unit == null) ? '' : $item->unit, ' ' . $item->itemType->title ?></li>
                                    <?php } ?>
                                    <li class="text-right list-group-item active"><strong><?= $response['membership']['price'] == null ? 'FREE' : 'TOTAL PRICE : $' . $response['membership']['price'] ?></strong></li>
                                </ul>
                            </div>
                        <?php } else if ($response['addons'] != null) { ?>
                            <h3><a data-toggle="collapse" href="#collapse1">ADDONS  <i class="fa fa-arrow-down"></i><p class="text-right"><strong><?= 'TOTAL PRICE : $' . $response['addonsTotalPrice'] ?></strong></p></a></h3>
                            <div id="collapse1" class="panel-collapse collapse">
                                <ul class="list-group">
                                    <?php foreach ($response['addons'] as $item) { ?>
                                        <li class="list-group-item"><?= ($item->unit == 0 || $item->unit == null) ? '' : $item->unit, ' ' . $item->itemType->title ?></li>
                                    <?php } ?>
                                    <li class="text-right list-group-item active"><strong><?= 'TOTAL PRICE : $' . $response['addonsTotalPrice'] ?></strong></li>
                                </ul>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php if ($response['request'] == 'membership' || $response['request'] == 'addons') { ?>
                <div>
                    <strong>FREE 2 MONTHS UPGRADED BILLING NOTICE </strong><br />
                    FOR YOUR SUPPORT ALL YOU IS GIVING YOU 2 FREE MONTHS OF UPGRADED MEMBERSHIP.
                    WE HOPE YOU ENJOY ALL OF THE PERKS THE UPGRADED PROVIDES.THERE IS NOT COMMITMENT 
                    AND CANCEL ANYTIME.IF YOU CHOOSE TO GO WITH THE FREE MEMBERSHIP YOU MUST CANCEL
                    BEFORE 60 DAYS NOT TO BE   CHARGED. FOR THIS UPGRADED BENEFIT YOU WILL HAVE TO
                    ENTER YOUR DEBIT/CREDIT CARD DURING YOUR SIGNUP PROCESS.YOUR CARD ON FILE IS
                    PROTECTED BY SQAURE’S TOP SECURITY PROTECTIONARY TECHNOLOGY AND ONLY FOR YOUR
                    USE TO PAY FOR GOODS, SERVICES AND YOUR UPGRADED FEATURES IF YOU CHOOSE TO
                    CONTINUE ON THEM.<br />
                    THANKYOU FOR YOUR SUPPORT<br /><br />
                </div>
            <?php } ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3><i class="fa fa-credit-card"></i> CARDS </h3></div>
                <div class="panel-body">

                    <?php if ($allcards) { ?>
                        <?php if ($allcards['code'] == 200) { ?>
                            <?php foreach ($allcards['data']['customerCards'] as $cardDetails) { ?>
                                <?php $check = ( \Yii::$app->user->identity->getUser()->getActiveCard() == $cardDetails['sourceId']) ? 'checked' : ''; ?>

                                <div class="radio"> 
                                    <div class="panel <?= $check == 'checked' ? 'panel-success' : 'panel-default' ?>">
                                        <div class="panel-heading" style="padding-bottom: -10px;padding-top: 5px;">
                                            <label class="label-width">
                                                <input type="radio" name="optradio" value="<?= $cardDetails['sourceId'] ?>" <?= $check ?>>
                                                <div id="textbox">
                                                    <h3 class="alignleftpay"> **** **** **** <?= $cardDetails['last4Digit'] ?> </h3>
                                                    <h3 class="alignrightpay"> <img style="width:40px;" src="<?= $cardDetails['imageLink'] ?>" /></h3>
                                                    <div style="clear: both;"></div>
                                                </div>
                                            </label>
                                        </div>
                                    </div> 
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                    <div class="radio"> 
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label class="label-width">
                                    <input type="radio" id="card-details-toggle-btn" value="new_card" name="optradio">
                                    <div id="textbox">
                                        <h3 class="alignleftpay"> ADD CARD </h3>
                                        <div style="clear: both;"></div>
                                    </div>
                                </label>
                            </div>
                            <div id="card-details-toggle" class="panel-body" style="display: none;">
                                <div id="form-container">

                                    <fieldset>
                                        <span style="color:black;" class="label">CARD NUMBER</span>
                                        <div id="sq-card-number"></div>

                                        <div class="third">
                                            <span style="color:black;" class="label">EXPIRATION</span>
                                            <div id="sq-expiration-date"></div>
                                        </div>

                                        <div class="third">
                                            <span style="color:black;" class="label">CVV</span>
                                            <div id="sq-cvv"></div>
                                        </div>

                                        <div class="third">
                                            <span style="color:black;" class="label">POSTAL CODE</span>
                                            <div id="sq-postal-code"></div>
                                        </div>

                                    </fieldset>
                                </div> <!-- end #form-container --> 
                            </div>
                        </div>
                    </div>
                    <?php if ($response['request'] == 'membership' || $response['request'] == 'addons') { ?>
                        <label class="label-container" id="lagree">
                            <input type="checkbox" id="agree" name="agree"> Talent Membership Agreement Acknowledgement
                            <span class="checkmark"></span>
                        </label>
                    <?php } ?>
                    <label class="label-container" id="lterms">
                        <input type="checkbox" id="terms" name="terms"> TERMS AND USES
                        <span class="checkmark"></span>
                    </label>
                    <fieldset>
                        <button id="sq-creditcard" style="width:100%;margin-top:10px;" class="btn btn-info btn-large" onclick="onGetCardNonce(event)">CONFIRM AND PAY</button>
                        <!-- <a href="#" style="width:100%;margin-top:10px;" class="btn btn-info btn-large" data-toggle="modal" data-target="#myModal" role="button" onclick="getUserCards()">Select Card From Your Previous Cards...</a> -->
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TODO: Add script from step 1.2.3 -->



<script type="text/javascript">
            // Create and initialize a payment form object
            const paymentForm = new SqPaymentForm({
            // Initialize the payment form elements

            //TODO: Replace with your sandbox application ID
            applicationId: "<?= \Yii::$app->params['squarePaymentGateWay']['sandBox-application-id'] ?>",
                    inputClass: 'sq-input',
                    autoBuild: false,
                    // Customize the CSS for SqPaymentForm iframe elements
                    inputStyles: [{
                    fontSize: '16px',
                            lineHeight: '24px',
                            padding: '16px',
                            placeholderColor: '#a0a0a0',
                            backgroundColor: 'transparent',
                    }],
                    // Initialize the credit card placeholders
                    cardNumber: {
                    elementId: 'sq-card-number',
                            placeholder: 'Card Number'
                    },
                    cvv: {
                    elementId: 'sq-cvv',
                            placeholder: 'CVV'
                    },
                    expirationDate: {
                    elementId: 'sq-expiration-date',
                            placeholder: 'MM/YY'
                    },
                    postalCode: {
                    elementId: 'sq-postal-code',
                            placeholder: 'Postal'
                    },
                    // SqPaymentForm callback functions
                    callbacks: {
                    /*
                     * callback function: cardNonceResponseReceived
                     * Triggered when: SqPaymentForm completes a card nonce request
                     */
                    cardNonceResponseReceived: function (errors, nonce, cardData) {
                    if (errors) {
                    // Log errors from nonce generation to the browser developer console.
                    console.log('Encountered errors:');
                            allErrors = '';
                            document.getElementById('show_errors').innerHTML = allErrors;
                            errors.forEach(function (error) {
                            allErrors += error.message + '. ';
                                    console.log('  ' + error.message);
                            });
                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">' +
                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                            '<strong>Error! </strong>' + allErrors + '.' +
                            '</div>';
                            document.getElementById("payment_div").style.display = "block";
                            document.getElementById("payment_load").style.display = "none";
                            return;
                    }
                    //TODO: Replace alert with code in step 2.1
<?php if ($response['request'] != null) { ?>
                        if ($('input:radio[name=optradio]:checked').val() == "new_card"){
                        addDetail(nonce, '<?= $response['request'] ?>');
                        } else{
                        changeCard('<?= $response['request'] ?>');
                        }

<?php } ?>

                    }
                    }
            });
            //TODO: paste code from step 1.2.4
                    //TODO: paste code from step 1.2.5
                            // onGetCardNonce is triggered when the "Pay $1.00" button is clicked
                                    function onGetCardNonce(event) {
                                    document.getElementById("payment_div").style.display = "none";
                                            document.getElementById("payment_load").style.display = "block";
                                            $('model').modal('show');
<?php if ($response['request'] == 'membership' || $response['request'] == 'addons') { ?>
                                        if ($("#agree").prop('checked') == true && $("#terms").prop('checked') == true){
                                        $('#lagree').css('color', '');
<?php } else { ?>
                                        if ($("#terms").prop('checked') == true){
<?php } ?>
                                    $('#lterms').css('color', '');
                                            if ($('input:radio[name=optradio]:checked').val() == "new_card"){
                                    // Don't submit the form until SqPaymentForm returns with a nonce
                                    event.preventDefault();
                                            // Request a nonce from the SqPaymentForm object
                                            paymentForm.requestCardNonce();
                                    } else{
                                    changeCard('<?= $response['request'] ?>');
                                    }

                                    }
                                    else
                                    {
                                    document.getElementById("payment_div").style.display = "block";
                                            document.getElementById("payment_load").style.display = "none";
                                            if ($("#agree").prop('checked') == false){
                                    $('#lagree').css('color', 'red');
                                    } else{
                                    $('#lagree').css('color', '');
                                    }
                                    if ($("#terms").prop('checked') == false){
                                    $('#lterms').css('color', 'red');
                                    } else{
                                    $('#lterms').css('color', '');
                                    }

                                    }
                                    }
                                    paymentForm.build();</script>

<script type="text/javascript">
                                            var activeCard = "<?= \Yii::$app->user->identity->getUser()->getActiveCard() ?>";
                                            // Add Card And Move To Request
                                                    function addDetail(nonce, requestType) {
                                                    var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
                                                            var request = baseUrl + "/auth/square/add-customer-with-card-details";
                                                            $.ajax({url: request,
                                                                    data: {nonce: nonce},
                                                                    type: 'POST',
                                                                    success: function (result) {
                                                                    console.log(result);
                                                                            if (result.code == 200) {
                                                                    document.getElementById('show_errors').innerHTML = '';
                                                                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-success alert-dismissible">' +
                                                                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                                                            '<strong>Success! </strong>' + result.message + '.' +
                                                                            '</div>';
                                                                            if (requestType == 'membership'){
                                                                    var url = baseUrl + '/selectplan';
                                                                            var form = $('<form action="' + url + '" method="POST">' +
                                                                                    '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />' +
                                                                                    '<input type="hidden" name="plan_id" value="' + <?php echo array_key_exists('membership', $response) ? $response['membership']['id'] : '' ?> + '" />' +
                                                                                    '<input type="hidden" name="card_given" value="1" />' +
                                                                                    '</form>');
                                                                    }
                                                                    else if (requestType == 'checkout'){
<?php $session = \Yii::$app->session; ?>
                                                                    var url = baseUrl + '/shop/checkout/index';
                                                                            var form = $('<form action="' + url + '" method="POST">' +
                                                                                    '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />' +
                                                                                    '<input type="hidden" name="card_given" value="1" />' +
                                                                                    '</form>');
                                                                    }
                                                                    else if (requestType == 'addons'){
<?php $session = \Yii::$app->session; ?>
                                                                    var url = baseUrl + '/subscription';
                                                                            var form = $('<form action="' + url + '" method="POST">' +
                                                                                    '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />' +
                                                                                    '<input type="hidden" name="card_given" value="1" />' +
                                                                                    '</form>');
                                                                    }
                                                                    document.getElementById("payment_div").style.display = "block";
                                                                            document.getElementById("payment_load").style.display = "none";
                                                                            $('body').append(form);
                                                                            form.submit();
                                                                            return;
                                                                    }

                                                                    if (result.code == 400) {
                                                                    document.getElementById("payment_div").style.display = "block";
                                                                            document.getElementById("payment_load").style.display = "none";
                                                                            if (Array.isArray(result.message)){
                                                                    document.getElementById('show_errors').innerHTML = '';
                                                                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">' +
                                                                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                                                            '<strong>Error! </strong>' + result.message + '.' +
                                                                            '</div>';
                                                                            return;
                                                                    } else{
                                                                    document.getElementById('show_errors').innerHTML = '';
                                                                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">' +
                                                                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                                                            '<strong>Error! </strong>' + result.message + '.' +
                                                                            '</div>';
                                                                            return;
                                                                    }
                                                                    }
                                                                    },
                                                                    error: function (error) {
                                                                    console.log(error);
                                                                            document.getElementById("payment_div").style.display = "block";
                                                                            document.getElementById("payment_load").style.display = "none";
                                                                            alert('ERROR : Please Check Console For More Details');
                                                                    }
                                                            });
                                                    }




                                            function changeCard(requestType)
                                            {
                                            document.getElementById("payment_div").style.display = "none";
                                                    document.getElementById("payment_load").style.display = "block";
                                                    var sourceId = $("input[name=optradio]:checked").val();
                                                    var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
                                                    var request = baseUrl + "/auth/square/change-active-card";
                                                    $.ajax({url: request,
                                                            data: {type: 'changer', id : sourceId},
                                                            type: 'POST',
                                                            success: function (result) {
                                                            if (result.code == 200) {
                                                            console.log(result);
                                                                    document.getElementById('show_errors').innerHTML = '';
                                                                    document.getElementById('show_errors').innerHTML = '<div class="alert alert-success alert-dismissible">' +
                                                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                                                    '<strong>Success! </strong>' + result.message + '.' +
                                                                    '</div>';
                                                                    if (requestType == 'membership'){
                                                            var url = baseUrl + '/selectplan';
                                                                    var form = $('<form action="' + url + '" method="POST">' +
                                                                            '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />' +
                                                                            '<input type="hidden" name="plan_id" value="' + <?php echo array_key_exists('membership', $response) ? $response['membership']['id'] : '' ?> + '" />' +
                                                                            '<input type="hidden" name="card_given" value="1" />' +
                                                                            '</form>');
                                                            }
                                                            else if (requestType == 'checkout'){
<?php $session = \Yii::$app->session; ?>
                                                            var url = baseUrl + '/shop/checkout/index';
                                                                    var form = $('<form action="' + url + '" method="POST">' +
                                                                            '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />' +
                                                                            '<input type="hidden" name="card_given" value="1" />' +
                                                                            '</form>');
                                                            }
                                                            else if (requestType == 'addons'){
<?php $session = \Yii::$app->session; ?>
                                                            var url = baseUrl + '/subscription';
                                                                    var form = $('<form action="' + url + '" method="POST">' +
                                                                            '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />' +
                                                                            '<input type="hidden" name="card_given" value="1" />' +
                                                                            '</form>');
                                                            }

                                                            activeCard = result.data.sourceId
                                                                    document.getElementById("payment_div").style.display = "block";
                                                                    document.getElementById("payment_load").style.display = "none";
                                                                    $('body').append(form);
                                                                    form.submit();
                                                                    return;
                                                            }

                                                            if (result.code == 400){
                                                            document.getElementById("payment_div").style.display = "block";
                                                                    document.getElementById("payment_load").style.display = "none";
                                                                    if (Array.isArray(result.message)){
                                                            document.getElementById('show_errors').innerHTML = '';
                                                                    document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">' +
                                                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                                                    '<strong>Error! </strong>' + result.message + '.' +
                                                                    '</div>';
                                                                    return;
                                                            } else{
                                                            document.getElementById('show_errors').innerHTML = '';
                                                                    document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">' +
                                                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                                                    '<strong>Error! </strong>' + result.message + '.' +
                                                                    '</div>';
                                                                    return;
                                                            }
                                                            }

                                                            },
                                                            error: function (error) {
                                                            document.getElementById("payment_div").style.display = "block";
                                                                    document.getElementById("payment_load").style.display = "none";
                                                                    console.log(error);
                                                                    alert('Error Check Console For More Detail');
                                                            }
                                                    });
                                            }



                                            $(document).ready(function(){
                                            $("input:radio[name=optradio]").click(function(){
                                            if ($('input:radio[name=optradio]:checked').val() == "new_card"){
                                            $("#card-details-toggle").show();
                                            } else{
                                            $("#card-details-toggle").hide();
                                            }
                                            });
                                            });
</script>




