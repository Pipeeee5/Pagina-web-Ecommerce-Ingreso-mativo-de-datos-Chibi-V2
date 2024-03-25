<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago</title>


    <script
        src="https://www.paypal.com/sdk/js?client-id=AQceuJLPSJyKajP3Af8Vc1JSlfs_8DDhBq0faLjLk-JXhpDqwthrYL-Unzwo_6h7Hb_NCFbS8d2pNY91&currency=USD"></script>

</head>

<body>
    <div id="paypal-button-container"></div>
    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: 100
                        }
                    }]
                })

            },
            onApprove: function (data, actions) {
                actions.order.capture().then(function (detalles) {
                    console.log(detalles);
                });
            },
            onCancel: function (data) {
                alert("pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>

</body>

</html>