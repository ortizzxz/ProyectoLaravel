# Proyecto Laravel: Jornadas de Videojuegos
## Descripción
Este proyecto es una aplicación web desarrollada en Laravel para gestionar las Jornadas de Videojuegos organizadas por el IES Francisco Ayala. El sistema permite gestionar la inscripción a conferencias y talleres, realizar pagos, y gestionar a los ponentes y eventos de la jornada.

## Funcionalidades principales:
- Gestión de eventos: La aplicación permite gestionar dos tipos de eventos: conferencias y talleres. Los eventos están distribuidos durante dos días (jueves y viernes), con limitaciones de espacio para conferencias y talleres.

- Inscripción de usuarios: Los usuarios pueden registrarse, elegir entre tres tipos de asistencia (presencial, virtual, gratuita para estudiantes), y seleccionar las conferencias y talleres a los que desean asistir. La inscripción se completa mediante un proceso de pago a través de PayPal.

- Validación de usuarios: Durante el proceso de registro, se realiza la validación del correo electrónico y la confirmación de la inscripción.

- Gestión de eventos y ponentes para administradores: Los administradores pueden gestionar la lista de eventos, ponentes, y controlar los ingresos recibidos.

## Instalación
1. Clonar el repositorio
Para comenzar a trabajar con este proyecto, clónalo en tu máquina local:
    `git clone https://github.com/ortizzxz/ProyectoLaravel.git`

2. Instalar las dependencias de Composer
Una vez clonado el proyecto, navega a la carpeta del proyecto y ejecuta el siguiente comando para instalar las dependencias de Laravel:
    `composer install`
    
3. Configuración de variables de entorno (Customizable)
A continuación, configura las variables de entorno en el archivo .env según tus necesidades, como la conexión a la base de datos y las credenciales de PayPal.

4. Generar la clave de la aplicación
    `php artisan key:generate`

5. Migrar la base de datos
Ejecuta las migraciones para crear las tablas necesarias en tu base de datos:
    `php artisan migrate`

6. Servir la aplicación
Finalmente, puedes iniciar el servidor local de Laravel con:
    `php artisan serve`

Esto debería hacer que la aplicación esté disponible en http://localhost:8000.

## Funcionalidades implementadas
1. Gestión de usuarios
Registro de usuario: Los usuarios pueden registrarse mediante un formulario, eligiendo el tipo de asistencia (presencial, virtual, gratuita).
Login y Logout: Los usuarios pueden iniciar sesión y cerrar sesión en la plataforma.
Confirmación por correo electrónico: Es obligatorio confirmar la cuenta a través del correo antes de completar el registro.
2. Inscripción a eventos
Los usuarios pueden seleccionar conferencias y talleres a los que desean asistir. Cada tipo de evento tiene un cupo limitado.
Se muestra la disponibilidad en tiempo real durante el proceso de inscripción.
Los usuarios pueden asistir a un máximo de 5 conferencias y 4 talleres.
3. Gestión de pagos
Los usuarios pueden realizar pagos a través de PayPal para completar su inscripción.
Una vez realizado el pago, se genera un comprobante y se envía por correo electrónico.
4. Gestión de eventos y ponentes (para administradores)
Los administradores pueden ver y gestionar los eventos y ponentes de las jornadas.
Es posible añadir nuevos eventos, asignarles un ponente y gestionar los detalles.
Los ponentes tienen una foto, nombre y redes sociales asociadas.
5. Visibilidad de ingresos
Los administradores pueden consultar los ingresos recibidos a través de los pagos de los asistentes.

## Rutas
### Rutas públicas:
- /register: Formulario de registro de usuario.
- /login: Formulario de inicio de sesión.
- /eventos: Ver lista de eventos y talleres disponibles.

### Rutas protegidas (requieren autenticación):
- /dashboard: Página principal del usuario autenticado.
- /profile: Página de edición del perfil del usuario.

### Rutas de administración (requieren autenticación y rol de administrador):
- /admin: Panel de administración.
- /admin/ponentes: Gestión de ponentes.
- /admin/eventos: Gestión de eventos.
- /admin/ingresos: Ver ingresos recibidos.

## Estructura del proyecto

El proyecto sigue el patrón MVC (Modelo-Vista-Controlador), y se utiliza repositorios para la interacción con la base de datos.

### Directorios principales:
- app/Http/Controllers: Controladores de la aplicación (gestión de usuarios, eventos, pagos, etc.).
- app/Models: Modelos de la base de datos (Usuario, Evento, Inscripción, etc.).
- resources/views: Archivos Blade para las vistas de la aplicación.
- database/migrations: Archivos de migración para la base de datos.

## Consideraciones
- Validación de formularios: Todos los formularios de registro, login y pago están validados.
- Comentarios: El código está comentado para facilitar su comprensión.
- Git y GitHub: Este proyecto está versionado mediante Git y alojado en GitHub. Puedes clonar el repositorio y hacer tus propios cambios si es necesario.