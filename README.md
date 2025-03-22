# Blog Laravel + Vue.js

[Ver Repositorio en GitHub](https://github.com/Xfirepc/ATC-Blog)

Este es un proyecto de blog desarrollado con Laravel y Vue.js 2, que incluye funcionalidades como gestión de posts, categorías, comentarios y autenticación de usuarios.

## Habilidades Técnicas

### Arquitectura y Patrones de Diseño
- **Arquitectura de Microservicios**
  - API RESTful con Laravel
  - Frontend desacoplado con Vue.js 2
  - Comunicación cliente-servidor mediante Axios
  - Manejo de estados con Vuex

- **Patrones de Diseño Implementados**
  - Repository Pattern para abstracción de datos
  - Service Layer para lógica de negocio
  - Factory Pattern en seeders y tests
  - Observer Pattern para eventos del sistema
  - Module Pattern en la organización de Vuex

### Backend Development
- **API RESTful Avanzada**
  - Recursos API transformados
  - Paginación y filtrado
  - Manejo de relaciones complejas
  - Validación de datos robusta
  - Middleware personalizado

- **Base de Datos**
  - Migraciones estructuradas
  - Relaciones eloquent complejas
  - Seeders para datos de prueba
  - Optimización de consultas
  - Transacciones y consistencia

- **Seguridad**
  - Autenticación con Sanctum
  - Protección CSRF
  - Validación de entrada
  - Manejo de sesiones seguro
  - Rate limiting

### Frontend Development (Vue.js 2)
- **Gestión de Estado**
  - Vuex modular
  - Actions asíncronas
  - Getters computados
  - Mutations controladas
  - Estado persistente

- **Componentes Avanzados**
  - Componentes reutilizables con Options API
  - Props validadas
  - Eventos personalizados
  - Slots y scoped slots
  - Mixins para lógica reutilizable

- **Optimización Frontend**
  - Lazy loading de componentes
  - Caching de datos
  - Manejo de assets con Vite
  - Optimización de imágenes
  - Code splitting

### UI/UX Design
- **Diseño Responsive**
  - Mobile-first approach
  - Grid system personalizado
  - Breakpoints estratégicos
  - Flexbox layouts
  - CSS Grid

- **Experiencia de Usuario**
  - Feedback visual inmediato
  - Estados de carga optimizados
  - Mensajes de error intuitivos
  - Animaciones suaves
  - Navegación fluida



## Requisitos Previos

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- NPM o Yarn
- MySQL >= 5.7
- Git

## Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/Xfirepc/ATC-Blog
cd ATC-Blog
```

2. Instalar dependencias de PHP:
```bash
composer install
```

3. Instalar dependencias de JavaScript:
```bash
npm install
# o si usas Yarn:
yarn install
```

4. Configurar el entorno:
```bash
# Copiar el archivo de ejemplo de variables de entorno
cp .env.example .env

# Generar la clave de la aplicación
php artisan key:generate
```

5. Configurar la base de datos:
   - Crear una base de datos MySQL
   - Editar el archivo `.env` con las credenciales de tu base de datos:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

6. Ejecutar las migraciones y seeders:
```bash
php artisan migrate --seed
```

7. Configurar el almacenamiento:
```bash
php artisan storage:link
```

## Ejecución del Proyecto

1. Iniciar el servidor de Laravel:
```bash
php artisan serve
```

2. En otra terminal, compilar y observar cambios en los assets:
```bash
npm run dev
# o si usas Yarn:
yarn dev
```

El proyecto estará disponible en `http://localhost:8000`

## Características Principales

- Autenticación de usuarios
- Gestión de posts
- Categorización de contenido
- Sistema de comentarios
- Diseño responsive con Tailwind CSS
- Panel de administración
- API RESTful

### Usuario de prueba
```json
{
    "email": "test@example.com",
    "password": "password"
}
```

## Estructura del Proyecto

- `/resources/js/` - Componentes Vue.js y lógica frontend
- `/app/Http/Controllers/` - Controladores Laravel
- `/routes/` - Definición de rutas (web.php y api.php)
- `/database/migrations/` - Migraciones de base de datos
- `/database/seeders/` - Seeders para datos de prueba
