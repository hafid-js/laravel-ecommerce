<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register HTML</title>
</head>
<body>
    <table>
        <tr>
            <td>Dear {{  $name }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Welcoe to Lektumbas.id. Your account information is as below :</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><a href="{{ url('user/confirm/'.$code) }}">Confirm Account</a></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Thanks & Regards,</td>
        </tr>
        <tr>
            <td>Lektumbas.id</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
</body>
</html>
