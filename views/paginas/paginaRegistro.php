<!DOCTYPE html>
 <head>
	<!-- Creado por Luis Fernández | Última Actualización 12/7/2026 -->
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../../public/img/logos/favicon.png" type="image/png">
	<link rel="stylesheet" href="../../public/css/paginaRegistro.css">
	<title> Zapatería Zora </title>
 </head>
 <body>
 	<?php require __DIR__ . '/header.php'; ?>
	<!--====================================================
					   Main del Registro
	Es un contenedor el cual contiene los apartados para
	ingresar datos para registrarse y crear una cuenta,
	contando también con un apartado por si ya tienes una cuenta,
	al cual si le haces click te mandará a la página de Inicio
	de Sesión.
	=====================================================-->
	<main>
		<section class="registro">
			<div class="titulo-registro">
				<h1>Crear cuenta</h1>
				<p>Regístrate para comprar en Zora.</p>
			</div>
			<form id="formRegistro" class="formulario-registro">
				<label for="nombre">Nombre</label>
				<input type="text" id="nombre" placeholder="Ingresa tu nombre">
				<label for="apellido">Apellido</label>
				<input type="text" id="apellido" placeholder="Ingresa tu apellido">
				<label for="correo">Correo electrónico</label>
				<input type="email" id="correo" placeholder="ejemplo@email.com">
				<label for="password">Contraseña</label>
				<input type="password" id="password" placeholder="********">
				<label for="confirmarPassword">Confirmar contraseña</label>
				<input type="password" id="confirmarPassword" placeholder="********">
				<button type="submit">Crear cuenta</button>
				<p class="iniciar-sesion">¿Ya tienes una cuenta?<a href="paginaInicioSesion.php">Inicia sesión</a></p>
			</form>
		</section>
	</main>
	<br>
	<!--====================================================
					        FOOTER
	Este banner es el último apartado visual que ve el usuario
	en la página principal, en este se encuentran las Redes
	Sociales de la marca y unos enlaces para ir a las otras
	partes de la página (Productos, Ofertas (página principal)
	y la página de registro).
	=====================================================-->
	<footer>
		<div class="footer-contenido">
			<div class="footer-marca">
				<h2>Zora</h2>
				<p>Más que calzados, tu esencia.</p>
			</div>
			
			<div class="footer-contacto">
				<h3>Contacto</h3>
				<p>📞 (+598) 094 812 290</p>
				<a href="mailto:digitalrocklp@gmail.com">📧 digitalrocklp@gmail.com</a>
				<p>📍 Las Piedras, Uruguay</p>
			</div>
			
			<div class="footer-tienda">
            <h3>Tienda</h3>
				<a href="paginaListadoProductos.php">👟 Productos</a>
				<a href="../../index.php">🏷️ Ofertas</a>
				<a href="paginaRegistro.php">👤 Registro</a>
			</div>
			
			<div class="footer-redes">
            <h3>Redes</h3>
				<a href="https://www.instagram.com/digitalrocklp/">📷 Síguenos!</a>
				<a href="https://www.tiktok.com/@digitalrocklp">✨ Inspírate con Zora!</a>
				<a href="https://x.com/DigitalRockLP">🐦 Novedades!</a>
				<a href="https://t.me/+IV4q_fwLv-NkNDZh">⚡ Resolvemos tus dudas al instante!</a>
			</div>
		</div>
		 <p class="copyright">© 2026 Zora. Todos los derechos reservados.</p>
	</footer>
	<!--====================================================
							Script
	Esto sirve para que el archivo ".js" de la página funcione
	en conjunto con el HTML, sin esto en el HTML, el código
	no funcionara correctamente.
	=====================================================-->
	<script src="../../public/js/paginaRegistro.js"></script>
 </body>
</html>