<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eef2f7; border-radius: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { height: 40px; }
        .content { background: #fdfdfd; padding: 30px; border-radius: 8px; border: 1px solid #f1f5f9; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #1a9a8a; color: #ffffff !important; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ config('app.url') }}/images/logo-viantryp.png" alt="Viantryp" class="logo">
        </div>
        <div class="content">
            <h2>¡Hola!</h2>
            <p>Has sido invitado a colaborar en el viaje <strong>{{ $trip->title }}</strong> por parte de <strong>{{ $trip->user->display_name }}</strong>.</p>
            
            <p>Has recibido permisos de <strong>{{ $role === 'editor' ? 'Editor' : 'Lector' }}</strong>.</p>
            
            <div style="text-align: center;">
                <a href="{{ $inviteUrl }}" class="btn">Aceptar Invitación</a>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #64748b;">Si no esperabas esta invitación, puedes ignorar este correo.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Viantryp. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
