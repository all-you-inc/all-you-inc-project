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
                    <?php if($response['request'] == 'membership'){?>
                        Plan Selected
                    <?php }else if($response['request'] == 'checkout'){ ?>
                        Shipping
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
                        <?php if($response['request'] == 'membership'){?>
                            <h3><a data-toggle="collapse" href="#collapse1">MEMBERSHIP : <?=$response['membership']['title']?> <i class="fa fa-arrow-down"></i><p class="text-right"><strong><?= $response['membership']['price'] == null ? 'FREE' : 'TOTAL PRICE : $' . $response['membership']['price'] ?></strong></p></a></h3>
                            <div id="collapse1" class="panel-collapse collapse">
                                <ul class="list-group">
                                    <?php foreach($response['msItems'] as $item){ ?>
                                        <li class="list-group-item"><?= ($item->unit == 0 || $item->unit == null) ? '' : $item->unit , ' ' . $item->itemType->title ?></li>
                                    <?php } ?>
                                    <li class="text-right list-group-item active"><strong><?= $response['membership']['price'] == null ? 'FREE' : 'TOTAL PRICE : $' . $response['membership']['price'] ?></strong></li>
                                </ul>
                            </div>
                        <?php }else if($response['request'] == 'checkout'){ ?>
                            <h3><a data-toggle="collapse" href="#collapse1">PRODUCTS IN CART <i class="fa fa-arrow-down"></i><p class="text-right"><strong>TOTAL PRICE : <?= PriceHelper::format($response['cart']->getCost()->getOrigin())  ?></strong></p></a></h3>
                            <div id="collapse1" class="panel-collapse collapse">
                                <ul class="list-group">
                                    <?php foreach($response['cart']->getItems() as $item){ ?>
                                        <?php $product = $item->getProduct(); ?>
                                        <li class="list-group-item"> <?= 'Product Name : ' . Html::encode($product->name) ,'<br> Qty : ', $item->getQuantity() , '<br> Price Per Item : ' . PriceHelper::format($product->price_new) ,'<br> Total Price : ', PriceHelper::format($product->price_new) . ' X ' . $item->getQuantity() . ' = ' , PriceHelper::format($item->getCost())  ?> </li>
                                    <?php } ?>
                                    <li class="text-right list-group-item active"><strong><?= 'TOTAL PRICE : ' . PriceHelper::format($response['cart']->getCost()->getOrigin()) ?></strong></li>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3><i class="fa fa-credit-card"></i> INSERT CARD DETAIL </h3></div>
                    <div class="panel-body">
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

                                <button id="sq-creditcard" style="width:100%;margin-top:10px;" class="btn btn-info btn-large" onclick="onGetCardNonce(event)">INSERT CARD DETAIL...</button>
                                <a href="#" style="width:100%;margin-top:10px;" class="btn btn-info btn-large" data-toggle="modal" data-target="#myModal" role="button" onclick="getUserCards()">Select Card From Your Previous Cards...</a>
                            </fieldset>
                        </div> <!-- end #form-container --> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-credit-card"></i> YOUR CARDS</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="user_card_loader" class="loader_payment_card"></div>
                    <div id="show_user_cards">
                        <h3 id="message_card" class="text-center"></h3>
                        <h3 id="user_square_detail" class="text-right"></h3>

                        <ul class="list-group">
                            <div id="user_all_cards"></div>
                        </ul>
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
                    document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">'+
                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                    '<strong>Error! </strong>'+ allErrors +'.'+
                                    '</div>';
                    document.getElementById("payment_div").style.display = "block";
                    document.getElementById("payment_load").style.display = "none";
                    return;
                }
                    //TODO: Replace alert with code in step 2.1
                    <?php if($response['request'] != null){ ?>
                            addDetail(nonce,'<?=$response['request']?>');
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
            // Don't submit the form until SqPaymentForm returns with a nonce
            event.preventDefault();
            // Request a nonce from the SqPaymentForm object
            paymentForm.requestCardNonce();
        }
        paymentForm.build();

    </script>

    <script type="text/javascript">
        var activeCard = "<?= \Yii::$app->user->identity->getUser()->getActiveCard() ?>";
        // Add Card And Move To Request
        function addDetail(nonce,requestType) { 
            var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
            var request = baseUrl + "/auth/square/add-customer-with-card-details";
            $.ajax({url: request,
                data: {nonce: nonce},
                type: 'POST',
                success: function (result) {
                    console.log(result);
                    if(result.code == 200) {
                        document.getElementById('show_errors').innerHTML = '';
                        document.getElementById('show_errors').innerHTML = '<div class="alert alert-success alert-dismissible">'+
                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                    '<strong>Success! </strong>'+ result.message +'.'+
                                    '</div>';
                        
                        if(requestType == 'membership'){ 
                            var url = baseUrl + '/plan';
                            var form = $('<form action="' + url + '" method="POST">' +
                                            '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />'+
                                            '<input type="hidden" name="plan_id" value="' + <?= $response['membership']['id'] ?> + '" />' +
                                        '</form>');
                        }
                        else if(requestType == 'checkout'){ 
                            <?php $session = \Yii::$app->session; ?>
                            var url = baseUrl + '/shop/checkout/index';
                            var form = $('<form action="' + url + '" method="POST">' +
                                            '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />'+
                                            '<input type="hidden" name="card_given" value="1" />' +
                                        '</form>');
                        } 
                        document.getElementById("payment_div").style.display = "block";
                        document.getElementById("payment_load").style.display = "none";
                        $('body').append(form);
                        form.submit();

                        return;
                    }

                    if(result.code == 400) {
                        document.getElementById("payment_div").style.display = "block";
                        document.getElementById("payment_load").style.display = "none";
                        if(Array.isArray(result.message)){
                            document.getElementById('show_errors').innerHTML = '';
                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">'+
                                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                        '<strong>Error! </strong>'+ result.message +'.'+
                                        '</div>';
                            return;
                        }else{
                            document.getElementById('show_errors').innerHTML = '';
                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">'+
                                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                        '<strong>Error! </strong>'+ result.message +'.'+
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


        function getUserCards() {
            document.getElementById("show_user_cards").style.display = "none";
            document.getElementById("user_card_loader").style.display = "block";
            var requestType = '<?=$response['request']?>';
            var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
            var request = baseUrl + "/auth/square/get-all-cards";
            $.ajax({url: request,
                data: {type: 'model'},
                type: 'POST',
                success: function (result) {
                    console.log(result);
                    if(result.code == 200){
                        var allcards =  ''; 
                        result.data.customerCards.forEach(function (card) {
                            allcards += '<li class="list-group-item">' +
                                            '<div class="radio">' +
                                                '<label><input type="radio" name="optradio" value="'+ card.sourceId +'"' + ((card.sourceId === activeCard) ? "checked" : "") + '>' +
                                                    '<ul style="width:100%;" class="list-group info-box-text">' +
                                                        '<li class="list-group-item">Brand : '+ card.cardBrand +'</li>' +
                                                        '<li class="list-group-item">Card Number : **** **** **** '+ card.last4Digit +'</li>' +
                                                        '<li class="list-group-item">Expire Month : '+ card.expMonth +'</li>' +
                                                        '<li class="list-group-item">Expire Year  : '+ card.expYear +'</li>' +
                                                    '</ul>' +
                                                '</label>' +
                                            '</div>' +
                                        '</li>';
                        });
                        allcards += '<div style="margin-top:10px;" class="text-center"><button type="button" onclick="changeCard(\''+ requestType +'\')" class="btn btn-info">Select Card For Direct Payment</button></div>';
                        document.getElementById('user_square_detail').innerHTML = 'Your ID : #' + result.data.customerDetail.id;
                        document.getElementById('user_all_cards').innerHTML = allcards ;          
                        document.getElementById("show_user_cards").style.display = "block";
                        document.getElementById("user_card_loader").style.display = "none";
                    }
                    if(result.code == 400){
                        document.getElementById('user_square_detail').innerHTML = ''; 
                        document.getElementById('user_all_cards').innerHTML = ''; 
                        document.getElementById('message_card').innerHTML = result.message;
                        document.getElementById("show_user_cards").style.display = "block";
                        document.getElementById("user_card_loader").style.display = "none";
                    }
                },
                error: function (error) {
                    console.log(error);
                    alert('error');
                }
            });
        }


        function changeCard(requestType)
        {
            $('#myModal').modal('hide');

            document.getElementById("payment_div").style.display = "none";
            document.getElementById("payment_load").style.display = "block";
            var sourceId = $("input[name=optradio]:checked").val();   
            var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
            var request = baseUrl + "/auth/square/change-active-card";
            $.ajax({url: request,
                data: {type: 'changer', id : sourceId},
                type: 'POST',
                success: function (result) {
                    if(result.code == 200) {
                        console.log(result);
                        document.getElementById('show_errors').innerHTML = '';
                        document.getElementById('show_errors').innerHTML = '<div class="alert alert-success alert-dismissible">'+
                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                    '<strong>Success! </strong>'+ result.message +'.'+
                                    '</div>';

                        if(requestType == 'membership'){ 
                            var url = baseUrl + '/plan';
                            var form = $('<form action="' + url + '" method="POST">' +
                                            '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />'+
                                            '<input type="hidden" name="plan_id" value="' + <?= $response['membership']['id'] ?> + '" />' +
                                        '</form>');
                        }
                        else if(requestType == 'checkout'){ 
                            <?php $session = \Yii::$app->session; ?>
                            var url = baseUrl + '/shop/checkout/index';
                            var form = $('<form action="' + url + '" method="POST">' +
                                            '<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />'+
                                            '<input type="hidden" name="card_given" value="1" />' +
                                        '</form>');
                        }

                        document.getElementById("show_user_cards").style.display = "block";
                        document.getElementById("user_card_loader").style.display = "none";
                        
                        activeCard = result.data.sourceId
                        document.getElementById("payment_div").style.display = "block";
                        document.getElementById("payment_load").style.display = "none";
                        $('body').append(form);
                        form.submit();

                        return;
                    }

                    if(result.code == 400){
                        document.getElementById("payment_div").style.display = "block";
                        document.getElementById("payment_load").style.display = "none";
                        if(Array.isArray(result.message)){
                            document.getElementById('show_errors').innerHTML = '';
                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">'+
                                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                        '<strong>Error! </strong>'+ result.message +'.'+
                                        '</div>';
                            return;
                        }else{
                            document.getElementById('show_errors').innerHTML = '';
                            document.getElementById('show_errors').innerHTML = '<div class="alert alert-danger alert-dismissible">'+
                                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                        '<strong>Error! </strong>'+ result.message +'.'+
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
    </script>




   