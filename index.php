<!DOCTYPE html>
<?php
/**
 *  @version 1.1.0
 *  @license MIT
 *  @author Unknown
 *  @author Kyto ( https://github.com/Kytoh )
 */
?>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Secure Payment Form</title>
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/Payments.css">
<link rel="stylesheet" href="css/bootstrap-formhelpers-min.css" media="screen">
<link rel="stylesheet" href="css/bootstrapValidator-min.css"/>
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" />
<link rel="stylesheet" href="css/bootstrap-side-notes.css" />
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/bootstrap-formhelpers-min.js"></script>
<script type="text/javascript" src="js/bootstrapValidator-min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#payment-form').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		submitHandler: function(validator, form, submitButton) {
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.card.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val(),
			name: $('.card-holder-name').val(),
			address_line1: $('.address').val(),
			address_city: $('.city').val(),
			address_zip: $('.zip').val(),
			address_state: $('.state').val(),
			address_country: $('.country').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
        },
        fields: {
            street: {
                validators: {
                    notEmpty: {
                        message: 'The street is required and cannot be empty'
                    },
					stringLength: {
                        min: 6,
                        max: 96,
                        message: 'The street must be more than 6 and less than 96 characters long'
                    }
                }
            },
            quantity: {
                validators: {
                    notEmpty: {
                        message: 'You must insert the quantity you have received'
                    },
                    digits: {
                        message: 'The quantity can contain digits only'
                    }
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: 'The city is required and cannot be empty'
                    }
                }
            },
			zip: {
                validators: {
                    notEmpty: {
                        message: 'The zip is required and cannot be empty'
                    },
					stringLength: {
                        min: 3,
                        max: 9,
                        message: 'The zip must be more than 3 and less than 9 characters long'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required and can\'t be empty'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    },
					stringLength: {
                        min: 6,
                        max: 65,
                        message: 'The email must be more than 6 and less than 65 characters long'
                    }
                }
            },
			cardholdername: {
                validators: {
                    notEmpty: {
                        message: 'The card holder name is required and can\'t be empty'
                    },
					stringLength: {
                        min: 6,
                        max: 70,
                        message: 'The card holder name must be more than 6 and less than 70 characters long'
                    }
                }
            },
			cardnumber: {
		selector: '#cardnumber',
                validators: {
                    notEmpty: {
                        message: 'The credit card number is required and can\'t be empty'
                    },
					creditCard: {
						message: 'The credit card number is invalid'
					},
                }
            },
			expMonth: {
                selector: '[data-stripe="exp-month"]',
                validators: {
                    notEmpty: {
                        message: 'The expiration month is required'
                    },
                    digits: {
                        message: 'The expiration month can contain digits only'
                    },
                    callback: {
                        message: 'Expired',
                        callback: function(value, validator) {
                            value = parseInt(value, 10);
                            var year         = validator.getFieldElements('expYear').val(),
                                currentMonth = new Date().getMonth() + 1,
                                currentYear  = new Date().getFullYear();
                            if (value < 0 || value > 12) {
                                return false;
                            }
                            if (year == '') {
                                return true;
                            }
                            year = parseInt(year, 10);
                            if (year > currentYear || (year == currentYear && value > currentMonth)) {
                                validator.updateStatus('expYear', 'VALID');
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            },
            expYear: {
                selector: '[data-stripe="exp-year"]',
                validators: {
                    notEmpty: {
                        message: 'The expiration year is required'
                    },
                    digits: {
                        message: 'The expiration year can contain digits only'
                    },
                    callback: {
                        message: 'Expired',
                        callback: function(value, validator) {
                            value = parseInt(value, 10);
                            var month        = validator.getFieldElements('expMonth').val(),
                                currentMonth = new Date().getMonth() + 1,
                                currentYear  = new Date().getFullYear();
                            if (value < currentYear || value > currentYear + 100) {
                                return false;
                            }
                            if (month == '') {
                                return false;
                            }
                            month = parseInt(month, 10);
                            if (value > currentYear || (value == currentYear && month > currentMonth)) {
                                validator.updateStatus('expMonth', 'VALID');
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            },
			cvv: {
		selector: '#cvv',
                validators: {
                    notEmpty: {
                        message: 'The cvv is required and can\'t be empty'
                    },
					cvv: {
                        message: 'The value is not a valid CVV',
                        creditCardField: 'cardnumber'
                    }
                }
            },
        }
    });
});
</script>
<script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('<public key>');

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
					// show hidden div
					document.getElementById('a_x200').style.display = 'block';
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }


</script>
</head>

<form action="" method="POST" id="payment-form" class="form-horizontal">
    <div class="page-header">
        <h2 style="text-align:center;" class="gdfg">Sistema de Pago</h2>
    </div>
  <noscript>
  <div class="bs-callout bs-callout-danger">
    <h4>JavaScript is not enabled!</h4>
    <p>This payment form requires your browser to have JavaScript enabled. Please activate JavaScript and reload this page. Check <a href="http://enable-javascript.com" target="_blank">enable-javascript.com</a> for more informations.</p>
  </div>
  </noscript>
  <?php
  require 'lib/Stripe.php';

  if (isset($_GET['token'])) {
    null;
  }
if ($_POST) {
      Stripe::setApiKey("<secret Key>");
    $error = '';
      $success = '';

      try {
          if (empty($_POST['street']) || empty($_POST['city']) || empty($_POST['zip']))
              throw new Exception("Rellena todos los campos.");
          if (!isset($_POST['stripeToken']))
              throw new Exception("Ha fallado algo al proceder al pago");
          Stripe_Charge::create(array("amount" => $_POST['quantity'] . '00',
              "currency" => "eur",
              "card" => $_POST['stripeToken'],
              "description" => $_POST['email']));
          $success = '<div class="alert alert-success">
                <strong>Pago Correcto!</strong> El pago ha sido procesado correctamente..
				</div>';
      } catch (Exception $e) {
          $error = '<div class="alert alert-danger">
			  <strong>Error!</strong> ' . $e->getMessage() . '
			  </div>';
      }
  }
  ?>
  <div class="alert alert-danger" id="a_x200" style="display: none;"> <strong>Error!</strong> <span class="payment-errors"></span> </div>
  <span class="payment-success">
      <?php $success ?>
      <?php $error ?>
  </span>
  <div class="container">
      <fieldset class="">
          <!-- Form Name -->
      <legend>Datos de Pago</legend>

      <!-- Street -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">Calle</label>
          <div class="col-sm-6">
              <input type="text" name="street" placeholder="Street" class="address form-control">
          </div>
      </div>

      <!-- City -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">Ciudad</label>
          <div class="col-sm-6">
              <input type="text" name="city" placeholder="City" class="city form-control">
          </div>
      </div>

      <!-- State -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">Estado / Comunidad Autonoma</label>
          <div class="col-sm-6">
              <input type="text" name="state" maxlength="65" placeholder="State" class="state form-control">
          </div>
      </div>

      <!-- Postcal Code -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">Codigo Postal</label>
          <div class="col-sm-6">
              <input type="text" name="zip" maxlength="9" placeholder="Postal Code" class="zip form-control">
          </div>
      </div>

      <!-- Country -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">Pais</label>
          <div class="col-sm-6">
              <!--input type="text" name="country" placeholder="Country" class="country form-control"-->
              <div class="country bfh-selectbox bfh-countries" name="country" placeholder="Select Country" data-flags="true" data-filter="true"> </div>
          </div>
      </div>

      <!-- Email -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">Correo Electr&oacute;nico</label>
          <div class="col-sm-6">
              <input type="text" name="email" maxlength="65" placeholder="Email" class="email form-control">
          </div>
      </div>
  </fieldset>
      <fieldset>
          <legend>Datos de la tarjeta de Pago</legend>
      <!-- Card Holder Name -->
      <div class="form-group">
          <label class="col-sm-4 control-label"  for="textinput">Quantity</label>
          <div class="col-sm-6">
              <input type="text" name="quantity" maxlength="70" placeholder="Cantidad" value="<?php if (isset($_GET['q'])) {
      echo $_GET['q'];
  } ?>" class="quantity-name form-control">
          </div>
      </div>

      <!-- Card Holder Name -->
      <div class="form-group">
          <label class="col-sm-4 control-label"  for="textinput">Nombre y Appelidos</label>
          <div class="col-sm-6">
              <input type="text" name="cardholdername" maxlength="70" placeholder="Card Holder Name" class="card-holder-name form-control">
          </div>
      </div>

      <!-- Card Number -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">N&uacute;mero de la Tarjeta</label>
          <div class="col-sm-6">
              <input type="text" id="cardnumber" maxlength="19" placeholder="Card Number" class="card-number form-control">
          </div>
      </div>

      <!-- Expiry-->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">Fecha de Expiración</label>
          <div class="col-sm-6">
              <div class="form-inline">
                  <select name="select2" data-stripe="exp-month" class="card-expiry-month stripe-sensitive required form-control">
                      <option value="01" selected="selected">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                  </select>
                  <span> / </span>
                  <select name="select2" data-stripe="exp-year" class="card-expiry-year stripe-sensitive required form-control">
                  </select>
                  <script type="text/javascript">
                    var select = $(".card-expiry-year"),
                    year = new Date().getFullYear();

                    for (var i = 0; i < 12; i++) {
                        select.append($("<option value='"+(i + year)+"' "+(i === 0 ? "selected" : "")+">"+(i + year)+"</option>"))
                    }
                  </script>
              </div>
          </div>
      </div>

      <!-- CVV -->
      <div class="form-group">
          <label class="col-sm-4 control-label" for="textinput">CVV/CVV2</label>
          <div class="col-sm-3">
              <input type="text" id="cvv" placeholder="CVV" maxlength="4" class="card-cvc form-control">
          </div>
      </div>
      </fieldset>
      <!-- Notice final y boton de pagar -->
      <div class="full-width">
          <!-- Important notice -->
      <div class="form-group">
          <div class="panel panel-success">
              <div class="panel-heading">
                  <h3 class="panel-title">Noticia Importante</h3>
              </div>
              <div class="panel-body">
                  <p>Seg&uacute;n has indicado, se te cobrará <span class="valor_cobro"></span>€ en tu cuenta bancaria</p>
                  <p>En tu cuenta aparecerá de la siguiente forma:
                      KCSystem </p>
              </div>
          </div>

          <!-- Submit -->
          <div class="control-group">
              <div class="controls">
                  <center>
                      <button class="btn btn-success" type="submit">Pagar Ahora</button>
                  </center>
              </div>
          </div>
      </div>
    </div>
</form>
<a href="admin/index.php" style="position:absolute;bottom:5px;left:5px">Control Panel</a>