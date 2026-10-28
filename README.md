# Zora

Está es la versión de la página web para la primera entrega del proyecto, es de una zapatería (la cual creamos entre mis compañeros de equipo y yo), el apartado del código de la web lo hice yo, mientras que mis compañeros me ayudaron a elegir los colores y el logo de la página.
Las demás personas de mi equipo no participaron mucho más en el código porque repartimos las materias del proyecto en 1 para cada uno (Por ejemplo yo elegí Programación Full Stack, mientras que Diego Berón eligió Sistema Operativos, José Posse eligió Ingeniería de Software y Ismael Cabrera eligió Inglés y Filosofía).

## Tecnologías utilizadas

- HTML5
Se utilizó HTML5 para estructurar el contenido de todas las páginas del sitio web, organizando la información mediante etiquetas semánticas y facilitando la navegación entre las diferentes secciones.

- CSS3
Se utilizó CSS3 para diseñar la interfaz gráfica del sitio, aplicando colores, tipografías, animaciones, Flexbox, Grid y diseño responsive para mejorar la experiencia del usuario en distintos dispositivos.

- JavaScript
JavaScript fue utilizado para incorporar interactividad al sitio, implementando funcionalidades como filtros de productos, buscador, validaciones de formularios, selección de talles, cambio de imágenes del producto y mensajes dinámicos para el usuario.

## Tecnologías no utilizadas

- Bootstrap no fue utilizado durante el desarrollo del proyecto.

## Nota

- No pude documentar los css y los js porque tenía pensado documentarlos por bloques, pero a la hora de programar cometí un par de descuidos y algunas líneas de código del css quedo separada de las otras, así que quedo todo desordenado. Mil disculpas y gracias por su comprensión.

## Funcionalidades

- Página de inicio.
- Catálogo de productos.
- Búsqueda GET en el catálogo, combinada con filtros por marca y con opción para limpiar la búsqueda.
- Filtros por marca de calzado.
- Página de detalle de los productos (tiene por el momento solo 1 producto con sus detalles ya que es la primera entrega recién).
- Página de Registro.
- Página de Inicio de sesión.
- Diseño responsive.
- Gestión administrativa de usuarios: roles, desactivación y reactivación de cuentas.
- Roles `cliente`, `moderador` (gestión de productos y pedidos) y `admin` (gestión de usuarios, productos y pedidos).
- Panel de administración centralizado con gestión de productos, usuarios (solo admin), pedidos, estados de envío, facturas e informe de transacciones.
- ABM administrativo de productos, imágenes, stock por talle y baja lógica.
- Carrito, creación de pedidos, simulación de pagos, historial de compras y factura imprimible.
- Mensajería interna entre clientes y tienda: consultas privadas por usuario, bandeja administrativa con filtros, respuestas no sobrescribibles y contador de pendientes en el panel.
- Notificaciones por correo SMTP mediante PHPMailer: bienvenida al registrarse, confirmación de compra aprobada, aviso al responder una consulta y aviso cuando el pedido pasa a enviado.

## Autor

Luis Alberto Fernández Caraballo.

## Arquitectura MVC

El proyecto usa `index.php` como punto de entrada único (router):

- `models/`: consultas preparadas a MySQL para productos, usuarios y pedidos.
- `controllers/`: coordina catálogo/detalle, registro/inicio de sesión y confirmación de pedidos.
- `views/paginas/`: vistas del inicio, catálogo, detalle, registro, login, carrito y confirmación.
- `views/assets/`: encabezado y pie compartidos.
- `public/js/`: interacción del catálogo y carrito local.
- `database/script.sql`: esquema y datos de demostración.

### Probar localmente

1. Iniciá Apache y MySQL en XAMPP.
2. Verificá en `config/conexion.php` el usuario, contraseña y nombre de base de datos (`zora`).
3. Importá `database/script.sql` en phpMyAdmin. Si ya tenés tablas con otra estructura, no las borres: primero verificá sus columnas y claves foráneas.
4. Si la base `zora` ya existía antes de los módulos administrativos y de pago, ejecutá las migraciones necesarias una sola vez: `2026_stock_minimo.sql` si falta `producto.stock_minimo`, `2026_pagos_facturas.sql` para pagos, `2026_rol_moderador.sql` para el rol moderador y `2026_stock_galeria_admin.sql` para crear la tabla de galería si falta, cargar talles 38–45 y asignar 5 unidades a las combinaciones de producto/talle que todavía no tengan stock registrado, y `2026_admin_pedidos.sql` para agregar el estado de envío separado, y `2026_mensajeria_interna.sql` para crear la tabla de consultas y respuestas. Estas migraciones conservan los stocks ya existentes. Si importaste el `database/script.sql` completo desde cero, las tablas ya están incluidas y no necesitás ejecutar esa migración de galería/stock.
5. Abrí el proyecto desde `http://localhost/ProyectoFinal-Zora/index.php`.
6. Registrá una cuenta. Para habilitar el panel administrativo, cambiá el rol de esa cuenta desde phpMyAdmin: `UPDATE usuario SET rol = 'admin' WHERE correo = 'tu-correo@gmail.com';` (reemplazá el correo por el de tu cuenta).
7. Probá catálogo, filtros, búsqueda, detalle, selección de talle, registro, inicio de sesión, carrito, gestión de usuarios/productos e historial de pedidos. Para finalizar una compra necesitás una cuenta registrada y stock en `producto_talle`.

El stock y los talles agregados por el script son datos de demostración para desarrollo. Ajustalos a la disponibilidad real antes de publicar. Los precios y el stock se verifican nuevamente en el servidor al confirmar el pedido; el navegador no decide el precio final.

### Pago de prueba

El pago es una simulación educativa, no una pasarela real. Para simular un pago aprobado, usá `4242 4242 4242 4242`, una fecha de vencimiento futura y un CVV de 3 o 4 dígitos. Una tarjeta de prueba válida que no termine en `4242` se simula como rechazada. No ingreses ni uses datos de tarjetas reales. El sistema solo guarda los últimos cuatro dígitos y nunca persiste el número completo, el vencimiento ni el CVV.



### Configurar notificaciones por correo

1. Verificá que PHPMailer esté en `lib/PHPMailer/` y que contenga `Exception.php`, `PHPMailer.php` y `SMTP.php`.
2. Copiá `config/correo_config_ejemplo.php` a `config/correo_config.php` en tu computadora.
3. En `config/correo_config.php`, completá el usuario del correo y su contraseña de aplicación SMTP. Para Gmail, usá una contraseña de aplicación, no la contraseña normal de la cuenta.
4. `config/correo_config.php` está excluido en `.gitignore`: no lo subas a GitHub. El archivo de ejemplo sí se versiona y no contiene credenciales reales.
5. Si el servidor de correo falla o falta la configuración local, la compra, el registro o la respuesta se mantienen guardados; el fallo de correo se registra en el log de errores de PHP/Apache.

Los correos se envían después de completar la operación correspondiente. El sitio no envía correo por un intento de pago rechazado.
