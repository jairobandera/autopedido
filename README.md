# 🍔 Autopedido - Sistema Laravel

Sistema de autopedido para locales gastronómicos como restaurantes, cafeterías o fast food, pensado para pantallas táctiles. Permite a los clientes realizar sus pedidos de forma autónoma y al personal gestionar el menú, categorías y usuarios desde un panel administrativo.

## 🛠️ Tecnologías utilizadas

- Laravel 11
- MySQL
- PHP 8.x
- Bootstrap 5
- JavaScript
- Faker (para datos de prueba)

---

## 🚀 Funcionalidades actuales

- Gestión de **productos** con múltiples ingredientes y categorías
- Gestión de **categorías**
- Gestión de **usuarios** por rol (cliente, cocina, caja, admin)
- Seeders con datos de prueba para todas las entidades
- Relaciones muchos a muchos totalmente funcionales
- Controladores funcionales (`index`, `store`, `update`, `destroy`, etc.)
- Listo para integración con interfaz de autopedido

---

## ⚙️ Cómo clonar y ejecutar

```bash
# Clonar el repositorio
git clone https://github.com/jairobandera/autopedido.git
cd autopedido

# Copiar archivo de entorno
cp .env.example .env

# Instalar dependencias
composer install

# Generar clave de aplicación
php artisan key:generate

# Crear y poblar la base de datos
php artisan migrate --seed

# Levantar el servidor local
php artisan serve

📦 Base de datos
La base de datos se llena automáticamente con:

20 productos

10 categorías

15 ingredientes

10 promociones

10+ pedidos con detalles, pagos y puntos

Relaciones ya configuradas en tablas intermedias

👨‍💻 Equipo
Desarrollado por estudiantes del Tecnólogo Informático - Sede Paysandú
Alan Ceballos – Jairo Bandera


