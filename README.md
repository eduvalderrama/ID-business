## Creamos nuestro archivo .env
Creamos nuestro archivo.env y copiamos lo que tenemos dentro del archivo .env.example

## Para levantar el contenedor de docker tenemos que usar el comando 
./vendor/bin/sail up -d

## Para correr las migraciones corremos el comando
./vendor/bin/sail artisan migrate

## Para probar las apis del Auth
Podemos probarlas en PostMan

### Registro de usuario
POST http://localhost:8000/api/register
Content-Type: application/json

{
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "juan@example.com",
    "password": "123456",
    "role": "admin"
}

### Login 
POST http://localhost/api/login
Content-Type: application/json

{
    "email": "juan@example.com",
    "password": "123456"
}

### Crear un producto
POST /api/products
Content-Type: application/json
Authorization: Bearer {TOKEN}

{
    "sku": "P001",
    "nombre": "Producto de Prueba",
    "precio_unitario": 10.99,
    "stock": 50
}

### Listar todos los productos 
GET /api/products
Authorization: Bearer {TOKEN}

### Obtener un producto por id
GET /api/products/1
Authorization: Bearer {TOKEN}

### Actualizar un producto 
PUT /api/products/1
Content-Type: application/json
Authorization: Bearer {TOKEN}

{
    "precio_unitario": 12.99,
    "stock": 40
}

### Eliminar un producto, esto solo funciona para un admin
DELETE /api/products/1
Authorization: Bearer {TOKEN}
