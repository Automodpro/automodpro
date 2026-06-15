-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2026 a las 19:51:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `automod_pro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_servicio`
--

CREATE TABLE `categorias_servicio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_servicio`
--

INSERT INTO `categorias_servicio` (`id`, `nombre`, `slug`, `descripcion`, `creado_en`) VALUES
(1, 'Personalización', 'personalizacion', 'Modificaciones estéticas y visuales', '2026-06-11 17:42:59'),
(2, 'Mecánica General', 'mecanica', 'Servicios de mantenimiento mecánico', '2026-06-11 17:42:59'),
(3, 'Escape y Emisiones', 'escape', 'Sistemas de escape y control de emisiones', '2026-06-11 17:42:59'),
(4, 'Suspensión', 'suspension', 'Sistemas de suspensión y dirección', '2026-06-11 17:42:59'),
(5, 'Eléctrico', 'electrico', 'Sistemas eléctricos y electrónicos', '2026-06-11 17:42:59'),
(6, 'Carrocería y Pintura', 'carroceria', 'Trabajos de carrocería y pintura', '2026-06-11 17:42:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` int(11) NOT NULL,
  `clave` varchar(80) NOT NULL,
  `valor` text NOT NULL,
  `tipo` enum('texto','numero','booleano','json') DEFAULT 'texto',
  `descripcion` text DEFAULT NULL,
  `grupo` varchar(50) DEFAULT 'general',
  `actualizado_en` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`id`, `clave`, `valor`, `tipo`, `descripcion`, `grupo`, `actualizado_en`) VALUES
