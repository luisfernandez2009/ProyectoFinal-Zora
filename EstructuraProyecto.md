# Estructura actual del proyecto Zora

> Actualizado para la versión PHP + MySQL con patrón Modelo-Vista-Controlador (MVC), panel de administración, pedidos, pagos de prueba, mensajería y notificaciones por correo.
>
> La estructura de archivos se basa en el punto de entrada `index.php`, las rutas que este carga y la organización descrita en el README del repositorio. Algunas carpetas incluyen archivos adicionales que pueden variar según la versión local; la lista funcional de abajo destaca los componentes conocidos del sistema.

## 1. Árbol de carpetas

```text
ProyectoFinal-Zora/
├── index.php
├── README.md
├── .gitignore
├── config/
│   ├── conexion.php
│   ├── correo.php
│   ├── correo_config_ejemplo.php
│   └── correo_config.php              # Solo local; excluido de Git
│
├── controllers/
│   ├── ProductoController.php
│   ├── UsuarioController.php
│   ├── PedidoController.php
│   ├── AdminUsuarioController.php
│   ├── AdminProductoController.php
│   ├── AdminPanelController.php
│   ├── AdminPedidoController.php
│   ├── MensajeController.php
│   └── AdminMensajeController.php
│
├── models/
│   ├── ProductoModel.php
│   ├── UsuarioModel.php
│   ├── PedidoModel.php
│   ├── PagoModel.php
│   └── MensajeModel.php
│
├── views/
│   ├── assets/
│   │   ├── header.php
│   │   └── footer.php
│   ├── paginas/
│   │   ├── home.php
│   │   ├── catalogo / listado de productos
│   │   ├── detalle de producto
│   │   ├── registro.php
│   │   ├── login.php
│   │   ├── carrito.php
│   │   ├── pedido_confirmado.php
│   │   ├── pago y resultado de pago
│   │   ├── mis_pedidos.php
│   │   ├── detalle de pedido y factura
│   │   ├── contacto.php
│   │   ├── mis_mensajes.php
│   │   └── noEncontrado.php
│   └── admin/
│       ├── panel de administración
│       ├── productos y formularios ABM
│       ├── usuarios
│       ├── pedidos, detalle y factura
│       ├── informe de transacciones
│       ├── lista de mensajes
│       └── detalle y respuesta de mensaje
│
├── public/
│   ├── css/
│   │   ├── estilos de la página de inicio
│   │   ├── estilos del catálogo y carrito
│   │   ├── estilos de administración
│   │   └── mensajes.css
│   ├── js/
│   │   ├── página de inicio y catálogo
│   │   ├── carrito.js
│   │   └── validaciones e interacciones de formularios
│   └── img/
│       └── imágenes utilizadas por el sitio
│
├── libs/
│   └── PHPMailer/
│       ├── Exception.php
│       ├── PHPMailer.php
│       └── SMTP.php
│
└── database/
    ├── script.sql
    └── migraciones/
        ├── 2026_stock_minimo.sql
        ├── 2026_pagos_facturas.sql
        ├── 2026_rol_moderador.sql
        ├── 2026_stock_galeria_admin.sql
        ├── 2026_admin_pedidos.sql
        └── 2026_mensajeria_interna.sql
```

**Importante sobre el árbol:** los nombres indicados como descripciones dentro de `views/paginas/`, `views/admin/` y `public/` representan grupos funcionales, no necesariamente nombres literales de archivos. Para una entrega técnica final, se recomienda contrastar el árbol con el explorador de archivos del repositorio y reemplazar esas descripciones por cada nombre real.

El archivo `config/correo_config.php` se muestra para explicar la configuración local; no debe añadirse al repositorio ni distribuirse con credenciales reales.

## 2. Punto de entrada y enrutamiento: `index.php`

`index.php` es el punto de entrada principal de la aplicación. Inicia la sesión, carga los archivos de configuración, modelos y controladores, verifica el estado del usuario autenticado y decide qué controlador o vista corresponde a la ruta solicitada mediante el parámetro `pagina`.

Ejemplos de rutas utilizadas:

| Ruta | Función |
|---|---|
| `index.php?pagina=home` | Página principal |
| `index.php?pagina=productos` | Catálogo |
| `index.php?pagina=detalle` | Detalle de producto |
| `index.php?pagina=registro` | Formulario de registro |
| `index.php?pagina=login` | Inicio de sesión |
| `index.php?pagina=carrito` | Carrito de compras |
| `index.php?pagina=mis-pedidos` | Historial del cliente |
| `index.php?pagina=factura` | Factura de un pedido |
| `index.php?pagina=contacto` | Formulario de contacto |
| `index.php?pagina=mis-mensajes` | Mensajes del cliente |
| `index.php?pagina=admin-panel` | Panel de administración |
| `index.php?pagina=admin-productos` | Administración de productos |
| `index.php?pagina=admin-usuarios` | Administración de usuarios |
| `index.php?pagina=admin-pedidos` | Administración de pedidos |
| `index.php?pagina=admin-informe` | Informe de transacciones |
| `index.php?pagina=admin-mensajes` | Bandeja administrativa de mensajes |

Los nombres de rutas se muestran como referencia; los parámetros adicionales, como los identificadores, dependen de la acción específica.

## 3. Responsabilidad de cada carpeta

### `config/`

Contiene la configuración del sistema. `conexion.php` proporciona la conexión a MySQL. `correo.php` define la función centralizada de envío de correo con PHPMailer. `correo_config_ejemplo.php` documenta la estructura de la configuración SMTP.

El archivo local `correo_config.php` contiene los valores privados del entorno y está excluido de Git mediante `.gitignore`.

### `models/`

Los modelos realizan las operaciones de datos y encapsulan las consultas SQL.

