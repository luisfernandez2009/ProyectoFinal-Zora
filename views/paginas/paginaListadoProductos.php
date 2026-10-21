 <?php require __DIR__ . '/header.php'; ?>
 <head>
	<!-- Creado por Luis Fernández | Última Actualización 12/7/2026 -->
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../../public/img/logos/favicon.png" type="image/png">
	<link rel="stylesheet" href="../../public/css/paginaListadoProductos.css">
	<title> Zapatería Zora </title>
 </head>
 <body>
	<a href="../../index.php" class="volver">← Volver al inicio</a>
	 <!--====================================================
						Main del Catálogo!
	Este main contiene las tarjetas de todos los productos de
	la página, además de también contener un buscador en tiempo
	real y un apartado de filtros por marca para los calzados.
	Cada tarjeta contiene el nombre del modelo, la calificación,
	el precio y un botón para ver los detalles.
	=====================================================-->
	<main>
		<section class="cabecera-catalogo">
			<section class="catalogo">
				<h1>Catálogo de productos</h1>
				<p>Encuentra el calzado ideal para cada ocasión.</p>
			</section>
		<div class="busqueda">
			<input type="text" id="buscador" placeholder="Buscar zapatillas...">
		</div>
		<div class="filtros">
			<button class="activo"data-filtro="todos">Todos</button>
			<button data-filtro="nike">Nike</button>
			<button data-filtro="adidas">Adidas</button>
			<button data-filtro="puma">Puma</button>
			<button data-filtro="newbalance">New Balance</button>
		</div>
		<p class="cantidad-productos">Mostrando 12 productos</p>
		</section>
		<section class="productos-catalogo">
			<div class="producto" data-marca="nike">
				<img src="../../public/img/productos/nike-airmax270.jpg" alt="Nike Air Max 270">
				<h3>Nike Air Max 270</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$6.590</p>
				<a href="paginaDetalleProducto.php?producto=nike-airmax270">Ver detalles</a>
			</div>
			<div class="producto" data-marca="nike">
				<img src="../../public/img/productos/nike-revolution-7.jpg" alt="Nike Revolution 7">
				<h3>Nike Revolution 7</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$4.390</p>
				<a href="paginaDetalleProducto.php?producto=nike-revolution-7">Ver detalles</a>
			</div>
			<div class="producto" data-marca="nike">
				<img src="../../public/img/productos/nike-pegasus-41.jpg" alt="Nike Pegasus 41">
				<h3>Nike Pegasus 41</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$7.990</p>
				<a href="paginaDetalleProducto.php?producto=nike-pegasus-41">Ver detalles</a>
			</div>
			<div class="producto" data-marca="adidas">
				<img src="../../public/img/productos/adidas-ultraboost-light.jpg" alt="Adidas Ultraboost Light">
				<h3>Adidas Ultraboost Light</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$8.490</p>
				<a href="paginaDetalleProducto.php?producto=adidas-ultraboost-light">Ver detalles</a>
			</div>
			<div class="producto" data-marca="adidas">
				<img src="../../public/img/productos/adidas-duramo-sl.jpg" alt="Adidas Duramo SL">
				<h3>Adidas Duramo SL</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$4.290</p>
				<a href="paginaDetalleProducto.php?producto=adidas-duramo-sl">Ver detalles</a>
			</div>
			<div class="producto" data-marca="adidas">
				<img src="../../public/img/productos/adidas-runfalcon-5.jpg" alt="Adidas Runfalcon 5">
				<h3>Adidas Runfalcon 5</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$4.990</p>
				<a href="paginaDetalleProducto.php?producto=adidas-runfalcon-5">Ver detalles</a>
			</div>
			<div class="producto" data-marca="puma">
				<img src="../../public/img/productos/puma_rs-x_b&w.jpg" alt="Puma RS-X B&W">
				<h3>Puma RS-X B&W</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$5.290</p>
				<a href="paginaDetalleProducto.php?producto=puma-rs-x-bw">Ver detalles</a>
			</div>
			<div class="producto" data-marca="puma">
				<img src="../../public/img/productos/puma-caven-2.0.jpg" alt="Puma Caven 2.0">
				<h3>Puma Caven 2.0</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$4.690</p>
				<a href="paginaDetalleProducto.php?producto=puma-caven-2">Ver detalles</a>
			</div>
			<div class="producto" data-marca="puma">
				<img src="../../public/img/productos/puma-velocity-nitro.jpg" alt="Puma Velocity Nitro">
				<h3>Puma Velocity Nitro</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$7.590</p>
				<a href="paginaDetalleProducto.php?producto=puma-velocity-nitro">Ver detalles</a>
			</div>
			<div class="producto" data-marca="newbalance">
				<img src="../../public/img/productos/new-balance-574.jpg" alt="New Balance 574">
				<h3>New Balance 574</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$5.990</p>
				<a href="paginaDetalleProducto.php?producto=new-balance-574">Ver detalles</a>
			</div>
			<div class="producto" data-marca="newbalance">
				<img src="../../public/img/productos/new-balance-530.jpg" alt="New Balance 530">
				<h3>New Balance 530</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$6.490</p>
				<a href="paginaDetalleProducto.php?producto=new-balance-530">Ver detalles</a>
			</div>
			<div class="producto" data-marca="newbalance">
				<img src="../../public/img/productos/new-balance-fresh-foam-x.jpg" alt="New Balance Fresh Foam X">
				<h3>New Balance Fresh Foam X</h3>
				<p>⭐⭐⭐⭐⭐</p>
				<p>$8.290</p>
				<a href="paginaDetalleProducto.php?producto=new-balance-fresh-foam-x">Ver detalles</a>
			</div>
		</section>
	</main>
	<br>
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
	<script src="../../public/js/paginaListadoProductos.js"></script>
 </body>
<?php require __DIR__ . '/footer.php'; ?>