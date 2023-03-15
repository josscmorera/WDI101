<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"'); 
require_once '../../wp-load.php';
function generar_token_seguro($longitud)
{
    if ($longitud < 4) {
        $longitud = 4;
    }
 
    return bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
}

require '../libs/Slim/Slim.php'; 
\Slim\Slim::registerAutoloader(); 
$app = new \Slim\Slim();

/* Usando GET*/
$app->get('/', function() use ($app){
    
    
    global $wpdb;
    if(!empty($app->request->get('invoice'))  &&
       !empty($app->request->get('amount'))  &&
       !empty($app->request->get('currency'))  &&
       !empty($app->request->get('key'))  
       ){
        
        //verificar si el key existe en la base de datos 
        $registros = $wpdb->get_results("select * from wp_billpay_sites where publicKey = '".$app->request->get('key')."' and active = 1");
        if(count($registros)>0){
            if($registros[0]->free_quantity	== 1){
                //monto libre
                  //ahora preguntamos por los currency 
$registros_cu = $wpdb->get_results("select * from wp_billpay_currency_sites where nombre = '".$app->request->get('currency')."'  and  active = 1 and id_site = '".$registros[0]->id."' ");
if(count($registros_cu)>0){
      //si existe y esta activo entonces todo bien 

      //ahora validar los metodos de pago si estan activos o no o si hay varios 
      $registros_pay = $wpdb->get_results("select * from wp_billpay_procesors_sites where id_site = '".$registros[0]->id."' and active = 1 ");
      if(count($registros_pay)>0){
          //hay activos 
          //ahora pregunto si hay uno o mas
          if(count($registros_pay)==1){
             //aqui hay uno solo por lo tanto no se usa la plantilla multiple
             $fechadehoy = date('Y-m-d H:i:s');
             $token_seguro = generar_token_seguro(40);
             //guardo los datos con el token generado para luego hacer la redireccion
             
             $response = array();
             $data = array(
                 'invoice'=>$app->request->get('invoice'), 
                 'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                 'amount'=>$app->request->get('amount'),
                 'currency'=>$app->request->get('currency'),
                 'key'=>$app->request->get('key')
             );
       //      $response["fecha"] = $fechadehoy;
       //      $response["template"] = 1;
             $response["status"] = 1;
             $response["message"] = "success";
             $response["data"] = $data;
             //ahora guardo los datos en la tabla del token 
             
             $wpdb->insert('wp_billpay_payment_token',
              array(
                  'id' => 'null',
                  'invoice' => $app->request->get('invoice'),
                  'amount' => $app->request->get('amount'),
                  'currency' => $app->request->get('currency'),
                  'publicKey' => $app->request->get('key'),
                  'token' => $token_seguro,
                  'procesor' =>$registros_pay[0]->nombre,
                  'template' => 1,
                  'fecha' => $fechadehoy,
                  'active' => 1

              )); 
             echoResponse(200, $response);
              //envio los datos normales 
          }else
          {
              //aqui son mas de uno x lo tanto se usa la plantilla multiple
              
             //aqui hay uno solo por lo tanto no se usa la plantilla multiple
             $fechadehoy = date('Y-m-d H:i:s');
             $token_seguro = generar_token_seguro(40);
             //guardo los datos con el token generado para luego hacer la redireccion
             
             $response = array();
             $data = array(
                 'invoice'=>$app->request->get('invoice'), 
                 'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                 'amount'=>$app->request->get('amount'),
                 'currency'=>$app->request->get('currency'),
                 'key'=>$app->request->get('key')
             );
       //      $response["fecha"] = $fechadehoy;
       //      $response["template"] = 1;
             $response["status"] = 1;
             $response["message"] = "success";
             $response["data"] = $data;
             //ahora guardo los datos en la tabla del token 
             $wpdb->insert('wp_billpay_payment_token',
              array(
                  'id' => 'null',
                  'invoice' => $app->request->get('invoice'),
                  'amount' => $app->request->get('amount'),
                  'currency' => $app->request->get('currency'),
                  'publicKey' => $app->request->get('key'),
                  'token' => $token_seguro,
                  'procesor' =>"varios",
                  'template' => 2,
                  'fecha' => $fechadehoy,
                  'active' => 1

              )); 
             echoResponse(200, $response);
          }

      }else
      {
          //no hay activos 
          $response = array();
          $response["status"] = 3;
          $response["message"] = "Procesors is not active Please contact webmaster! Thank you!";
          echoResponse(200, $response);
      }
      
}else
{
    //no existe o no esta activo 
    $response = array();
    $response["status"] = 2;
    $response["message"] = "Currency not allowed or is not active Please contact webmaster! Thank you!";
    echoResponse(200, $response);
}


            }else
            {
                //no pasa nada validamos los montos 
                 //ahora verificar q el monto este permitido en el sitio 
            $montosPermitidos = explode(",", $registros[0]->amount);
            
            $buscarMontos = array_search($app->request->get('amount'), $montosPermitidos);
                if(false !== $buscarMontos){
                     //   $buscarMontos = "si hay"; el monto es permitido
                     //ahora preguntamos por los currency 
$registros_cu = $wpdb->get_results("select * from wp_billpay_currency_sites where nombre = '".$app->request->get('currency')."'  and  active = 1 and id_site = '".$registros[0]->id."' ");
                      if(count($registros_cu)>0){
                            //si existe y esta activo entonces todo bien 

                            //ahora validar los metodos de pago si estan activos o no o si hay varios 
                            $registros_pay = $wpdb->get_results("select * from wp_billpay_procesors_sites where id_site = '".$registros[0]->id."' and active = 1 ");
                            if(count($registros_pay)>0){
                                //hay activos 
                                //ahora pregunto si hay uno o mas
                                if(count($registros_pay)==1){
                                   //aqui hay uno solo por lo tanto no se usa la plantilla multiple
                                   $fechadehoy = date('Y-m-d H:i:s');
                                   $token_seguro = generar_token_seguro(40);
                                   //guardo los datos con el token generado para luego hacer la redireccion
                                   
                                   $response = array();
                                   $data = array(
                                       'invoice'=>$app->request->get('invoice'), 
                                       'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                                       'amount'=>$app->request->get('amount'),
                                       'currency'=>$app->request->get('currency'),
                                       'key'=>$app->request->get('key')
                                   );
                             //      $response["fecha"] = $fechadehoy;
                             //      $response["template"] = 1;
                                   $response["status"] = 1;
                                   $response["message"] = "success";
                                   $response["data"] = $data;
                                   //ahora guardo los datos en la tabla del token 
                                   
                                   $wpdb->insert('wp_billpay_payment_token',
                                    array(
                                        'id' => 'null',
                                        'invoice' => $app->request->get('invoice'),
                                        'amount' => $app->request->get('amount'),
                                        'currency' => $app->request->get('currency'),
                                        'publicKey' => $app->request->get('key'),
                                        'token' => $token_seguro,
                                        'procesor' =>$registros_pay[0]->nombre,
                                        'template' => 1,
                                        'fecha' => $fechadehoy,
                                        'active' => 1

                                    )); 
                                   echoResponse(200, $response);
                                    //envio los datos normales 
                                }else
                                {
                                    //aqui son mas de uno x lo tanto se usa la plantilla multiple
                                    
                                   //aqui hay uno solo por lo tanto no se usa la plantilla multiple
                                   $fechadehoy = date('Y-m-d H:i:s');
                                   $token_seguro = generar_token_seguro(40);
                                   //guardo los datos con el token generado para luego hacer la redireccion
                                   
                                   $response = array();
                                   $data = array(
                                       'invoice'=>$app->request->get('invoice'), 
                                       'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                                       'amount'=>$app->request->get('amount'),
                                       'currency'=>$app->request->get('currency'),
                                       'key'=>$app->request->get('key')
                                   );
                             //      $response["fecha"] = $fechadehoy;
                             //      $response["template"] = 1;
                                   $response["status"] = 1;
                                   $response["message"] = "success";
                                   $response["data"] = $data;
                                   //ahora guardo los datos en la tabla del token 
                                   $wpdb->insert('wp_billpay_payment_token',
                                    array(
                                        'id' => 'null',
                                        'invoice' => $app->request->get('invoice'),
                                        'amount' => $app->request->get('amount'),
                                        'currency' => $app->request->get('currency'),
                                        'publicKey' => $app->request->get('key'),
                                        'token' => $token_seguro,
                                        'procesor' =>"varios",
                                        'template' => 2,
                                        'fecha' => $fechadehoy,
                                        'active' => 1

                                    )); 
                                   echoResponse(200, $response);
                                }

                            }else
                            {
                                //no hay activos 
                                $response = array();
                                $response["status"] = 3;
                                $response["message"] = "Procesors is not active Please contact webmaster! Thank you!";
                                echoResponse(200, $response);
                            }
                            
                      }else
                      {
                          //no existe o no esta activo 
                          $response = array();
                          $response["status"] = 2;
                          $response["message"] = "Currency not allowed or is not active Please contact webmaster! Thank you!";
                          echoResponse(200, $response);
                      }
       
                }else
                {
                    //el monto no es permitido
                    $response = array();
                 //   $response["montosPermitidos"] = $montosPermitidos;
                    $response["status"] = 2;
                    $response["message"] = "Amount not allowed. Please contact webmaster! Thank you!";
                    echoResponse(200, $response);
                }
            }
           
            
        }else
        {
            $response = array();
            $response["status"] = 0;
            $response["message"] = "Goods info error. Please contact webmaster! Thank you!";
            echoResponse(200, $response);
        }
     

    }else
    {
        $response = array();
        $response["status"] = 0;
        $response["message"] = "Goods info error. Please contact webmaster! Thank you!";
        echoResponse(200, $response);
    }
});

