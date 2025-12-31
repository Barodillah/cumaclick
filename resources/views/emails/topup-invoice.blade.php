<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Top Up Invoice</title>
</head>
<body style="font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0;">

    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px;">
        <tr>
            <td style="text-align: center; padding-bottom: 20px;">
                <h2 style="margin: 0; color: #2d3436;">Top Up Coins Invoice</h2>
            </td>
        </tr>
        <tr>
            <td style="padding-bottom: 20px;">
                <p style="margin: 0; font-size: 16px; color: #636e72;">
                    Hello <strong>{{ $topup->user->name }}</strong>,
                </p>
                <p style="margin: 5px 0 0 0; font-size: 16px; color: #636e72;">
                    Thank you for topping up. Please find your invoice details below and proceed with the payment.
                </p>
            </td>
        </tr>

        <tr>
            <td>
                <table width="100%" cellpadding="10" cellspacing="0" style="border: 1px solid #dfe6e9; border-radius: 5px; margin-bottom: 20px;">
                    <tr style="background-color: #f1f2f6;">
                        <td><strong>Order ID</strong></td>
                        <td>{{ $topup->order_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $topup->user->email }}</td>
                    </tr>
                    <tr style="background-color: #f1f2f6;">
                        <td><strong>Amount</strong></td>
                        <td>Rp {{ number_format($topup->gross_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Coins</strong></td>
                        <td>{{ $coins }}</td>
                    </tr>
                    <tr style="background-color: #f1f2f6;">
                        <td><strong>Status</strong></td>
                        <td style="color: #e17055;"><strong>Pending Payment</strong></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding-bottom: 20px;">
                <p style="margin: 0; font-size: 16px; color: #636e72;">
                    Once your payment is completed, your coins will be credited automatically to your account.
                </p>
            </td>
        </tr>

        <tr>
            <td style="font-size: 12px; color: #b2bec3; text-align: center; padding-top: 20px;">
                This is an automated email. Please do not reply.
            </td>
        </tr>
    </table>

</body>
</html>
