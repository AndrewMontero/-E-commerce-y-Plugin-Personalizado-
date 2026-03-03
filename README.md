Role Based Pricing Custom

Plugin personalizado para WooCommerce que permite asignar precios específicos por rol de usuario, manteniendo el precio normal visible para visitantes y restringiendo las compras únicamente a usuarios registrados.

📌 Descripción General

Role Based Pricing Custom extiende WooCommerce agregando la funcionalidad de precios personalizados por rol de usuario.

El sistema permite:

Asignar precios distintos según el rol del usuario.

Mantener el precio normal visible para visitantes.

Aplicar automáticamente el precio correspondiente en tienda, producto, carrito y checkout.

Bloquear la compra a usuarios no registrados.

Crear automáticamente el rol "Solidarista".

⚙️ Requisitos

WordPress 6.x o superior

WooCommerce activo

PHP 7.4 o superior

Base de datos MySQL

📂 Estructura del Plugin
role-based-pricing-custom/
│
├── role-based-pricing-custom.php
└── includes/
    ├── activator.php
    ├── admin.php
    └── pricing.php
🗄 Base de Datos

Al activarse, el plugin crea la tabla:

wp_role_prices

Estructura:
Campo	Tipo	Descripción
id	BIGINT UNSIGNED	ID único
product_id	BIGINT UNSIGNED	ID del producto
role	VARCHAR(50)	Nombre del rol
price	DECIMAL(10,2)	Precio personalizado
Índices:

UNIQUE (product_id, role)

INDEX product_id

INDEX role

Esto garantiza rendimiento y evita duplicados.

👤 Gestión de Roles

El plugin crea automáticamente el rol:

Solidarista

Este rol funciona igual que los demás roles de WordPress y puede recibir un precio personalizado.

🖥 Uso del Plugin
1️⃣ Asignar precios por rol

Ir a Productos → Editar producto

Abrir la pestaña Precios por Rol

Ingresar los valores deseados para cada rol

Guardar el producto

2️⃣ Comportamiento del sistema
Usuario NO registrado:

Ve el precio normal.

No puede añadir productos al carrito.

Si intenta comprar, es redirigido al login/registro.

Usuario registrado sin precio especial:

Ve el precio normal.

Puede comprar normalmente.

Usuario registrado con precio por rol:

Ve el precio asignado a su rol.

El precio se aplica automáticamente en:

Tienda

Página de producto

Carrito

Checkout

🔄 Flujo Técnico
Aplicación de precio

Se utilizan los filtros:

woocommerce_product_get_price

woocommerce_product_get_regular_price

woocommerce_product_get_sale_price

El sistema:

Verifica si el usuario está autenticado.

Obtiene los roles del usuario.

Consulta la tabla personalizada.

Si encuentra precio → lo aplica.

Si no → mantiene precio normal.

Aplicación en carrito

Hook utilizado:

woocommerce_before_calculate_totals

El precio se sobrescribe dinámicamente antes de calcular totales.

Restricción a visitantes

Filtros utilizados:

woocommerce_is_purchasable

template_redirect

Se bloquea la compra a usuarios no autenticados y se redirige al login.

📊 Comportamiento Final
Usuario	Precio visible	Puede comprar
Visitante	Precio normal	❌ No
Usuario sin precio especial	Precio normal	✅ Sí
Usuario con precio por rol	Precio personalizado	✅ Sí
🔐 Seguridad

Uso de consultas preparadas ($wpdb->prepare)

Sanitización de datos

Protección contra ejecución directa

Separación modular de responsabilidades

🚀 Posibles Mejoras Futuras

Precio por porcentaje automático

Prioridad entre roles

Precio por categoría

Precio por usuario específico

Panel global de configuración

Compatibilidad avanzada con productos variables

Sistema de caché
