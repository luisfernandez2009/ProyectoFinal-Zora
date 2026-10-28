# Bocetos de páginas de Zora

> Documento actualizado para la versión PHP + MySQL con arquitectura MVC.
>
> Los dibujos son bocetos estructurales en texto, no capturas de pantalla finales. Deben complementarse con capturas reales de la aplicación ejecutada en XAMPP cuando se prepare la documentación de entrega.

## Elementos compartidos

Todas las páginas públicas utilizan un encabezado y un pie compartidos. El encabezado permite volver al inicio, abrir el catálogo, acceder al carrito, iniciar sesión o registrarse y entrar a las opciones de cuenta disponibles. Los enlaces administrativos se muestran según la sesión y el rol.

```text
+-----------------------------------------------------------------------+
| ZORA                                      Inicio | Catálogo | Carrito |
|                                            Contacto | Mi cuenta       |
+-----------------------------------------------------------------------+
|                         CONTENIDO DE LA PÁGINA                        |
+-----------------------------------------------------------------------+
| Zora | Información de la tienda | Contacto                            |
+-----------------------------------------------------------------------+
```

## 1. Página de inicio

```text
+-----------------------------------------------------------------------+
| HEADER: logo Zora | Inicio | Catálogo | Contacto | Cuenta | Carrito   |
+-----------------------------------------------------------------------+
|                         BANNER PRINCIPAL                              |
|              Mensaje de bienvenida / colección destacada              |
+-----------------------------------------------------------------------+
|                         PRODUCTOS DESTACADOS                          |
|                [Producto]       [Producto]       [Producto]           |
+-----------------------------------------------------------------------+
|                         VENTAJAS DE ZORA                              |
|                   [Ventaja 1] [Ventaja 2] [Ventaja 3]                 |
+-----------------------------------------------------------------------+
|                         BANNER SECUNDARIO                             |
+-----------------------------------------------------------------------+
| FOOTER: información general y acceso a contacto                       |
+-----------------------------------------------------------------------+
```

**Descripción:** página principal del comercio. Presenta la identidad visual de Zora y productos destacados recuperados desde la base de datos. Desde aquí se puede acceder al catálogo y a las demás secciones públicas.

**Ruta:** `index.php?pagina=home`

## 2. Catálogo de productos

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
| Catálogo de calzado                                                    |
| Buscar: [ nombre del producto........................ ] [Buscar]      |
| Marca/categoría: [Todos v] [Aplicar] [Limpiar filtros]                 |
+-----------------------------------------------------------------------+
| [Imagen]          [Imagen]          [Imagen]          [Imagen]         |
| Nombre            Nombre            Nombre            Nombre           |
| Precio            Precio            Precio            Precio           |
| [Ver detalle]     [Ver detalle]     [Ver detalle]     [Ver detalle]    |
+-----------------------------------------------------------------------+
| [Página anterior]                         [Página siguiente] (si aplica)|
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** muestra productos almacenados en MySQL. Permite buscar por texto y aplicar los filtros disponibles. Los nombres, precios, imágenes y disponibilidad no deben depender de datos escritos manualmente en la vista.

**Ruta:** `index.php?pagina=productos`

## 3. Detalle del producto

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
| [Volver al catálogo]                                                   |
| +--------------------------+  Nombre del producto                     |
| |                          |  Precio                                  |
| |      Imagen principal    |  Descripción                             |
| |                          |  Talle: [Seleccionar v]                  |
| +--------------------------+  Stock disponible                        |
| [Miniatura] [Miniatura]     |  Cantidad: [-] 1 [+]                    |
|                             |  [Agregar al carrito]                   |
+-----------------------------------------------------------------------+
| Productos relacionados (si corresponde)                               |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** presenta la información de un producto específico, las imágenes disponibles, el precio y las opciones de talle/stock que ofrece el sistema. La disponibilidad debe verificarse nuevamente en el servidor al confirmar la compra.

**Ruta:** `index.php?pagina=detalle&id=...` (el parámetro concreto debe coincidir con el controlador).

## 4. Registro

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                           CREAR CUENTA                                |
| Nombre:                 [......................................]      |
| Apellido:               [......................................]      |
| Correo electrónico:     [......................................]      |
| Contraseña:             [......................................]      |
| Confirmar contraseña:   [......................................]      |
|                         [Crear cuenta]                                |
| ¿Ya tienes cuenta? [Iniciar sesión]                                   |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** permite crear una cuenta. Los datos deben validarse en el navegador y nuevamente en PHP. La contraseña se almacena mediante hashing y, si el registro es correcto, se envía el correo de bienvenida configurado.