- `ProductoModel.php`: consultas y operaciones de productos, categorías y datos de catálogo.
- `UsuarioModel.php`: registro, autenticación y datos de las cuentas.
- `PedidoModel.php`: creación y consulta de pedidos y sus detalles.
- `PagoModel.php`: registro y consulta del resultado de pagos de prueba.
- `MensajeModel.php`: registro, consulta y respuesta de mensajes.

Las consultas que reciben valores externos deben utilizar consultas preparadas.

### `controllers/`

Los controladores reciben solicitudes, validan datos, aplican las comprobaciones de acceso, coordinan los modelos y seleccionan las vistas.

- `ProductoController.php`: catálogo y detalle de productos.
- `UsuarioController.php`: registro, inicio y cierre de sesión.
- `PedidoController.php`: carrito, confirmación de compra, pago de prueba, historial y factura.
- `AdminUsuarioController.php`: consulta de usuarios y administración de roles/estado.
- `AdminProductoController.php`: listado y operaciones ABM de productos.
- `AdminPanelController.php`: información general del panel.
- `AdminPedidoController.php`: pedidos administrativos, envío, facturas e informe.
- `MensajeController.php`: formulario de contacto y mensajes del cliente.
- `AdminMensajeController.php`: bandeja, detalle y respuesta de mensajes administrativos.

### `views/`

Contiene la presentación HTML que recibe el usuario.

- `views/assets/`: componentes compartidos, como encabezado y pie de página.
- `views/paginas/`: páginas públicas y de cliente.
- `views/admin/`: pantallas del panel administrativo.

Las vistas no deben establecer conexiones directas a la base de datos. La información necesaria debe llegar a ellas desde los controladores y modelos.

### `public/`

Contiene recursos estáticos que el navegador puede cargar, como CSS, JavaScript e imágenes. Aquí se encuentran los estilos específicos del catálogo, carrito, mensajes y demás pantallas, así como los scripts de interacción de la interfaz.

### `libs/PHPMailer/`

Contiene la biblioteca PHPMailer utilizada para el envío de correos por SMTP. Los archivos de la biblioteca se cargan desde `config/correo.php`.

### `database/`

Contiene el script SQL principal y las migraciones incrementales. El script principal prepara la estructura de la base de datos y los datos de demostración; las migraciones actualizan instalaciones existentes para incorporar cambios de esquema.

## 4. Relación entre MVC y el flujo de una solicitud

Ejemplo: el cliente consulta el catálogo.

1. El navegador solicita `index.php?pagina=productos`.
2. `index.php` identifica la ruta y llama a `ProductoController`.
3. El controlador solicita los datos a `ProductoModel`.
4. El modelo consulta MySQL y devuelve los resultados.
5. El controlador entrega los datos a la vista del catálogo.
6. La vista genera el HTML que recibe el navegador.

Ejemplo: el administrador responde una consulta.

1. El administrador abre una consulta desde el panel.
2. El enrutador dirige la solicitud a `AdminMensajeController`.
3. El controlador comprueba que el usuario tenga permisos administrativos.
4. `MensajeModel` guarda la respuesta en la base de datos.
5. La función de correo intenta enviar una notificación al cliente.
6. La interfaz muestra el resultado de la operación.

El correo es una notificación complementaria: un error de SMTP se registra para diagnóstico y no debe confundirse con el resultado de la operación principal que ya se guardó.

## 5. Base de datos y migraciones

El esquema SQL principal se encuentra en `database/script.sql`. Las migraciones existentes documentan cambios agregados a lo largo del desarrollo, entre ellos:

- Stock mínimo.
- Pagos y facturas.
- Rol moderador.
- Galería de imágenes y stock por talle.
- Administración del estado de envío de pedidos.
- Mensajería interna.

Las migraciones deben ejecutarse solo cuando corresponda al estado de la base de datos local. Antes de ejecutar una migración, se debe comprobar si sus tablas, columnas e índices ya existen. No se recomienda borrar una base con datos útiles sin disponer de una copia de seguridad.

## 6. Seguridad y archivos sensibles

- La configuración real del correo se mantiene fuera del repositorio.
- Las credenciales no deben escribirse en el código fuente ni en la documentación pública.
- Las contraseñas de usuarios se almacenan mediante hashing.
- Las consultas con datos externos deben utilizar sentencias preparadas.
- Las salidas HTML deben escapar los datos no confiables para reducir el riesgo de XSS.
- Las rutas administrativas deben verificar sesión y rol en el servidor.
- Los datos de compra, precios y stock se verifican en el servidor antes de confirmar el pedido.
- No se deben almacenar el número completo de una tarjeta ni el CVV en el sistema de pago simulado.

## 7. Ejecutar el proyecto localmente

1. Colocar el proyecto dentro de la carpeta de aplicaciones web de XAMPP.
2. Iniciar Apache y MySQL.
3. Configurar la conexión en `config/conexion.php`.
4. Importar `database/script.sql` en phpMyAdmin si se crea una instalación nueva.
5. Aplicar únicamente las migraciones que falten en una instalación existente.
6. Copiar `config/correo_config_ejemplo.php` como `config/correo_config.php` y completar la configuración SMTP local sin subirla a GitHub.
7. Abrir `http://localhost/ProyectoFinal-Zora/index.php`.
8. Probar las funciones según el rol de cada cuenta.

La configuración local de correo, las cuentas de prueba y los datos de demostración deben ajustarse a cada entorno.

## 8. Repositorio

Código fuente: https://github.com/luisfernandez2009/ProyectoFinal-Zora

Para la entrega digital debe proporcionarse el código completo de la aplicación, el script SQL actualizado, las migraciones necesarias y la documentación. En la versión impresa no es necesario incluir el código fuente completo.
