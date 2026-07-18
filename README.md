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
├── .env.example                # Plantilla de variables de entorno globales
├── .gitignore                  # Exclusiones estrictas para un repositorio limpio
└── docker-compose.yml          # Orquestación de los contenedores Docker
.

## 🔧 Instrucciones para el Despliegue Local y Puesta en Marcha
