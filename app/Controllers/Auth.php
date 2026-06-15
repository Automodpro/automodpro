<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Auth extends BaseController
{
    public function login()
    {
        if (session('logueado')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function doLogin()
    {
        $correo = trim((string) $this->request->getPost('correo'));
        $password = (string) $this->request->getPost('password');

        if ($correo === '' || $password === '') {
            return redirect()->back()->with('error', 'Debes completar todos los campos');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Correo inválido');
        }

        if (mb_strlen($password) < 6) {
            return redirect()->back()->with('error', 'La contraseña debe tener mínimo 6 caracteres');
        }

        $modelo = new UsuarioModel();
        $usuario = $modelo
            ->select('id, nombre_usuario, correo, rol_id, contrasena, email_verified')
            ->where('correo', $correo)
            ->first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'Credenciales inválidas');
        }

        $hash = (string) ($usuario['contrasena'] ?? '');
        if ($hash === '' || !password_verify($password, $hash)) {
            return redirect()->back()->with('error', 'Credenciales inválidas');
        }

        if (!$usuario['email_verified']) {
            return redirect()->back()->with('error', 'Debes verificar tu correo antes de iniciar sesión. Revisa tu bandeja de entrada.');
        }

        $rolSlug = match ((int) ($usuario['rol_id'] ?? 0)) {
            1 => 'admin',
            2 => 'mecanico',
            3 => 'usuario',
            default => 'usuario',
        };

        session()->set([
            'id' => $usuario['id'],
            'nombre_usuario' => $usuario['nombre_usuario'],
            'nombre' => $usuario['nombre_usuario'],
            'correo' => $usuario['correo'],
            'rol_id' => $usuario['rol_id'],
            'rol' => $rolSlug,
            'logueado' => true,
        ]);
        return redirect()->to('/dashboard');
    }

    public function register()
    {
        if (session('logueado')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    public function doRegister()
    {
        $modelo = new UsuarioModel();
        $nombreCompleto = trim((string) $this->request->getPost('nombre'));
        $correo = $this->request->getPost('correo');

        $token = bin2hex(random_bytes(32));

        $data = [
            'nombre_usuario' => $nombreCompleto,
            'correo' => $correo,
            'contrasena' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'rol_id' => 3,
            'email_verified' => 0,
            'verification_token' => $token,
        ];

        if ($modelo->insert($data)) {
            if ($this->sendVerificationEmail($correo, $nombreCompleto, $token)) {
                return redirect()->to('/auth/login')->with('success', 'Registro exitoso. Revisa tu correo para verificar tu cuenta.');
            }
            return redirect()->to('/auth/login')->with('success', 'Registro exitoso. No se pudo enviar el correo de verificación, contacta al administrador.');
        }

        return redirect()->back()->with('errores', $modelo->errors());
    }

    public function verifyEmail($token = null)
    {
        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Token de verificación inválido');
        }

        $modelo = new UsuarioModel();
        $usuario = $modelo->where('verification_token', $token)->first();

        if (!$usuario) {
            return redirect()->to('/auth/login')->with('error', 'Token de verificación inválido o expirado');
        }

        $modelo->update($usuario['id'], [
            'email_verified' => 1,
            'verification_token' => null,
        ]);

        return redirect()->to('/auth/login')->with('success', 'Correo verificado exitosamente. Ya puedes iniciar sesión.');
    }

    public function googleLogin()
    {
        $idToken = $this->request->getPost('credential') ?? $this->request->getPost('id_token');
        if (!$idToken) {
            return redirect()->back()->with('error', 'Token de Google requerido');
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return redirect()->back()->with('error', 'Token de Google inválido');
        }

        $googleUser = json_decode($response, true);

        $modelo = new UsuarioModel();
        $usuario = $modelo->where('correo', $googleUser['email'])->first();

        if (!$usuario) {
            $username = explode('@', $googleUser['email'])[0];
            $username = substr(preg_replace('/[^a-zA-Z0-9_]/', '_', $username), 0, 55);

            $base = $username;
            $counter = 1;
            while ($modelo->where('nombre_usuario', $username)->first()) {
                $username = $base . '_' . $counter++;
            }

            $modelo->insert([
                'nombre_usuario' => $username,
                'correo' => $googleUser['email'],
                'contrasena' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'nombre_completo' => $googleUser['name'] ?? $googleUser['given_name'] ?? 'Usuario Google',
                'avatar_url' => $googleUser['picture'] ?? null,
                'rol_id' => 3,
                'activo' => 1,
                'email_verified' => 1,
            ]);

            $usuario = $modelo->find($modelo->insertID());
        }

        $rolSlug = match ((int) ($usuario['rol_id'] ?? 0)) {
            1 => 'admin',
            2 => 'mecanico',
            3 => 'usuario',
            default => 'usuario',
        };

        session()->set([
            'id' => $usuario['id'],
            'nombre_usuario' => $usuario['nombre_usuario'],
            'nombre' => $usuario['nombre_usuario'],
            'correo' => $usuario['correo'],
            'rol_id' => $usuario['rol_id'],
            'rol' => $rolSlug,
            'logueado' => true,
        ]);

        return redirect()->to('/dashboard');
    }

    private function sendVerificationEmail(string $email, string $nombre, string $token): bool
    {
        $verifyUrl = base_url('auth/verify/' . $token);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'automodprotec@gmail.com';
            $mail->Password = 'edyw yydi xmiw gfmd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('automodprotec@gmail.com', 'AutoMod Pro');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Confirma tu registro en AutoMod Pro';
            $mail->Body = view('emails/verify', [
                'nombre' => $nombre,
                'verifyUrl' => $verifyUrl,
            ]);

            $mail->send();
            return true;
        } catch (\Exception $e) {
            log_message('error', 'PHPMailer error a ' . $email . ': ' . $e->getMessage());
            return false;
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('logged_out', true);
    }
}
