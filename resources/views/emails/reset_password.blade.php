<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register HTML</title>
</head>
<body>
    <table>
        <tr>
            <td>Dear User</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Click on the below link to reset your Password :</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><a href="{{ url('user/reset-password/'.$code) }}">Reset Password</a></td>
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
