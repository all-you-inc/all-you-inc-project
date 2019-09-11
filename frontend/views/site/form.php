<html>
<head>
    <title>Square Payment Gateway</title>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['squarePaymentGateWay']['sandBox-paymentform'] ?>"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
          button {
    border: 0;
    font-weight: 500;
  }
  
  fieldset {
    margin: 0;
    padding: 0;
    border: 0;
  }
  
  #form-container {
    margin-top: 30px;
    margin-bottom: 30px;
  }
  
  .third {
    float: left;
    width: calc((100% - 32px) / 3);
    padding: 0;
    margin: 0 16px 16px 0;
  }

  .two-col {
    float: left;
    width: calc((100% - 32px) / 2);
    padding: 0;
    margin: 0 16px 16px 0;
  }
  
  .third:last-of-type {
    margin-right: 0;
  }
  
  /* Define how SqPaymentForm iframes should look */
  .sq-input {
    height: 56px;
    box-sizing: border-box;
    border: 1px solid #E0E2E3;
    background-color: white;
    border-radius: 6px;
    -webkit-transition: border-color .2s ease-in-out;
       -moz-transition: border-color .2s ease-in-out;
        -ms-transition: border-color .2s ease-in-out;
            transition: border-color .2s ease-in-out;
  }
  
  /* Define how SqPaymentForm iframes should look when they have focus */
  .sq-input--focus {
    border: 1px solid #4A90E2;
  }
  
  /* Define how SqPaymentForm iframes should look when they contain invalid values */
  .sq-input--error {
    border: 1px solid #E02F2F;
  }
  
  #sq-card-number {
    width: 100%;
    margin-bottom: 16px;
  }
  
  /* Customize the "Pay with Credit Card" button */
  .button-credit-card {
    width: 100%;
    height: 56px;
    margin-top: 10px;
    background: #4A90E2;
    border-radius: 6px;
    cursor: pointer;
    color: #FFFFFF;
    font-size: 16px;
    line-height: 24px;
    font-weight: 700;
    letter-spacing: 0;
    text-align: center;
    -webkit-transition: background .2s ease-in-out;
       -moz-transition: background .2s ease-in-out;
        -ms-transition: background .2s ease-in-out;
            transition: background .2s ease-in-out;
  }
  
  .button-credit-card:hover {
    background-color: #4281CB;
  }
    </style>

</head>
<body>
 
    <div id="payment_div" class="container" style="margin-top:30px;">
        
        <div class="row">
            <div id="show_errors" class="col-md-12">
            </div>
            <?php if($cards != null){ ?>
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item"><h2>Your Cards</h2></li>
                        <?php for($i=0;$i<count($cards);$i++){ ?>
                            <li class="list-group-item"><label><input type="radio" name="optradio" value="<?= $cards[$i]['sourceId'] ?>" /> <?= 'Card Brand : ' . $cards[$i]['cardBrand'] . ', Last 4 Number : ' . $cards[$i]['last4Digit'] ?></label></li>
                        <?php } ?>
                        <li class="list-group-item"><button class="btn btn-info btn-large" onclick="selectCardIdAlert()">Select This Card</button></li>
                    </ul>
                </div>
            <?php } ?>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3><i class="fa fa-credit-card"></i>Add Card</h3></div>
                    <div class="panel-body">
                        <div id="form-container">
                            <fieldset>
                                <span style="color:black;" class="label">CARD NUMBER</span>
                                <div id="sq-card-number"></div>

                                <div class="two-col">
                                    <span style="color:black;" class="label">EXPIRATION</span>
                                    <div id="sq-expiration-date"></div>
                                </div>
                                
                                <div class="two-col">
                                    <span style="color:black;" class="label">CVV</span>
                                    <div id="sq-cvv"></div>
                                </div>
                                
                                
                                <span style="color:black;" class="label">POSTAL CODE</span>
                                <div id="sq-postal-code"></div>
                                
                                <button id="sq-creditcard" class="button-credit-card" onclick="onGetCardNonce(event)">INSERT CARD DETAIL</button>
                            </fieldset>
                        </div> <!-- end #form-container --> 
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
                    console.error('Encountered errors:');
                    allErrors = '';
                    document.getElementById('show_errors').innerHTML = allErrors;
                    errors.forEach(function (error) {
                        allErrors += '<div class="alert alert-danger alert-dismissible">'+
                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                                    '<strong>Error! </strong>'+ error.message +'.'+
                                    '</div>';
                        console.error('  ' + error.message);
                    });
                    document.getElementById('show_errors').innerHTML = allErrors;
                    return;
                }

                    var responseObject = {type:1,value:nonce};
                    window.postMessage(JSON.stringify(responseObject),"*");
                    
                }
            }
        });
        //TODO: paste code from step 1.2.4
        //TODO: paste code from step 1.2.5
        // onGetCardNonce is triggered when the "Pay $1.00" button is clicked
        function onGetCardNonce(event) {

            // Don't submit the form until SqPaymentForm returns with a nonce
            event.preventDefault();
            // Request a nonce from the SqPaymentForm object
            paymentForm.requestCardNonce();
        }
        paymentForm.build();


        function selectCardIdAlert(){
            var responseObject = {  type:2,
                                    value:$("input[name=optradio]:checked").val()
                                 };
            window.postMessage(JSON.stringify(responseObject),"*");
        }

    </script>
    <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<body>
</html>