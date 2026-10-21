<?php require __DIR__ . '/header.php'; ?>
 <head>
	<!-- Creado por Luis Fernández | Última Actualización 12/7/2026 -->
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../../public/img/logos/favicon.png" type="image/png">
	<link rel="stylesheet" href="../../public/css/paginaDetalleProducto.css">
	<title> Zapatería Zora </title>
 </head>
 <body>
	<a href="paginaListadoProductos.php" class="volver">← Volver al catálogo</a>
	<!--====================================================
					Main de los detalles
	Es un contenedor el cual contiene las descripciones,
	imagenes, precios, calificación, talles, stock y
	caracteristicas del producto en cuestión que se haya
	elegido con anterioridad (como demostración para la
	primera entrega solo hay de 1 producto por ahora, así
	que cualquier botón "Ver detalles" de las páginas
	anteriores te enviaran a la de este producto "Nike
	Airmax 270".
	=====================================================-->
	<main>
		<section class="detalle-producto">
			<div class="galeria">
				<img src="../../public/img/productos/placeholder.png" alt="placeholder" class="imagen-principal">
				<div class="miniaturas">
					<img src="../../public/img/productos/placeholder.png" alt="miniatura1" class="miniatura activa">
					<img src="../../public/img/productos/placeholder.png" alt="miniatura2" class="miniatura">
					<img src="../../public/img/productos/placeholder.png" alt="miniatura3" class="miniatura">
				</div>
			</div>
			<div class="info-producto">
				<h1>Nombre del producto</h1>
				<p class="precio">Precio del producto</p>
				<p class="estrellas">★★★★★</p>
				<p class="descripcion">Descripción breve del producto.</p>
				<p class="stock">✔ Stock disponible</p>
				<h3>Talles</h3>
				<div class="talles">
					<button class="talle">39</button>
					<button class="talle">40</button>
					<button class="talle">41</button>
					<button class="talle">42</button>
					<button class="talle">43</button>
				</div>
				<button class="agregar-carrito" id="carrito-compra">Agregar al carrito</button>
				<p id="mensajeCarrito"></p>
			</div>
		</section>
		<section class="descripcion-completa">
			<h2>Descripción</h2>
			<p>La descripción completa del producto se cargará aquí.</p>
			<h2>Características</h2>
			<ul>
				<li>Las características del producto se cargarán aquí.</li>
			</ul>
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
	<script src="../../public/js/paginaDetalleProducto.js"></script>
 </body>
</html>