(1, 'nombre_sistema', 'AutoMod Pro', 'texto', 'Nombre del sistema', 'general', NULL),
(2, 'empresa_nombre', 'AutoMod Pro SAS', 'texto', 'Nombre de la empresa', 'general', NULL),
(3, 'empresa_nit', '901.123.456-7', 'texto', 'NIT de la empresa', 'general', NULL),
(4, 'empresa_telefono', '+57 300 123 4567', 'texto', 'Teléfono de contacto', 'general', NULL),
(5, 'empresa_direccion', 'Cra 45 # 23-12, Bogotá', 'texto', 'Dirección', 'general', NULL),
(6, 'iva_porcentaje', '19', 'numero', 'Porcentaje de IVA aplicado', 'facturacion', NULL),
(7, 'moneda_simbolo', '$', 'texto', 'Símbolo de moneda', 'facturacion', NULL),
(8, 'notificaciones_email', 'true', 'booleano', 'Enviar notificaciones por correo', 'notificaciones', NULL),
(9, 'limite_vehiculos_usuario', '5', 'numero', 'Máximo de vehículos por usuario', 'limites', NULL),
(10, 'mantenimiento_activo', 'false', 'booleano', 'Modo mantenimiento del sistema', 'sistema', NULL),
(11, 'mensaje_bienvenida', 'Bienvenido a AutoMod Pro', 'texto', 'Mensaje de bienvenida', 'personalizacion', NULL),
(12, 'nombre_taller', 'AutoMod Pro', 'texto', NULL, 'general', NULL),
(13, 'direccion', 'dg. 54b sur #41-64', 'texto', NULL, 'general', NULL),
(14, 'telefono', '8754391948', 'texto', NULL, 'general', NULL),
(15, 'email_contacto', 'automod@gmail.com', 'texto', NULL, 'general', NULL),
(16, 'iva', '19', 'texto', NULL, 'general', NULL),
(17, 'moneda', 'COP', 'texto', NULL, 'general', NULL),
(18, 'horario', '8:00 A.M', 'texto', NULL, 'general', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `confirmaciones_pedido`
--

CREATE TABLE `confirmaciones_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `propietario_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmado','rechazado','expirado') DEFAULT 'pendiente',
  `fecha_expiracion` timestamp NULL DEFAULT NULL,
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  `motivo_rechazo` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `precio_unitario` decimal(12,2) NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id`, `pedido_id`, `servicio_id`, `cantidad`, `precio_unitario`, `observaciones`) VALUES
(1, 1, 10, 1, 90000000.00, NULL),
(2, 2, 10, 1, 90000000.00, NULL),
(3, 3, 2, 1, 450000.00, NULL),
(4, 3, 3, 1, 5500000.00, NULL),
(5, 3, 5, 1, 3500000.00, NULL),
(6, 3, 6, 1, 800000.00, NULL),
(7, 4, 1, 1, 2500000.00, NULL),
(8, 4, 2, 1, 450000.00, NULL),
(9, 4, 3, 1, 5500000.00, NULL),
(10, 4, 4, 1, 1800000.00, NULL),
(11, 4, 5, 1, 3500000.00, NULL),
(12, 4, 6, 1, 800000.00, NULL),
(13, 4, 7, 1, 1500000.00, NULL),
(14, 4, 8, 1, 200000.00, NULL),
(15, 4, 10, 1, 90000000.00, NULL),
(16, 5, 11, 1, 1500000.00, NULL),
(17, 6, 1, 1, 2500000.00, NULL),
(18, 7, 5, 1, 3500000.00, NULL),
(19, 7, 10, 1, 90000000.00, NULL),
(20, 7, 11, 1, 900000.00, NULL),
(21, 8, 10, 1, 90000000.00, NULL),
(22, 9, 8, 1, 300000.00, NULL),
(23, 9, 10, 1, 135000000.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factores_antiguedad`
--

CREATE TABLE `factores_antiguedad` (
  `id` int(11) NOT NULL,
  `anio_min` int(11) NOT NULL,
  `anio_max` int(11) NOT NULL,
  `factor` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `factores_antiguedad`
--

INSERT INTO `factores_antiguedad` (`id`, `anio_min`, `anio_max`, `factor`) VALUES
(1, 1980, 1999, 0.80),
(2, 2000, 2009, 1.00),
(3, 2010, 2019, 1.10),
(4, 2020, 2029, 1.20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factores_marca`
--

CREATE TABLE `factores_marca` (
  `id` int(11) NOT NULL,
  `marca_id` int(11) NOT NULL,
  `factor` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `factores_marca`
--

INSERT INTO `factores_marca` (`id`, `marca_id`, `factor`) VALUES
(1, 5, 0.90),
(2, 9, 1.25),
(3, 10, 1.23),
(4, 8, 1.10),
(5, 2, 1.13),
(6, 1, 1.15),
(7, 3, 1.05),
(8, 6, 1.02),
(9, 7, 1.07),
(10, 4, 1.09);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factores_tipo`
--

CREATE TABLE `factores_tipo` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `factor` decimal(5,2) NOT NULL DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factores_tipo`
--

INSERT INTO `factores_tipo` (`id`, `tipo`, `factor`) VALUES
(2, 'Sedan', 0.90),
(6, 'Hatchback', 1.05),
(7, 'SUV', 1.18),
(8, 'Camioneta', 1.23),
(9, 'Deportivo', 1.36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_estados_pedido`
--

CREATE TABLE `historial_estados_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `estado_anterior` varchar(60) DEFAULT NULL,
  `estado_nuevo` varchar(60) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`) VALUES
(9, 'BMW'),
(3, 'Chevrolet'),
(8, 'Ford'),
(6, 'Hyundai'),
(5, 'Kia'),
(2, 'Mazda'),
(10, 'Mercedes-Benz'),
(7, 'Nissan'),
(4, 'Renault'),
(1, 'Toyota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelos`
--

CREATE TABLE `modelos` (
  `id` int(11) NOT NULL,
  `marca_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('Sedan','SUV','Camioneta','Deportivo','Hatchback') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modelos`
--

INSERT INTO `modelos` (`id`, `marca_id`, `nombre`, `tipo`) VALUES
(1, 1, 'Corolla', 'Sedan'),
(2, 1, 'Yaris', 'Sedan'),
(3, 1, 'Hilux', 'Camioneta'),
(4, 1, 'Fortuner', 'SUV'),
(5, 2, 'Mazda 2', 'Sedan'),
(6, 2, 'Mazda 3', 'Sedan'),
(7, 2, 'CX-5', 'SUV'),
(8, 2, 'CX-30', 'SUV'),
(9, 3, 'Onix', 'Sedan'),
(10, 3, 'Tracker', 'SUV'),
(11, 3, 'Captiva', 'SUV'),
(12, 3, 'Camaro', 'Deportivo'),
(13, 4, 'Logan', 'Sedan'),
(14, 4, 'Sandero', 'Hatchback'),
(15, 4, 'Duster', 'SUV'),
(16, 4, 'Oroch', 'Camioneta'),
(17, 5, 'Rio', 'Sedan'),
(18, 5, 'Cerato', 'Sedan'),
(19, 5, 'Sportage', 'SUV'),
(20, 5, 'Seltos', 'SUV'),
(21, 6, 'Accent', 'Sedan'),
(22, 6, 'Elantra', 'Sedan'),
(23, 6, 'Tucson', 'SUV'),
(24, 6, 'Santa Fe', 'SUV'),
(25, 7, 'Versa', 'Sedan'),
(26, 7, 'Sentra', 'Sedan'),
(27, 7, 'Kicks', 'SUV'),
(28, 7, 'Frontier', 'Camioneta'),
(29, 8, 'Fiesta', 'Sedan'),
(30, 8, 'Escape', 'SUV'),
(31, 8, 'Ranger', 'Camioneta'),
(32, 8, 'Mustang', 'Deportivo'),
(33, 9, '320i', 'Sedan'),
(34, 9, 'X3', 'SUV'),
(35, 9, 'X5', 'SUV'),
(36, 9, 'M4', 'Deportivo'),
(37, 10, 'Clase C', 'Sedan'),
(38, 10, 'GLA', 'SUV'),
(39, 10, 'GLE', 'SUV'),
(40, 10, 'AMG GT', 'Deportivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','otros') DEFAULT 'efectivo',
  `estado` enum('pendiente','pagado','reembolsado','fallido') DEFAULT 'pendiente',
  `referencia` varchar(100) DEFAULT NULL,
  `fecha_pago` timestamp NULL DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `pedido_id`, `monto`, `metodo_pago`, `estado`, `referencia`, `fecha_pago`, `creado_en`) VALUES
(1, 3, 10762500.00, 'tarjeta', 'pagado', '', NULL, '2026-06-13 05:12:42'),
(2, 2, 9999999999.99, 'tarjeta', 'pagado', '91739183183', NULL, '2026-06-13 05:21:08'),
(3, 3, 10250000.00, 'efectivo', 'pagado', '', NULL, '2026-06-14 10:43:17'),
(4, 4, 106250000.00, 'efectivo', 'pagado', '', NULL, '2026-06-14 17:50:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `vehiculo_id` int(11) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','en_proceso','completado','cancelado') DEFAULT 'pendiente',
  `total` decimal(12,2) DEFAULT 0.00,
  `notas` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `vehiculo_id`, `estado`, `total`, `notas`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 3, 'pendiente', 90000000.00, NULL, '2026-06-12 21:01:14', NULL),
(2, 1, 1, 'completado', 90000000.00, NULL, '2026-06-12 21:12:30', '2026-06-13 05:21:08'),
(3, 2, 3, 'completado', 10250000.00, NULL, '2026-06-13 02:59:28', '2026-06-13 05:12:42'),
(4, 3, 1, 'completado', 106250000.00, NULL, '2026-06-13 03:01:23', '2026-06-14 17:50:24'),
(5, 1, 5, 'pendiente', 1500000.00, NULL, '2026-06-13 15:52:31', NULL),
(6, 3, 1, 'en_proceso', 2500000.00, NULL, '2026-06-13 20:27:42', '2026-06-14 17:44:07'),
(7, 3, 3, 'completado', 94400000.00, NULL, '2026-06-13 20:29:20', '2026-06-14 17:44:32'),
(8, 3, 7, 'cancelado', 90000000.00, NULL, '2026-06-13 20:32:33', '2026-06-14 17:44:18'),
(9, 1, 5, 'pendiente', 135300000.00, NULL, '2026-06-14 06:13:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reglas_validacion`
--

CREATE TABLE `reglas_validacion` (
  `id` int(11) NOT NULL,
  `slug_modificacion` varchar(50) NOT NULL,
  `tipo_regla` enum('minimo','maximo','igual','booleano','lista') NOT NULL,
  `valor_regla` varchar(100) NOT NULL,
  `estado_legal` enum('legal','ilegal','condicional') NOT NULL,
  `severidad` enum('leve','moderada','grave') DEFAULT 'leve',
  `descripcion` text NOT NULL,
  `base_legal` text DEFAULT NULL,
  `sancion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `servicio_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reglas_validacion`
--

INSERT INTO `reglas_validacion` (`id`, `slug_modificacion`, `tipo_regla`, `valor_regla`, `estado_legal`, `severidad`, `descripcion`, `base_legal`, `sancion`, `creado_en`, `servicio_id`) VALUES
(1, 'polarizado_frontal', 'minimo', '70', 'legal', 'leve', 'Polarizado frontal: mínimo 70% transmisión de luz', 'Resolución 3754/2016', 'Multa $450,000', '2026-06-11 17:42:59', NULL),
(2, 'polarizado_frontal', 'maximo', '69', 'ilegal', 'moderada', 'Polarizado frontal NO puede ser menor al 70%', 'Resolución 3754/2016 Art.5', 'Multa $450,000', '2026-06-11 17:42:59', NULL),
(3, 'polarizado_trasero', 'minimo', '50', 'legal', 'leve', 'Polarizado trasero: mínimo 50% transmisión de luz', 'Resolución 3754/2016', 'Multa $450,000', '2026-06-11 17:42:59', NULL),
(4, 'polarizado_trasero', 'maximo', '49', 'ilegal', 'moderada', 'Polarizado trasero NO puede ser menor al 50%', 'Resolución 3754/2016 Art.5', 'Multa $450,000', '2026-06-11 17:42:59', NULL),
(5, 'polarizado_parabrisas', 'igual', '100', 'legal', 'leve', 'Parabrisas NO puede tener polarizado', 'Código Nacional Tránsito Art.83', 'Multa $450,000', '2026-06-11 17:42:59', NULL),
(6, 'polarizado_parabrisas', 'maximo', '99', 'ilegal', 'grave', 'Parabrisas NO puede tener polarizado', 'Código Nacional Tránsito Art.83', 'Multa $450,000', '2026-06-11 17:42:59', NULL),
(7, 'altura_suspension', 'minimo', '12', 'legal', 'leve', 'Altura mínima: 12 cm del suelo', 'Resolución 3754/2016', 'Multa $300,000 + inmovilización', '2026-06-11 17:42:59', NULL),
(8, 'altura_suspension', 'maximo', '11', 'ilegal', 'grave', 'Suspensión NO puede ser menor a 12 cm', 'Resolución 3754/2016 Art.8', 'Multa $300,000 + inmovilización', '2026-06-11 17:42:59', NULL),
(9, 'altura_suspension', 'maximo', '30', 'legal', 'leve', 'Altura máxima: 30 cm', 'Resolución 3754/2016', 'Multa $300,000', '2026-06-11 17:42:59', NULL),
(10, 'altura_suspension', 'minimo', '31', 'ilegal', 'grave', 'Suspensión NO puede exceder 30 cm', 'Resolución 3754/2016 Art.8', 'Multa $300,000', '2026-06-11 17:42:59', NULL),
(11, 'tamano_rines', 'maximo', '20', 'legal', 'leve', 'Tamaño máximo rines: 20 pulgadas', 'Resolución 3754/2016', 'Multa $150,000', '2026-06-11 17:42:59', NULL),
(12, 'tamano_rines', 'minimo', '21', 'ilegal', 'moderada', 'Rines >20\" requieren permiso especial', 'Resolución 3754/2016 Art.6', 'Multa $150,000', '2026-06-11 17:42:59', NULL),
(13, 'kit_aerodinamico', 'booleano', 'true', 'condicional', 'leve', 'Kit aerodinámico requiere revisión técnica', 'Resolución 3754/2016', 'Multa $300,000', '2026-06-11 17:42:59', NULL),
(14, 'aleron_trasero', 'lista', 'ninguno:pequeno:medio:grande', 'condicional', 'leve', 'Alerones que excedan 15 cm requieren permiso', 'Resolución 3754/2016', 'Multa $300,000', '2026-06-11 17:42:59', NULL),
(15, 'sistema_escape', 'lista', 'estandar:deportivo:libre', 'condicional', 'moderada', 'Escape libre prohibido en vía pública', 'Código Tránsito Art.82', 'Multa $450,000', '2026-06-11 17:42:59', NULL),
(16, 'silenciador_deportivo', 'booleano', 'false', 'legal', 'leve', 'Silenciador deportivo permitido si cumple 80dB', 'Resolución 3754/2016', 'Multa $300,000', '2026-06-11 17:42:59', NULL),
(17, 'silenciador_deportivo', 'booleano', 'true', 'condicional', 'moderada', 'Requiere certificación de ruido', 'Resolución 3754/2016 Art.9', 'Multa $300,000', '2026-06-11 17:42:59', NULL),
(18, 'luces_neon', 'booleano', 'true', 'condicional', 'leve', 'Luces neón solo blanco o ámbar', 'Código Tránsito Art.81', 'Multa $300,000', '2026-06-11 17:42:59', NULL),
(19, 'faros_led', 'booleano', 'true', 'condicional', 'leve', 'Faros LED requieren alineación certificada', 'Resolución 3754/2016', 'Multa $150,000', '2026-06-11 17:42:59', NULL),
(20, 'turbo', 'booleano', 'true', 'condicional', 'grave', 'Turbo requiere certificación de emisiones', 'Resolución 3754/2016 Art.10', 'Multa $600,000', '2026-06-11 17:42:59', NULL),
(21, 'ecu_remap', 'booleano', 'true', 'ilegal', 'grave', 'ECU Remap no certificado PROHIBIDO', 'Resolución 3754/2016', 'Multa $600,000', '2026-06-11 17:42:59', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `nivel` int(11) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `slug`, `descripcion`, `nivel`, `creado_en`) VALUES
(1, 'Administrador', 'admin', 'Gestión completa de operaciones', 80, '2026-06-11 17:42:58'),
(2, 'Mecánico', 'mecanico', 'Gestión de servicios y vehículos', 50, '2026-06-11 17:42:58'),
(3, 'Usuario', 'usuario', 'Acceso básico a personalización', 10, '2026-06-11 17:42:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(12,2) NOT NULL DEFAULT 0.00,
  `categoria_id` int(11) DEFAULT NULL,
  `duracion_estimada` int(11) DEFAULT 60 COMMENT 'minutos',
  `requiere_aprobacion` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `categoria_id`, `duracion_estimada`, `requiere_aprobacion`, `activo`, `creado_en`) VALUES
(1, 'Kit Carrocería Deportivo', 'Instalación de kit aerodinámico completo', 2500000.00, 1, 240, 1, 1, '2026-06-11 17:42:59'),
(2, 'Polarizado Profesional', 'Polarizado de vidrios con lámina certificada', 450000.00, 1, 90, 0, 1, '2026-06-11 17:42:59'),
(3, 'Instalación Turbo', 'Kit turbo compresor con intercooler', 5500000.00, 2, 480, 1, 1, '2026-06-11 17:42:59'),
(4, 'Sistema Escape Deportivo', 'Escape completo en acero inoxidable', 1800000.00, 3, 120, 1, 1, '2026-06-11 17:42:59'),
(5, 'Suspensión Deportiva', 'Suspensión ajustable de alto rendimiento', 3500000.00, 4, 180, 1, 1, '2026-06-11 17:42:59'),
(6, 'Faros LED', 'Conversión a iluminación LED completa', 800000.00, 5, 60, 0, 1, '2026-06-11 17:42:59'),
(7, 'ECU Remap', 'Remapeo de centralita electrónica', 1500000.00, 5, 120, 1, 1, '2026-06-11 17:42:59'),
(8, 'Cambio de Rines', 'Montaje y balanceo de rines personalizados', 200000.00, 1, 60, 0, 1, '2026-06-11 17:42:59'),
(10, 'casita', 'casita de madera', 90000000.00, NULL, 60, 0, 1, '2026-06-12 20:59:43'),
(11, 'pan', 'de arina', 500000.00, NULL, 60, 0, 1, '2026-06-13 15:51:43'),
(12, 'queso', 'de queso', 10000.00, NULL, 60, 0, 1, '2026-06-13 15:53:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(60) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre_completo` varchar(120) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `rol_id` int(11) DEFAULT 3,
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `correo`, `contrasena`, `nombre_completo`, `telefono`, `avatar_url`, `rol_id`, `activo`, `ultimo_acceso`, `creado_en`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$HNSO9CF1SM7jEsWIV06ByOFuI/vyeDPttRB1jCvYedRpB26UBR9VG', NULL, NULL, NULL, 1, 1, NULL, '2026-06-12 04:15:33'),
(2, 'Cristhofer Rivera', '704riveracristhoferrccb@gmail.com', '$2y$10$pvrEfD8eHqo/lVCHRBVQbOkmIH8TjdoHyhJAXtAq7zmUTlOVtvMY6', NULL, NULL, NULL, 2, 1, NULL, '2026-06-12 03:17:19'),
(3, 'pepito', 'pepito@gmail.com', '$2y$10$DFLEq7b1IRN0C0hSn.Zl1..9k69MnCz7BPOQjqC.jQgEdS7b0VRUu', NULL, NULL, NULL, 3, 1, NULL, '2026-06-12 03:34:50'),
(6, 'cris', 'cris@gmail.com', '$2y$10$22.PW7Pp9YdUqjt.a9NxgORzT0ILCQUsveM7KVuClTHopIpUBNVFq', NULL, NULL, NULL, 3, 1, NULL, '2026-06-14 09:08:54'),
(7, 'admin2', 'admin2@gmail.com', '$2y$10$WV3pi8EdKEtmHSWcUKfW1u3qCyH3AZGFZbPs.AS4RBXtjBAqFIOUC', NULL, NULL, NULL, 1, 1, NULL, '2026-06-14 09:09:22'),
(8, 'pan', 'pan@gmail.com', '$2y$10$5850llrr6Zr20t7JdLSX0.LQbITdKzehXTCkr9BzK4Z66C9k6DDcu', NULL, NULL, NULL, 3, 1, NULL, '2026-06-14 09:09:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `año` int(11) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT 'sedan',
  `vin` varchar(30) DEFAULT NULL,
  `kilometraje` int(11) DEFAULT 0,
  `observaciones` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `marca_id` int(11) DEFAULT NULL,
  `modelo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `usuario_id`, `placa`, `año`, `color`, `tipo`, `vin`, `kilometraje`, `observaciones`, `activo`, `creado_en`, `marca_id`, `modelo_id`) VALUES
(1, 3, 'ASD-514', 2026, NULL, 'Sedan', NULL, 0, NULL, 1, '2026-06-12 15:20:01', 1, 1),
(3, 3, 'ABC-001', 2020, NULL, 'Sedan', NULL, 0, NULL, 1, '2026-06-12 20:27:57', 5, 17),
(4, 2, 'AAA-012', 2012, NULL, 'Sedan', NULL, 0, NULL, 1, '2026-06-13 03:09:04', 6, 21),
(5, 1, '101-010', 2027, NULL, 'Deportivo', NULL, 0, NULL, 1, '2026-06-13 03:09:28', 9, 36),
(6, 1, 'IYN-713', 2021, NULL, 'SUV', NULL, 0, NULL, 1, '2026-06-13 03:09:58', 2, 8),
(7, 3, 'AFA-131', 2020, NULL, 'SUV', NULL, 0, NULL, 1, '2026-06-13 20:31:59', 9, 34),
(8, 2, 'QBO-213', 2022, NULL, 'SUV', NULL, 0, NULL, 1, '2026-06-14 03:36:38', 6, 24),
(9, 1, 'AAA-024', 2027, NULL, 'Camioneta', NULL, 0, NULL, 1, '2026-06-14 03:56:26', 1, 3),
(10, 2, 'UBA-826', 2018, NULL, 'Hatchback', NULL, 0, NULL, 1, '2026-06-14 03:56:59', 4, 14);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_servicio`
--
ALTER TABLE `categorias_servicio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `confirmaciones_pedido`
--
ALTER TABLE `confirmaciones_pedido`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_token` (`token`),
  ADD KEY `idx_confirmacion_pedido` (`pedido_id`),
  ADD KEY `idx_confirmacion_propietario` (`propietario_id`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `factores_antiguedad`
--
ALTER TABLE `factores_antiguedad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `factores_marca`
--
ALTER TABLE `factores_marca`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marca_id` (`marca_id`);

--
-- Indices de la tabla `factores_tipo`
--
ALTER TABLE `factores_tipo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo` (`tipo`);

--
-- Indices de la tabla `historial_estados_pedido`
--
ALTER TABLE `historial_estados_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_historial_pedido` (`pedido_id`),
  ADD KEY `idx_historial_usuario` (`usuario_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marca_id` (`marca_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `vehiculo_id` (`vehiculo_id`);

--
-- Indices de la tabla `reglas_validacion`
--
ALTER TABLE `reglas_validacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_vehiculo_marca` (`marca_id`),
  ADD KEY `fk_vehiculo_modelo` (`modelo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_servicio`
--
ALTER TABLE `categorias_servicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `confirmaciones_pedido`
--
ALTER TABLE `confirmaciones_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `factores_antiguedad`
--
ALTER TABLE `factores_antiguedad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `factores_marca`
--
ALTER TABLE `factores_marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `factores_tipo`
--
ALTER TABLE `factores_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `historial_estados_pedido`
--
ALTER TABLE `historial_estados_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modelos`
--
ALTER TABLE `modelos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `reglas_validacion`
--
ALTER TABLE `reglas_validacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `confirmaciones_pedido`
--
ALTER TABLE `confirmaciones_pedido`
  ADD CONSTRAINT `fk_confirmacion_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_confirmacion_propietario` FOREIGN KEY (`propietario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `factores_marca`
--
ALTER TABLE `factores_marca`
  ADD CONSTRAINT `factores_marca_ibfk_1` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Filtros para la tabla `historial_estados_pedido`
--
ALTER TABLE `historial_estados_pedido`
  ADD CONSTRAINT `fk_historial_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD CONSTRAINT `modelos_ibfk_1` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `reglas_validacion`
--
ALTER TABLE `reglas_validacion`
  ADD CONSTRAINT `reglas_validacion_ibfk_1` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_servicio` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculo_marca` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  ADD CONSTRAINT `fk_vehiculo_modelo` FOREIGN KEY (`modelo_id`) REFERENCES `modelos` (`id`),
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
