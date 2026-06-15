<?php
namespace App\Controllers;

use App\Models\PersonalizacionModel;

class Personalizador extends BaseController
{
    public function index()
    {
        $model = new PersonalizacionModel();
        $data['personalizacion'] = $model->where('usuario_id', session('id'))->first();
        return view('personalizador/index', $data);
    }

    public function guardar()
    {
        $model = new PersonalizacionModel();
        $data = [
            'usuario_id' => session('id'),
            'color_carro' => $this->request->getPost('color_carro') ?? '#e74c3c',
            'color_ruedas' => $this->request->getPost('color_ruedas') ?? '#2c3e50',
            'color_vidrios' => $this->request->getPost('color_vidrios') ?? '#85c1e9',
            'tipo_aleron' => $this->request->getPost('tipo_aleron') ?? 'ninguno',
            'tipo_faros' => $this->request->getPost('tipo_faros') ?? 'normales',
        ];

        $existing = $model->where('usuario_id', session('id'))->first();
        if ($existing) {
            $model->update($existing['id'], $data);
        } else {
            $model->insert($data);
        }

        return redirect()->to('/personalizador')->with('success', 'Personalización guardada');
    }
}
