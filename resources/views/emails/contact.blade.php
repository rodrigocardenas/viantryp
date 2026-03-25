<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje de Contacto</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f5f9; margin: 0; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #1a7a8a; margin: 0;">¡Nuevo mensaje recibido!</h2>
        </div>
        <div style="margin-bottom: 20px; border-bottom: 1px solid #e1e4e8; padding-bottom: 20px;">
            <p style="margin: 0 0 10px;"><strong>Nombre:</strong> {{ $data['name'] }}</p>
            <p style="margin: 0 0 10px;"><strong>Agencia / Empresa:</strong> {{ $data['agency'] ?? 'N/A' }}</p>
            <p style="margin: 0 0 10px;"><strong>Email:</strong> <a href="mailto:{{ $data['email'] }}" style="color: #1a7a8a;">{{ $data['email'] }}</a></p>
            <p style="margin: 0 0 10px;"><strong>Motivo:</strong> {{ $data['reason'] ?? 'N/A' }}</p>
        </div>
        <div>
            <h3 style="color: #1a7a8a; margin-top: 0; margin-bottom: 15px;">Mensaje:</h3>
            <p style="background: #f9f9f9; padding: 15px; border-radius: 5px; line-height: 1.6; margin: 0;">{{ nl2br(e($data['message'])) }}</p>
        </div>
        <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #888;">
            <p>Este es un correo automático generado desde el formulario de contacto de Viantryp.</p>
        </div>
    </div>
</body>
</html>
