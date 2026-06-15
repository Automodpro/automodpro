<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;

class Configuracion extends BaseController
{
    public function index()
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/dashboard')->with('error', 'No tienes permisos');
        }

        $model = new ConfiguracionModel();
        $configs = $model->findAll();
        $data['config'] = [];
        foreach ($configs as $c) {
            $data['config'][$c['clave']] = $c['valor'];
        }

        $data['verified'] = session()->get('config_verified');
        return view('configuracion/index', $data);
    }

    public function verify()
    {
        $model = new ConfiguracionModel();
        $adminPass = $model->getValor('admin_password_config');
        $ingresada = $this->request->getPost('password');

        if ($ingresada === $adminPass) {
            session()->set('config_verified', true);
            return redirect()->to('/configuracion');
        }

        return redirect()->to('/configuracion')->with('error', 'Contraseña incorrecta');
    }

    public function update()
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/dashboard');
        }

        if (!session()->get('config_verified') && session('rol') !== 'admin') {
            return redirect()->to('/configuracion')->with('error', 'Debe verificar su identidad');
        }

        $model = new ConfiguracionModel();
        $claves = ['nombre_taller', 'direccion', 'telefono', 'email_contacto', 'iva', 'moneda', 'horario'];

        foreach ($claves as $clave) {
            $valor = $this->request->getPost($clave);
            if ($valor !== null) {
                $existing = $model->where('clave', $clave)->first();
                if ($existing) {
                    $model->update($existing['id'], ['valor' => $valor]);
                } else {
                    $model->insert(['clave' => $clave, 'valor' => $valor]);
                }
            }
        }

        return redirect()->to('/configuracion')->with('success', 'Configuración actualizada');
    }

    public function lock()
    {
        session()->remove('config_verified');
        return redirect()->to('/configuracion');
    }
}
