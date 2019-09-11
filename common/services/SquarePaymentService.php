<?php 

namespace common\services;

use Yii;
use Exception;
use SquareConnect\ApiClient;
use SquareConnect\Model\Money;
use SquareConnect\Configuration;
use SquareConnect\Api\PaymentsApi;
use SquareConnect\Api\CustomersApi;
use SquareConnect\Api\LocationsApi;
use SquareConnect\Model\CreatePaymentRequest;
use SquareConnect\Model\CreateCustomerRequest;
use SquareConnect\Model\UpdateCustomerRequest;
use SquareConnect\Model\SearchCustomersRequest;
use SquareConnect\Model\CreateCustomerCardRequest;

class SquarePaymentService
{
    // Square payment gateway credentials
    private $applicationId;
    private $locationId;
    private $accessToken;
    private $host;

    // Sandbox square payment gateway credentials
    private $sandBoxApplicationId;
    private $sandBoxLocationId;
    private $sandBoxAccessToken;
    private $sandBoxHost;
    
    public static $checkoutCard = 0;
    // Development Mode ON : OFF
    private $sandBox = true;

    public function __construct()
    {
        $this->applicationId = Yii::$app->params['squarePaymentGateWay']['application-id'];
        $this->locationId = Yii::$app->params['squarePaymentGateWay']['location-id'];
        $this->accessToken = Yii::$app->params['squarePaymentGateWay']['access-token'];
        $this->host = Yii::$app->params['squarePaymentGateWay']['host'];

        $this->sandBoxApplicationId = Yii::$app->params['squarePaymentGateWay']['sandBox-application-id'];
        $this->sandBoxLocationId = Yii::$app->params['squarePaymentGateWay']['sandBox-location-id'];
        $this->sandBoxAccessToken = Yii::$app->params['squarePaymentGateWay']['sandBox-access-token'];
        $this->sandBoxHost = Yii::$app->params['squarePaymentGateWay']['sandBox-host'];
    }

    private function Configuration()
    {
        $apiConfig = new Configuration();
        $apiConfig->setHost($this->sandBox === false ? $this->host : $this->sandBoxHost);
        $apiConfig->setAccessToken($this->sandBox === false ? $this->accessToken : $this->sandBoxAccessToken);
        return new ApiClient($apiConfig);
    }

    public function listLocations()
    {
        $apiClient = $this->Configuration();
        $locationsApi = new LocationsApi($apiClient);

        try 
        {
            $locations = $locationsApi->listLocations();
            return $locations->getLocations();
        }
        catch (ApiException $e) 
        {
            echo "Caught exception!<br/>";
            print_r("<strong>Response body:</strong><br/>");
            echo "<pre>"; var_dump($e->getResponseBody()); echo "</pre>";
            echo "<br/><strong>Response headers:</strong><br/>";
            echo "<pre>"; var_dump($e->getResponseHeaders()); echo "</pre>";
            die();
        }
    }

    
    public function payment(int $chargesAmount, string $currencyType ='USD')
    {
        $user = \Yii::$app->user->identity->getUser();
        $nonce_id = $user->getActiveCard();
        $cust_id = $user->square_cust_id;

        if($nonce_id == null) {
            return "No Active Card Found For Payment";
        }

        $apiClient = $this->Configuration();
        $paymentApi = new PaymentsApi($apiClient);
        $locationId = $this->sandBox === false ? $this->locationId : $this->sandBoxLocationId;

        $paymentModel = new CreatePaymentRequest();
        $amountMoney = new Money();

        // Monetary amounts are specified in the smallest unit of the applicable currency.
        // This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.

        $amountMoney->setAmount($chargesAmount);
        $amountMoney->setCurrency($currencyType);

        $paymentModel->setSourceId($nonce_id);
        $paymentModel->setAmountMoney($amountMoney);
        $paymentModel->setLocationId($locationId);
        $paymentModel->setCustomerId($cust_id);

        //  Every payment you process with the SDK must have a unique idempotency key.
        //  If you're unsure whether a particular payment succeeded, you can reattempt
        //  it with the same idempotency key without worrying about double charging
        //  the buyer.

        $paymentModel->setIdempotencyKey(uniqid());

        try {
            $result = $paymentApi->createPayment($paymentModel);
            return $result;
        } catch (\SquareConnect\ApiException $e) {
            $showError = '';
            if(is_array($e->getResponseBody()->errors)){
                foreach($e->getResponseBody()->errors as $error){
                    $showError .= $error->detail;
                }
                throw new \yii\web\HttpException(404,$showError);
            }
            throw new \yii\web\HttpException(404,$e->getResponseBody()->errors);
        }
    }

    public function createCustomer($customerArr = [])
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient);
        $body = new CreateCustomerRequest($customerArr); 

        try {
            $result = $apiInstance->createCustomer($body);
            return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->createCustomer: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function addCustomerCardDetail($customer_id = null, $cardDetail = [])
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient); 
        $body = new CreateCustomerCardRequest($cardDetail);
        try {
            $result = $apiInstance->createCustomerCard($customer_id, $body);
            return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->createCustomerCard: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function deleteCustomer($customer_id = null)
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient);
        try {
            $result = $apiInstance->deleteCustomer($customer_id);
            return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->deleteCustomer: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function deleteCustomerCard($customer_id = null, $card_id = null)
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient);
        try {
            $result = $apiInstance->deleteCustomerCard($customer_id, $card_id);
            return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->deleteCustomerCard: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function listCustomers($cursor = null, $sort_field = null, $sort_order = null)
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient);
        try {
            $result = $apiInstance->listCustomers($cursor, $sort_field, $sort_order);
            return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->listCustomers: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function retrieveCustomer($customer_id = null)
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient);
        try {
            $result = $apiInstance->retrieveCustomer($customer_id);
            return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->retrieveCustomer: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function searchCustomers($searchCustomerArr = [])
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient);
        $body = new SearchCustomersRequest($searchCustomerArr);

        try {
            $result = $apiInstance->searchCustomers($body);
           return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->searchCustomers: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function updateCustomer($customer_id = null, $customerArr = [])
    {
        $apiClient = $this->Configuration();
        $apiInstance = new CustomersApi($apiClient);
        $body = new UpdateCustomerRequest($customerArr); 

        try {
            $result = $apiInstance->updateCustomer($customer_id, $body);
            return $result;
        } catch (Exception $e) {
            return 'Exception when calling CustomersApi->updateCustomer: ' . $e->getMessage() . PHP_EOL;
        }
    }
}