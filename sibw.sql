-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: database:3306
-- Tiempo de generación: 18-07-2026 a las 16:33:07
-- Versión del servidor: 8.4.8
-- Versión de PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sibw`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int NOT NULL,
  `id_noticia` int DEFAULT NULL,
  `autor` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `texto` text,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `editado_por_moderador` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_noticia`, `autor`, `email`, `texto`, `fecha`, `editado_por_moderador`) VALUES
(2, 1, 'Jorge', 'jorge@correo.es', 'Gracias por la información.', '2026-04-16 10:09:17', 0),
(3, 1, 'Alberto', 'alberto@correo.es', 'Muchas gracias DON BENITO', '2026-04-23 14:33:40', 0),
(4, 1, 'Jorge', 'jorge@correo.es', 'Gracias.', '2026-04-26 08:20:35', 0),
(5, 1, 'Alberto', 'jorge@correo.es', 'Muy bien', '2026-04-26 09:04:25', 0),
(6, 1, 'Ana', 'ana@correo.es', 'Valiosa información.', '2026-04-26 09:32:46', 1),
(7, 1, 'Laura', 'laura@correo.es', 'Gracias.', '2026-04-26 09:33:00', 1),
(9, 19, 'Alberto', 'alberto@correo.es', 'Magnífica información.', '2026-05-16 13:56:18', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hashtags`
--

CREATE TABLE `hashtags` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `hashtags`
--

INSERT INTO `hashtags` (`id`, `nombre`) VALUES
(1, 'DonBenito'),
(4, 'Fiesta'),
(3, 'SanIsidro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` int NOT NULL,
  `id_noticia` int DEFAULT NULL,
  `ruta_foto` varchar(255) DEFAULT NULL,
  `titulo_foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`id`, `id_noticia`, `ruta_foto`, `titulo_foto`) VALUES
(1, 1, 'img/accidente1.jpg', 'Vista general del accidente'),
(2, 1, 'img/accidente2.jpg', 'Detalle de los daños'),
(3, 1, 'img/accidente3.jpg', 'Intervención de bomberos'),
(4, 1, 'img/accidente4.jpg', 'Corte de tráfico'),
(5, 1, 'img/accidente5.jpg', 'Retirada de vehículos'),
(6, NULL, 'img/logo_don_benito.png', 'Logo Ayuntamiento'),
(30, 19, 'img/sanisidro_1778853722_0.webp', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidades`
--

CREATE TABLE `localidades` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `localidades`
--

INSERT INTO `localidades` (`id`, `nombre`) VALUES
(1, 'Don Benito'),
(2, 'Granada'),
(3, 'Maracena'),
(4, 'Peligros'),
(5, 'Armilla'),
(6, 'Huelva'),
(7, 'Atarfe'),
(8, 'Albolote'),
(9, 'Churriana'),
(10, 'Belicena'),
(11, 'San Isidro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fecha_pub` date NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `concejalia` varchar(100) DEFAULT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `lugar_id` int DEFAULT NULL,
  `cuerpo` text,
  `publicado` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `fecha_pub`, `tipo`, `concejalia`, `responsable`, `lugar_id`, `cuerpo`, `publicado`) VALUES
(1, 'Grave accidente en la Avenida de la Constitución', '2026-04-16', 'Incidente vial', 'Seguridad Ciudadana', 'Juan Pérez', 1, 'La tranquilidad de la mañana de este martes en la localidad extremeña de Don Benito se ha visto truncada por un aparatoso accidente de tráfico. El siniestro, que ha tenido lugar en la conocida Avenida de Madrid, una de las arterias principales de la ciudad, ha involucrado a tres vehículos particulares y ha dejado un balance de cuatro personas heridas de diversa consideración, provocando además importantes retenciones en el acceso norte del municipio. El incidente se produjo aproximadamente a las 11:45 horas.\r\nSegún las primeras investigaciones y los testimonios de los testigos presenciales que se encontraban en la zona, el accidente se desencadenó cuando uno de los turismos, que circulaba a una velocidad aparentemente superior a la permitida en vía urbana, no pudo frenar a tiempo ante la detención de la fila de coches en un paso de peatones. El impacto inicial fue una colisión por alcance que, por efecto dominó, terminó afectando a otros dos vehículos que se encontraban estacionados o en proceso de incorporación a la vía.\r\n\r\nTras recibir las primeras llamadas de alerta al Centro de Atención de Urgencias y Emergencias 112 de Extremadura, se activó de inmediato un amplio dispositivo de seguridad y asistencia. Al lugar del suceso se desplazaron dos unidades de soporte vital básico, una ambulancia medicalizada del Servicio Extremeño de Salud (SES), varias patrullas de la Policía Local de Don Benito y una dotación de bomberos del parque comarcal de la zona, ante la posibilidad de que alguno de los ocupantes hubiera quedado atrapado en el interior de los habitáculos metálicos deformados por el impacto. Al llegar, los efectivos sanitarios procedieron al triaje de las víctimas en plena calle. Los heridos, dos mujeres de mediana edad y dos jóvenes, presentaban cuadros de latigazo cervical, contusiones torácicas y crisis de ansiedad. Afortunadamente, y a pesar de la espectacularidad de las imágenes que dejaban los vehículos —con frontales destrozados y cristales esparcidos por todo el asfalto—, ninguno de los afectados parece presentar riesgo vital inminente. No obstante, todos fueron trasladados al Hospital Comarcal de Don Benito-Villanueva para realizarles pruebas diagnósticas más exhaustivas y descartar lesiones internas o traumatismos craneoencefálicos leves.\r\n\r\nLa Policía Local se vio obligada a cortar totalmente el tráfico en el tramo afectado de la Avenida de Madrid durante más de una hora. Esto generó un notable colapso circulatorio, desviando el flujo de coches hacia calles aledañas que no están diseñadas para absorber tal volumen de vehículos, especialmente en hora punta comercial. Los agentes trabajaron intensamente no solo en el control del tráfico, sino también en la toma de mediciones y fotografías para la elaboración del atestado correspondiente que determine las responsabilidades legales del suceso. Los bomberos, por su parte, tuvieron que intervenir para asegurar la zona, desconectando las baterías de los coches implicados para evitar posibles incendios por cortocircuito y limpiando la calzada de aceites y líquidos refrigerantes que hacían el pavimento extremadamente deslizante y peligroso para el resto de conductores.\r\n', 1),
(19, 'El barrio de San Isidro inicia hoy sus fiestas patronales', '2026-05-15', 'Celebración', 'Eventos', 'Jorge', 11, 'El barrio de San Isidro celebrará sus fiestas patronales del 15 al 18 de mayo con un programa que combinará tradición, convivencia y actividades para todas las edades.\r\n\r\nLas celebraciones arrancan hoy con las Rogativas a San Isidro Labrador en la parroquia de Santiago y continuarán con el pregón de las fiestas en la sede social del barrio, donde además se entregará la Espiga de Honor al Parque de Bomberos Don Benito-villanueva y el Escudo de Oro de la ciudad a Enrique García Margallo. La jornada finalizará con las actuaciones musicales de La Rumbera y Flamenrock.\r\n\r\nEl sábado estará marcado por las tradicionales migas extremeñas, talleres infantiles y la tercera edición de la tractorada infantil, una de las actividades más destacadas del programa. Durante la jornada también se celebrará el San Isidro Fest II.\r\n\r\nLas fiestas concluirán el domingo con una misa solemne y el tradicional cortejo de San Isidro hasta su ermita acompañado por tamborileros, caballistas y carretas. Además, habrá actuaciones, entrega de premios y una degustación de paella para despedir las celebraciones.', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias_hashtags`
--

CREATE TABLE `noticias_hashtags` (
  `id_noticia` int NOT NULL,
  `id_hashtag` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `noticias_hashtags`
--

INSERT INTO `noticias_hashtags` (`id_noticia`, `id_hashtag`) VALUES
(1, 1),
(19, 3),
(19, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(2, 'gestor'),
(3, 'moderador'),
(4, 'registrado'),
(1, 'root');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `id_rol` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre_completo`, `id_rol`) VALUES
(5, 'gestor@sibw.com', '$2y$12$e8dJraBdDJDSZjOM1sCuGuUV4GpRzzy1PTWQGwWLTdsxNjCB97zru', 'Gestor Noticias', 2),
(6, 'mod@sibw.com', '$2y$12$e8dJraBdDJDSZjOM1sCuGuUV4GpRzzy1PTWQGwWLTdsxNjCB97zru', 'Moderador', 3),
(10, 'admin@sibw.com', '$2y$12$L9.cvUAQ9RK9omszjE9Lv.j5KOwILGYRruf6XlTx11ja1hi.xMWOe', 'Admin', 1),
(12, 'jorge@sibw.com', '$2y$12$/gHaP6QM5n6YosXJV9bO2.JT8I3Gs6OBY3MsuMzP9lIN1wLUdonrm', 'Jorge', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_noticia` (`id_noticia`);

--
-- Indices de la tabla `hashtags`
--
ALTER TABLE `hashtags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_noticia` (`id_noticia`);

--
-- Indices de la tabla `localidades`
--
ALTER TABLE `localidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lugar_id` (`lugar_id`);

--
-- Indices de la tabla `noticias_hashtags`
--
ALTER TABLE `noticias_hashtags`
  ADD PRIMARY KEY (`id_noticia`,`id_hashtag`),
  ADD KEY `id_hashtag` (`id_hashtag`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `hashtags`
--
ALTER TABLE `hashtags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `localidades`
--
ALTER TABLE `localidades`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_noticia`) REFERENCES `noticias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`id_noticia`) REFERENCES `noticias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`lugar_id`) REFERENCES `localidades` (`id`);

--
-- Filtros para la tabla `noticias_hashtags`
--
ALTER TABLE `noticias_hashtags`
  ADD CONSTRAINT `noticias_hashtags_ibfk_1` FOREIGN KEY (`id_noticia`) REFERENCES `noticias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `noticias_hashtags_ibfk_2` FOREIGN KEY (`id_hashtag`) REFERENCES `hashtags` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
