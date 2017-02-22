<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get All Customers
$app->get('/api/customers', function(Request $request, Response $response){
    $sql = "SELECT * FROM customers";

    try{
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    }catch(PDOException $e){
        echo '{"error":  {"text": '. $e->getMessage().'}';
    }
});

// Get Single Customer
$app->get('/api/customer/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    
    $sql = "SELECT * FROM customers WHERE id = $id";

    try{
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);
    }catch(PDOException $e){
        echo '{"error":  {"text": '. $e->getMessage().'}';
    }
});

// Post Customer
$app->post('/api/customer/add', function(Request $request, Response $response){
    $name = $request->getParam('name');
    $email = $request->getParam('email');
    $city = $request->getParam('city');
    
    $sql = "INSERT INTO customers (name, email, city) VALUES (:name,:email,:city)";

    try{
        $db = new db();
        $db = $db->connect();

       $stmt = $db->prepare($sql);
       $stmt->bindParam(':name', $name);
       $stmt->bindParam(':email', $email);
       $stmt->bindParam(':city', $city);

       $stmt->execute();

       echo '{"notice": {"text": "Customer Added"}';

    }catch(PDOException $e){
        echo '{"error":  {"text": '. $e->getMessage().'}';
    }
});

// Update Customer
$app->put('/api/customer/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $name = $request->getParam('name');
    $email = $request->getParam('email');
    $city = $request->getParam('city');
    
    $sql = "UPDATE customers SET
            name = :name,
            email = :email,
            city = :city 
            WHERE id = $id";

    try{
        $db = new db();
        $db = $db->connect();

       $stmt = $db->prepare($sql);
       $stmt->bindParam(':name', $name);
       $stmt->bindParam(':email', $email);
       $stmt->bindParam(':city', $city);

       $stmt->execute();

       echo '{"notice": {"text": "Customer Updated"}';

    }catch(PDOException $e){
        echo '{"error":  {"text": '. $e->getMessage().'}';
    }
});

// Delete Customer
$app->get('/api/customer/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    
    $sql = "DELETE FROM customers WHERE id = $id";

    try{
        $db = new db();
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Customer deleted"}';
    }catch(PDOException $e){
        echo '{"error":  {"text": '. $e->getMessage().'}';
    }
});