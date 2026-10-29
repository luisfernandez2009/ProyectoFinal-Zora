# Despliegue de Zora en Ubuntu Server 24.04 LTS

Esta guía sirve para ejecutar **el mismo repositorio** en Ubuntu Server y en Windows + XAMPP.

## 1. Instalar dependencias

```bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-mbstring php-xml php-curl git
sudo systemctl enable --now apache2 mysql
```

No es necesario instalar XAMPP en Ubuntu. Para acceder desde el equipo anfitrión de VirtualBox, configurar **adaptador puente** o **NAT con redirección del puerto 80** y revisar UFW si está activado.

## 2. Descargar el proyecto

```bash
cd /var/www/html
sudo git clone https://github.com/luisfernandez2009/ProyectoFinal-Zora.git
cd ProyectoFinal-Zora
sudo git fetch origin
sudo git switch compat/ubuntu-server
```

Abrir `http://IP_DEL_SERVIDOR/ProyectoFinal-Zora/index.php`. No se necesitan reglas de URL amigable porque el router usa `index.php?pagina=...`.

## 3. Crear la base y un usuario propio

Importar el esquema en una base nueva (no repetir esta importación encima de una base que contenga datos importantes):

```bash
sudo mysql < database/script.sql
sudo mysql
```

En la consola de MySQL (elegir una contraseña segura **propia**, no compartirla ni subirla):

```sql
CREATE USER 'zora_app'@'localhost' IDENTIFIED BY 'REEMPLAZAR_CON_CONTRASENA_SEGURA';
GRANT SELECT, INSERT, UPDATE, DELETE ON zora.* TO 'zora_app'@'localhost';
```

El script crea la base de datos **zora**. Si ya hay datos de XAMPP que se quieran conservar, importar un respaldo de ellos en lugar de reinicializar la base.

## 4. Configurar conexión MySQL sin modificar el código compartido

`config/conexion.php` admite estas variables de entorno:

| Variable | Valor predeterminado (Windows/XAMPP) |
|---|---|
| `ZORA_DB_HOST` | `localhost` |
| `ZORA_DB_USER` | `root` |
| `ZORA_DB_PASSWORD` | cadena vacía |
| `ZORA_DB_NAME` | `zora` |

Para Ubuntu con el módulo Apache de PHP, crear **solo en el servidor** un archivo `/etc/apache2/conf-available/zora-db.conf` con este contenido de ejemplo (no versionarlo):

```apache
<IfModule mod_env.c>
    SetEnv ZORA_DB_HOST localhost
    SetEnv ZORA_DB_USER zora_app
    SetEnv ZORA_DB_PASSWORD "REEMPLAZAR_CON_CONTRASENA_SEGURA"
    SetEnv ZORA_DB_NAME zora
</IfModule>
```

Restringir su lectura y activarlo:

```bash
sudo chown root:root /etc/apache2/conf-available/zora-db.conf
sudo chmod 600 /etc/apache2/conf-available/zora-db.conf
sudo a2enconf zora-db
sudo apache2ctl configtest
sudo systemctl reload apache2
```

Elegir un secreto compatible con la sintaxis de Apache y probar la conexión. **No guardar contraseñas reales en GitHub ni en capturas de pantalla.** Si se despliega con PHP-FPM en vez de `libapache2-mod-php`, la propagación de variables requiere una configuración distinta.

En XAMPP no se exige definir ninguna variable: se mantienen los valores predeterminados de la aplicación. Si tu MySQL local usa otras credenciales, configurá esas variables en ese entorno.

## 5. Permisos de imágenes

El administrador carga imágenes en `public/img/productos`. El proceso de Apache (`www-data`) requiere permiso de escritura sobre **esa carpeta**, no sobre todo el código.

```bash
sudo chgrp -R www-data public/img/productos
sudo find public/img/productos -type d -exec chmod 2775 {} \;
sudo find public/img/productos -type f -exec chmod 664 {} \;
```

Para entornos donde el propietario no pertenece al grupo adecuado, ajustar el propietario de esa carpeta con un administrador. Evitar `chmod -R 777`. Como el formulario acepta imágenes de hasta 5 MB, verificar `upload_max_filesize` y `post_max_size` en la configuración de PHP.

## 6. SMTP (PHPMailer)

El archivo privado `config/correo_config.php` **no está en el repositorio**: está ignorado por Git. Copiarlo por un medio privado desde la computadora local al servidor, o construirlo desde `config/correo_config_ejemplo.php` y completar datos solo en Ubuntu. No compartir las credenciales por Git, chats públicos ni documentación.

```bash
sudo chown root:www-data config/correo_config.php
sudo chmod 640 config/correo_config.php
```

Comprobar conexión saliente al proveedor SMTP. Si falta el archivo o el SMTP falla, el código registra el error sin cancelar la operación principal.

## 7. Pruebas antes de unir a main

1. Cargar la página inicial, catálogo, buscador, filtros y detalle.
2. Registrar un usuario e iniciar/cerrar sesión.
3. Crear un pedido con stock de prueba y consultar historial/factura.
4. Probar paneles de cliente, moderador y administrador.
5. Subir una imagen JPG, PNG o WEBP desde el panel administrativo.
6. Enviar una consulta y probar correo SMTP; mirar `sudo tail -n 60 /var/log/apache2/error.log` ante fallos.

**Importante:** `git pull` actualiza código, no la base de datos MySQL ni la configuración SMTP privada. Hacer una copia de seguridad antes de cualquier migración de tablas.

En un servidor público se recomienda además HTTPS, copias de seguridad, limitar accesos, y mover las configuraciones privadas fuera del árbol servido por Apache.
