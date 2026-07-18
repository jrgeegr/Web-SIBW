# Plataforma de Gestión de Avisos e Incidentes Municipales

Aplicación Web dinámica y modular diseñada para la publicación, moderación y administración de noticias e incidentes en el ámbito municipal. Este proyecto se ha consolidado en su **Práctica 5** como una plataforma asíncrona interactiva para la asignatura *Sistemas de Información Basados en la Web* (SIBW) de la Escuela Técnica Superior de Ingenierías Informática y de Telecomunicación (ETSIIT) de la Universidad de Granada.

La aplicación destaca por la completa separación de la lógica de negocio y la capa de presentación mediante un motor de plantillas, un sistema robusto de control de accesos basado en roles y comunicación asíncrona avanzada con el servidor sin recarga de interfaz.

## 🛠️ Arquitectura y Stack Tecnológico

El entorno está completamente virtualizado y aislado utilizando contenedores de servicios independientes:

* **Servidor Web:** Apache 2.4 encapsulado en el contenedor `lamp-php84` (PHP 8.4).
* **Motor de Plantillas:** Twig (Integración nativa para la renderización de vistas dinámicas).
* **Base de Datos:** MySQL 8.0 alojado en el contenedor `lamp-mysql8` (Esquema relacional `sibw`).
* **Administración de BD:** phpMyAdmin en el contenedor `lamp-phpmyadmin` (Puerto local `8080`).
* **Frontend:** JavaScript Moderno (Vanilla JS, Fetch API), HTML5 estructural y diseño adaptativo unificado con CSS Grid y Flexbox.
* **Cache/Sesiones:** Servidor Redis integrado en el contenedor `lamp-redis`.

## 📋 Histórico de Desarrollo y Evolución del Sistema

A lo largo del semestre académico, la plataforma ha atravesado un proceso de ingeniería incremental, evolucionando a través de las siguientes fases detalladas en los guiones docentes[cite: 3, 4, 5, 6, 7]:

### 🎨 Práctica 1: Fundamentos de Interfaz y Diseño Estático (HTML5 y CSS3)
*   **Maquetación Semántica:** Construcción y estructuración de la interfaz visual base (`portada.html`, `noticia.html`) aplicando etiquetas semánticas nativas de HTML5, evitando el uso de layouts obsoletos basados en tablas[cite: 7].
*   **Layouts Avanzados (Grid & Flexbox):** Implementación combinada de CSS Grid Layouts para la distribución macroscópica de las vistas y la rejilla adaptativa de 4 columnas de la portada, junto con Flexbox para la alineación precisa de elementos internos y menús[cite: 7].
*   **Hoja de Estilos de Impresión:** Creación del módulo `noticia_imprimir.html` reutilizando la estructura HTML original pero aplicando una hoja de estilos de medios (`@media print`) alternativa para mutar la interfaz en blanco y negro, con tipografías optimizadas, logotipos reubicados y ocultación estricta de elementos interactivos o redes sociales[cite: 7].

### ⚡ Práctica 2: Programación de Dinamismo del Lado del Cliente (JavaScript Nativo)
*   **Manipulación del DOM y Eventos:** Inyección interactiva del panel de comentarios oculto mediante listeners de captura de eventos del ratón (`input`, `keyup`, `change`)[cite: 3].
*   **Motores de Validación Front-End:** Diseño de algoritmos para verificar la presencia de campos obligatorios en los formularios de envío y validación sintáctica de direcciones de correo electrónico apoyándose en expresiones regulares (Regex) con diálogos modales de alerta[cite: 3].
*   **Interactividad en Tiempo Real:** Implementación de un filtro corrector de texto en caliente que intercepta las entradas del usuario mientras teclea en el área de texto, contrastándolas contra un array parametrizado de localidades locales para convertirlas automáticamente a mayúsculas estrictas[cite: 3].

### 🐘 Práctica 3: Arquitectura del Lado del Servidor e Introducción al Patrón MVC (PHP & Twig)
*   **Motor de Plantillas Twig:** Migración completa de la estructura estática hacia un esquema modular estructurado bajo el patrón Modelo-Vista-Controlador (MVC), abstrayendo las plantillas mediante herencia y bloques, garantizando la ausencia total de código HTML en los archivos controladores PHP[cite: 6].
*   **Persistencia Relacional Original:** Modelado y despliegue del esquema de base de datos relacional para indexar noticias en detalle, galerías fotográficas dinámicas y comentarios vinculados mediante claves foráneas[cite: 6].
*   **Sanitización y Parámetros GET:** Implementación de las primeras capas de seguridad lógicas mediante el filtrado de variables superglobales `$_GET['id']` para repeler inyecciones de código SQL o inclusiones remotas de ficheros maliciosos[cite: 6].