**Rutas:** `index.php?pagina=registro` y `index.php?pagina=procesar-registro`

## 5. Inicio de sesión

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                         INICIAR SESIÓN                                |
| Correo electrónico: [...........................................]      |
| Contraseña:         [...........................................]      |
|                         [Iniciar sesión]                              |
| ¿No tienes cuenta? [Registrarse]                                      |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** autentica al usuario y crea la sesión correspondiente. Las funciones disponibles dependen del rol y del estado de la cuenta.

**Rutas:** `index.php?pagina=login`, `index.php?pagina=procesar-login` y `index.php?pagina=logout`

## 6. Carrito de compras

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                            MI CARRITO                                 |
| Producto             Talle       Cantidad      Precio       Subtotal  |
| --------------------------------------------------------------------- |
| Calzado A            40          [-] 1 [+]     $....         $....     |
| Calzado B            42          [-] 2 [+]     $....         $....     |
|                                                                       |
| [Quitar artículo] [Seguir comprando]                                  |
|                                               Total: $........        |
|                                               [Confirmar pedido]      |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** reúne los productos elegidos y permite revisar las cantidades antes de confirmar. El navegador no es una fuente confiable para los precios ni para el stock: el servidor vuelve a consultarlos en la base de datos.

**Ruta:** `index.php?pagina=carrito`

## 7. Confirmación del pedido

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                      CONFIRMAR / PEDIDO REGISTRADO                    |
| Número de pedido: #....                                               |
| Resumen de productos, cantidades y total                              |
| Estado actual del pedido                                              |
|                                                                       |
| [Continuar al pago de prueba] [Ver mis pedidos]                       |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** muestra el resultado de la creación del pedido y permite continuar al pago simulado. La vista debe reflejar el pedido realmente registrado en la base de datos.

**Rutas:** `index.php?pagina=confirmar-pedido` y `index.php?pagina=pedido-confirmado`

## 8. Pago de prueba y resultado

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                         PAGO DE PRUEBA                                |
| Pedido: #....                                                         |
| Importe: $....                                                        |
| Número de tarjeta de prueba: [.................................]       |
| Vencimiento: [MM/AA]       CVV: [...]                                 |
|                         [Simular pago]                                |
|                                                                       |
| Aviso: entorno educativo; no ingresar datos de tarjetas reales.       |
+-----------------------------------------------------------------------+
| Resultado: [Pago aprobado / Pago rechazado]                           |
| [Ver pedido] [Volver a mis pedidos]                                   |
+-----------------------------------------------------------------------+
```

**Descripción:** simula el resultado de un pago; no es una pasarela bancaria real. La aplicación registra el resultado y conserva únicamente los últimos cuatro dígitos cuando corresponde. No se deben almacenar el número completo de tarjeta, el vencimiento ni el CVV.

**Rutas:** `index.php?pagina=pago` y `index.php?pagina=pago-resultado`

## 9. Historial de pedidos del cliente

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                         MIS PEDIDOS                                   |
| Pedido       Fecha        Estado          Total          Acción       |
| --------------------------------------------------------------------- |
| #....        ....         Pagado          $....          [Ver detalle]|
| #....        ....         Enviado         $....          [Ver detalle]|
|                                                                       |
| Selecciona un pedido para ver sus productos y su factura.             |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** permite al cliente consultar sus propias compras, el estado de cada pedido y acceder a su detalle. El servidor debe comprobar que el pedido pertenezca al usuario autenticado.

**Ruta:** `index.php?pagina=mis-pedidos`

## 10. Detalle del pedido y factura

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
| DETALLE DEL PEDIDO #....                                              |
| Cliente: ................................                             |
| Fecha: ................. Estado: .................                    |
| --------------------------------------------------------------------- |
| Producto / talle          Cantidad         Precio         Subtotal    |
| ..................................................................... |
|                                                                       |
| Total: $........                                                       |
| Datos del pago de prueba disponibles                                  |
| [Ver / imprimir factura] [Volver al historial]                        |
+-----------------------------------------------------------------------+
```

**Descripción:** presenta los datos de una compra y su detalle. La factura imprimible se construye con la información almacenada en el sistema.

