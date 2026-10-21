<?php require __DIR__ . '/header.php'; ?>
	<link rel="stylesheet" href="../../public/css/paginaInicio.css">
	<!--====================================================
						BANNER PRINCIPAL
	Este banner es la primera sección visual que observa el
	usuario al ingresar al sitio web.
	Sirve para promocionar al usuario una colección nueva de
	la página web.
	=====================================================-->
	<div class="contenedor-banner">
		<img class="banner" src="../../public/img/banners/banner1.png" alt="Banner1">
		<div class="texto-banner">
			<h1>Nueva colección 2026</h1>
			<p>Camina con confianza.</p>
			<p>Descubre la nueva colección Zora.</p>
			<a href="paginaListadoProductos.php">Explorar colección.</a>
		</div>
	</div>
	<!--====================================================
					Productos Destacados
	Esto es una sección que agrupa las tarjetas de los
	productos destacados de la página, muestra el nombre del
	producto, la calificación, el precio y te da un botón
	para ir a la página de la información del producto.
	Esta página cuenta con 3 tarjetas de productos destacados.
	=====================================================-->
	<section class="productos oculto">
		<h2>Productos destacados</h2>
		<div class="contenedor-productos">
			<div class="producto">
				<img src="../../public/img/productos/nike-airmax270.jpg" alt="Nike Air Max 270">
				<h3>Nike Air Max 270</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$6.590</p>
				<a href="paginaDetalleProducto.php?producto=nike-airmax270">Ver detalles</a>
			</div>
			<div class="producto">
				<img src="../../public/img/productos/adidas-ultraboost-light.jpg" alt="Adidas Ultraboost Light">
				<h3>Adidas Ultraboost Light</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$8.490</p>
				<a href="paginaDetalleProducto.php?producto=adidas-ultraboost-light">Ver detalles</a>
			</div>
			<div class="producto">
				<img src="../../public/img/productos/new-balance-574.jpg" alt="New Balance 574">
				<h3>New Balance 574</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$5.990</p>
				<a href="paginaDetalleProducto.php?producto=new-balance-574">Ver detalles</a>
			</div>
			<div class="producto">
				<img src="../../public/img/productos/puma_rs-x_b&w.jpg" alt="Puma RS-X B&W">
				<h3>Puma RS-X B&W</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$5.290</p>
				<a href="paginaDetalleProducto.php?producto=puma-rs-x-bw">Ver detalles</a>
			</div>
		</div>
	</section>
	<!--====================================================
							Ventajas
	Esto es una sección de ventajas, esto es para que el
	Usuario/Cliente que entre a la página y dude en comprar,
	pueda tener la seguridad de que a la hora de elegirnos
	como página de compra va a tener ciertas ventajas.
	=====================================================-->
	<section class="ventajas oculto">
		<h2>¿Por qué elegirnos a nosotros?</h2>
		<div class="contenedor-ventajas">
			<div class="ventaja">
				<img src="../../public/img/logos/camion.png" alt="Envíos">
				<h3>🚚 Hacemos envíos a todo Uruguay!</h3>
				<p>Recibe tus zapatillas de forma rápida y segura en cualquier departamento.</p>
			</div>
			<div class="ventaja">
				<img src="../../public/img/logos/escudo.png" alt="Seguridad">
				<h3>🛡️ Compra segura!</h3>
				<p>Tus datos y pagos están protegidos.</p>
			</div>
			<div class="ventaja">
				<img src="../../public/img/logos/zapato.png" alt="Original">
				<h3>👟 Marcas originales!</h3>
				<p>Trabajamos únicamente con productos auténticos.</p>
			</div>
			<div class="ventaja">
				<img src="../../public/img/logos/estrella.png" alt="Calidad">
				<h3>⭐ Calidad garantizada!</h3>
				<p>Elegimos modelos cómodos y duraderos.</p>
			</div>
		</div>
	</section>
	<!--====================================================
							Reseñas
	Esto es una sección de reseñas, es un pequeño apartado
	con 3 reseñas (que inventé) de clientes que ya han
	realizado una compra en el sitio, estas reseñas contienen
	el nombre del Usuario que compro, las estrellas que le da
	a su compra y una pequeño parrafo que sería la reseña.
	=====================================================-->
	<section class="reseñas oculto">
		<h2>Lo que opinan nuestros clientes</h2>
		<div class="contenedor-reseñas">
			<div class="reseña">
				<img src="../../public/img/clientes/cliente1.png" alt="Sofía Martínez">
				<h3>Sofía Martínez</h3>
				<p class="estrellas">★★★★★</p>
				<p>"Las zapatillas llegaron antes de lo esperado. Muy cómodas y la atención fue excelente."</p>
			</div>
			<div class="reseña">
				<img src="../../public/img/clientes/cliente2.png" alt="Martín Rodríguez">
				<h3>Martín Rodríguez</h3>
				<p class="estrellas">★★★★☆</p>
				<p>"Compré unas Adidas para correr y la calidad superó mis expectativas. Seguro vuelvo a comprar."</p>
			</div>
			<div class="reseña">
				<img src="../../public/img/clientes/cliente3.png" alt="Valentina Pereira">
				<h3>Valentina Pereira</h3>
				<p class="estrellas">★★★★★</p>
				<p>"El sitio es muy fácil de usar y el envío fue rapidísimo. Recomiendo comprar en Zora sin dudas!"</p>
			</div>
		</div>
	</section>
	<!--====================================================
					  BANNER SECUNDARIO
	Este banner es el último apartado visual (antes del footer)
	que ve el usuario en la página principal, sirve también
	para redirigirlo a el catálogo.
	Sirve para promocionar al usuario otra colección nueva de
	la página web.
	=====================================================-->
	<div class="contenedor-banner">
		<img class="banner" src="../../public/img/banners/banner2.png" alt="Banner2">
		<div class="texto-banner">
			<h1>Nueva temporada</h1>
			<p>Descubrela y caminaras sobre nubes!</p>
			<a href="paginaListadoProductos.php">Comprar ahora</a>
		</div>
	</div>
	 <!--====================================================
					Botón para ir arriba
	Como el nombre indica, es un botón para volver hasta arriba
	de la página.
	=====================================================-->
	<div class="volver-arriba">
		<a href="#">⬆ Volver arriba</a>
	</div>
	
	<!--====================================================
							Script
	Esto sirve para que el archivo ".js" de la página funcione
	en conjunto con el HTML, sin esto en el HTML, el código
	no funcionara correctamente.
	=====================================================-->
	<script src="../../public/js/paginaInicio.js"></script>
 <?php require __DIR__ . '/footer.php'; ?>