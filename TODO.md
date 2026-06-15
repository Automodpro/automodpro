# TODO - Refactor edición de perfil (Usuarios)

- [x] Reescribir `app/Controllers/Usuarios.php::actualizar($id)` separando lógica: nombre/rol, correo, contraseña.
- [x] Reescribir `app/Views/usuarios/form.php`:
  - [x] Rol: editable solo admin, readonly si no admin.
  - [x] Correo actual readonly.
  - [x] Secciones/tarjetas separadas para Seguridad: Contraseña y Correo.
  - [x] Usar campos `nuevo_correo`/`confirmar_nuevo_correo` (no `correo`).
- [x] Ajustar `app/Views/usuarios/index.php` para mapear nombres de rol: admin->Administrador, mecanico->Mecánico, etc.
- [x] Confirmar que `index()` usa JOIN roles y muestra nombre real del rol.
- [ ] Validar comportamiento manual (o con tests si existen):
  - [ ] Solo nombre => no pide contraseña.
  - [ ] Solo correo => exige contraseña_actual y password_verify.
  - [ ] Solo contraseña => exige contraseña_actual y password_verify.
  - [ ] Cambio de rol => solo si session('rol') === 'admin'.

