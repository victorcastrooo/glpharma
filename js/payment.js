document.addEventListener("DOMContentLoaded", function () {
    const mercadopago = new MercadoPago('TEST-24ccdcc5-b8c3-4ea2-b72d-bb7f6420ecff', { locale: 'pt-BR' });

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

function removeFieldErrorMessages(input, validationErrorMessages) {
    Array.from(validationErrorMessages.children).forEach(child => {
        const shouldRemoveChild = child.id.includes(input.id);
        if (shouldRemoveChild) {
            validationErrorMessages.removeChild(child);
        }
    });
}

function addFieldErrorMessages(input, validationErrorMessages, error) {
    if (error) {
        input.classList.add('validation-error');
        error.forEach((e, index) => {
            const p = document.createElement('p');
            p.id = `${input.id}-${index}`;
            p.innerText = e.message;
            validationErrorMessages.appendChild(p);
        });
    } else {
        input.classList.remove('validation-error');
    }
}

function enableOrDisablePayButton(validationErrorMessages, payButton) {
    if (validationErrorMessages.children.length > 0) {
        payButton.setAttribute('disabled', true);
    } else {
        payButton.removeAttribute('disabled');
    }
}

// Handle transitions
document.getElementById('checkout-btn').addEventListener('click', function(){
    $('.container__cart').fadeOut(500);
    setTimeout(() => {
        loadCardForm();
        $('.container__payment').show(500).fadeIn();
    }, 500);
});

document.getElementById('go-back').addEventListener('click', function(){
    $('.container__payment').fadeOut(500);
    setTimeout(() => { $('.container__cart').show(500).fadeIn(); }, 500);
});

// Handle price update
function updatePrice(){
    let quantity = document.getElementById('quantity').value;
    let unitPrice = document.getElementById('unit-price').innerText;
    let amount = parseInt(unitPrice) * parseInt(quantity);

    document.getElementById('cart-total').innerText = '$ ' + amount;
    document.getElementById('summary-price').innerText = '$ ' + unitPrice;
    document.getElementById('summary-quantity').innerText = quantity;
    document.getElementById('summary-total').innerText = '$ ' + amount;
    document.getElementById('amount').value = amount;
};

document.getElementById('quantity').addEventListener('change', updatePrice);
updatePrice();