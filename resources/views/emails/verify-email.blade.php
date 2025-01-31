<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de E-mail - EducrIA</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F9F4FE; font-family: Arial, sans-serif; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: white; margin: 20px; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding: 20px 0; background-color: white;">
                            <img src="https://sxrkcbzuiybsrikmvyrm.supabase.co/storage/v1/object/public/image/logo.png" alt="EducrIA Logo" style="width: 150px;">
                        </td>
                    </tr>

                    <!-- Conteúdo -->
                    <tr>
                        <td style="padding: 30px; text-align: center;">
                            <h1 style="color: #000;">Bem-vindo(a) ao EducrIA!</h1>
                            <p style="font-size: 16px; color: #000; line-height: 1.5; margin: 20px 0;">
                                Olá, {{ $user->name }}! Estamos felizes por você estar aqui. Antes de começar, precisamos verificar seu endereço de e-mail.
                            </p>
                            <!-- Botão de validação -->
                            <a href="{{ $url }}" target="_blank" style="display: inline-block; background-color: #A480F2; color: white; text-decoration: none; padding: 15px 30px; font-size: 16px; border-radius: 5px; margin: 20px 0; font-weight: bold;">
                                Validar E-mail
                            </a>
                            <p style="font-size: 14px; line-height: 1.5; color: #666; margin: 20px 0;">
                                Caso não consiga clicar no botão, copie e cole o link abaixo em seu navegador:
                            </p>
                            <p style="font-size: 14px; line-height: 1.5; color: #666; word-break: break-word;">
                                <a href="{{ $url }}" style="color: #F2622E;">{{ $url }}</a>
                            </p>
                            <p style="font-size: 14px; color: #666; margin: 20px 0;">
                                — Team EducrIA
                            </p>
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td align="center" style="background-color: #A480F2; padding: 15px;">
                            <p style="font-size: 14px; color: white; font-weight: 700;">
                                &copy; {{ date('Y') }} EducrIA. Todos os direitos reservados.<br>
                                EducrIA é uma marca registrada.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