### 🔐 Práctica 4: Gestión Avanzada de Estado, Sesiones y Seguridad por Roles (PHP II)
*   **Criptografía y Control de Sesiones:** Autenticación segura empleando funciones criptográficas (`password_hash` y `password_verify`) para el resguardo de credenciales en base de datos, persistidas mediante variables de `$_SESSION` seguras en el servidor[cite: 5].
*   **Ecosistema de Roles y Privilegios:** Restricción estricta de accesos en base a cinco identidades (*Anónimo*, *Registrado*, *Moderador*, *Gestor* y *Superusuario/Root*), permitiendo la edición in-situ de contenidos y la moderación de aportes[cite: 5].
*   **Buscadores de Panel de Control:** Diseño e integración del backend del panel de gestión con soporte para búsquedas combinadas multivariable dentro del cuerpo, título y hashtags asociados de los registros de noticias[cite: 5].

### 🚀 Práctica 5: Componentización Asíncrona y Peticiones No Bloqueantes (AJAX & Fetch API)
*   **Búsqueda Predictiva Desplegable:** Creación de un endpoint API dinámico en el servidor de portada que resuelve búsquedas con operadores `LIKE` y responde en formato JSON puro. El cliente captura los impulsos del teclado mandando peticiones con `fetch()` y reconstruyendo un listado de accesos flotantes en el DOM sin recargar la página[cite: 4].
*   **Migración Asíncrona del Panel Administrativo:** Rediseño del formulario de triple criterio para interceptar el evento `submit` con `preventDefault()`, sustituyendo la tabla de gestión interna de forma fluida mediante inyecciones en caliente de plantillas de cadena lógicas[cite: 4].
*   **Mutación de Estado Remota:** Adición de checkboxes en la tabla para alternar la visibilidad de publicación de contenidos. Utiliza delegación de eventos en JavaScript para notificar silenciosamente las actualizaciones a la base de datos a través de peticiones HTTP en segundo plano[cite: 4].


## 📦 Estructura del Proyecto

```text
.
├── bin/                        # Scripts de utilidad para contenedores
├── config/                     # Configuraciones de Apache, MySQL y PHP
│   └── initdb/                 # Scripts SQL de inicialización
├── data/                       # Almacenamiento persistente de MySQL (ignorado en Git)
├── logs/                       # Registros de actividad del servidor (ignorado en Git)
├── www/                        # Código Fuente de la Aplicación Web (Document Root)
│   ├── js/                     # Controladores lógicos de AJAX y Fetch
│   ├── plantillas/             # Vistas del lado del servidor (Twig templates)
│   ├── conexion.php            # Configuración de conexiones mediante mysqli
│   └── portada.php             # Controlador principal de la web pública
├── sibw.sql                    # Volcado estructural inicial de la Base de Datos
├── .env.example                # Plantilla de variables de entorno globales
├── .gitignore                  # Exclusiones estrictas para un repositorio limpio
└── docker-compose.yml          # Orquestación de los contenedores Docker
```

## 🔧 Instrucciones para el Despliegue Local y Puesta en Marcha
Para levantar este proyecto en un entorno local de desarrollo y configurar la base de datos desde cero sin problemas de permisos, sigue escrupulosamente estos pasos en tu terminal Linux:
1. **Clonar el proyecto y acceder al directorio.**
```text
git clone [https://github.com/tu-usuario/tu-repositorio.git](https://github.com/tu-usuario/tu-repositorio.git)
cd tu-repositorio
```
2. **Configurar las variables de entorno locales**. Duplica la plantilla de configuración de Docker para inicializar tu entorno privado:
```text
cp .env.example .env
```
3. **Levantar la infraestructura de contenedores en segundo plano.**
```text
docker compose up -d
```
4. **Instalar dependencias del Backend** (Autoloader de Twig). Accede al entorno aislado del servidor web para descargar los componentes requeridos mediantes Composer:
```text
docker compose exec webserver composer install
```
5. **Configurar el motor de Base de Datos e Inicializar el esquema sibw**. Dado que el directorio binario data/mysql se ignora por seguridad, la primera vez que se ejecuta el comando up la base de datos nace completamente vacía. Para estructurarla de forma idéntica a la aplicación, conéctate al contenedor e inicializa el esquema:
```text
docker compose exec database mysql -u root -ptiger -e "CREATE DATABASE IF NOT EXISTS sibw;"
```
6. **Crear el usuario del sistema y conceder privilegios**. Asegúrate de comprobar en tu archivo www/conexion.php qué clave utiliza la función conectar() y reemplaza tu_contraseña_real_aqui por dicho valor:
```text
docker compose exec database mysql -u root -ptiger -e "CREATE USER IF NOT EXISTS 'sibwuser'@'%' IDENTIFIED BY 'tu_contraseña_real_aqui'; GRANT ALL PRIVILEGES ON sibw.* TO 'sibwuser'@'%'; FLUSH PRIVILEGES;"
```
7. **Poblar las tablas y volcar los datos de prueba**. Por último, inyecta el volcado estructurado .sql que se encuentra en la raíz del proyecto llamado "sibw.sql" en el motor MySQL del contenedor para rellenar los datos de la aplicación.
```text
docker compose exec -T database mysql -u root -ptiger sibw < sibw.sql
```
8. **¡Todo listo!**. Abre tu navegador web en ingresa en http://localhost/portada.php o en http://localhost:8080 para gestionar la BD.
