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

  /* The container */
.label-container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 5px;
    cursor: pointer;
    font-size: 14px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default checkbox */
.label-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
}

/* On mouse-over, add a grey background color */
.label-container:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.label-container input:checked ~ .checkmark {
    background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.label-container input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.label-container .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
.alignleftpay {
	float: left;
}
.alignrightpay {
	float: right;
}

.label-width {
    width: 100%;
}
    </style>

</head>
<body>
 
    <div id="payment_div" class="container" style="margin-top:30px;">
        <div class="row">
            <?php  if($response['request'] == 'membership' ){ ?>
                <div class="col-md-12">
                    <h3><strong>FREE 2 MONTHS UPGRADED BILLING NOTICE </strong></h3><br />
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
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading"><h3><i class="fa fa-credit-card"></i>Cards</h3></div>
                <div class="panel-body">
                    <div id="show_errors" class="col-md-12">
                    </div>
                    <?php if($cards != null){ ?>
                        <div class="col-md-12">
                            
                            <?php for($i=0;$i<count($cards);$i++){ ?>
                                <?php $check = ( $activeCard == $cards[$i]['sourceId']) ? 'checked' : ''; ?>
                                <div class="radio"> 
                                    <div class="panel <?= $check == 'checked' ? 'panel-success' :'panel-default' ?>">
                                        <div class="panel-heading" style="padding-bottom: -10px;padding-top: 5px;">
                                            <label class="label-width">
                                                <input type="radio" name="optradio" value="<?= $cards[$i]['sourceId'] ?>" <?= $check ?>>
                                                <div id="textbox">
                                                    <h3 class="alignleftpay"> **** **** **** <?= $cards[$i]['last4Digit'] ?> </h3>
                                                    <h3 class="alignrightpay"> <img style="width:40px;" src="<?= $cards[$i]['imageLink'] ?>" /></h3>
                                                    <div style="clear: both;"></div>
                                                </div>
                                            </label>
                                        </div>
                                    </div> 
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="col-md-12">

                                
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
                                            
                                            
                                        </fieldset>
                                    </div> <!-- end #form-container --> 
                                    
                                </div>
                            </div>
                        </div>
                        <?php  if($response['request'] == 'membership' ){ ?>
                        <label class="label-container" id="lagree">
                            <input type="checkbox" id="agree" name="agree"> Talent Membership Agreement Acknowledgement
                            <span class="checkmark"></span>
                        </label>
                        <br />
                        <?php } ?>
                        <label class="label-container" id="lterms">
                            <input type="checkbox" id="terms" name="terms"> TERMS AND USE
                            <span class="checkmark"></span>
                        </label>
                        <br />

                        <button id="sq-creditcard" class="button-credit-card" onclick="onGetCardNonce(event)">CONFIRM AND PAY</button>
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

            
            <?php if($response['request'] == 'membership'){ ?>
                if($("#agree").prop('checked') == true && $("#terms").prop('checked') == true){
                        $('#lagree').css('color', '');
            <?php }else{ ?>
                if($("#terms").prop('checked') == true){
                <?php } ?>
                $('#lterms').css('color', '');

                if($('input:radio[name=optradio]:checked').val() == "new_card"){
                     // Don't submit the form until SqPaymentForm returns with a nonce
                    event.preventDefault();
                    // Request a nonce from the SqPaymentForm object
                    paymentForm.requestCardNonce();
                }else{
                    selectCardIdAlert();
                }

            }
            else
            {
                if($("#agree").prop('checked') == false){
                    $('#lagree').css('color', 'red');
                }else{
                    $('#lagree').css('color', '');
                }
                if($("#terms").prop('checked') == false){
                    $('#lterms').css('color', 'red');  
                }else{
                    $('#lterms').css('color', '');
                }
                
            }
        }
        paymentForm.build();


        function selectCardIdAlert(){
            var responseObject = {  type:2,
                                value:$("input[name=optradio]:checked").val()
                                };
            window.postMessage(JSON.stringify(responseObject),"*");
        }

        $(document).ready(function(){
            $("input:radio[name=optradio]").click(function(){
                if($('input:radio[name=optradio]:checked').val() == "new_card"){
                    $("#card-details-toggle").show();
                }else{
                    $("#card-details-toggle").hide();
                }
            });
        });

    </script>
    <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<body>
</html>