**Rutas:** `index.php?pagina=pedido-detalle` y `index.php?pagina=factura`

## 11. Contacto

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                         CONTACTAR CON ZORA                            |
| Asunto: [.......................................................]     |
| Mensaje:                                                               |
| [................................................................]    |
| [................................................................]    |
|                         [Enviar consulta]                             |
|                                                                       |
| [Ver mis mensajes] (para usuarios autenticados)                       |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** formulario para que el cliente envíe una consulta. El servidor valida los datos y registra el mensaje en la base de datos. La página muestra la respuesta correspondiente al envío.

**Rutas:** `index.php?pagina=contacto` y `index.php?pagina=mensaje-enviar`

## 12. Mis mensajes

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                         MIS MENSAJES                                  |
| Asunto                    Fecha             Estado        Acción      |
| --------------------------------------------------------------------- |
| Consulta sobre pedido     ....              Respondido    [Ver]       |
| Consulta sobre producto   ....              Pendiente     [Ver]       |
|                                                                       |
| [Nueva consulta]                                                      |
+-----------------------------------------------------------------------+
| Detalle seleccionado: mensaje original y respuesta de Zora            |
+-----------------------------------------------------------------------+
```

**Descripción:** permite consultar las conversaciones del usuario autenticado y las respuestas recibidas. Un cliente no debe poder consultar mensajes de otras cuentas.

**Ruta:** `index.php?pagina=mis-mensajes`

## 13. Panel de administración

```text
+-----------------------------------------------------------------------+
| ZORA ADMIN                     Usuario conectado | Cerrar sesión     |
+-----------------------------------------------------------------------+
| PANEL DE ADMINISTRACIÓN                                               |
| [Productos] [Usuarios] [Pedidos] [Mensajes] [Informe]                |
|                                                                       |
| Resumen de actividad                                                  |
| [Pedidos] [Productos] [Usuarios] [Mensajes pendientes]                |
|                                                                       |
| Alertas de stock bajo (si corresponde)                               |
| Actividad reciente / accesos directos                                 |
+-----------------------------------------------------------------------+
```

**Descripción:** concentra las funciones de gestión. El acceso y las acciones se controlan en el servidor según el rol. El rol administrador gestiona usuarios, productos y pedidos; el rol moderador dispone de las funciones autorizadas para productos y pedidos.

**Ruta:** `index.php?pagina=admin-panel`

## 14. Administración de productos

```text
+-----------------------------------------------------------------------+
| ADMINISTRACIÓN > PRODUCTOS                                            |
| [Agregar producto]                                                    |
| Buscar: [...........................] [Buscar]                        |
| Producto        Precio       Stock       Estado       Acciones        |
| --------------------------------------------------------------------- |
| Calzado A       $....        ....        Activo       [Editar][Baja]  |
| Calzado B       $....        ....        Activo       [Editar][Baja]  |
+-----------------------------------------------------------------------+
| FORMULARIO DE ALTA / MODIFICACIÓN                                     |
| Nombre [...................] Categoría [..............]               |
| Precio [$........] Descripción [.................................]    |
| Imágenes / talles / stock                                              |
| [Guardar] [Cancelar]                                                  |
+-----------------------------------------------------------------------+
```

**Descripción:** permite dar de alta productos, modificar sus datos y cambiar su estado mediante las operaciones ABM. También contempla las imágenes y el stock por talle que maneja la aplicación.

**Rutas:** `index.php?pagina=admin-productos`, `index.php?pagina=admin-producto-formulario`, `index.php?pagina=admin-producto-guardar` y `index.php?pagina=admin-producto-estado`

## 15. Administración de usuarios

```text
+-----------------------------------------------------------------------+
| ADMINISTRACIÓN > USUARIOS (solo admin)                                |
| Buscar usuario: [.............................]                       |
| Nombre / correo          Rol             Estado         Acciones      |
| --------------------------------------------------------------------- |
| Cliente A                cliente         Activo         [Rol][Estado] |
| Usuario B                moderador       Activo         [Rol][Estado] |
|                                                                       |
| Confirmación de cambios de rol o activación/desactivación              |
+-----------------------------------------------------------------------+
```

**Descripción:** permite al administrador consultar las cuentas registradas, cambiar roles y activar o desactivar cuentas. Estas acciones no deben estar disponibles para clientes ni para moderadores.

**Rutas:** `index.php?pagina=admin-usuarios`, `index.php?pagina=admin-usuario-rol` y `index.php?pagina=admin-usuario-activo`

## 16. Administración de pedidos

```text
+-----------------------------------------------------------------------+
| ADMINISTRACIÓN > PEDIDOS                                              |
| Filtros: estado [Todos v] fecha [........] [Buscar]                    |
| Pedido    Cliente     Fecha      Total      Pago       Envío           |
| --------------------------------------------------------------------- |
| #....     Cliente A   ....       $....      Pagado     Pendiente       |
| [Ver detalle] [Ver factura] [Cambiar estado de envío]                 |
+-----------------------------------------------------------------------+
```

**Descripción:** permite revisar los pedidos de la base de datos, consultar su detalle, ver la factura y cambiar el estado del envío. Cuando el pedido pasa a enviado, se notifica al cliente por correo.

**Rutas:** `index.php?pagina=admin-pedidos`, `index.php?pagina=admin-pedido-detalle`, `index.php?pagina=admin-pedido-envio` y `index.php?pagina=admin-pedido-factura`

## 17. Informe de transacciones

```text
+-----------------------------------------------------------------------+
| ADMINISTRACIÓN > INFORME DE TRANSACCIONES                             |
| Desde [fecha] Hasta [fecha] Estado [Todos v] [Filtrar]                |
| Pedido       Fecha          Estado           Total                    |
| --------------------------------------------------------------------- |
| #....        ....           Aprobado         $....                    |
| #....        ....           Rechazado        $....                    |
|                                                                       |
| Resumen de cantidades e importes según los filtros                    |
+-----------------------------------------------------------------------+
```

**Descripción:** presenta información básica de las transacciones o pedidos registrados, de acuerdo con los datos y filtros disponibles en el sistema.

**Ruta:** `index.php?pagina=admin-informe`

## 18. Bandeja administrativa de mensajes

```text
+-----------------------------------------------------------------------+
| ADMINISTRACIÓN > MENSAJES                                             |
| Filtro: [Pendientes v] Buscar [................]                      |
| Cliente       Asunto              Fecha        Estado       Acción     |
| --------------------------------------------------------------------- |
| Cliente A     Consulta pedido     ....         Pendiente    [Abrir]    |
| Cliente B     Consulta producto   ....         Respondido   [Abrir]    |
+-----------------------------------------------------------------------+
```

**Descripción:** permite al personal autorizado revisar las consultas guardadas, aplicar filtros y abrir cada conversación. La bandeja muestra el estado de las consultas y facilita la atención de los mensajes pendientes.

**Ruta:** `index.php?pagina=admin-mensajes`

## 19. Detalle y respuesta administrativa de mensaje

```text
+-----------------------------------------------------------------------+
| ADMINISTRACIÓN > DETALLE DE MENSAJE                                   |
| Cliente: ................................                             |
| Asunto: ..................................                            |
| Fecha: ...................................                            |
| Mensaje del cliente:                                                   |
| [................................................................]    |
|                                                                       |
| Respuesta:                                                             |
| [................................................................]    |
| [................................................................]    |
|                         [Enviar respuesta]                            |
+-----------------------------------------------------------------------+
```

**Descripción:** permite leer una consulta y enviar una respuesta al cliente. La respuesta queda registrada en el sistema y se envía una notificación por correo cuando el envío está configurado correctamente.

**Rutas:** `index.php?pagina=admin-mensaje-detalle` y `index.php?pagina=admin-mensaje-responder`

## 20. Página no encontrada

```text
+-----------------------------------------------------------------------+
| HEADER                                                                |
+-----------------------------------------------------------------------+
|                         PÁGINA NO ENCONTRADA                          |
| No se encontró la página solicitada.                                  |
|                         [Volver al inicio]                            |
+-----------------------------------------------------------------------+
| FOOTER                                                                |
+-----------------------------------------------------------------------+
```

**Descripción:** se muestra cuando se solicita una ruta que no está reconocida por el enrutador.

**Comportamiento:** la aplicación establece el código HTTP 404.

## Recomendaciones para actualizar las capturas

- Ejecutar la versión actual del proyecto en XAMPP y capturar cada página real.
- Mantener el texto de explicación debajo de cada captura.
- Ocultar correos personales, datos de clientes y cualquier información privada.
- No mostrar contraseñas, credenciales SMTP ni datos de tarjetas reales.
- En la versión digital, incluir el código completo del proyecto; la versión impresa no necesita imprimirlo.
