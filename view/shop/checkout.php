<?php
session_start();
include '../../db_connect.php';
require '../../vendor/autoload.php'; // Certifique-se de que o SDK do Mercado Pago está instalado


// Redirecione para a página de login se o usuário não estiver logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit();
}

// Inicialize variáveis
$total = 0;
$cart = [];
    $nome = $_SESSION['cliente_nome'] ?? '';
    $email = $_SESSION['cliente_email'] ?? '';
$cliente_id = $_SESSION['cliente_id'];

// Verifique se há dados enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receba os dados do carrinho
    $total = isset($_POST['total']) ? $_POST['total'] : 0;
    $cart = isset($_POST['cart']) ? $_POST['cart'] : [];

    // Se os dados do carrinho e do pedido estiverem presentes, prossiga para salvar no banco de dados
    if (!empty($cart) && isset($cliente_id, $_POST['endereco'], $_POST['telefone'])) {
        // Salve os dados do pedido no banco de dados
                $endereco = $conn->real_escape_string($_POST['endereco']);
        $telefone = $conn->real_escape_string($_POST['telefone']);

        // Crie o pagamento
        $payment->transaction_amount = $total;
        $payment->description = "Compra na GL Pharma";
        $payment->payment_method_id = "pix"; // Exemplo: PIX, altere conforme necessário
        $payment->payer = array(
            "email" => $_SESSION['cliente_email'],
            "first_name" => $_SESSION['cliente_nome'],
        );

        $payment->save();

        if ($payment->status == 'approved') {
            // Salve o pedido no banco de dados
        $stmt_pedido = $conn->prepare("INSERT INTO pedidos (cliente_id, endereco, telefone, total) VALUES (?, ?, ?, ?)");
        $stmt_pedido->bind_param("issd", $_SESSION['cliente_id'], $endereco, $telefone, $total);

        if ($stmt_pedido->execute()) {
            $pedido_id = $stmt_pedido->insert_id;

            // Salve os itens do pedido
            $stmt_itens = $conn->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade) VALUES (?, ?, ?)");
            foreach ($_POST['cart'] as $product_id => $quantity) {
                $stmt_itens->bind_param("iii", $pedido_id, $product_id, $quantity);
                                    $stmt_itens->execute();
                            }

            // Redirecione para a página de confirmação
            header('Location: confirmacao.php');
            exit();
        } else {
            echo "Erro ao salvar pedido: " . $stmt_pedido->error;
        }
} else {
            echo "Erro no pagamento: " . $payment->status_detail;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout</title>
<link rel="stylesheet" href="../../css/checkout.css">
<link rel="stylesheet" href="../../js/payment.js">
<script src="https://sdk.mercadopago.com/js/v2"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
<main>
    <!-- Hidden input to store your integration public key -->
    <input type="hidden" id="mercado-pago-public-key" value="{{ public_key }}">

    <!-- Shopping Cart -->
    <section class="shopping-cart dark">
        <div class="container container__cart">
            <div class="block-heading">
                <h2>Shopping Cart</h2>
                <p>This is an example of a Mercado Pago integration</p>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="items">
                            <div class="product">
                                <div class="info">
                                    <div class="product-details">
                                        <div class="row justify-content-md-center">
                                            <div class="col-md-3">
                                                <img class="img-fluid mx-auto d-block image" src="img/product.png">
                                            </div>
                                            <div class="col-md-4 product-detail">
                                                <h5>Product</h5>
                                                <div class="product-info">
                                                    <p><b>Description: </b><span id="product-description">Some book</span><br>
                                                        <b>Author: </b>Dale Carnegie<br>
                                                        <b>Number of pages: </b>336<br>
                                                        <b>Price:</b> $ <span id="unit-price">10</span></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 product-detail">
                                                <label for="quantity"><h5>Quantity</h5></label>
                                                <input type="number" id="quantity" value="1" min="1" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="summary">
                            <h3>Cart</h3>
                            <div class="summary-item"><span class="text">Subtotal</span><span class="price" id="cart-total"></span></div>
                            <button class="btn btn-primary btn-lg btn-block" id="checkout-btn">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Payment -->
    <section class="payment-form dark">
        <div class="container__payment">
            <div class="block-heading">
                <h2>Card Payment</h2>
                <p>This is an example of a Mercado Pago integration</p>
            </div>
            <div class="form-payment">
                <div class="products">
                    <h2 class="title">Summary</h2>
                    <div class="item">
                        <span class="price" id="summary-price"></span>
                        <p class="item-name">Book x <span id="summary-quantity"></span></p>
                    </div>
                    <div class="total">Total<span class="price" id="summary-total"></span></div>
                </div>
                <div class="payment-details">
                    <form id="form-checkout">
                        <input id="form-checkout__cardholderName" name="cardholderName" type="text" class="form-control" />
                        <input id="form-checkout__cardholderEmail" name="cardholderEmail" type="email" class="form-control" />
                        <div id="form-checkout__cardNumber" class="form-control"></div>
                        <div id="form-checkout__expirationDate" class="form-control"></div>
                        <div id="form-checkout__securityCode" class="form-control"></div>
                        <select id="form-checkout__installments" name="installments" class="form-control"></select>
                        <select id="form-checkout__identificationType" name="identificationType" class="form-control"></select>
                        <input id="form-checkout__identificationNumber" name="identificationNumber" type="text" class="form-control" />
                        <select id="form-checkout__issuer" name="issuer" class="form-control"></select>
                        <input id="amount" type="hidden" value="<?= htmlspecialchars($total) ?>" />
                        <button id="form-checkout__submit" type="submit" class="btn btn-primary btn-block">Pagar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Result -->
    <section class="shopping-cart dark">
        <div class="container container__result">
            <div class="block-heading">
                <h2>Payment Result</h2>
                <p>This is an example of a Mercado Pago integration</p>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="items product info product-details">
                            <div class="row justify-content-md-center">
                                <div class="col-md-4 product-detail">
                                    <div class="product-info">
                                        <div id="fail-response">
                                            <br/>
                                            <img src="img/fail.png" width="350px">
                                            <p class="text-center font-weight-bold">Something went wrong</p>
                                            <p id="error-message" class="text-center"></p>
                                            <br/>
                                        </div>

                                        <div id="success-response">
                                            <br/>
                                            <p><b>ID: </b><span id="payment-id"></span></p>
                                            <p><b>Status: </b><span id="payment-status"></span></p>
                                            <p><b>Detail: </b><span id="payment-detail"></span></p>
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<footer>
    <div class="footer_logo"><img id="horizontal_logo" src="img/horizontal_logo.png"></div>
    <div class="footer_text">
        <p>Developers Site:</p>
        <p><a href="https://www.mercadopago.com/developers">https://www.mercadopago.com/developers</a></p>
    </div>
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mercadopago = new MercadoPago('YOUR_PUBLIC_KEY', { locale: 'pt-BR' });

        const cardForm = mercadopago.cardForm({
            amount: document.getElementById('amount').value,
            autoMount: true,
            form: {
                id: "form-checkout",
                cardholderName: {
                    id: "form-checkout__cardholderName",
                    placeholder: "Nome no cartão",
                },
                cardholderEmail: {
                    id: "form-checkout__cardholderEmail",
                    placeholder: "E-mail",
                },
                cardNumber: {
                    id: "form-checkout__cardNumber",
                    placeholder: "Número do cartão",
                },
                expirationDate: {
                    id: "form-checkout__expirationDate",
                    placeholder: "MM/AA",
                },
                securityCode: {
                    id: "form-checkout__securityCode",
                    placeholder: "CVV",
                },
                installments: {
                    id: "form-checkout__installments",
                    placeholder: "Parcelas",
                },
                identificationType: {
                    id: "form-checkout__identificationType",
                },
                identificationNumber: {
                    id: "form-checkout__identificationNumber",
                    placeholder: "CPF",
                },
                issuer: {
                    id: "form-checkout__issuer",
                    placeholder: "Banco emissor",
                },
            },
            callbacks: {
                onFormMounted: (error) => {
                    if (error) {
                        console.error("Erro ao montar o formulário:", error);
                    } else {
                        console.log("Formulário montado com sucesso!");
                    }
                },
                onSubmit: (event) => {
                    event.preventDefault();
                    const formData = cardForm.getCardFormData();
                    console.log("Dados do formulário:", formData);

                    // Enviar os dados para o backend
                    fetch("/process_payment", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(formData),
                    })
                        .then((response) => response.json())
                        .then((result) => {
                            console.log("Resultado do pagamento:", result);
                            if (result.status === "approved") {
                                document.getElementById("success-response").style.display = "block";
                                document.getElementById("payment-id").innerText = result.id;
                                document.getElementById("payment-status").innerText = result.status;
                                document.getElementById("payment-detail").innerText = result.detail;
                            } else {
                                document.getElementById("fail-response").style.display = "block";
                                document.getElementById("error-message").innerText = result.error_message;
                            }
                        })
                        .catch((error) => {
                            console.error("Erro ao processar o pagamento:", error);
                        });
                },
            },
        });
    });
</script>
</body>
</html>