/* Usando POST */
$app->post('/',  function() use ($app) { 
    
    global $wpdb;
    if(!empty($app->request->post('invoice'))  &&
       !empty($app->request->post('amount'))  &&
       !empty($app->request->post('currency'))  &&
       !empty($app->request->post('key'))  
       ){
        
        //verificar si el key existe en la base de datos 
        $registros = $wpdb->get_results("select * from wp_billpay_sites where publicKey = '".$app->request->post('key')."' and active = 1");
        if(count($registros)>0){
            if($registros[0]->free_quantity	== 1){
                //monto libre
                  //ahora preguntamos por los currency 
$registros_cu = $wpdb->get_results("select * from wp_billpay_currency_sites where nombre = '".$app->request->post('currency')."'  and  active = 1 and id_site = '".$registros[0]->id."' ");
if(count($registros_cu)>0){
      //si existe y esta activo entonces todo bien 

      //ahora validar los metodos de pago si estan activos o no o si hay varios 
      $registros_pay = $wpdb->get_results("select * from wp_billpay_procesors_sites where id_site = '".$registros[0]->id."' and active = 1 ");
      if(count($registros_pay)>0){
          //hay activos 
          //ahora pregunto si hay uno o mas
          if(count($registros_pay)==1){
             //aqui hay uno solo por lo tanto no se usa la plantilla multiple
             $fechadehoy = date('Y-m-d H:i:s');
             $token_seguro = generar_token_seguro(40);
             //guardo los datos con el token generado para luego hacer la redireccion
             
             $response = array();
             $data = array(
                 'invoice'=>$app->request->post('invoice'), 
                 'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                 'amount'=>$app->request->post('amount'),
                 'currency'=>$app->request->post('currency'),
                 'key'=>$app->request->post('key')
             );
       //      $response["fecha"] = $fechadehoy;
       //      $response["template"] = 1;
             $response["status"] = 1;
             $response["message"] = "success";
             $response["data"] = $data;
             //ahora guardo los datos en la tabla del token 
             
             $wpdb->insert('wp_billpay_payment_token',
              array(
                  'id' => 'null',
                  'invoice' => $app->request->post('invoice'),
                  'amount' => $app->request->post('amount'),
                  'currency' => $app->request->post('currency'),
                  'publicKey' => $app->request->post('key'),
                  'token' => $token_seguro,
                  'procesor' =>$registros_pay[0]->nombre,
                  'template' => 1,
                  'fecha' => $fechadehoy,
                  'active' => 1

              )); 
             echoResponse(200, $response);
              //envio los datos normales 
          }else
          {
              //aqui son mas de uno x lo tanto se usa la plantilla multiple
              
             //aqui hay uno solo por lo tanto no se usa la plantilla multiple
             $fechadehoy = date('Y-m-d H:i:s');
             $token_seguro = generar_token_seguro(40);
             //guardo los datos con el token generado para luego hacer la redireccion
             
             $response = array();
             $data = array(
                 'invoice'=>$app->request->post('invoice'), 
                 'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                 'amount'=>$app->request->post('amount'),
                 'currency'=>$app->request->post('currency'),
                 'key'=>$app->request->post('key')
             );
       //      $response["fecha"] = $fechadehoy;
       //      $response["template"] = 1;
             $response["status"] = 1;
             $response["message"] = "success";
             $response["data"] = $data;
             //ahora guardo los datos en la tabla del token 
             $wpdb->insert('wp_billpay_payment_token',
              array(
                  'id' => 'null',
                  'invoice' => $app->request->post('invoice'),
                  'amount' => $app->request->post('amount'),
                  'currency' => $app->request->post('currency'),
                  'publicKey' => $app->request->post('key'),
                  'token' => $token_seguro,
                  'procesor' =>"varios",
                  'template' => 2,
                  'fecha' => $fechadehoy,
                  'active' => 1

              )); 
             echoResponse(200, $response);
          }

      }else
      {
          //no hay activos 
          $response = array();
          $response["status"] = 3;
          $response["message"] = "Procesors is not active Please contact webmaster! Thank you!";
          echoResponse(200, $response);
      }
      
}else
{
    //no existe o no esta activo 
    $response = array();
    $response["status"] = 2;
    $response["message"] = "Currency not allowed or is not active Please contact webmaster! Thank you!";
    echoResponse(200, $response);
}


            }else
            {
                //no pasa nada validamos los montos 
                 //ahora verificar q el monto este permitido en el sitio 
            $montosPermitidos = explode(",", $registros[0]->amount);
            
            $buscarMontos = array_search($app->request->post('amount'), $montosPermitidos);
                if(false !== $buscarMontos){
                     //   $buscarMontos = "si hay"; el monto es permitido
                     //ahora preguntamos por los currency 
$registros_cu = $wpdb->get_results("select * from wp_billpay_currency_sites where nombre = '".$app->request->post('currency')."'  and  active = 1 and id_site = '".$registros[0]->id."' ");
                      if(count($registros_cu)>0){
                            //si existe y esta activo entonces todo bien 

                            //ahora validar los metodos de pago si estan activos o no o si hay varios 
                            $registros_pay = $wpdb->get_results("select * from wp_billpay_procesors_sites where id_site = '".$registros[0]->id."' and active = 1 ");
                            if(count($registros_pay)>0){
                                //hay activos 
                                //ahora pregunto si hay uno o mas
                                if(count($registros_pay)==1){
                                   //aqui hay uno solo por lo tanto no se usa la plantilla multiple
                                   $fechadehoy = date('Y-m-d H:i:s');
                                   $token_seguro = generar_token_seguro(40);
                                   //guardo los datos con el token generado para luego hacer la redireccion
                                   
                                   $response = array();
                                   $data = array(
                                       'invoice'=>$app->request->post('invoice'), 
                                       'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                                       'amount'=>$app->request->post('amount'),
                                       'currency'=>$app->request->post('currency'),
                                       'key'=>$app->request->post('key')
                                   );
                             //      $response["fecha"] = $fechadehoy;
                             //      $response["template"] = 1;
                                   $response["status"] = 1;
                                   $response["message"] = "success";
                                   $response["data"] = $data;
                                   //ahora guardo los datos en la tabla del token 
                                   
                                   $wpdb->insert('wp_billpay_payment_token',
                                    array(
                                        'id' => 'null',
                                        'invoice' => $app->request->post('invoice'),
                                        'amount' => $app->request->post('amount'),
                                        'currency' => $app->request->post('currency'),
                                        'publicKey' => $app->request->post('key'),
                                        'token' => $token_seguro,
                                        'procesor' =>$registros_pay[0]->nombre,
                                        'template' => 1,
                                        'fecha' => $fechadehoy,
                                        'active' => 1

                                    )); 
                                   echoResponse(200, $response);
                                    //envio los datos normales 
                                }else
                                {
                                    //aqui son mas de uno x lo tanto se usa la plantilla multiple
                                    
                                   //aqui hay uno solo por lo tanto no se usa la plantilla multiple
                                   $fechadehoy = date('Y-m-d H:i:s');
                                   $token_seguro = generar_token_seguro(40);
                                   //guardo los datos con el token generado para luego hacer la redireccion
                                   
                                   $response = array();
                                   $data = array(
                                       'invoice'=>$app->request->post('invoice'), 
                                       'payment_url'=>get_site_url().'/api/pay?token='.$token_seguro, 
                                       'amount'=>$app->request->post('amount'),
                                       'currency'=>$app->request->post('currency'),
                                       'key'=>$app->request->post('key')
                                   );
                             //      $response["fecha"] = $fechadehoy;
                             //      $response["template"] = 1;
                                   $response["status"] = 1;
                                   $response["message"] = "success";
                                   $response["data"] = $data;
                                   //ahora guardo los datos en la tabla del token 
                                   $wpdb->insert('wp_billpay_payment_token',
                                    array(
                                        'id' => 'null',
                                        'invoice' => $app->request->post('invoice'),
                                        'amount' => $app->request->post('amount'),
                                        'currency' => $app->request->post('currency'),
                                        'publicKey' => $app->request->post('key'),
                                        'token' => $token_seguro,
                                        'procesor' =>"varios",
                                        'template' => 2,
                                        'fecha' => $fechadehoy,
                                        'active' => 1

                                    )); 
                                   echoResponse(200, $response);
                                }

                            }else
                            {
                                //no hay activos 
                                $response = array();
                                $response["status"] = 3;
                                $response["message"] = "Procesors is not active Please contact webmaster! Thank you!";
                                echoResponse(200, $response);
                            }
                            
                      }else
                      {
                          //no existe o no esta activo 
                          $response = array();
                          $response["status"] = 2;
                          $response["message"] = "Currency not allowed or is not active Please contact webmaster! Thank you!";
                          echoResponse(200, $response);
                      }
       
                }else
                {
                    //el monto no es permitido
                    $response = array();
                 //   $response["montosPermitidos"] = $montosPermitidos;
                    $response["status"] = 2;
                    $response["message"] = "Amount not allowed. Please contact webmaster! Thank you!";
                    echoResponse(200, $response);
                }
            }
           
            
        }else
        {
            $response = array();
            $response["status"] = 0;
            $response["message"] = "Goods info error. Please contact webmaster! Thank you!";
            echoResponse(200, $response);
        }
     

    }else
    {
        $response = array();
        $response["status"] = 0;
        $response["message"] = "Goods info error. Please contact webmaster! Thank you!";
        echoResponse(200, $response);
    }
});

/* corremos la aplicación */
$app->run();

/*********************** USEFULL FUNCTIONS **************************************/

/**
 * Verificando los parametros requeridos en el metodo o endpoint
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
 
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        
        $app->stop();
    }
}
 
/**
 * Validando parametro email si necesario; un Extra ;)
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoResponse(400, $response);
        
        $app->stop();
    }
}
 
/**
 * Mostrando la respuesta en formato json al cliente o navegador
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
    
}

/**
 * Agregando un leyer intermedio e autenticación para uno o todos los metodos, usar segun necesidad
 * Revisa si la consulta contiene un Header "Authorization" para validar
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();
 
    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        //$db = new DbHandler(); //utilizar para manejar autenticacion contra base de datos
 
        // get the api key
        $token = $headers['Authorization'];
        
        // validating api key
        if (!($token == API_KEY)) { //API_KEY declarada en Config.php
            
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Acceso denegado. Token inválido";
            echoResponse(401, $response);
            
            $app->stop(); //Detenemos la ejecución del programa al no validar
            
        } else {
            //procede utilizar el recurso o metodo del llamado
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Falta token de autorización";
        echoResponse(400, $response);
        
        $app->stop();
    }
}


?>