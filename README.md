# Plataforma de Gestión de Avisos e Incidentes Municipales

Aplicación Web dinámica y modular diseñada para la publicación, moderación y administración de noticias e incidentes en el ámbito municipal. Este proyecto se ha consolidado en su **Práctica 5** como una plataforma asíncrona interactiva para la asignatura *Sistemas de Información Basados en la Web* (SIBW) de la Escuela Técnica Superior de Ingenierías Informática y de Telecomunicación (ETSIIT) de la Universidad de Granada.

La aplicación destaca por la completa separación de la lógica de negocio y la capa de presentación mediante un motor de plantillas, un sistema robusto de control de accesos basado en roles y comunicación asíncrona avanzada con el servidor sin recarga de interfaz.

## 🛠️ Arquitectura y Stack Tecnológico

El entorno está completamente virtualizado y aislado utilizando contenedores de servicios independientes:

* **Servidor Web:** Apache 2.4 encapsulado en el contenedor `lamp-php84` (PHP 8.4).
* **Motor de Plantillas:** Twig (Integración nativa para la renderización de vistas dinámicas)[cite: 1, 2].
* **Base de Datos:** MySQL 8.0 alojado en el contenedor `lamp-mysql8` (Esquema relacional `sibw`).
* **Administración de BD:** phpMyAdmin en el contenedor `lamp-phpmyadmin` (Puerto local `8080`).
* **Frontend:** JavaScript Moderno (Vanilla JS, Fetch API), HTML5 estructural y diseño adaptativo unificado con CSS Grid y Flexbox[cite: 1, 2].
* **Cache/Sesiones:** Servidor Redis integrado en el contenedor `lamp-redis`[cite: 2].

## 🚀 Características Clave Implementadas (Práctica 5)

1. **Buscador Predictivo Asíncrono (Estilo Google):** Filtrado interactivo en la portada pública. Mediante el evento `input` de JavaScript y la API `fetch()`, el cliente consulta un endpoint de filtrado (`LIKE %q%`) que retorna estructuras JSON puras, inyectando los resultados en un desplegable dinámico sobre el DOM.
2. **Búsqueda Avanzada en Panel de Gestión:** Módulo de administración dotado de un formulario de búsqueda de triple criterio combinable (Título, Cuerpo, Hashtags). Capturado mediante `e.preventDefault()`, procesa la petición asíncronamente y regenera el cuerpo de la tabla (`<tbody>`) en caliente, optimizando el tráfico de red[cite: 2].
3. **Persistencia de Publicación en un Clic:** Inclusión de controles interactivos (*checkboxes*) integrados en la tabla de administración mediante delegación de eventos. Cualquier cambio de estado se comunica en segundo plano al servidor, alterando la visibilidad del aviso en la portada principal de forma inmediata.
4. **Seguridad y Reglas de Negocio:** Control estricto de accesos por roles (Anónimo, Registrado, Moderador, Gestor, Root)[cite: 2]. El backend implementa políticas de protección que impiden el auto-sabotaje del sistema (bloqueo ante intentos de degradación o eliminación del último usuario Root vivo).

## 📦 Estructura del Proyecto

```text
.
├── bin/                        # Scripts de utilidad para contenedores
├── config/                     # Configuraciones de Apache, MySQL y PHP[cite: 2]
│   └── initdb/                 # Scripts SQL de inicialización
├── data/                       # Almacenamiento persistente de MySQL (ignorado en Git)[cite: 2]
├── logs/                       # Registros de actividad del servidor (ignorado en Git)[cite: 2]
├── www/                        # Código Fuente de la Aplicación Web (Document Root)[cite: 2]
│   ├── js/                     # Controladores lógicos de AJAX y Fetch[cite: 1]
│   ├── plantillas/             # Vistas del lado del servidor (Twig templates)[cite: 1, 2]
│   ├── conexion.php            # Configuración de conexiones mediante mysqli[cite: 1]
│   └── portada.php             # Controlador principal de la web pública
├── .env.example                # Plantilla de variables de entorno globales[cite: 2]
├── .gitignore                  # Exclusiones estrictas para un repositorio limpio[cite: 2]
└── docker-compose.yml          # Orquestación de los contenedores Docker[cite: 2]