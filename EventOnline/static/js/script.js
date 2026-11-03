let carrito = [];

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    const cartToggle = document.getElementById('cartToggle');
    const cartClose = document.getElementById('cartClose');
    const cartOverlay = document.getElementById('cartOverlay');
    const cartDrawer = document.getElementById('cartDrawer');

    if (cartToggle && cartDrawer) {
        cartToggle.addEventListener('click', () => {
            cartDrawer.classList.add('active');
            cartOverlay.classList.add('active');
        });
    }

    if (cartClose && cartOverlay) {
        [cartClose, cartOverlay].forEach(el => {
            el.addEventListener('click', () => {
                cartDrawer.classList.remove('active');
                cartOverlay.classList.remove('active');
            });
        });
    }
});

function agregarAlCarrito(nombre, precio, tipo = 'sitio') {
    const itemExistente = carrito.find(item => item.nombre === nombre);
    
    if (!itemExistente) {
        carrito.push({
            nombre,
            precio,
            tipo,
            fecha: '',
            hora: '18:00'
        });
    }
    actualizarCarrito();
    document.getElementById('cartDrawer').classList.add('active');
    document.getElementById('cartOverlay').classList.add('active');
}

// Validación en tiempo real para evitar elegir fechas ya reservadas
async function actualizarFechaSitio(nombre, fecha) {
    const item = carrito.find(i => i.nombre === nombre);
    if (item && fecha) {
        try {
            const resp = await fetch(`../php/verificar_disponibilidad.php?accion=verificar&nombre=${encodeURIComponent(nombre)}&fecha=${encodeURIComponent(fecha)}`);
            const data = await resp.json();

            if (!data.disponible) {
                alert(data.mensaje);
                item.fecha = '';
                actualizarCarrito();
                return;
            }
            item.fecha = fecha;
        } catch (error) {
            console.error('Error verificando fecha:', error);
            item.fecha = fecha;
        }
    }
}

function actualizarHoraSitio(nombre, hora) {
    const item = carrito.find(item => item.nombre === nombre);
    if (item) {
        item.hora = hora;
    }
}

function eliminarItem(nombre) {
    carrito = carrito.filter(i => i.nombre !== nombre);
    actualizarCarrito();
}

function actualizarCarrito() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartBadge = document.getElementById('cartBadge');
    const cartTotal = document.getElementById('cartTotal');

    let total = 0;
    let totalItems = carrito.length;

    if (carrito.length === 0) {
        cartItemsContainer.innerHTML = '<p class="empty-cart">Tu carrito está vacío</p>';
    } else {
        cartItemsContainer.innerHTML = '';
        carrito.forEach(item => {
            total += item.precio;

            const div = document.createElement('div');
            div.className = 'cart-item';

            div.innerHTML = `
                <div style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong>${item.nombre}</strong>
                        <button onclick="eliminarItem('${item.nombre}')" class="cart-delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                    <small>$${item.precio.toLocaleString()} tarifa base</small>
                    <div class="cart-booking-inputs">
                        <label>Fecha de reserva:</label>
                        <input type="date" value="${item.fecha}" onchange="actualizarFechaSitio('${item.nombre}', this.value)" class="cart-input-date" required>
                        <label>Hora programada:</label>
                        <input type="time" value="${item.hora}" onchange="actualizarHoraSitio('${item.nombre}', this.value)" class="cart-input-hours" required>
                    </div>
                </div>
            `;
            cartItemsContainer.appendChild(div);
        });
    }

    cartBadge.textContent = totalItems;
    cartTotal.textContent = '$' + total.toLocaleString();
}

async function procesarCompra() {
    if (carrito.length === 0) {
        alert('El carrito está vacío');
        return;
    }
    
    for (let item of carrito) {
        if (!item.fecha) {
            alert(`Por favor selecciona una fecha de reserva para: ${item.nombre}`);
            return;
        }
    }

    // Solicitar método de pago
    let metodoPago = prompt("Selecciona el método de pago:\n1. Nequi\n2. Daviplata\n3. Tarjeta de Crédito/Débito\n4. Efectivo al evento\n\n(Escribe el nombre o número de 1 a 4):");
    
    if (!metodoPago) return;

    if (metodoPago === '1') metodoPago = 'Nequi';
    else if (metodoPago === '2') metodoPago = 'Daviplata';
    else if (metodoPago === '3') metodoPago = 'Tarjeta';
    else if (metodoPago === '4') metodoPago = 'Efectivo';

    try {
        const response = await fetch('../php/verificar_disponibilidad.php?accion=guardar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items: carrito, metodo_pago: metodoPago })
        });
        const resultado = await response.json();

        if (resultado.exito) {
            alert(`¡Reserva y pago vía [${metodoPago}] confirmados con éxito!`);
            carrito = [];
            actualizarCarrito();
            document.getElementById('cartDrawer').classList.remove('active');
            document.getElementById('cartOverlay').classList.remove('active');
            window.location.href = 'mis_reservas.php';
        } else {
            alert(resultado.mensaje);
        }
    } catch (error) {
        alert('Ocurrió un error al procesar tu reserva.');
    }
}

const modal = document.getElementById('ventanaModal');
const modalBody = document.getElementById('modalBody');

function abrirModalSitio(titulo, imagen, descripcion) {
    if (modal && modalBody) {
        modalBody.innerHTML = `
            <img src="${imagen}" alt="${titulo}" class="modal-img">
            <h2 class="modal-title">${titulo}</h2>
            <p class="modal-text">${descripcion}</p>
        `;
        modal.style.display = 'flex';
    }
}

function abrirModalProducto(titulo, precio, descripcion) {
    if (modal && modalBody) {
        modalBody.innerHTML = `
            <h2 class="modal-title">${titulo}</h2>
            <p class="modal-price">Precio: ${precio}</p>
            <p class="modal-text"><strong>Detalles:</strong> ${descripcion}</p>
        `;
        modal.style.display = 'flex';
    }
}

function cerrarModal() {
    if (modal) modal.style.display = 'none';
}

window.onclick = function(event) {
    if (event.target === modal) cerrarModal();
};