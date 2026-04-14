<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Plan - Viantryp</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f5;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f5;padding:40px 20px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

                <!-- Header -->
                <tr>
                    <td style="background:#1a7a8a;border-radius:16px 16px 0 0;padding:28px 36px;text-align:center;">
                        <div style="font-size:24px;font-weight:900;color:#ffffff;letter-spacing:1px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">VIANTRYP</div>
                        <div style="font-size:13px;color:rgba(255,255,255,0.75);margin-top:4px;">Plataforma de Itinerarios de Viaje</div>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="background:#ffffff;padding:36px;border-left:1px solid #e0ecea;border-right:1px solid #e0ecea;">
                        <div style="font-size:11px;font-weight:700;letter-spacing:2px;color:#94a3b8;text-transform:uppercase;margin-bottom:10px;">Nueva Solicitud</div>
                        <h1 style="margin:0 0 8px;font-size:24px;font-weight:800;color:#0f172a;">Solicitud de Cambio de Plan</h1>
                        <p style="margin:0 0 28px;color:#64748b;font-size:15px;line-height:1.6;">Un usuario ha solicitado el acceso a un plan de suscripción. Aquí están los detalles de la solicitud:</p>

                        <!-- Plan Info -->
                        <div style="background:#f0f9f8;border:1px solid #1a7a8a30;border-radius:12px;padding:20px 24px;margin-bottom:24px;">
                            <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;color:#1a7a8a;text-transform:uppercase;margin-bottom:8px;">Plan Solicitado</div>
                            <div style="font-size:22px;font-weight:900;color:#1a7a8a;text-transform:uppercase;letter-spacing:1px;">{{ strtoupper($requestedPlan) }}</div>
                        </div>

                        <!-- User Details -->
                        <div style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:28px;">
                            <div style="background:#f8fafc;padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                                <span style="font-size:11px;font-weight:700;letter-spacing:1.5px;color:#94a3b8;text-transform:uppercase;">Datos del Solicitante</span>
                            </div>
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                                        <div style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Nombre</div>
                                        <div style="font-size:15px;color:#0f172a;font-weight:600;">{{ $contactName }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                                        <div style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Correo Electrónico</div>
                                        <div style="font-size:15px;color:#0f172a;font-weight:600;">
                                            <a href="mailto:{{ $contactEmail }}" style="color:#1a7a8a;text-decoration:none;">{{ $contactEmail }}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                                        <div style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Teléfono / WhatsApp</div>
                                        <div style="font-size:15px;color:#0f172a;font-weight:600;">{{ $contactPhone ?: 'No proporcionado' }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px;">
                                        <div style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Usuario Registrado (email en cuenta)</div>
                                        <div style="font-size:15px;color:#0f172a;font-weight:600;">{{ $userEmail }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style="background:#fffbeb;border:1px solid #fef3c7;border-radius:10px;padding:16px 20px;margin-bottom:28px;">
                            <p style="margin:0;font-size:14px;color:#92400e;line-height:1.6;">
                                <strong>Acción requerida:</strong> Contacta al usuario para procesar el cambio de plan y generar un código de acceso si corresponde.
                            </p>
                        </div>

                        <div style="text-align:center;">
                            <a href="mailto:{{ $contactEmail }}" style="display:inline-block;background:#1a7a8a;color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;padding:14px 28px;border-radius:100px;">
                                Responder al Solicitante →
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f8fafc;border:1px solid #e0ecea;border-top:none;border-radius:0 0 16px 16px;padding:20px 36px;text-align:center;">
                        <p style="margin:0;font-size:12px;color:#94a3b8;">Este mensaje fue generado automáticamente por Viantryp · <a href="{{ config('app.url') }}" style="color:#1a7a8a;text-decoration:none;">{{ config('app.url') }}</a></p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
