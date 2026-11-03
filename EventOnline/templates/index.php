<?php
include '../php/conexion.php';
include '../includes/header.php';
?>

    <section id="hero">
        <div class="hero-content">
            <h1>Organiza tus eventos</h1>
            <p>Los mejores espacios y servicios para tus celebraciones</p>
            <a href="#sitios" class="cta-button">Ver Espacios y Servicios</a>
        </div>
    </section>

    <!-- SECCIÓN DE SITIOS / SALONES -->
    <section id="sitios" class="container-section">
        <h2 class="section-title">Sitios Disponibles</h2>
        <div class="sitios-grid">
            
            <!-- Sitios Predeterminados -->
            <div class="sitio-card">
                <img src="../static/img/hq720.jpg" alt="Salón Gran Fiesta">
                <div class="card-body">
                    <h3>Salón Gran Fiesta</h3>
                    <p>Espacio exclusivo ideal para recepciones y bodas.</p>
                    <p class="precio">$1.500.000</p>
                    <div class="card-actions">
                        <button type="button" class="btn-toggle-desc" onclick="abrirModalSitio('Salón Gran Fiesta', '../static/img/hq720.jpg', 'Espacio exclusivo y amplio ideal para recepciones, bodas y grandes eventos con capacidad de hasta 200 personas.')">Detalles</button>
                        <button class="btn-add-cart" onclick="agregarAlCarrito('Salón Gran Fiesta', 1500000, 'sitio')"><i class="fas fa-calendar-alt"></i> Reservar</button>
                    </div>
                </div>
            </div>

            <div class="sitio-card">
                <img src="../static/img/15-anos-11_optimized-1-1920x1300.jpg" alt="Salón Moderno 15s">
                <div class="card-body">
                    <h3>Salón Moderno 15s</h3>
                    <p>Elegante salón equipado con luces robóticas y sonido.</p>
                    <p class="precio">$800.000</p>
                    <div class="card-actions">
                        <button type="button" class="btn-toggle-desc" onclick="abrirModalSitio('Salón Moderno 15s', '../static/img/15-anos-11_optimized-1-1920x1300.jpg', 'Un salón elegante y moderno equipado con iluminación ambiental y sonido integrado para fiestas de 15 años.')">Detalles</button>
                        <button class="btn-add-cart" onclick="agregarAlCarrito('Salón Moderno 15s', 800000, 'sitio')"><i class="fas fa-calendar-alt"></i> Reservar</button>
                    </div>
                </div>
            </div>

            <div class="sitio-card">
                <img src="../static/img/7_optimized-1.jpg" alt="Jardín Campestre">
                <div class="card-body">
                    <h3>Jardín Campestre</h3>
                    <p>Jardín al aire libre rodeado de naturaleza.</p>
                    <p class="precio">$600.000</p>
                    <div class="card-actions">
                        <button type="button" class="btn-toggle-desc" onclick="abrirModalSitio('Jardín Campestre', '../static/img/7_optimized-1.jpg', 'Jardín campestre al aire libre, ideal para eventos diurnos rodeados de naturaleza.')">Detalles</button>
                        <button class="btn-add-cart" onclick="agregarAlCarrito('Jardín Campestre', 600000, 'sitio')"><i class="fas fa-calendar-alt"></i> Reservar</button>
                    </div>
                </div>
            </div>

            <!-- Sitios Dinámicos de la Base de Datos -->
            <?php
            $resultado_sitios = $conexion->query("SELECT * FROM sitios");
            if ($resultado_sitios && $resultado_sitios->num_rows > 0) {
                while ($sitio = $resultado_sitios->fetch_assoc()) {
                    $nombreEsc = htmlspecialchars($sitio['nombre'], ENT_QUOTES);
                    $descEsc = htmlspecialchars($sitio['descripcion'], ENT_QUOTES);
                    $imgEsc = htmlspecialchars($sitio['imagen'], ENT_QUOTES);
                    $precioVal = floatval($sitio['precio']);
                    $precioFormateado = '$' . number_format($precioVal, 0, ',', '.');

                    echo '<div class="sitio-card">';
                    echo '<img src="' . $imgEsc . '" alt="' . $nombreEsc . '">';
                    echo '<div class="card-body">';
                    echo '<h3>' . $nombreEsc . '</h3>';
                    echo '<p>' . $descEsc . '</p>';
                    echo '<p class="precio">' . $precioFormateado . '</p>';
                    echo '<div class="card-actions">';
                    echo '<button type="button" class="btn-toggle-desc" onclick="abrirModalSitio(\'' . $nombreEsc . '\', \'' . $imgEsc . '\', \'' . $descEsc . '\')">Detalles</button>';
                    echo '<button class="btn-add-cart" onclick="agregarAlCarrito(\'' . $nombreEsc . '\', ' . $precioVal . ', \'sitio\')"><i class="fas fa-calendar-alt"></i> Reservar</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>

        </div>
    </section>

    <!-- SECCIÓN DE SERVICIOS ESPECIALES -->
    <section id="servicios" class="container-section bg-alt">
        <h2 class="section-title">Servicios Especiales</h2>
        <div class="productos-grid">
            
            <!-- Servicio Predeterminado 1 -->
            <div class="productos-card">
                <span class="card-badge">Popular</span>
                <h3>Animador Profesional</h3>
                <p class="precio">$300.000</p>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Maestro de ceremonias</li>
                    <li><i class="fas fa-check-circle"></i> Dinámicas y entretenimiento</li>
                </ul>
                <div class="card-actions">
                    <button type="button" class="btn-toggle-desc" onclick="abrirModalProducto('Animador Profesional', '$300.000', 'Animador y maestro de ceremonias con experiencia para mantener la energía y diversión durante todo el evento.')">Detalles</button>
                    <button class="btn-add-cart" onclick="agregarAlCarrito('Animador Profesional', 300000, 'sitio')"><i class="fas fa-calendar-alt"></i> Reservar</button>
                </div>
            </div>

            <!-- Servicio Predeterminado 2 -->
            <div class="productos-card destacada">
                <span class="card-badge vip">Destacado</span>
                <h3>Serenata Romántica</h3>
                <p class="precio">$450.000</p>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Show musical en vivo</li>
                    <li><i class="fas fa-check-circle"></i> Repertorio personalizado</li>
                </ul>
                <div class="card-actions">
                    <button type="button" class="btn-toggle-desc" onclick="abrirModalProducto('Serenata Romántica', '$450.000', 'Presentación musical en vivo con mariachis o trío romántico para sorprender a tus invitados.')">Detalles</button>
                    <button class="btn-add-cart" onclick="agregarAlCarrito('Serenata Romántica', 450000, 'sitio')"><i class="fas fa-calendar-alt"></i> Reservar</button>
                </div>
            </div>

            <!-- Servicios Dinámicos de la Base de Datos -->
            <?php
            $resultado_servicios = $conexion->query("SELECT * FROM servicios");
            if ($resultado_servicios && $resultado_servicios->num_rows > 0) {
                while ($serv = $resultado_servicios->fetch_assoc()) {
                    $nombreEsc = htmlspecialchars($serv['nombre'], ENT_QUOTES);
                    $descEsc = htmlspecialchars($serv['descripcion'], ENT_QUOTES);
                    $imgEsc = htmlspecialchars($serv['imagen'], ENT_QUOTES);
                    $precioVal = floatval($serv['precio']);
                    $precioFormateado = '$' . number_format($precioVal, 0, ',', '.');

                    echo '<div class="productos-card">';
                    echo '<img src="' . $imgEsc . '" alt="' . $nombreEsc . '" class="modal-img" style="height: 140px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">';
                    echo '<h3>' . $nombreEsc . '</h3>';
                    echo '<p>' . $descEsc . '</p>';
                    echo '<p class="precio">' . $precioFormateado . '</p>';
                    echo '<div class="card-actions">';
                    echo '<button type="button" class="btn-toggle-desc" onclick="abrirModalProducto(\'' . $nombreEsc . '\', \'' . $precioFormateado . '\', \'' . $descEsc . '\')">Detalles</button>';
                    echo '<button class="btn-add-cart" onclick="agregarAlCarrito(\'' . $nombreEsc . '\', ' . $precioVal . ', \'sitio\')"><i class="fas fa-calendar-alt"></i> Reservar</button>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>

        </div>
    </section>

    <!-- CARRITO LATERAL -->
    <div class="cart-drawer-overlay" id="cartOverlay"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <h3><i class="fas fa-shopping-bag"></i> Mi Carrito de Reservas</h3>
            <button class="btn-close-cart" id="cartClose">&times;</button>
        </div>
        <div class="cart-body" id="cartItems">
            <p class="empty-cart">Tu carrito está vacío</p>
        </div>
        <div class="cart-footer">
            <div class="cart-total-row">
                <span>TOTAL:</span>
                <span id="cartTotal">$0</span>
            </div>
            <button class="btn-checkout" onclick="procesarCompra()">Continuar a la Compra</button>
        </div>
    </div>

    <!-- MODAL DE DETALLES -->
    <div id="ventanaModal" class="modal">
        <div class="modal-content">
            <span onclick="cerrarModal()" class="close-modal">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>