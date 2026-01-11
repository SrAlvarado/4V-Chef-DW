# 4V-Chef-DW

Descripción
---
4V-Chef-DW es una API para una web de gestión de recetas. Implementada principalmente en PHP (con algunos recursos en JavaScript), proporciona endpoints para crear, leer, actualizar y eliminar recetas, gestionar ingredientes y usuarios (según la implementación). Esta documentación está en español y cubre instalación, ejecución, uso y contribución.

Características principales
---
- API REST para gestión de recetas (CRUD)
- Lógica de servidor en PHP
- Persistencia en MariaDB
- Endpoints típicos: /api/recetas, /api/ingredientes, /api/usuarios
- Estructura modular para facilitar mantenimiento

Estado del proyecto
---
- Idioma principal: PHP (~96%)
- Partes en JavaScript (~3%)
- Estado: En desarrollo (ajusta si es necesario)

Requisitos
---
- PHP >= 8.0 (recomendado)
- Composer (gestor de dependencias)
- MariaDB (servidor de base de datos)
- Servidor web (PHP built-in, Apache o Nginx) o Docker (opcional)
- Git

Instalación (pasos genéricos)
---
1. Clona el repositorio:
   - git clone https://github.com/SrAlvarado/4V-Chef-DW.git
   - cd 4V-Chef-DW

2. Instala dependencias PHP con Composer:
   - composer install

3. Configura variables de entorno:
   - Copia ejemplo (si existe): `cp .env.example .env`
   - Edita `.env` con tus credenciales. Variables recomendadas:
     - DB_HOST=127.0.0.1
     - DB_PORT=3306
     - DB_DATABASE=4v_chef_dw
     - DB_USERNAME=usuario
     - DB_PASSWORD=contraseña
     - APP_ENV=development
     - APP_DEBUG=true
   - Si no hay `.env.example`, crea `.env` con las claves anteriores.

4. Inicializa la base de datos (MariaDB):
   - Crea la base de datos: `CREATE DATABASE 4v_chef_dw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
   - Ejecuta migraciones o importa script SQL si existe (ejemplo):
     - `mysql -u usuario -p 4v_chef_dw < database/schema.sql`
   - Si el proyecto usa migraciones PHP, ejecuta el script correspondiente (p. ej. `php scripts/migrate.php`).

5. Ejecuta el servidor local:
   - Usando PHP integrado: `php -S localhost:8000 -t public` (ajusta `public` si tu punto de entrada es otro)
   - O configura Apache/Nginx según tu entorno
   - O usa Docker: `docker-compose up --build` (si hay `docker-compose.yml`)

Configuración de la base de datos (MariaDB)
---
- Asegúrate de que MariaDB esté accesible desde las credenciales en `.env`.
- Puerto por defecto: 3306.
- Ejemplo de DSN PDO:
  - `mysql:host=DB_HOST;port=DB_PORT;dbname=DB_DATABASE;charset=utf8mb4`

API — Endpoints sugeridos
---
- Listar recetas
  - GET /api/recetas
  - Respuesta: 200 OK — lista de recetas (JSON)

- Obtener receta por id
  - GET /api/recetas/{id}
  - Respuesta: 200 OK — objeto receta (JSON)

- Crear receta
  - POST /api/recetas
  - Body JSON:
    {
      "titulo": "Tarta de manzana",
      "descripcion": "Deliciosa...",
      "ingredientes": [
        {"nombre":"Manzana","cantidad":"3"},
        {"nombre":"Harina","cantidad":"200g"}
      ],
      "tiempo_preparacion": 45
    }
  - Respuesta: 201 Created — receta creada

- Actualizar receta
  - PUT /api/recetas/{id}
  - Body JSON similar al POST

- Eliminar receta
  - DELETE /api/recetas/{id}

Ejemplos cURL
---
- Obtener todas las recetas:
  - curl -X GET "http://localhost:8000/api/recetas" -H "Accept: application/json"

- Crear una receta:
  - curl -X POST "http://localhost:8000/api/recetas" -H "Content-Type: application/json" -d '{"titulo":"Ejemplo","ingredientes":[{"nombre":"Sal","cantidad":"1cda"}]}'

Autenticación
---
- Si la API requiere autenticación (tokens JWT, sesiones, OAuth), documenta los endpoints de login y cómo enviar el token (por ejemplo, `Authorization: Bearer <token>`).
- Actualmente: TODO — añadir detalles de autenticación si aplica.

Pruebas
---
- Si se usan pruebas con PHPUnit:
  - `vendor/bin/phpunit`
- Para pruebas manuales, usa herramientas como Postman o HTTPie.
- Añade suites de pruebas y CI en el futuro para garantizar estabilidad.

Estructura del repositorio (sugerida)
---
- /src — código PHP
- /public — punto de entrada público
- /config — configuraciones
- /database — migraciones y seeders (o /scripts)
- /tests — pruebas unitarias/integración
- /resources o /assets — front (JS/CSS) si aplica

Despliegue
---
- Preparar variables de entorno en el entorno de producción.
- Ejecutar migraciones y seeds.
- Asegurar permisos en carpetas de cache/logs.
- Usar HTTPS y configurar backups de la base de datos.
- Recomiendo Docker + docker-compose para consistencia entre entornos.

Contribuir
---
- Fork -> branch con nombre descriptivo -> PR con descripción clara
- Seguir guía de estilo (PSR-12 para PHP, ESLint para JS si aplica)
- Añadir pruebas para cambios importantes
- Usar issues para reportar bugs o proponer features

Contacto
---
- Autor / Mantenedor: SrAlvarado
- Email: markel.alvarado@gmail.com
- Issues: usa el sistema de issues del repositorio para reportar bugs o solicitar features.
